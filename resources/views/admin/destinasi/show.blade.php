<x-app-layout>

    <!-- Breadcrumb -->
    <div class="container mt-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.destinasi.index') }}">Destinasi</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $destinasi->nama }}</li>
            </ol>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="container my-4">
        <div class="row">
            <!-- Left Column: Info -->
            <div class="col-lg-8">
                <!-- Header -->
                <div class="card mb-4 shadow-sm border-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <span class="badge bg-primary mb-2">{{ $destinasi->kategori }}</span>
                                <h1 class="display-5 fw-bold mb-0 text-dark">{{ $destinasi->nama }}</h1>
                                <p class="text-muted mt-1"><i class="bi bi-geo-alt"></i> {{ $destinasi->alamat }}</p>
                            </div>
                            <div class="text-end">
                                <a href="{{ route('admin.destinasi.edit', $destinasi->id) }}" class="btn btn-warning fw-bold">
                                    <i class="bi bi-pencil-square"></i> Edit Data
                                </a>
                            </div>
                        </div>

                        <hr>

                        <!-- Quick Info Grid -->
                        <div class="row g-4 mt-2">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <div class="bg-light p-3 rounded-circle me-3">
                                        <i class="bi bi-cash fs-3 text-success"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Harga Tiket</small>
                                        <span class="fw-bold fs-5">Rp {{ number_format($destinasi->harga_tiket, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <div class="bg-light p-3 rounded-circle me-3">
                                        <i class="bi bi-clock fs-3 text-primary"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Jam Operasional</small>
                                        <span class="fw-bold">
                                            {{ $destinasi->jam_buka ? \Carbon\Carbon::parse($destinasi->jam_buka)->format('H:i') : '-' }} -
                                            {{ $destinasi->jam_tutup ? \Carbon\Carbon::parse($destinasi->jam_tutup)->format('H:i') : '-' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <div class="bg-light p-3 rounded-circle me-3">
                                        <i class="bi bi-telephone fs-3 text-info"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Kontak</small>
                                        <span class="fw-bold">{{ $destinasi->telepon ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <div class="bg-light p-3 rounded-circle me-3">
                                        <i class="bi bi-globe fs-3 text-secondary"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Website</small>
                                        @if($destinasi->website)
                                            <a href="{{ $destinasi->website }}" target="_blank" class="fw-bold text-decoration-none">Kunjungi Link</a>
                                        @else
                                            <span class="fw-bold">-</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Deskripsi -->
                <div class="card mb-4 shadow-sm border-0">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold"><i class="bi bi-info-circle me-2"></i> Deskripsi</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-secondary" style="text-align: justify; line-height: 1.8;">
                            {!! nl2br(e($destinasi->deskripsi)) !!}
                        </p>
                    </div>
                </div>

                <!-- Fasilitas -->
                @if($destinasi->fasilitas && count($destinasi->fasilitas) > 0)
                <div class="card mb-4 shadow-sm border-0">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold"><i class="bi bi-grid me-2"></i> Fasilitas</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            @foreach($destinasi->fasilitas as $fasilitas)
                                <div class="col-md-4 col-6">
                                    <div class="d-flex align-items-center p-2 border rounded bg-light">
                                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                                        <span class="fw-medium">{{ $fasilitas }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- Nilai Kriteria ARAS -->
                <div class="card mb-4 shadow-sm border-0">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold"><i class="bi bi-calculator-fill me-2 text-success"></i> Nilai Kriteria (Alternatif ARAS)</h5>
                        <a href="{{ route('admin.destinasi.nilai.edit', $destinasi->id) }}" class="btn btn-sm btn-outline-success fw-bold">
                            <i class="bi bi-pencil-square"></i> Kelola Nilai
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th width="15%" class="text-center">Kode</th>
                                        <th>Nama Kriteria</th>
                                        <th width="20%" class="text-center">Tipe</th>
                                        <th width="25%" class="text-end">Nilai Input</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($destinasi->alternatif as $alt)
                                        <tr>
                                            <td class="fw-bold font-monospace text-primary text-center">{{ $alt->kriteria->kode }}</td>
                                            <td class="fw-medium text-dark">{{ $alt->kriteria->nama_kriteria }}</td>
                                            <td class="text-center">
                                                <span class="badge {{ $alt->kriteria->tipe == 'benefit' ? 'bg-success' : 'bg-danger' }}">
                                                    {{ ucfirst($alt->kriteria->tipe) }}
                                                </span>
                                            </td>
                                            <td class="text-end fw-bold text-dark font-monospace">
                                                @if($alt->kriteria->satuan == 'Rp')
                                                    Rp {{ number_format($alt->nilai, 0, ',', '.') }}
                                                @else
                                                    {{ number_format($alt->nilai, 1) }} {{ $alt->kriteria->satuan }}
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-4 text-muted">
                                                <i class="bi bi-exclamation-circle fs-3 d-block mb-2 text-warning"></i>
                                                Belum ada nilai kriteria yang diinput. Klik tombol <strong>Kelola Nilai</strong> untuk mengisi.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Media & Map -->
            <div class="col-lg-4">
                <!-- Foto Utama -->
                <div class="card mb-4 shadow-sm border-0">
                    <div class="card-body p-2">
                        @if($destinasi->foto)
                            <img src="{{ asset('images/destinasi/' . $destinasi->foto) }}" class="img-fluid rounded w-100" alt="{{ $destinasi->nama }}">
                        @else
                            <div class="bg-secondary text-white d-flex align-items-center justify-content-center rounded" style="height: 250px;">
                                <i class="bi bi-image fs-1"></i>
                            </div>
                        @endif
                        <div class="mt-3 text-center">
                            <span class="badge {{ $destinasi->status == 'aktif' ? 'bg-success' : 'bg-danger' }} fs-6 px-4 py-2">
                                Status: {{ ucfirst($destinasi->status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Google Maps Embed (Simple Link) -->
                <div class="card mb-4 shadow-sm border-0">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold"><i class="bi bi-map me-2"></i> Lokasi Maps</h5>
                    </div>
                    <div class="card-body text-center">
                        <p class="text-muted small mb-2">Latitude: {{ $destinasi->latitude }}</p>
                        <p class="text-muted small mb-3">Longitude: {{ $destinasi->longitude }}</p>

                        <a href="https://www.google.com/maps/search/?api=1&query={{ $destinasi->latitude }},{{ $destinasi->longitude }}" target="_blank" class="btn btn-outline-primary w-100">
                            <i class="bi bi-box-arrow-up-right me-2"></i> Buka di Google Maps
                        </a>
                    </div>
                </div>

                <!-- Tombol Hapus -->
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <form action="{{ route('admin.destinasi.destroy', $destinasi->id) }}" method="POST" onsubmit="return confirm('Hapus permanen destinasi ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100 fw-bold">
                                <i class="bi bi-trash me-2"></i> Hapus Destinasi
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
