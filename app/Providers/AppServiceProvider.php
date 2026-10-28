<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        \Illuminate\Support\Facades\View::composer(['layouts.app', 'dashboard'], function ($view) {
            if (\Illuminate\Support\Facades\Schema::hasTable('bahan_bakus') && \Illuminate\Support\Facades\Schema::hasTable('produks')) {
                $lowBahan = \App\Models\BahanBaku::whereColumn('jumlah_stok', '<=', 'stok_minimum')->get();
                $lowProduk = \App\Models\Produk::whereColumn('stok_etalase', '<=', 'stok_minimum')->get();
                $view->with([
                    'globalLowStockBahan' => $lowBahan,
                    'globalLowStockProduk' => $lowProduk,
                    'globalLowStockCount' => $lowBahan->count() + $lowProduk->count(),
                ]);
            }
        });
    }
}
