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

        // 2. Convert raw values in alternatif to score-based (1 = Sangat Murah, 5 = Sangat Mahal)
        $kriteriaId = DB::table('kriteria')->where('kode', 'C5')->value('id');
        if ($kriteriaId) {
            $alternatifs = DB::table('alternatif')
                ->where('kriteria_id', $kriteriaId)
                ->get();

            foreach ($alternatifs as $alt) {
                // If it is a raw ticket price (usually >= 100)
                if ($alt->nilai >= 100) {
                    $newScore = 5.0; // Default to Sangat Mahal
                    if ($alt->nilai < 3000) {
                        $newScore = 1.0;
                    } elseif ($alt->nilai <= 5000) {
                        $newScore = 2.0;
                    } elseif ($alt->nilai <= 10000) {
                        $newScore = 3.0;
                    } elseif ($alt->nilai < 15000) {
                        $newScore = 4.0;
                    }

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

        // 2. Revert score-based values in alternatif back to raw (multiply by 5000 or approximate)
        $kriteriaId = DB::table('kriteria')->where('kode', 'C5')->value('id');
        if ($kriteriaId) {
            $alternatifs = DB::table('alternatif')
                ->where('kriteria_id', $kriteriaId)
                ->get();

            foreach ($alternatifs as $alt) {
                if ($alt->nilai <= 10) { // If it is a score (usually <= 10)
                    $rawPrice = 15000;
                    if ($alt->nilai == 1.0) {
                        $rawPrice = 2000;
                    } elseif ($alt->nilai == 2.0) {
                        $rawPrice = 5000;
                    } elseif ($alt->nilai == 3.0) {
                        $rawPrice = 10000;
                    } elseif ($alt->nilai == 4.0) {
                        $rawPrice = 12000;
                    }

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
