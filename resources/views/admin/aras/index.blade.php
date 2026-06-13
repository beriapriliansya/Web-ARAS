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

                        <!-- TOMBOL HITUNG (Memicu Modal Konfirmasi) -->
                        <button type="button" class="btn btn-primary w-100 py-2 fw-bold" data-bs-toggle="modal" data-bs-target="#modalKonfirmasiHitung">
                            <i class="bi bi-calculator me-2"></i> Hitung Sekarang
                        </button>
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
                            <table class="table table-hover align-middle mb-0" id="rankingTable">
                                <thead class="table-light align-middle text-center">
                                    <tr>
                                        <th rowspan="2" class="text-center" width="8%">Rank</th>
                                        <th rowspan="2" class="text-start" width="22%">Nama Destinasi</th>
                                        <th colspan="{{ $kriteria->count() }}" class="text-center">Nilai Kriteria (Bobot)</th>
                                        <th rowspan="2" class="text-center" width="12%">Nilai S (Optimalitas)</th>
                                        <th rowspan="2" class="text-center" width="12%">Nilai K (Utilitas)</th>
                                    </tr>
                                    <tr>
                                        @foreach($kriteria as $k)
                                            <th class="small fw-semibold text-center">{{ $k->nama_kriteria }} ({{ $k->bobot }})</th>
                                        @endforeach
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
                                                <span class="fw-bold text-dark">{{ $h->destinasi->nama }}</span>
                                            </td>
                                            
                                            <!-- Kriteria Columns -->
                                            @foreach($kriteria as $k)
                                                @php
                                                    $val = $h->destinasi->alternatif->firstWhere('kriteria_id', $k->id);
                                                @endphp
                                                <td class="text-center text-secondary">
                                                    {{ $val ? (float)$val->nilai : 0 }}
                                                </td>
                                            @endforeach

                                            <td class="text-center">{{ number_format($h->nilai_s, 4) }}</td>
                                            <td class="text-center fw-bold text-primary">{{ number_format($h->nilai_k, 4) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="{{ 4 + $kriteria->count() }}" class="text-center py-5 text-muted">
                                                <i class="bi bi-clipboard-x fs-1"></i>
                                                <p class="mt-2">Belum ada hasil perhitungan. Silakan klik tombol hitung.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-white py-3 d-flex justify-content-end align-items-center flex-wrap gap-2 border-top">
                        <button type="button" class="btn btn-sm btn-success fw-bold text-white px-3" data-bs-toggle="modal" data-bs-target="#modalKonfirmasiHitung">
                            <i class="bi bi-save me-1"></i> Simpan/Update Hasil
                        </button>
                        <button type="button" onclick="exportRankingToCSV()" class="btn btn-sm btn-outline-secondary fw-bold px-3">
                            <i class="bi bi-file-earmark-spreadsheet me-1"></i> Unduh CSV
                        </button>
                        <a href="{{ route('admin.aras.cetak') }}" target="_blank" class="btn btn-sm btn-danger fw-bold text-white px-3">
                            <i class="bi bi-file-pdf me-1"></i> Unduh PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Hitung -->
    <div class="modal fade" id="modalKonfirmasiHitung" tabindex="-1" aria-labelledby="modalKonfirmasiHitungLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header bg-primary text-white py-3">
                    <h5 class="modal-title fw-bold" id="modalKonfirmasiHitungLabel">
                        <i class="bi bi-calculator me-2"></i> Konfirmasi Perhitungan
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 text-center">
                    <div class="text-primary fs-1 mb-3">
                        <i class="bi bi-calculator-fill"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Mulai Perhitungan ARAS?</h5>
                    <p class="text-secondary small mb-0">
                        Sistem akan menghitung ulang ranking seluruh destinasi aktif berdasarkan bobot kriteria saat ini. Data ranking sebelumnya akan ditimpa.
                    </p>
                </div>
                <div class="modal-footer border-0 p-3 bg-light d-flex justify-content-end gap-2 rounded-bottom-4">
                    <button type="button" class="btn btn-secondary px-4 fw-semibold" data-bs-dismiss="modal">Batal</button>
                    <form action="{{ route('admin.aras.hitung') }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="btn btn-primary px-4 fw-bold">
                            Ya, Hitung Sekarang
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Client-Side CSV Export Script -->
    <script>
        function exportRankingToCSV() {
            let csv = [];
            let table = document.querySelector('#rankingTable');
            if (!table) return;

            let rows = table.querySelectorAll('tr');
            for (let i = 0; i < rows.length; i++) {
                let row = [], cols = rows[i].querySelectorAll('td, th');
                
                for (let j = 0; j < cols.length; j++) {
                    // Clean text (remove emojis, line breaks and multiple spaces)
                    let data = cols[j].innerText.replace(/🥇|🥈|🥉/g, '').replace(/(\r\n|\n|\r)/gm, '').replace(/(\s\s+)/gm, ' ').trim();
                    // Escape double quotes
                    data = data.replace(/"/g, '""');
                    row.push('"' + data + '"');
                }
                csv.push(row.join(','));
            }

            let csvString = csv.join('\n');
            let filename = 'laporan_ranking_aras_' + new Date().toISOString().slice(0,10) + '.csv';
            let link = document.createElement('a');
            link.style.display = 'none';
            link.setAttribute('href', 'data:text/csv;charset=utf-8,\uFEFF' + encodeURIComponent(csvString));
            link.setAttribute('download', filename);
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    </script>
</x-app-layout>
