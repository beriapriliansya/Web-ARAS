<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilAras extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database
     */
    protected $table = 'hasil_aras';

    /**
     * Kolom yang bisa diisi mass assignment
     */
    protected $fillable = [
        'destinasi_id',
        'nilai_normalisasi',
        'nilai_optimal',
        'utilitas',
        'ranking',
        'persentase',
        'tanggal_hitung',
        'detail_perhitungan',
    ];

    /**
     * Kolom yang di-cast ke tipe data tertentu
     */
    protected $casts = [
        'nilai_normalisasi' => 'decimal:6',
        'nilai_optimal' => 'decimal:6',
        'utilitas' => 'decimal:6',
        'persentase' => 'decimal:2',
        'tanggal_hitung' => 'datetime',
        'detail_perhitungan' => 'array', // JSON ke array
    ];

    /**
     * Relasi ke tabel destinasi_wisata (many to one)
     */
    public function destinasi()
    {
        return $this->belongsTo(DestinasiWisata::class, 'destinasi_id');
    }

    /**
     * Scope: Order by ranking (best to worst)
     */
    public function scopeOrderByRanking($query)
    {
        return $query->orderBy('ranking', 'asc');
    }

    /**
     * Scope: Order by utilitas (highest to lowest)
     */
    public function scopeOrderByUtilitas($query)
    {
        return $query->orderBy('utilitas', 'desc');
    }

    /**
     * Scope: Get top N destinations
     */
    public function scopeTopDestinations($query, $limit = 10)
    {
        return $query->orderBy('ranking', 'asc')->limit($limit);
    }

    /**
     * Accessor: Get ranking label with medal emoji
     */
    public function getRankingLabelAttribute()
    {
        return match($this->ranking) {
            1 => '🥇 Ranking 1',
            2 => '🥈 Ranking 2',
            3 => '🥉 Ranking 3',
            default => '🏅 Ranking ' . $this->ranking,
        };
    }

    /**
     * Accessor: Get utilitas percentage formatted
     */
    public function getUtilitasPersenAttribute()
    {
        return number_format($this->utilitas * 100, 2) . '%';
    }

    /**
     * Accessor: Get performance category
     */
    public function getKategoriPerformaAttribute()
    {
        $utilitas = $this->utilitas;

        return match(true) {
            $utilitas >= 0.9 => 'Sangat Baik',
            $utilitas >= 0.7 => 'Baik',
            $utilitas >= 0.5 => 'Cukup',
            $utilitas >= 0.3 => 'Kurang',
            default => 'Sangat Kurang',
        };
    }
}
