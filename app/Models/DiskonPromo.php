<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiskonPromo extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_promo';

    protected $fillable = [
        'id_produk',
        'nama_promo',
        'persentase',
        'tgl_mulai',
        'tgl_akhir'
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
    }
}
