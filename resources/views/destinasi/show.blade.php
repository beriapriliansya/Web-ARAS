<x-app-layout>

    <!-- Style Tambahan untuk Efek Teks -->
    <style>
        .text-shadow-large {
            text-shadow: 2px 4px 10px rgba(0, 0, 0, 0.7);
        }
        .text-shadow-small {
            text-shadow: 1px 2px 5px rgba(0, 0, 0, 0.6);
        }
        .hero-banner {
            height: 450px;
            background-color: #1a1e21;
        }
        @media (max-width: 768px) {
            .hero-banner {
                height: 300px;
            }
        }
    </style>

    <!-- Hero Banner Section -->
    <div class="position-relative w-100 overflow-hidden hero-banner">
        @if($destinasi->foto && file_exists(public_path('images/destinasi/' . $destinasi->foto)))
            <img src="{{ asset('images/destinasi/' . $destinasi->foto) }}"
                 alt="{{ $destinasi->nama }}"
                 class="position-absolute top-50 start-50 translate-middle w-100 h-100"
                 style="object-fit: cover; filter: brightness(0.6);">
        @else
            <!-- Default Gradient Fallback if Image doesn't exist -->
            <div class="w-100 h-100 d-flex align-items-center justify-content-center"
                 style="background: linear-gradient(135deg, #1f4068 0%, #162447 100%);">
                <i class="bi bi-geo-alt text-white opacity-10" style="font-size: 8rem; position: absolute;"></i>
            </div>
        @endif

        <!-- Centered Text Content Overlay -->
        <div class="position-absolute top-50 start-50 translate-middle text-center text-white px-3 w-100" style="z-index: 5;">
            <div class="container">
                <span class="badge bg-primary px-3 py-2 text-uppercase fw-bold mb-3 shadow-sm" style="font-size: 0.85rem; letter-spacing: 1px;">
                    {{ $destinasi->kategori }}
                </span>
                <h1 class="display-3 fw-bold mb-2 text-shadow-large" style="font-family: 'Outfit', 'Inter', sans-serif;">
                    {{ $destinasi->nama }}
                </h1>
                <p class="lead mb-0 text-shadow-small opacity-90 fs-5">
                    <i class="bi bi-geo-alt-fill text-danger me-1"></i> {{ $destinasi->alamat }}
                </p>
            </div>
        </div>
    </div>

    <div class="container py-5">
        <div class="row g-5">

            <!-- KOLOM KIRI: Informasi Detail -->
            <div class="col-lg-8">
                <!-- Deskripsi -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body p-4">
                        <h4 class="fw-bold mb-3 text-primary"><i class="bi bi-info-circle me-2"></i>Tentang Destinasi</h4>
                        <p class="text-secondary leading-relaxed" style="text-align: justify; line-height: 1.8;">
                            {!! nl2br(e($destinasi->deskripsi)) !!}
                        </p>
                    </div>
                </div>

                <!-- Fasilitas Grid -->
                @if($destinasi->fasilitas && count($destinasi->fasilitas) > 0)
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3"><i class="bi bi-grid-fill me-2"></i>Fasilitas Tersedia</h5>
                        <div class="row g-3">
                            @foreach($destinasi->fasilitas as $fasilitas)
                                <div class="col-md-4 col-6">
                                    <div class="d-flex align-items-center p-2 border rounded bg-light">
                                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                                        <span class="fw-medium text-dark">{{ $fasilitas }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- Peta Lokasi -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3 text-primary"><i class="bi bi-map-fill me-2"></i>Peta Lokasi</h5>
                        
                        <!-- Leaflet Map Container -->
                        <div id="map" style="height: 380px; width: 100%; z-index: 1;" class="rounded-4 shadow-sm border mb-4"></div>

                        <!-- Lokasi & Jarak Info Panel -->
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded-3 h-100 border">
                                    <h6 class="fw-bold text-dark mb-2"><i class="bi bi-geo-alt-fill text-danger me-1"></i>Titik Koordinat</h6>
                                    <p class="text-secondary mb-0 small">
                                        Latitude: <code>{{ $destinasi->latitude }}</code><br>
                                        Longitude: <code>{{ $destinasi->longitude }}</code>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 rounded-3 h-100 border text-white" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
                                    <h6 class="fw-bold mb-2"><i class="bi bi-truck me-1"></i>Jarak Dari Pusat Kota</h6>
                                    <p class="mb-0 fs-5 fw-bold">{{ $jarakText }}</p>
                                    <small class="opacity-75">*Dari Pusat Kota Bandar Lampung</small>
                                </div>
                            </div>
                        </div>

                        <!-- Action Button to Google Maps Navigation -->
                        <div class="text-center mt-4">
                            <a href="https://www.google.com/maps/dir/?api=1&destination={{ $destinasi->latitude }},{{ $destinasi->longitude }}"
                               target="_blank"
                               class="btn btn-primary px-4 py-2 rounded-pill fw-bold shadow-sm">
                                <i class="bi bi-compass-fill me-1.5"></i> Buka Rute di Google Maps
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Leaflet CSS & JS CDN -->
                <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
                <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
                
                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        var lat = {{ (float)$destinasi->latitude }};
                        var lng = {{ (float)$destinasi->longitude }};
                        
                        // Inisialisasi peta Leaflet
                        var map = L.map('map', {
                            scrollWheelZoom: false // Mencegah zoom tidak sengaja saat scroll
                        }).setView([lat, lng], 13);
                        
                        // Gunakan tile OpenStreetMap
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                        }).addTo(map);
                        
                        // Tambahkan penanda (Marker) di lokasi destinasi
                        var marker = L.marker([lat, lng]).addTo(map);
                        marker.bindPopup("<div class='text-center'><strong>{{ $destinasi->nama }}</strong><br><small class='text-muted'>{{ $destinasi->alamat }}</small></div>").openPopup();
                    });
                </script>
            </div> <!-- Close col-lg-8 -->

            <!-- KOLOM KANAN: Card Booking (Sticky) -->
            <div class="col-lg-4">
                <div class="card shadow border-0 sticky-top" style="top: 100px; z-index: 99;">
                    <div class="card-body p-4">
                        <h5 class="fw-bold text-dark mb-4">Jadwalkan Kunjungan</h5>

                        <!-- Harga -->
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                            <span class="text-muted">Harga Tiket</span>
                            <span class="fs-4 fw-bold text-primary">Rp {{ number_format($destinasi->harga_tiket, 0, ',', '.') }}</span>
                        </div>

                        <!-- Jam Buka -->
                        <div class="mb-4">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-clock text-secondary me-2"></i>
                                <span class="fw-bold">Jam Operasional</span>
                            </div>
                            <div class="bg-light p-2 rounded text-center text-sm">
                                {{ $destinasi->jam_buka ? \Carbon\Carbon::parse($destinasi->jam_buka)->format('H:i') : '08:00' }} -
                                {{ $destinasi->jam_tutup ? \Carbon\Carbon::parse($destinasi->jam_tutup)->format('H:i') : '17:00' }} WIB
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-top text-center">
                            <span class="text-muted small d-block mb-2">Bagikan destinasi ini:</span>
                            <div class="d-flex justify-content-center gap-2">
                                <button class="btn btn-sm btn-outline-primary rounded-circle" style="width: 35px; height: 35px;"><i class="bi bi-facebook"></i></button>
                                <button class="btn btn-sm btn-outline-info rounded-circle" style="width: 35px; height: 35px;"><i class="bi bi-twitter"></i></button>
                                <button class="btn btn-sm btn-outline-success rounded-circle" style="width: 35px; height: 35px;"><i class="bi bi-whatsapp"></i></button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
