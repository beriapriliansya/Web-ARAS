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
        // 1. Hapus foreign key booking_id dari tabel ulasan
        if (Schema::hasColumn('ulasan', 'booking_id')) {
            Schema::table('ulasan', function (Blueprint $table) {
                $table->dropForeign(['booking_id']);
                $table->dropColumn('booking_id');
            });
        }

        // 2. Hapus tabel bookings
        Schema::dropIfExists('bookings');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tidak perlu rollback karena sistem booking dihapus secara permanen.
    }
};
