<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\News;
use App\Models\User;
use Illuminate\Support\Str;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil superadmin sebagai penulis default
        $author = User::where('role', 'superadmin')->first();

        if (!$author) {
            $author = User::first();
        }

        $newsData = [
            [
                'title' => 'Festival Pahawang: Menjelajahi Keindahan Bawah Laut Pesawaran',
                'content' => "Pemerintah Kabupaten Pesawaran kembali menggelar Festival Pahawang tahun ini. Kegiatan ini bertujuan untuk mengenalkan lebih dekat potensi wisata air, khususnya keindahan bawah laut Pulau Pahawang yang menjadi surga bagi para pecinta snorkeling dan diving.\n\nDalam festival ini, pengunjung dapat menikmati berbagai atraksi budaya, penanaman terumbu karang, hingga perlombaan foto bawah air. Wisatawan lokal maupun mancanegara terlihat sangat antusias memadati lokasi festival.",
                'image' => null, // Opsional jika belum ada file di public/storage
                'user_id' => $author->id,
                'status' => 'published',
                'views' => 120,
                'published_at' => now(),
            ],
            [
                'title' => 'Pantai Sari Ringgung Bersiap Menyambut Wisatawan Libur Lebaran',
                'content' => "Menjelang liburan Lebaran, pengelola destinasi wisata Pantai Sari Ringgung terus berbenah. Sejumlah fasilitas pendukung seperti area parkir yang lebih luas, perbaikan gazebo, dan penambahan wahana air baru seperti Banana Boat dan Jet Ski kini telah siap digunakan.\n\nSelain itu, pengelola juga memastikan bahwa protokol keselamatan penyeberangan ke Pasir Timbul diperketat guna menjamin keamanan seluruh pengunjung.",
                'image' => null,
                'user_id' => $author->id,
                'status' => 'published',
                'views' => 85,
                'published_at' => now()->subDays(2),
            ],
            [
                'title' => 'Keindahan Pasir Putih Pantai Mutun yang Memukau Wisatawan',
                'content' => "Pantai Mutun tetap menjadi primadona destinasi wisata keluarga bagi warga Lampung dan sekitarnya. Dengan pasir putihnya yang lembut dan air laut yang sangat tenang, anak-anak dapat bermain air dengan aman di bibir pantai.\n\nFasilitas yang disediakan oleh pengelola pun terbilang sangat lengkap, mulai dari kamar bilas, mushola, warung makan lokal, hingga pondokan yang bersih untuk beristirahat.",
                'image' => null,
                'user_id' => $author->id,
                'status' => 'published',
                'views' => 150,
                'published_at' => now()->subDays(5),
            ],
        ];

        foreach ($newsData as $data) {
            $data['slug'] = Str::slug($data['title']);
            News::create($data);
        }

        $this->command->info('✅ News seeder berhasil dijalankan!');
    }
}
