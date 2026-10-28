<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProduksiDapur extends Model
{
    protected $table = 'produksi_dapurs';
    protected $primaryKey = 'id_produksi';
    protected $fillable = [
        'id_user',
        'id_produk',
        'jumlah_batch',
        'jumlah_matang',
        'tanggal_produksi',
        'stok_sisa_batch',
        'catatan_produksi',
        'status_produksi',
    ];

    protected $casts = [
        'tanggal_produksi' => 'date',
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}
