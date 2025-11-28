@extends('layouts.app')

@section('title', 'Tentang Kami')

@section('content')
<!-- Header -->
<div class="bg-primary text-white py-5">
    <div class="container text-center">
        <h1 class="display-4 fw-bold mb-3">
            <i class="bi bi-info-circle"></i> Tentang Kami
        </h1>
        <p class="lead">Sistem Informasi Pariwisata Kabupaten Pesawaran</p>
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
                        berdasarkan berbagai kriteria seperti harga tiket, jarak, fasilitas, rating pengunjung, dan aksesibilitas.
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

            <div class="card">
                <div class="card-body">
                    <h3 class="fw-bold mb-4">
                        <i class="bi bi-gear text-warning"></i> Teknologi yang Digunakan
                    </h3>
                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="text-center p-3 bg-light rounded">
                                <i class="bi bi-code-slash text-primary" style="font-size: 3rem;"></i>
                                <h5 class="mt-3 fw-bold">Frontend</h5>
                                <ul class="list-unstyled text-start mt-3">
                                    <li>✅ HTML5 & CSS3</li>
                                    <li>✅ Bootstrap 5.3</li>
                                    <li>✅ JavaScript ES6+</li>
                                    <li>✅ Vite</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-3 bg-light rounded">
                                <i class="bi bi-server text-success" style="font-size: 3rem;"></i>
                                <h5 class="mt-3 fw-bold">Backend</h5>
                                <ul class="list-unstyled text-start mt-3">
                                    <li>✅ Laravel 10</li>
                                    <li>✅ PHP 8.1+</li>
                                    <li>✅ MySQL Database</li>
                                    <li>✅ XAMPP Server</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-3 bg-light rounded">
                                <i class="bi bi-star text-warning" style="font-size: 3rem;"></i>
                                <h5 class="mt-3 fw-bold">Features</h5>
                                <ul class="list-unstyled text-start mt-3">
                                    <li>✅ Metode ARAS</li>
                                    <li>✅ Google Maps API</li>
                                    <li>✅ Responsive Design</li>
                                    <li>✅ RESTful API</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
