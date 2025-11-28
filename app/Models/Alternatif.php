<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alternatif extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database
     */
    protected $table = 'alternatif';

    /**
     * Kolom yang bisa diisi mass assignment
     */
    protected $fillable = [
        'destinasi_id',
        'kriteria_id',
        'nilai',
        'catatan',
    ];

    /**
     * Kolom yang di-cast ke tipe data tertentu
     */
    protected $casts = [
        'nilai' => 'decimal:2',
    ];

    /**
     * Relasi ke tabel destinasi_wisata (many to one)
     * Banyak alternatif belongs to satu destinasi
     */
    public function destinasi()
    {
        return $this->belongsTo(DestinasiWisata::class, 'destinasi_id');
    }

    /**
     * Relasi ke tabel kriteria (many to one)
     * Banyak alternatif belongs to satu kriteria
     */
    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class, 'kriteria_id');
    }

    /**
     * Scope: Filter by destinasi
     */
    public function scopeByDestinasi($query, $destinasiId)
    {
        return $query->where('destinasi_id', $destinasiId);
    }

    /**
     * Scope: Filter by kriteria
     */
    public function scopeByKriteria($query, $kriteriaId)
    {
        return $query->where('kriteria_id', $kriteriaId);
    }

    /**
     * Get nilai with kriteria info
     */
    public function getNilaiFormatAttribute()
    {
        $satuan = $this->kriteria->satuan ?? '';
        return $this->nilai . ' ' . $satuan;
    }
}
