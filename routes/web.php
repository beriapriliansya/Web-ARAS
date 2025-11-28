<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DestinasiController;
use App\Http\Controllers\ArasController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application.
|
*/

// ==========================================
// HOME & STATIC PAGES
// ==========================================

// Halaman Home (Landing Page)
Route::get('/', [HomeController::class, 'index'])->name('home');

// Halaman Tentang
Route::get('/tentang', [HomeController::class, 'tentang'])->name('tentang');

// Halaman Kontak
Route::get('/kontak', [HomeController::class, 'kontak'])->name('kontak');

// ==========================================
// DESTINASI WISATA ROUTES
// ==========================================

// Resource route untuk CRUD Destinasi
// Ini akan generate 7 routes otomatis:
// GET    /destinasi              -> index    (list semua destinasi)
// GET    /destinasi/create       -> create   (form tambah)
// POST   /destinasi              -> store    (simpan data baru)
// GET    /destinasi/{id}         -> show     (detail destinasi)
// GET    /destinasi/{id}/edit    -> edit     (form edit)
// PUT    /destinasi/{id}         -> update   (update data)
// DELETE /destinasi/{id}         -> destroy  (hapus data)
Route::resource('destinasi', DestinasiController::class);

// ==========================================
// METODE ARAS ROUTES
// ==========================================

// Halaman utama ARAS
Route::get('/aras', [ArasController::class, 'index'])->name('aras.index');

// Proses perhitungan ARAS
Route::post('/aras/hitung', [ArasController::class, 'hitung'])->name('aras.hitung');

// Halaman ranking hasil ARAS
Route::get('/aras/ranking', [ArasController::class, 'ranking'])->name('aras.ranking');

// Detail hasil ARAS per destinasi
Route::get('/aras/detail/{id}', [ArasController::class, 'detail'])->name('aras.detail');

// ==========================================
// API ROUTES (untuk AJAX)
// ==========================================

// API untuk mendapatkan koordinat destinasi (untuk Google Maps)
Route::get('/api/destinasi/{id}/koordinat', function($id) {
    $destinasi = \App\Models\DestinasiWisata::findOrFail($id);
    return response()->json([
        'latitude' => $destinasi->latitude,
        'longitude' => $destinasi->longitude,
        'nama' => $destinasi->nama,
    ]);
})->name('api.destinasi.koordinat');

// API untuk mendapatkan semua destinasi (untuk maps)
Route::get('/api/destinasi/all', function() {
    $destinasi = \App\Models\DestinasiWisata::aktif()->get();
    return response()->json($destinasi);
})->name('api.destinasi.all');
