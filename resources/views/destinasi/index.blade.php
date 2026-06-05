<x-app-layout>
    <!-- Page Header -->
    <div class="container mt-4">
        <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
            <div class="card-body py-3 d-flex justify-content-between align-items-center flex-wrap gap-3 border-start border-primary border-5">
                <div>
                    <h2 class="fw-bold mb-1 text-primary m-0">
                        <i class="bi bi-compass-fill text-warning"></i> Jelajahi Destinasi Wisata
                    </h2>
                    <p class="text-muted mb-0 small">Temukan surga wisata terbaik di Kabupaten Pesawaran</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Search Section -->
    <div class="container my-5">
        <div class="row g-4">
            <!-- KOLOM KIRI: Filter Pencarian -->
            <div class="col-lg-3 col-md-4">
                <div class="card shadow border-0 rounded-4 overflow-hidden">
                    <div class="card-header bg-primary text-white py-3">
                        <h6 class="mb-0 fw-bold"><i class="bi bi-funnel-fill me-2"></i>Filter Pencarian</h6>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('destinasi.index') }}" method="GET" id="searchFilterForm">
                            <!-- Search -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-secondary small mb-1">Cari Nama:</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="bi bi-search text-muted"></i>
                                    </span>
                                    <input type="text" class="form-control border-start-0 ps-0" name="search"
                                           placeholder="Cari destinasi..."
                                           value="{{ request('search') }}">
                                </div>
                            </div>

                            <!-- Kategori -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-secondary small mb-1">Kategori:</label>
                                <select class="form-select form-select-sm" name="kategori">
                                    <option value="">Semua Kategori</option>
                                    @foreach($kategoriList as $kat)
                                        <option value="{{ $kat }}" {{ request('kategori') == $kat ? 'selected' : '' }}>
                                            {{ $kat }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Criteria Dropdowns (C1 - C5) -->
                            @foreach($kriteriaFilter as $kf)
                                <div class="mb-3">
                                    <label class="form-label fw-semibold text-secondary small mb-1">{{ $kf->nama_kriteria }}:</label>
                                    <select name="{{ strtolower($kf->kode) }}" class="form-select form-select-sm">
                                        <option value="">-- Semua --</option>
                                        @foreach($kf->subKriteria as $sub)
                                            @php
                                                $isSelected = request(strtolower($kf->kode)) !== null && abs((float)request(strtolower($kf->kode)) - (float)$sub->nilai) < 0.001;
                                            @endphp
                                            <option value="{{ $sub->nilai }}" {{ $isSelected ? 'selected' : '' }}>
                                                {{ $sub->keterangan }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endforeach

                            <div class="d-flex align-items-center gap-2 mt-4 pt-3 border-top">
                                <button type="submit" class="btn btn-primary btn-sm px-3 fw-bold flex-grow-1">
                                    Cari Wisata
                                </button>
                                <a href="{{ route('destinasi.index') }}" class="btn btn-outline-secondary btn-sm fw-semibold">
                                    Reset
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- KOLOM KANAN: Hasil Rekomendasi -->
            <div class="col-lg-9 col-md-8">
                <div class="card shadow border-0 rounded-4 overflow-hidden">
                    <div class="card-header bg-primary text-white py-3">
                        <h6 class="mb-0 fw-bold"><i class="bi bi-award-fill me-2"></i>Hasil Rekomendasi</h6>
                    </div>
                    <div class="card-body p-4 bg-light">
                        @if($destinasi->count() > 0)
                            <div class="row g-4">
                                @foreach($destinasi as $item)
                                    <div class="col-lg-4 col-md-6 col-12">
                                        <div class="card h-100 shadow-sm border-0 hover-card transition-all">
                                            <!-- Foto Destinasi -->
                                            <div class="position-relative" style="height: 180px; overflow: hidden; border-top-left-radius: 0.375rem; border-top-right-radius: 0.375rem;">
                                                @if($item->foto && file_exists(public_path('images/destinasi/' . $item->foto)))
                                                    <img src="{{ asset('images/destinasi/' . $item->foto) }}"
                                                         class="card-img-top"
                                                         alt="{{ $item->nama }}"
                                                         style="object-fit: cover; height: 100%; width: 100%; transition: transform 0.3s ease;">
                                                @else
                                                    <div class="bg-secondary d-flex align-items-center justify-content-center h-100 text-white">
                                                        <i class="bi bi-image" style="font-size: 2.5rem;"></i>
                                                    </div>
                                                @endif

                                                <!-- Badge Kategori -->
                                                <span class="position-absolute top-0 end-0 m-2 badge bg-primary shadow-sm px-2 py-1 small" style="font-size: 0.7rem;">
                                                    {{ $item->kategori }}
                                                </span>
                                            </div>

                                            <div class="card-body d-flex flex-column p-3">
                                                <h6 class="card-title fw-bold mb-2 text-primary">
                                                    @if(isset($item->ranking))
                                                        #{{ $item->ranking }} {{ $item->nama }}
                                                    @else
                                                        {{ $item->nama }}
                                                    @endif
                                                </h6>

                                                <p class="card-text text-secondary small flex-grow-1" style="font-size: 0.8rem; line-height: 1.4;">
                                                    {{ Str::limit($item->deskripsi, 90) }}
                                                </p>

                                                <!-- Info -->
                                                <div class="border-top pt-2 mt-2">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <span class="fw-bold text-success" style="font-size: 0.9rem;">
                                                            Rp {{ number_format($item->harga_tiket, 0, ',', '.') }}
                                                        </span>
                                                        @if(isset($item->nilai_k))
                                                            <span class="badge bg-light text-primary border border-primary-subtle" style="font-size: 0.7rem;" title="Nilai Utilitas ARAS">
                                                                K: {{ number_format($item->nilai_k, 3) }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <!-- Actions -->
                                                <a href="{{ route('destinasi.show', $item->id) }}"
                                                   class="btn btn-primary btn-sm w-100 fw-bold mt-3 py-1.5 shadow-sm">
                                                    Selengkapnya
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
                            <div class="text-center py-5">
                                <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                                <h5 class="mt-3 text-muted">Tidak ada destinasi ditemukan</h5>
                                <p class="text-muted small">Coba ubah kata kunci pencarian atau filter Anda.</p>
                                <a href="{{ route('destinasi.index') }}" class="btn btn-sm btn-outline-primary fw-bold">
                                    <i class="bi bi-arrow-clockwise"></i> Reset Filter
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
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
