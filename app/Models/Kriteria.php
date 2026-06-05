<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database
     */
    protected $table = 'kriteria';

    /**
     * Kolom yang bisa diisi mass assignment
     */
    protected $fillable = [
        'kode',
        'nama_kriteria',
        'bobot',
        'tipe',
        'keterangan',
        'satuan',
        'status',
    ];

    /**
     * Kolom yang di-cast ke tipe data tertentu
     */
    protected $casts = [
        'bobot' => 'decimal:4',
    ];

    /**
     * Relasi ke tabel alternatif (one to many)
     * Satu kriteria punya banyak nilai alternatif
     */
    public function alternatif()
    {
        return $this->hasMany(Alternatif::class, 'kriteria_id');
    }

    /**
     * Relasi ke tabel sub_kriteria (one to many)
     */
    public function subKriteria()
    {
        return $this->hasMany(SubKriteria::class, 'kriteria_id');
    }

    /**
     * Scope: Filter kriteria aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    /**
     * Scope: Filter by tipe (benefit/cost)
     */
    public function scopeTipe($query, $tipe)
    {
        return $query->where('tipe', $tipe);
    }

    /**
     * Accessor: Check if benefit type
     */
    public function getIsBenefitAttribute()
    {
        return $this->tipe === 'benefit';
    }

    /**
     * Accessor: Check if cost type
     */
    public function getIsCostAttribute()
    {
        return $this->tipe === 'cost';
    }

    /**
     * Accessor: Format bobot as percentage
     */
    public function getBobotPersenAttribute()
    {
        return ($this->bobot * 100) . '%';
    }
}
