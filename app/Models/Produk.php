<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_produk';
    
    // stok_etalase dibiarkan default 0 saat pendataan awal produk
    protected $fillable = [
        'id_kategori', 
        'nama_produk', 
        'harga_dasar', 
        'stok_etalase',
        'stok_minimum',
        'tipe_produksi',
        'jumlah_per_batch',
    ];

    public function isBatch(): bool
    {
        return ($this->tipe_produksi ?? 'Batch') === 'Batch';
    }

    public function isKustom(): bool
    {
        return ($this->tipe_produksi ?? 'Batch') === 'Kustom';
    }

    public function isStokMenipis(): bool
    {
        return $this->stok_etalase <= ($this->stok_minimum ?? 5);
    }

    public function hitungHpp(): float
    {
        $totalBiayaResep = 0;
        foreach ($this->reseps as $resep) {
            $hargaBahan = $resep->bahanBaku->harga_beli_satuan ?? 0;
            $totalBiayaResep += ($resep->takaran * $hargaBahan);
        }

        if ($this->isBatch()) {
            $yield = max(1, (int)($this->jumlah_per_batch ?? 1));
            return round($totalBiayaResep / $yield, 2);
        }

        return round($totalBiayaResep, 2);
    }

    public function getHppAttribute(): float
    {
        return $this->hitungHpp();
    }

    public function getMarginLabaAttribute(): float
    {
        return max(0, $this->harga_dasar - $this->hpp);
    }

    public function getPersentaseMarginAttribute(): float
    {
        if ($this->harga_dasar <= 0) return 0;
        return round(($this->margin_laba / $this->harga_dasar) * 100, 1);
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }

    public function detailTransaksis()
    {
        return $this->hasMany(DetailTransaksi::class, 'id_produk', 'id_produk');
    }

    public function produksiDapurs()
    {
        return $this->hasMany(ProduksiDapur::class, 'id_produk', 'id_produk');
    }

    public function diskonPromos()
    {
        return $this->hasMany(DiskonPromo::class, 'id_produk', 'id_produk');
    }

    public function reseps()
    {
        return $this->hasMany(Resep::class, 'id_produk', 'id_produk');
    }
}
