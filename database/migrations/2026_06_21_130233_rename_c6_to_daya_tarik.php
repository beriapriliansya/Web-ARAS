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
        Illuminate\Support\Facades\DB::table('kriteria')
            ->where('kode', 'C6')
            ->update([
                'nama_kriteria' => 'Daya Tarik',
                'keterangan' => 'Keindahan, keunikan, dan daya tarik wisata'
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Illuminate\Support\Facades\DB::table('kriteria')
            ->where('kode', 'C6')
            ->update([
                'nama_kriteria' => 'Jumlah Pengunjung',
                'keterangan' => 'Rata-rata kepadatan atau jumlah pengunjung wisata'
            ]);
    }
};
