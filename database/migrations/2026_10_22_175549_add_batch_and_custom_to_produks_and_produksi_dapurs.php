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
        Schema::table('produks', function (Blueprint $table) {
            $table->enum('tipe_produksi', ['Batch', 'Kustom'])->default('Batch')->after('harga_dasar');
            $table->integer('jumlah_per_batch')->default(1)->after('tipe_produksi');
        });

        Schema::table('produksi_dapurs', function (Blueprint $table) {
            $table->integer('jumlah_batch')->nullable()->after('id_produk');
            $table->string('catatan_produksi', 255)->nullable()->after('stok_sisa_batch');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produks', function (Blueprint $table) {
            $table->dropColumn(['tipe_produksi', 'jumlah_per_batch']);
        });

        Schema::table('produksi_dapurs', function (Blueprint $table) {
            $table->dropColumn(['jumlah_batch', 'catatan_produksi']);
        });
    }
};
