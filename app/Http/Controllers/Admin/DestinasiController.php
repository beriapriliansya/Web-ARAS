<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DestinasiWisata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class DestinasiController extends Controller
{
    /**
     * Menampilkan daftar destinasi di halaman admin
     */
    public function index()
    {
        $destinasi = DestinasiWisata::latest()->paginate(10);
        return view('admin.destinasi.index', compact('destinasi'));
    }

    /**
     * Menampilkan DETAIL destinasi
     */
    public function show($id)
    {
        $destinasi = DestinasiWisata::with(['alternatif.kriteria', 'ulasan.user'])->findOrFail($id);
        return view('admin.destinasi.show', compact('destinasi'));
    }

    /**
     * Menampilkan FORM tambah destinasi baru
     * (Fungsi ini yang menyebabkan error jika hilang)
     */
    public function create()
    {
        // Menggunakan view yang sama dengan edit (reusable form)
        return view('admin.destinasi.create');
    }

    /**
     * Menyimpan destinasi baru ke database
     */
    public function store(Request $request)
    {
        $this->handleSave($request);

        return redirect()->route('admin.destinasi.index')
            ->with('success', 'Destinasi Wisata berhasil ditambahkan!');
    }

    /**
     * Menampilkan FORM edit untuk destinasi tertentu
     */
    public function edit($id)
    {
        $destinasi = DestinasiWisata::findOrFail($id);
        // Load view form dan kirim data $destinasi agar form terisi otomatis
        return view('admin.destinasi.edit', compact('destinasi'));
    }

    /**
     * Mengupdate data destinasi yang sudah ada
     */
    public function update(Request $request, $id)
    {
        $destinasi = DestinasiWisata::findOrFail($id);

        $this->handleSave($request, $destinasi);

        return redirect()->route('admin.destinasi.index')
            ->with('success', 'Data Destinasi berhasil diperbarui!');
    }

    /**
     * Menghapus destinasi dari database
     */
    public function destroy($id)
    {
        $destinasi = DestinasiWisata::findOrFail($id);

        // Hapus Foto Fisik jika ada
        if ($destinasi->foto && File::exists(public_path('images/destinasi/' . $destinasi->foto))) {
            File::delete(public_path('images/destinasi/' . $destinasi->foto));
        }

        $destinasi->delete();

        return redirect()->route('admin.destinasi.index')
            ->with('success', 'Destinasi berhasil dihapus.');
    }

    /**
     * Helper Function: Menangani Logika Simpan (Create & Update)
     */
    private function handleSave(Request $request, $destinasi = null)
    {
        // 1. Aturan Validasi
        $rules = [
            'nama' => 'required|string|max:200',
            'kategori' => 'required|string',
            'status' => 'required|in:aktif,non-aktif',
            'harga_tiket' => 'required|numeric|min:0',
            'alamat' => 'required|string',
            'deskripsi' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'jam_buka' => 'nullable',
            'jam_tutup' => 'nullable',
            'website' => 'nullable|url',
            'telepon' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,jpg,png|max:10240', // Max 10MB
        ];

        $validated = $request->validate($rules);

        // 2. Upload Foto Baru (Jika Ada)
        if ($request->hasFile('foto')) {
            // Jika Mode Update & Foto Lama Ada -> Hapus dulu foto lama
            if ($destinasi && $destinasi->foto && File::exists(public_path('images/destinasi/' . $destinasi->foto))) {
                File::delete(public_path('images/destinasi/' . $destinasi->foto));
            }

            // Simpan foto baru
            $foto = $request->file('foto');
            $namaFoto = time() . '_' . $foto->getClientOriginalName();
            $foto->move(public_path('images/destinasi'), $namaFoto);

            // Masukkan nama foto ke array data yang akan disimpan
            $validated['foto'] = $namaFoto;
        }

        // 3. Simpan ke Database
        if ($destinasi) {
            // Mode Update
            $destinasi->update($validated);
        } else {
            // Mode Create
            DestinasiWisata::create($validated);
        }
    }

    /**
     * Menampilkan form edit nilai kriteria (alternatif)
     */
    public function editNilai($id)
    {
        $destinasi = DestinasiWisata::findOrFail($id);
        $kriteria = \App\Models\Kriteria::all();
        
        // Ambil nilai alternatif yang sudah ada
        $alternatifValues = \App\Models\Alternatif::where('destinasi_id', $id)
            ->pluck('nilai', 'kriteria_id')
            ->toArray();

        // Ambil sub kriteria dikelompokkan berdasarkan kriteria_id
        $subKriteria = \App\Models\SubKriteria::orderBy('nilai', 'desc')->get()->groupBy('kriteria_id');
            
        return view('admin.destinasi.nilai', compact('destinasi', 'kriteria', 'alternatifValues', 'subKriteria'));
    }

    /**
     * Mengupdate nilai kriteria (alternatif)
     */
    public function updateNilai(Request $request, $id)
    {
        $destinasi = DestinasiWisata::findOrFail($id);
        $kriteria = \App\Models\Kriteria::all();

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            foreach ($kriteria as $k) {
                $subKriteriaId = $request->input('nilai_' . $k->id);
                $rawNilai = $request->input('nilai_' . $k->id . '_raw');

                if ($subKriteriaId) {
                    $sub = \App\Models\SubKriteria::findOrFail($subKriteriaId);
                    
                    \App\Models\Alternatif::updateOrCreate(
                        [
                            'destinasi_id' => $destinasi->id,
                            'kriteria_id' => $k->id,
                        ],
                        [
                            'nilai' => $sub->nilai,
                            'catatan' => 'Diberikan opsi sub-kriteria: ' . $sub->keterangan,
                        ]
                    );
                } elseif ($rawNilai !== null) {
                    \App\Models\Alternatif::updateOrCreate(
                        [
                            'destinasi_id' => $destinasi->id,
                            'kriteria_id' => $k->id,
                        ],
                        [
                            'nilai' => $rawNilai,
                            'catatan' => 'Diberikan nilai manual',
                        ]
                    );
                }
            }
            \Illuminate\Support\Facades\DB::commit();
            return redirect()->route('admin.destinasi.show', $destinasi->id)
                ->with('success', 'Nilai kriteria berhasil diperbarui!');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Gagal menyimpan nilai: ' . $e->getMessage());
        }
    }
}
