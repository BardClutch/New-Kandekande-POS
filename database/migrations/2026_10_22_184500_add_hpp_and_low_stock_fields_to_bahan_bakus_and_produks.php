<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bahan_bakus', function (Blueprint $table) {
            $table->decimal('harga_beli_satuan', 15, 2)->default(0)->after('satuan');
            $table->double('stok_minimum')->default(5)->after('jumlah_stok');
        });

        Schema::table('produks', function (Blueprint $table) {
            $table->integer('stok_minimum')->default(5)->after('stok_etalase');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bahan_bakus', function (Blueprint $table) {
            $table->dropColumn(['harga_beli_satuan', 'stok_minimum']);
        });

        Schema::table('produks', function (Blueprint $table) {
            $table->dropColumn(['stok_minimum']);
        });
    }
};
