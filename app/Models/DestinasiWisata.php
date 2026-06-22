<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DestinasiWisata extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Nama tabel di database
     */
    protected $table = 'destinasi_wisata';

    /**
     * Kolom yang bisa diisi mass assignment
     */
    protected $fillable = [
        'kode',
        'nama',
        'deskripsi',
        'alamat',
        'latitude',
        'longitude',
        'kategori',
        'foto',
        'harga_tiket',
        'jarak',
        'jam_buka',
        'jam_tutup',
        'telepon',
        'website',
        'fasilitas',
        'status',
    ];

    /**
     * Kolom yang di-cast ke tipe data tertentu
     */
    protected $casts = [
        'fasilitas' => 'array', // JSON ke array
        'harga_tiket' => 'decimal:2',
        'jarak' => 'integer',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'jam_buka' => 'datetime:H:i',
        'jam_tutup' => 'datetime:H:i',
    ];

    /**
     * Relasi ke tabel alternatif (one to many)
     * Satu destinasi punya banyak nilai alternatif
     */
    public function alternatif()
    {
        return $this->hasMany(Alternatif::class, 'destinasi_id');
    }

    /**
     * Relasi ke tabel hasil_aras (one to one)
     * Satu destinasi punya satu hasil ARAS
     */
    public function hasilAras()
    {
        return $this->hasOne(HasilAras::class, 'destinasi_id');
    }

    /**
     * Scope: Filter destinasi aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    /**
     * Scope: Filter by kategori
     */
    public function scopeKategori($query, $kategori)
    {
        return $query->where('kategori', $kategori);
    }

    /**
     * Scope: Search by nama atau deskripsi
     */
    public function scopeSearch($query, $keyword)
    {
        return $query->where(function($q) use ($keyword) {
            $q->where('nama', 'like', "%{$keyword}%")
              ->orWhere('deskripsi', 'like', "%{$keyword}%")
              ->orWhere('alamat', 'like', "%{$keyword}%");
        });
    }
}
