<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@yield('meta_description', 'Sistem Informasi Pariwisata Kabupaten Pesawaran dengan Metode ARAS')">
    <meta name="keywords" content="@yield('meta_keywords', 'pariwisata, pesawaran, wisata, ARAS, rekomendasi')">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Pariwisata Pesawaran') - Sistem Rekomendasi Destinasi Wisata</title>

    <!-- Vite CSS & JS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Google Maps API -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCnMki_UaMHA56B2gcZwgPVmVmL23tXKII&libraries=places"></script>

    <!-- Custom CSS per halaman -->
    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    @include('partials.navbar')

    <!-- Alert Messages -->
    @if(session('success'))
    <div class="container mt-3">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            <strong>Berhasil!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="container mt-3">
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <strong>Error!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
    @endif

    @if($errors->any())
    <div class="container mt-3">
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <strong>Terjadi kesalahan:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
    @endif

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    @include('partials.footer')

    <!-- Scroll to Top Button (created by JavaScript) -->

    <!-- Custom JavaScript per halaman -->
    @stack('scripts')
</body>
</html>
