<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fasilitas;
use Illuminate\Http\Request;

class FasilitasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $fasilitas = Fasilitas::latest()->paginate(15);
        return view('admin.fasilitas.index', compact('fasilitas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_fasilitas' => 'required|string|max:100|unique:fasilitas,nama_fasilitas',
        ], [
            'nama_fasilitas.required' => 'Nama fasilitas wajib diisi.',
            'nama_fasilitas.unique' => 'Nama fasilitas sudah ada.',
            'nama_fasilitas.max' => 'Nama fasilitas maksimal 100 karakter.',
        ]);

        Fasilitas::create([
            'nama_fasilitas' => trim($request->nama_fasilitas),
        ]);

        return redirect()->route('admin.fasilitas.index')
            ->with('success', 'Fasilitas berhasil ditambahkan!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $fasilitas = Fasilitas::findOrFail($id);

        $request->validate([
            'nama_fasilitas' => 'required|string|max:100|unique:fasilitas,nama_fasilitas,' . $id,
        ], [
            'nama_fasilitas.required' => 'Nama fasilitas wajib diisi.',
            'nama_fasilitas.unique' => 'Nama fasilitas sudah ada.',
            'nama_fasilitas.max' => 'Nama fasilitas maksimal 100 karakter.',
        ]);

        $fasilitas->update([
            'nama_fasilitas' => trim($request->nama_fasilitas),
        ]);

        return redirect()->route('admin.fasilitas.index')
            ->with('success', 'Fasilitas berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $fasilitas = Fasilitas::findOrFail($id);
        $fasilitas->delete();

        return redirect()->route('admin.fasilitas.index')
            ->with('success', 'Fasilitas berhasil dihapus!');
    }
}
