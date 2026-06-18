<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DestinasiWisata;
use App\Models\Kriteria;
use App\Models\HasilAras;

class HomeController extends Controller
{
    public function index()
    {
        // 1. Data Statistik Sederhana
        $totalDestinasi = DestinasiWisata::count();
        $totalKriteria = Kriteria::count();
        $totalUser = \App\Models\User::count();
        $totalHasilAras = HasilAras::count();

        // 2. Data Top 3 Destinasi berdasarkan Hasil ARAS (Ranking 1-3)
        // Pastikan tabel 'hasil_aras' sudah ada datanya. Kalau kosong, bagian ini tidak akan tampil error.
        $topDestinasi = [];
        try {
            $topDestinasi = HasilAras::whereHas('destinasi')
                ->with('destinasi')
                ->orderBy('ranking', 'asc') // Urutkan dari ranking 1
                ->limit(3)
                ->get();
        } catch (\Exception $e) {
            // Jika tabel belum ada atau error, biarkan array kosong agar tidak crash
            $topDestinasi = collect([]);
        }

        // 3. Data Destinasi Terbaru (Limit 3 atau 6)
        $destinasiTerbaru = DestinasiWisata::where('status', 'aktif') // Jika ada kolom status
            ->latest()
            ->limit(3)
            ->get();

        // Oper semua variabel ke view 'home'
        return view('home', compact(
            'totalDestinasi',
            'totalKriteria',
            'totalUser',
            'totalHasilAras',
            'topDestinasi',
            'destinasiTerbaru'
        ));
    }

    public function tentang()
    {
        return view('tentang');
    }

    public function kontak()
    {
        return view('kontak');
    }
}
