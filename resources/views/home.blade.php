@extends('layouts.app')

@section('title', 'Home')

@section('content')
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
<div class="bg-light py-5">
    <div class="container">
        <div class="row text-center g-4">
            <div class="col-md-3">
                <div class="p-4">
                    <i class="bi bi-pin-map-fill text-primary" style="font-size: 3rem;"></i>
                    <h3 class="fw-bold mt-3 mb-0">{{ $totalDestinasi }}+</h3>
                    <p class="text-muted">Destinasi Wisata</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-4">
                    <i class="bi bi-list-check text-success" style="font-size: 3rem;"></i>
                    <h3 class="fw-bold mt-3 mb-0">{{ $totalKriteria }}</h3>
                    <p class="text-muted">Kriteria Penilaian</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-4">
                    <i class="bi bi-star-fill text-warning" style="font-size: 3rem;"></i>
                    <h3 class="fw-bold mt-3 mb-0">4.8/5</h3>
                    <p class="text-muted">Rating Pengguna</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-4">
                    <i class="bi bi-award-fill text-danger" style="font-size: 3rem;"></i>
                    <h3 class="fw-bold mt-3 mb-0">99%</h3>
                    <p class="text-muted">Akurasi Rekomendasi</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Top 3 Destinasi -->
@if($topDestinasi->count() > 0)
<div class="container my-5">
    <div class="text-center mb-5">
        <h2 class="display-5 fw-bold text-gradient mb-3">
            🏆 Top 3 Destinasi Wisata Terbaik
        </h2>
        <p class="lead text-muted">
            Berdasarkan perhitungan Metode ARAS
        </p>
    </div>

    <div class="row g-4">
        @foreach($topDestinasi as $index => $hasil)
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        @if($index == 0)
                            <div class="fs-1 mb-3">🥇</div>
                        @elseif($index == 1)
                            <div class="fs-1 mb-3">🥈</div>
                        @else
                            <div class="fs-1 mb-3">🥉</div>
                        @endif

                        <h5 class="card-title fw-bold">{{ $hasil->destinasi->nama }}</h5>
                        <p class="text-muted mb-2">
                            <i class="bi bi-geo-alt"></i> {{ $hasil->destinasi->kategori }}
                        </p>

                        <div class="progress mb-3" style="height: 25px;">
                            <div class="progress-bar bg-success" role="progressbar"
                                 style="width: {{ $hasil->persentase }}%">
                                {{ number_format($hasil->persentase, 2) }}%
                            </div>
                        </div>

                        <p class="mb-3">
                            <span class="badge bg-primary">Ranking {{ $hasil->ranking }}</span>
                            <span class="badge bg-success">Utilitas: {{ number_format($hasil->utilitas, 4) }}</span>
                        </p>

                        <a href="{{ route('destinasi.show', $hasil->destinasi->id) }}" class="btn btn-primary">
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
                <div class="card h-100">
                    <div class="card-body">
                        <span class="badge bg-primary mb-2">{{ $destinasi->kategori }}</span>
                        <h5 class="card-title fw-bold">{{ $destinasi->nama }}</h5>
                        <p class="card-text text-muted">
                            {{ Str::limit($destinasi->deskripsi, 100) }}
                        </p>
                        <p class="mb-2">
                            <i class="bi bi-geo-alt-fill text-danger"></i>
                            <small>{{ Str::limit($destinasi->alamat, 50) }}</small>
                        </p>
                        <p class="mb-3">
                            <i class="bi bi-cash text-success"></i>
                            <strong>Rp {{ number_format($destinasi->harga_tiket, 0, ',', '.') }}</strong>
                        </p>
                        <a href="{{ route('destinasi.show', $destinasi->id) }}" class="btn btn-outline-primary">
                            <i class="bi bi-arrow-right-circle"></i> Selengkapnya
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="text-center mt-4">
        <a href="{{ route('destinasi.index') }}" class="btn btn-primary btn-lg">
            <i class="bi bi-grid-3x3-gap"></i> Lihat Semua Destinasi
        </a>
    </div>
</div>

<!-- CTA Section -->
<div class="bg-light py-5">
    <div class="container text-center">
        <h2 class="display-5 fw-bold mb-4">Siap Menjelajah Pesawaran?</h2>
        <p class="lead text-muted mb-4">
            Dapatkan rekomendasi destinasi wisata terbaik sesuai preferensi Anda dengan metode ARAS
        </p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
            <a href="{{ route('aras.ranking') }}" class="btn btn-primary btn-lg">
                <i class="bi bi-rocket-takeoff-fill"></i> Lihat Rekomendasi
            </a>
            <a href="{{ route('aras.index') }}" class="btn btn-outline-primary btn-lg">
                <i class="bi bi-calculator"></i> Perhitungan ARAS
            </a>
        </div>
    </div>
</div>
@endsection
