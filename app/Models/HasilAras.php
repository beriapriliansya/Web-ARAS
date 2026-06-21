<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilAras extends Model
{
    use HasFactory;

    protected $table = 'hasil_aras';

    protected $fillable = [
        'destinasi_id',
        'nilai_s',        // Nilai Optimality Function (S)
        'nilai_k',        // Nilai Degree of Utility (K) - Ini yang dipakai untuk ranking
        'ranking',
    ];

    /**
     * Relasi ke Destinasi
     */
    public function destinasi()
    {
        return $this->belongsTo(DestinasiWisata::class, 'destinasi_id');
    }

    /**
     * Scope untuk mengurutkan berdasarkan ranking terbaik (1, 2, 3...)
     */
    public function scopeOrderByRanking($query)
    {
        return $query->orderBy('ranking', 'asc');
    }

    /**
     * Recalculate ARAS ranking for all active destinations
     */
    public static function recalculate()
    {
        $destinasi = DestinasiWisata::aktif()->get();
        $kriteria = Kriteria::all();

        if ($destinasi->isEmpty() || $kriteria->isEmpty()) {
            self::query()->delete();
            return false;
        }

        // 1. Decision Matrix (X) & Optimal Values (X0)
        $matriks = [];
        $x0 = [];

        foreach ($kriteria as $k) {
            $nilaiKolom = [];
            foreach ($destinasi as $d) {
                $nilai = Alternatif::where('destinasi_id', $d->id)
                            ->where('kriteria_id', $k->id)
                            ->value('nilai');
                $nilai = $nilai !== null ? (float)$nilai : 0.0;
                $matriks[$d->id][$k->id] = $nilai;
                $nilaiKolom[] = $nilai;
            }

            if ($k->tipe == 'benefit') {
                $x0[$k->id] = count($nilaiKolom) > 0 ? max($nilaiKolom) : 0;
            } else {
                $filtered = array_filter($nilaiKolom);
                $x0[$k->id] = count($filtered) > 0 ? min($filtered) : 0;
            }
        }

        // 2. Normalization Matrix (R)
        $matriksR = [];
        foreach ($kriteria as $k) {
            $totalKolom = 0;
            if ($k->tipe == 'benefit') {
                $totalKolom = array_sum(array_column($matriks, $k->id)) + $x0[$k->id];
            } else {
                if ($x0[$k->id] > 0) {
                    $totalKolom += (1 / $x0[$k->id]);
                }
                foreach ($matriks as $d_id => $kols) {
                    if ($kols[$k->id] > 0) {
                        $totalKolom += (1 / $kols[$k->id]);
                    }
                }
            }

            if ($totalKolom == 0) {
                $totalKolom = 1.0;
            }

            if ($k->tipe == 'benefit') {
                $matriksR['A0'][$k->id] = $x0[$k->id] / $totalKolom;
            } else {
                $matriksR['A0'][$k->id] = ($x0[$k->id] > 0 ? (1 / $x0[$k->id]) : 0) / $totalKolom;
            }

            foreach ($destinasi as $d) {
                $nilaiAsli = $matriks[$d->id][$k->id];
                if ($k->tipe == 'benefit') {
                    $matriksR[$d->id][$k->id] = $nilaiAsli / $totalKolom;
                } else {
                    $val = ($nilaiAsli > 0) ? (1 / $nilaiAsli) : 0;
                    $matriksR[$d->id][$k->id] = $val / $totalKolom;
                }
            }
        }

        // 3. Weighted Matrix (V) & Optimality Function (S)
        $nilaiS = [];
        $S0 = 0;
        foreach ($kriteria as $k) {
            $weightedVal = $matriksR['A0'][$k->id] * (float)$k->bobot;
            $S0 += $weightedVal;
        }

        foreach ($destinasi as $d) {
            $Si = 0;
            foreach ($kriteria as $k) {
                $weightedVal = $matriksR[$d->id][$k->id] * (float)$k->bobot;
                $Si += $weightedVal;
            }
            $nilaiS[$d->id] = $Si;
        }

        // 4. Degree of Utility (K) & Ranking
        $hasilK = [];
        foreach ($nilaiS as $id => $Si) {
            $Ki = ($S0 > 0) ? $Si / $S0 : 0;
            $hasilK[$id] = $Ki;
        }

        arsort($hasilK);

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            self::query()->delete();
            $rank = 1;
            foreach ($hasilK as $id => $nilaiK) {
                self::create([
                    'destinasi_id' => $id,
                    'nilai_s'      => $nilaiS[$id],
                    'nilai_k'      => $nilaiK,
                    'ranking'      => $rank++
                ]);
            }
            \Illuminate\Support\Facades\DB::commit();
            return true;
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return false;
        }
    }
}
