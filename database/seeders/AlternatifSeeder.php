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
                'C1' => 5,
                'C2' => 4,
                'C3' => 4,
                'C4' => 4,
                'C5' => 5,
                'C6' => 5,
            ],
            'Pantai Sari Ringgung' => [
                'C1' => 4,
                'C2' => 5,
                'C3' => 4,
                'C4' => 4,
                'C5' => 5,
                'C6' => 5,
            ],
            'Pulau Pahawang' => [
                'C1' => 4,
                'C2' => 5,
                'C3' => 5,
                'C4' => 5,
                'C5' => 3,
                'C6' => 4,
            ],
            'Pantai Klara' => [
                'C1' => 5,
                'C2' => 4,
                'C3' => 4,
                'C4' => 5,
                'C5' => 5,
                'C6' => 4,
            ],
            'Teluk Hantu' => [
                'C1' => 2,
                'C2' => 2,
                'C3' => 5,
                'C4' => 3,
                'C5' => 2,
                'C6' => 2,
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
