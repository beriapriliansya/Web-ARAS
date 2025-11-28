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
        Schema::create('hasil_aras', function (Blueprint $table) {
            $table->id();

            // Foreign Key
            $table->foreignId('destinasi_id')
                  ->constrained('destinasi_wisata')
                  ->onDelete('cascade');

            // Hasil Perhitungan ARAS
            // Step 1: Nilai Normalisasi Terbobot
            $table->decimal('nilai_normalisasi', 10, 6);

            // Step 2: Nilai Optimal (S0)
            $table->decimal('nilai_optimal', 10, 6);

            // Step 3: Utilitas (Ki = Si / S0)
            $table->decimal('utilitas', 10, 6);

            // Ranking (posisi urutan)
            $table->integer('ranking')->nullable();

            // Persentase (untuk visualisasi)
            $table->decimal('persentase', 5, 2)->nullable(); // 0.00 - 100.00

            // Metadata Perhitungan
            $table->timestamp('tanggal_hitung'); // Kapan dihitung
            $table->json('detail_perhitungan')->nullable(); // Detail step by step

            // Timestamps
            $table->timestamps();

            // Indexes
            $table->index('destinasi_id');
            $table->index('ranking');
            $table->index('tanggal_hitung');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hasil_aras');
    }
};
