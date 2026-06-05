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
                    <div class="d-flex align-items-center gap-3 text-muted flex-wrap small mb-4 pb-3 border-bottom">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-calendar-event me-1.5 text-primary"></i>
                            {{ $article->published_at ? $article->published_at->format('d M Y') : 'N/A' }}
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-eye me-1.5 text-info"></i>
                            {{ number_format($article->views) }} Kali Dilihat
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-heart-fill me-1.5 text-danger"></i>
                            {{ $article->likes->count() }} Menyukai
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-chat-left-text-fill me-1.5 text-success"></i>
                            {{ $article->comments->count() }} Komentar
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-person me-1.5 text-secondary"></i>
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

                    <!-- Interaction & Share Section -->
                    <div class="mt-5 pt-4 border-top d-flex align-items-center justify-content-between flex-wrap gap-3">
                        <div class="d-flex align-items-center gap-2">
                            @auth
                                @php
                                    $hasLiked = $article->likes->where('user_id', auth()->id())->isNotEmpty();
                                @endphp
                                <form action="{{ route('admin.news.like', $article->id) }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit" class="btn btn-sm {{ $hasLiked ? 'btn-danger' : 'btn-outline-danger' }} rounded-pill px-3 fw-bold d-flex align-items-center gap-1.5">
                                        <i class="bi {{ $hasLiked ? 'bi-heart-fill' : 'bi-heart' }}"></i>
                                        {{ $hasLiked ? 'Batal Suka' : 'Suka' }}
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-sm btn-outline-danger rounded-pill px-3 fw-bold d-flex align-items-center gap-1.5">
                                    <i class="bi bi-heart"></i> Suka
                                </a>
                            @endauth
                            <span class="text-secondary small fw-semibold">{{ $article->likes->count() }} orang menyukai berita ini</span>
                        </div>
                        
                        <div class="d-flex align-items-center gap-2">
                            <span class="fw-bold text-dark small me-1">Bagikan:</span>
                            <button class="btn btn-outline-primary btn-sm rounded-pill"><i class="bi bi-facebook"></i></button>
                            <button class="btn btn-outline-info btn-sm rounded-pill"><i class="bi bi-twitter-x"></i></button>
                            <button class="btn btn-outline-success btn-sm rounded-pill"><i class="bi bi-whatsapp"></i></button>
                        </div>
                    </div>
                </article>

                <!-- KOLOM KOMENTAR -->
                <div class="card border shadow border-0 rounded-4 overflow-hidden mt-4 shadow-sm">
                    <div class="card-header bg-primary text-white py-3">
                        <h6 class="mb-0 fw-bold"><i class="bi bi-chat-left-text-fill me-2"></i>Komentar ({{ $article->comments->count() }})</h6>
                    </div>
                    <div class="card-body p-4 bg-white">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <!-- Form Input Komentar -->
                        @auth
                            <form action="{{ route('admin.news.comment', $article->id) }}" method="POST" class="mb-4 pb-4 border-bottom">
                                @csrf
                                <div class="mb-3">
                                    <label for="comment_content" class="form-label fw-bold text-secondary small">Tulis Komentar Anda:</label>
                                    <textarea name="content" id="comment_content" rows="3" class="form-control rounded-3" placeholder="Bagikan tanggapan Anda mengenai berita ini..." required></textarea>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary px-4 fw-bold rounded-pill">
                                        Kirim Komentar
                                    </button>
                                </div>
                            </form>
                        @else
                            <div class="alert alert-warning rounded-3 mb-4 p-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                                <span class="small"><i class="bi bi-info-circle-fill me-2"></i> Anda harus login terlebih dahulu untuk menyukai atau mengomentari berita ini.</span>
                                <a href="{{ route('login') }}" class="btn btn-sm btn-primary fw-bold px-3 rounded-pill">Login</a>
                            </div>
                        @endif

                        <!-- Daftar Komentar -->
                        <div class="comments-list d-flex flex-column gap-3">
                            @forelse($article->comments as $comment)
                                <div class="p-3 bg-light rounded-3 border">
                                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-2">
                                        <div class="d-flex align-items-center gap-2">
                                            <!-- Initial Badge Avatar -->
                                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 32px; height: 32px; font-size: 0.85rem;">
                                                {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.9rem;">{{ $comment->user->name }}</h6>
                                                <small class="text-muted" style="font-size: 0.75rem;">{{ $comment->created_at->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                        @if(auth()->check() && auth()->id() === $comment->user_id)
                                            <form action="{{ route('admin.news.comment.destroy', $comment->id) }}" method="POST" onsubmit="return confirm('Hapus komentar ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm text-danger p-0 border-0" title="Hapus Komentar">
                                                    <i class="bi bi-trash-fill"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                    <p class="mb-0 text-secondary" style="font-size: 0.95rem; white-space: pre-line;">{{ $comment->content }}</p>
                                </div>
                            @empty
                                <div class="text-center py-4 text-muted">
                                    <i class="bi bi-chat-quote fs-2 mb-2 d-block"></i>
                                    <p class="small mb-0">Belum ada komentar. Jadilah yang pertama memberikan tanggapan!</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
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
