<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BahanBaku extends Model
{
    use HasFactory;
    
    // Memberi tahu Laravel bahwa nama tabel khusus ini karena majemuk
    protected $table = 'bahan_bakus';

    protected $primaryKey = 'id_bahan';
    protected $fillable = ['nama_bahan', 'jumlah_stok', 'stok_minimum', 'satuan', 'harga_beli_satuan'];

    public function restocks()
    {
        return $this->hasMany(RestockBahan::class, 'id_bahan', 'id_bahan');
    }

    public function isStokMenipis(): bool
    {
        return $this->jumlah_stok <= ($this->stok_minimum ?? 5);
    }
}
