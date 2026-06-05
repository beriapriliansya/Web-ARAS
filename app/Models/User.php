<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',          // Pastikan 'role' ada di sini
        'destinasi_id',  // Pastikan 'destinasi_id' ada di sini
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // ==========================================
    // TAMBAHAN RELASI (Ini yang bikin error tadi)
    // ==========================================

    /**
     * Relasi: User (Admin) memiliki satu Destinasi Wisata yang dikelola.
     */
    public function destinasi()
    {
        // belongsTo artinya: User "milik" satu destinasi (karena ada destinasi_id di tabel users)
        return $this->belongsTo(DestinasiWisata::class, 'destinasi_id');
    }

    /**
     * Relasi ke NewsLike
     */
    public function newsLikes()
    {
        return $this->hasMany(NewsLike::class, 'user_id');
    }

    /**
     * Relasi ke NewsComment
     */
    public function newsComments()
    {
        return $this->hasMany(NewsComment::class, 'user_id');
    }
}
