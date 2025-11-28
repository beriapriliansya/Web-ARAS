<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DestinasiWisata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File; // Import Facade File untuk hapus gambar

class DestinasiController extends Controller
{
    /**
     * Menampilkan daftar destinasi di halaman admin
     */
    public function index()
    {
        // Ambil data destinasi terbaru, paginate 10 per halaman
        $destinasi = DestinasiWisata::latest()->paginate(10);
        return view('admin.destinasi.index', compact('destinasi'));
    }

    /**
     * Menyimpan destinasi baru ke database
     */
    public function store(Request $request)
    {
        // Panggil fungsi helper handleSave untuk validasi & simpan
        $this->handleSave($request);

        return redirect()->route('admin.destinasi.index')
            ->with('success', 'Destinasi Wisata berhasil ditambahkan!');
    }

    /**
     * Mengupdate data destinasi yang sudah ada
     */
    public function update(Request $request, $id)
    {
        $destinasi = DestinasiWisata::findOrFail($id);

        // Panggil fungsi helper handleSave dengan parameter destinasi (mode update)
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
     * Menggabungkan logika agar tidak duplikat kode
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
            'foto' => 'nullable|image|mimes:jpeg,jpg,png|max:2048', // Max 2MB
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
}
