<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DestinasiWisata;
use App\Models\Kriteria;

class DestinasiController extends Controller
{
    /**
     * Display a listing of the resource.
     * Menampilkan daftar semua destinasi (PUBLIC - untuk user biasa)
     */
    public function index(Request $request)
    {
        $query = DestinasiWisata::aktif();

        // Filter by kategori jika ada
        if ($request->has('kategori') && $request->kategori != '') {
            $query->kategori($request->kategori);
        }

        // Search jika ada keyword
        if ($request->has('search') && $request->search != '') {
            $query->search($request->search);
        }

        // Ambil data dengan pagination
        $destinasi = $query->latest()->paginate(12);

        // Ambil semua kategori untuk filter
        $kategoriList = DestinasiWisata::select('kategori')
            ->distinct()
            ->pluck('kategori');

        return view('destinasi.index', compact('destinasi', 'kategoriList'));
    }

    /**
     * ADMIN: Display a listing of all destinasi
     */
    public function adminIndex(Request $request)
    {
        $query = DestinasiWisata::query();

        if ($request->has('kategori') && $request->kategori != '') {
            $query->kategori($request->kategori);
        }

        if ($request->has('search') && $request->search != '') {
            $query->search($request->search);
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $destinasi = $query->latest()->paginate(12);
        $kategoriList = DestinasiWisata::select('kategori')->distinct()->pluck('kategori');

        return view('admin.destinasi.index', compact('destinasi', 'kategoriList'));
    }

    /**
     * Show the form for creating a new resource.
     * Menampilkan form tambah destinasi (ADMIN ONLY)
     */
    public function create()
    {
        return view('admin.destinasi.create');
    }

    /**
     * Store a newly created resource in storage.
     * Menyimpan destinasi baru ke database
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'nama' => 'required|string|max:200',
            'deskripsi' => 'required|string',
            'alamat' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'kategori' => 'required|in:Alam,Pantai,Gunung,Air Terjun,Budaya,Kuliner,Religi,Edukasi',
            'harga_tiket' => 'required|numeric|min:0',
            'jam_buka' => 'nullable|date_format:H:i',
            'jam_tutup' => 'nullable|date_format:H:i',
            'telepon' => 'nullable|string|max:20',
            'website' => 'nullable|url|max:255',
            'foto' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ]);

        // Handle upload foto
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $namaFoto = time() . '_' . $foto->getClientOriginalName();
            $foto->move(public_path('images/destinasi'), $namaFoto);
            $validated['foto'] = $namaFoto;
        }

        // Handle fasilitas (checkbox)
        $validated['fasilitas'] = $request->input('fasilitas', []);

        // Simpan ke database
        DestinasiWisata::create($validated);

        return redirect()->route('admin.destinasi.index')
            ->with('success', 'Destinasi wisata berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     * Menampilkan detail destinasi (PUBLIC)
     */
    public function show($id)
    {
        $destinasi = DestinasiWisata::with(['alternatif.kriteria', 'hasilAras', 'ulasan.user'])
            ->findOrFail($id);

        return view('destinasi.show', compact('destinasi'));
    }

    /**
     * ADMIN: Display the specified resource.
     */
    public function adminShow($id)
    {
        $destinasi = DestinasiWisata::with(['alternatif.kriteria', 'hasilAras', 'ulasan.user'])
            ->findOrFail($id);

        return view('admin.destinasi.show', compact('destinasi'));
    }

    /**
     * Show the form for editing the specified resource.
     * Menampilkan form edit destinasi (ADMIN ONLY)
     */
    public function edit($id)
    {
        $destinasi = DestinasiWisata::findOrFail($id);
        return view('admin.destinasi.edit', compact('destinasi'));
    }

    /**
     * Update the specified resource in storage.
     * Update data destinasi
     */
    public function update(Request $request, $id)
    {
        $destinasi = DestinasiWisata::findOrFail($id);

        // Validasi
        $validated = $request->validate([
            'nama' => 'required|string|max:200',
            'deskripsi' => 'required|string',
            'alamat' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'kategori' => 'required|in:Alam,Pantai,Gunung,Air Terjun,Budaya,Kuliner,Religi,Edukasi',
            'harga_tiket' => 'required|numeric|min:0',
            'jam_buka' => 'nullable|date_format:H:i',
            'jam_tutup' => 'nullable|date_format:H:i',
            'telepon' => 'nullable|string|max:20',
            'website' => 'nullable|url|max:255',
            'foto' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ]);

        // Handle upload foto baru
        if ($request->hasFile('foto')) {
            // Hapus foto lama
            if ($destinasi->foto && file_exists(public_path('images/destinasi/' . $destinasi->foto))) {
                unlink(public_path('images/destinasi/' . $destinasi->foto));
            }

            $foto = $request->file('foto');
            $namaFoto = time() . '_' . $foto->getClientOriginalName();
            $foto->move(public_path('images/destinasi'), $namaFoto);
            $validated['foto'] = $namaFoto;
        }

        // Handle fasilitas
        $validated['fasilitas'] = $request->input('fasilitas', []);

        // Update database
        $destinasi->update($validated);

        return redirect()->route('admin.destinasi.show', $id)
            ->with('success', 'Destinasi wisata berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     * Hapus destinasi (soft delete) (ADMIN ONLY)
     */
    public function destroy($id)
    {
        $destinasi = DestinasiWisata::findOrFail($id);
        $destinasi->delete(); // Soft delete

        return redirect()->route('admin.destinasi.index')
            ->with('success', 'Destinasi wisata berhasil dihapus!');
    }
}
