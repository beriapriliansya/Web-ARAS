<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Google Fonts: Plus Jakarta Sans -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- BOOTSTRAP 5 CSS & ICONS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

        <style>
            body {
                font-family: 'Plus Jakarta Sans', sans-serif;
                background-color: #f4f7f6;
                overflow-x: hidden;
            }
            
            /* Split Screen Layout */
            .auth-container {
                min-height: 100vh;
            }

            .auth-sidebar {
                background: linear-gradient(135deg, rgba(30, 60, 114, 0.9) 0%, rgba(42, 82, 152, 0.72) 100%), 
                            url('https://images.unsplash.com/photo-1506929562872-bb421503ef21?q=80&w=1368&auto=format&fit=crop') no-repeat center center;
                background-size: cover;
                position: relative;
                color: #ffffff;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                padding: 3rem;
            }

            .auth-form-section {
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 2.5rem;
                background-color: #fafbfc;
            }

            /* Glassmorphism Card on Sidebar */
            .glass-card {
                background: rgba(255, 255, 255, 0.1);
                backdrop-filter: blur(12px);
                -webkit-backdrop-filter: blur(12px);
                border: 1px solid rgba(255, 255, 255, 0.2);
                border-radius: 1.25rem;
                box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.15);
            }

            /* Form Elements Styling */
            .auth-card {
                width: 100%;
                max-width: 440px;
                background: transparent;
            }

            .form-control {
                border-radius: 10px;
                padding: 0.75rem 1rem;
                border: 1px solid #e2e8f0;
                font-size: 0.95rem;
                background-color: #ffffff;
                transition: all 0.3s ease;
            }

            .form-control:focus {
                border-color: #2a5298;
                box-shadow: 0 0 0 4px rgba(42, 82, 152, 0.12);
                background-color: #ffffff;
            }

            .input-group-text {
                background-color: #ffffff;
                border: 1px solid #e2e8f0;
                color: #a0aec0;
                border-radius: 10px;
                padding-left: 1.1rem;
                padding-right: 1.1rem;
                transition: all 0.3s ease;
            }

            .input-group:focus-within .input-group-text {
                border-color: #2a5298;
                color: #2a5298;
            }

            .btn-primary {
                background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
                border: none;
                border-radius: 10px;
                padding: 0.8rem 1.5rem;
                font-weight: 600;
                font-size: 1rem;
                letter-spacing: 0.3px;
                transition: all 0.3s ease;
            }

            .btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 20px rgba(42, 82, 152, 0.3);
                background: linear-gradient(135deg, #2a5298 0%, #1e3c72 100%);
            }

            .btn-primary:active {
                transform: translateY(0);
            }

            .text-blue {
                color: #2a5298;
            }
            
            .text-blue:hover {
                color: #1e3c72;
            }

            .bg-blue-light {
                background-color: rgba(42, 82, 152, 0.1);
                color: #2a5298;
            }

            a {
                text-decoration: none;
                transition: color 0.2s ease;
            }
            
            a:hover {
                text-decoration: none;
            }
        </style>
    </head>
    <body>
        <div class="container-fluid p-0">
            <div class="row g-0 auth-container">
                
                <!-- KIRI: Gambar & Info (Hanya tampil di layar besar) -->
                <div class="col-lg-6 col-xl-7 auth-sidebar d-none d-lg-flex">
                    <!-- Top Logo -->
                    <div class="d-flex align-items-center">
                        <a href="/" class="d-flex align-items-center text-decoration-none text-white">
                            <div class="bg-white p-2 rounded-3 me-3 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                                <i class="bi bi-geo-alt-fill text-blue" style="font-size: 1.5rem;"></i>
                            </div>
                            <div>
                                <h4 class="fw-bold m-0 tracking-wide">Wisata Pesawaran</h4>
                                <span class="text-white-50 small">Sistem Pendukung Keputusan</span>
                            </div>
                        </a>
                    </div>

                    <!-- Center Card (Glassmorphism) -->
                    <div class="glass-card p-4 p-xl-5 my-auto" style="max-width: 550px;">
                        <span class="badge bg-blue-light px-3 py-2 rounded-pill fw-bold mb-3">Metode ARAS</span>
                        <h2 class="fw-extrabold text-white mb-3" style="line-height: 1.3;">Temukan Destinasi Wisata Air Terbaik Secara Objektif</h2>
                        <p class="text-white-80 mb-0" style="line-height: 1.6; font-size: 1.05rem;">
                            Dengan mengintegrasikan preferensi kriteria Anda (Aksesibilitas, Fasilitas, Kebersihan, Keamanan, Harga, dan Daya Tarik), sistem menghitung rekomendasi terbaik menggunakan metode <strong>Additive Ratio Assessment (ARAS)</strong>.
                        </p>
                    </div>

                    <!-- Bottom Info -->
                    <div class="text-white-50 small d-flex justify-content-between">
                        <span>&copy; {{ date('Y') }} Dinas Pariwisata Kabupaten Pesawaran</span>
                        <span>V1.0</span>
                    </div>
                </div>

                <!-- KANAN: Form (Login / Register) -->
                <div class="col-lg-6 col-xl-5 auth-form-section">
                    <div class="auth-card">
                        <!-- Logo for mobile only -->
                        <div class="text-center d-lg-none mb-4">
                            <a href="/" class="d-inline-flex align-items-center text-decoration-none">
                                <i class="bi bi-geo-alt-fill text-blue me-2" style="font-size: 2rem;"></i>
                                <h3 class="fw-bold text-dark m-0">Wisata Pesawaran</h3>
                            </a>
                        </div>
                        
                        <!-- Form Slot -->
                        {{ $slot }}
                    </div>
                </div>

            </div>
        </div>

        <!-- BOOTSTRAP JS BUNDLE -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
