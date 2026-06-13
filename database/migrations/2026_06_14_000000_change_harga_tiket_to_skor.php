<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Update kriteria C5 satuan to 'Skor'
        DB::table('kriteria')
            ->where('kode', 'C5')
            ->update(['satuan' => 'Skor']);

        // 2. Convert raw values in alternatif to score-based (divide by 5000, e.g. 15000 -> 3.0)
        $kriteriaId = DB::table('kriteria')->where('kode', 'C5')->value('id');
        if ($kriteriaId) {
            $alternatifs = DB::table('alternatif')
                ->where('kriteria_id', $kriteriaId)
                ->get();

            foreach ($alternatifs as $alt) {
                // If it is a raw ticket price (usually >= 100)
                if ($alt->nilai >= 100) {
                    $newScore = $alt->nilai / 5000;
                    DB::table('alternatif')
                        ->where('id', $alt->id)
                        ->update([
                            'nilai' => $newScore,
                            'catatan' => str_replace($alt->nilai, $newScore, $alt->catatan)
                        ]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Revert kriteria C5 satuan to 'Rp'
        DB::table('kriteria')
            ->where('kode', 'C5')
            ->update(['satuan' => 'Rp']);

        // 2. Revert score-based values in alternatif back to raw (multiply by 5000)
        $kriteriaId = DB::table('kriteria')->where('kode', 'C5')->value('id');
        if ($kriteriaId) {
            $alternatifs = DB::table('alternatif')
                ->where('kriteria_id', $kriteriaId)
                ->get();

            foreach ($alternatifs as $alt) {
                if ($alt->nilai <= 10) { // If it is a score (usually <= 10)
                    $rawPrice = $alt->nilai * 5000;
                    DB::table('alternatif')
                        ->where('id', $alt->id)
                        ->update([
                            'nilai' => $rawPrice,
                            'catatan' => str_replace($alt->nilai, $rawPrice, $alt->catatan)
                        ]);
                }
            }
        }
    }
};
