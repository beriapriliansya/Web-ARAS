<x-app-layout>
    <div class="container py-5">

        <!-- Notifikasi -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <!-- Panel Kiri: Info & Aksi -->
            <div class="col-md-4">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body text-center">
                        <h5 class="fw-bold text-primary mb-3">Eksekusi Perhitungan</h5>
                        <p class="text-muted small">
                            Sistem akan menghitung ulang ranking semua destinasi aktif berdasarkan bobot kriteria saat ini.
                        </p>

                        <div class="d-flex justify-content-between mb-3 px-3">
                            <div class="text-center">
                                <h3 class="fw-bold mb-0">{{ $destinasi }}</h3>
                                <small>Alternatif</small>
                            </div>
                            <div class="text-center">
                                <h3 class="fw-bold mb-0">{{ $kriteria->count() }}</h3>
                                <small>Kriteria</small>
                            </div>
                        </div>

                        <!-- TOMBOL HITUNG (Form ke Route Admin) -->
                        <form action="{{ route('admin.aras.hitung') }}" method="POST" onsubmit="return confirm('Mulai perhitungan ARAS? Data ranking lama akan ditimpa.');">
                            @csrf
                            <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">
                                <i class="bi bi-calculator me-2"></i> Hitung Sekarang
                            </button>
                        </form>
                    </div>
                </div>

                <!-- List Kriteria -->
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0 fw-bold">Daftar Kriteria & Bobot</h6>
                    </div>
                    <ul class="list-group list-group-flush">
                        @foreach($kriteria as $k)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="fw-medium">{{ $k->nama_kriteria }}</span>
                                    <br>
                                    <span class="badge {{ $k->tipe == 'benefit' ? 'bg-success' : 'bg-danger' }}">{{ ucfirst($k->tipe) }}</span>
                                </div>
                                <span class="badge bg-primary rounded-pill">{{ $k->bobot }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Panel Kanan: Hasil Ranking -->
            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold text-success">
                            <i class="bi bi-trophy-fill me-2"></i> Hasil Perankingan Terbaru
                        </h5>
                        <small class="text-muted">Diupdate: {{ $hasil->first() ? $hasil->first()->created_at->format('d M Y H:i') : '-' }}</small>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" width="10%">Rank</th>
                                        <th>Nama Destinasi</th>
                                        <th class="text-center">Nilai S (Optimalitas)</th>
                                        <th class="text-center">Nilai K (Utilitas)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($hasil as $h)
                                        <tr class="{{ $h->ranking <= 3 ? 'table-warning' : '' }}">
                                            <td class="text-center fw-bold">
                                                @if($h->ranking == 1) 🥇
                                                @elseif($h->ranking == 2) 🥈
                                                @elseif($h->ranking == 3) 🥉
                                                @else #{{ $h->ranking }}
                                                @endif
                                            </td>
                                            <td>
                                                <span class="fw-bold">{{ $h->destinasi->nama }}</span>
                                            </td>
                                            <td class="text-center">{{ number_format($h->nilai_s, 4) }}</td>
                                            <td class="text-center fw-bold text-primary">{{ number_format($h->nilai_k, 4) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-5 text-muted">
                                                <i class="bi bi-clipboard-x fs-1"></i>
                                                <p class="mt-2">Belum ada hasil perhitungan. Silakan klik tombol hitung.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
