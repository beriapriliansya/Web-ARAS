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
        // Auto-create likes and comments tables if they do not exist
        if (!\Illuminate\Support\Facades\Schema::hasTable('news_likes')) {
            \Illuminate\Support\Facades\DB::statement("
                CREATE TABLE IF NOT EXISTS `news_likes` (
                    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                    `news_id` bigint unsigned NOT NULL,
                    `user_id` bigint unsigned NOT NULL,
                    `created_at` timestamp NULL DEFAULT NULL,
                    `updated_at` timestamp NULL DEFAULT NULL,
                    PRIMARY KEY (`id`),
                    UNIQUE KEY `news_likes_news_id_user_id_unique` (`news_id`, `user_id`),
                    FOREIGN KEY (`news_id`) REFERENCES `news` (`id`) ON DELETE CASCADE,
                    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
            ");
        }

        if (!\Illuminate\Support\Facades\Schema::hasTable('news_comments')) {
            \Illuminate\Support\Facades\DB::statement("
                CREATE TABLE IF NOT EXISTS `news_comments` (
                    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                    `news_id` bigint unsigned NOT NULL,
                    `user_id` bigint unsigned NOT NULL,
                    `content` text NOT NULL,
                    `created_at` timestamp NULL DEFAULT NULL,
                    `updated_at` timestamp NULL DEFAULT NULL,
                    PRIMARY KEY (`id`),
                    FOREIGN KEY (`news_id`) REFERENCES `news` (`id`) ON DELETE CASCADE,
                    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
            ");
        }

        // Cari berita berdasarkan slug dan status harus 'published'
        // Eager load comments (dengan user yang menulisnya) dan likes
        $article = News::published()
            ->with(['comments' => function($q) {
                $q->latest();
            }, 'comments.user', 'likes'])
            ->where('slug', $slug)
            ->firstOrFail();

        // Tingkatkan jumlah views
        $article->increment('views');

        // Ambil 3 berita terkait
        $related_articles = News::published()
            ->where('id', '!=', $article->id)
            ->latest('published_at')
            ->limit(3)
            ->get();

        return view('news.show', compact('article', 'related_articles'));
    }

    /**
     * Menyukai / Batal menyukai artikel (Toggle Like)
     */
    public function toggleLike(Request $request, $id)
    {
        $user = auth()->user();
        $like = \App\Models\NewsLike::where('news_id', $id)
                                    ->where('user_id', $user->id)
                                    ->first();

        if ($like) {
            $like->delete();
            return back()->with('success', 'Batal menyukai berita.');
        } else {
            \App\Models\NewsLike::create([
                'news_id' => $id,
                'user_id' => $user->id,
            ]);
            return back()->with('success', 'Berhasil menyukai berita.');
        }
    }

    /**
     * Menyimpan komentar baru
     */
    public function storeComment(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|string|min:1|max:1000',
        ], [
            'content.required' => 'Komentar tidak boleh kosong.',
            'content.max' => 'Komentar terlalu panjang (maksimal 1000 karakter).',
        ]);

        \App\Models\NewsComment::create([
            'news_id' => $id,
            'user_id' => auth()->id(),
            'content' => $request->input('content'),
        ]);

        return back()->with('success', 'Komentar berhasil ditambahkan!');
    }

    /**
     * Menghapus komentar
     */
    public function destroyComment(Request $request, $id)
    {
        $comment = \App\Models\NewsComment::findOrFail($id);

        // Hanya pemilik komentar yang bisa menghapusnya
        if ($comment->user_id !== auth()->id()) {
            return back()->with('error', 'Anda tidak memiliki akses untuk menghapus komentar ini.');
        }

        $comment->delete();
        return back()->with('success', 'Komentar berhasil dihapus.');
    }
}
