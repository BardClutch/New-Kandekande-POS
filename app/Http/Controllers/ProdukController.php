<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    public function index()
    {
        // Menggunakan Eager Loading untuk performa pengambilan data berelasi
        $produks = Produk::with(['kategori', 'reseps.bahanBaku'])->get();
        return view('produk.index', compact('produks'));
    }

    public function create()
    {
        $kategoris = Kategori::all();
        return view('produk.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_kategori' => 'required|exists:kategoris,id_kategori',
            'nama_produk' => 'required|string|max:100',
            'harga_dasar' => 'required|numeric',
            'tipe_produksi' => 'nullable|in:Batch,Kustom',
            'jumlah_per_batch' => 'nullable|integer|min:1',
        ]);
        
        $data = $request->all();
        $data['tipe_produksi'] = $request->input('tipe_produksi', 'Batch');
        $data['jumlah_per_batch'] = $data['tipe_produksi'] === 'Batch' 
            ? max(1, (int)$request->input('jumlah_per_batch', 1)) 
            : 1;

        Produk::create($data);
        
        return redirect()->route('produk.index')->with('success', 'Produk baru berhasil didaftarkan ke etalase!');
    }

    public function edit(Produk $produk)
    {
        $kategoris = Kategori::all();
        return view('produk.edit', compact('produk', 'kategoris'));
    }

    public function update(Request $request, Produk $produk)
    {
        $request->validate([
            'id_kategori' => 'required|exists:kategoris,id_kategori',
            'nama_produk' => 'required|string|max:100',
            'harga_dasar' => 'required|numeric',
            'tipe_produksi' => 'nullable|in:Batch,Kustom',
            'jumlah_per_batch' => 'nullable|integer|min:1',
        ]);

        $data = $request->all();
        $data['tipe_produksi'] = $request->input('tipe_produksi', 'Batch');
        $data['jumlah_per_batch'] = $data['tipe_produksi'] === 'Batch' 
            ? max(1, (int)$request->input('jumlah_per_batch', 1)) 
            : 1;

        $produk->update($data);
        
        return redirect()->route('produk.index')->with('success', 'Informasi produk berhasil diperbarui!');
    }

    public function destroy(Produk $produk)
    {
        $produk->delete();
        return redirect()->route('produk.index')->with('success', 'Produk dihapus/ditarik dari etalase.');
    }
}
