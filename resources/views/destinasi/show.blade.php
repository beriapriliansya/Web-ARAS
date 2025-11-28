@extends('layouts.app')

@section('title', $destinasi->nama)

@section('content')
<!-- Breadcrumb -->
<div class="container mt-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('destinasi.index') }}">Destinasi</a></li>
            <li class="breadcrumb-item active">{{ $destinasi->nama }}</li>
        </ol>
    </nav>
</div>

<!-- Main Content -->
<div class="container my-4">
    <div class="row">
        <!-- Left Column: Info -->
        <div class="col-lg-8">
            <!-- Header -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <span class="badge bg-primary mb-2">{{ $destinasi->kategori }}</span>
                            <h1 class="display-5 fw-bold mb-0">{{ $destinasi->nama }}</h1>
                        </div>
                        <div class="text-end">
                            <a href="{{ route('destinasi.edit', $destinasi->id) }}" class="btn btn-warning">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                        </div>
                    </div>

                    <!-- Quick Info -->
                    <div class="row g-3">
                        <div class="col-md-6">
                            <p class="mb-2">
                                <i class="bi bi-geo-alt-fill text-danger"></i>
                                <strong>Lokasi:</strong><br>
                                <span class="text-muted">{{ $destinasi->alamat }}</span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2">
                                <i class="bi bi-cash text-success"></i>
                                <strong>Harga Tiket:</strong><br>
                                <span class="fs-5 fw-bold text-success">
                                    Rp {{ number_format($destinasi->harga_tiket, 0, ',', '.') }}
                                </span>
                            </p>
                        </div>
                        @if($destinasi->jam_buka && $destinasi->jam_tutup)
                        <div class="col-md-6">
                            <p class="mb-2">
                                <i class="bi bi-clock text-primary"></i>
                                <strong>Jam Operasional:</strong><br>
                                <span class="text-muted">
                                    {{ \Carbon\Carbon::parse($destinasi->jam_buka)->format('H:i') }} -
                                    {{ \Carbon\Carbon::parse($destinasi->jam_tutup)->format('H:i') }} WIB
                                </span>
                            </p>
                        </div>
                        @endif
                        @if($destinasi->telepon)
                        <div class="col-md-6">
                            <p class="mb-2">
                                <i class="bi bi-telephone text-info"></i>
                                <strong>Telepon:</strong><br>
                                <span class="text-muted">{{ $destinasi->telepon }}</span>
                            </p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Deskripsi -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-info-circle"></i> Deskripsi</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted" style="text-align: justify;">
                        {{ $destinasi->deskripsi }}
                    </p>
                </div>
            </div>

            <!-- Fasilitas -->
            @if($destinasi->fasilitas && count($destinasi->fasilitas) > 0)
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-house-check"></i> Fasilitas</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @foreach($destinasi->fasilitas as $fasilitas)
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                    <span>{{ $fasilitas }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Nilai Kriteria ARAS -->
            @if($destinasi->alternatif->count() > 0)
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-bar-chart"></i> Nilai Kriteria</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Kriteria</th>
                                    <th>Nilai</th>
                                    <th>Satuan</th>
                                    <th>Tipe</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($destinasi->alternatif as $alt)
                                    <tr>
                                        <td>
                                            <strong>{{ $alt->kriteria->nama_kriteria }}</strong>
                                            <br>
                                            <small class="text-muted">({{ $alt->kriteria->kode }})</small>
                                        </td>
                                        <td class="fw-bold">{{ $alt->nilai }}</td>
                                        <td>{{ $alt->kriteria->satuan }}</td>
                                        <td>
                                            @if($alt->kriteria->tipe == 'benefit')
                                                <span class="badge bg-success">Benefit</span>
                                            @else
                                                <span class="badge bg-warning">Cost</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <!-- Hasil ARAS -->
            @if($destinasi->hasilAras)
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-trophy"></i> Hasil Analisis ARAS</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center g-3">
                        <div class="col-md-3">
                            <div class="p-3 bg-light rounded">
                                <h2 class="fw-bold text-primary mb-0">{{ $destinasi->hasilAras->ranking }}</h2>
                                <small class="text-muted">Ranking</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3 bg-light rounded">
                                <h2 class="fw-bold text-success mb-0">
                                    {{ number_format($destinasi->hasilAras->utilitas, 4) }}
                                </h2>
                                <small class="text-muted">Utilitas</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3 bg-light rounded">
                                <h2 class="fw-bold text-info mb-0">
                                    {{ number_format($destinasi->hasilAras->persentase, 2) }}%
                                </h2>
                                <small class="text-muted">Persentase</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3 bg-light rounded">
                                <h6 class="fw-bold text-warning mb-0">
                                    {{ $destinasi->hasilAras->kategori_performa }}
                                </h6>
                                <small class="text-muted">Kategori</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Right Column: Map & Actions -->
        <div class="col-lg-4">
            <!-- Google Maps -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-map"></i> Lokasi</h5>
                </div>
                <div class="card-body p-0">
                    <div id="map" style="height: 400px; width: 100%;"></div>
                </div>
                <div class="card-footer bg-white">
                    <div class="d-grid gap-2">
                        <a href="https://www.google.com/maps/dir/?api=1&destination={{ $destinasi->latitude }},{{ $destinasi->longitude }}"
                           target="_blank"
                           class="btn btn-primary">
                            <i class="bi bi-compass"></i> Buka di Google Maps
                        </a>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-share"></i> Bagikan</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button class="btn btn-outline-primary" onclick="shareToFacebook()">
                            <i class="bi bi-facebook"></i> Facebook
                        </button>
                        <button class="btn btn-outline-info" onclick="shareToTwitter()">
                            <i class="bi bi-twitter"></i> Twitter
                        </button>
                        <button class="btn btn-outline-success" onclick="shareToWhatsApp()">
                            <i class="bi bi-whatsapp"></i> WhatsApp
                        </button>
                        <button class="btn btn-outline-secondary" onclick="copyLink()">
                            <i class="bi bi-link-45deg"></i> Copy Link
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Initialize Google Maps
    function initMap() {
        const destinasiLat = {{ $destinasi->latitude }};
        const destinasiLng = {{ $destinasi->longitude }};
        const destinasiNama = "{{ $destinasi->nama }}";

        // Create map
        const map = new google.maps.Map(document.getElementById('map'), {
            center: { lat: destinasiLat, lng: destinasiLng },
            zoom: 15,
            mapTypeControl: true,
            streetViewControl: true,
            fullscreenControl: true,
        });

        // Add marker
        const marker = new google.maps.Marker({
            position: { lat: destinasiLat, lng: destinasiLng },
            map: map,
            title: destinasiNama,
            animation: google.maps.Animation.DROP,
        });

        // Info window
        const infoWindow = new google.maps.InfoWindow({
            content: `
                <div style="padding: 10px;">
                    <h6 class="fw-bold mb-2">${destinasiNama}</h6>
                    <p class="mb-1 small">{{ $destinasi->kategori }}</p>
                    <p class="mb-0 small text-muted">{{ Str::limit($destinasi->alamat, 50) }}</p>
                </div>
            `
        });

        marker.addListener('click', () => {
            infoWindow.open(map, marker);
        });

        // Auto open info window
        infoWindow.open(map, marker);
    }

    // Load map when page ready
    window.addEventListener('load', initMap);

    // Share Functions
    function shareToFacebook() {
        const url = encodeURIComponent(window.location.href);
        window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}`, '_blank');
    }

    function shareToTwitter() {
        const url = encodeURIComponent(window.location.href);
        const text = encodeURIComponent('{{ $destinasi->nama }} - Destinasi wisata di Pesawaran');
        window.open(`https://twitter.com/intent/tweet?url=${url}&text=${text}`, '_blank');
    }

    function shareToWhatsApp() {
        const url = encodeURIComponent(window.location.href);
        const text = encodeURIComponent('Lihat destinasi wisata: {{ $destinasi->nama }}');
        window.open(`https://wa.me/?text=${text} ${url}`, '_blank');
    }

    function copyLink() {
        navigator.clipboard.writeText(window.location.href).then(() => {
            alert('Link berhasil disalin!');
        });
    }
</script>
@endpush
