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
        Schema::create('kriteria', function (Blueprint $table) {
            $table->id();

            // Nama dan Kode Kriteria
            $table->string('kode', 10)->unique(); // Contoh: C1, C2, C3
            $table->string('nama_kriteria', 100); // Nama kriteria

            // Bobot Kriteria
            $table->decimal('bobot', 5, 4); // Bobot (0.0000 - 1.0000)

            // Tipe Kriteria untuk ARAS
            $table->enum('tipe', ['benefit', 'cost'])->default('benefit');
            // benefit = semakin besar semakin baik (contoh: rating, fasilitas)
            // cost = semakin kecil semakin baik (contoh: harga, jarak)

            // Keterangan
            $table->text('keterangan')->nullable();

            // Satuan (opsional)
            $table->string('satuan', 50)->nullable(); // Contoh: Km, Rp, Rating, dll

            // Status
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');

            // Timestamps
            $table->timestamps();

            // Index
            $table->index('kode');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kriteria');
    }
};
