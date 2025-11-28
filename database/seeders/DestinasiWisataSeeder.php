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
                'nama' => 'Pantai Mutun',
                'deskripsi' => 'Pantai Mutun adalah salah satu destinasi wisata pantai yang terkenal di Pesawaran. Memiliki pasir putih yang bersih dan ombak yang tenang, sangat cocok untuk keluarga.',
                'alamat' => 'Desa Gebang, Kecamatan Padang Cermin, Kabupaten Pesawaran',
                'latitude' => -5.5892100,
                'longitude' => 105.3078800,
                'kategori' => 'Pantai',
                'foto' => 'pantai_mutun.jpg',
                'harga_tiket' => 15000,
                'jam_buka' => '06:00:00',
                'jam_tutup' => '18:00:00',
                'telepon' => '081234567890',
                'website' => null,
                'fasilitas' => ['Parkir', 'Toilet', 'Mushola', 'Warung Makan', 'Gazebo'],
                'status' => 'aktif',
            ],
            [
                'nama' => 'Teluk Kiluan',
                'deskripsi' => 'Teluk Kiluan terkenal dengan wisata dolphin watching. Pengunjung dapat melihat lumba-lumba di habitat aslinya dan menikmati keindahan teluk yang memukau.',
                'alamat' => 'Desa Kiluan Negeri, Kecamatan Kelumbayan, Kabupaten Pesawaran',
                'latitude' => -5.7542300,
                'longitude' => 105.2328100,
                'kategori' => 'Pantai',
                'foto' => 'teluk_kiluan.jpg',
                'harga_tiket' => 10000,
                'jam_buka' => '05:00:00',
                'jam_tutup' => '17:00:00',
                'telepon' => '081234567891',
                'website' => 'www.telukkiluan.com',
                'fasilitas' => ['Parkir', 'Toilet', 'Penginapan', 'Perahu', 'Warung Makan'],
                'status' => 'aktif',
            ],
            [
                'nama' => 'Way Lalaan',
                'deskripsi' => 'Way Lalaan adalah tempat wisata alam dengan air terjun yang indah. Suasana asri dan sejuk membuat tempat ini cocok untuk refreshing.',
                'alamat' => 'Desa Way Layap, Kecamatan Gedong Tataan, Kabupaten Pesawaran',
                'latitude' => -5.4513200,
                'longitude' => 105.4027800,
                'kategori' => 'Air Terjun',
                'foto' => 'way_lalaan.jpg',
                'harga_tiket' => 5000,
                'jam_buka' => '07:00:00',
                'jam_tutup' => '17:00:00',
                'telepon' => '081234567892',
                'website' => null,
                'fasilitas' => ['Parkir', 'Toilet', 'Gazebo', 'Area Bermain'],
                'status' => 'aktif',
            ],
            [
                'nama' => 'Goa Landak',
                'deskripsi' => 'Goa Landak merupakan objek wisata goa alam yang unik. Di dalam goa terdapat formasi stalaktit dan stalagmit yang menawan.',
                'alamat' => 'Desa Sukarame, Kecamatan Padang Cermin, Kabupaten Pesawaran',
                'latitude' => -5.5123400,
                'longitude' => 105.3456700,
                'kategori' => 'Alam',
                'foto' => 'goa_landak.jpg',
                'harga_tiket' => 10000,
                'jam_buka' => '08:00:00',
                'jam_tutup' => '16:00:00',
                'telepon' => '081234567893',
                'website' => null,
                'fasilitas' => ['Parkir', 'Toilet', 'Pemandu', 'Warung Makan'],
                'status' => 'aktif',
            ],
            [
                'nama' => 'Pantai Sari Ringgung',
                'deskripsi' => 'Pantai Sari Ringgung menawarkan pemandangan sunset yang spektakuler. Terdapat berbagai fasilitas rekreasi air dan penginapan.',
                'alamat' => 'Desa Sidodadi, Kecamatan Padang Cermin, Kabupaten Pesawaran',
                'latitude' => -5.6234500,
                'longitude' => 105.2987600,
                'kategori' => 'Pantai',
                'foto' => 'sari_ringgung.jpg',
                'harga_tiket' => 20000,
                'jam_buka' => '06:00:00',
                'jam_tutup' => '19:00:00',
                'telepon' => '081234567894',
                'website' => 'www.sariringgung.com',
                'fasilitas' => ['Parkir', 'Toilet', 'Mushola', 'Resort', 'Warung Makan', 'Banana Boat', 'Jet Ski'],
                'status' => 'aktif',
            ],
            [
                'nama' => 'Puncak Mas',
                'deskripsi' => 'Puncak Mas adalah destinasi wisata pegunungan dengan view kota Bandar Lampung yang menakjubkan. Cocok untuk camping dan hiking.',
                'alamat' => 'Desa Hanura, Kecamatan Teluk Pandan, Kabupaten Pesawaran',
                'latitude' => -5.4789100,
                'longitude' => 105.3712300,
                'kategori' => 'Gunung',
                'foto' => 'puncak_mas.jpg',
                'harga_tiket' => 8000,
                'jam_buka' => '00:00:00',
                'jam_tutup' => '23:59:59',
                'telepon' => '081234567895',
                'website' => null,
                'fasilitas' => ['Parkir', 'Toilet', 'Area Camping', 'Gazebo', 'Warung Makan'],
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
