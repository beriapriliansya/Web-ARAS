@extends('layouts.app') {{-- Ganti dengan layout publik kamu (misalnya, layouts.frontend) --}}

@section('title', 'Berita Terbaru')

@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-12">
            <h1 class="fw-bold mb-4 border-bottom pb-2">Berita & Artikel Terbaru</h1>
        </div>
    </div>

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
                            <i class="fas fa-image fa-3x text-muted"></i>
                        </div>
                    @endif

                    <div class="card-body d-flex flex-column">
                        <small class="text-muted mb-2">
                            <i class="far fa-calendar-alt"></i> {{ $article->published_at ? $article->published_at->format('d M Y') : 'N/A' }}
                            &middot;
                            <i class="far fa-eye"></i> {{ number_format($article->views) }}
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
