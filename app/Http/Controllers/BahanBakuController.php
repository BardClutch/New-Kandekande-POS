<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use Illuminate\Http\Request;

class BahanBakuController extends Controller
{
    public function index()
    {
        $bahanbaku = BahanBaku::all();
        return view('bahanbaku.index', compact('bahanbaku'));
    }

    public function create()
    {
        return view('bahanbaku.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_bahan' => 'required|string|max:100',
            'jumlah_stok' => 'required|numeric|min:0',
            'stok_minimum' => 'nullable|numeric|min:0',
            'satuan' => 'required|string|max:20',
            'harga_beli_satuan' => 'nullable|numeric|min:0',
        ]);
        
        $data = $request->all();
        $data['stok_minimum'] = $request->input('stok_minimum', 5);
        $data['harga_beli_satuan'] = $request->input('harga_beli_satuan', 0);

        BahanBaku::create($data);
        
        return redirect()->route('bahanbaku.index')->with('success', 'Bahan Baku berhasil dicatat ke gudang!');
    }

    public function edit(BahanBaku $bahanbaku)
    {
        return view('bahanbaku.edit', compact('bahanbaku'));
    }

    public function update(Request $request, BahanBaku $bahanbaku)
    {
        $request->validate([
            'nama_bahan' => 'required|string|max:100',
            'jumlah_stok' => 'required|numeric|min:0',
            'stok_minimum' => 'nullable|numeric|min:0',
            'satuan' => 'required|string|max:20',
            'harga_beli_satuan' => 'nullable|numeric|min:0',
        ]);

        $data = $request->all();
        $data['stok_minimum'] = $request->input('stok_minimum', 5);
        $data['harga_beli_satuan'] = $request->input('harga_beli_satuan', 0);

        $bahanbaku->update($data);
        
        return redirect()->route('bahanbaku.index')->with('success', 'Data Bahan Baku diperbarui!');
    }

    public function destroy(BahanBaku $bahanbaku)
    {
        $bahanbaku->delete();
        return redirect()->route('bahanbaku.index')->with('success', 'Bahan dihapus dari logistik.');
    }
}
