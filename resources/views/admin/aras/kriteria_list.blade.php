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

        <!-- Session Messages -->
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

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <ul class="mb-0 d-inline-block ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card shadow border-0 rounded-4 overflow-hidden">
            <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold"><i class="bi bi-list-task me-2"></i>Daftar Kriteria Penilaian</h5>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-light fw-bold text-primary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modalTambahKriteria">
                        <i class="bi bi-plus-circle me-1"></i> Tambah Kriteria
                    </button>
                    <a href="{{ route('admin.aras.kriteria.edit') }}" class="btn btn-sm btn-light fw-bold text-primary rounded-pill px-3">
                        <i class="bi bi-sliders me-1"></i> Atur Bobot & Tipe
                    </a>
                </div>
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
                                <th class="text-center" width="10%">Status</th>
                                <th class="text-center" width="10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($kriteria as $k)
                                <tr>
                                    <td class="text-center fw-bold text-primary">{{ $k->kode }}</td>
                                    <td class="fw-semibold text-dark">
                                        {{ $k->nama_kriteria }}
                                        <br>
                                        <small class="text-muted">Satuan: {{ $k->satuan ?? '-' }}</small>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge {{ $k->tipe == 'benefit' ? 'bg-success' : 'bg-danger' }}">
                                            {{ ucfirst($k->tipe) }}
                                        </span>
                                    </td>
                                    <td class="text-center fw-bold text-dark">
                                        {{ number_format($k->bobot * 100, 1) }}% ({{ $k->bobot }})
                                    </td>
                                    <td><span class="small text-secondary">{{ $k->keterangan }}</span></td>
                                    <td class="text-center">
                                        <span class="badge {{ $k->status == 'aktif' ? 'bg-success' : 'bg-secondary' }}">
                                            {{ ucfirst($k->status) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex gap-1 justify-content-center">
                                            <!-- Edit Button -->
                                            <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#modalEditKriteria{{ $k->id }}" title="Edit Kriteria">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <!-- Delete Button -->
                                            <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalDeleteKriteria{{ $k->id }}" title="Hapus Kriteria">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Modal Edit Kriteria -->
                                <div class="modal fade" id="modalEditKriteria{{ $k->id }}" tabindex="-1" aria-labelledby="modalEditKriteriaLabel{{ $k->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-warning text-dark">
                                                <h5 class="modal-title fw-bold" id="modalEditKriteriaLabel{{ $k->id }}"><i class="bi bi-pencil-square me-2"></i>Edit Kriteria {{ $k->kode }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="alert" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('admin.aras.kriteria.update', $k->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold">Kode Kriteria *</label>
                                                        <input type="text" name="kode" class="form-control" value="{{ old('kode', $k->kode) }}" required placeholder="Contoh: C7">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold">Nama Kriteria *</label>
                                                        <input type="text" name="nama_kriteria" class="form-control" value="{{ old('nama_kriteria', $k->nama_kriteria) }}" required placeholder="Contoh: Aksesibilitas">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold">Tipe Kriteria *</label>
                                                        <select name="tipe" class="form-select" required>
                                                            <option value="benefit" {{ old('tipe', $k->tipe) == 'benefit' ? 'selected' : '' }}>Benefit (Semakin tinggi semakin baik)</option>
                                                            <option value="cost" {{ old('tipe', $k->tipe) == 'cost' ? 'selected' : '' }}>Cost (Semakin rendah semakin baik)</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold">Bobot Preferensi *</label>
                                                        <input type="number" step="0.0001" min="0" max="100" name="bobot" class="form-control" value="{{ old('bobot', $k->bobot) }}" required placeholder="Contoh: 0.15 atau 15">
                                                        <small class="text-muted">Masukkan desimal (0.15) atau persentase (15).</small>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold">Satuan *</label>
                                                        <input type="text" name="satuan" class="form-control" value="{{ old('satuan', $k->satuan) }}" required placeholder="Contoh: Skor, Rp, km">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold">Keterangan</label>
                                                        <input type="text" name="keterangan" class="form-control" value="{{ old('keterangan', $k->keterangan) }}" placeholder="Contoh: Keterangan singkat mengenai kriteria">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold">Status *</label>
                                                        <select name="status" class="form-select" required>
                                                            <option value="aktif" {{ old('status', $k->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                                            <option value="nonaktif" {{ old('status', $k->status) == 'nonaktif' ? 'selected' : '' }}>Non-Aktif</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-warning fw-bold text-dark"><i class="bi bi-save"></i> Simpan Perubahan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal Delete Kriteria -->
                                <div class="modal fade" id="modalDeleteKriteria{{ $k->id }}" tabindex="-1" aria-labelledby="modalDeleteKriteriaLabel{{ $k->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title fw-bold" id="modalDeleteKriteriaLabel{{ $k->id }}"><i class="bi bi-exclamation-triangle me-2"></i>Hapus Kriteria {{ $k->kode }}</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('admin.aras.kriteria.destroy', $k->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <div class="modal-body text-start">
                                                    <p>Apakah Anda yakin ingin menghapus kriteria <strong>{{ $k->kode }} - {{ $k->nama_kriteria }}</strong>?</p>
                                                    <div class="alert alert-warning mb-0">
                                                        <i class="bi bi-exclamation-circle-fill me-2"></i><strong>Penting:</strong> Menghapus kriteria ini juga akan menghapus seluruh data penilaian / alternatif terkait untuk kriteria ini secara permanen dari semua destinasi!
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-danger fw-bold"><i class="bi bi-trash"></i> Hapus Permanen</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Kriteria -->
    <div class="modal fade" id="modalTambahKriteria" tabindex="-1" aria-labelledby="modalTambahKriteriaLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold" id="modalTambahKriteriaLabel"><i class="bi bi-plus-circle me-2"></i>Tambah Kriteria Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.aras.kriteria.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Kode Kriteria *</label>
                            <input type="text" name="kode" class="form-control @error('kode') is-invalid @enderror" value="{{ old('kode') }}" required placeholder="Contoh: C7">
                            @error('kode')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Kriteria *</label>
                            <input type="text" name="nama_kriteria" class="form-control" value="{{ old('nama_kriteria') }}" required placeholder="Contoh: Aksesibilitas">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tipe Kriteria *</label>
                            <select name="tipe" class="form-select" required>
                                <option value="benefit" {{ old('tipe') == 'benefit' ? 'selected' : '' }}>Benefit (Semakin tinggi semakin baik)</option>
                                <option value="cost" {{ old('tipe') == 'cost' ? 'selected' : '' }}>Cost (Semakin rendah semakin baik)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Bobot Preferensi *</label>
                            <input type="number" step="0.0001" min="0" max="100" name="bobot" class="form-control" value="{{ old('bobot') }}" required placeholder="Contoh: 0.15 atau 15">
                            <small class="text-muted">Masukkan desimal (0.15) atau persentase (15).</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Satuan *</label>
                            <input type="text" name="satuan" class="form-control" value="{{ old('satuan') }}" required placeholder="Contoh: Skor, Rp, km">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Keterangan</label>
                            <input type="text" name="keterangan" class="form-control" value="{{ old('keterangan') }}" placeholder="Contoh: Keterangan singkat mengenai kriteria">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Status *</label>
                            <select name="status" class="form-select" required>
                                <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>Non-Aktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary fw-bold"><i class="bi bi-check-circle"></i> Tambah Kriteria</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
