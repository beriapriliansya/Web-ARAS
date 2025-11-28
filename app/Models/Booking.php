<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database (opsional jika nama tabelnya jamak 'bookings')
     */
    protected $table = 'bookings';

    /**
     * Kolom yang boleh diisi secara massal (Mass Assignment).
     */
    protected $fillable = [
        'user_id',
        'destinasi_id',
        'kode_booking',
        'tanggal_kunjungan',
        'jumlah_tiket',
        'total_harga',
        'status',           // pending, confirmed, completed, cancelled
        'bukti_pembayaran', // path file gambar
        'catatan',          // opsional, jika ada
    ];

    /**
     * Tipe data untuk casting atribut tertentu.
     * Membuat tanggal_kunjungan otomatis jadi objek Carbon (Date).
     */
    protected $casts = [
        'tanggal_kunjungan' => 'date',
        'total_harga' => 'integer',
        'jumlah_tiket' => 'integer',
    ];

    /**
     * Relasi: Booking milik satu User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi: Booking memiliki satu tujuan Destinasi Wisata.
     */
    public function destinasi()
    {
        // Pastikan nama model DestinasiWisata sesuai dengan file yang kamu punya
        return $this->belongsTo(DestinasiWisata::class, 'destinasi_id');
    }
}
