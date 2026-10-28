<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Ubah tipe kolom role menjadi string(50) agar fleksibel mendukung peran 'Kustom' dan peran baru lainnya
            $table->string('role', 50)->default('Cashier')->change();
            
            // Tambahkan kolom permissions bertipe JSON untuk menyimpan daftar hak akses modul bagi peran Kustom
            if (!Schema::hasColumn('users', 'permissions')) {
                $table->json('permissions')->nullable()->after('role');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'permissions')) {
                $table->dropColumn('permissions');
            }
            $table->string('role', 50)->default('Cashier')->change();
        });
    }
};
