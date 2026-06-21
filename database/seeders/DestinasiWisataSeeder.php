<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DestinasiWisata;

class DestinasiWisataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $destinasiData = [
            [
                'nama' => 'Kyoko beach',
                'deskripsi' => 'Kyoko Beach menawarkan keindahan pemandangan pantai pasir putih dengan panorama sunset yang menakjubkan.',
                'alamat' => 'Kecamatan Kalianda, Kabupaten Lampung Selatan',
                'latitude' => -5.61234000,
                'longitude' => 105.45678000,
                'kategori' => 'Pantai',
                'foto' => 'kyoko_beach.jpg',
                'harga_tiket' => 10000,
                'jarak' => 40,
                'jam_buka' => '07:00:00',
                'jam_tutup' => '17:00:00',
                'telepon' => '081234567891',
                'website' => null,
                'fasilitas' => ['Parkir', 'Toilet', 'Mushola', 'Warung Makan', 'Spot Foto', 'Gazebo'],
                'status' => 'aktif',
            ],
            [
                'nama' => 'Pantai Mutun',
                'deskripsi' => 'Pantai Mutun adalah salah satu destinasi wisata pantai yang terkenal di Pesawaran. Memiliki pasir putih yang bersih dan ombak yang tenang, sangat cocok untuk rekreasi keluarga.',
                'alamat' => 'Desa Gebang, Kecamatan Padang Cermin, Kabupaten Pesawaran',
                'latitude' => -5.58921000,
                'longitude' => 105.30788000,
                'kategori' => 'Pantai',
                'foto' => 'pantaimutun.jpg',
                'harga_tiket' => 15000,
                'jarak' => 15,
                'jam_buka' => '06:00:00',
                'jam_tutup' => '18:00:00',
                'telepon' => '081234567890',
                'website' => null,
                'fasilitas' => ['Parkir', 'Toilet', 'Mushola', 'Warung Makan', 'Gazebo', 'Spot Foto'],
                'status' => 'aktif',
            ],
            [
                'nama' => 'Pantai Klara 2',
                'deskripsi' => 'Pantai Klara 2 menawarkan keindahan pantai pasir putih dengan ombak yang sangat tenang, pepohonan rindang di bibir pantai, gazebo di atas air, serta jembatan selfie yang ikonik.',
                'alamat' => 'Jalan Raya Way Ratay, Desa Batu Menyan, Kecamatan Teluk Pandan, Kabupaten Pesawaran',
                'latitude' => -5.60232000,
                'longitude' => 105.24278000,
                'kategori' => 'Pantai',
                'foto' => 'pantai_klara_2.jpg',
                'harga_tiket' => 15000,
                'jarak' => 28,
                'jam_buka' => '07:00:00',
                'jam_tutup' => '18:00:00',
                'telepon' => '081234567899',
                'website' => null,
                'fasilitas' => ['Parkir', 'Toilet', 'Mushola', 'Gazebo', 'Warung Makan'],
                'status' => 'aktif',
            ],
            [
                'nama' => 'Pantai Ketapang Bahari',
                'deskripsi' => 'Pantai Ketapang Bahari menyajikan keindahan pantai alami dengan pohon ketapang yang rindang dan air laut jernih, sangat cocok untuk berenang.',
                'alamat' => 'Desa Ketapang, Kecamatan Padang Cermin, Kabupaten Pesawaran',
                'latitude' => -5.62345000,
                'longitude' => 105.29876000,
                'kategori' => 'Pantai',
                'foto' => 'ketapang_bahari.jpg',
                'harga_tiket' => 20000,
                'jarak' => 24,
                'jam_buka' => '06:00:00',
                'jam_tutup' => '19:00:00',
                'telepon' => '081234567894',
                'website' => null,
                'fasilitas' => ['Parkir', 'Toilet', 'Mushola', 'Warung Makan', 'Gazebo', 'Spot Foto', 'WiFi'],
                'status' => 'aktif',
            ],
            [
                'nama' => 'Pantai Bensam',
                'deskripsi' => 'Pantai Bensam (Benteng Samudra) adalah pantai yang dikelola dengan baik, memiliki pasir bersih, dan menyajikan wahana rekreasi air untuk para pengunjung.',
                'alamat' => 'Desa Batumenyan, Kecamatan Teluk Pandan, Kabupaten Pesawaran',
                'latitude' => -5.74891000,
                'longitude' => 105.22123000,
                'kategori' => 'Pantai',
                'foto' => 'pantai_bensam.jpg',
                'harga_tiket' => 15000,
                'jarak' => 82,
                'jam_buka' => '08:00:00',
                'jam_tutup' => '17:00:00',
                'telepon' => null,
                'website' => null,
                'fasilitas' => ['Toilet', 'Gazebo', 'Parkir'],
                'status' => 'aktif',
            ],
        ];

        foreach ($destinasiData as $data) {
            DestinasiWisata::create($data);
        }

        $this->command->info('✅ Destinasi Wisata seeder berhasil dijalankan!');
        $this->command->info('🏝️ Total destinasi: ' . count($destinasiData));
    }
}
