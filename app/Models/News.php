<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class News extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',
        'content',
        'image',
        'user_id',
        'status',
        'views',
        'published_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'published_at' => 'datetime',
    ];

    // Relasi: Berita dimiliki oleh satu User (Penulis)
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi: Berita memiliki banyak Like
    public function likes()
    {
        return $this->hasMany(NewsLike::class, 'news_id');
    }

    // Relasi: Berita memiliki banyak Komentar
    public function comments()
    {
        return $this->hasMany(NewsComment::class, 'news_id');
    }

    // Scope untuk mengambil berita yang sudah dipublikasi
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                     ->where('published_at', '<=', now());
    }
}
