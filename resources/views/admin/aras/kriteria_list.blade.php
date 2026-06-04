<x-app-layout>
    <div class="container py-5">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.aras.index') }}">Perhitungan ARAS</a></li>
                <li class="breadcrumb-item active" aria-current="page">Daftar Kriteria</li>
            </ol>
        </nav>

        <div class="card shadow border-0 rounded-4 overflow-hidden">
            <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold"><i class="bi bi-list-task me-2"></i>Daftar Kriteria Penilaian</h5>
                <a href="{{ route('admin.aras.kriteria.edit') }}" class="btn btn-sm btn-light fw-bold text-primary">
                    <i class="bi bi-sliders me-1"></i> Atur Bobot & Tipe
                </a>
            </div>
            <div class="card-body p-4">
                <p class="text-secondary small mb-4">
                    Berikut adalah daftar kriteria yang digunakan dalam Metode ARAS untuk menganalisis dan menentukan rekomendasi tempat wisata terbaik di Kabupaten Pesawaran.
                </p>

                <div class="table-responsive">
                    <table class="table table-hover align-middle table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" width="10%">Kode</th>
                                <th width="25%">Nama Kriteria</th>
                                <th class="text-center" width="15%">Tipe</th>
                                <th class="text-center" width="15%">Bobot Saat Ini</th>
                                <th>Keterangan</th>
                                <th class="text-center" width="12%">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($kriteria as $k)
                                <tr>
                                    <td class="text-center fw-bold text-primary">{{ $k->kode }}</td>
                                    <td class="fw-semibold text-dark">{{ $k->nama_kriteria }}</td>
                                    <td class="text-center">
                                        <span class="badge {{ $k->tipe == 'benefit' ? 'bg-success' : 'bg-danger' }}">
                                            {{ ucfirst($k->tipe) }}
                                        </span>
                                    </td>
                                    <td class="text-center fw-bold text-dark">
                                        {{ number_format($k->bobot * 100, 0) }}% ({{ $k->bobot }})
                                    </td>
                                    <td><span class="small text-secondary">{{ $k->keterangan }}</span></td>
                                    <td class="text-center">
                                        <span class="badge bg-success">Aktif</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
