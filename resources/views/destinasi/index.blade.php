<x-app-layout>
    <!-- Page Header -->
    <div class="bg-primary text-white py-5" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
        <div class="container text-center py-3">
            <h1 class="display-4 fw-bold mb-2">
                <i class="bi bi-compass-fill"></i> Jelajahi Destinasi Wisata
            </h1>
            <p class="lead mb-0 opacity-75">
                Temukan surga wisata terbaik di Kabupaten Pesawaran
            </p>
        </div>
    </div>

    <!-- Filter & Search Section -->
    <div class="container my-4">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <form action="{{ route('destinasi.index') }}" method="GET" class="row g-3">
                    <!-- Search -->
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="bi bi-search text-muted"></i>
                            </span>
                            <input type="text" class="form-control border-start-0 ps-0" name="search"
                                   placeholder="Cari nama destinasi wisata..."
                                   value="{{ request('search') }}">
                        </div>
                    </div>

                    <!-- Filter Kategori -->
                    <div class="col-md-4">
                        <select class="form-select" name="kategori">
                            <option value="">Semua Kategori</option>
                            @foreach($kategoriList as $kat)
                                <option value="{{ $kat }}" {{ request('kategori') == $kat ? 'selected' : '' }}>
                                    {{ $kat }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Button -->
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100 fw-bold">
                            <i class="bi bi-funnel"></i> Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Destinasi Grid -->
    <div class="container mb-5">
        @if($destinasi->count() > 0)
            <div class="row g-4">
                @foreach($destinasi as $item)
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 shadow-sm border-0 hover-card transition-all">
                            <!-- Foto Destinasi -->
                            <div class="position-relative" style="height: 220px; overflow: hidden; border-top-left-radius: 0.375rem; border-top-right-radius: 0.375rem;">
                                @if($item->foto && file_exists(public_path('images/destinasi/' . $item->foto)))
                                    <img src="{{ asset('images/destinasi/' . $item->foto) }}"
                                         class="card-img-top"
                                         alt="{{ $item->nama }}"
                                         style="object-fit: cover; height: 100%; width: 100%; transition: transform 0.3s ease;">
                                @else
                                    <div class="bg-secondary d-flex align-items-center justify-content-center h-100 text-white">
                                        <i class="bi bi-image" style="font-size: 3rem;"></i>
                                    </div>
                                @endif

                                <!-- Badge Kategori -->
                                <span class="position-absolute top-0 end-0 m-3 badge bg-primary shadow-sm px-3 py-2">
                                    {{ $item->kategori }}
                                </span>
                            </div>

                            <div class="card-body d-flex flex-column p-4">
                                <h5 class="card-title fw-bold mb-2 text-dark">{{ $item->nama }}</h5>

                                <p class="card-text text-secondary small flex-grow-1">
                                    {{ Str::limit($item->deskripsi, 120) }}
                                </p>

                                <!-- Info -->
                                <div class="mb-4 border-top pt-3 mt-3">
                                    <p class="mb-2 small text-muted text-truncate">
                                        <i class="bi bi-geo-alt-fill text-danger me-1"></i>
                                        {{ $item->alamat }}
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-bold text-success fs-5">
                                            Rp {{ number_format($item->harga_tiket, 0, ',', '.') }}
                                        </span>
                                        <span class="small text-muted">
                                            <i class="bi bi-clock me-1"></i> Operasional
                                        </span>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <a href="{{ route('destinasi.show', $item->id) }}"
                                   class="btn btn-primary w-100 fw-bold py-2 shadow-sm">
                                    <i class="bi bi-eye-fill me-1"></i> Lihat Detail Wisata
                                </a>
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
                <p class="text-muted">Coba ubah kata kunci pencarian atau kategori filter Anda.</p>
                <a href="{{ route('destinasi.index') }}" class="btn btn-outline-primary fw-bold">
                    <i class="bi bi-arrow-clockwise"></i> Reset Filter
                </a>
            </div>
        @endif
    </div>

    <style>
        .hover-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.1) !important;
        }
        .hover-card:hover img {
            transform: scale(1.05);
        }
        .transition-all {
            transition: all 0.3s ease;
        }
    </style>
</x-app-layout>
