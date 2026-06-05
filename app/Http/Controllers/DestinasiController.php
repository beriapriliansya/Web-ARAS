<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DestinasiWisata;

class DestinasiController extends Controller
{
    /**
     * PUBLIC VIEW: Menampilkan daftar destinasi untuk pengunjung
     */
    public function index(Request $request)
    {
        // Auto-update Pantai Mutun image filename in DB to match user's file
        \Illuminate\Support\Facades\DB::table('destinasi_wisata')
            ->where('foto', 'pantai_mutun.jpg')
            ->update(['foto' => 'pantaimutun.jpg']);

        $query = DestinasiWisata::where('destinasi_wisata.status', 'aktif')
                    ->leftJoin('hasil_aras', 'destinasi_wisata.id', '=', 'hasil_aras.destinasi_id')
                    ->select('destinasi_wisata.*', 'hasil_aras.ranking', 'hasil_aras.nilai_k');

        // Filter by Kriteria (C1 - C5)
        $kriteriaCodes = ['C1', 'C2', 'C3', 'C4', 'C5'];
        foreach ($kriteriaCodes as $code) {
            $inputName = strtolower($code);
            if ($request->has($inputName) && $request->input($inputName) != '') {
                $filterVal = (float)$request->input($inputName);
                
                $query->whereHas('alternatif', function($q) use ($code, $filterVal) {
                    $q->whereHas('kriteria', function($qk) use ($code) {
                        $qk->where('kode', $code);
                    })->where(function($qsub) use ($code, $filterVal) {
                        // Match exact sub-criteria value
                        $qsub->where('nilai', $filterVal);
                        
                        // Or match corresponding raw values from the seeder
                        if ($code == 'C5') { // Harga Tiket
                            if (abs($filterVal - 0.25) < 0.001) {
                                $qsub->orWhereBetween('nilai', [5000, 15000]);
                            } elseif (abs($filterVal - 0.5) < 0.001) {
                                $qsub->orWhereBetween('nilai', [16000, 25000]);
                            } elseif (abs($filterVal - 0.75) < 0.001) {
                                $qsub->orWhereBetween('nilai', [26000, 35000]);
                            } elseif (abs($filterVal - 1.0) < 0.001) {
                                $qsub->orWhere('nilai', '>', 35000);
                            }
                        } elseif ($code == 'C1') { // Aksesibilitas
                            if (abs($filterVal - 0.25) < 0.001) {
                                $qsub->orWhereBetween('nilai', [1.0, 2.5]);
                            } elseif (abs($filterVal - 0.5) < 0.001) {
                                $qsub->orWhereBetween('nilai', [2.6, 3.7]);
                            } elseif (abs($filterVal - 0.75) < 0.001) {
                                $qsub->orWhereBetween('nilai', [3.8, 4.5]);
                            } elseif (abs($filterVal - 1.0) < 0.001) {
                                $qsub->orWhere('nilai', '>=', 4.6);
                            }
                        } elseif ($code == 'C2') { // Fasilitas
                            if (abs($filterVal - 0.25) < 0.001) {
                                $qsub->orWhere('nilai', '<=', 4.0);
                            } elseif (abs($filterVal - 0.5) < 0.001) {
                                $qsub->orWhereBetween('nilai', [4.1, 4.4]);
                            } elseif (abs($filterVal - 0.75) < 0.001) {
                                $qsub->orWhereBetween('nilai', [4.5, 4.7]);
                            } elseif (abs($filterVal - 1.0) < 0.001) {
                                $qsub->orWhere('nilai', '>=', 4.8);
                            }
                        } elseif ($code == 'C3') { // Kebersihan
                            if (abs($filterVal - 0.25) < 0.001) {
                                $qsub->orWhere('nilai', '<', 3.0);
                            } elseif (abs($filterVal - 0.5) < 0.001) {
                                $qsub->orWhereBetween('nilai', [3.0, 3.9]);
                            } elseif (abs($filterVal - 0.75) < 0.001) {
                                $qsub->orWhereBetween('nilai', [4.0, 4.4]);
                            } elseif (abs($filterVal - 1.0) < 0.001) {
                                $qsub->orWhere('nilai', '>=', 4.5);
                            }
                        } elseif ($code == 'C4') { // Keamanan
                            if (abs($filterVal - 0.25) < 0.001) {
                                $qsub->orWhere('nilai', '<', 3.0);
                            } elseif (abs($filterVal - 0.5) < 0.001) {
                                $qsub->orWhereBetween('nilai', [3.0, 3.9]);
                            } elseif (abs($filterVal - 0.75) < 0.001) {
                                $qsub->orWhereBetween('nilai', [4.0, 4.4]);
                            } elseif (abs($filterVal - 1.0) < 0.001) {
                                $qsub->orWhere('nilai', '>=', 4.5);
                            }
                        }
                    });
                });
            }
        }

        // Filter by kategori
        if ($request->has('kategori') && $request->kategori != '') {
            $query->where('destinasi_wisata.kategori', $request->kategori);
        }

        // Search
        if ($request->has('search') && $request->search != '') {
            $query->where('destinasi_wisata.nama', 'like', '%' . $request->search . '%');
        }

        // Order by ARAS ranking (if exists), then by name
        $query->orderByRaw('CASE WHEN hasil_aras.ranking IS NULL THEN 9999 ELSE hasil_aras.ranking END ASC')
              ->orderBy('destinasi_wisata.nama');

        $destinasi = $query->paginate(12);

        // Ambil list kategori untuk filter sidebar
        $kategoriList = DestinasiWisata::select('kategori')->distinct()->pluck('kategori');

        // Ambil data kriteria filter beserta sub-kriterianya
        $kriteriaFilter = \App\Models\Kriteria::with('subKriteria')
            ->whereIn('kode', $kriteriaCodes)
            ->get();

        return view('destinasi.index', compact('destinasi', 'kategoriList', 'kriteriaFilter'));
    }

