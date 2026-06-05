<x-app-layout>

    <!-- Konten Utama (Menggunakan Container Bootstrap) -->
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-12">

                <!-- Header Halaman -->
                <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                    <h1 class="h3 mb-0 text-gray-800 fw-bold">{{ __('Manajemen Berita') }}</h1>
                    <a href="{{ route('admin.news.create') }}" class="btn btn-primary shadow-sm fw-bold rounded-pill px-4">
                        <i class="bi bi-plus-lg me-1"></i> {{ __('Tambah Berita Baru') }}
                    </a>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Tabel Data Berita -->
                <div class="card shadow-lg border-0 rounded-3">
                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped" width="100%" cellspacing="0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="small py-3">{{ __('Judul') }}</th>
                                        <th class="small py-3">{{ __('Penulis') }}</th>
                                        <th class="small py-3 text-center">{{ __('Status') }}</th>
                                        <th class="small py-3 text-center"><i class="bi bi-eye"></i> {{ __('Views') }}</th>
                                        <th class="small py-3 text-center"><i class="bi bi-heart"></i> {{ __('Likes') }}</th>
                                        <th class="small py-3 text-center"><i class="bi bi-chat-left-text"></i> {{ __('Comments') }}</th>
                                        <th class="small py-3 text-center">{{ __('Aksi') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($news as $article)
                                    <tr class="align-middle">
                                        <td class="small fw-bold">{{ Str::limit($article->title, 50) }}</td>
                                        <td class="small">{{ $article->author->name ?? 'N/A' }}</td>
                                        <td class="small text-center">
                                            @if($article->status == 'published')
                                                <span class="badge bg-success py-2 px-3">{{ __('Published') }}</span>
                                            @else
                                                <span class="badge bg-warning text-dark py-2 px-3">{{ __('Draft') }}</span>
                                            @endif
                                        </td>
                                        <td class="small text-center fw-semibold text-secondary">{{ number_format($article->views) }}</td>
                                        <td class="small text-center">
                                            <span class="badge py-2 px-3 rounded-pill fw-bold" style="background-color: rgba(220, 53, 69, 0.1); color: #dc3545;">
                                                <i class="bi bi-heart-fill me-1"></i> {{ number_format($article->likes_count) }}
                                            </span>
                                        </td>
                                        <td class="small text-center">
                                            <span class="badge py-2 px-3 rounded-pill fw-bold" style="background-color: rgba(40, 167, 69, 0.1); color: #28a745;">
                                                <i class="bi bi-chat-left-text-fill me-1"></i> {{ number_format($article->comments_count) }}
                                            </span>
                                        </td>
                                        <td class="small text-center">
                                            <div class="d-flex justify-content-center gap-1">
                                                <a href="{{ route('admin.news.show', $article->id) }}" class="btn btn-sm text-white d-inline-flex align-items-center justify-content-center" style="background: #0ea5e9 !important; width: 36px; height: 36px; border-radius: 50% !important;" title="Lihat"><i class="bi bi-eye"></i></a>
                                                <a href="{{ route('admin.news.edit', $article->id) }}" class="btn btn-sm text-dark d-inline-flex align-items-center justify-content-center" style="background: #ffc107 !important; width: 36px; height: 36px; border-radius: 50% !important;" title="Edit"><i class="bi bi-pencil"></i></a>

                                                <form action="{{ route('admin.news.destroy', $article->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus berita ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm text-white d-inline-flex align-items-center justify-content-center" style="background: #dc3545 !important; width: 36px; height: 36px; border-radius: 50% !important;" title="Hapus"><i class="bi bi-trash"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center mt-3">
                            {{ $news->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
