<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kriteria;

class KriteriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data kriteria untuk penilaian destinasi wisata
        $kriteriaData = [
            [
                'kode' => 'C1',
                'nama_kriteria' => 'Harga Tiket Masuk',
                'bobot' => 0.20, // 20%
                'tipe' => 'cost', // Semakin murah semakin baik
                'keterangan' => 'Biaya yang harus dikeluarkan untuk masuk ke destinasi wisata',
                'satuan' => 'Rp',
                'status' => 'aktif',
            ],
            [
                'kode' => 'C2',
                'nama_kriteria' => 'Jarak dari Pusat Kota',
                'bobot' => 0.15, // 15%
                'tipe' => 'cost', // Semakin dekat semakin baik
                'keterangan' => 'Jarak tempuh dari pusat Kota Pesawaran ke destinasi',
                'satuan' => 'Km',
                'status' => 'aktif',
            ],
            [
                'kode' => 'C3',
                'nama_kriteria' => 'Fasilitas',
                'bobot' => 0.25, // 25%
                'tipe' => 'benefit', // Semakin banyak semakin baik
                'keterangan' => 'Kelengkapan fasilitas yang tersedia (parkir, toilet, mushola, dll)',
                'satuan' => 'Jumlah',
                'status' => 'aktif',
            ],
            [
                'kode' => 'C4',
                'nama_kriteria' => 'Rating Pengunjung',
                'bobot' => 0.25, // 25%
                'tipe' => 'benefit', // Semakin tinggi semakin baik
                'keterangan' => 'Rating rata-rata dari pengunjung (skala 1-5)',
                'satuan' => 'Bintang',
                'status' => 'aktif',
            ],
            [
                'kode' => 'C5',
                'nama_kriteria' => 'Aksesibilitas Jalan',
                'bobot' => 0.15, // 15%
                'tipe' => 'benefit', // Semakin baik semakin baik
                'keterangan' => 'Kualitas akses jalan menuju destinasi (skala 1-5)',
                'satuan' => 'Skor',
                'status' => 'aktif',
            ],
        ];

        // Insert data ke database
        foreach ($kriteriaData as $data) {
            Kriteria::create($data);
        }

        $this->command->info('✅ Kriteria seeder berhasil dijalankan!');
        $this->command->info('📊 Total kriteria: ' . count($kriteriaData));
    }
}
