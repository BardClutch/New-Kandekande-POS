<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class KaryawanController extends Controller
{
    /**
     * Daftar seluruh modul hak akses yang dapat dikustomisasi oleh Owner.
     */
    public static function getAvailablePermissions(): array
    {
        return [
            'kasir'     => ['label' => 'Kasir (POS) & Transaksi', 'desc' => 'Buka layar kasir POS, buat transaksi pembayaran, dan cetak struk.', 'icon' => '💼', 'category' => 'Operasional'],
            'pelanggan' => ['label' => 'Daya Pelanggan (CRM)', 'desc' => 'Akses database pelanggan, data kontak, dan riwayat belanja.', 'icon' => '👥', 'category' => 'Operasional'],
            'dapur'     => ['label' => 'Dapur & Produksi (KDS)', 'desc' => 'Layar Kitchen Display live, antrean pesanan masak, dan input batch roti matang.', 'icon' => '🍳', 'category' => 'Operasional'],
            'produk'    => ['label' => 'Katalog Produk & Kategori', 'desc' => 'Kelola etalase roti/kue, harga jual, dan kategori produk.', 'icon' => '🍞', 'category' => 'Katalog & Menu'],
            'resep'     => ['label' => 'Resep Roti & Takaran Batch', 'desc' => 'Kelola komposisi bahan baku dan pengaturan batch resep.', 'icon' => '📋', 'category' => 'Katalog & Menu'],
            'diskon'    => ['label' => 'Promo & Diskon', 'desc' => 'Kelola diskon penjualan, potongan harga, dan promosi.', 'icon' => '🏷️', 'category' => 'Katalog & Menu'],
            'bahanbaku' => ['label' => 'Bahan Baku & Restock', 'desc' => 'Kelola stok fisik bahan baku gudang dan catat pasokan restock masuk.', 'icon' => '📦', 'category' => 'Gudang & Stok'],
            'laporan'   => ['label' => 'Laporan Penjualan & Rekap', 'desc' => 'Lihat rekap penjualan, ringkasan transaksi, dan ekspor laporan.', 'icon' => '📊', 'category' => 'Analisis'],
            'karyawan'  => ['label' => 'Kelola Akun Karyawan', 'desc' => 'Buat, ubah, dan kelola akun staf toko serta hak akses pengguna.', 'icon' => '👤', 'category' => 'Pengaturan'],
        ];
    }

    /**
     * Tampilkan daftar seluruh akun karyawan & staf toko.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Filter berdasarkan peran jika dipilih
        if ($request->filled('role')) {
            if ($request->role === 'Kustom') {
                $query->whereIn('role', ['Kustom', 'Custom', 'kustom', 'custom']);
            } elseif ($request->role === 'Cashier') {
                $query->whereIn('role', ['Cashier', 'Kasir']);
            } elseif ($request->role === 'Kitchen') {
                $query->whereIn('role', ['Kitchen', 'Dapur']);
            } elseif ($request->role === 'Stock_Admin') {
                $query->whereIn('role', ['Stock_Admin', 'Admin_Stok', 'Admin Gudang', 'Admin_Gudang']);
            } else {
                $query->where('role', $request->role);
            }
        }

        // Pencarian nama atau email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $karyawans = $query->orderBy('name', 'asc')->get();

        // Hitungan per-peran
        $totalCount  = User::count();
        $ownerCount  = User::where('role', 'Owner')->count();
        $kasirCount  = User::whereIn('role', ['Cashier', 'Kasir'])->count();
        $dapurCount  = User::whereIn('role', ['Kitchen', 'Dapur'])->count();
        $stokCount   = User::whereIn('role', ['Stock_Admin', 'Admin_Stok', 'Admin Gudang', 'Admin_Gudang'])->count();
        $kustomCount = User::whereIn('role', ['Kustom', 'Custom', 'kustom', 'custom'])->count();

        return view('karyawan.index', compact(
            'karyawans',
            'totalCount',
            'ownerCount',
            'kasirCount',
            'dapurCount',
            'stokCount',
            'kustomCount'
        ));
    }

    /**
     * Tampilkan form pembuatan akun karyawan baru.
     */
    public function create()
    {
        $availablePermissions = self::getAvailablePermissions();
        return view('karyawan.create', compact('availablePermissions'));
    }

    /**
     * Simpan akun karyawan baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'role' => 'required|in:Owner,Cashier,Kitchen,Stock_Admin,Kustom',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'name.required' => 'Nama lengkap karyawan wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar untuk pengguna lain.',
            'role.required' => 'Pilih jenis peran karyawan.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal terdiri dari 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        if ($request->role === 'Kustom' && empty($request->permissions)) {
            return back()->withInput()->withErrors([
                'permissions' => 'Pilih minimal 1 modul hak akses untuk peran Kustom.'
            ]);
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'permissions' => $request->role === 'Kustom' ? array_values($request->permissions ?? []) : null,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('karyawan.index')
            ->with('success', "Akun karyawan '{$request->name}' berhasil dibuat!");
    }

    /**
     * Tampilkan form edit akun karyawan.
     */
    public function edit(User $karyawan)
    {
        $availablePermissions = self::getAvailablePermissions();
        return view('karyawan.edit', compact('karyawan', 'availablePermissions'));
    }

    /**
     * Perbarui data akun karyawan.
     */
    public function update(Request $request, User $karyawan)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($karyawan->id),
            ],
            'role' => 'required|in:Owner,Cashier,Kitchen,Stock_Admin,Kustom',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string',
            'password' => 'nullable|string|min:6|confirmed',
        ], [
            'name.required' => 'Nama lengkap karyawan wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.unique' => 'Email ini sudah digunakan oleh akun lain.',
            'role.required' => 'Pilih jenis peran karyawan.',
            'password.min' => 'Password minimal terdiri dari 6 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        if ($request->role === 'Kustom' && empty($request->permissions)) {
            return back()->withInput()->withErrors([
                'permissions' => 'Pilih minimal 1 modul hak akses untuk peran Kustom.'
            ]);
        }

        $karyawan->name = $request->name;
        $karyawan->email = $request->email;
        $karyawan->role = $request->role;
        $karyawan->permissions = $request->role === 'Kustom' ? array_values($request->permissions ?? []) : null;

        // Update password hanya jika diisi
        if ($request->filled('password')) {
            $karyawan->password = Hash::make($request->password);
        }

        $karyawan->save();

        return redirect()->route('karyawan.index')
            ->with('success', "Data akun '{$karyawan->name}' berhasil diperbarui!");
    }

    /**
     * Hapus akun karyawan dari sistem.
     */
    public function destroy(User $karyawan)
    {
        // Cegah Owner menghapus akun dirinya sendiri yang sedang login
        if ($karyawan->id === Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif digunakan.');
        }

        $nama = $karyawan->name;
        $karyawan->delete();

        return redirect()->route('karyawan.index')
            ->with('success', "Akun '{$nama}' berhasil dihapus dari sistem.");
    }
}
