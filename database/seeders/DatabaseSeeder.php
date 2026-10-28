<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'owner@kandekande.com'],
            [
                'name' => 'Owner Utama',
                'password' => bcrypt('password'),
                'role' => 'Owner',
            ]
        );

        User::firstOrCreate(
            ['email' => 'kasir@kandekande.com'],
            [
                'name' => 'Kasir 1',
                'password' => bcrypt('password'),
                'role' => 'Cashier',
            ]
        );

        User::firstOrCreate(
            ['email' => 'dapur@kandekande.com'],
            [
                'name' => 'Koki Dapur',
                'password' => bcrypt('password'),
                'role' => 'Kitchen',
            ]
        );

        User::firstOrCreate(
            ['email' => 'gudang@kandekande.com'],
            [
                'name' => 'Admin Gudang & Stok',
                'password' => bcrypt('password'),
                'role' => 'Stock_Admin',
            ]
        );
    }
}
