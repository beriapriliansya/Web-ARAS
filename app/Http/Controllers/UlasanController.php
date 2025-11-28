<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Ulasan; // Pastikan model Ulasan dibuat
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UlasanController extends Controller
{
    /**
     * Menampilkan form pembuatan ulasan.
     * Hanya bisa diakses untuk booking yang statusnya 'completed'.
     */
    public function create($booking_id)
    {
        // Cari booking beserta data destinasinya
        $booking = Booking::with('destinasi')->findOrFail($booking_id);

        // 1. Validasi Keamanan: Pastikan booking ini milik user yang sedang login
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Anda tidak berhak mengakses halaman ini.');
        }

        // 2. Validasi Status: Pastikan status booking sudah 'completed'
        if ($booking->status !== 'completed') {
            return redirect()->route('booking.index')
                ->with('error', 'Anda hanya bisa memberikan ulasan setelah kunjungan selesai (Status: Completed).');
        }

        // 3. Validasi Duplikasi: Cek apakah ulasan untuk booking ini sudah ada
        $existingUlasan = Ulasan::where('booking_id', $booking_id)->first();
        if ($existingUlasan) {
            return redirect()->route('booking.index')
                ->with('warning', 'Anda sudah memberikan ulasan untuk pesanan ini sebelumnya.');
        }

        return view('ulasan.create', compact('booking'));
    }

    /**
     * Menyimpan ulasan baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'required|string|max:1000',
        ]);

        $booking = Booking::findOrFail($request->booking_id);

        // Validasi ulang di sisi backend sebelum simpan
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        if ($booking->status !== 'completed') {
            return back()->with('error', 'Booking belum selesai.');
        }

        // Simpan ke tabel ulasan
        Ulasan::create([
            'user_id' => Auth::id(),
            'destinasi_id' => $booking->destinasi_id,
            'booking_id' => $booking->id,
            'rating' => $request->rating,
            'komentar' => $request->komentar,
            'tanggal_ulasan' => now(),
        ]);

        return redirect()->route('destinasi.show', $booking->destinasi_id)
            ->with('success', 'Terima kasih atas ulasan Anda!');
    }
}
