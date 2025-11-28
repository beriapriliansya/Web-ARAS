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
        Schema::table('users', function (Blueprint $table) {
            // 1. Tambahkan kolom ROLE dulu (setelah email)
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('user')->after('email');
            }

            // 2. Baru tambahkan kolom DESTINASI_ID (setelah role)
            // Pastikan tabel 'destinasi_wisata' sudah ada sebelum menjalankan ini
            if (!Schema::hasColumn('users', 'destinasi_id')) {
                // Gunakan unsignedBigInteger agar cocok dengan id default Laravel
                $table->foreignId('destinasi_id')
                      ->nullable()
                      ->after('role') // Sekarang aman karena role sudah dibuat di atas
                      ->constrained('destinasi_wisata') // Pastikan nama tabel destinasi benar
                      ->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Hapus foreign key dulu
            $table->dropForeign(['destinasi_id']);
            // Hapus kolomnya
            $table->dropColumn(['role', 'destinasi_id']);
        });
    }
};
