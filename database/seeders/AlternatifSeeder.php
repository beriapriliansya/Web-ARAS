<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Alternatif;
use App\Models\DestinasiWisata;
use App\Models\Kriteria;

class AlternatifSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data nilai untuk setiap destinasi per kriteria
        // Format: [destinasi_nama => [kriteria_kode => nilai]]
        $nilaiData = [
            'Pantai Mutun' => [
                'C1' => 15000,  // Harga Tiket (Rp)
                'C2' => 25,     // Jarak (Km)
                'C3' => 5,      // Fasilitas (Jumlah)
                'C4' => 4.5,    // Rating (Bintang)
                'C5' => 5,      // Aksesibilitas (Skor)
            ],
            'Teluk Kiluan' => [
                'C1' => 10000,  // Harga Tiket (Rp)
                'C2' => 60,     // Jarak (Km)
                'C3' => 5,      // Fasilitas (Jumlah)
                'C4' => 4.8,    // Rating (Bintang)
                'C5' => 3,      // Aksesibilitas (Skor)
            ],
            'Way Lalaan' => [
                'C1' => 5000,   // Harga Tiket (Rp)
                'C2' => 15,     // Jarak (Km)
                'C3' => 4,      // Fasilitas (Jumlah)
                'C4' => 4.2,    // Rating (Bintang)
                'C5' => 4,      // Aksesibilitas (Skor)
            ],
            'Goa Landak' => [
                'C1' => 10000,  // Harga Tiket (Rp)
                'C2' => 30,     // Jarak (Km)
                'C3' => 4,      // Fasilitas (Jumlah)
                'C4' => 4.0,    // Rating (Bintang)
                'C5' => 3,      // Aksesibilitas (Skor)
            ],
            'Pantai Sari Ringgung' => [
                'C1' => 20000,  // Harga Tiket (Rp)
                'C2' => 35,     // Jarak (Km)
                'C3' => 7,      // Fasilitas (Jumlah)
                'C4' => 4.7,    // Rating (Bintang)
                'C5' => 5,      // Aksesibilitas (Skor)
            ],
            'Puncak Mas' => [
                'C1' => 8000,   // Harga Tiket (Rp)
                'C2' => 20,     // Jarak (Km)
                'C3' => 5,      // Fasilitas (Jumlah)
                'C4' => 4.4,    // Rating (Bintang)
                'C5' => 4,      // Aksesibilitas (Skor)
            ],
        ];

        // Loop dan insert data
        $totalInserted = 0;

        foreach ($nilaiData as $namaDestinasi => $kriteriaValues) {
            // Cari ID destinasi berdasarkan nama
            $destinasi = DestinasiWisata::where('nama', $namaDestinasi)->first();

            if (!$destinasi) {
                $this->command->warn("⚠️ Destinasi '{$namaDestinasi}' tidak ditemukan!");
                continue;
            }

            foreach ($kriteriaValues as $kodeKriteria => $nilai) {
                // Cari ID kriteria berdasarkan kode
                $kriteria = Kriteria::where('kode', $kodeKriteria)->first();

                if (!$kriteria) {
                    $this->command->warn("⚠️ Kriteria '{$kodeKriteria}' tidak ditemukan!");
                    continue;
                }

                // Insert data alternatif
                Alternatif::create([
                    'destinasi_id' => $destinasi->id,
                    'kriteria_id' => $kriteria->id,
                    'nilai' => $nilai,
                    'catatan' => "Nilai {$kriteria->nama_kriteria} untuk {$destinasi->nama}",
                ]);

                $totalInserted++;
            }
        }

        $this->command->info('✅ Alternatif seeder berhasil dijalankan!');
        $this->command->info('📊 Total data alternatif: ' . $totalInserted);
        $this->command->info('🏝️ Destinasi: ' . count($nilaiData));
        $this->command->info('📋 Kriteria per destinasi: ' . count(reset($nilaiData)));
    }
}
