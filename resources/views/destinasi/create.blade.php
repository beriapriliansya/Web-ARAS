@extends('layouts.app')

@section('title', 'Tambah Destinasi Wisata')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Header -->
            <div class="card mb-4">
                <div class="card-body">
                    <h2 class="fw-bold mb-2">
                        <i class="bi bi-plus-circle"></i> Tambah Destinasi Wisata
                    </h2>
                    <p class="text-muted mb-0">Lengkapi form di bawah untuk menambahkan destinasi wisata baru</p>
                </div>
            </div>

            <!-- Form -->
            <form action="{{ route('destinasi.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Informasi Dasar -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">📝 Informasi Dasar</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <!-- Nama -->
                            <div class="col-md-8">
                                <label class="form-label fw-bold">Nama Destinasi *</label>
                                <input type="text" class="form-control @error('nama') is-invalid @enderror"
                                       name="nama" value="{{ old('nama') }}" required>
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Kategori -->
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Kategori *</label>
                                <select class="form-select @error('kategori') is-invalid @enderror" name="kategori" required>
                                    <option value="">Pilih Kategori</option>
                                    <option value="Alam" {{ old('kategori') == 'Alam' ? 'selected' : '' }}>Alam</option>
                                    <option value="Pantai" {{ old('kategori') == 'Pantai' ? 'selected' : '' }}>Pantai</option>
                                    <option value="Gunung" {{ old('kategori') == 'Gunung' ? 'selected' : '' }}>Gunung</option>
                                    <option value="Air Terjun" {{ old('kategori') == 'Air Terjun' ? 'selected' : '' }}>Air Terjun</option>
                                    <option value="Budaya" {{ old('kategori') == 'Budaya' ? 'selected' : '' }}>Budaya</option>
                                    <option value="Kuliner" {{ old('kategori') == 'Kuliner' ? 'selected' : '' }}>Kuliner</option>
                                    <option value="Religi" {{ old('kategori') == 'Religi' ? 'selected' : '' }}>Religi</option>
                                    <option value="Edukasi" {{ old('kategori') == 'Edukasi' ? 'selected' : '' }}>Edukasi</option>
                                </select>
                                @error('kategori')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Deskripsi -->
                            <div class="col-12">
                                <label class="form-label fw-bold">Deskripsi *</label>
                                <textarea class="form-control @error('deskripsi') is-invalid @enderror"
                                          name="deskripsi" rows="5" required>{{ old('deskripsi') }}</textarea>
                                @error('deskripsi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Alamat -->
                            <div class="col-12">
                                <label class="form-label fw-bold">Alamat Lengkap *</label>
                                <textarea class="form-control @error('alamat') is-invalid @enderror"
                                          name="alamat" rows="2" required>{{ old('alamat') }}</textarea>
                                @error('alamat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Lokasi Google Maps -->
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">📍 Koordinat Lokasi (Google Maps)</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Latitude *</label>
                                <input type="number" step="0.00000001" class="form-control @error('latitude') is-invalid @enderror"
                                       name="latitude" value="{{ old('latitude', -5.4513206) }}" required>
                                <small class="text-muted">Contoh: -5.4513206</small>
                                @error('latitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Longitude *</label>
                                <input type="number" step="0.00000001" class="form-control @error('longitude') is-invalid @enderror"
                                       name="longitude" value="{{ old('longitude', 105.2700861) }}" required>
                                <small class="text-muted">Contoh: 105.2700861</small>
                                @error('longitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle"></i>
                                    <strong>Cara mendapatkan koordinat:</strong> Buka Google Maps →
                                    Klik kanan pada lokasi → Klik koordinat yang muncul untuk copy
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informasi Tambahan -->
                <div class="card mb-4">
                    <div class="card-header bg-warning">
                        <h5 class="mb-0">ℹ️ Informasi Tambahan</h5>
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

                            <!-- Jam Buka -->
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Jam Buka</label>
                                <input type="time" class="form-control @error('jam_buka') is-invalid @enderror"
                                       name="jam_buka" value="{{ old('jam_buka', '07:00') }}">
                                @error('jam_buka')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Jam Tutup -->
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Jam Tutup</label>
                                <input type="time" class="form-control @error('jam_tutup') is-invalid @enderror"
                                       name="jam_tutup" value="{{ old('jam_tutup', '17:00') }}">
                                @error('jam_tutup')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Telepon -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Nomor Telepon</label>
                                <input type="text" class="form-control @error('telepon') is-invalid @enderror"
                                       name="telepon" value="{{ old('telepon') }}" placeholder="081234567890">
                                @error('telepon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Website -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Website</label>
                                <input type="url" class="form-control @error('website') is-invalid @enderror"
                                       name="website" value="{{ old('website') }}" placeholder="https://example.com">
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
                                @error('foto')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Fasilitas -->
                            <div class="col-12">
                                <label class="form-label fw-bold">Fasilitas</label>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="fasilitas[]" value="Parkir" id="parkir">
                                            <label class="form-check-label" for="parkir">Parkir</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="fasilitas[]" value="Toilet" id="toilet">
                                            <label class="form-check-label" for="toilet">Toilet</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="fasilitas[]" value="Mushola" id="mushola">
                                            <label class="form-check-label" for="mushola">Mushola</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="fasilitas[]" value="Warung Makan" id="warung">
                                            <label class="form-check-label" for="warung">Warung Makan</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="fasilitas[]" value="Gazebo" id="gazebo">
                                            <label class="form-check-label" for="gazebo">Gazebo</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="fasilitas[]" value="Penginapan" id="penginapan">
                                            <label class="form-check-label" for="penginapan">Penginapan</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="fasilitas[]" value="Area Bermain" id="area_bermain">
                                            <label class="form-check-label" for="area_bermain">Area Bermain</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('destinasi.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Simpan Destinasi
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
