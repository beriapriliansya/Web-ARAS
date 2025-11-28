<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DestinasiWisata;
use App\Models\Kriteria;
use App\Models\Alternatif; // Pastikan model ini menyimpan nilai kriteria per destinasi
use App\Models\HasilAras;
use Illuminate\Support\Facades\DB;

class ArasController extends Controller
{
    /**
     * [ADMIN] Halaman Utama Perhitungan
     * Menampilkan data kriteria dan tombol hitung
     */
    public function index()
    {
        $kriteria = Kriteria::all();
        $destinasi = DestinasiWisata::where('status', 'aktif')->count();

        // Ambil hasil perhitungan terakhir jika ada
        $hasil = HasilAras::with('destinasi')->orderBy('ranking')->get();

        return view('admin.aras.index', compact('kriteria', 'destinasi', 'hasil'));
    }

    /**
     * [ADMIN] Proses Hitung Metode ARAS
     */
    public function hitung()
    {
        // 1. Ambil Data
        $destinasi = DestinasiWisata::where('status', 'aktif')->get();
        $kriteria = Kriteria::all();

        // Cek kelengkapan data
        if ($destinasi->isEmpty() || $kriteria->isEmpty()) {
            return back()->with('error', 'Data Destinasi atau Kriteria masih kosong!');
        }

        // Cek apakah setiap destinasi sudah punya nilai alternatif
        // (Asumsi: Tabel 'alternatif' menyimpan nilai murni per kriteria)
        // Jika belum ada sistem input nilai alternatif, kamu harus membuatnya di menu Destinasi.

        // --- MULAI PERHITUNGAN ---

        // 2. Buat Matriks Keputusan (X) & Tentukan Nilai Optimal (X0)
        $matriks = [];
        $x0 = []; // Nilai optimal untuk baris ke-0 (A0)

        foreach ($kriteria as $k) {
            $nilaiKolom = [];

            // Ambil nilai setiap destinasi untuk kriteria ini
            foreach ($destinasi as $d) {
                // Ambil nilai dari tabel alternatif
                $nilai = Alternatif::where('destinasi_id', $d->id)
                            ->where('kriteria_id', $k->id)
                            ->value('nilai');

                // Jika nilai kosong, beri default (misal 0 atau nilai min)
                $nilai = $nilai ?? 0;

                $matriks[$d->id][$k->id] = $nilai;
                $nilaiKolom[] = $nilai;
            }

            // Tentukan X0 berdasarkan tipe kriteria
            if ($k->tipe == 'benefit') {
                $x0[$k->id] = max($nilaiKolom); // Benefit cari MAX
            } else {
                // Cost cari MIN (Hati-hati jika 0)
                $x0[$k->id] = min(array_filter($nilaiKolom)) ?? 0;
            }
        }

        // 3. Normalisasi Matriks (R)
        $matriksR = [];

        foreach ($kriteria as $k) {
            $pembagi = 0;

            // Hitung sigma (pembagi) sesuai rumus ARAS
            if ($k->tipe == 'benefit') {
                // Sigma Xij + X0j
                $totalKolom = array_sum(array_column($matriks, $k->id)) + $x0[$k->id];
            } else {
                // Untuk Cost: Sigma (1/Xij) + (1/X0j)
                $totalKolom = 0;
                if ($x0[$k->id] > 0) $totalKolom += (1 / $x0[$k->id]);
                foreach ($matriks as $d_id => $kols) {
                    if ($kols[$k->id] > 0) $totalKolom += (1 / $kols[$k->id]);
                }
            }

            // Hitung nilai normalisasi per sel
            // Normalisasi A0 (Baris Optimal)
            if ($k->tipe == 'benefit') {
                $matriksR['A0'][$k->id] = $x0[$k->id] / $totalKolom;
            } else {
                $matriksR['A0'][$k->id] = (1 / $x0[$k->id]) / $totalKolom;
            }

            // Normalisasi Ai (Baris Destinasi)
            foreach ($destinasi as $d) {
                $nilaiAsli = $matriks[$d->id][$k->id];

                if ($k->tipe == 'benefit') {
                    $matriksR[$d->id][$k->id] = $nilaiAsli / $totalKolom;
                } else {
                    // Cost
                    $val = ($nilaiAsli > 0) ? (1 / $nilaiAsli) : 0;
                    $matriksR[$d->id][$k->id] = $val / $totalKolom;
                }
            }
        }

        // 4. Matriks Terbobot (D) & Nilai Fungsi Optimalitas (S)
        $nilaiS = [];

        // Hitung S0 (Optimality Function untuk A0)
        $S0 = 0;
        foreach ($kriteria as $k) {
            $bobot = $k->bobot; // Asumsi bobot dalam desimal (0.1, 0.2), jika persen bagi 100
            $S0 += $matriksR['A0'][$k->id] * $bobot;
        }

        // Hitung Si (Optimality Function untuk setiap Destinasi)
        foreach ($destinasi as $d) {
            $Si = 0;
            foreach ($kriteria as $k) {
                $bobot = $k->bobot;
                $Si += $matriksR[$d->id][$k->id] * $bobot;
            }
            $nilaiS[$d->id] = $Si;
        }

        // 5. Perhitungan Tingkat Utilitas (K) & Ranking
        $hasilAkhir = [];
        foreach ($nilaiS as $id => $Si) {
            $Ki = ($S0 > 0) ? $Si / $S0 : 0; // Rumus Degree of Utility
            $hasilAkhir[$id] = $Ki;
        }

        // Urutkan dari nilai K terbesar (Ranking 1)
        arsort($hasilAkhir);

        // 6. Simpan ke Database (Reset dulu data lama)
        DB::beginTransaction();
        try {
            HasilAras::query()->delete(); // Hapus hasil lama agar bersih

            $rank = 1;
            foreach ($hasilAkhir as $id => $nilaiK) {
                HasilAras::create([
                    'destinasi_id' => $id,
                    'nilai_s'      => $nilaiS[$id],
                    'nilai_k'      => $nilaiK, // Ini nilai akhir (Utility)
                    'ranking'      => $rank++
                ]);
            }
            DB::commit();
            return back()->with('success', 'Perhitungan ARAS selesai! Ranking telah diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan hitung: ' . $e->getMessage());
        }
    }

    /**
     * [PUBLIC] Halaman Ranking
     * Hanya menampilkan data dari tabel hasil_aras
     */
    public function ranking()
    {
        $hasil = HasilAras::with('destinasi')
                    ->orderBy('ranking', 'asc')
                    ->get();

        return view('aras.ranking', compact('hasil'));
    }
}
