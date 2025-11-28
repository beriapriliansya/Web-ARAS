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
        Schema::create('ulasan', function (Blueprint $table) {
            $table->id();

            // Foreign Keys
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade');

            $table->foreignId('destinasi_id')
                  ->constrained('destinasi_wisata')
                  ->onDelete('cascade');

            // Rating (1-5 bintang)
            $table->tinyInteger('rating')->unsigned(); // 1, 2, 3, 4, 5

            // Komentar
            $table->text('komentar');

            // Foto ulasan (opsional)
            $table->json('foto_ulasan')->nullable(); // Array path foto

            // Tanggal Kunjungan
            $table->date('tanggal_kunjungan')->nullable();

            // Status Moderasi
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');

            // Helpful Count (jumlah yang merasa helpful)
            $table->integer('helpful_count')->default(0);

            // Timestamps
            $table->timestamps();

            // Indexes
            $table->index('user_id');
            $table->index('destinasi_id');
            $table->index('rating');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ulasan');
    }
};
