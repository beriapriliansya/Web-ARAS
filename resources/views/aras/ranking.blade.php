<x-app-layout>
    <!-- Hero Banner -->
    <div class="bg-primary text-white py-5 text-center">
        <div class="container">
            <h1 class="display-4 fw-bold mb-3">🏆 Top Destinasi Pilihan</h1>
            <p class="lead">
                Berikut adalah urutan destinasi wisata terbaik di Pesawaran berdasarkan analisis sistem pendukung keputusan metode ARAS.
            </p>
        </div>
    </div>

    <div class="container py-5">
        <div class="row g-4">
            @foreach($hasil as $h)
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm border-0 position-relative overflow-hidden">
                        <!-- Ribbon Ranking -->
                        <div class="position-absolute top-0 start-0 bg-warning text-dark fw-bold px-3 py-1 rounded-bottom-end shadow-sm" style="z-index: 10;">
                            Ranking #{{ $h->ranking }}
                        </div>

                        <!-- Gambar -->
                        <div style="height: 200px; overflow: hidden;">
                            @if($h->destinasi->foto)
                                <img src="{{ asset('images/destinasi/'.$h->destinasi->foto) }}" class="w-100 h-100 object-cover">
                            @else
                                <div class="bg-secondary w-100 h-100 d-flex align-items-center justify-content-center text-white">
                                    <i class="bi bi-image fs-1"></i>
                                </div>
                            @endif
                        </div>

                        <div class="card-body text-center">
                            <h4 class="fw-bold mb-1">{{ $h->destinasi->nama }}</h4>
                            <span class="badge bg-info text-dark mb-3">{{ $h->destinasi->kategori }}</span>

                            <div class="py-2 bg-light rounded mb-3">
                                <small class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem;">Nilai Utilitas (K)</small>
                                <h3 class="fw-bold text-primary mb-0">{{ number_format($h->nilai_k, 4) }}</h3>
                            </div>

                            <a href="{{ route('destinasi.show', $h->destinasi_id) }}" class="btn btn-outline-primary w-100">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
