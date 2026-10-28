<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\ProduksiDapur;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DapurController extends Controller
{
    public function index()
    {
        $produks = Produk::with(['reseps.bahanBaku'])->get();
        $produksis = ProduksiDapur::with(['produk', 'user'])
            ->orderBy('tanggal_produksi', 'desc')
            ->orderBy('id_produksi', 'desc')
            ->get();
            
        // Pesanan kasir yang perlu disiapkan oleh dapur
        $pesananMasuk = Transaksi::with(['detailTransaksis.produk', 'user', 'pelanggan'])
            ->whereIn('status_pesanan', ['Menunggu Dapur', 'Sedang Disiapkan'])
            ->orderBy('id_transaksi', 'desc')
            ->get();

        $latestTransaksiId = Transaksi::max('id_transaksi') ?? 0;

        return view('dapur.index', compact('produksis', 'produks', 'pesananMasuk', 'latestTransaksiId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_produk' => 'required|exists:produks,id_produk',
            'jumlah_batch' => 'nullable|integer|min:1',
            'jumlah_matang' => 'nullable|integer|min:1',
            'tanggal_produksi' => 'nullable|date',
            'catatan_produksi' => 'nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();
            $produk = Produk::with('reseps.bahanBaku')->findOrFail($request->id_produk);

            if ($produk->isKustom()) {
                $jumlahMatang = max(1, (int)$request->input('jumlah_matang', 1));
                $jumlahBatch = null;
                $multiplier = $jumlahMatang;
            } else {
                $jumlahBatch = max(1, (int)$request->input('jumlah_batch', 1));
                $yieldPerBatch = max(1, (int)$produk->jumlah_per_batch);
                $jumlahMatang = $jumlahBatch * $yieldPerBatch;
                $multiplier = $jumlahBatch;
            }

            if ($produk->reseps->isEmpty()) {
                throw new \Exception("Produk '{$produk->nama_produk}' belum memiliki komposisi resep bahan baku! Silakan atur resep terlebih dahulu di menu Buku Resep.");
            }

            // Cek ketersediaan semua bahan baku
            foreach ($produk->reseps as $resep) {
                $kebutuhan = $resep->takaran * $multiplier;
                if ($resep->bahanBaku->jumlah_stok < $kebutuhan) {
                    throw new \Exception("Stok {$resep->bahanBaku->nama_bahan} tidak cukup! Butuh: {$kebutuhan} {$resep->bahanBaku->satuan}, Sisa di gudang: {$resep->bahanBaku->jumlah_stok} {$resep->bahanBaku->satuan}");
                }
            }

            // Kurangi stok bahan baku
            foreach ($produk->reseps as $resep) {
                $bahan = $resep->bahanBaku;
                $bahan->jumlah_stok -= ($resep->takaran * $multiplier);
                $bahan->save();
            }

            ProduksiDapur::create([
                'id_user' => Auth::id() ?? 1,
                'id_produk' => $request->id_produk,
                'jumlah_batch' => $jumlahBatch,
                'jumlah_matang' => $jumlahMatang,
                'catatan_produksi' => $produk->isKustom() ? $request->catatan_produksi : null,
                'tanggal_produksi' => $request->tanggal_produksi ?? now()->toDateString(),
                'stok_sisa_batch' => $jumlahMatang,
                'status_produksi' => 'Persiapan',
            ]);

            DB::commit();

            $successMsg = $produk->isKustom()
                ? "Pesanan kustom ({$jumlahMatang} unit) berhasil dicatat dan stok bahan baku telah dikurangi!"
                : "Produksi ({$jumlahBatch} batch = {$jumlahMatang} pcs) berhasil dicatat dan stok bahan baku telah dikurangi!";

            return redirect()->route('dapur.index')->with('success', $successMsg);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('dapur.index')->with('error', 'Gagal memproduksi: ' . $e->getMessage());
        }
    }

    public function updateStatus(Request $request, ProduksiDapur $dapur)
    {
        $request->validate([
            'status_produksi' => 'required|in:Persiapan,Dipanggang,Selesai'
        ]);

        try {
            DB::beginTransaction();

            $statusLama = $dapur->status_produksi;
            $statusBaru = $request->status_produksi;

            // Jika status berubah menjadi selesai, tambah stok produk (hanya sekali)
            if ($statusLama !== 'Selesai' && $statusBaru === 'Selesai') {
                $produk = Produk::find($dapur->id_produk);
                $produk->stok_etalase += $dapur->jumlah_matang;
                $produk->save();
            }

            // Jika status di-revert dari selesai ke status lain, kurangi stok
            if ($statusLama === 'Selesai' && $statusBaru !== 'Selesai') {
                $produk = Produk::find($dapur->id_produk);
                if ($produk->stok_etalase < $dapur->jumlah_matang) {
                    throw new \Exception("Stok tidak dapat dibatalkan, produk telah terjual!");
                }
                $produk->stok_etalase -= $dapur->jumlah_matang;
                $produk->save();
            }

            $dapur->status_produksi = $statusBaru;
            $dapur->save();

            DB::commit();
            return redirect()->route('dapur.index')->with('success', 'Status produksi berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('dapur.index')->with('error', $e->getMessage());
        }
    }

    public function destroy(ProduksiDapur $dapur)
    {
        try {
            DB::beginTransaction();

            $produk = Produk::with('reseps.bahanBaku')->findOrFail($dapur->id_produk);

            // Jika status Selesai, kita harus kembalikan stok etalase
            if ($dapur->status_produksi === 'Selesai') {
                if ($produk->stok_etalase < $dapur->jumlah_matang) {
                    throw new \Exception("Tidak dapat membatalkan produksi karena sebagian produk jadi telah terjual!");
                }
                $produk->stok_etalase -= $dapur->jumlah_matang;
                $produk->save();
            }

            // Kembalikan stok bahan baku
            $multiplier = ($dapur->jumlah_batch && $produk->isBatch()) 
                ? $dapur->jumlah_batch 
                : $dapur->jumlah_matang;

            foreach ($produk->reseps as $resep) {
                $bahan = $resep->bahanBaku;
                $bahan->jumlah_stok += ($resep->takaran * $multiplier);
                $bahan->save();
            }

            // Hapus record produksi
            $dapur->delete();

            DB::commit();
            return redirect()->route('dapur.index')->with('success', 'Produksi berhasil dibatalkan dan stok bahan baku telah dikembalikan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('dapur.index')->with('error', 'Gagal membatalkan produksi: ' . $e->getMessage());
        }
    }

    /**
     * Endpoint API Polling untuk cek notifikasi pesanan baru secara real-time
     */
    public function cekPesananBaru(Request $request)
    {
        $lastId = (int)$request->input('last_id', 0);
        
        $newOrders = Transaksi::with(['detailTransaksis.produk', 'pelanggan'])
            ->where('id_transaksi', '>', $lastId)
            ->orderBy('id_transaksi', 'asc')
            ->get();

        $activeOrders = Transaksi::with(['detailTransaksis.produk', 'pelanggan'])
            ->whereIn('status_pesanan', ['Menunggu Dapur', 'Sedang Disiapkan'])
            ->orderBy('id_transaksi', 'desc')
            ->get();

        $latestId = Transaksi::max('id_transaksi') ?? 0;

        return response()->json([
            'latest_id' => $latestId,
            'has_new' => $newOrders->count() > 0,
            'new_orders' => $newOrders,
            'active_orders' => $activeOrders,
        ]);
    }

    /**
     * Update status pesanan dapur (Menunggu Dapur -> Sedang Disiapkan -> Siap Disajikan)
     */
    public function updateStatusPesanan(Request $request, Transaksi $transaksi)
    {
        $request->validate([
            'status_pesanan' => 'required|in:Menunggu Dapur,Sedang Disiapkan,Siap Disajikan,Selesai'
        ]);

        $transaksi->update([
            'status_pesanan' => $request->status_pesanan
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true, 
                'id_transaksi' => $transaksi->id_transaksi,
                'status_pesanan' => $transaksi->status_pesanan
            ]);
        }

        return redirect()->route('dapur.index')->with('success', "Status Pesanan #{$transaksi->id_transaksi} diperbarui menjadi '{$transaksi->status_pesanan}'");
    }
}
