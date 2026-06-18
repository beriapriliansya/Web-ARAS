<x-app-layout>
<!-- Hero Section -->
<div class="hero-section text-center" id="home">
    <div class="container">
        <h1 class="display-3 fw-bold mb-3">
            <i class="bi bi-compass"></i> Selamat Datang di<br>
            <span class="text-white">Pariwisata Pesawaran</span>
        </h1>
        <p class="lead mb-4 fs-4">
            🌴 Temukan destinasi wisata terbaik dengan sistem rekomendasi cerdas<br>
            menggunakan <strong>Metode ARAS</strong> (Additive Ratio Assessment)
        </p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
            <a href="{{ route('destinasi.index') }}" class="btn btn-light btn-lg">
                <i class="bi bi-search"></i> Cari Destinasi
            </a>
            <a href="{{ route('aras.ranking') }}" class="btn btn-outline-light btn-lg">
                <i class="bi bi-stars"></i> Lihat Rekomendasi
            </a>
        </div>
    </div>
</div>

<!-- Stats Section -->
    <div class="bg-white py-5 shadow-sm position-relative" style="z-index: 10;">
        <div class="container">
            <div class="row text-center g-4">
                <div class="col-md-3">
                    <div class="p-4 rounded bg-light h-100">
                        <i class="bi bi-pin-map-fill text-primary" style="font-size: 3rem;"></i>
                        <h3 class="fw-bold mt-3 mb-0">{{ $totalDestinasi }}+</h3>
                        <p class="text-muted">Destinasi Wisata</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-4 rounded bg-light h-100">
                        <i class="bi bi-list-check text-success" style="font-size: 3rem;"></i>
                        <h3 class="fw-bold mt-3 mb-0">{{ $totalKriteria }}</h3>
                        <p class="text-muted">Kriteria Penilaian</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-4 rounded bg-light h-100">
                        <i class="bi bi-people text-warning" style="font-size: 3rem;"></i>
                        <h3 class="fw-bold mt-3 mb-0">{{ $totalUser }}</h3>
                        <p class="text-muted">Pengguna terdaftar</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-4 rounded bg-light h-100">
                        <i class="bi bi-award-fill text-danger" style="font-size: 3rem;"></i>
                        <h3 class="fw-bold mt-3 mb-0">{{ $totalHasilAras }}</h3>
                        <p class="text-muted">Rekomendasi Dihasilkan</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top 3 Destinasi -->
    @if(isset($topDestinasi) && $topDestinasi->count() > 0)
    <div class="container my-5 py-5">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold text-gradient mb-3">
                🏆 Top 3 Destinasi Wisata Terbaik
            </h2>
            <p class="lead text-muted">
                Berdasarkan perhitungan Metode ARAS (Sistem Pendukung Keputusan)
            </p>
        </div>

        <div class="row g-4">
            @foreach($topDestinasi as $index => $hasil)
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm border-0 hover-card">
                        <div class="card-body text-center p-4">
                            @if($index == 0)
                                <div class="display-1 mb-3">🥇</div>
                            @elseif($index == 1)
                                <div class="display-1 mb-3">🥈</div>
                            @else
                                <div class="display-1 mb-3">🥉</div>
                            @endif

                            <h5 class="card-title fw-bold fs-4">{{ $hasil->destinasi->nama ?? 'Nama Destinasi' }}</h5>
                            <p class="text-muted mb-2">
                                <i class="bi bi-geo-alt"></i> {{ $hasil->destinasi->kategori ?? 'Umum' }}
                            </p>

                            <div class="progress mb-3" style="height: 25px;">
                                <div class="progress-bar bg-success" role="progressbar"
                                     style="width: {{ min(100, $hasil->nilai_k * 100) }}%">
                                    {{ number_format($hasil->nilai_k * 100, 2) }}%
                                </div>
                            </div>

                            <p class="mb-3">
                                <span class="badge bg-primary rounded-pill px-3">Ranking {{ $hasil->ranking }}</span>
                                <span class="badge bg-info text-dark rounded-pill px-3">Nilai K: {{ number_format($hasil->nilai_k, 4) }}</span>
                            </p>

                            <a href="{{ route('destinasi.show', $hasil->destinasi_id) }}" class="btn btn-outline-primary w-100">
                                <i class="bi bi-eye"></i> Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Destinasi Terbaru -->
    <div class="container my-5">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold text-gradient mb-3">
                ✨ Destinasi Wisata Terbaru
            </h2>
            <p class="lead text-muted">
                Jelajahi destinasi wisata menarik di Kabupaten Pesawaran
            </p>
        </div>

        <div class="row g-4">
            @foreach($destinasiTerbaru as $destinasi)
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm border-0">
                        <!-- Foto Destinasi -->
                        <div class="position-relative" style="height: 200px; overflow: hidden; border-top-left-radius: 0.25rem; border-top-right-radius: 0.25rem;">
                            @if($destinasi->foto && file_exists(public_path('images/destinasi/' . $destinasi->foto)))
                                <img src="{{ asset('images/destinasi/' . $destinasi->foto) }}" 
                                     class="card-img-top" 
                                     alt="{{ $destinasi->nama }}"
                                     style="object-fit: cover; height: 100%; width: 100%;">
                            @else
                                <div class="bg-secondary text-white d-flex align-items-center justify-content-center h-100">
                                    <i class="bi bi-image fs-1"></i>
                                </div>
                            @endif
                        </div>

                        <div class="card-body">
                            <span class="badge bg-primary mb-2">{{ $destinasi->kategori }}</span>
                            <h5 class="card-title fw-bold">{{ $destinasi->nama }}</h5>
                            <p class="card-text text-muted small">
                                {{ \Illuminate\Support\Str::limit($destinasi->deskripsi, 100) }}
                            </p>
                            <p class="mb-2 small text-secondary">
                                <i class="bi bi-geo-alt-fill text-danger"></i>
                                {{ \Illuminate\Support\Str::limit($destinasi->alamat, 40) }}
                            </p>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <span class="text-success fw-bold">
                                    Rp {{ number_format($destinasi->harga_tiket, 0, ',', '.') }}
                                </span>
                                <a href="{{ route('destinasi.show', $destinasi->id) }}" class="btn btn-sm btn-outline-primary">
                                    Detail <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="text-center mt-5">
            <a href="{{ route('destinasi.index') }}" class="btn btn-primary btn-lg px-5">
                <i class="bi bi-grid-3x3-gap"></i> Lihat Semua Destinasi
            </a>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-light py-5 mt-5">
        <div class="container text-center py-4">
            <h2 class="display-5 fw-bold mb-4">Siap Menjelajah Pesawaran?</h2>
            <p class="lead text-muted mb-4">
                Dapatkan rekomendasi destinasi wisata terbaik sesuai preferensi Anda dengan metode ARAS sekarang juga!
            </p>
            <div class="d-flex gap-3 justify-content-center flex-wrap">
                <a href="{{ route('aras.ranking') }}" class="btn btn-primary btn-lg px-4">
                    <i class="bi bi-rocket-takeoff-fill"></i> Mulai Rekomendasi
                </a>
                @auth
                    @if(auth()->user()->role === 'superadmin')
                    <a href="{{ route('admin.aras.index') }}" class="btn btn-outline-primary btn-lg px-4">
                        <i class="bi bi-calculator"></i> Kelola ARAS
                    </a>
                    @endif
                @endauth
            </div>
        </div>
    </div>
</x-app-layout>
