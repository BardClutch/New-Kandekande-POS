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
        // 1. Buat tabel pelanggans untuk daya pelanggan & CRM
        if (!Schema::hasTable('pelanggans')) {
            Schema::create('pelanggans', function (Blueprint $table) {
                $table->id('id_pelanggan');
                $table->string('nama_pelanggan');
                $table->string('no_hp')->nullable()->index();
                $table->enum('tipe_pelanggan', ['Baru', 'Reguler', 'VIP'])->default('Baru');
                $table->decimal('total_belanja', 15, 2)->default(0);
                $table->integer('total_transaksi')->default(0);
                $table->dateTime('terakhir_belanja')->nullable();
                $table->text('catatan')->nullable();
                $table->timestamps();
            });
        }

        // 2. Tambah kolom pelanggan dan status pesanan dapur pada tabel transaksis
        Schema::table('transaksis', function (Blueprint $table) {
            if (!Schema::hasColumn('transaksis', 'id_pelanggan')) {
                $table->unsignedBigInteger('id_pelanggan')->nullable()->after('id_user');
                $table->foreign('id_pelanggan')->references('id_pelanggan')->on('pelanggans')->onDelete('set null');
            }
            if (!Schema::hasColumn('transaksis', 'nama_pelanggan')) {
                $table->string('nama_pelanggan')->nullable()->after('id_pelanggan');
            }
            if (!Schema::hasColumn('transaksis', 'no_hp_pelanggan')) {
                $table->string('no_hp_pelanggan')->nullable()->after('nama_pelanggan');
            }
            if (!Schema::hasColumn('transaksis', 'catatan_pesanan')) {
                $table->string('catatan_pesanan')->nullable()->after('no_hp_pelanggan');
            }
            if (!Schema::hasColumn('transaksis', 'status_pesanan')) {
                $table->enum('status_pesanan', ['Menunggu Dapur', 'Sedang Disiapkan', 'Siap Disajikan', 'Selesai'])->default('Menunggu Dapur')->after('total_belanja');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            if (Schema::hasColumn('transaksis', 'id_pelanggan')) {
                $table->dropForeign(['id_pelanggan']);
                $table->dropColumn('id_pelanggan');
            }
            if (Schema::hasColumn('transaksis', 'nama_pelanggan')) {
                $table->dropColumn('nama_pelanggan');
            }
            if (Schema::hasColumn('transaksis', 'no_hp_pelanggan')) {
                $table->dropColumn('no_hp_pelanggan');
            }
            if (Schema::hasColumn('transaksis', 'catatan_pesanan')) {
                $table->dropColumn('catatan_pesanan');
            }
            if (Schema::hasColumn('transaksis', 'status_pesanan')) {
                $table->dropColumn('status_pesanan');
            }
        });

        Schema::dropIfExists('pelanggans');
    }
};
