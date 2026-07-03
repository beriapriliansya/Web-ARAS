<footer class="text-white text-center py-5 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4 mb-md-0">
                <h5 class="fw-bold mb-3">
                    <i class="bi bi-geo-alt-fill"></i> Pariwisata Pesawaran
                </h5>
                <p class="text-white-50">
                    Sistem Informasi Pariwisata dengan implementasi Metode ARAS untuk rekomendasi destinasi wisata terbaik di Kabupaten Pesawaran.
                </p>
            </div>

            <div class="col-md-4 mb-4 mb-md-0">
                <h5 class="fw-bold mb-3">Quick Links</h5>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="{{ route('home') }}" class="text-white-50 text-decoration-none">
                            <i class="bi bi-house"></i> Home
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('destinasi.index') }}" class="text-white-50 text-decoration-none">
                            <i class="bi bi-pin-map"></i> Destinasi Wisata
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('aras.ranking') }}" class="text-white-50 text-decoration-none">
                            <i class="bi bi-stars"></i> Rekomendasi
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('tentang') }}" class="text-white-50 text-decoration-none">
                            <i class="bi bi-info-circle"></i> Tentang Kami
                        </a>
                    </li>
                </ul>
            </div>

            <div class="col-md-4">
                <h5 class="fw-bold mb-3">Ikuti Kami</h5>
                <div class="d-flex gap-3 justify-content-center">
                    <a href="#" class="text-white fs-4" title="Facebook">
                        <i class="bi bi-facebook"></i>
                    </a>
                    <a href="#" class="text-white fs-4" title="Instagram">
                        <i class="bi bi-instagram"></i>
                    </a>
                    <a href="#" class="text-white fs-4" title="Twitter">
                        <i class="bi bi-twitter"></i>
                    </a>
                    <a href="#" class="text-white fs-4" title="YouTube">
                        <i class="bi bi-youtube"></i>
                    </a>
                </div>
                <div class="mt-3">
                    <p class="text-white-50 mb-1">
                        <i class="bi bi-envelope"></i> info@pariwisatapesawaran.com
                    </p>
                    <p class="text-white-50">
                        <i class="bi bi-telephone"></i> +62 812-3456-7890
                    </p>
                </div>
            </div>
        </div>

        <hr class="my-4 bg-white opacity-25">

        <div class="row">
            <div class="col text-center">
                <p class="mb-0 text-white-50">
                    <small>© {{ date('Y') }} Pariwisata Pesawaran. Metode ARAS Implementation.</small>
                </p>
            </div>
        </div>
    </div>
</footer>
