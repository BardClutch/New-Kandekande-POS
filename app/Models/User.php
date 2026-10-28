<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'permissions',
        'theme',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'permissions' => 'array',
        ];
    }

    public function isOwner(): bool
    {
        return $this->role === 'Owner';
    }

    public function isKasir(): bool
    {
        return in_array($this->role, ['Kasir', 'Cashier']);
    }

    public function isDapur(): bool
    {
        return in_array($this->role, ['Dapur', 'Kitchen']);
    }

    public function isStockAdmin(): bool
    {
        return in_array($this->role, ['Stock_Admin', 'Admin_Stok', 'Admin Gudang', 'Admin_Gudang']);
    }

    public function isAdminGudang(): bool
    {
        return $this->isStockAdmin();
    }

    public function isCustom(): bool
    {
        return in_array($this->role, ['Kustom', 'Custom', 'kustom', 'custom']);
    }

    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            'Cashier', 'Kasir' => 'Kasir',
            'Kitchen', 'Dapur' => 'Dapur',
            'Stock_Admin', 'Admin_Stok', 'Admin Gudang', 'Admin_Gudang' => 'Admin Gudang',
            'Kustom', 'Custom', 'kustom', 'custom' => 'Kustom',
            default => 'Owner',
        };
    }

    /**
     * Memeriksa apakah pengguna memiliki hak akses ke modul tertentu.
     */
    public function hasPermission(string $permission): bool
    {
        // Owner memiliki akses penuh ke seluruh modul sistem
        if ($this->isOwner()) {
            return true;
        }

        // Peran default bawaan sistem memiliki hak akses sesuai spesialisasinya
        if ($this->isKasir() && in_array($permission, ['kasir', 'pelanggan', 'laporan'])) {
            return true;
        }

        if ($this->isDapur() && in_array($permission, ['dapur'])) {
            return true;
        }

        if ($this->isStockAdmin() && in_array($permission, ['bahanbaku', 'restock'])) {
            return true;
        }

        // Peran Kustom memeriksa daftar permissions yang diatur oleh Owner
        if ($this->isCustom()) {
            $userPerms = is_array($this->permissions) 
                ? $this->permissions 
                : (json_decode($this->permissions ?? '[]', true) ?: []);
            
            // Sub-modul & relasi turunan
            if ($permission === 'pelanggan' && in_array('kasir', $userPerms)) {
                return true;
            }
            if ($permission === 'restock' && in_array('bahanbaku', $userPerms)) {
                return true;
            }

            return in_array($permission, $userPerms);
        }

        return false;
    }

    /**
     * Memeriksa apakah pengguna memiliki salah satu dari daftar izin yang diberikan.
     */
    public function hasAnyPermission(array $permissions): bool
    {
        foreach ($permissions as $p) {
            if ($this->hasPermission($p)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Daftar label modul hak akses yang dimiliki pengguna untuk tampilan UI / tabel.
     */
    public function getPermissionLabelsAttribute(): array
    {
        $map = [
            'kasir'     => 'Kasir (POS)',
            'pelanggan' => 'Daya Pelanggan',
            'dapur'     => 'Dapur (KDS)',
            'produk'    => 'Katalog Produk',
            'resep'     => 'Resep Roti',
            'diskon'    => 'Promo & Diskon',
            'bahanbaku' => 'Bahan Baku & Restock',
            'laporan'   => 'Laporan Penjualan',
            'karyawan'  => 'Kelola Karyawan',
        ];

        if ($this->isOwner()) {
            return ['Semua Modul'];
        }

        if ($this->isKasir()) {
            return ['Kasir (POS)', 'Riwayat Transaksi', 'Daya Pelanggan'];
        }

        if ($this->isDapur()) {
            return ['Dapur (KDS)'];
        }

        if ($this->isStockAdmin()) {
            return ['Bahan Baku & Restock'];
        }

        if ($this->isCustom()) {
            $perms = is_array($this->permissions) ? $this->permissions : (json_decode($this->permissions ?? '[]', true) ?: []);
            $labels = [];
            foreach ($perms as $p) {
                if (isset($map[$p])) {
                    $labels[] = $map[$p];
                }
            }
            return empty($labels) ? ['Belum Diatur'] : $labels;
        }

        return [];
    }

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class, 'id_user', 'id');
    }

    public function produksiDapurs()
    {
        return $this->hasMany(ProduksiDapur::class, 'id_user', 'id');
    }

    public function shiftKasirs()
    {
        return $this->hasMany(ShiftKasir::class, 'id_user', 'id');
    }

    public function activeShift(): ?ShiftKasir
    {
        return $this->shiftKasirs()->where('status', 'Open')->latest('waktu_buka')->first();
    }
}
