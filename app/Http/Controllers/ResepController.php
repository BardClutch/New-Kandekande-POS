<?php

namespace App\Http\Controllers;

use App\Models\Resep;
use App\Models\Produk;
use App\Models\BahanBaku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResepController extends Controller
{
    public function index()
    {
        $reseps = Resep::with(['produk', 'bahanBaku'])->get();
        $produks = Produk::with(['reseps.bahanBaku', 'kategori'])->get();
        return view('resep.index', compact('reseps', 'produks'));
    }

    public function updateBatchSetting(Request $request, Produk $produk)
    {
        $request->validate([
            'tipe_produksi' => 'required|in:Batch,Kustom',
            'jumlah_per_batch' => 'nullable|integer|min:1',
        ]);

        $produk->tipe_produksi = $request->tipe_produksi;
        $produk->jumlah_per_batch = $request->tipe_produksi === 'Batch' 
            ? max(1, (int)$request->input('jumlah_per_batch', 1)) 
            : 1;
        $produk->save();

        return redirect()->route('resep.index')->with('success', "Pengaturan batch/resep untuk '{$produk->nama_produk}' berhasil diperbarui!");
    }

    public function create()
    {
        $produks = Produk::all();
        $bahans = BahanBaku::all();
        return view('resep.create', compact('produks', 'bahans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_produk' => 'required|exists:produks,id_produk',
        ]);

        // Jika input berupa multi-bahan (array)
        if ($request->has('bahans') && is_array($request->bahans)) {
            $request->validate([
                'bahans' => 'required|array|min:1',
                'bahans.*.id_bahan' => 'required|exists:bahan_bakus,id_bahan',
                'bahans.*.takaran' => 'required|numeric|min:0.0001',
            ], [
                'bahans.*.id_bahan.required' => 'Pilih bahan baku untuk setiap baris.',
                'bahans.*.takaran.required' => 'Masukkan takaran untuk setiap bahan baku.',
                'bahans.*.takaran.min' => 'Takaran bahan harus lebih dari 0.',
            ]);

            try {
                DB::beginTransaction();
                $savedCount = 0;

                foreach ($request->bahans as $item) {
                    if (!empty($item['id_bahan']) && isset($item['takaran'])) {
                        Resep::updateOrCreate(
                            [
                                'id_produk' => $request->id_produk,
                                'id_bahan' => $item['id_bahan'],
                            ],
                            [
                                'takaran' => $item['takaran'],
                            ]
                        );
                        $savedCount++;
                    }
                }

                DB::commit();

                $produk = Produk::find($request->id_produk);
                return redirect()->route('resep.index')->with('success', "Berhasil menyimpan {$savedCount} komposisi bahan baku untuk '{$produk->nama_produk}'!");
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->back()->withInput()->with('error', 'Gagal menyimpan komposisi resep: ' . $e->getMessage());
            }
        }

        // Fallback untuk single-bahan
        $request->validate([
            'id_bahan' => 'required|exists:bahan_bakus,id_bahan',
            'takaran' => 'required|numeric|min:0'
        ]);

        Resep::updateOrCreate(
            [
                'id_produk' => $request->id_produk,
                'id_bahan' => $request->id_bahan,
            ],
            [
                'takaran' => $request->takaran,
            ]
        );

        return redirect()->route('resep.index')->with('success', 'Komposisi resep berhasil ditambahkan!');
    }

    public function edit(Resep $resep)
    {
        $produks = Produk::all();
        $bahans = BahanBaku::all();
        return view('resep.edit', compact('resep', 'produks', 'bahans'));
    }

    public function update(Request $request, Resep $resep)
    {
        $request->validate([
            'id_produk' => 'required|exists:produks,id_produk',
            'id_bahan' => 'required|exists:bahan_bakus,id_bahan',
            'takaran' => 'required|numeric|min:0'
        ]);

        $resep->update($request->all());

        return redirect()->route('resep.index')->with('success', 'Komposisi resep berhasil diperbarui!');
    }

    public function destroy(Resep $resep)
    {
        $resep->delete();
        return redirect()->route('resep.index')->with('success', 'Komposisi resep berhasil dihapus.');
    }
}
