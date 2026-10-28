<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    protected $table = 'pelanggans';
    protected $primaryKey = 'id_pelanggan';

    protected $fillable = [
        'nama_pelanggan',
        'no_hp',
        'tipe_pelanggan',
        'total_belanja',
        'total_transaksi',
        'terakhir_belanja',
        'catatan',
    ];

    protected $casts = [
        'terakhir_belanja' => 'datetime',
        'total_belanja' => 'decimal:2',
    ];

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class, 'id_pelanggan', 'id_pelanggan');
    }

    /**
     * Hitung ulang akumulasi belanja dan update tiering pelanggan secara otomatis
     */
    public function hitungUlangDayaBeli(): void
    {
        $transaksis = $this->transaksis;
        $totalBelanja = $transaksis->sum('total_belanja');
        $totalTrx = $transaksis->count();
        $terakhir = $transaksis->max('tanggal_waktu');

        $tier = 'Baru';
        if ($totalBelanja >= 750000 || $totalTrx >= 5) {
            $tier = 'VIP';
        } elseif ($totalBelanja >= 250000 || $totalTrx >= 2) {
            $tier = 'Reguler';
        }

        $this->update([
            'total_belanja' => $totalBelanja,
            'total_transaksi' => $totalTrx,
            'terakhir_belanja' => $terakhir,
            'tipe_pelanggan' => $tier,
        ]);
    }

    /**
     * Rata-rata pengeluaran per transaksi (Average Order Value / Basket Size)
     */
    public function getRataRataBelanjaAttribute(): float
    {
        if ($this->total_transaksi > 0) {
            return (float) ($this->total_belanja / $this->total_transaksi);
        }
        return 0;
    }
}
