<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestockBahan extends Model
{
    use HasFactory;

    protected $table = 'restock_bahans';
    protected $primaryKey = 'id_restock';
    protected $fillable = [
        'id_bahan',
        'id_user',
        'jumlah_tambah',
        'harga_beli',
        'supplier',
        'tanggal_waktu',
    ];

    public function bahanBaku()
    {
        return $this->belongsTo(BahanBaku::class, 'id_bahan', 'id_bahan');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}
