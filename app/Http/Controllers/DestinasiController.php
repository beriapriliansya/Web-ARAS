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

        // Filter by kategori
        if ($request->has('kategori') && $request->kategori != '') {
            $query->where('destinasi_wisata.kategori', $request->kategori);
        }

        // Search
        if ($request->has('search') && $request->search != '') {
            $query->where('destinasi_wisata.nama', 'like', '%' . $request->search . '%');
        }

        // Filter by skor kriteria alternatif
        $kriteria = \App\Models\Kriteria::all();
        foreach ($kriteria as $k) {
            $paramName = 'kriteria_' . $k->id;
            if ($request->has($paramName) && $request->input($paramName) != '') {
                $val = (float)$request->input($paramName);
                $query->whereExists(function ($q) use ($k, $val) {
                    $q->select(\Illuminate\Support\Facades\DB::raw(1))
                      ->from('alternatif')
                      ->whereColumn('alternatif.destinasi_id', 'destinasi_wisata.id')
                      ->where('alternatif.kriteria_id', $k->id);
                    if ($k->tipe == 'benefit') {
                        $q->where('alternatif.nilai', '>=', $val);
                    } else {
                        $q->where('alternatif.nilai', '<=', $val);
                    }
                });
            }
        }

        // Order by ARAS ranking (if exists), then by name
        $query->orderByRaw('CASE WHEN hasil_aras.ranking IS NULL THEN 9999 ELSE hasil_aras.ranking END ASC')
              ->orderBy('destinasi_wisata.nama');

        $destinasi = $query->paginate(12);

        // Ambil list kategori untuk filter sidebar
        $kategoriList = DestinasiWisata::select('kategori')->distinct()->pluck('kategori');

        return view('destinasi.index', compact('destinasi', 'kategoriList', 'kriteria'));
    }

    public function show($id)
    {
        // Auto-update Pantai Mutun image filename in DB to match user's file
        \Illuminate\Support\Facades\DB::table('destinasi_wisata')
            ->where('foto', 'pantai_mutun.jpg')
            ->update(['foto' => 'pantaimutun.jpg']);

        // Relasi dimuat agar halaman detail lengkap
        $destinasi = DestinasiWisata::with(['alternatif.kriteria'])->findOrFail($id);

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
