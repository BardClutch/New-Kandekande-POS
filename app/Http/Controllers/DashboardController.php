<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\BahanBaku;
use App\Models\Produk;
use App\Models\ProduksiDapur;
use App\Models\RestockBahan;
use App\Models\Pelanggan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();

        // 1. DASHBOARD KHUSUS OWNER (Super Admin)
        if ($user->isOwner()) {
            $pendapatanHariIni = Transaksi::whereDate('tanggal_waktu', $today)->sum('total_belanja');
            $pendapatanBulanIni = Transaksi::where('tanggal_waktu', '>=', $startOfMonth)->sum('total_belanja');
            $pesananHariIni = Transaksi::whereDate('tanggal_waktu', $today)->count();

            $produkTerlaris = DetailTransaksi::select('id_produk', DB::raw('SUM(qty_beli) as total_terjual'))
                ->with('produk.kategori')
                ->groupBy('id_produk')
                ->orderByDesc('total_terjual')
                ->limit(5)
                ->get();

            $grafikTanggal = [];
            $grafikPendapatan = [];
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::today()->subDays($i);
                $grafikTanggal[] = $date->format('d M');
                $grafikPendapatan[] = Transaksi::whereDate('tanggal_waktu', $date)->sum('total_belanja');
            }

            $bahanMenipis = BahanBaku::whereColumn('jumlah_stok', '<=', 'stok_minimum')->get();
            $produkMenipis = Produk::whereColumn('stok_etalase', '<=', 'stok_minimum')->get();
            $pesananDapurAktif = Transaksi::whereIn('status_pesanan', ['Menunggu Dapur', 'Sedang Disiapkan'])->count();
            $totalPelanggan = Pelanggan::count();

            return view('dashboard', [
                'roleView' => 'owner',
                'pendapatanHariIni' => $pendapatanHariIni,
                'pendapatanBulanIni' => $pendapatanBulanIni,
                'pesananHariIni' => $pesananHariIni,
                'produkTerlaris' => $produkTerlaris,
                'grafikTanggal' => $grafikTanggal,
                'grafikPendapatan' => $grafikPendapatan,
                'bahanMenipis' => $bahanMenipis,
                'produkMenipis' => $produkMenipis,
                'pesananDapurAktif' => $pesananDapurAktif,
                'totalPelanggan' => $totalPelanggan,
            ]);
        }

        // 2. DASHBOARD KHUSUS KASIR (POS & Penjualan)
        if ($user->isKasir()) {
            $transaksiKasirHariIni = Transaksi::whereDate('tanggal_waktu', $today)
                ->where('id_user', $user->id)
                ->count();

            $totalItemTerjual = DetailTransaksi::whereHas('transaksi', function($q) use ($today, $user) {
                $q->whereDate('tanggal_waktu', $today)->where('id_user', $user->id);
            })->sum('qty_beli');

            $rataRataBelanja = Transaksi::whereDate('tanggal_waktu', $today)
                ->where('id_user', $user->id)
                ->avg('total_belanja') ?? 0;

            $pesananSiapDisajikan = Transaksi::whereDate('tanggal_waktu', $today)
                ->where('status_pesanan', 'Siap Disajikan')
                ->count();

            $transaksiTerakhir = Transaksi::with(['details.produk', 'pelanggan'])
                ->where('id_user', $user->id)
                ->latest('tanggal_waktu')
                ->take(5)
                ->get();

            $produkMenipis = Produk::whereColumn('stok_etalase', '<=', 'stok_minimum')->get();
            $totalProdukReady = Produk::where('stok_etalase', '>', 0)->count();

            return view('dashboard', [
                'roleView' => 'kasir',
                'transaksiKasirHariIni' => $transaksiKasirHariIni,
                'totalItemTerjual' => $totalItemTerjual,
                'rataRataBelanja' => $rataRataBelanja,
                'pesananSiapDisajikan' => $pesananSiapDisajikan,
                'transaksiTerakhir' => $transaksiTerakhir,
                'produkMenipis' => $produkMenipis,
                'totalProdukReady' => $totalProdukReady,
            ]);
        }

        // 3. DASHBOARD KHUSUS DAPUR (Kitchen Display & Antrean Produksi)
        if ($user->isDapur()) {
            $antreanMenunggu = Transaksi::whereDate('tanggal_waktu', $today)
                ->where('status_pesanan', 'Menunggu Dapur')
                ->count();

            $sedangDimasak = Transaksi::whereDate('tanggal_waktu', $today)
                ->where('status_pesanan', 'Sedang Disiapkan')
                ->count();

            $siapSelesaiHariIni = Transaksi::whereDate('tanggal_waktu', $today)
                ->whereIn('status_pesanan', ['Siap Disajikan', 'Selesai'])
                ->count();

            $totalBatchProduksiHariIni = ProduksiDapur::whereDate('tanggal_produksi', $today)->count();

            $pesananAktif = Transaksi::with(['details.produk'])
                ->whereIn('status_pesanan', ['Menunggu Dapur', 'Sedang Disiapkan'])
                ->latest('tanggal_waktu')
                ->take(6)
                ->get();

            $produkMenipis = Produk::whereColumn('stok_etalase', '<=', 'stok_minimum')->get();

            return view('dashboard', [
                'roleView' => 'dapur',
                'antreanMenunggu' => $antreanMenunggu,
                'sedangDimasak' => $sedangDimasak,
                'siapSelesaiHariIni' => $siapSelesaiHariIni,
                'totalBatchProduksiHariIni' => $totalBatchProduksiHariIni,
                'pesananAktif' => $pesananAktif,
                'produkMenipis' => $produkMenipis,
            ]);
        }

        // 4. DASHBOARD KHUSUS PERAN KUSTOM (Granular Permissions)
        if ($user->isCustom()) {
            $data = [
                'roleView' => 'kustom',
            ];

            if ($user->hasPermission('laporan')) {
                $data['pendapatanHariIni'] = Transaksi::whereDate('tanggal_waktu', $today)->sum('total_belanja');
                $data['pesananHariIni'] = Transaksi::whereDate('tanggal_waktu', $today)->count();
                $data['pendapatanBulanIni'] = Transaksi::where('tanggal_waktu', '>=', $startOfMonth)->sum('total_belanja');
            }

            if ($user->hasPermission('kasir')) {
                $data['transaksiKasirHariIni'] = Transaksi::whereDate('tanggal_waktu', $today)
                    ->where('id_user', $user->id)
                    ->count();
                $data['pesananSiapDisajikan'] = Transaksi::whereDate('tanggal_waktu', $today)
                    ->where('status_pesanan', 'Siap Disajikan')
                    ->count();
                $data['transaksiTerakhir'] = Transaksi::with(['details.produk', 'pelanggan'])
                    ->where('id_user', $user->id)
                    ->latest('tanggal_waktu')
                    ->take(5)
                    ->get();
            }

            if ($user->hasPermission('dapur')) {
                $data['antreanMenunggu'] = Transaksi::whereDate('tanggal_waktu', $today)
                    ->where('status_pesanan', 'Menunggu Dapur')
                    ->count();
                $data['sedangDimasak'] = Transaksi::whereDate('tanggal_waktu', $today)
                    ->where('status_pesanan', 'Sedang Disiapkan')
                    ->count();
                $data['pesananAktifDapur'] = Transaksi::with(['details.produk'])
                    ->whereIn('status_pesanan', ['Menunggu Dapur', 'Sedang Disiapkan'])
                    ->latest('tanggal_waktu')
                    ->take(5)
                    ->get();
            }

            if ($user->hasPermission('bahanbaku')) {
                $data['totalBahan'] = BahanBaku::count();
                $data['bahanMenipis'] = BahanBaku::whereColumn('jumlah_stok', '<=', 'stok_minimum')->get();
                $data['riwayatRestockTerbaru'] = RestockBahan::with(['bahanBaku', 'user'])
                    ->latest('tanggal_waktu')
                    ->take(5)
                    ->get();
            }

            if ($user->hasPermission('produk')) {
                $data['produkMenipis'] = Produk::whereColumn('stok_etalase', '<=', 'stok_minimum')->get();
                $data['totalProdukReady'] = Produk::where('stok_etalase', '>', 0)->count();
            }

            if ($user->hasPermission('pelanggan')) {
                $data['totalPelanggan'] = Pelanggan::count();
            }

            return view('dashboard', $data);
        }

        // 5. DASHBOARD KHUSUS ADMIN GUDANG (Logistik, Bahan Baku & Restock)
        $totalBahan = BahanBaku::count();
        $bahanMenipis = BahanBaku::whereColumn('jumlah_stok', '<=', 'stok_minimum')->get();
        $totalRestockBulanIni = RestockBahan::where('tanggal_waktu', '>=', $startOfMonth)->count();
        $totalRestockHariIni = RestockBahan::whereDate('tanggal_waktu', $today)->count();

        $riwayatRestockTerbaru = RestockBahan::with(['bahanBaku', 'user'])
            ->latest('tanggal_waktu')
            ->take(5)
            ->get();

        return view('dashboard', [
            'roleView' => 'gudang',
            'totalBahan' => $totalBahan,
            'bahanMenipis' => $bahanMenipis,
            'totalRestockBulanIni' => $totalRestockBulanIni,
            'totalRestockHariIni' => $totalRestockHariIni,
            'riwayatRestockTerbaru' => $riwayatRestockTerbaru,
        ]);
    }
}
