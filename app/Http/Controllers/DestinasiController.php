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

        $query = DestinasiWisata::where('status', 'aktif');

        // Filter by kategori
        if ($request->has('kategori') && $request->kategori != '') {
            $query->where('kategori', $request->kategori);
        }

        // Search
        if ($request->has('search') && $request->search != '') {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        $destinasi = $query->latest()->paginate(12);

        // Ambil list kategori untuk filter sidebar
        $kategoriList = DestinasiWisata::select('kategori')->distinct()->pluck('kategori');

        return view('destinasi.index', compact('destinasi', 'kategoriList'));
    }

    /**
     * PUBLIC VIEW: Menampilkan detail satu destinasi
     */
    public function show($id)
    {
        // Auto-update Pantai Mutun image filename in DB to match user's file
        \Illuminate\Support\Facades\DB::table('destinasi_wisata')
            ->where('foto', 'pantai_mutun.jpg')
            ->update(['foto' => 'pantaimutun.jpg']);

        // Relasi dimuat agar halaman detail lengkap
        $destinasi = DestinasiWisata::with(['ulasan.user'])->findOrFail($id);

        return view('destinasi.show', compact('destinasi'));
    }
}
