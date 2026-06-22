<x-app-layout>
    <!-- Hero Banner -->
    <div class="bg-gradient text-white py-5 text-center" style="background: linear-gradient(135deg, #0d6efd 0%, #0dcaf0 100%);">
        <div class="container">
            <h1 class="display-4 fw-bold mb-3">🌴 Rekomendasi Pintar</h1>
            <p class="lead">
                Sesuaikan kriteria preferensi Anda untuk menemukan destinasi wisata air terbaik di Pesawaran menggunakan metode ARAS.
            </p>
        </div>
    </div>

    <div class="container py-5">
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-4">
            <!-- Panel Kiri: Input Bobot Preferensi -->
            <div class="col-lg-5">
                <div class="card shadow border-0 rounded-4 overflow-hidden">
                    <div class="card-header bg-dark text-white py-3">
                        <h5 class="mb-0 fw-bold"><i class="bi bi-sliders me-2 text-warning"></i>Atur Preferensi Anda</h5>
                    </div>
                    <div class="card-body p-4">
                        <p class="text-muted small">
                            Tentukan seberapa penting kriteria di bawah ini bagi Anda. <strong>Total bobot wajib sama dengan 100%</strong>.
                        </p>

                        <form action="{{ route('aras.rekomendasi.hitung') }}" method="POST" id="formRekomendasi">
                            @csrf
                            
                            @foreach($kriteria as $k)
                                @php
                                    // Cari bobot default dalam persen (misal 0.15 jadi 15)
                                    $defaultVal = isset($customWeightsInput[$k->id]) 
                                        ? $customWeightsInput[$k->id] 
                                        : ($k->bobot * 100);
                                @endphp
                                <div class="mb-4">
                                    <div class="d-flex justify-content-between mb-2">
                                        <label class="fw-bold text-dark d-flex align-items-center">
                                             @if($k->kode == 'C1') <i class="bi bi-cash-stack text-warning me-2"></i>
                                             @elseif($k->kode == 'C2') <i class="bi bi-signpost-split text-primary me-2"></i>
                                             @elseif($k->kode == 'C3') <i class="bi bi-tools text-info me-2"></i>
                                             @elseif($k->kode == 'C4') <i class="bi bi-trash3 text-success me-2"></i>
                                             @elseif($k->kode == 'C5') <i class="bi bi-shield-lock text-danger me-2"></i>
                                             @elseif($k->kode == 'C6') <i class="bi bi-star text-warning me-2"></i>
                                             @endif
                                            {{ $k->nama_kriteria }} 
                                            <span class="badge {{ $k->tipe == 'benefit' ? 'bg-success' : 'bg-danger' }} ms-2" style="font-size: 0.65rem;">
                                                {{ ucfirst($k->tipe) }}
                                            </span>
                                        </label>
                                        <span class="fw-bold text-primary" id="val_label_{{ $k->id }}">{{ (int)$defaultVal }}%</span>
                                    </div>
                                    <input type="range" class="form-range weight-slider" 
                                           name="weight_{{ $k->id }}" 
                                           id="weight_{{ $k->id }}"
                                           min="0" max="100" step="5" 
                                           value="{{ (int)$defaultVal }}"
                                           data-id="{{ $k->id }}">
                                    <div class="form-text small opacity-75">{{ $k->keterangan }}</div>
                                </div>
                            @endforeach

                            <!-- Indikator Total Bobot -->
                            <div class="p-3 bg-light rounded-3 mb-4 border">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-bold text-secondary">Total Bobot Terinput:</span>
                                    <span class="fs-4 fw-bold" id="totalWeightText">0%</span>
                                </div>
                                <div class="progress" style="height: 12px;">
                                    <div class="progress-bar" id="totalWeightProgressBar" role="progressbar" style="width: 0%"></div>
                                </div>
                                <div class="text-center mt-2 small" id="totalWeightStatus">
                                    Total bobot harus pas 100%
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-3 fw-bold rounded-3 shadow-sm" id="btnSubmit">
                                <i class="bi bi-cpu-fill me-2"></i> Hitung Rekomendasi ARAS
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Panel Kanan: Hasil Kalkulasi -->
            <div class="col-lg-7">
                <div class="card shadow border-0 rounded-4">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                        <h5 class="mb-0 fw-bold text-success">
                            <i class="bi bi-award-fill me-2"></i>
                            @if(isset($hasilRekomendasi))
                                Hasil Rekomendasi Kustom Anda
                            @else
                                Hasil Rekomendasi Sistem (Default)
                            @endif
                        </h5>
                        @if(isset($hasilRekomendasi))
                            <a href="{{ route('aras.rekomendasi.form') }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-arrow-counterclockwise"></i> Reset Bobot
                            </a>
                        @endif
                    </div>
                    <div class="card-body p-0">
                        @php
                            $listHasil = isset($hasilRekomendasi) ? $hasilRekomendasi : $hasilDefault;
                        @endphp

                        @if($listHasil && $listHasil->count() > 0 || (is_array($listHasil) && count($listHasil) > 0))
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="text-center" width="12%">Rank</th>
                                            <th>Destinasi Wisata</th>
                                            <th class="text-center" width="20%">Skor Utilitas (K)</th>
                                            <th class="text-end" width="20%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($listHasil as $index => $item)
                                            @php
                                                $dest = isset($item->destinasi) ? $item->destinasi : null;
                                            @endphp
                                            @if(!$dest)
                                                @continue
                                            @endif
                                            @php
                                                $ranking = isset($item->ranking) ? $item->ranking : ($index + 1);
                                                $nilaiK = isset($item->nilai_k) ? $item->nilai_k : $item->nilai_k;
                                            @endphp
                                            <tr class="{{ $ranking <= 3 ? 'table-warning opacity-100' : '' }}">
                                                <td class="text-center fw-bold fs-5">
                                                    @if($ranking == 1) 🥇
                                                    @elseif($ranking == 2) 🥈
                                                    @elseif($ranking == 3) 🥉
                                                    @else #{{ $ranking }}
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="me-3" style="width: 50px; height: 50px; overflow: hidden; border-radius: 8px;">
                                                            @if($dest->foto)
                                                                <img src="{{ asset('images/destinasi/' . $dest->foto) }}" class="w-100 h-100 object-cover">
                                                            @else
                                                                <div class="bg-secondary w-100 h-100 d-flex align-items-center justify-content-center text-white">
                                                                    <i class="bi bi-image" style="font-size: 1.2rem;"></i>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div>
                                                            <h6 class="fw-bold mb-0 text-dark">A<sub>{{ \App\Models\DestinasiWisata::aktif()->pluck('id')->search($dest->id) + 1 }}</sub> - {{ $dest->nama }}</h6>
                                                            <small class="text-muted"><i class="bi bi-geo-alt"></i> {{ Str::limit($dest->alamat, 45) }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center fw-bold text-primary fs-5">
                                                    {{ number_format($nilaiK, 4) }}
                                                </td>
                                                <td class="text-end pe-3">
                                                    <a href="{{ route('destinasi.show', $dest->id) }}" class="btn btn-sm btn-primary rounded-pill px-3">
                                                        Detail
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5 text-muted">
                                <i class="bi bi-clipboard-x fs-1"></i>
                                <p class="mt-2 mb-0">Belum ada data destinasi aktif untuk kalkulasi.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript Real-time Validator -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sliders = document.querySelectorAll('.weight-slider');
            const totalText = document.getElementById('totalWeightText');
            const progressBar = document.getElementById('totalWeightProgressBar');
            const statusText = document.getElementById('totalWeightStatus');
            const btnSubmit = document.getElementById('btnSubmit');

            function calculateTotal() {
                let total = 0;
                sliders.forEach(slider => {
                    const id = slider.getAttribute('data-id');
                    const label = document.getElementById('val_label_' + id);
                    label.innerText = slider.value + '%';
                    total += parseInt(slider.value);
                });

                totalText.innerText = total + '%';
                progressBar.style.width = Math.min(100, total) + '%';

                if (total === 100) {
                    progressBar.className = 'progress-bar bg-success';
                    statusText.innerHTML = '<span class="text-success fw-bold"><i class="bi bi-check-circle-fill"></i> Total bobot pas 100%! Siap hitung.</span>';
                    btnSubmit.removeAttribute('disabled');
                } else {
                    progressBar.className = total > 100 ? 'progress-bar bg-danger' : 'progress-bar bg-warning';
                    statusText.innerHTML = `<span class="text-danger fw-bold"><i class="bi bi-exclamation-triangle-fill"></i> Total bobot harus 100% (saat ini ${total}%).</span>`;
                    btnSubmit.setAttribute('disabled', 'disabled');
                }
            }

            sliders.forEach(slider => {
                slider.addEventListener('input', calculateTotal);
            });

            calculateTotal(); // Run once on load
        });
    </script>
</x-app-layout>
