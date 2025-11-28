<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm sticky-top">
    <div class="container">
        <!-- 1. LOGO / BRAND -->
        <a class="navbar-brand fw-bold text-primary d-flex align-items-center" href="{{ route('home') }}">
            <!-- Ikon Peta (Bootstrap Icons) -->
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
                {{-- Cek apakah route destinasi ada --}}
                @if(Route::has('destinasi.index'))
                <li class="nav-item">
                    <a class="nav-link px-3 {{ request()->routeIs('destinasi.*') || request()->routeIs('admin.destinasi.*') ? 'active fw-bold text-primary' : '' }}"
                    href="{{ (auth()->check() && (auth()->user()->role === 'superadmin' || auth()->user()->role === 'admin')) ? route('admin.destinasi.index') : route('destinasi.index') }}">
                        Destinasi
                    </a>
                </li>
                @endif
                {{-- Cek apakah route aras ranking ada --}}
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
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center px-3" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="bg-primary text-white rounded-circle d-flex justify-content-center align-items-center me-2" style="width: 32px; height: 32px; font-weight: bold;">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <span class="fw-medium text-dark">{{ Auth::user()->name }}</span>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-2 rounded-3" aria-labelledby="userDropdown">
                            <!-- Menu Admin (Hanya muncul jika role admin) -->
                            @if(auth()->user()->role === 'admin')
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('admin.dashboard') }}">
                                        <i class="bi bi-speedometer2 me-2 text-warning"></i> Dashboard Admin
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                            @endif

                            <!-- Menu User Biasa -->
                            <li>
                                <a class="dropdown-item py-2" href="{{ route('profile.edit') }}">
                                    <i class="bi bi-person-circle me-2 text-primary"></i> Profile Saya
                                </a>
                            </li>

                            @if(Route::has('booking.index'))
                            <li>
                                <a class="dropdown-item py-2" href="{{ route('booking.index') }}">
                                    <i class="bi bi-ticket-perforated me-2 text-success"></i> Tiket Saya
                                </a>
                            </li>
                            @endif

                            <li><hr class="dropdown-divider"></li>

                            <!-- Tombol Logout -->
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item py-2 text-danger fw-bold">
                                        <i class="bi bi-box-arrow-right me-2"></i> Log Out
                                    </button>
                                </form>
                            </li>
                        </ul>
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
