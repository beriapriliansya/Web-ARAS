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

            // Relasi ke Destinasi
            $table->foreignId('destinasi_id')
                  ->constrained('destinasi_wisata')
                  ->onDelete('cascade');

            // Nilai Perhitungan ARAS
            $table->double('nilai_s'); // Nilai Optimality Function (Si)
            $table->double('nilai_k'); // Nilai Degree of Utility (Ki)

            // Ranking Akhir
            $table->integer('ranking');

            $table->timestamps();
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
