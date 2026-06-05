<x-app-layout>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-9">
                <div class="card shadow-lg border-0 rounded-3">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h2 class="h5 mb-0">{{ __('Detail Berita') }}</h2>
                        <a href="{{ route('admin.news.edit', $news->id) }}" class="btn btn-sm fw-bold shadow-sm text-dark rounded-pill" style="background: #ffc107 !important; border: none; padding: 0.4rem 1.2rem;">
                            <i class="bi bi-pencil-square me-1"></i> {{ __('Edit Berita') }}
                        </a>
                    </div>
                    <div class="card-body p-4">

                        <!-- Judul dan Metadata -->
                        <h1 class="h3 fw-bold mb-3">{{ $news->title }}</h1>
                        <div class="text-muted small mb-4 border-bottom pb-2 d-flex align-items-center gap-3 flex-wrap">
                            <span><i class="bi bi-person me-1"></i> {{ $news->author->name ?? 'N/A' }}</span>
                            <span class="text-muted">|</span>
                            <span><i class="bi bi-calendar-event me-1"></i> {{ $news->created_at->format('d M Y H:i') }}</span>
                            <span class="text-muted">|</span>
                            <span><i class="bi bi-eye me-1"></i> {{ number_format($news->views) }} Views</span>
                            <span class="text-muted">|</span>
                            <span><i class="bi bi-heart me-1"></i> {{ number_format($news->likes_count) }} Likes</span>
                            <span class="text-muted">|</span>
                            <span><i class="bi bi-chat-left-text me-1"></i> {{ number_format($news->comments_count) }} Comments</span>
                            <span class="text-muted">|</span>
                            <span>
                                @if($news->status == 'published')
                                    <span class="badge bg-success">{{ __('Published') }}</span>
                                @else
                                    <span class="badge bg-warning text-dark">{{ __('Draft') }}</span>
                                @endif
                            </span>
                        </div>

                        <!-- Gambar Utama -->
                        @if($news->image)
                        <figure class="mb-4">
                            <img src="{{ asset('storage/' . $news->image) }}" alt="{{ $news->title }}" class="img-fluid rounded shadow-sm" style="width: 100%; max-height: 400px; object-fit: cover;">
                            <figcaption class="figure-caption text-center pt-2">{{ __('Gambar Utama') }}</figcaption>
                        </figure>
                        @endif

                        <!-- Konten Berita -->
                        <div class="news-content border-top pt-4 mb-5">
                            {!! $news->content !!}
                        </div>

                        <!-- KOLOM KOMENTAR UNTUK MODERASI -->
                        <div class="mt-5 border-top pt-4">
                            <h5 class="fw-bold mb-4 text-dark"><i class="bi bi-chat-left-text-fill me-2 text-primary"></i>Komentar Pengunjung ({{ $news->comments_count }})</h5>
                            
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
                                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif
                            @if(session('error'))
                                <div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">
                                    <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <div class="d-flex flex-column gap-3">
                                @forelse($news->comments as $comment)
                                    <div class="p-3 bg-light rounded-3 border">
                                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-2">
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 32px; height: 32px; font-size: 0.85rem;">
                                                    {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.9rem;">{{ $comment->user->name }}</h6>
                                                    <small class="text-muted" style="font-size: 0.75rem;">{{ $comment->created_at->diffForHumans() }}</small>
                                                </div>
                                            </div>
                                            <form action="{{ route('news.comment.destroy', $comment->id) }}" method="POST" onsubmit="return confirm('Hapus komentar ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger px-2 py-1" title="Hapus Komentar">
                                                    <i class="bi bi-trash-fill me-1"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                        <p class="mb-0 text-secondary" style="font-size: 0.95rem; white-space: pre-line;">{{ $comment->content }}</p>
                                    </div>
                                @empty
                                    <div class="text-center py-4 text-muted bg-light rounded-3 border">
                                        <i class="bi bi-chat-quote fs-2 mb-2 d-block"></i>
                                        <p class="small mb-0">Belum ada komentar pada berita ini.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                    </div>
                    <div class="card-footer text-end">
                        <a href="{{ route('admin.news.index') }}" class="btn btn-secondary">{{ __('Kembali ke Daftar Berita') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
