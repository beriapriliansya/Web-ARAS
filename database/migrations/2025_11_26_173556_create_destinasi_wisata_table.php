<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('destinasi_wisata', function (Blueprint $table) {
            $table->id(); // Primary key auto increment

            // Data Destinasi
            $table->string('nama', 200); // Nama destinasi
            $table->text('deskripsi'); // Deskripsi lengkap
            $table->text('alamat'); // Alamat lengkap

            // Koordinat untuk Google Maps
            $table->decimal('latitude', 10, 8); // Latitude (contoh: -5.4513206)
            $table->decimal('longitude', 11, 8); // Longitude (contoh: 105.2700861)

            // Kategori Wisata
            $table->enum('kategori', [
                'Alam',
                'Pantai',
                'Gunung',
                'Air Terjun',
                'Budaya',
                'Kuliner',
                'Religi',
                'Edukasi'
            ])->default('Alam');

            // Informasi Tambahan
            $table->string('foto')->nullable(); // Path foto utama
            $table->decimal('harga_tiket', 10, 2)->default(0); // Harga tiket masuk
            $table->time('jam_buka')->nullable(); // Jam buka
            $table->time('jam_tutup')->nullable(); // Jam tutup
            $table->string('telepon', 20)->nullable(); // Nomor telepon
            $table->string('website', 255)->nullable(); // Website (jika ada)

            // Fasilitas (JSON format)
            $table->json('fasilitas')->nullable(); // ["parkir", "toilet", "mushola", dll]

            // Status
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');

            // Timestamps
            $table->timestamps(); // created_at, updated_at
            $table->softDeletes(); // deleted_at (untuk soft delete)

            // Indexes untuk performa query
            $table->index('kategori');
            $table->index('status');
            $table->index(['latitude', 'longitude']); // Untuk pencarian lokasi
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('destinasi_wisata');
    }
};
