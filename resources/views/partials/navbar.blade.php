<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">
            <i class="bi bi-geo-alt-fill"></i> Pariwisata Pesawaran
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                        <i class="bi bi-house-fill"></i> Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('destinasi.*') ? 'active' : '' }}" href="{{ route('destinasi.index') }}">
                        <i class="bi bi-pin-map-fill"></i> Destinasi
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('aras.*') ? 'active' : '' }}" href="{{ route('aras.ranking') }}">
                        <i class="bi bi-stars"></i> Rekomendasi
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-gear-fill"></i> Lainnya
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" href="{{ route('aras.index') }}">
                                <i class="bi bi-calculator"></i> Perhitungan ARAS
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="{{ route('tentang') }}">
                                <i class="bi bi-info-circle"></i> Tentang
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('kontak') }}">
                                <i class="bi bi-envelope"></i> Kontak
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
