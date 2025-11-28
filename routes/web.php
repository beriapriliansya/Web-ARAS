<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DestinasiController;
use App\Http\Controllers\ArasController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\UlasanController;
use App\Http\Controllers\ProfileController;

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
Route::get('/tentang', [HomeController::class, 'tentang'])->name('tentang');
Route::get('/kontak', [HomeController::class, 'kontak'])->name('kontak');

// Destinasi - HANYA LIHAT (Read Only untuk public)
Route::get('/destinasi', [DestinasiController::class, 'index'])->name('destinasi.index');
Route::get('/destinasi/{id}', [DestinasiController::class, 'show'])->name('destinasi.show');

// ARAS - Public view
Route::get('/aras/ranking', [ArasController::class, 'ranking'])->name('aras.ranking');

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
// USER AUTHENTICATED ROUTES (Harus login)
// ==========================================
Route::middleware(['auth'])->group(function () {

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Booking Tiket (Harus login)
    Route::get('/booking', [BookingController::class, 'index'])->name('booking.index');
    Route::get('/booking/create/{destinasi_id}', [BookingController::class, 'create'])->name('booking.create');
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/booking/{id}', [BookingController::class, 'show'])->name('booking.show');
    Route::post('/booking/{id}/payment', [BookingController::class, 'uploadPayment'])->name('booking.upload_payment');
    Route::post('/booking/{id}/cancel', [BookingController::class, 'cancel'])->name('booking.cancel');

    // Ulasan/Rating (Harus punya booking completed)
    Route::get('/ulasan/create/{booking_id}', [UlasanController::class, 'create'])->name('ulasan.create');
    Route::post('/ulasan', [UlasanController::class, 'store'])->name('ulasan.store');

});

// ==========================================
// ADMIN ROUTES (Hanya Admin Destinasi)
// ==========================================
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');

    // Route untuk Manajemen User (CRUD)
    Route::post('/users', [App\Http\Controllers\AdminController::class, 'storeUser'])->name('users.store');
    Route::delete('/users/{id}', [App\Http\Controllers\AdminController::class, 'destroyUser'])->name('users.destroy');

    // Dashboard
    // Route::get('/dashboard', function() {
    //     $totalDestinasi = \App\Models\DestinasiWisata::count();
    //     $destinasiAktif = \App\Models\DestinasiWisata::where('status', 'aktif')->count();
    //     $totalKriteria = \App\Models\Kriteria::count();
    //     $totalUser = \App\Models\User::where('role', 'user')->count();
    //     $totalBooking = \App\Models\Booking::count();
    //     $bookingPending = \App\Models\Booking::where('status', 'pending')->count();
// 
    //     $bookingsTerbaru = \App\Models\Booking::with(['user', 'destinasi'])
    //         ->latest()->limit(10)->get();
// 
    //     $topDestinasi = \App\Models\HasilAras::with('destinasi')
    //         ->orderByRanking()->limit(5)->get();
// 
    //     return view('admin.dashboard', compact(
    //         'totalDestinasi', 'destinasiAktif', 'totalKriteria',
    //         'totalUser', 'totalBooking', 'bookingPending',
    //         'bookingsTerbaru', 'topDestinasi'
    //     ));
    // })->name('dashboard');

    // CRUD Destinasi (Admin only)
    Route::resource('destinasi', DestinasiController::class)->except(['index', 'show']);
    Route::get('destinasi', [DestinasiController::class, 'adminIndex'])->name('destinasi.index');
    Route::get('destinasi/{id}', [DestinasiController::class, 'adminShow'])->name('destinasi.show');

    // ARAS Management
    Route::get('/aras', [ArasController::class, 'index'])->name('aras.index');
    Route::post('/aras/hitung', [ArasController::class, 'hitung'])->name('aras.hitung');

    // Booking Management
    Route::get('/bookings', function() {
        $bookings = \App\Models\Booking::with(['user', 'destinasi'])->latest()->paginate(20);
        return view('admin.bookings.index', compact('bookings'));
    })->name('bookings.index');

    Route::get('/bookings/{id}', function($id) {
        $booking = \App\Models\Booking::with(['user', 'destinasi'])->findOrFail($id);
        return view('admin.bookings.show', compact('booking'));
    })->name('bookings.show');

    Route::post('/bookings/{id}/complete', function($id) {
        $booking = \App\Models\Booking::findOrFail($id);
        $booking->update(['status' => 'completed']);
        return redirect()->back()->with('success', 'Booking berhasil diselesaikan!');
    })->name('bookings.complete');

});


