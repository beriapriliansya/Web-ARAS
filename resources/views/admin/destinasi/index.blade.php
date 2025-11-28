<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Destinasi Wisata') }}
        </h2>
    </x-slot>

    <div class="container py-5">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Pesan Error Validasi -->
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-4">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-success">
                    <i class="bi bi-map-fill me-2"></i> Daftar Destinasi Pariwisata
                </h5>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalTambahDestinasi">
                    <i class="bi bi-plus-lg"></i> Tambah Baru
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Destinasi</th>
                                <th>Kategori</th>
                                <th>Harga & Jam</th>
                                <th>Status</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($destinasi as $dest)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($dest->foto)
                                                <img src="{{ asset('images/destinasi/'.$dest->foto) }}" class="rounded me-2" style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                                <div class="bg-secondary rounded me-2 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                    <i class="bi bi-image text-white"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <span class="fw-bold d-block">{{ $dest->nama }}</span>
                                                <small class="text-muted"><i class="bi bi-geo-alt"></i> {{ Str::limit($dest->alamat, 20) }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-info text-dark">{{ $dest->kategori }}</span></td>
                                    <td>
                                        <small class="d-block fw-bold">Rp {{ number_format($dest->harga_tiket, 0, ',', '.') }}</small>
                                        <small class="text-muted">{{ $dest->jam_buka ?? '08:00' }} - {{ $dest->jam_tutup ?? '17:00' }}</small>
                                    </td>
                                    <td>
                                        @if($dest->status == 'aktif')
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary">Non-Aktif</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <!-- TOMBOL EDIT (Memicu Modal Edit Spesifik ID) -->
                                        <button class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#modalEditDestinasi{{ $dest->id }}">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>

                                        <form action="{{ route('admin.destinasi.destroy', $dest->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus destinasi ini?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- ============================== -->
                                <!-- MODAL EDIT (Di dalam Loop) -->
                                <!-- ============================== -->
                                <div class="modal fade" id="modalEditDestinasi{{ $dest->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i> Edit: {{ $dest->nama }}</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('admin.destinasi.update', $dest->id) }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body text-start">
                                                    <!-- Info Dasar -->
                                                    <h6 class="fw-bold text-primary mb-3 border-bottom pb-2">Informasi Dasar</h6>
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Nama Destinasi *</label>
                                                            <input type="text" name="nama" class="form-control" value="{{ $dest->nama }}" required>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Kategori *</label>
                                                            <select name="kategori" class="form-select" required>
                                                                @foreach(['Pantai', 'Pulau', 'Air Terjun', 'Bukit', 'Taman', 'Lainnya'] as $cat)
                                                                    <option value="{{ $cat }}" {{ $dest->kategori == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-4 mb-3">
                                                            <label class="form-label">Harga Tiket (Rp) *</label>
                                                            <input type="number" name="harga_tiket" class="form-control" value="{{ $dest->harga_tiket }}" required>
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                            <label class="form-label">Jam Buka</label>
                                                            <input type="time" name="jam_buka" class="form-control" value="{{ $dest->jam_buka }}">
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                            <label class="form-label">Jam Tutup</label>
                                                            <input type="time" name="jam_tutup" class="form-control" value="{{ $dest->jam_tutup }}">
                                                        </div>
                                                    </div>

                                                    <!-- Lokasi -->
                                                    <h6 class="fw-bold text-primary mt-3 mb-3 border-bottom pb-2">Lokasi & Koordinat</h6>
                                                    <div class="mb-3">
                                                        <label class="form-label">Alamat Lengkap *</label>
                                                        <textarea name="alamat" class="form-control" rows="2" required>{{ $dest->alamat }}</textarea>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Latitude *</label>
                                                            <input type="text" name="latitude" class="form-control" value="{{ $dest->latitude }}" required>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Longitude *</label>
                                                            <input type="text" name="longitude" class="form-control" value="{{ $dest->longitude }}" required>
                                                        </div>
                                                    </div>

                                                    <!-- Kontak & Media -->
                                                    <h6 class="fw-bold text-primary mt-3 mb-3 border-bottom pb-2">Media & Lainnya</h6>
                                                    <div class="mb-3">
                                                        <label class="form-label">Deskripsi Lengkap *</label>
                                                        <textarea name="deskripsi" class="form-control" rows="3" required>{{ $dest->deskripsi }}</textarea>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Website</label>
                                                            <input type="url" name="website" class="form-control" value="{{ $dest->website }}" placeholder="https://...">
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">No. Telepon</label>
                                                            <input type="text" name="telepon" class="form-control" value="{{ $dest->telepon }}">
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Update Foto (Opsional)</label>
                                                        @if($dest->foto)
                                                            <div class="mb-2">
                                                                <img src="{{ asset('images/destinasi/'.$dest->foto) }}" class="img-thumbnail" style="height: 80px;">
                                                                <small class="text-muted d-block">Foto saat ini</small>
                                                            </div>
                                                        @endif
                                                        <input type="file" name="foto" class="form-control" accept="image/*">
                                                        <small class="text-muted">Biarkan kosong jika tidak ingin mengganti foto.</small>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Status</label>
                                                        <select name="status" class="form-select">
                                                            <option value="aktif" {{ $dest->status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                                            <option value="non-aktif" {{ $dest->status == 'non-aktif' ? 'selected' : '' }}>Non-Aktif</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer bg-light">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary">Update Perubahan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- END MODAL EDIT -->

                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">Belum ada data destinasi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">{{ $destinasi->links() }}</div>
            </div>
        </div>
    </div>

    <!-- MODAL TAMBAH (Create) -->
    <!-- Sama seperti sebelumnya tapi ditambah field lengkap -->
    <div class="modal fade" id="modalTambahDestinasi" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title fw-bold"><i class="bi bi-plus-circle me-2"></i> Tambah Destinasi Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.destinasi.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <!-- Gunakan struktur form yang sama persis dengan modal edit di atas, tapi value-nya kosong -->
                        <!-- SAYA SINGKAT BAGIAN INI AGAR TIDAK KEPANJANGAN, INTINYA SAMA TAPI KOSONG -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Destinasi *</label>
                                <input type="text" name="nama" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kategori *</label>
                                <select name="kategori" class="form-select" required>
                                    <option value="Pantai">Pantai</option>
                                    <option value="Pulau">Pulau</option>
                                    <option value="Air Terjun">Air Terjun</option>
                                    <option value="Bukit">Bukit</option>
                                    <option value="Taman">Taman</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Harga Tiket *</label>
                                <input type="number" name="harga_tiket" class="form-control" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Latitude</label>
                                <input type="text" name="latitude" class="form-control" value="-5.4297" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Longitude</label>
                                <input type="text" name="longitude" class="form-control" value="105.2625" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alamat *</label>
                            <textarea name="alamat" class="form-control" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi *</label>
                            <textarea name="deskripsi" class="form-control" required></textarea>
                        </div>
                         <div class="mb-3">
                            <label class="form-label">Foto Utama</label>
                            <input type="file" name="foto" class="form-control" accept="image/*">
                        </div>
                        <input type="hidden" name="status" value="aktif">
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Simpan Baru</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
