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
        Schema::create('restock_bahans', function (Blueprint $table) {
            $table->id('id_restock');
            $table->unsignedBigInteger('id_bahan');
            $table->unsignedBigInteger('id_user');
            $table->decimal('jumlah_tambah', 10, 2); // support decimal values just in case (e.g. 1.5 kg)
            $table->decimal('harga_beli', 15, 2)->nullable();
            $table->string('supplier')->nullable();
            $table->timestamp('tanggal_waktu')->useCurrent();
            $table->timestamps();

            // Foreign keys
            $table->foreign('id_bahan')->references('id_bahan')->on('bahan_bakus')->onDelete('cascade');
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restock_bahans');
    }
};
