<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class KasirController extends Controller
{
    public function index()
    {
        // Tampilkan produk yang stok etalasenya lebih dari 0, beserta diskon promo yang sedang aktif
        $today = now()->format('Y-m-d');
        $produks = Produk::where('stok_etalase', '>', 0)
            ->with(['kategori', 'diskonPromos' => function($q) use ($today) {
                $q->where('tgl_mulai', '<=', $today)->where('tgl_akhir', '>=', $today);
            }])
            ->get();
            
        $pelanggans = Pelanggan::orderBy('nama_pelanggan')->get(['id_pelanggan', 'nama_pelanggan', 'no_hp', 'tipe_pelanggan']);
        $activeShift = Auth::user() ? Auth::user()->activeShift() : null;

        return view('kasir.index', compact('produks', 'pelanggans', 'activeShift'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_pesanan' => 'required|in:Offline,Whatsapp',
            'metode_pembayaran' => 'required|string|max:20',
            'id_produk' => 'required|array',
            'id_produk.*' => 'exists:produks,id_produk',
            'qty_beli' => 'required|array',
            'qty_beli.*' => 'integer|min:1',
            'nama_pelanggan' => 'nullable|string|max:100',
            'no_hp_pelanggan' => 'nullable|string|max:20',
            'catatan_pesanan' => 'nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            // Identifikasi atau daftarkan Pelanggan jika diisi
            $pelanggan = null;
            $namaPelanggan = trim((string)$request->input('nama_pelanggan', ''));
            $noHpPelanggan = trim((string)$request->input('no_hp_pelanggan', ''));
            $catatanPesanan = trim((string)$request->input('catatan_pesanan', ''));

            if (!empty($namaPelanggan)) {
                if (!empty($noHpPelanggan)) {
                    $pelanggan = Pelanggan::where('no_hp', $noHpPelanggan)->first();
                }
                if (!$pelanggan) {
                    $pelanggan = Pelanggan::where('nama_pelanggan', $namaPelanggan)->first();
                }
                if (!$pelanggan) {
                    $pelanggan = Pelanggan::create([
                        'nama_pelanggan' => $namaPelanggan,
                        'no_hp' => $noHpPelanggan ?: null,
                        'tipe_pelanggan' => 'Baru',
                    ]);
                } else {
                    if (empty($pelanggan->no_hp) && !empty($noHpPelanggan)) {
                        $pelanggan->update(['no_hp' => $noHpPelanggan]);
                    }
                }
            }

            $activeShift = Auth::user() ? Auth::user()->activeShift() : null;

            // Buat Transaksi Utama dengan total 0 sementara
            $transaksi = Transaksi::create([
                'id_user' => Auth::id() ?? 1,
                'id_shift' => $activeShift ? $activeShift->id_shift : null,
                'id_pelanggan' => $pelanggan ? $pelanggan->id_pelanggan : null,
                'nama_pelanggan' => $pelanggan ? $pelanggan->nama_pelanggan : ($namaPelanggan ?: null),
                'no_hp_pelanggan' => $pelanggan ? $pelanggan->no_hp : ($noHpPelanggan ?: null),
                'catatan_pesanan' => $catatanPesanan ?: null,
                'status_pesanan' => 'Menunggu Dapur',
                'jenis_pesanan' => $request->jenis_pesanan,
                'metode_pembayaran' => $request->metode_pembayaran,
                'total_belanja' => 0,
                'tanggal_waktu' => now(),
            ]);

            $produks_id = $request->id_produk;
            $qtys = $request->qty_beli;
            
            $total_belanja_calculated = 0;
            $today = now()->format('Y-m-d');

            for ($i = 0; $i < count($produks_id); $i++) {
                $produk = Produk::with(['diskonPromos' => function($q) use ($today) {
                    $q->where('tgl_mulai', '<=', $today)->where('tgl_akhir', '>=', $today);
                }])->find($produks_id[$i]);

                if (!$produk) throw new \Exception("Produk tidak ditemukan.");
                
                // Cek stok
                if ($produk->stok_etalase < $qtys[$i]) {
                    throw new \Exception("Stok produk {$produk->nama_produk} tidak mencukupi!");
                }
                
                $harga_satuan = $produk->harga_dasar;
                $activePromo = $produk->diskonPromos->first();
                $potongan = 0;
                
                if ($activePromo) {
                    $potongan = $harga_satuan * ($activePromo->persentase / 100);
                }
                
                $subtotal = ($harga_satuan - $potongan) * $qtys[$i];
                $total_belanja_calculated += $subtotal;
                
                DetailTransaksi::create([
                    'id_transaksi' => $transaksi->id_transaksi,
                    'id_produk' => $produks_id[$i],
                    'qty_beli' => $qtys[$i],
                    'harga_satuan' => $harga_satuan,
                    'potongan_diskon' => $potongan,
                    'subtotal' => $subtotal,
                ]);

                // Kurangi stok etalase
                $produk->stok_etalase -= $qtys[$i];
                $produk->save();
            }

            // Update total belanja yang sebenarnya
            $transaksi->update(['total_belanja' => $total_belanja_calculated]);

            // Perbarui akumulasi daya beli pelanggan
            if ($pelanggan) {
                $pelanggan->hitungUlangDayaBeli();
            }

            // Perbarui kas shift kasir jika ada shift aktif
            if ($activeShift) {
                $activeShift->hitungUlangKas();
            }

            DB::commit();

            return redirect()->route('kasir.index')
                ->with('success', 'Transaksi berhasil disimpan!')
                ->with('cetak_id', $transaksi->id_transaksi);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function cetak($id_transaksi)
    {
        $transaksi = Transaksi::with(['details.produk', 'user'])->findOrFail($id_transaksi);
        return view('kasir.cetak', compact('transaksi'));
    }
}
