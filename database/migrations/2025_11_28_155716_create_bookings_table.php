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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            // Relasi ke User (Pemesan)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Relasi ke Destinasi Wisata
            $table->foreignId('destinasi_id')->constrained('destinasi_wisata')->onDelete('cascade');

            $table->string('kode_booking')->unique(); // Contoh: BKG-20231001-001
            $table->date('tanggal_kunjungan');
            $table->integer('jumlah_tiket');
            $table->decimal('total_harga', 15, 2); // Pakai decimal biar aman untuk uang

            // Status Booking
            $table->enum('status', ['pending', 'menunggu_konfirmasi', 'confirmed', 'completed', 'cancelled'])->default('pending');

            $table->string('bukti_pembayaran')->nullable(); // Path gambar upload
            $table->text('catatan')->nullable(); // Opsional

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
