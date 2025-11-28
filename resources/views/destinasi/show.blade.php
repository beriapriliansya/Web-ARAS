<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $destinasi->nama }}
        </h2>
    </x-slot>

    <!-- Hero Image / Foto Utama -->
    <div class="relative w-full h-64 md:h-96 bg-gray-200">
        @if($destinasi->foto)
            <img src="{{ asset('images/destinasi/'.$destinasi->foto) }}"
                 alt="{{ $destinasi->nama }}"
                 class="w-full h-full object-cover">
        @else
            <div class="w-full h-full flex items-center justify-center bg-secondary text-white">
                <i class="bi bi-image fs-1"></i>
            </div>
        @endif
        <div class="absolute inset-0 bg-black bg-opacity-40 flex items-end">
            <div class="container mx-auto px-4 py-6">
                <span class="badge bg-primary mb-2">{{ $destinasi->kategori }}</span>
                <h1 class="text-white text-3xl md:text-5xl font-bold drop-shadow-lg">{{ $destinasi->nama }}</h1>
                <p class="text-white text-opacity-90 mt-2"><i class="bi bi-geo-alt-fill"></i> {{ $destinasi->alamat }}</p>
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

                <!-- Ulasan (Placeholder) -->
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3"><i class="bi bi-star-fill text-warning me-2"></i>Ulasan Pengunjung</h5>
                        <!-- Logika ulasan bisa ditambahkan nanti -->
                        <p class="text-muted">Belum ada ulasan untuk destinasi ini.</p>
                    </div>
                </div>
            </div>

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
                        <div class="d-grid gap-2">
                            @auth
                                {{-- JIKA SUDAH LOGIN: Masuk ke halaman booking --}}
                                <a href="{{ route('booking.create', $destinasi->id) }}" class="btn btn-primary btn-lg fw-bold py-3 shadow-sm">
                                    <i class="bi bi-ticket-perforated me-2"></i> Pesan Tiket Sekarang
                                </a>
                            @else
                                {{-- JIKA BELUM LOGIN: Arahkan ke Login --}}
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
