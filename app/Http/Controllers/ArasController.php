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
     * [ADMIN] Halaman Utama Perhitungan
     */
    public function index()
    {
        $kriteria = Kriteria::all();
        $destinasi = DestinasiWisata::aktif()->count();

        // Ambil hasil perhitungan terakhir jika ada (hanya jika destinasi tidak di-softdelete/dihapus)
        $hasil = HasilAras::whereHas('destinasi')
                    ->with('destinasi.alternatif')
                    ->orderBy('ranking')
                    ->get();

        return view('admin.aras.index', compact('kriteria', 'destinasi', 'hasil'));
    }

    /**
     * [ADMIN] Proses Hitung Metode ARAS & Simpan ke DB
     */
    public function hitung()
    {
        $success = HasilAras::recalculate();

        if ($success) {
            // Kirim notifikasi hasil perhitungan ARAS ke semua user
            \App\Models\UserNotification::ensureTableExists();
            $users = \App\Models\User::all();
            foreach ($users as $u) {
                \App\Models\UserNotification::create([
                    'user_id' => $u->id,
                    'type'    => 'perhitungan',
                    'title'   => 'Hasil Perankingan Diperbarui',
                    'message' => 'Kalkulasi Metode ARAS baru saja dijalankan oleh admin. Hasil rekomendasi peringkat wisata air terupdate telah diterbitkan!',
                ]);
            }

            return back()->with('success', 'Perhitungan ARAS selesai! Ranking di database telah diperbarui.');
        } else {
            return back()->with('error', 'Gagal melakukan perhitungan ARAS. Pastikan data kriteria dan destinasi terisi.');
        }
    }

    /**
     * [ADMIN] Halaman Daftar Kriteria (Read Only)
     */
    public function kriteriaList()
    {
        $kriteria = Kriteria::all();
        return view('admin.aras.kriteria_list', compact('kriteria'));
    }

    /**
     * [ADMIN] Halaman Kelola Kriteria (Edit Bobot & Tipe)
     */
    public function editKriteria()
    {
        $kriteria = Kriteria::all();
        return view('admin.aras.kriteria', compact('kriteria'));
    }

    /**
     * [ADMIN] Update Bobot & Tipe Kriteria
     */
    public function updateKriteria(Request $request)
    {
        $kriteria = Kriteria::all();

        // Validasi input
        $rules = [];
        $messages = [];
        foreach ($kriteria as $k) {
            $rules['bobot_' . $k->id] = 'required|numeric|min:0';
            $rules['tipe_' . $k->id] = 'required|in:benefit,cost';
            $messages['bobot_' . $k->id . '.required'] = 'Bobot kriteria ' . $k->nama_kriteria . ' wajib diisi.';
            $messages['bobot_' . $k->id . '.numeric'] = 'Bobot kriteria ' . $k->nama_kriteria . ' harus angka.';
            $messages['bobot_' . $k->id . '.min'] = 'Bobot kriteria ' . $k->nama_kriteria . ' minimal 0.';
        }

        $request->validate($rules, $messages);

        // Hitung total bobot
        $totalBobot = 0;
        foreach ($kriteria as $k) {
            $totalBobot += (float)$request->input('bobot_' . $k->id);
        }

        // Toleransi selisih float kecil (misal 0.999 - 1.001)
        // Kita dukung format persen (total = 100) atau desimal (total = 1.0)
        $isPercent = false;
        if (abs($totalBobot - 100) < 0.001) {
            $isPercent = true;
        } elseif (abs($totalBobot - 1.0) > 0.001) {
            return back()->with('error', 'Total bobot kriteria harus sama dengan 100% atau 1.0! Total input saat ini: ' . $totalBobot)
                ->withInput();
        }

        DB::beginTransaction();
        try {
            foreach ($kriteria as $k) {
                $bobotVal = (float)$request->input('bobot_' . $k->id);
                // Jika input dalam persen, ubah ke desimal untuk disimpan ke database
                if ($isPercent) {
                    $bobotVal = $bobotVal / 100;
                }

                $k->update([
                    'bobot' => $bobotVal,
                    'tipe' => $request->input('tipe_' . $k->id),
                ]);
            }
            DB::commit();

            // Auto recalculate ARAS ranking
            HasilAras::recalculate();

            return redirect()->route('admin.aras.index')->with('success', 'Bobot dan tipe kriteria berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui kriteria: ' . $e->getMessage());
        }
    }



    /**
     * [ADMIN] Halaman Kelola Penilaian Alternatif (Input Nilai Langsung)
     */
    public function penilaian(Request $request)
    {
        $destinasi = DestinasiWisata::aktif()->get();
        $kriteria = Kriteria::all();

        // Get selected destination
        $selectedDestinasiId = $request->input('destinasi_id');
        $selectedDestinasi = null;
        $alternatifValues = [];

        if ($selectedDestinasiId) {
            $selectedDestinasi = DestinasiWisata::findOrFail($selectedDestinasiId);
            
            // Get existing scores for this destination
            $alternatifValues = Alternatif::where('destinasi_id', $selectedDestinasiId)
                                    ->pluck('nilai', 'kriteria_id')
                                    ->toArray();
        }

        return view('admin.aras.penilaian', compact('destinasi', 'kriteria', 'selectedDestinasi', 'alternatifValues', 'selectedDestinasiId'));
    }

    /**
     * Store/Update Penilaian Alternatif
     */
    public function storePenilaian(Request $request)
    {
        $request->validate([
            'destinasi_id' => 'required|exists:destinasi_wisata,id',
        ]);

        $kriteria = Kriteria::all();

        DB::beginTransaction();
        try {
            foreach ($kriteria as $k) {
                $nilaiVal = $request->input('nilai_' . $k->id);

                if ($nilaiVal !== null) {
                    Alternatif::updateOrCreate(
                        [
                            'destinasi_id' => $request->destinasi_id,
                            'kriteria_id' => $k->id,
                        ],
                        [
                            'nilai' => $nilaiVal,
                            'catatan' => 'Diberikan nilai kriteria langsung',
                        ]
                    );
                }
            }
            DB::commit();

            // Auto recalculate ARAS ranking
            HasilAras::recalculate();

            // Kirim notifikasi pembaruan nilai alternatif ke superadmin saja
            $destinasiObj = DestinasiWisata::find($request->destinasi_id);
            if ($destinasiObj) {
                \App\Models\UserNotification::ensureTableExists();
                $users = \App\Models\User::where('role', 'superadmin')->get();
                foreach ($users as $u) {
                    \App\Models\UserNotification::create([
                        'user_id' => $u->id,
                        'type'    => 'destinasi',
                        'title'   => 'Pembaruan Nilai Alternatif',
                        'message' => 'Nilai kriteria / alternatif untuk destinasi "' . $destinasiObj->nama . '" baru saja diperbarui oleh admin.',
                    ]);
                }
            }

            return back()->with('success', 'Penilaian berhasil disimpan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan penilaian: ' . $e->getMessage());
        }
    }

    /**
     * [ADMIN] Halaman Panduan Konversi Nilai Kriteria
     */
    public function panduanKonversi()
    {
        return view('admin.aras.panduan');
    }

    /**
     * [ADMIN] Cetak Laporan Detail Perhitungan ARAS
     */
    public function cetakLaporan()
    {
        $destinasi = DestinasiWisata::aktif()->get();
        $kriteria = Kriteria::all();

        if ($destinasi->isEmpty() || $kriteria->isEmpty()) {
            return redirect()->route('admin.aras.index')->with('error', 'Data destinasi atau kriteria kosong!');
        }

        // Jalankan perhitungan untuk mendapatkan matriks lengkap
        $result = $this->runArasCalculation($destinasi, $kriteria);

        return view('admin.aras.cetak', compact('destinasi', 'kriteria', 'result'));
    }

    /**
     * [PUBLIC] Halaman Ranking Statis
     */
    public function ranking()
    {
        $hasil = HasilAras::whereHas('destinasi')
                    ->with('destinasi')
                    ->orderBy('ranking', 'asc')
                    ->get();

        return view('aras.ranking', compact('hasil'));
    }

    /**
     * [PUBLIC] Halaman Kalkulator Rekomendasi Dinamis
     */
    public function rekomendasiForm()
    {
        $kriteria = Kriteria::all();
        // Ambil ranking default sebagai referensi awal (hanya yang destinasinya aktif/ada)
        $hasilDefault = HasilAras::whereHas('destinasi')
                            ->with('destinasi')
                            ->orderBy('ranking')
                            ->get();

        return view('aras.rekomendasi', compact('kriteria', 'hasilDefault'));
    }

    /**
     * [PUBLIC] Hitung Rekomendasi Dinamis (In-Memory)
     */
    public function rekomendasiHitung(Request $request)
    {
        $kriteria = Kriteria::all();
        $destinasi = DestinasiWisata::aktif()->get();

        if ($destinasi->isEmpty() || $kriteria->isEmpty()) {
            return back()->with('error', 'Data Destinasi atau Kriteria masih kosong!');
        }

        // Validasi input bobot dari form
        $rules = [];
        foreach ($kriteria as $k) {
            $rules['weight_' . $k->id] = 'required|numeric|min:0';
        }
        $request->validate($rules);

        // Hitung total bobot inputan user
        $totalInputWeight = 0;
        $customWeightsInput = [];
        foreach ($kriteria as $k) {
            $val = (float)$request->input('weight_' . $k->id);
            $customWeightsInput[$k->id] = $val;
            $totalInputWeight += $val;
        }

        // Cek apakah total = 100% atau 1.0
        $isPercent = false;
        if (abs($totalInputWeight - 100) < 0.001) {
            $isPercent = true;
        } elseif (abs($totalInputWeight - 1.0) > 0.001) {
            return back()->with('error', 'Total bobot preferensi harus sama dengan 100% atau 1.0! Total saat ini: ' . $totalInputWeight)
                ->withInput();
        }

        // Jika input persen, konversi ke desimal untuk kalkulasi ARAS
        $customWeights = [];
        foreach ($customWeightsInput as $id => $val) {
            $customWeights[$id] = $isPercent ? ($val / 100) : $val;
        }

        // Jalankan kalkulasi ARAS in-memory dengan custom weights
        $result = $this->runArasCalculation($destinasi, $kriteria, $customWeights);

        // Bentuk data ranking untuk ditampilkan
        $hasilRekomendasi = [];
        $rank = 1;
        foreach ($result['hasilSorted'] as $id => $nilaiK) {
            $hasilRekomendasi[] = (object)[
                'destinasi' => $destinasi->firstWhere('id', $id),
                'nilai_s' => $result['nilaiS'][$id],
                'nilai_k' => $nilaiK,
                'ranking' => $rank++
            ];
        }

        return view('aras.rekomendasi', compact('kriteria', 'hasilRekomendasi', 'customWeightsInput'));
    }

    /**
     * Reusable ARAS calculation logic
     */
    private function runArasCalculation($destinasi, $kriteria, $customWeights = null)
    {
        // 1. Buat Matriks Keputusan (X) & Tentukan Nilai Optimal (X0)
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

                // Jika nilai kosong, beri default
                $nilai = $nilai !== null ? (float)$nilai : 0.0;

                $matriks[$d->id][$k->id] = $nilai;
                $nilaiKolom[] = $nilai;
            }

            // Tentukan X0 berdasarkan tipe kriteria
            if ($k->tipe == 'benefit') {
                $x0[$k->id] = count($nilaiKolom) > 0 ? max($nilaiKolom) : 0; // Benefit cari MAX
            } else {
                // Cost cari MIN (Hati-hati jika 0)
                $filtered = array_filter($nilaiKolom);
                $x0[$k->id] = count($filtered) > 0 ? min($filtered) : 0;
            }
        }

        // 2. Normalisasi Matriks (R)
        $matriksR = [];

        foreach ($kriteria as $k) {
            $totalKolom = 0;

            // Hitung sigma (pembagi) sesuai rumus ARAS
            if ($k->tipe == 'benefit') {
                // Sigma Xij + X0j
                $totalKolom = array_sum(array_column($matriks, $k->id)) + $x0[$k->id];
            } else {
                // Untuk Cost: Sigma (1/Xij) + (1/X0j)
                if ($x0[$k->id] > 0) {
                    $totalKolom += (1 / $x0[$k->id]);
                }
                foreach ($matriks as $d_id => $kols) {
                    if ($kols[$k->id] > 0) {
                        $totalKolom += (1 / $kols[$k->id]);
                    }
                }
            }

            // Jika total kolom 0, hindari pembagian dengan nol
            if ($totalKolom == 0) {
                $totalKolom = 1.0;
            }

            // Hitung nilai normalisasi per sel
            // Normalisasi A0 (Baris Optimal)
            if ($k->tipe == 'benefit') {
                $matriksR['A0'][$k->id] = $x0[$k->id] / $totalKolom;
            } else {
                $matriksR['A0'][$k->id] = ($x0[$k->id] > 0 ? (1 / $x0[$k->id]) : 0) / $totalKolom;
            }

            // Normalisasi Ai (Baris Destinasi)
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

        // 3. Matriks Terbobot (V) & Nilai Fungsi Optimalitas (S)
        $nilaiS = [];
        $matriksV = [];

        // Gunakan bobot kustom jika disediakan, jika tidak gunakan bobot default dari DB
        $bobotArray = [];
        foreach ($kriteria as $k) {
            if ($customWeights !== null && isset($customWeights[$k->id])) {
                $bobotArray[$k->id] = (float)$customWeights[$k->id];
            } else {
                $bobotArray[$k->id] = (float)$k->bobot;
            }
        }

        // Hitung S0 (Optimality Function untuk A0)
        $S0 = 0;
        foreach ($kriteria as $k) {
            $bobot = $bobotArray[$k->id];
            $weightedVal = $matriksR['A0'][$k->id] * $bobot;
            $matriksV['A0'][$k->id] = $weightedVal;
            $S0 += $weightedVal;
        }

        // Hitung Si (Optimality Function untuk setiap Destinasi)
        foreach ($destinasi as $d) {
            $Si = 0;
            foreach ($kriteria as $k) {
                $bobot = $bobotArray[$k->id];
                $weightedVal = $matriksR[$d->id][$k->id] * $bobot;
                $matriksV[$d->id][$k->id] = $weightedVal;
                $Si += $weightedVal;
            }
            $nilaiS[$d->id] = $Si;
        }

        // 4. Perhitungan Tingkat Utilitas (K) & Ranking
        $hasilK = [];
        foreach ($nilaiS as $id => $Si) {
            $Ki = ($S0 > 0) ? $Si / $S0 : 0; // Rumus Degree of Utility
            $hasilK[$id] = $Ki;
        }

        // Urutkan dari nilai K terbesar (Ranking 1)
        $hasilSorted = $hasilK;
        arsort($hasilSorted);

        return [
            'matriks' => $matriks,
            'x0' => $x0,
            'matriksR' => $matriksR,
            'matriksV' => $matriksV,
            'nilaiS' => $nilaiS,
            'S0' => $S0,
            'hasilSorted' => $hasilSorted,
            'bobotUsed' => $bobotArray
        ];
    }
}
