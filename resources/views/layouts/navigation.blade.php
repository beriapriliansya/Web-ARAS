<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm sticky-top">
    <div class="container">
        <!-- 1. LOGO / BRAND -->
        <a class="navbar-brand fw-bold text-primary d-flex align-items-center" href="{{ route('home') }}">
            <i class="bi bi-geo-alt-fill me-2 fs-4"></i>
            <span>Wisata Pesawaran</span>
        </a>

        <!-- 2. TOMBOL HAMBURGER (Mobile) -->
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- 3. ISI MENU -->
        <div class="collapse navbar-collapse" id="navbarContent">

            <!-- MENU KIRI (Navigasi Utama) -->
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 py-2">
                <!-- Home -->
                <li class="nav-item">
                    <a class="nav-link px-3 {{ request()->routeIs('home') ? 'active fw-bold text-primary' : '' }}" href="{{ route('home') }}">
                        Home
                    </a>
                </li>

                <!-- Menu Destinasi (Pintar: Admin ke Kelola, User ke Lihat) -->
                @if(Route::has('destinasi.index'))
                <li class="nav-item">
                    <a class="nav-link px-3 {{ request()->routeIs('destinasi.*') || request()->routeIs('admin.destinasi.*') ? 'active fw-bold text-primary' : '' }}"
                       href="{{ (auth()->check() && (auth()->user()->role === 'superadmin' || auth()->user()->role === 'admin')) ? route('admin.destinasi.index') : route('destinasi.index') }}">
                        Destinasi
                    </a>
                </li>
                @endif

                <!-- MENU KHUSUS SUPERADMIN: Kalkulasi ARAS (Dropdown) -->
                @if(auth()->check() && auth()->user()->role === 'superadmin')
                <li class="nav-item dropdown" x-data="{ open: false }" @click.outside="open = false">
                    <a class="nav-link dropdown-toggle px-3 {{ request()->routeIs('admin.aras.*') ? 'active fw-bold text-primary' : '' }}" 
                       href="#" id="navbarDropdownAras" role="button" 
                       @click.prevent="open = !open"
                       data-bs-toggle="dropdown" aria-expanded="false">
                        Perhitungan ARAS
                    </a>
                    <ul class="dropdown-menu shadow border-0" :class="{ 'show': open }" x-show="open" x-transition
                        aria-labelledby="navbarDropdownAras" style="display: none;">
                        <li>
                            <a class="dropdown-item py-2 {{ request()->routeIs('admin.aras.kriteria.list') ? 'active fw-bold' : '' }}" href="{{ route('admin.aras.kriteria.list') }}">
                                <i class="bi bi-list-check me-2"></i> Kriteria
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item py-2 {{ request()->routeIs('admin.aras.kriteria.edit') ? 'active fw-bold' : '' }}" href="{{ route('admin.aras.kriteria.edit') }}">
                                <i class="bi bi-sliders me-2"></i> Bobot
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item py-2 {{ request()->routeIs('admin.aras.penilaian') ? 'active fw-bold' : '' }}" href="{{ route('admin.aras.penilaian') }}">
                                <i class="bi bi-table me-2"></i> Penilaian
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item py-2 {{ request()->routeIs('admin.aras.panduan') ? 'active fw-bold' : '' }}" href="{{ route('admin.aras.panduan') }}">
                                <i class="bi bi-book me-2"></i> Panduan Konversi
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item py-2 {{ request()->routeIs('admin.aras.index') ? 'active fw-bold' : '' }}" href="{{ route('admin.aras.index') }}">
                                <i class="bi bi-calculator me-2"></i> Hasil & Kalkulasi
                            </a>
                        </li>
                    </ul>
                </li>
                @endif

                <!-- Menu Rekomendasi (Hanya User/Public, Admin tidak butuh ini di navbar) -->
                @if(Route::has('aras.ranking') && (!auth()->check() || auth()->user()->role === 'user'))
                <li class="nav-item">
                    <a class="nav-link px-3 {{ request()->routeIs('aras.ranking') ? 'active fw-bold text-primary' : '' }}" href="{{ route('aras.ranking') }}">
                        Rekomendasi
                    </a>
                </li>
                @endif

                <!-- MENU KHUSUS SUPERADMIN: Manage Berita -->
                <!-- Ini yang tadi ketinggalan -->
                @if(auth()->check() && (auth()->user()->role === 'superadmin' || auth()->user()->role === 'admin'))
                <li class="nav-item">
                    <a class="nav-link px-3 {{ request()->routeIs('admin.news.*') ? 'active fw-bold text-primary' : '' }}" href="{{ route('admin.news.index') }}">
                        Manajemen Berita
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3 {{ request()->routeIs('admin.fasilitas.*') ? 'active fw-bold text-primary' : '' }}" href="{{ route('admin.fasilitas.index') }}">
                        Manajemen Fasilitas
                    </a>
                </li>
                @endif

                <!-- Menu Berita (Hanya User/Public, Admin tidak butuh ini di navbar) -->
                @if(Route::has('news.index') && (!auth()->check() || auth()->user()->role === 'user'))
                <li class="nav-item">
                    <a class="nav-link px-3 {{ request()->routeIs('news.index') ? 'active fw-bold text-primary' : '' }}" href="{{ route('news.index') }}">
                        Berita
                    </a>
                </li>
                @endif

                <!-- Menu Tentang -->
                <li class="nav-item">
                    <a class="nav-link px-3 {{ request()->routeIs('tentang') ? 'active fw-bold text-primary' : '' }}" href="{{ route('tentang') }}">
                        Tentang
                    </a>
                </li>

            </ul>

            <!-- MENU KANAN (User Authentication) -->
            <ul class="navbar-nav ms-auto align-items-lg-center mb-2 mb-lg-0">
                @auth
                    @php
                        \App\Models\UserNotification::ensureTableExists();
                        $userNotifications = \App\Models\UserNotification::getUserNotifications(auth()->id())->take(5);
                        $unreadNotificationCount = \App\Models\UserNotification::getUnreadCount(auth()->id());
                    @endphp
                    <!-- JIKA SUDAH LOGIN -->
                    <li class="nav-item d-flex align-items-center gap-3">

                        <!-- Lonceng Notifikasi (Link ke Halaman Notifikasi) -->
                        <a href="{{ route('notifications.index') }}" class="btn border rounded-circle d-flex justify-content-center align-items-center position-relative shadow-sm me-2" style="width: 40px; height: 40px; background-color: #ffffff;" title="Notifikasi">
                            <i class="bi bi-bell text-dark fs-5"></i>
                            @if($unreadNotificationCount > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="unread-count-badge" style="font-size: 0.55rem; padding: 0.25em 0.5em; top: 5px !important; left: 32px !important;">
                                    {{ $unreadNotificationCount }}
                                </span>
                            @endif
                        </a>

                        <!-- Nama User (Link Cerdas: Admin ke Dashboard, User ke Profil) -->
                        <a href="{{ (auth()->user()->role === 'admin' || auth()->user()->role === 'superadmin') ? route('admin.dashboard') : route('profile.edit') }}"
                           class="text-decoration-none fw-bold text-dark d-flex align-items-center bg-light px-3 py-1 rounded-pill border hover-shadow transition">
                            <div class="bg-primary text-white rounded-circle d-flex justify-content-center align-items-center me-2" style="width: 30px; height: 30px; font-size: 0.8rem;">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <span class="small">{{ Str::limit(Auth::user()->name, 15) }}</span>
                        </a>

                        <!-- Tombol Logout (Direct Form - Anti Macet) -->
                        <form method="POST" action="{{ route('logout') }}" class="m-0">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm fw-bold px-3 rounded-pill" onclick="return confirm('Apakah Anda yakin ingin keluar?');">
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

    <style>
        .hover-shadow:hover { box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075); text-decoration: none; }
        @media (min-width: 992px) {
            .nav-item.dropdown:hover .dropdown-menu {
                display: block !important;
                margin-top: 0;
            }
        }
    </style>
</nav>
