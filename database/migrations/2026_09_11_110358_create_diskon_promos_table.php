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
        Schema::create('diskon_promos', function (Blueprint $table) {
            $table->id('id_promo');
            $table->unsignedBigInteger('id_produk');
            $table->string('nama_promo', 50);
            $table->double('persentase');
            $table->date('tgl_mulai');
            $table->date('tgl_akhir');
            $table->timestamps();

            $table->foreign('id_produk')->references('id_produk')->on('produks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diskon_promos');
    }
};
