@extends('layouts.app') {{-- Ganti dengan layout publik kamu (misalnya, layouts.frontend) --}}

@section('title', 'Berita Terbaru')

@section('content')
<!-- Page Header -->
<div class="container mt-4">
    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="card-body py-3 d-flex justify-content-between align-items-center flex-wrap gap-3 border-start border-primary border-5">
            <div>
                <h2 class="fw-bold mb-1 text-primary m-0">
                    <i class="bi bi-newspaper text-warning"></i> Berita & Artikel Terbaru
                </h2>
                <p class="text-muted mb-0 small">Ikuti perkembangan destinasi wisata dan berita pariwisata resmi Kabupaten Pesawaran</p>
            </div>
        </div>
    </div>
</div>

<div class="container my-4">

    @if ($articles->count() > 0)
        <div class="row">
            @foreach ($articles as $article)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 shadow-sm border-0 rounded-3 overflow-hidden">
                    {{-- Gambar Thumbnail --}}
                    @if($article->image)
                        <img src="{{ asset('storage/' . $article->image) }}" class="card-img-top" alt="{{ $article->title }}" style="height: 200px; object-fit: cover;">
                    @else
                        {{-- Placeholder jika tidak ada gambar --}}
                        <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="bi bi-image fs-1 text-muted"></i>
                        </div>
                    @endif

                    <div class="card-body d-flex flex-column">
                        <small class="text-muted mb-2">
                            <i class="bi bi-calendar-event"></i> {{ $article->published_at ? $article->published_at->format('d M Y') : 'N/A' }}
                            &middot;
                            <i class="bi bi-eye"></i> {{ number_format($article->views) }}
                        </small>

                        {{-- Judul --}}
                        <h5 class="card-title fw-bold">
                            <a href="{{ route('news.show', $article->slug) }}" class="text-decoration-none text-dark hover-primary">{{ Str::limit($article->title, 60) }}</a>
                        </h5>

                        {{-- Ringkasan Konten --}}
                        <p class="card-text text-secondary mb-3">
                            {{ Str::limit(strip_tags($article->content), 100) }}
                        </p>

                        {{-- Tombol Baca --}}
                        <div class="mt-auto">
                            <a href="{{ route('news.show', $article->slug) }}" class="btn btn-primary btn-sm rounded-pill">Baca Selengkapnya</a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Paginasi --}}
        <div class="d-flex justify-content-center mt-4">
            {{ $articles->links('pagination::bootstrap-5') }}
        </div>
    @else
        {{-- Jika tidak ada berita --}}
        <div class="alert alert-info text-center" role="alert">
            Belum ada berita yang dipublikasikan saat ini.
        </div>
    @endif
</div>

<style>
/* Custom style sederhana untuk efek hover */
.card-title a.hover-primary:hover {
    color: #007bff !important; /* Ganti dengan warna primary Bootstrap kamu */
}
</style>
@endsection
