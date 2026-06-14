<x-app-layout>

    <!-- Page Header -->
    <div class="container mt-4">
        <div class="card shadow-sm border-0">
            <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h2 class="fw-bold mb-1 text-primary m-0">
                        <i class="bi bi-pin-map-fill"></i> Destinasi Wisata
                    </h2>
                    <p class="text-muted mb-0 small">Total {{ $destinasi->total() }} destinasi wisata terdaftar dalam sistem.</p>
                </div>
                <a href="{{ route('admin.destinasi.create') }}" class="btn btn-primary fw-bold rounded-pill">
                    <i class="bi bi-plus-circle"></i> Tambah Destinasi
                </a>
            </div>
        </div>
    </div>

    <!-- Filter & Search Section -->
    <div class="container my-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <form action="{{ route('admin.destinasi.index') }}" method="GET" class="row g-3 align-items-end">
                    <!-- Search -->
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-secondary small mb-1">Cari Nama Pantai:</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="bi bi-search text-muted"></i>
                            </span>
                            <input type="text" class="form-control border-start-0 ps-0" name="search"
                                   placeholder="Masukkan nama pantai..."
                                   value="{{ request('search') }}">
                        </div>
                    </div>

                    <!-- Filter Status -->
                    <div class="col-md-3">
                        <label class="form-label fw-semibold text-secondary small mb-1">Status Publikasi:</label>
                        <select class="form-select" name="status">
                            <option value="">Semua Status</option>
                            <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif (Tampil)</option>
                            <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Non-Aktif (Sembunyi)</option>
                        </select>
                    </div>

                    <!-- Urutkan (Sorting) -->
                    <div class="col-md-3">
                        <label class="form-label fw-semibold text-secondary small mb-1">Urutkan Berdasarkan:</label>
                        <select class="form-select" name="sort">
                            <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Terbaru</option>
                            <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Harga Terendah</option>
                            <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Harga Tertinggi</option>
                            <option value="name_asc" {{ request('sort') === 'name_asc' ? 'selected' : '' }}>Nama A - Z</option>
                        </select>
                    </div>

                    <!-- Button -->
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100 fw-semibold">
                            <i class="bi bi-funnel"></i> Terapkan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Destinasi Grid -->
    <div class="container my-5">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($destinasi->count() > 0)
            <div class="row g-4">
                @foreach($destinasi as $item)
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 shadow-sm border-0 hover-card">
                            <!-- Foto Destinasi -->
                            <div class="position-relative" style="height: 200px; overflow: hidden;">
                                @if($item->foto && file_exists(public_path('images/destinasi/' . $item->foto)))
                                    <img src="{{ asset('images/destinasi/' . $item->foto) }}"
                                         class="card-img-top"
                                         alt="{{ $item->nama }}"
                                         style="object-fit: cover; height: 100%; width: 100%;">
                                @else
                                    <div class="bg-secondary d-flex align-items-center justify-content-center h-100">
                                        <i class="bi bi-image text-white" style="font-size: 3rem;"></i>
                                    </div>
                                @endif

                                <!-- Badge Kategori -->
                                <span class="position-absolute top-0 end-0 m-2 badge bg-primary shadow-sm">
                                    {{ $item->kategori }}
                                </span>

                                <!-- Badge Status -->
                                <span class="position-absolute top-0 start-0 m-2 badge {{ $item->status == 'aktif' ? 'bg-success' : 'bg-danger' }} shadow-sm">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </div>

                            <div class="card-body">
                                <h5 class="card-title fw-bold mb-2 text-dark">{{ $item->nama }}</h5>

                                <p class="card-text text-muted small">
                                    {{ Str::limit($item->deskripsi, 90) }}
                                </p>

                                <!-- Info -->
                                <div class="mb-3 border-top pt-3 mt-3">
                                    <p class="mb-1 small text-truncate">
                                        <i class="bi bi-geo-alt-fill text-danger me-1"></i>
                                        {{ $item->alamat }}
                                    </p>
                                    <p class="mb-1 fw-bold text-success">
                                        <i class="bi bi-tag-fill me-1"></i>
                                        Rp {{ number_format($item->harga_tiket, 0, ',', '.') }}
                                    </p>

                                    @if($item->fasilitas && count($item->fasilitas) > 0)
                                        <p class="mb-0 small text-muted">
                                            <i class="bi bi-grid-fill me-1"></i>
                                            {{ count($item->fasilitas) }} Fasilitas Tersedia
                                        </p>
                                    @endif
                                </div>

                                <!-- Actions -->
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.destinasi.show', $item->id) }}"
                                       class="btn btn-outline-primary btn-sm flex-grow-1">
                                        <i class="bi bi-eye"></i> Detail
                                    </a>
                                    <a href="{{ route('admin.destinasi.edit', $item->id) }}"
                                       class="btn btn-outline-warning btn-sm">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.destinasi.destroy', $item->id) }}"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Yakin ingin menghapus destinasi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-5">
                {{ $destinasi->withQueryString()->links() }}
            </div>
        @else
            <div class="text-center py-5 bg-white rounded shadow-sm">
                <i class="bi bi-inbox text-muted" style="font-size: 4rem;"></i>
                <h3 class="mt-3 text-muted">Tidak ada destinasi ditemukan</h3>
                <p class="text-muted">Coba ubah filter atau tambahkan data baru.</p>
                <a href="{{ route('admin.destinasi.index') }}" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-clockwise"></i> Reset Filter
                </a>
            </div>
        @endif
    </div>
</x-app-layout>
