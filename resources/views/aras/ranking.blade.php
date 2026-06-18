<x-app-layout>
    <style>
        .ranking-card {
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            border-radius: 20px !important;
            border: 1px solid rgba(0,0,0,0.05);
        }
        .ranking-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 1.5rem 3rem rgba(30, 60, 114, 0.12) !important;
        }
        .ranking-badge {
            font-size: 0.85rem;
            padding: 0.6rem 1.2rem;
            border-radius: 0 0 16px 0;
            z-index: 10;
        }
        .img-container {
            height: 220px;
            overflow: hidden;
            position: relative;
        }
        .img-container img {
            transition: transform 0.6s cubic-bezier(0.165, 0.84, 0.44, 1);
        }
        .ranking-card:hover .img-container img {
            transform: scale(1.1);
        }
        .score-box {
            background-color: #f8f9fa;
            border-left: 4px solid #1e3c72;
            transition: all 0.3s ease;
        }
        .ranking-card:hover .score-box {
            background-color: #edf2f9;
            border-left-color: #2a5298;
        }
        .gold-border {
            border: 2px solid #ffd700 !important;
            box-shadow: 0 0.5rem 1.5rem rgba(255, 215, 0, 0.15);
        }
        .silver-border {
            border: 2px solid #c0c0c0 !important;
            box-shadow: 0 0.5rem 1.5rem rgba(192, 192, 192, 0.15);
        }
        .bronze-border {
            border: 2px solid #cd7f32 !important;
            box-shadow: 0 0.5rem 1.5rem rgba(205, 127, 50, 0.15);
        }
    </style>

    <!-- Page Header -->
    <div class="container mt-4">
        <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
            <div class="card-body p-4 d-flex justify-content-between align-items-center flex-wrap gap-3 border-start border-primary border-5">
                <div>
                    <h2 class="fw-bold mb-1 text-primary d-flex align-items-center gap-2">
                        <i class="bi bi-trophy-fill text-warning"></i> Rekomendasi Destinasi Terbaik
                    </h2>
                    <p class="text-secondary mb-0 small">Daftar peringkat destinasi wisata terfavorit di Kabupaten Pesawaran yang dihitung secara matematis menggunakan Metode ARAS (Additive Ratio Assessment).</p>
                </div>
            </div>
        </div>
    </div>

    <div class="container py-5">
        <div class="row g-4 justify-content-center">
            @forelse($hasil as $h)
                @if(!$h->destinasi)
                    @continue
                @endif
                @php
                    $borderClass = '';
                    $badgeClass = 'bg-primary text-white';
                    $badgeText = 'Peringkat ' . $h->ranking;
                    
                    if ($h->ranking == 1) {
                        $borderClass = 'gold-border';
                        $badgeClass = 'bg-warning text-dark';
                        $badgeText = '🏆 Peringkat 1 (Terbaik)';
                    } elseif ($h->ranking == 2) {
                        $borderClass = 'silver-border';
                        $badgeClass = 'bg-secondary text-white';
                        $badgeText = '🥈 Peringkat 2';
                    } elseif ($h->ranking == 3) {
                        $borderClass = 'bronze-border';
                        $badgeClass = 'bg-danger-emphasis text-white';
                        $badgeText = '🥉 Peringkat 3';
                    }
                @endphp
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm ranking-card {{ $borderClass }} position-relative overflow-hidden">
                        <!-- Ribbon Ranking -->
                        <div class="position-absolute top-0 start-0 fw-bold ranking-badge shadow-sm {{ $badgeClass }}">
                            {{ $badgeText }}
                        </div>

                        <!-- Gambar dengan Efek Hover Zoom -->
                        <div class="img-container">
                            @if($h->destinasi->foto)
                                <img src="{{ asset('images/destinasi/'.$h->destinasi->foto) }}" class="w-100 h-100 object-cover" alt="{{ $h->destinasi->nama }}">
                            @else
                                <div class="bg-secondary w-100 h-100 d-flex align-items-center justify-content-center text-white">
                                    <i class="bi bi-image fs-1"></i>
                                </div>
                            @endif
                        </div>

                        <div class="card-body d-flex flex-column justify-content-between p-4">
                            <div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge bg-primary-subtle text-primary rounded-pill px-3">{{ $h->destinasi->kategori }}</span>
                                    <span class="text-success small fw-bold">
                                        <i class="bi bi-tag-fill me-1"></i>Rp {{ number_format($h->destinasi->harga_tiket, 0, ',', '.') }}
                                    </span>
                                </div>
                                <h4 class="fw-bold text-dark mb-3">{{ $h->destinasi->nama }}</h4>
                                
                                <p class="text-muted small mb-4">
                                    <i class="bi bi-geo-alt-fill text-danger me-1"></i>{{ Str::limit($h->destinasi->alamat, 90) }}
                                </p>
                            </div>

                            <div>
                                <!-- Skor Utilitas (K) -->
                                <div class="p-3 rounded-3 mb-3 score-box">
                                    <small class="text-secondary text-uppercase fw-bold d-block mb-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">Skor Utilitas (K)</small>
                                    <h3 class="fw-extrabold text-primary mb-0 font-monospace" style="font-size: 1.6rem;">{{ number_format($h->nilai_k, 4) }}</h3>
                                </div>

                                <a href="{{ route('destinasi.show', $h->destinasi_id) }}" class="btn btn-primary w-100 fw-bold rounded-pill">
                                    <i class="bi bi-arrow-right-circle me-1"></i> Lihat Detail Wisata
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <div class="card shadow-sm border-0 rounded-4 p-5 text-muted">
                        <i class="bi bi-clipboard-x fs-1 text-secondary mb-3"></i>
                        <h5 class="fw-bold text-dark">Belum Ada Rekomendasi Terhitung</h5>
                        <p class="small text-secondary mb-0">Hubungi admin untuk melakukan perhitungan analisis ranking ARAS di dashboard.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
