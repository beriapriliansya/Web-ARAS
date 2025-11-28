<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DestinasiWisata;
use App\Models\Kriteria;
use App\Models\Alternatif;
use App\Models\HasilAras;
use Illuminate\Support\Facades\DB;

class ArasController extends Controller
{
    /**
     * Halaman utama perhitungan ARAS
     */
    public function index()
    {
        // Ambil data untuk ditampilkan
        $kriteria = Kriteria::aktif()->get();
        $destinasi = DestinasiWisata::aktif()->get();

        // Ambil hasil ARAS yang sudah dihitung
        $hasilAras = HasilAras::with('destinasi')
            ->orderByRanking()
            ->get();

        return view('aras.index', compact('kriteria', 'destinasi', 'hasilAras'));
    }

    /**
     * Hitung metode ARAS
     */
    public function hitung()
    {
        try {
            DB::beginTransaction();

            // STEP 1: Ambil semua data yang diperlukan
            $kriteria = Kriteria::aktif()->get();
            $destinasi = DestinasiWisata::aktif()->get();

            if ($kriteria->count() == 0 || $destinasi->count() == 0) {
                return redirect()->back()->with('error', 'Data kriteria atau destinasi tidak tersedia!');
            }

            // STEP 2: Buat Decision Matrix
            $decisionMatrix = $this->buatDecisionMatrix($destinasi, $kriteria);

            // STEP 3: Hitung nilai optimal (S0) untuk setiap kriteria
            $nilaiOptimal = $this->hitungNilaiOptimal($decisionMatrix, $kriteria);

            // STEP 4: Normalisasi Decision Matrix
            $matrixNormalisasi = $this->normalisasiMatrix($decisionMatrix, $nilaiOptimal, $kriteria);

            // STEP 5: Hitung Nilai Terbobot
            $nilaiTerbobot = $this->hitungNilaiTerbobot($matrixNormalisasi, $kriteria);

            // STEP 6: Hitung Optimality Function (Si)
            $nilaiSi = $this->hitungNilaiSi($nilaiTerbobot, $destinasi);

            // STEP 7: Hitung S0 (jumlah nilai optimal terbobot)
            $s0 = $this->hitungS0($nilaiOptimal, $kriteria);

            // STEP 8: Hitung Degree of Utility (Ki = Si / S0)
            $nilaiUtilitas = $this->hitungUtilitas($nilaiSi, $s0);

            // STEP 9: Ranking berdasarkan utilitas tertinggi
            $ranking = $this->hitungRanking($nilaiUtilitas);

            // STEP 10: Simpan hasil ke database
            $this->simpanHasil($ranking, $nilaiSi, $s0, $nilaiUtilitas, $destinasi);

            DB::commit();

            return redirect()->route('aras.index')
                ->with('success', 'Perhitungan ARAS berhasil! Data telah diupdate.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * STEP 2: Buat Decision Matrix
     * Format: [destinasi_id][kriteria_id] = nilai
     */
    private function buatDecisionMatrix($destinasi, $kriteria)
    {
        $matrix = [];

        foreach ($destinasi as $dest) {
            foreach ($kriteria as $krit) {
                $alternatif = Alternatif::where('destinasi_id', $dest->id)
                    ->where('kriteria_id', $krit->id)
                    ->first();

                $matrix[$dest->id][$krit->id] = $alternatif ? $alternatif->nilai : 0;
            }
        }

        return $matrix;
    }

    /**
     * STEP 3: Hitung Nilai Optimal (S0) untuk setiap kriteria
     * Benefit: Ambil nilai maksimum
     * Cost: Ambil nilai minimum
     */
    private function hitungNilaiOptimal($decisionMatrix, $kriteria)
    {
        $nilaiOptimal = [];

        foreach ($kriteria as $krit) {
            $nilaiKolom = array_column($decisionMatrix, $krit->id);

            if ($krit->tipe == 'benefit') {
                // Untuk benefit, ambil nilai maksimum
                $nilaiOptimal[$krit->id] = max($nilaiKolom);
            } else {
                // Untuk cost, ambil nilai minimum
                $nilaiOptimal[$krit->id] = min($nilaiKolom);
            }
        }

        return $nilaiOptimal;
    }

    /**
     * STEP 4: Normalisasi Decision Matrix
     * Rumus: xij / sum(xij) untuk benefit
     *        (1/xij) / sum(1/xij) untuk cost
     */
    private function normalisasiMatrix($decisionMatrix, $nilaiOptimal, $kriteria)
    {
        $matrixNormalisasi = [];

        foreach ($kriteria as $krit) {
            // Hitung jumlah kolom untuk kriteria ini
            $nilaiKolom = array_column($decisionMatrix, $krit->id);

            if ($krit->tipe == 'benefit') {
                $sumKolom = array_sum($nilaiKolom) + $nilaiOptimal[$krit->id];
            } else {
                // Untuk cost, kita inverse dulu
                $inverseKolom = array_map(function($x) {
                    return $x != 0 ? 1/$x : 0;
                }, $nilaiKolom);
                $inverseOptimal = $nilaiOptimal[$krit->id] != 0 ? 1/$nilaiOptimal[$krit->id] : 0;
                $sumKolom = array_sum($inverseKolom) + $inverseOptimal;
            }

            foreach ($decisionMatrix as $destId => $nilaiKriteria) {
                $nilai = $nilaiKriteria[$krit->id];

                if ($krit->tipe == 'benefit') {
                    $matrixNormalisasi[$destId][$krit->id] = $sumKolom != 0 ? $nilai / $sumKolom : 0;
                } else {
                    $inverse = $nilai != 0 ? 1/$nilai : 0;
                    $matrixNormalisasi[$destId][$krit->id] = $sumKolom != 0 ? $inverse / $sumKolom : 0;
                }
            }
        }

        return $matrixNormalisasi;
    }

    /**
     * STEP 5: Hitung Nilai Terbobot
     * Rumus: normalisasi * bobot
     */
    private function hitungNilaiTerbobot($matrixNormalisasi, $kriteria)
    {
        $nilaiTerbobot = [];

        foreach ($matrixNormalisasi as $destId => $nilaiKriteria) {
            foreach ($kriteria as $krit) {
                $nilaiNormalisasi = $nilaiKriteria[$krit->id];
                $nilaiTerbobot[$destId][$krit->id] = $nilaiNormalisasi * $krit->bobot;
            }
        }

        return $nilaiTerbobot;
    }

    /**
     * STEP 6: Hitung Optimality Function (Si)
     * Rumus: Jumlah semua nilai terbobot per destinasi
     */
    private function hitungNilaiSi($nilaiTerbobot, $destinasi)
    {
        $nilaiSi = [];

        foreach ($destinasi as $dest) {
            $nilaiSi[$dest->id] = array_sum($nilaiTerbobot[$dest->id] ?? []);
        }

        return $nilaiSi;
    }

    /**
     * STEP 7: Hitung S0 (nilai optimal)
     * S0 = Jumlah (nilai_optimal * bobot) untuk semua kriteria
     */
    private function hitungS0($nilaiOptimal, $kriteria)
    {
        $s0 = 0;

        foreach ($kriteria as $krit) {
            $nilaiOpt = $nilaiOptimal[$krit->id];

            if ($krit->tipe == 'cost') {
                $nilaiOpt = $nilaiOpt != 0 ? 1/$nilaiOpt : 0;
            }

            // Normalisasi nilai optimal (simplified)
            $s0 += $nilaiOpt * $krit->bobot;
        }

        return $s0;
    }

    /**
     * STEP 8: Hitung Degree of Utility (Ki)
     * Rumus: Ki = Si / S0
     */
    private function hitungUtilitas($nilaiSi, $s0)
    {
        $utilitas = [];

        foreach ($nilaiSi as $destId => $si) {
            $utilitas[$destId] = $s0 != 0 ? $si / $s0 : 0;
        }

        return $utilitas;
    }

    /**
     * STEP 9: Hitung Ranking
     * Urutkan berdasarkan utilitas tertinggi
     */
    private function hitungRanking($nilaiUtilitas)
    {
        // Sort descending by utilitas
        arsort($nilaiUtilitas);

        $ranking = [];
        $rank = 1;

        foreach ($nilaiUtilitas as $destId => $utilitas) {
            $ranking[$destId] = $rank;
            $rank++;
        }

        return $ranking;
    }

    /**
     * STEP 10: Simpan hasil ke database
     */
    private function simpanHasil($ranking, $nilaiSi, $s0, $nilaiUtilitas, $destinasi)
    {
        // Hapus hasil lama
        HasilAras::truncate();

        foreach ($destinasi as $dest) {
            $destId = $dest->id;

            // Hitung persentase (utilitas * 100)
            $persentase = $nilaiUtilitas[$destId] * 100;

            HasilAras::create([
                'destinasi_id' => $destId,
                'nilai_normalisasi' => $nilaiSi[$destId],
                'nilai_optimal' => $s0,
                'utilitas' => $nilaiUtilitas[$destId],
                'ranking' => $ranking[$destId],
                'persentase' => $persentase,
                'tanggal_hitung' => now(),
                'detail_perhitungan' => [
                    'si' => $nilaiSi[$destId],
                    's0' => $s0,
                    'ki' => $nilaiUtilitas[$destId],
                    'ranking' => $ranking[$destId],
                ],
            ]);
        }
    }

    /**
     * Tampilkan detail perhitungan
     */
    public function detail($id)
    {
        $hasilAras = HasilAras::with('destinasi')->findOrFail($id);

        return view('aras.detail', compact('hasilAras'));
    }

    /**
     * Tampilkan hasil ranking
     */
    public function ranking()
    {
        $hasilAras = HasilAras::with('destinasi')
            ->orderByRanking()
            ->get();

        return view('aras.ranking', compact('hasilAras'));
    }
}
