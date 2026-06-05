@extends('layouts.app')

@section('title', 'Kontak Kami')

@section('content')
<!-- Header -->
<div class="container mt-4">
    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="card-body py-3 d-flex justify-content-between align-items-center flex-wrap gap-3 border-start border-primary border-5">
            <div>
                <h2 class="fw-bold mb-1 text-primary m-0">
                    <i class="bi bi-envelope-fill text-warning"></i> Kontak Kami
                </h2>
                <p class="text-muted mb-0 small">Hubungi kami untuk informasi lebih lanjut</p>
            </div>
        </div>
    </div>
</div>

<!-- Content -->
<div class="container my-5">
    <div class="row g-4">
        <!-- Contact Info -->
        <div class="col-lg-5">
            <div class="card h-100">
                <div class="card-body">
                    <h4 class="fw-bold mb-4">Informasi Kontak</h4>

                    <div class="mb-4">
                        <div class="d-flex align-items-start mb-3">
                            <i class="bi bi-geo-alt-fill text-danger fs-3 me-3"></i>
                            <div>
                                <h6 class="fw-bold mb-1">Alamat</h6>
                                <p class="text-muted mb-0">
                                    Jl. Raya Pesawaran No. 123<br>
                                    Gedong Tataan, Kabupaten Pesawaran<br>
                                    Lampung 35371
                                </p>
                            </div>
                        </div>

                        <div class="d-flex align-items-start mb-3">
                            <i class="bi bi-telephone-fill text-success fs-3 me-3"></i>
                            <div>
                                <h6 class="fw-bold mb-1">Telepon</h6>
                                <p class="text-muted mb-0">
                                    +62 812-3456-7890<br>
                                    +62 813-4567-8901
                                </p>
                            </div>
                        </div>

                        <div class="d-flex align-items-start mb-3">
                            <i class="bi bi-envelope-fill text-primary fs-3 me-3"></i>
                            <div>
                                <h6 class="fw-bold mb-1">Email</h6>
                                <p class="text-muted mb-0">
                                    info@pariwisatapesawaran.com<br>
                                    support@pariwisatapesawaran.com
                                </p>
                            </div>
                        </div>

                        <div class="d-flex align-items-start">
                            <i class="bi bi-clock-fill text-warning fs-3 me-3"></i>
                            <div>
                                <h6 class="fw-bold mb-1">Jam Operasional</h6>
                                <p class="text-muted mb-0">
                                    Senin - Jumat: 08:00 - 16:00 WIB<br>
                                    Sabtu: 08:00 - 12:00 WIB<br>
                                    Minggu & Libur: Tutup
                                </p>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <h6 class="fw-bold mb-3">Media Sosial</h6>
                    <div class="d-flex gap-3">
                        <a href="#" class="btn btn-outline-primary">
                            <i class="bi bi-facebook fs-5"></i>
                        </a>
                        <a href="#" class="btn btn-outline-info">
                            <i class="bi bi-twitter fs-5"></i>
                        </a>
                        <a href="#" class="btn btn-outline-danger">
                            <i class="bi bi-instagram fs-5"></i>
                        </a>
                        <a href="#" class="btn btn-outline-danger">
                            <i class="bi bi-youtube fs-5"></i>
                        </a>
                        <a href="#" class="btn btn-outline-success">
                            <i class="bi bi-whatsapp fs-5"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Form -->
        <div class="col-lg-7">
            <div class="card h-100">
                <div class="card-body">
                    <h4 class="fw-bold mb-4">Kirim Pesan</h4>

                    <form>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Nama Lengkap *</label>
                                <input type="text" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Email *</label>
                                <input type="email" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Nomor Telepon</label>
                                <input type="tel" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Subjek *</label>
                                <input type="text" class="form-control" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold">Pesan *</label>
                                <textarea class="form-control" rows="6" required></textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary px-5">
                                    <i class="bi bi-send"></i> Kirim Pesan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Map -->
    <div class="card mt-4">
        <div class="card-body p-0">
            <div id="map-kontak" style="height: 400px; width: 100%;"></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Google Maps for contact page
    function initMap() {
        // Koordinat Kantor (contoh: Gedong Tataan)
        const kantorPos = { lat: -5.4513206, lng: 105.4027861 };

        const map = new google.maps.Map(document.getElementById('map-kontak'), {
            center: kantorPos,
            zoom: 14,
        });

        const marker = new google.maps.Marker({
            position: kantorPos,
            map: map,
            title: 'Pariwisata Pesawaran Office',
        });

        const infoWindow = new google.maps.InfoWindow({
            content: `
                <div style="padding: 10px;">
                    <h6 class="fw-bold">Pariwisata Pesawaran</h6>
                    <p class="mb-0 small">Jl. Raya Pesawaran No. 123<br>Gedong Tataan, Pesawaran</p>
                </div>
            `
        });

        marker.addListener('click', () => {
            infoWindow.open(map, marker);
        });
    }

    window.addEventListener('load', initMap);
</script>
@endpush
