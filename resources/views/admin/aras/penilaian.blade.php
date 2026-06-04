<x-app-layout>
    <div class="container py-5">
        
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.aras.index') }}">Perhitungan ARAS</a></li>
                <li class="breadcrumb-item active" aria-current="page">Penilaian Objek Wisata</li>
            </ol>
        </nav>

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

        <!-- LANGKAH 1: Pilih Objek Wisata -->
        <div class="card shadow border-0 rounded-4 overflow-hidden mb-4">
            <div class="card-header bg-primary text-white py-3">
                <h6 class="mb-0 fw-bold"><i class="bi bi-check2-square me-2"></i>Langkah 1: Pilih Objek Wisata</h6>
            </div>
            <div class="card-body p-4">
                <label class="form-label fw-bold text-secondary mb-2">Pilih wisata yang akan dinilai:</label>
                <form id="destinasiSelectForm" action="{{ route('admin.aras.penilaian') }}" method="GET" class="m-0">
                    <select name="destinasi_id" class="form-select form-select-lg" onchange="document.getElementById('destinasiSelectForm').submit()">
                        <option value="" selected disabled>-- Pilih Objek Wisata --</option>
                        @foreach($destinasi as $d)
                            <option value="{{ $d->id }}" {{ $selectedDestinasiId == $d->id ? 'selected' : '' }}>
                                {{ $d->nama }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>

        <!-- LANGKAH 2: Beri Penilaian -->
        @if($selectedDestinasi)
            <div class="card shadow border-0 rounded-4 overflow-hidden">
                <div class="card-header bg-primary text-white py-3">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-pencil-square me-2"></i>Langkah 2: Beri Penilaian ({{ $selectedDestinasi->nama }})</h6>
                </div>
                <form action="{{ route('admin.aras.penilaian.store') }}" method="POST" class="m-0">
                    @csrf
                    <input type="hidden" name="destinasi_id" value="{{ $selectedDestinasi->id }}">

                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" width="8%">No</th>
                                        <th class="text-start" width="42%">Kriteria</th>
                                        <th class="text-start" width="50%">Nilai (Sub-Kriteria)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($kriteria as $index => $k)
                                        <tr>
                                            <td class="text-center text-secondary">{{ $index + 1 }}</td>
                                            <td class="text-start">
                                                <span class="fw-bold text-dark">{{ $k->nama_kriteria }}</span>
                                                <span class="badge {{ $k->tipe == 'benefit' ? 'bg-success' : 'bg-danger' }} ms-2" style="font-size: 0.65rem;">
                                                    {{ ucfirst($k->tipe) }}
                                                </span>
                                                <div class="small text-muted">{{ $k->keterangan }}</div>
                                            </td>
                                            <td class="text-start">
                                                @php
                                                    $existingNilai = isset($alternatifValues[$k->id]) ? (float)$alternatifValues[$k->id] : null;
                                                    $options = isset($subKriteria[$k->id]) ? $subKriteria[$k->id] : collect();
                                                @endphp
                                                
                                                @if($options->isNotEmpty())
                                                    <select name="nilai_{{ $k->id }}" class="form-select" required>
                                                        <option value="" disabled {{ $existingNilai === null ? 'selected' : '' }}>-- Pilih Nilai Sub-Kriteria --</option>
                                                        @foreach($options as $opt)
                                                            @php
                                                                // Pre-select if existing value matches option value
                                                                $isSelected = ($existingNilai !== null && abs($existingNilai - (float)$opt->nilai) < 0.001);
                                                            @endphp
                                                            <option value="{{ $opt->id }}" {{ $isSelected ? 'selected' : '' }}>
                                                                {{ $opt->keterangan }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                @else
                                                    <!-- Fallback input number if sub-criteria is not defined for this kriteria -->
                                                    <input type="number" step="0.01" name="nilai_{{ $k->id }}_raw" class="form-control mb-2" 
                                                           value="{{ $existingNilai }}" required placeholder="Masukkan nilai langsung (desimal/angka)">
                                                    <span class="text-danger small"><i class="bi bi-exclamation-triangle"></i> Sub Kriteria belum diset! Silakan tambahkan opsi di halaman Sub Kriteria.</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div class="card-footer bg-white py-3 d-flex justify-content-end border-top">
                        <button type="submit" class="btn btn-primary px-4 fw-bold">
                            <i class="bi bi-save me-1"></i> Simpan Penilaian
                        </button>
                    </div>
                </form>
            </div>
        @else
            <!-- Info Alert: Please select a destination -->
            <div class="card border-0 shadow rounded-4 overflow-hidden">
                <div class="card-body p-5 text-center text-muted">
                    <i class="bi bi-card-list fs-1 text-primary mb-3"></i>
                    <h5 class="fw-semibold text-dark">Silakan pilih objek wisata pada Langkah 1 untuk memulai proses penilaian.</h5>
                    <p class="small text-secondary mb-0">Nilai kriteria yang Anda berikan akan digunakan sebagai basis data alternatif pada kalkulasi Metode ARAS.</p>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
