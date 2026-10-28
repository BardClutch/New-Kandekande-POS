<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShiftKasir extends Model
{
    use HasFactory;

    protected $table = 'shift_kasirs';
    protected $primaryKey = 'id_shift';

    protected $fillable = [
        'id_user',
        'waktu_buka',
        'waktu_tutup',
        'modal_awal',
        'total_penjualan_tunai',
        'total_penjualan_nontunai',
        'total_transaksi',
        'kas_akhir_sistem',
        'kas_akhir_fisik',
        'selisih',
        'status',
        'catatan',
    ];

    protected $casts = [
        'waktu_buka' => 'datetime',
        'waktu_tutup' => 'datetime',
        'modal_awal' => 'decimal:2',
        'total_penjualan_tunai' => 'decimal:2',
        'total_penjualan_nontunai' => 'decimal:2',
        'kas_akhir_sistem' => 'decimal:2',
        'kas_akhir_fisik' => 'decimal:2',
        'selisih' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class, 'id_shift', 'id_shift');
    }

    public function isOpen(): bool
    {
        return $this->status === 'Open';
    }

    /**
     * Hitung ulang total penjualan tunai, non-tunai, dan kas akhir sistem
     */
    public function hitungUlangKas(): void
    {
        $tunai = $this->transaksis()
            ->where(function($q) {
                $q->where('metode_pembayaran', 'like', '%cash%')
                  ->orWhere('metode_pembayaran', 'like', '%tunai%');
            })
            ->sum('total_belanja');

        $nonTunai = $this->transaksis()
            ->where(function($q) {
                $q->where('metode_pembayaran', 'not like', '%cash%')
                  ->where('metode_pembayaran', 'not like', '%tunai%');
            })
            ->sum('total_belanja');

        $totalTrx = $this->transaksis()->count();
        $kasSistem = $this->modal_awal + $tunai;

        $this->update([
            'total_penjualan_tunai' => $tunai,
            'total_penjualan_nontunai' => $nonTunai,
            'total_transaksi' => $totalTrx,
            'kas_akhir_sistem' => $kasSistem,
        ]);
    }

    /**
     * Dapatkan status selisih kas (Cocok, Lebih, atau Kurang)
     */
    public function getStatusSelisihAttribute(): string
    {
        if (is_null($this->selisih)) {
            return 'Belum Ditutup';
        }
        if ($this->selisih == 0) {
            return 'Sesuai (Pas)';
        } elseif ($this->selisih > 0) {
            return 'Surplus (+Rp ' . number_format($this->selisih, 0, ',', '.') . ')';
        } else {
            return 'Minus (-Rp ' . number_format(abs($this->selisih), 0, ',', '.') . ')';
        }
    }
}
