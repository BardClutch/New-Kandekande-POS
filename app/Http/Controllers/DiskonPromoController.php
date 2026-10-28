<?php

namespace App\Http\Controllers;

use App\Models\DiskonPromo;
use App\Models\Produk;
use Illuminate\Http\Request;

class DiskonPromoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $promos = DiskonPromo::with('produk')->orderBy('tgl_mulai', 'desc')->get();
        $produks = Produk::orderBy('nama_produk')->get();
        return view('diskon.index', compact('promos', 'produks'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_produk' => 'required|exists:produks,id_produk',
            'nama_promo' => 'required|string|max:50',
            'persentase' => 'required|numeric|min:0|max:100',
            'tgl_mulai' => 'required|date',
            'tgl_akhir' => 'required|date|after_or_equal:tgl_mulai',
        ]);

        DiskonPromo::create($validated);

        return redirect()->route('diskon.index')->with('success', 'Promo berhasil ditambahkan.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DiskonPromo $diskon)
    {
        $validated = $request->validate([
            'id_produk' => 'required|exists:produks,id_produk',
            'nama_promo' => 'required|string|max:50',
            'persentase' => 'required|numeric|min:0|max:100',
            'tgl_mulai' => 'required|date',
            'tgl_akhir' => 'required|date|after_or_equal:tgl_mulai',
        ]);

        $diskon->update($validated);

        return redirect()->route('diskon.index')->with('success', 'Promo berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DiskonPromo $diskon)
    {
        $diskon->delete();
        return redirect()->route('diskon.index')->with('success', 'Promo berhasil dihapus.');
    }
}