    public function show($id)
    {
        // Auto-update Pantai Mutun image filename in DB to match user's file
        \Illuminate\Support\Facades\DB::table('destinasi_wisata')
            ->where('foto', 'pantai_mutun.jpg')
            ->update(['foto' => 'pantaimutun.jpg']);

        // Relasi dimuat agar halaman detail lengkap
        $destinasi = DestinasiWisata::with(['ulasan.user', 'alternatif.kriteria'])->findOrFail($id);

        $aksesibilitasAlternatif = $destinasi->alternatif->first(function($alt) {
            return $alt->kriteria && $alt->kriteria->kode === 'C1';
        });

        $nilaiC1 = $aksesibilitasAlternatif ? (float)$aksesibilitasAlternatif->nilai : null;
        $jarakText = 'Tidak diketahui';
        if ($nilaiC1 !== null) {
            if ($nilaiC1 >= 50.0) {
                $jarakText = $nilaiC1 . ' km';
            } elseif ($nilaiC1 === 0.25 || ($nilaiC1 >= 1.0 && $nilaiC1 <= 2.5)) {
                $jarakText = '50 - 60 km';
            } elseif ($nilaiC1 === 0.50 || ($nilaiC1 >= 2.6 && $nilaiC1 <= 3.7)) {
                $jarakText = '61 - 70 km';
            } elseif ($nilaiC1 === 0.75 || ($nilaiC1 >= 3.8 && $nilaiC1 <= 4.5)) {
                $jarakText = '71 - 80 km';
            } elseif ($nilaiC1 === 1.00 || $nilaiC1 >= 4.6) {
                $jarakText = '> 80 km';
            } else {
                $jarakText = $nilaiC1 . ' km';
            }
        }

        return view('destinasi.show', compact('destinasi', 'jarakText'));
    }
}
