<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm sticky-top">
    <div class="container">
        <!-- 1. LOGO / BRAND -->
        <a class="navbar-brand fw-bold text-primary d-flex align-items-center" href="{{ route('home') }}">
            <i class="bi bi-geo-alt-fill me-2 fs-4"></i>
            <span>Wisata Pesawaran</span>
        </a>

        <!-- 2. TOMBOL HAMBURGER (Untuk Tampilan HP) -->
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- 3. ISI MENU (Collapsible) -->
        <div class="collapse navbar-collapse" id="navbarContent">

            <!-- Menu Kiri (Navigasi Utama) -->
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 py-2">
                <li class="nav-item">
                    <a class="nav-link px-3 {{ request()->routeIs('home') ? 'active fw-bold text-primary' : '' }}" href="{{ route('home') }}">
                        Home
                    </a>
                </li>

                @if(Route::has('destinasi.index'))
                <li class="nav-item">
                    <a class="nav-link px-3 {{ request()->routeIs('destinasi.*') || request()->routeIs('admin.destinasi.*') ? 'active fw-bold text-primary' : '' }}"
                       href="{{ (auth()->check() && (auth()->user()->role === 'superadmin' || auth()->user()->role === 'admin')) ? route('admin.destinasi.index') : route('destinasi.index') }}">
                        Destinasi
                    </a>
                </li>
                @endif

                @if(Route::has('aras.ranking'))
                <li class="nav-item">
                    <a class="nav-link px-3 {{ request()->routeIs('aras.ranking') ? 'active fw-bold text-primary' : '' }}" href="{{ route('aras.ranking') }}">
                        Rekomendasi ARAS
                    </a>
                </li>
                @endif
            </ul>

            <!-- Menu Kanan (User Authentication) -->
            <ul class="navbar-nav ms-auto align-items-lg-center mb-2 mb-lg-0">
                @auth
                    <!-- JIKA SUDAH LOGIN -->
                    <li class="nav-item d-flex align-items-center gap-3">
                        <!-- 1. Nama User (Link ke Dashboard/Profile) -->
                        <a href="{{ (auth()->user()->role === 'admin' || auth()->user()->role === 'superadmin') ? route('admin.dashboard') : route('profile.edit') }}"
                           class="text-decoration-none fw-bold text-dark d-flex align-items-center">
                            <div class="bg-primary text-white rounded-circle d-flex justify-content-center align-items-center me-2" style="width: 35px; height: 35px;">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <span>{{ Auth::user()->name }}</span>
                        </a>

                        <!-- 2. Tombol Logout (Langsung Form) -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm fw-bold px-3 rounded-pill">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </button>
                        </form>
                    </li>
                @else
                    <!-- JIKA BELUM LOGIN (Tamu) -->
                    <li class="nav-item d-flex gap-2 ms-lg-3 mt-2 mt-lg-0">
                        <a href="{{ route('login') }}" class="btn btn-outline-primary px-4 rounded-pill fw-bold">
                            Log In
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-primary px-4 rounded-pill fw-bold text-white shadow-sm">
                                Register
                            </a>
                        @endif
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
