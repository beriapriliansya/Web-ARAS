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
        // 1. Rename existing codes temporarily to avoid unique constraints
        for ($i = 1; $i <= 6; $i++) {
            Illuminate\Support\Facades\DB::table('kriteria')
                ->where('kode', "C{$i}")
                ->update(['kode' => "TEMP_C{$i}"]);
        }

        // 2. Map and update each criterion to the new configuration
        // TEMP_C5 (old Harga Tiket) -> C1
        Illuminate\Support\Facades\DB::table('kriteria')
            ->where('kode', 'TEMP_C5')
            ->update([
                'kode' => 'C1',
                'nama_kriteria' => 'Harga Tiket',
                'tipe' => 'cost',
                'keterangan' => 'Biaya / Harga Tiket Masuk',
                'satuan' => 'Skor'
            ]);

        // TEMP_C1 (old Aksesibilitas) -> C2
        Illuminate\Support\Facades\DB::table('kriteria')
            ->where('kode', 'TEMP_C1')
            ->update([
                'kode' => 'C2',
                'nama_kriteria' => 'Aksesibilitas',
                'tipe' => 'benefit',
                'keterangan' => 'Kemudahan akses jalan menuju lokasi wisata',
                'satuan' => 'Skor'
            ]);

        // TEMP_C2 (old Fasilitas) -> C3
        Illuminate\Support\Facades\DB::table('kriteria')
            ->where('kode', 'TEMP_C2')
            ->update([
                'kode' => 'C3',
                'nama_kriteria' => 'Fasilitas',
                'tipe' => 'benefit',
                'keterangan' => 'Kelengkapan sarana dan fasilitas penunjang di lokasi',
                'satuan' => 'Skor'
            ]);

        // TEMP_C3 (old Kebersihan) -> C4
        Illuminate\Support\Facades\DB::table('kriteria')
            ->where('kode', 'TEMP_C3')
            ->update([
                'kode' => 'C4',
                'nama_kriteria' => 'Kebersihan',
                'tipe' => 'benefit',
                'keterangan' => 'Tingkat kebersihan area wisata',
                'satuan' => 'Skor'
            ]);

        // TEMP_C4 (old Keamanan) -> C5
        Illuminate\Support\Facades\DB::table('kriteria')
            ->where('kode', 'TEMP_C4')
            ->update([
                'kode' => 'C5',
                'nama_kriteria' => 'Keamanan',
                'tipe' => 'benefit',
                'keterangan' => 'Tingkat keamanan di area lokasi wisata',
                'satuan' => 'Skor'
            ]);

        // TEMP_C6 (old Daya Tarik) -> C6
        Illuminate\Support\Facades\DB::table('kriteria')
            ->where('kode', 'TEMP_C6')
            ->update([
                'kode' => 'C6',
                'nama_kriteria' => 'Daya Tarik',
                'tipe' => 'benefit',
                'keterangan' => 'Keindahan, keunikan, dan daya tarik wisata',
                'satuan' => 'Skor'
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Rename existing codes temporarily to avoid unique constraints
        for ($i = 1; $i <= 6; $i++) {
            Illuminate\Support\Facades\DB::table('kriteria')
                ->where('kode', "C{$i}")
                ->update(['kode' => "TEMP_C{$i}"]);
        }

        // 2. Map back to original configuration
        // TEMP_C2 (Aksesibilitas) -> C1
        Illuminate\Support\Facades\DB::table('kriteria')
            ->where('kode', 'TEMP_C2')
            ->update([
                'kode' => 'C1',
                'nama_kriteria' => 'Aksesibilitas',
                'tipe' => 'benefit',
                'keterangan' => 'Kemudahan akses jalan menuju lokasi wisata',
                'satuan' => 'Skor'
            ]);

        // TEMP_C3 (Fasilitas) -> C2
        Illuminate\Support\Facades\DB::table('kriteria')
            ->where('kode', 'TEMP_C3')
            ->update([
                'kode' => 'C2',
                'nama_kriteria' => 'Fasilitas',
                'tipe' => 'benefit',
                'keterangan' => 'Kelengkapan sarana dan fasilitas penunjang di lokasi',
                'satuan' => 'Skor'
            ]);

        // TEMP_C4 (Kebersihan) -> C3
        Illuminate\Support\Facades\DB::table('kriteria')
            ->where('kode', 'TEMP_C4')
            ->update([
                'kode' => 'C3',
                'nama_kriteria' => 'Kebersihan',
                'tipe' => 'benefit',
                'keterangan' => 'Tingkat kebersihan area wisata',
                'satuan' => 'Skor'
            ]);

        // TEMP_C5 (Keamanan) -> C4
        Illuminate\Support\Facades\DB::table('kriteria')
            ->where('kode', 'TEMP_C5')
            ->update([
                'kode' => 'C4',
                'nama_kriteria' => 'Keamanan',
                'tipe' => 'benefit',
                'keterangan' => 'Tingkat keamanan di area lokasi wisata',
                'satuan' => 'Skor'
            ]);

        // TEMP_C1 (Harga Tiket) -> C5
        Illuminate\Support\Facades\DB::table('kriteria')
            ->where('kode', 'TEMP_C1')
            ->update([
                'kode' => 'C5',
                'nama_kriteria' => 'Harga Tiket',
                'tipe' => 'cost',
                'keterangan' => 'Biaya masuk / tiket masuk tempat wisata',
                'satuan' => 'Skor'
            ]);

        // TEMP_C6 (Daya Tarik) -> C6
        Illuminate\Support\Facades\DB::table('kriteria')
            ->where('kode', 'TEMP_C6')
            ->update([
                'kode' => 'C6',
                'nama_kriteria' => 'Daya Tarik',
                'tipe' => 'benefit',
                'keterangan' => 'Keindahan, keunikan, dan daya tarik wisata',
                'satuan' => 'Skor'
            ]);
    }
};
