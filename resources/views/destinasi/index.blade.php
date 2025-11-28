@extends('layouts.app')

@section('title', 'Daftar Destinasi Wisata')

@section('content')
<!-- Page Header -->
<div class="bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="display-4 fw-bold mb-2">
                    <i class="bi bi-pin-map-fill"></i> Destinasi Wisata
                </h1>
                <p class="lead mb-0">
                    Jelajahi {{ $destinasi->total() }} destinasi wisata menarik di Kabupaten Pesawaran
                </p>
            </div>
            <div class="col-md-4 text-md-end">
                @auth
                    <a href="{{ route('booking.index') }}" class="btn btn-light btn-lg">
                        <i class="bi bi-ticket-perforated"></i> Booking Saya
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-light btn-lg">
                        <i class="bi bi-box-arrow-in-right"></i> Login untuk Booking
                    </a>
                @endauth
            </div>
        </div>
    </div>
</div>

<!-- Filter & Search Section -->
<div class="container my-4">
    <div class="card">
        <div class="card-body">
            <form action="{{ route('destinasi.index') }}" method="GET" class="row g-3">
                <!-- Search -->
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" class="form-control" name="search"
                               placeholder="Cari destinasi..."
                               value="{{ request('search') }}">
                    </div>
                </div>

                <!-- Filter Kategori -->
                <div class="col-md-4">
                    <select class="form-select" name="kategori">
                        <option value="">Semua Kategori</option>
                        @foreach($kategoriList as $kat)
                            <option value="{{ $kat }}"
                                    {{ request('kategori') == $kat ? 'selected' : '' }}>
                                {{ $kat }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Button -->
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-funnel"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Destinasi Grid -->
<div class="container my-5">
    @if($destinasi->count() > 0)
        <div class="row g-4">
            @foreach($destinasi as $item)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100">
                        <!-- Foto Destinasi -->
                        <div class="position-relative" style="height: 200px; overflow: hidden;">
                            @if($item->foto)
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
                            <span class="position-absolute top-0 end-0 m-2 badge bg-primary">
                                {{ $item->kategori }}
                            </span>
                        </div>

                        <div class="card-body">
                            <h5 class="card-title fw-bold mb-2">{{ $item->nama }}</h5>

                            <p class="card-text text-muted small">
                                {{ Str::limit($item->deskripsi, 100) }}
                            </p>

                            <!-- Info -->
                            <div class="mb-3">
                                <p class="mb-1 small">
                                    <i class="bi bi-geo-alt-fill text-danger"></i>
                                    {{ Str::limit($item->alamat, 50) }}
                                </p>
                                <p class="mb-1">
                                    <i class="bi bi-cash text-success"></i>
                                    <strong>Rp {{ number_format($item->harga_tiket, 0, ',', '.') }}</strong>
                                </p>

                                @if($item->fasilitas && count($item->fasilitas) > 0)
                                    <p class="mb-0 small text-muted">
                                        <i class="bi bi-house-check"></i>
                                        {{ count($item->fasilitas) }} Fasilitas
                                    </p>
                                @endif
                            </div>

                            <!-- Actions -->
                            <div class="d-flex gap-2">
                                <a href="{{ route('destinasi.show', $item->id) }}"
                                   class="btn btn-primary btn-sm flex-grow-1">
                                    <i class="bi bi-eye"></i> Detail
                                </a>
                                @auth
                                    <a href="{{ route('booking.create', $item->id) }}"
                                       class="btn btn-success btn-sm">
                                        <i class="bi bi-cart-plus"></i> Book
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-5">
            {{ $destinasi->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <i class="bi bi-inbox text-muted" style="font-size: 5rem;"></i>
            <h3 class="mt-3 text-muted">Tidak ada destinasi ditemukan</h3>
            <p class="text-muted">Coba ubah filter atau kata kunci pencarian</p>
            <a href="{{ route('destinasi.index') }}" class="btn btn-primary">
                <i class="bi bi-arrow-clockwise"></i> Reset Filter
            </a>
        </div>
    @endif
</div>
@endsection 
