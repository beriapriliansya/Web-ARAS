<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    /**
     * Tampilkan daftar semua berita yang sudah dipublikasi.
     */
    public function index()
    {
        // Ambil berita yang berstatus 'published', diurutkan dari yang terbaru
        $articles = News::published()->latest('published_at')->paginate(10);

        // Pastikan untuk membuat views/news/index.blade.php
        return view('news.index', compact('articles'));
    }

    /**
     * Tampilkan detail satu berita berdasarkan slug.
     */
    public function show($slug)
    {
        // Cari berita berdasarkan slug dan status harus 'published'
        $article = News::published()->where('slug', $slug)->firstOrFail();

        // Tingkatkan jumlah views
        $article->increment('views');

        // Ambil 3 berita terkait (misalnya dari kategori yang sama, jika kamu implementasi kategori)
        // Saat ini, kita ambil 3 berita terbaru lainnya
        $related_articles = News::published()
            ->where('id', '!=', $article->id)
            ->latest('published_at')
            ->limit(3)
            ->get();

        // Pastikan untuk membuat views/news/show.blade.php
        return view('news.show', compact('article', 'related_articles'));
    }
}
