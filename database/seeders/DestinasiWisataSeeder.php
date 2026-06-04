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
                'deskripsi' => 'Pantai Mutun adalah salah satu destinasi wisata pantai yang terkenal di Pesawaran. Memiliki pasir putih yang bersih dan ombak yang tenang, sangat cocok untuk rekreasi keluarga.',
                'alamat' => 'Desa Gebang, Kecamatan Padang Cermin, Kabupaten Pesawaran',
                'latitude' => -5.58921000,
                'longitude' => 105.30788000,
                'kategori' => 'Pantai',
                'foto' => 'pantai_mutun.jpg',
                'harga_tiket' => 15000,
                'jam_buka' => '06:00:00',
                'jam_tutup' => '18:00:00',
                'telepon' => '081234567890',
                'website' => null,
                'fasilitas' => ['Parkir', 'Toilet', 'Mushola', 'Warung Makan', 'Gazebo', 'Spot Foto'],
                'status' => 'aktif',
            ],
            [
                'nama' => 'Pantai Sari Ringgung',
                'deskripsi' => 'Pantai Sari Ringgung menawarkan keindahan pasir timbul yang unik di tengah laut dan fasilitas lengkap rekreasi air seperti banana boat.',
                'alamat' => 'Desa Sidodadi, Kecamatan Teluk Pandan, Kabupaten Pesawaran',
                'latitude' => -5.62345000,
                'longitude' => 105.29876000,
                'kategori' => 'Pantai',
                'foto' => 'sari_ringgung.jpg',
                'harga_tiket' => 20000,
                'jam_buka' => '06:00:00',
                'jam_tutup' => '19:00:00',
                'telepon' => '081234567894',
                'website' => 'www.sariringgung.com',
                'fasilitas' => ['Parkir', 'Toilet', 'Mushola', 'Warung Makan', 'Gazebo', 'Spot Foto', 'WiFi'],
                'status' => 'aktif',
            ],
            [
                'nama' => 'Pulau Pahawang',
                'deskripsi' => 'Pulau Pahawang terkenal dengan keindahan bawah lautnya yang menakjubkan, menjadikannya surga snorkeling dengan terumbu karang menawan dan ikan badut.',
                'alamat' => 'Kecamatan Punduh Pidada, Kabupaten Pesawaran',
                'latitude' => -5.67499000,
                'longitude' => 105.21731000,
                'kategori' => 'Pulau',
                'foto' => 'pulau_pahawang.jpg',
                'harga_tiket' => 10000,
                'jam_buka' => '07:00:00',
                'jam_tutup' => '17:00:00',
                'telepon' => '081234567898',
                'website' => 'www.pahawangisland.com',
                'fasilitas' => ['Toilet', 'Mushola', 'Penginapan', 'Perahu', 'Warung Makan', 'WiFi'],
                'status' => 'aktif',
            ],
            [
                'nama' => 'Pantai Klara',
                'deskripsi' => 'Pantai Klara memiliki pohon kelapa yang tumbuh sangat rapat di sepanjang pantai. Airnya dangkal dan tenang, sangat aman untuk bermain anak-anak.',
                'alamat' => 'Jalan Raya Way Ratay, Desa Batu Menyan, Kecamatan Teluk Pandan, Kabupaten Pesawaran',
                'latitude' => -5.60232000,
                'longitude' => 105.24278000,
                'kategori' => 'Pantai',
                'foto' => 'pantai_klara.jpg',
                'harga_tiket' => 15000,
                'jam_buka' => '07:00:00',
                'jam_tutup' => '18:00:00',
                'telepon' => '081234567899',
                'website' => null,
                'fasilitas' => ['Parkir', 'Toilet', 'Mushola', 'Gazebo', 'Warung Makan'],
                'status' => 'aktif',
            ],
            [
                'nama' => 'Teluk Hantu',
                'deskripsi' => 'Teluk Hantu menyimpan pesona tersembunyi dengan pasir putih bersih dan air laut biru tenang. Destinasi yang sangat cocok untuk mencari ketenangan.',
                'alamat' => 'Desa Pagar Jaya, Kecamatan Punduh Pidada, Kabupaten Pesawaran',
                'latitude' => -5.74891000,
                'longitude' => 105.22123000,
                'kategori' => 'Pantai',
                'foto' => 'teluk_hantu.jpg',
                'harga_tiket' => 5000,
                'jam_buka' => '08:00:00',
                'jam_tutup' => '17:00:00',
                'telepon' => null,
                'website' => null,
                'fasilitas' => ['Toilet', 'Gazebo'],
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
