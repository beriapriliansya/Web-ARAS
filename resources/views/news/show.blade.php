<x-app-layout>
    <div class="container py-5">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('news.index') }}">Berita</a></li>
                <li class="breadcrumb-item active text-truncate" aria-current="page" style="max-width: 250px;">{{ $article->title }}</li>
            </ol>
        </nav>

        <div class="row g-5">
            <!-- Kolom Kiri: Detail Artikel -->
            <div class="col-lg-8">
                <article class="bg-white p-4 p-md-5 rounded-4 shadow-sm border">
                    <!-- Title -->
                    <h1 class="display-6 fw-bold mb-3 text-dark">{{ $article->title }}</h1>

                    <!-- Meta Info -->
                    <div class="d-flex align-items-center gap-3 text-muted small mb-4 pb-3 border-bottom">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-calendar-event me-2 text-primary"></i>
                            {{ $article->published_at ? $article->published_at->format('d M Y') : 'N/A' }}
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-eye me-2 text-info"></i>
                            {{ number_format($article->views) }} Kali Dilihat
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-person me-2 text-secondary"></i>
                            Penulis: {{ $article->author->name ?? 'Admin Dinas' }}
                        </div>
                    </div>

                    <!-- Gambar Utama -->
                    @if($article->image)
                        <div class="mb-4 rounded-3 overflow-hidden border shadow-sm" style="max-height: 400px;">
                            <img src="{{ asset('storage/' . $article->image) }}" class="w-100 h-100 object-cover" alt="{{ $article->title }}">
                        </div>
                    @endif

                    <!-- Konten Artikel -->
                    <div class="article-content text-secondary fs-5" style="line-height: 1.8; text-align: justify;">
                        {!! nl2br($article->content) !!}
                    </div>

                    <!-- Share Section -->
                    <div class="mt-5 pt-4 border-top d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <span class="fw-bold text-dark">Bagikan Artikel Ini:</span>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-primary btn-sm rounded-pill"><i class="bi bi-facebook me-1"></i> Facebook</button>
                            <button class="btn btn-outline-info btn-sm rounded-pill"><i class="bi bi-twitter-x me-1"></i> Twitter</button>
                            <button class="btn btn-outline-success btn-sm rounded-pill"><i class="bi bi-whatsapp me-1"></i> WhatsApp</button>
                        </div>
                    </div>
                </article>
            </div>

            <!-- Kolom Kanan: Artikel Terkait & Sidebar -->
            <div class="col-lg-4">
                <!-- Box Informasi -->
                <div class="card border shadow-sm rounded-4 mb-4 bg-light">
                    <div class="card-body p-4 text-center">
                        <i class="bi bi-info-circle text-primary fs-1 mb-3"></i>
                        <h5 class="fw-bold text-dark">Dinas Pariwisata Pesawaran</h5>
                        <p class="text-secondary small mb-0">
                            Terus pantau informasi dan berita pariwisata resmi Kabupaten Pesawaran untuk referensi kunjungan Anda.
                        </p>
                    </div>
                </div>

                <!-- Artikel Terkait -->
                <div class="card border shadow-sm rounded-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-newspaper me-2 text-primary"></i>Berita Terkini Lainnya</h6>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush rounded-bottom-4">
                            @forelse($related_articles as $related)
                                <a href="{{ route('news.show', $related->slug) }}" class="list-group-item list-group-item-action p-3">
                                    <div class="d-flex flex-column gap-1">
                                        <small class="text-muted small">{{ $related->published_at ? $related->published_at->format('d M Y') : 'N/A' }}</small>
                                        <h6 class="fw-bold text-dark mb-1 text-truncate-2" style="font-size: 0.95rem;">
                                            {{ $related->title }}
                                        </h6>
                                        <small class="text-primary small">Baca Selengkapnya <i class="bi bi-arrow-right"></i></small>
                                    </div>
                                </a>
                            @empty
                                <li class="list-group-item text-center text-muted p-4">Tidak ada berita lain.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .text-truncate-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</x-app-layout>
