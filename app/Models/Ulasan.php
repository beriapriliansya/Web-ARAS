<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ulasan extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database
     */
    protected $table = 'ulasan';

    /**
     * Kolom yang bisa diisi mass assignment
     */
    protected $fillable = [
        'user_id',
        'destinasi_id',
        'booking_id',
        'rating',
        'komentar',
        'foto_ulasan',
        'tanggal_kunjungan',
        'status',
        'helpful_count',
    ];

    /**
     * Kolom yang di-cast ke tipe data tertentu
     */
    protected $casts = [
        'foto_ulasan' => 'array', // JSON ke array
        'tanggal_kunjungan' => 'date',
        'rating' => 'integer',
        'helpful_count' => 'integer',
    ];

    /**
     * Relasi ke tabel users (many to one)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke tabel destinasi_wisata (many to one)
     */
    public function destinasi()
    {
        return $this->belongsTo(DestinasiWisata::class, 'destinasi_id');
    }

    /**
     * Relasi ke Booking
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    /**
     * Scope: Filter approved ulasan
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope: Filter pending ulasan
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope: Order by helpful count
     */
    public function scopeMostHelpful($query)
    {
        return $query->orderBy('helpful_count', 'desc');
    }

    /**
     * Scope: Order by latest
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Scope: Filter by rating
     */
    public function scopeByRating($query, $rating)
    {
        return $query->where('rating', $rating);
    }

    /**
     * Accessor: Get rating stars
     */
    public function getRatingStarsAttribute()
    {
        return str_repeat('⭐', $this->rating);
    }

    /**
     * Accessor: Get rating label
     */
    public function getRatingLabelAttribute()
    {
        return match($this->rating) {
            5 => 'Luar Biasa',
            4 => 'Bagus',
            3 => 'Cukup',
            2 => 'Kurang',
            1 => 'Buruk',
            default => 'N/A',
        };
    }

    /**
     * Accessor: Check if has photos
     */
    public function getHasPhotosAttribute()
    {
        return !empty($this->foto_ulasan);
    }

    /**
     * Accessor: Get photo count
     */
    public function getPhotoCountAttribute()
    {
        return is_array($this->foto_ulasan) ? count($this->foto_ulasan) : 0;
    }
}
