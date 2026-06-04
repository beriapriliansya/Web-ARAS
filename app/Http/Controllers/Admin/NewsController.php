<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    /**
     * Tampilkan daftar berita di dashboard admin.
     */
    public function index()
    {
        $news = News::with('author')->latest()->paginate(15);
        return view('admin.news.index', compact('news'));
    }

    /**
     * Tampilkan form untuk membuat berita baru.
     */
    public function create()
    {
        return view('admin.news.create');
    }

    /**
     * Simpan berita baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'status' => 'required|in:draft,published',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            // Simpan gambar ke storage/app/public/news_images
            $imagePath = $request->file('image')->store('news_images', 'public');
        }

        News::create([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']) . '-' . time(), // Tambah timestamp agar slug unik
            'content' => $validated['content'],
            'image' => $imagePath,
            'user_id' => auth()->id(), // Ambil ID user yang sedang login
            'status' => $validated['status'],
            'published_at' => ($validated['status'] == 'published') ? now() : null,
        ]);

        return redirect()->route('admin.news.index')->with('success', 'Berita berhasil ditambahkan!');
    }

    /**
     * Tampilkan form edit berita.
     */
    public function edit(News $news)
    {
        return view('admin.news.edit', compact('news'));
    }

    /**
     * Update berita di database.
     */
    public function update(Request $request, News $news)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'status' => 'required|in:draft,published',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
        ]);

        $imagePath = $news->image;
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($news->image) {
                Storage::disk('public')->delete($news->image);
            }
            $imagePath = $request->file('image')->store('news_images', 'public');
        }

        $news->update([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']) . '-' . time(),
            'content' => $validated['content'],
            'image' => $imagePath,
            'status' => $validated['status'],
            'published_at' => ($validated['status'] == 'published' && $news->published_at === null) ? now() : $news->published_at,
        ]);

        return redirect()->route('admin.news.index')->with('success', 'Berita berhasil diperbarui!');
    }

    /**
     * Hapus berita dan gambarnya.
     */
    public function destroy(News $news)
    {
        if ($news->image) {
            Storage::disk('public')->delete($news->image);
        }
        $news->delete();

        return redirect()->route('admin.news.index')->with('success', 'Berita berhasil dihapus!');
    }

    // Metode show tidak diperlukan untuk admin karena sudah ada edit
    public function show(News $news)
    {
        // Redirect ke edit atau tampilkan detail sederhana
        return view('admin.news.show', compact('news'));
    }
}
