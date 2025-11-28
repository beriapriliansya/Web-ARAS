<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DestinasiWisata;
use App\Models\HasilAras;
use App\Models\Kriteria;

class HomeController extends Controller
{
    /**
     * Tampilkan halaman home
     */
    public function index()
    {
        // Ambil statistik untuk ditampilkan di home
        $totalDestinasi = DestinasiWisata::aktif()->count();
        $totalKriteria = Kriteria::aktif()->count();

        // Ambil top 3 destinasi berdasarkan ranking ARAS
        $topDestinasi = HasilAras::with('destinasi')
            ->orderByRanking()
            ->limit(3)
            ->get();

        // Ambil destinasi terbaru
        $destinasiTerbaru = DestinasiWisata::aktif()
            ->latest()
            ->limit(6)
            ->get();

        return view('home', compact(
            'totalDestinasi',
            'totalKriteria',
            'topDestinasi',
            'destinasiTerbaru'
        ));
    }

    /**
     * Tampilkan halaman tentang
     */
    public function tentang()
    {
        return view('tentang');
    }

    /**
     * Tampilkan halaman kontak
     */
    public function kontak()
    {
        return view('kontak');
    }
}
