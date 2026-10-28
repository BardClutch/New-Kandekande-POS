<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'role:Owner,Kasir'])->group(function () {
    Route::get('/laporan', [App\Http\Controllers\LaporanController::class, 'index'])->name('laporan.index');
});

Route::middleware(['auth', 'role:Owner'])->group(function () {
    Route::get('/laporan/export', [App\Http\Controllers\LaporanController::class, 'exportCsv'])->name('laporan.export');
    Route::resource('karyawan', App\Http\Controllers\KaryawanController::class);
    Route::resource('kategori', App\Http\Controllers\KategoriController::class);
    Route::resource('produk', App\Http\Controllers\ProdukController::class);
    Route::patch('/resep/produk/{produk}/batch', [App\Http\Controllers\ResepController::class, 'updateBatchSetting'])->name('resep.updateBatch');
    Route::resource('resep', App\Http\Controllers\ResepController::class);
    Route::resource('diskon', App\Http\Controllers\DiskonPromoController::class);
});

Route::middleware(['auth', 'role:Owner,Stock_Admin'])->group(function () {
    Route::resource('bahanbaku', App\Http\Controllers\BahanBakuController::class);
    Route::resource('restock', App\Http\Controllers\RestockBahanController::class)->only(['index', 'store']);
});

Route::middleware(['auth', 'role:Owner,Kasir'])->group(function () {
    Route::get('/kasir', [App\Http\Controllers\KasirController::class, 'index'])->name('kasir.index');
    Route::post('/kasir', [App\Http\Controllers\KasirController::class, 'store'])->name('kasir.store');
    Route::get('/kasir/cetak/{id}', [App\Http\Controllers\KasirController::class, 'cetak'])->name('kasir.cetak');
    Route::resource('pelanggan', App\Http\Controllers\PelangganController::class)->only(['index', 'store', 'destroy']);

    // Manajemen Shift Kasir
    Route::get('/shift', [App\Http\Controllers\ShiftKasirController::class, 'index'])->name('shift.index');
    Route::post('/shift/buka', [App\Http\Controllers\ShiftKasirController::class, 'buka'])->name('shift.buka');
    Route::post('/shift/{shift}/tutup', [App\Http\Controllers\ShiftKasirController::class, 'tutup'])->name('shift.tutup');
    Route::get('/shift/status', [App\Http\Controllers\ShiftKasirController::class, 'status'])->name('shift.status');
    Route::get('/shift/{shift}/cetak', [App\Http\Controllers\ShiftKasirController::class, 'cetak'])->name('shift.cetak');
});

Route::middleware(['auth', 'role:Owner,Dapur'])->group(function () {
    Route::get('/dapur', [App\Http\Controllers\DapurController::class, 'index'])->name('dapur.index');
    Route::get('/dapur/cek-pesanan', [App\Http\Controllers\DapurController::class, 'cekPesananBaru'])->name('dapur.cek_pesanan');
    Route::patch('/dapur/pesanan/{transaksi}/status', [App\Http\Controllers\DapurController::class, 'updateStatusPesanan'])->name('dapur.update_status_pesanan');
    Route::post('/dapur', [App\Http\Controllers\DapurController::class, 'store'])->name('dapur.store');
    Route::patch('/dapur/{dapur}/status', [App\Http\Controllers\DapurController::class, 'updateStatus'])->name('dapur.updateStatus');
    Route::delete('/dapur/{dapur}', [App\Http\Controllers\DapurController::class, 'destroy'])->name('dapur.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/theme', [ProfileController::class, 'updateTheme'])->name('profile.theme');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
