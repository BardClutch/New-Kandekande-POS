<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date)->startOfDay() : Carbon::today()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : Carbon::today()->endOfDay();

        $transaksis = Transaksi::with(['user', 'detailTransaksis.produk.reseps.bahanBaku'])
            ->whereBetween('tanggal_waktu', [$startDate, $endDate])
            ->orderBy('tanggal_waktu', 'desc')
            ->get();

        $totalPendapatan = $transaksis->sum('total_belanja');
        $totalTransaksi = $transaksis->count();

        // Hitung Total HPP Terjual dan Breakdown per Produk
        $totalHpp = 0;
        $laporanProduk = [];

        foreach ($transaksis as $trx) {
            foreach ($trx->detailTransaksis as $detail) {
                $prod = $detail->produk;
                $qty = $detail->qty_beli;
                $subtotal = $detail->subtotal;
                $hppSatuan = $prod ? $prod->hitungHpp() : 0;
                $hppTotal = $hppSatuan * $qty;
                $labaItem = $subtotal - $hppTotal;

                $totalHpp += $hppTotal;

                $prodId = $detail->id_produk;
                if (!isset($laporanProduk[$prodId])) {
                    $laporanProduk[$prodId] = [
                        'nama_produk' => $prod ? $prod->nama_produk : 'Produk #' . $prodId,
                        'tipe_produksi' => $prod ? $prod->tipe_produksi : 'Batch',
                        'harga_jual_rata' => $detail->harga_satuan,
                        'hpp_satuan' => $hppSatuan,
                        'qty_terjual' => 0,
                        'total_omset' => 0,
                        'total_hpp' => 0,
                        'total_laba' => 0,
                    ];
                }

                $laporanProduk[$prodId]['qty_terjual'] += $qty;
                $laporanProduk[$prodId]['total_omset'] += $subtotal;
                $laporanProduk[$prodId]['total_hpp'] += $hppTotal;
                $laporanProduk[$prodId]['total_laba'] += $labaItem;
            }
        }

        $labaKotor = $totalPendapatan - $totalHpp;
        $marginPersen = $totalPendapatan > 0 ? round(($labaKotor / $totalPendapatan) * 100, 1) : 0;

        // Urutkan laporan produk dari omset tertinggi
        usort($laporanProduk, fn($a, $b) => $b['total_omset'] <=> $a['total_omset']);

        return view('laporan.index', compact(
            'transaksis', 
            'totalPendapatan', 
            'totalTransaksi', 
            'totalHpp', 
            'labaKotor', 
            'marginPersen', 
            'laporanProduk', 
            'startDate', 
            'endDate'
        ));
    }

    public function exportCsv(Request $request)
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date)->startOfDay() : Carbon::today()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : Carbon::today()->endOfDay();

        $transaksis = Transaksi::with(['user', 'detailTransaksis.produk.reseps.bahanBaku'])
            ->whereBetween('tanggal_waktu', [$startDate, $endDate])
            ->orderBy('tanggal_waktu', 'desc')
            ->get();

        $filename = "laporan_laba_rugi_" . date('Y-m-d_H-i-s') . ".csv";

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('ID Transaksi', 'Tanggal Waktu', 'Kasir', 'Jenis Pesanan', 'Metode Pembayaran', 'Total Belanja (Omset)', 'Estimasi HPP Bahan', 'Laba Kotor');

        $callback = function() use($transaksis, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($transaksis as $trx) {
                $trxHpp = 0;
                foreach ($trx->detailTransaksis as $detail) {
                    $prod = $detail->produk;
                    $trxHpp += ($prod ? $prod->hitungHpp() : 0) * $detail->qty_beli;
                }
                $trxLaba = $trx->total_belanja - $trxHpp;

                $row = array(
                    $trx->id_transaksi,
                    $trx->tanggal_waktu->format('Y-m-d H:i:s'),
                    $trx->user->name ?? 'Unknown',
                    $trx->jenis_pesanan,
                    $trx->metode_pembayaran,
                    $trx->total_belanja,
                    $trxHpp,
                    $trxLaba
                );
                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
