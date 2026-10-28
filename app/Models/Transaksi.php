<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksis';
    protected $primaryKey = 'id_transaksi';
    protected $fillable = [
        'id_user',
        'id_shift',
        'id_pelanggan',
        'nama_pelanggan',
        'no_hp_pelanggan',
        'catatan_pesanan',
        'status_pesanan',
        'jenis_pesanan',
        'metode_pembayaran',
        'total_belanja',
        'tanggal_waktu',
    ];

    protected $casts = [
        'tanggal_waktu' => 'datetime',
    ];

    public function detailTransaksis()
    {
        return $this->hasMany(DetailTransaksi::class, 'id_transaksi', 'id_transaksi');
    }

    public function details()
    {
        return $this->detailTransaksis();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id_pelanggan');
    }

    public function shiftKasir()
    {
        return $this->belongsTo(ShiftKasir::class, 'id_shift', 'id_shift');
    }
}
