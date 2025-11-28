<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\DestinasiWisata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BookingController extends Controller
{
    /**
     * Menampilkan daftar booking milik user yang sedang login.
     */
    public function index()
    {
        // Ambil booking milik user yang login, urutkan dari yang terbaru
        $bookings = Booking::where('user_id', Auth::id())
                            ->with('destinasi') // Eager load relasi destinasi
                            ->latest()
                            ->paginate(10);

        return view('booking.index', compact('bookings'));
    }

    /**
     * Menampilkan form booking untuk destinasi tertentu.
     */
    public function create($destinasi_id)
    {
        $destinasi = DestinasiWisata::findOrFail($destinasi_id);
        return view('booking.create', compact('destinasi'));
    }

    /**
     * Menyimpan data booking baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'destinasi_id' => 'required|exists:destinasi_wisata,id',
            'tanggal_kunjungan' => 'required|date|after:today',
            'jumlah_tiket' => 'required|integer|min:1',
        ]);

        $destinasi = DestinasiWisata::findOrFail($request->destinasi_id);

        // Hitung total harga otomatis (Harga Tiket x Jumlah)
        // Ini lebih aman daripada mengambil total_harga dari input user
        $totalHarga = $destinasi->harga_tiket * $request->jumlah_tiket;

        // Buat kode booking unik (Contoh: BKG-20231101-001)
        $kodeBooking = 'BKG-' . date('Ymd') . '-' . rand(100, 999);

        Booking::create([
            'user_id' => Auth::id(),
            'destinasi_id' => $destinasi->id,
            'kode_booking' => $kodeBooking,
            'tanggal_kunjungan' => $request->tanggal_kunjungan,
            'jumlah_tiket' => $request->jumlah_tiket,
            'total_harga' => $totalHarga,
            'status' => 'pending', // Default status
        ]);

        return redirect()->route('booking.index')
            ->with('success', 'Booking berhasil dibuat! Silakan lakukan pembayaran.');
    }

    /**
     * Menampilkan detail booking tertentu.
     */
    public function show($id)
    {
        // Pastikan booking tersebut milik user yang login
        $booking = Booking::where('user_id', Auth::id())->findOrFail($id);

        return view('booking.show', compact('booking'));
    }

    /**
     * Menangani upload bukti pembayaran.
     */
    public function uploadPayment(Request $request, $id)
    {
        $request->validate([
            'bukti_bayar' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Max 2MB
        ]);

        $booking = Booking::where('user_id', Auth::id())->findOrFail($id);

        if ($request->hasFile('bukti_bayar')) {
            // Hapus file lama jika ada (opsional, untuk hemat storage)
            if ($booking->bukti_pembayaran) {
                Storage::disk('public')->delete($booking->bukti_pembayaran);
            }

            // Simpan file baru ke folder public/payments
            $path = $request->file('bukti_bayar')->store('payments', 'public');

            // Update database
            $booking->update([
                'bukti_pembayaran' => $path,
                'status' => 'menunggu_konfirmasi' // Ubah status agar admin mengecek
            ]);
        }

        return redirect()->back()->with('success', 'Bukti pembayaran berhasil diupload!');
    }

    /**
     * Membatalkan booking (hanya jika status masih pending).
     */
    public function cancel($id)
    {
        $booking = Booking::where('user_id', Auth::id())->findOrFail($id);

        if ($booking->status == 'pending') {
            $booking->update(['status' => 'cancelled']);
            return redirect()->back()->with('success', 'Booking berhasil dibatalkan.');
        }

        return redirect()->back()->with('error', 'Booking tidak dapat dibatalkan karena sudah diproses.');
    }
}
