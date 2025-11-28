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

        // 2. ADMIN DESTINASI (CONTOH 1)
        // Ini contoh akun admin yang nanti memegang destinasi "Pantai Pahawang"
        User::updateOrCreate(
            ['email' => 'admin.pahawang@pariwisata.com'],
            [
                'name'     => 'Admin Pahawang',
                'password' => Hash::make('password123'),
                'role'     => 'admin', // Role tetap 'admin'
                // Nanti kolom 'destinasi_id' diisi manual lewat Dashboard Superadmin
                'email_verified_at' => now(),
            ]
        );

        // 3. ADMIN DESTINASI (CONTOH 2)
        // Ini contoh akun admin untuk "Pulau Tegal Mas"
        User::updateOrCreate(
            ['email' => 'admin.tegalmas@pariwisata.com'],
            [
                'name'     => 'Admin Tegal Mas',
                'password' => Hash::make('password123'),
                'role'     => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // 4. USER BIASA (PENGUNJUNG)
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
