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
                'nama_kriteria' => 'Harga Tiket',
                'bobot' => 0.20,
                'tipe' => 'cost',
                'keterangan' => 'Biaya / Harga Tiket Masuk',
                'satuan' => 'Skor',
                'status' => 'aktif',
            ],
            [
                'kode' => 'C2',
                'nama_kriteria' => 'Aksesibilitas',
                'bobot' => 0.15,
                'tipe' => 'benefit',
                'keterangan' => 'Kemudahan akses jalan menuju lokasi wisata',
                'satuan' => 'Skor',
                'status' => 'aktif',
            ],
            [
                'kode' => 'C3',
                'nama_kriteria' => 'Fasilitas',
                'bobot' => 0.20,
                'tipe' => 'benefit',
                'keterangan' => 'Kelengkapan sarana dan fasilitas penunjang di lokasi',
                'satuan' => 'Skor',
                'status' => 'aktif',
            ],
            [
                'kode' => 'C4',
                'nama_kriteria' => 'Kebersihan',
                'bobot' => 0.15,
                'tipe' => 'benefit',
                'keterangan' => 'Tingkat kebersihan area wisata',
                'satuan' => 'Skor',
                'status' => 'aktif',
            ],
            [
                'kode' => 'C5',
                'nama_kriteria' => 'Keamanan',
                'bobot' => 0.15,
                'tipe' => 'benefit',
                'keterangan' => 'Tingkat keamanan di area lokasi wisata',
                'satuan' => 'Skor',
                'status' => 'aktif',
            ],
            [
                'kode' => 'C6',
                'nama_kriteria' => 'Daya Tarik',
                'bobot' => 0.15,
                'tipe' => 'benefit',
                'keterangan' => 'Keindahan, keunikan, dan daya tarik wisata',
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
