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
    public function index(Request $request)
    {
        $query = DestinasiWisata::query();

        // 1. Pencarian nama
        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        // 2. Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 3. Pengurutan (Sorting)
        $sort = $request->input('sort', 'latest');
        if ($sort === 'price_asc') {
            $query->orderBy('harga_tiket', 'asc');
        } elseif ($sort === 'price_desc') {
            $query->orderBy('harga_tiket', 'desc');
        } elseif ($sort === 'name_asc') {
            $query->orderBy('nama', 'asc');
        } else {
            $query->latest();
        }

        $destinasi = $query->paginate(9); // 9 per page fits the 3x3 grid beautifully
        return view('admin.destinasi.index', compact('destinasi'));
    }

    /**
     * Menampilkan DETAIL destinasi
     */
    public function show($id)
    {
        $destinasi = DestinasiWisata::with(['alternatif.kriteria'])->findOrFail($id);
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
        $destinasi = $this->handleSave($request);

        // Kirim notifikasi destinasi baru ke semua user
        \App\Models\UserNotification::ensureTableExists();
        $users = \App\Models\User::all();
        foreach ($users as $u) {
            \App\Models\UserNotification::create([
                'user_id' => $u->id,
                'type'    => 'destinasi',
                'title'   => 'Destinasi Wisata Baru',
                'message' => 'Destinasi air baru "' . $destinasi->nama . '" telah ditambahkan ke sistem. Yuk, lihat keindahannya!',
            ]);
        }

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

        // Kirim notifikasi pembaruan destinasi ke superadmin saja
        \App\Models\UserNotification::ensureTableExists();
        $users = \App\Models\User::where('role', 'superadmin')->get();
        foreach ($users as $u) {
            \App\Models\UserNotification::create([
                'user_id' => $u->id,
                'type'    => 'destinasi',
                'title'   => 'Pembaruan Informasi Destinasi',
                'message' => 'Informasi untuk destinasi wisata "' . $destinasi->nama . '" baru saja diperbarui oleh admin.',
            ]);
        }

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
            'status' => 'required|in:aktif,nonaktif',
            'harga_tiket' => 'required|numeric|min:0',
            'jarak' => 'nullable|integer|min:0',
            'alamat' => 'required|string',
            'deskripsi' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'jam_buka' => 'nullable',
            'jam_tutup' => 'nullable',
            'website' => 'nullable|string|max:255',
            'telepon' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,jpg,png|max:10240', // Max 10MB
            'fasilitas' => 'nullable|array',
            'fasilitas.*' => 'string|max:100',
        ];

        $validated = $request->validate($rules);

        // Assign facilities array directly
        $validated['fasilitas'] = $request->input('fasilitas', []);

        // Auto prepend protocol to website if it is filled but missing a scheme
        if (!empty($validated['website'])) {
            $website = trim($validated['website']);
            if (!preg_match("~^(?:f|ht)tps?://~i", $website)) {
                $website = "https://" . $website;
            }
            $validated['website'] = $website;
        }

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
            return $destinasi;
        } else {
            // Mode Create
            return DestinasiWisata::create($validated);
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
            
        return view('admin.destinasi.nilai', compact('destinasi', 'kriteria', 'alternatifValues'));
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
                $nilaiVal = $request->input('nilai_' . $k->id);

                if ($nilaiVal !== null) {
                    \App\Models\Alternatif::updateOrCreate(
                        [
                            'destinasi_id' => $destinasi->id,
                            'kriteria_id' => $k->id,
                        ],
                        [
                            'nilai' => $nilaiVal,
                            'catatan' => 'Diberikan nilai kriteria langsung',
                        ]
                    );
                }
            }
            \Illuminate\Support\Facades\DB::commit();

            // Kirim notifikasi pembaruan nilai alternatif ke superadmin saja
            \App\Models\UserNotification::ensureTableExists();
            $users = \App\Models\User::where('role', 'superadmin')->get();
            foreach ($users as $u) {
                \App\Models\UserNotification::create([
                    'user_id' => $u->id,
                    'type'    => 'destinasi',
                    'title'   => 'Pembaruan Nilai Alternatif',
                    'message' => 'Nilai kriteria / alternatif untuk destinasi "' . $destinasi->nama . '" baru saja diperbarui oleh admin.',
                ]);
            }

            return redirect()->route('admin.destinasi.show', $destinasi->id)
                ->with('success', 'Nilai kriteria berhasil diperbarui!');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Gagal menyimpan nilai: ' . $e->getMessage());
        }
    }
}
