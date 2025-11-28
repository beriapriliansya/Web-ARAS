@extends('layouts.app')

@section('title', 'Rekomendasi Destinasi Wisata')

@section('content')
<!-- Hero Header -->
<div class="bg-primary text-white py-5">
    <div class="container text-center">
        <h1 class="display-3 fw-bold mb-3">
            <i class="bi bi-stars"></i> Rekomendasi Destinasi Wisata
        </h1>
        <p class="lead">
            Destinasi wisata terbaik di Kabupaten Pesawaran berdasarkan Metode ARAS
        </p>
    </div>
</div>

<!-- Content -->
<div class="container my-5">
    @if($hasilAras->count() > 0)
        <!-- Top 3 -->
        <div class="row g-4 mb-5">
            @foreach($hasilAras->take(3) as $index => $hasil)
                <div class="col-md-4">
                    <div class="card h-100 {{ $index == 0 ? 'border-warning border-3' : '' }}">
                        <div class="card-body text-center">
                            <!-- Medal -->
                            <div class="mb-3" style="font-size: 4rem;">
                                @if($index == 0)
                                    🥇
                                @elseif($index == 1)
                                    🥈
                                @else
                                    🥉
                                @endif
                            </div>

                            <!-- Info -->
                            <span class="badge bg-primary mb-2">{{ $hasil->destinasi->kategori }}</span>
                            <h3 class="fw-bold mb-3">{{ $hasil->destinasi->nama }}</h3>

                            <!-- Stats -->
                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <div class="p-2 bg-light rounded">
                                        <small class="text-muted d-block">Ranking</small>
                                        <strong class="fs-4 text-primary">{{ $hasil->ranking }}</strong>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-2 bg-light rounded">
                                        <small class="text-muted d-block">Utilitas</small>
                                        <strong class="fs-4 text-success">{{ number_format($hasil->utilitas, 4) }}</strong>
                                    </div>
                                </div>
                            </div>

                            <!-- Progress -->
                            <div class="progress mb-3" style="height: 30px;">
                                <div class="progress-bar bg-success"
                                     role="progressbar"
                                     style="width: {{ $hasil->persentase }}%">
                                    {{ number_format($hasil->persentase, 2) }}%
                                </div>
                            </div>

                            <!-- Badge -->
                            <span class="badge bg-success mb-3 px-3 py-2">
                                {{ $hasil->kategori_performa }}
                            </span>

                            <!-- Description -->
                            <p class="text-muted small mb-3">
                                {{ Str::limit($hasil->destinasi->deskripsi, 100) }}
                            </p>

                            <!-- Quick Info -->
                            <div class="text-start mb-3">
                                <p class="mb-1 small">
                                    <i class="bi bi-geo-alt-fill text-danger"></i>
                                    {{ Str::limit($hasil->destinasi->alamat, 40) }}
                                </p>
                                <p class="mb-1 small">
                                    <i class="bi bi-cash text-success"></i>
                                    Rp {{ number_format($hasil->destinasi->harga_tiket, 0, ',', '.') }}
                                </p>
                            </div>

                            <!-- Button -->
                            <a href="{{ route('destinasi.show', $hasil->destinasi->id) }}"
                               class="btn btn-primary w-100">
                                <i class="bi bi-eye"></i> Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- All Rankings -->
        <div class="card">
            <div class="card-header bg-white">
                <h4 class="mb-0"><i class="bi bi-list-ol"></i> Semua Destinasi ({{ $hasilAras->count() }})</h4>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 80px;">Rank</th>
                                <th>Destinasi</th>
                                <th>Kategori</th>
                                <th style="width: 120px;">Utilitas</th>
                                <th style="width: 200px;">Performa</th>
                                <th style="width: 150px;">Harga Tiket</th>
                                <th style="width: 100px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($hasilAras as $hasil)
                                <tr>
                                    <td class="text-center">
                                        @if($hasil->ranking <= 3)
                                            @if($hasil->ranking == 1)
                                                <span class="fs-3">🥇</span>
                                            @elseif($hasil->ranking == 2)
                                                <span class="fs-3">🥈</span>
                                            @else
                                                <span class="fs-3">🥉</span>
                                            @endif
                                        @else
                                            <span class="badge bg-secondary fs-6">
                                                {{ $hasil->ranking }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $hasil->destinasi->nama }}</strong>
                                        <br>
                                        <small class="text-muted">
                                            <i class="bi bi-geo-alt"></i>
                                            {{ Str::limit($hasil->destinasi->alamat, 50) }}
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ $hasil->destinasi->kategori }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <strong class="text-success">
                                            {{ number_format($hasil->utilitas, 4) }}
                                        </strong>
                                    </td>
                                    <td>
                                        <div class="progress" style="height: 25px;">
                                            <div class="progress-bar
                                                @if($hasil->utilitas >= 0.9) bg-success
                                                @elseif($hasil->utilitas >= 0.7) bg-primary
                                                @elseif($hasil->utilitas >= 0.5) bg-warning
                                                @else bg-danger
                                                @endif"
                                                 role="progressbar"
                                                 style="width: {{ $hasil->persentase }}%">
                                                {{ number_format($hasil->persentase, 2) }}%
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <strong class="text-success">
                                            Rp {{ number_format($hasil->destinasi->harga_tiket, 0, ',', '.') }}
                                        </strong>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('destinasi.show', $hasil->destinasi->id) }}"
                                           class="btn btn-sm btn-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Info Footer -->
        <div class="alert alert-info mt-4">
            <div class="d-flex align-items-center">
                <i class="bi bi-info-circle-fill fs-3 me-3"></i>
                <div>
                    <strong>Informasi:</strong> Ranking di atas dihitung menggunakan <strong>Metode ARAS</strong>
                    berdasarkan kriteria: Harga Tiket, Jarak, Fasilitas, Rating Pengunjung, dan Aksesibilitas Jalan.
                    Perhitungan terakhir: <strong>{{ $hasilAras->first()->tanggal_hitung->format('d F Y, H:i') }} WIB</strong>
                </div>
            </div>
        </div>

    @else
        <!-- Empty State -->
        <div class="text-center py-5">
            <i class="bi bi-calculator text-muted" style="font-size: 5rem;"></i>
            <h3 class="mt-4 text-muted">Belum Ada Hasil Perhitungan</h3>
            <p class="text-muted mb-4">
                Silakan lakukan perhitungan ARAS terlebih dahulu untuk mendapatkan rekomendasi destinasi
            </p>
            <a href="{{ route('admin.aras.index') }}" class="btn btn-primary btn-lg">
                <i class="bi bi-calculator"></i> Hitung Metode ARAS
            </a>
        </div>
    @endif
</div>
@endsection
