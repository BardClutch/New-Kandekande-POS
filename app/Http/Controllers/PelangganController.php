<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\DetailTransaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PelangganController extends Controller
{
    public function index(Request $request)
    {
        $query = Pelanggan::with(['transaksis.detailTransaksis.produk']);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_pelanggan', 'like', "%{$search}%")
                  ->orWhere('no_hp', 'like', "%{$search}%");
            });
        }

        // Tier filter
        if ($request->filled('tier') && in_array($request->tier, ['VIP', 'Reguler', 'Baru'])) {
            $query->where('tipe_pelanggan', $request->tier);
        }

        $pelanggans = $query->orderBy('total_belanja', 'desc')->get();

        // Rekapitulasi Metrik Daya Pelanggan
        $totalPelanggan = Pelanggan::count();
        $totalVip = Pelanggan::where('tipe_pelanggan', 'VIP')->count();
        $totalReguler = Pelanggan::where('tipe_pelanggan', 'Reguler')->count();
        $totalBaru = Pelanggan::where('tipe_pelanggan', 'Baru')->count();
        
        $totalAkumulasiBelanja = Pelanggan::sum('total_belanja');
        $totalTransaksiSemua = Pelanggan::sum('total_transaksi');
        $rataRataDayaBeli = $totalTransaksiSemua > 0 ? ($totalAkumulasiBelanja / $totalTransaksiSemua) : 0;

        return view('pelanggan.index', compact(
            'pelanggans',
            'totalPelanggan',
            'totalVip',
            'totalReguler',
            'totalBaru',
            'totalAkumulasiBelanja',
            'rataRataDayaBeli'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pelanggan' => 'required|string|max:100',
            'no_hp' => 'nullable|string|max:20',
            'catatan' => 'nullable|string|max:255',
        ]);

        Pelanggan::create([
            'nama_pelanggan' => $request->nama_pelanggan,
            'no_hp' => $request->no_hp,
            'catatan' => $request->catatan,
            'tipe_pelanggan' => 'Baru',
        ]);

        return redirect()->route('pelanggan.index')->with('success', 'Data pelanggan berhasil ditambahkan!');
    }

    public function destroy(Pelanggan $pelanggan)
    {
        $pelanggan->delete();
        return redirect()->route('pelanggan.index')->with('success', 'Data pelanggan berhasil dihapus.');
    }
}
