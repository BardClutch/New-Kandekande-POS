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
        Schema::create('shift_kasirs', function (Blueprint $table) {
            $table->id('id_shift');
            $table->foreignId('id_user')->constrained('users')->onDelete('cascade');
            $table->dateTime('waktu_buka');
            $table->dateTime('waktu_tutup')->nullable();
            $table->decimal('modal_awal', 14, 2)->default(0);
            $table->decimal('total_penjualan_tunai', 14, 2)->default(0);
            $table->decimal('total_penjualan_nontunai', 14, 2)->default(0);
            $table->integer('total_transaksi')->default(0);
            $table->decimal('kas_akhir_sistem', 14, 2)->default(0);
            $table->decimal('kas_akhir_fisik', 14, 2)->nullable();
            $table->decimal('selisih', 14, 2)->nullable();
            $table->enum('status', ['Open', 'Closed'])->default('Open');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });

        // Hubungkan transaksis dengan shift kasir
        Schema::table('transaksis', function (Blueprint $table) {
            $table->unsignedBigInteger('id_shift')->nullable()->after('id_user');
            $table->foreign('id_shift')->references('id_shift')->on('shift_kasirs')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropForeign(['id_shift']);
            $table->dropColumn('id_shift');
        });

        Schema::dropIfExists('shift_kasirs');
    }
};
