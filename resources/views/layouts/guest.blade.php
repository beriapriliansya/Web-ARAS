<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- ======================================================= -->
        <!-- WAJIB: BOOTSTRAP 5 CSS & ICONS (CDN) -->
        <!-- ======================================================= -->
        <!-- Tanpa link ini, halaman login akan terlihat berantakan -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

        {{-- MATIKAN CSS TAILWIND BAWAAN BIAR GAK BENTROK --}}
        {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}

        <style>
            body {
                background-color: #f8f9fa; /* Background abu-abu muda */
                font-family: 'Figtree', sans-serif;
            }
            .auth-card {
                width: 100%;
                max-width: 420px; /* Batasi lebar form login */
                border-radius: 12px;
                overflow: hidden;
            }
            .auth-logo {
                font-size: 2rem;
                color: #0d6efd; /* Bootstrap Primary Color */
            }
            /* Style khusus link supaya tidak ada garis bawah default */
            a { text-decoration: none; }
            a:hover { text-decoration: underline; }
        </style>
    </head>
    <body>
        <div class="min-vh-100 d-flex flex-column justify-content-center align-items-center py-4">

            <!-- 1. Logo Website -->
            <div class="mb-4 text-center">
                <a href="/" class="d-flex flex-column align-items-center text-decoration-none">
                    <!-- Ganti Icon sesuai keinginan -->
                    <i class="bi bi-geo-alt-fill auth-logo mb-2"></i>
                    <h3 class="fw-bold text-dark m-0">Wisata Pesawaran</h3>
                </a>
            </div>

            <!-- 2. Kotak Form (Slot Login/Register masuk sini) -->
            <div class="card auth-card shadow-lg border-0">
                <div class="card-body p-4 p-md-5">
                    {{ $slot }}
                </div>
            </div>

            <!-- Footer kecil -->
            <div class="mt-4 text-center text-muted small">
                &copy; {{ date('Y') }} Pariwisata Pesawaran. All rights reserved.
            </div>
        </div>

        <!-- ======================================================= -->
        <!-- WAJIB: BOOTSTRAP JS BUNDLE -->
        <!-- ======================================================= -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
