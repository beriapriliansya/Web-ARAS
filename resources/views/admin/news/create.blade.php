<x-app-layout>

    <div class="container py-4">
        <div class="row justify-content-center">
            {{-- Kita buat kolom konten lebih sempit agar fokus, misal 8 dari 12 --}}
            <div class="col-md-9 col-lg-8">

                <div class="card shadow-lg border-0 rounded-3">
                    <div class="card-header bg-primary text-white">
                        <h2 class="h5 mb-0">{{ __('Formulir Berita Baru') }}</h2>
                    </div>
                    <div class="card-body p-4">

                        <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <!-- Judul Berita -->
                            <div class="mb-4">
                                <label for="title" class="form-label font-weight-bold">{{ __('Judul Berita') }}</label>
                                <input type="text" name="title" id="title" value="{{ old('title') }}" required class="form-control form-control-lg @error('title') is-invalid @enderror">
                                @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <!-- Konten Berita -->
                            <div class="mb-4">
                                <label for="content" class="form-label font-weight-bold">{{ __('Konten Berita') }}</label>
                                <textarea name="content" id="content" rows="12" required class="form-control @error('content') is-invalid @enderror">{{ old('content') }}</textarea>
                                @error('content') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <!-- Gambar Utama -->
                            <div class="mb-4">
                                <label for="image" class="form-label font-weight-bold">{{ __('Gambar Utama') }}</label>
                                <input type="file" name="image" id="image" required class="form-control @error('image') is-invalid @enderror">
                                @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <!-- Status Publikasi -->
                            <div class="mb-5">
                                <label for="status" class="form-label font-weight-bold">{{ __('Status Publikasi') }}</label>
                                <select name="status" id="status" required class="form-select @error('status') is-invalid @enderror">
                                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                                </select>
                                @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="d-flex justify-content-end border-top pt-3">
                                <a href="{{ route('admin.news.index') }}" class="btn btn-secondary me-3 shadow-sm">{{ __('Batal') }}</a>
                                <button type="submit" class="btn btn-primary btn-lg shadow-sm">{{ __('Simpan Berita') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
