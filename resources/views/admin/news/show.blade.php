<x-app-layout>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-9">
                <div class="card shadow-lg border-0 rounded-3">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h2 class="h5 mb-0">{{ __('Detail Berita') }}</h2>
                        <a href="{{ route('admin.news.edit', $news->id) }}" class="btn btn-warning btn-sm fw-bold shadow-sm">
                            <i class="fas fa-edit me-1"></i> {{ __('Edit Berita') }}
                        </a>
                    </div>
                    <div class="card-body p-4">

                        <!-- Judul dan Metadata -->
                        <h1 class="h3 fw-bold mb-3">{{ $news->title }}</h1>
                        <div class="text-muted small mb-4 border-bottom pb-2">
                            <span><i class="fas fa-user me-1"></i> {{ $news->author->name ?? 'N/A' }}</span>
                            <span class="mx-2">|</span>
                            <span><i class="fas fa-calendar-alt me-1"></i> {{ $news->created_at->format('d M Y H:i') }}</span>
                            <span class="mx-2">|</span>
                            <span><i class="fas fa-eye me-1"></i> {{ number_format($news->views) }}</span>
                            <span class="mx-2">|</span>
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
                        <div class="news-content border-top pt-4">
                            {!! $news->content !!}
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
