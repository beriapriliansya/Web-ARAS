<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🌱 Memulai proses seeding database...');
        $this->command->info('');

        // Jalankan seeder sesuai urutan (penting!)
        // Urutan: Kriteria -> Destinasi -> Alternatif

        $this->command->info('1️⃣ Seeding Kriteria...');
        $this->call(KriteriaSeeder::class);
        $this->command->info('');

        $this->command->info('2️⃣ Seeding Destinasi Wisata...');
        $this->call(DestinasiWisataSeeder::class);
        $this->command->info('');

        $this->command->info('3️⃣ Seeding Alternatif (Nilai Destinasi)...');
        $this->call(AlternatifSeeder::class);
        $this->command->info('');

        $this->command->info('4️⃣ Seeding Admin & User Default...');
        $this->call(AdminSeeder::class);
        $this->command->info('');

        $this->command->info('5️⃣ Seeding Berita/News...');
        $this->call(NewsSeeder::class);
        $this->command->info('');

        $this->command->info('✅ Semua seeder berhasil dijalankan!');
        $this->command->info('🎉 Database siap digunakan!');
    }
}
