@extends('layouts.app')

@section('title', 'Tentang Kami')

@section('content')
<!-- Header -->
<div class="container mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-body py-3 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2 class="fw-bold mb-1 text-primary m-0">
                    <i class="bi bi-info-circle"></i> Tentang Kami
                </h2>
                <p class="text-muted mb-0 small">Sistem Informasi Pariwisata Kabupaten Pesawaran</p>
            </div>
        </div>
    </div>
</div>

<!-- Content -->
<div class="container my-5">
    <div class="row g-4">
        <!-- About System -->
        <div class="col-lg-12">
            <div class="card mb-4">
                <div class="card-body">
                    <h3 class="fw-bold mb-3">
                        <i class="bi bi-laptop text-primary"></i> Tentang Sistem
                    </h3>
                    <p style="text-align: justify;">
                        Website <strong>Pariwisata Pesawaran</strong> adalah sistem informasi pariwisata berbasis web
                        yang dikembangkan untuk membantu wisatawan dalam menemukan dan memilih destinasi wisata terbaik
                        di Kabupaten Pesawaran, Lampung. Sistem ini menggunakan metode <strong>ARAS (Additive Ratio Assessment)</strong>,
                        sebuah metode pengambilan keputusan multi-kriteria, untuk memberikan rekomendasi destinasi wisata
                        berdasarkan berbagai kriteria seperti aksesibilitas, fasilitas, kebersihan, keamanan, harga tiket, dan daya tarik.
                    </p>
                    <p style="text-align: justify;">
                        Dengan integrasi <strong>Google Maps API</strong>, pengguna dapat melihat lokasi destinasi secara visual
                        dan mendapatkan petunjuk arah dengan mudah. Website ini dibangun menggunakan teknologi modern seperti
                        Laravel 10, Bootstrap 5, dan Vite untuk memastikan performa yang cepat dan tampilan yang responsif
                        di berbagai perangkat.
                    </p>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-body">
                    <h3 class="fw-bold mb-3">
                        <i class="bi bi-bullseye text-success"></i> Tujuan & Manfaat
                    </h3>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <h5 class="fw-bold text-success">Tujuan:</h5>
                            <ul>
                                <li>Memudahkan wisatawan dalam mencari informasi destinasi wisata</li>
                                <li>Memberikan rekomendasi destinasi terbaik secara objektif</li>
                                <li>Meningkatkan promosi pariwisata Kabupaten Pesawaran</li>
                                <li>Membantu pengambilan keputusan dalam memilih destinasi</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h5 class="fw-bold text-primary">Manfaat:</h5>
                            <ul>
                                <li>Menghemat waktu dalam perencanaan perjalanan wisata</li>
                                <li>Mendapatkan informasi akurat dan terkini</li>
                                <li>Akses mudah ke lokasi dengan Google Maps</li>
                                <li>Membandingkan destinasi secara objektif</li>
                            </ul>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>
@endsection
