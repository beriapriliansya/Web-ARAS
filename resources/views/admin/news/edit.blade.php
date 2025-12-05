<x-app-layout>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-9 col-lg-8">

                <div class="card shadow-lg border-0 rounded-3">
                    <div class="card-header bg-warning text-dark">
                        <h2 class="h5 mb-0">{{ __('Edit Berita') }}</h2>
                    </div>
                    <div class="card-body p-4">

                        <form action="{{ route('admin.news.update', $news->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')

                            <!-- Judul Berita -->
                            <div class="mb-4">
                                <label for="title" class="form-label font-weight-bold">{{ __('Judul Berita') }}</label>
                                <input type="text" name="title" id="title" value="{{ old('title', $news->title) }}" required class="form-control form-control-lg @error('title') is-invalid @enderror">
                                @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <!-- Konten Berita -->
                            <div class="mb-4">
                                <label for="content" class="form-label font-weight-bold">{{ __('Konten Berita') }}</label>
                                <textarea name="content" id="content" rows="12" required class="form-control @error('content') is-invalid @enderror">{{ old('content', $news->content) }}</textarea>
                                @error('content') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <!-- Gambar Saat Ini -->
                            @if($news->image)
                            <div class="mb-4 p-3 border rounded-3 bg-light">
                                <label class="form-label font-weight-bold">{{ __('Gambar Saat Ini') }}</label>
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $news->image) }}" alt="Gambar Utama" class="img-fluid rounded shadow-sm" style="max-height: 150px; object-fit: cover;">
                                </div>
                            </div>
                            @endif

                            <!-- Ganti Gambar Utama -->
                            <div class="mb-4">
                                <label for="image" class="form-label font-weight-bold">{{ __('Ganti Gambar Utama (Opsional)') }}</label>
                                <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror">
                                @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <!-- Status Publikasi -->
                            <div class="mb-5">
                                <label for="status" class="form-label font-weight-bold">{{ __('Status Publikasi') }}</label>
                                <select name="status" id="status" required class="form-select @error('status') is-invalid @enderror">
                                    <option value="draft" {{ old('status', $news->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ old('status', $news->status) == 'published' ? 'selected' : '' }}>Published</option>
                                </select>
                                @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="d-flex justify-content-end border-top pt-3">
                                <a href="{{ route('admin.news.index') }}" class="btn btn-secondary me-3 shadow-sm">{{ __('Batal') }}</a>
                                <button type="submit" class="btn btn-success btn-lg shadow-sm">{{ __('Update Berita') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
