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
                        <h5 class="fw-bold mb-3"><i class="bi bi-map-fill me-2"></i>Lokasi</h5>
                        <div class="bg-light rounded p-3 text-center">
                            <p class="mb-2">Koordinat: <code>{{ $destinasi->latitude }}, {{ $destinasi->longitude }}</code></p>
                            <a href="https://www.google.com/maps/search/?api=1&query={{ $destinasi->latitude }},{{ $destinasi->longitude }}"
                               target="_blank"
                               class="btn btn-outline-primary">
                                <i class="bi bi-box-arrow-up-right me-1"></i> Buka di Google Maps
                            </a>
                        </div>
                    </div>
                </div>
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

                        <!-- TOMBOL ACTION (LOGIKA KUNCI) -->
                        <div class="d-grid gap-2 mb-3">
                            @auth
                                <a href="{{ route('booking.create', $destinasi->id) }}" class="btn btn-primary btn-lg fw-bold py-3 shadow-sm">
                                    <i class="bi bi-ticket-perforated me-2"></i> Pesan Tiket Sekarang
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-primary btn-lg fw-bold py-3 shadow-sm"
                                   onclick="return confirm('Anda harus Login terlebih dahulu untuk memesan tiket. Lanjutkan ke halaman Login?');">
                                    <i class="bi bi-lock-fill me-2"></i> Login untuk Memesan
                                </a>
                                <div class="text-center mt-2">
                                    <small class="text-muted">Belum punya akun? <a href="{{ route('register') }}" class="text-primary fw-bold">Daftar disini</a></small>
                                </div>
                            @endauth
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
