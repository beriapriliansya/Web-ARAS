<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DestinasiController; // Controller Public (Read Only)
use App\Http\Controllers\ArasController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\NewsController; // Controller Public News
use App\Http\Controllers\Admin\NewsController as AdminNewsController; // Controller Admin News

// Import Controller Admin Destinasi dengan Alias agar tidak bentrok
use App\Http\Controllers\Admin\DestinasiController as AdminDestinasiController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ==========================================
// AUTHENTICATION ROUTES (Laravel Breeze)
// ==========================================
require __DIR__.'/auth.php';

// ==========================================
// PUBLIC ROUTES (Tidak perlu login)
// ==========================================

// Home & Static Pages
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::redirect('/home', '/');
Route::get('/tentang', [HomeController::class, 'tentang'])->name('tentang');
Route::get('/kontak', [HomeController::class, 'kontak'])->name('kontak');

// Berita (News) - Tampilan Publik
Route::get('/news', [NewsController::class, 'index'])->name('news.index');

// Destinasi - HANYA LIHAT (Read Only untuk public)
Route::get('/destinasi', [DestinasiController::class, 'index'])->name('destinasi.index');

// API untuk maps
Route::get('/api/destinasi/{id}/koordinat', function($id) {
    $destinasi = \App\Models\DestinasiWisata::findOrFail($id);
    return response()->json([
        'latitude' => $destinasi->latitude,
        'longitude' => $destinasi->longitude,
        'nama' => $destinasi->nama,
    ]);
})->name('api.destinasi.koordinat');

Route::get('/api/destinasi/all', function() {
    $destinasi = \App\Models\DestinasiWisata::aktif()->get();
    return response()->json($destinasi);
})->name('api.destinasi.all');


// ==========================================
// ADMIN ROUTES (Manajemen Berita) <--- BLOK YANG DIBUAT KHUSUS DAN AMAN
// ==========================================
// Middleware 'can:manage-news' perlu Policy/Gate diimplementasi di App\Providers\AuthServiceProvider
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Route::resource otomatis membuat route untuk CRUD berita
    Route::resource('news', AdminNewsController::class);
});


// ==========================================
// USER AUTHENTICATED ROUTES (Harus login)
// ==========================================
Route::middleware(['auth'])->group(function () {

    // Detail Wisata (Membutuhkan Login)
    Route::get('/destinasi/{id}', [DestinasiController::class, 'show'])->name('destinasi.show');

    // Detail Berita (Membutuhkan Login)
    Route::get('/news/{slug}', [NewsController::class, 'show'])->name('news.show');

    // Hasil Rekomendasi & Ranking ARAS (Membutuhkan Login)
    Route::get('/aras/ranking', [ArasController::class, 'ranking'])->name('aras.ranking');
    Route::get('/rekomendasi', [ArasController::class, 'rekomendasiForm'])->name('aras.rekomendasi.form');
    Route::post('/rekomendasi', [ArasController::class, 'rekomendasiHitung'])->name('aras.rekomendasi.hitung');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');



    // Likes & Comments pada Berita (News)
    Route::post('/news/{id}/like', [NewsController::class, 'toggleLike'])->name('news.like');
    Route::post('/news/{id}/comment', [NewsController::class, 'storeComment'])->name('news.comment');
    Route::delete('/news/comment/{id}', [NewsController::class, 'destroyComment'])->name('news.comment.destroy');

    // Notifikasi
    Route::get('/notifications', function() {
        \App\Models\UserNotification::ensureTableExists();
        $notifications = \App\Models\UserNotification::where('user_id', auth()->id())->latest()->paginate(15);
        return view('profile.notifications', compact('notifications'));
    })->name('notifications.index');

    Route::post('/notifications/read-all', function() {
        \App\Models\UserNotification::ensureTableExists();
        \App\Models\UserNotification::where('user_id', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
        return redirect()->back()->with('success', 'Semua notifikasi berhasil ditandai sebagai dibaca.');
    })->name('notifications.readAll');

    Route::post('/notifications/{id}/read', function($id) {
        \App\Models\UserNotification::ensureTableExists();
        $notification = \App\Models\UserNotification::where('user_id', auth()->id())->findOrFail($id);
        $notification->update(['read_at' => now()]);
        return redirect()->back()->with('success', 'Notifikasi berhasil ditandai sebagai dibaca.');
    })->name('notifications.read');
});

// ==========================================
// ADMIN ROUTES (Superadmin & Admin Destinasi) - Route Sisanya
// ==========================================
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    // 1. Dashboard Utama
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // 2. Manajemen User (CRUD)
    Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
    Route::put('/users/{id}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{id}', [AdminController::class, 'destroyUser'])->name('users.destroy');

    // 3. Manajemen Destinasi (CRUD Lengkap)
    // Menggunakan AdminDestinasiController (bukan yang public)
    // Except 'create', 'edit' karena kita pakai Modal di halaman index
    Route::resource('destinasi', App\Http\Controllers\Admin\DestinasiController::class);

    // 3.1 Manajemen Fasilitas (CRUD)
    Route::resource('fasilitas', App\Http\Controllers\Admin\FasilitasController::class);

    // 4. ARAS Management
    Route::get('/aras', [ArasController::class, 'index'])->name('aras.index');
    Route::post('/aras/hitung', [ArasController::class, 'hitung'])->name('aras.hitung');
    Route::get('/aras/kriteria-list', [ArasController::class, 'kriteriaList'])->name('aras.kriteria.list');
    Route::get('/aras/kriteria', [ArasController::class, 'editKriteria'])->name('aras.kriteria.edit');
    Route::post('/aras/kriteria', [ArasController::class, 'updateKriteria'])->name('aras.updateKriteria');
    Route::get('/aras/penilaian', [ArasController::class, 'penilaian'])->name('aras.penilaian');
    Route::post('/aras/penilaian', [ArasController::class, 'storePenilaian'])->name('aras.penilaian.store');
    Route::get('/aras/cetak', [ArasController::class, 'cetakLaporan'])->name('aras.cetak');
    Route::get('/aras/panduan', [ArasController::class, 'panduanKonversi'])->name('aras.panduan');

    // Manajemen Nilai Alternatif per Destinasi
    Route::get('/destinasi/{id}/nilai', [App\Http\Controllers\Admin\DestinasiController::class, 'editNilai'])->name('destinasi.nilai.edit');
    Route::post('/destinasi/{id}/nilai', [App\Http\Controllers\Admin\DestinasiController::class, 'updateNilai'])->name('destinasi.nilai.update');

});

