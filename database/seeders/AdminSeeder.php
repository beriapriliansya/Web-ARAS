<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. SUPERADMIN (HAK AKSES PENUH)
        // Ini akun kamu/pemilik sistem. Bisa create admin lain.
        User::updateOrCreate(
            ['email' => 'superadmin@pariwisata.com'],
            [
                'name'     => 'Super Administrator',
                'password' => Hash::make('password123'),
                'role'     => 'superadmin', // Role khusus Superadmin
                'email_verified_at' => now(),
            ]
        );
        // 2. USER BIASA (PENGUNJUNG)
        User::updateOrCreate(
            ['email' => 'user@gmail.com'],
            [
                'name'     => 'Pengunjung Wisata',
                'password' => Hash::make('password123'),
                'role'     => 'user',
                'email_verified_at' => now(),
            ]
        );
    }
}
