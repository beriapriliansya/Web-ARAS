<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DestinasiWisata;
use App\Models\Kriteria;
use App\Models\Alternatif;
use App\Models\HasilAras;
use App\Models\SubKriteria;
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

        // Ambil hasil perhitungan terakhir jika ada
        $hasil = HasilAras::with('destinasi')->orderBy('ranking')->get();

        return view('admin.aras.index', compact('kriteria', 'destinasi', 'hasil'));
    }

    /**
     * [ADMIN] Proses Hitung Metode ARAS & Simpan ke DB
     */
    public function hitung()
    {
        $destinasi = DestinasiWisata::aktif()->get();
        $kriteria = Kriteria::all();

        if ($destinasi->isEmpty() || $kriteria->isEmpty()) {
            return back()->with('error', 'Data Destinasi atau Kriteria masih kosong!');
        }

        // Jalankan perhitungan ARAS
        $result = $this->runArasCalculation($destinasi, $kriteria);

        DB::beginTransaction();
        try {
            HasilAras::query()->delete(); // Hapus hasil lama

            $rank = 1;
            foreach ($result['hasilSorted'] as $id => $nilaiK) {
                HasilAras::create([
                    'destinasi_id' => $id,
                    'nilai_s'      => $result['nilaiS'][$id],
                    'nilai_k'      => $nilaiK,
                    'ranking'      => $rank++
                ]);
            }
            DB::commit();
            return back()->with('success', 'Perhitungan ARAS selesai! Ranking di database telah diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan hitung: ' . $e->getMessage());
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
            return redirect()->route('admin.aras.index')->with('success', 'Bobot dan tipe kriteria berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui kriteria: ' . $e->getMessage());
        }
    }

    /**
     * [ADMIN] Halaman Kelola Sub Kriteria (CRUD)
     */
    public function subkriteria(Request $request)
    {
        // 1. Auto-run migration if table does not exist
        if (!\Illuminate\Support\Facades\Schema::hasTable('sub_kriteria')) {
            try {
                \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
            } catch (\Exception $e) {
                // If migration fails, run direct SQL query to create the table
                \Illuminate\Support\Facades\DB::statement("
                    CREATE TABLE IF NOT EXISTS `sub_kriteria` (
                        `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                        `kriteria_id` bigint unsigned NOT NULL,
                        `keterangan` varchar(255) NOT NULL,
                        `nilai` decimal(5,2) NOT NULL,
                        `created_at` timestamp NULL DEFAULT NULL,
                        `updated_at` timestamp NULL DEFAULT NULL,
                        PRIMARY KEY (`id`),
                        FOREIGN KEY (`kriteria_id`) REFERENCES `kriteria` (`id`) ON DELETE CASCADE
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
                ");
            }
        }

        // 2. Auto-seed if table is empty
        if (\Illuminate\Support\Facades\DB::table('sub_kriteria')->count() == 0) {
            $c1 = Kriteria::where('kode', 'C1')->first();
            $c2 = Kriteria::where('kode', 'C2')->first();
            $c3 = Kriteria::where('kode', 'C3')->first();
            $c4 = Kriteria::where('kode', 'C4')->first();
            $c5 = Kriteria::where('kode', 'C5')->first();
            $c6 = Kriteria::where('kode', 'C6')->first();

            $seeds = [];
            if ($c5) {
                $seeds[] = ['kriteria_id' => $c5->id, 'keterangan' => '> Rp 35.000', 'nilai' => 1.0];
                $seeds[] = ['kriteria_id' => $c5->id, 'keterangan' => 'Rp 26.000 - Rp 35.000', 'nilai' => 0.75];
                $seeds[] = ['kriteria_id' => $c5->id, 'keterangan' => 'Rp 16.000 - Rp 25.000', 'nilai' => 0.5];
                $seeds[] = ['kriteria_id' => $c5->id, 'keterangan' => 'Rp 5.000 - Rp 15.000', 'nilai' => 0.25];
            }
            if ($c1) {
                $seeds[] = ['kriteria_id' => $c1->id, 'keterangan' => '> 80 km', 'nilai' => 1.0];
                $seeds[] = ['kriteria_id' => $c1->id, 'keterangan' => '71 - 80 km', 'nilai' => 0.75];
                $seeds[] = ['kriteria_id' => $c1->id, 'keterangan' => '61 - 70 km', 'nilai' => 0.5];
                $seeds[] = ['kriteria_id' => $c1->id, 'keterangan' => '50 - 60 km', 'nilai' => 0.25];
            }
            if ($c2) {
                $seeds[] = ['kriteria_id' => $c2->id, 'keterangan' => '> 8', 'nilai' => 1.0];
                $seeds[] = ['kriteria_id' => $c2->id, 'keterangan' => '7', 'nilai' => 0.75];
                $seeds[] = ['kriteria_id' => $c2->id, 'keterangan' => '6', 'nilai' => 0.5];
                $seeds[] = ['kriteria_id' => $c2->id, 'keterangan' => '< 5', 'nilai' => 0.25];
            }
            if ($c3) {
                $seeds[] = ['kriteria_id' => $c3->id, 'keterangan' => 'Sangat Bersih', 'nilai' => 1.0];
                $seeds[] = ['kriteria_id' => $c3->id, 'keterangan' => 'Bersih', 'nilai' => 0.75];
                $seeds[] = ['kriteria_id' => $c3->id, 'keterangan' => 'Cukup Bersih', 'nilai' => 0.5];
                $seeds[] = ['kriteria_id' => $c3->id, 'keterangan' => 'Kurang Bersih', 'nilai' => 0.25];
            }
            if ($c4) {
                $seeds[] = ['kriteria_id' => $c4->id, 'keterangan' => 'Sangat Aman', 'nilai' => 1.0];
                $seeds[] = ['kriteria_id' => $c4->id, 'keterangan' => 'Aman', 'nilai' => 0.75];
                $seeds[] = ['kriteria_id' => $c4->id, 'keterangan' => 'Cukup Aman', 'nilai' => 0.5];
                $seeds[] = ['kriteria_id' => $c4->id, 'keterangan' => 'Kurang Aman', 'nilai' => 0.25];
            }
            if ($c6) {
                $seeds[] = ['kriteria_id' => $c6->id, 'keterangan' => 'Sangat Ramai', 'nilai' => 1.0];
                $seeds[] = ['kriteria_id' => $c6->id, 'keterangan' => 'Ramai', 'nilai' => 0.75];
                $seeds[] = ['kriteria_id' => $c6->id, 'keterangan' => 'Cukup Ramai', 'nilai' => 0.5];
                $seeds[] = ['kriteria_id' => $c6->id, 'keterangan' => 'Sepi', 'nilai' => 0.25];
            }
            foreach ($seeds as $s) {
                \App\Models\SubKriteria::create($s);
            }
        }

        $kriteria = Kriteria::all();
        
        // Filter by Kriteria Induk if selected in dropdown
        $filterKriteriaId = $request->input('kriteria_filter');
        $query = \App\Models\SubKriteria::with('kriteria');
        if ($filterKriteriaId) {
            $query->where('kriteria_id', $filterKriteriaId);
        }
        $subKriteria = $query->orderBy('kriteria_id')->get();

        return view('admin.aras.subkriteria', compact('kriteria', 'subKriteria', 'filterKriteriaId'));
    }

    /**
     * Store new Sub Kriteria
     */
    public function storeSubkriteria(Request $request)
    {
        $request->validate([
            'kriteria_id' => 'required|exists:kriteria,id',
            'keterangan' => 'required|string|max:255',
            'nilai' => 'required|numeric|min:0|max:100',
        ]);

        \App\Models\SubKriteria::create([
            'kriteria_id' => $request->kriteria_id,
            'keterangan' => $request->keterangan,
            'nilai' => $request->nilai,
        ]);

        return back()->with('success', 'Sub Kriteria berhasil ditambahkan!');
    }

    /**
     * Update existing Sub Kriteria
     */
    public function updateSubkriteria(Request $request, $id)
    {
        $sub = \App\Models\SubKriteria::findOrFail($id);

        $request->validate([
            'kriteria_id' => 'required|exists:kriteria,id',
            'keterangan' => 'required|string|max:255',
            'nilai' => 'required|numeric|min:0|max:100',
        ]);

        $sub->update([
            'kriteria_id' => $request->kriteria_id,
            'keterangan' => $request->keterangan,
            'nilai' => $request->nilai,
        ]);

        return back()->with('success', 'Sub Kriteria berhasil diperbarui!');
    }

    /**
     * Delete Sub Kriteria
     */
    public function destroySubkriteria($id)
    {
        $sub = \App\Models\SubKriteria::findOrFail($id);
        $sub->delete();

        return back()->with('success', 'Sub Kriteria berhasil dihapus.');
    }

    /**
     * [ADMIN] Halaman Kelola Penilaian Alternatif (Dropdown Opsi)
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

        // Get all sub criteria for dropdowns
        $subKriteria = SubKriteria::orderBy('nilai', 'desc')->get()->groupBy('kriteria_id');

        return view('admin.aras.penilaian', compact('destinasi', 'kriteria', 'selectedDestinasi', 'alternatifValues', 'subKriteria', 'selectedDestinasiId'));
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
                $subKriteriaId = $request->input('nilai_' . $k->id);
                $rawNilai = $request->input('nilai_' . $k->id . '_raw');

                if ($subKriteriaId) {
                    $sub = SubKriteria::findOrFail($subKriteriaId);
                    
                    // Update or create in alternatif table
                    Alternatif::updateOrCreate(
                        [
                            'destinasi_id' => $request->destinasi_id,
                            'kriteria_id' => $k->id,
                        ],
                        [
                            'nilai' => $sub->nilai,
                            'catatan' => 'Diberikan opsi sub-kriteria: ' . $sub->keterangan,
                        ]
                    );
                } elseif ($rawNilai !== null) {
                    // Fallback to raw value
                    Alternatif::updateOrCreate(
                        [
                            'destinasi_id' => $request->destinasi_id,
                            'kriteria_id' => $k->id,
                        ],
                        [
                            'nilai' => $rawNilai,
                            'catatan' => 'Diberikan nilai manual',
                        ]
                    );
                }
            }
            DB::commit();
            return back()->with('success', 'Penilaian berhasil disimpan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan penilaian: ' . $e->getMessage());
        }
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
        $hasil = HasilAras::with('destinasi')
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
        // Ambil ranking default sebagai referensi awal
        $hasilDefault = HasilAras::with('destinasi')->orderBy('ranking')->get();

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
