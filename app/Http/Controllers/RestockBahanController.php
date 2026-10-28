<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BahanBaku;
use App\Models\RestockBahan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RestockBahanController extends Controller
{
    public function index()
    {
        // Mengambil data restock terbaru beserta relasi bahan baku dan user yang mencatat
        $restocks = RestockBahan::with(['bahanBaku', 'user'])->orderBy('tanggal_waktu', 'desc')->get();
        // Mengambil daftar bahan baku untuk pilihan di modal
        $bahanBakus = BahanBaku::orderBy('nama_bahan')->get();

        return view('restock.index', compact('restocks', 'bahanBakus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_bahan' => 'required|exists:bahan_bakus,id_bahan',
            'jumlah_tambah' => 'required|numeric|min:0.1',
            'harga_beli' => 'nullable|numeric|min:0',
            'supplier' => 'nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            // 1. Simpan record restock
            $restock = RestockBahan::create([
                'id_bahan' => $request->id_bahan,
                'id_user' => Auth::id(),
                'jumlah_tambah' => $request->jumlah_tambah,
                'harga_beli' => $request->harga_beli,
                'supplier' => $request->supplier,
                'tanggal_waktu' => now(),
            ]);

            // 2. Tambah stok bahan baku dan update harga beli satuan
            $bahanBaku = BahanBaku::findOrFail($request->id_bahan);
            $bahanBaku->jumlah_stok += $request->jumlah_tambah;
            if ($request->filled('harga_beli') && $request->jumlah_tambah > 0) {
                $bahanBaku->harga_beli_satuan = round($request->harga_beli / $request->jumlah_tambah, 2);
            }
            $bahanBaku->save();

            DB::commit();

            return redirect()->route('restock.index')->with('success', 'Riwayat restock berhasil ditambahkan dan stok bahan baku telah diupdate.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('restock.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
