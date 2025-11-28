<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilAras extends Model
{
    use HasFactory;

    protected $table = 'hasil_aras';

    protected $fillable = [
        'destinasi_id',
        'nilai_s',        // Nilai Optimality Function (S)
        'nilai_k',        // Nilai Degree of Utility (K) - Ini yang dipakai untuk ranking
        'ranking',
    ];

    /**
     * Relasi ke Destinasi
     */
    public function destinasi()
    {
        return $this->belongsTo(DestinasiWisata::class, 'destinasi_id');
    }

    /**
     * Scope untuk mengurutkan berdasarkan ranking terbaik (1, 2, 3...)
     */
    public function scopeOrderByRanking($query)
    {
        return $query->orderBy('ranking', 'asc');
    }
}
