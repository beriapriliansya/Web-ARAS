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
        Schema::create('alternatif', function (Blueprint $table) {
            $table->id();

            // Foreign Keys
            $table->foreignId('destinasi_id')
                  ->constrained('destinasi_wisata')
                  ->onDelete('cascade'); // Jika destinasi dihapus, data ini ikut terhapus

            $table->foreignId('kriteria_id')
                  ->constrained('kriteria')
                  ->onDelete('cascade'); // Jika kriteria dihapus, data ini ikut terhapus

            // Nilai untuk kriteria tertentu
            $table->decimal('nilai', 10, 2);

            // Catatan (opsional)
            $table->text('catatan')->nullable();

            // Timestamps
            $table->timestamps();

            // Unique constraint: satu destinasi hanya punya 1 nilai untuk 1 kriteria
            $table->unique(['destinasi_id', 'kriteria_id']);

            // Indexes
            $table->index('destinasi_id');
            $table->index('kriteria_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alternatif');
    }
};
