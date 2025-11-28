<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\DestinasiWisata;
use App\Models\Booking;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage; // Tambahan untuk hapus gambar

class AdminController extends Controller
{
    /**
     * Menampilkan Dashboard Super Admin
     */
    public function dashboard()
    {
        // 1. Statistik
        $totalUser = User::where('role', 'user')->count();
        $totalAdmin = User::where('role', 'admin')->count();
        $totalDestinasi = DestinasiWisata::count();
        $totalBooking = Booking::count();

        // 2. Data User
        $users = User::with('destinasi')
                    ->whereIn('role', ['admin', 'superadmin', 'user'])
                    ->latest()
                    ->paginate(5, ['*'], 'users_page'); // Kasih nama page biar gak bentrok

        // 3. Data Destinasi (Untuk Tabel & Dropdown)
        $listDestinasi = DestinasiWisata::latest()->paginate(5, ['*'], 'destinasi_page');

        return view('admin.dashboard', compact(
            'totalUser',
            'totalAdmin',
            'totalDestinasi',
            'totalBooking',
            'users',
            'listDestinasi'
        ));
    }

    /**
     * Menyimpan User Baru
     */
    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'role' => 'required|in:superadmin,admin,user',
            'destinasi_id' => 'nullable|required_if:role,admin|exists:destinasi_wisata,id',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'destinasi_id' => ($request->role === 'admin') ? $request->destinasi_id : null,
        ]);

        return redirect()->back()->with('success', 'User berhasil ditambahkan!');
    }

    /**
     * Menghapus User
     */
    public function destroyUser($id)
    {
        $user = User::findOrFail($id);
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak bisa menghapus akun sendiri!');
        }
        $user->delete();
        return back()->with('success', 'User berhasil dihapus.');
    }

    // ==========================================
    // TAMBAHAN: MANAJEMEN DESTINASI
    // ==========================================

    /**
     * Menyimpan Destinasi Baru (Simplifikasi untuk Admin Dashboard)
     */
    public function storeDestinasi(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string',
            'alamat' => 'required|string',
            'deskripsi' => 'required|string',
            'harga_tiket' => 'required|numeric',
            'status' => 'required|in:aktif,non-aktif',
            // Gambar opsional dulu biar ga ribet
        ]);

        DestinasiWisata::create([
            'nama' => $request->nama,
            'kategori' => $request->kategori,
            'alamat' => $request->alamat,
            'deskripsi' => $request->deskripsi,
            'harga_tiket' => $request->harga_tiket,
            'status' => $request->status,
            'latitude' => '-5.4297', // Default dummy
            'longitude' => '105.2625', // Default dummy
        ]);

        return redirect()->back()->with('success', 'Destinasi Wisata berhasil ditambahkan!');
    }

    /**
     * Menghapus Destinasi
     */
    public function destroyDestinasi($id)
    {
        $destinasi = DestinasiWisata::findOrFail($id);
        $destinasi->delete();
        return back()->with('success', 'Destinasi berhasil dihapus.');
    }
}
