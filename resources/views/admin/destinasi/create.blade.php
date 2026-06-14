<x-app-layout>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Header -->
                <div class="card mb-4 shadow-sm border-0">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="fw-bold mb-1 text-primary">
                                <i class="bi bi-plus-circle-fill"></i> Tambah Destinasi
                            </h2>
                            <p class="text-muted mb-0">Lengkapi form di bawah untuk menambahkan wisata baru</p>
                        </div>
                        <a href="{{ route('admin.destinasi.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>

                <!-- Form -->
                <form action="{{ route('admin.destinasi.store') }}" method="POST" enctype="multipart/form-data" onsubmit="return confirm('Apakah Anda yakin ingin menyimpan destinasi wisata baru ini?');">
                    @csrf

                    <!-- Informasi Dasar -->
                    <div class="card mb-4 shadow-sm border-0">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fw-bold text-dark">📝 Informasi Dasar</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <!-- Nama -->
                                <div class="col-md-8">
                                    <label class="form-label fw-bold">Nama Destinasi *</label>
                                    <input type="text" class="form-control @error('nama') is-invalid @enderror"
                                           name="nama" value="{{ old('nama') }}" required placeholder="Contoh: Pantai Pahawang">
                                    @error('nama')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Kategori -->
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Kategori *</label>
                                    <select class="form-select @error('kategori') is-invalid @enderror" name="kategori" required>
                                        <option value="Pantai" selected>Pantai</option>
                                    </select>
                                    @error('kategori')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Deskripsi -->
                                <div class="col-12">
                                    <label class="form-label fw-bold">Deskripsi *</label>
                                    <textarea class="form-control @error('deskripsi') is-invalid @enderror"
                                              name="deskripsi" rows="5" required placeholder="Jelaskan daya tarik tempat ini...">{{ old('deskripsi') }}</textarea>
                                    @error('deskripsi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Alamat -->
                                <div class="col-12">
                                    <label class="form-label fw-bold">Alamat Lengkap *</label>
                                    <textarea class="form-control @error('alamat') is-invalid @enderror"
                                              name="alamat" rows="2" required placeholder="Alamat lengkap lokasi...">{{ old('alamat') }}</textarea>
                                    @error('alamat')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Lokasi Google Maps -->
                    <div class="card mb-4 shadow-sm border-0">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fw-bold text-dark">📍 Koordinat Lokasi (Google Maps)</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Latitude *</label>
                                    <input type="number" step="0.00000001" class="form-control @error('latitude') is-invalid @enderror"
                                           name="latitude" value="{{ old('latitude', '-5.4297') }}" required>
                                    @error('latitude')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Longitude *</label>
                                    <input type="number" step="0.00000001" class="form-control @error('longitude') is-invalid @enderror"
                                           name="longitude" value="{{ old('longitude', '105.2625') }}" required>
                                    @error('longitude')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <div class="alert alert-info d-flex align-items-center mb-0">
                                        <i class="bi bi-info-circle-fill me-2 fs-4"></i>
                                        <div>
                                            <strong>Cara mendapatkan koordinat:</strong> Buka Google Maps → Klik kanan pada lokasi → Klik angka koordinat yang muncul untuk menyalin.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Tambahan -->
                    <div class="card mb-4 shadow-sm border-0">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fw-bold text-dark">ℹ️ Informasi Tambahan</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <!-- Harga Tiket -->
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Harga Tiket (Rp) *</label>
                                    <input type="number" class="form-control @error('harga_tiket') is-invalid @enderror"
                                           name="harga_tiket" value="{{ old('harga_tiket', 0) }}" min="0" required>
                                    @error('harga_tiket')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Jam Operasional -->
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Jam Buka</label>
                                    <input type="time" class="form-control" name="jam_buka" value="{{ old('jam_buka', '07:00') }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Jam Tutup</label>
                                    <input type="time" class="form-control" name="jam_tutup" value="{{ old('jam_tutup', '17:00') }}">
                                </div>

                                <!-- Kontak -->
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Nomor Telepon</label>
                                    <input type="text" class="form-control" name="telepon" value="{{ old('telepon') }}" placeholder="081234567890">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Website (Opsional)</label>
                                    <input type="text" class="form-control @error('website') is-invalid @enderror" 
                                           name="website" value="{{ old('website') }}" placeholder="https://example.com atau www.example.com">
                                    @error('website')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Foto -->
                                <div class="col-12">
                                    <label class="form-label fw-bold">Foto Destinasi</label>
                                    <input type="file" class="form-control @error('foto') is-invalid @enderror"
                                           name="foto" accept="image/jpeg,image/jpg,image/png">
                                    <small class="text-muted">Format: JPG, JPEG, PNG. Maksimal 2MB</small>
                                </div>

                                <!-- Status -->
                                <div class="col-12">
                                    <label class="form-label fw-bold">Status Publikasi</label>
                                    <select name="status" class="form-select bg-light">
                                        <option value="aktif" selected>✅ Aktif (Tampil di Web)</option>
                                        <option value="nonaktif">❌ Non-Aktif (Sembunyikan)</option>
                                    </select>
                                </div>

                                <!-- Fasilitas (Checkbox) -->
                                <div class="col-12">
                                    <label class="form-label fw-bold mb-3">Fasilitas Tersedia</label>
                                    <div class="row">
                                        @php
                                            $fasilitasOptions = \App\Models\Fasilitas::orderBy('nama_fasilitas')->pluck('nama_fasilitas')->toArray();
                                            if (empty($fasilitasOptions)) {
                                                $fasilitasOptions = ['Parkir', 'Toilet', 'Mushola', 'Warung Makan', 'Gazebo', 'Penginapan', 'Area Bermain', 'Spot Foto', 'WiFi'];
                                            }
                                        @endphp
                                        @forelse($fasilitasOptions as $fas)
                                            <div class="col-md-3 col-6 mb-2">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="fasilitas[]" value="{{ $fas }}" id="fas_{{ $loop->index }}">
                                                    <label class="form-check-label" for="fas_{{ $loop->index }}">{{ $fas }}</label>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="col-12 text-muted">
                                                Belum ada pilihan fasilitas. Silakan kelola di menu <a href="{{ route('admin.fasilitas.index') }}" target="_blank">Kelola Fasilitas</a>.
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="card shadow-sm border-0 mb-5">
                        <div class="card-body d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.destinasi.index') }}" class="btn btn-secondary px-4">
                                <i class="bi bi-x-circle"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary px-4 fw-bold">
                                <i class="bi bi-save"></i> Simpan Destinasi
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
