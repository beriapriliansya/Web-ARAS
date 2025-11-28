@extends('layouts.app')

@section('title', 'Edit ' . $destinasi->nama)

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Header -->
            <div class="card mb-4">
                <div class="card-body">
                    <h2 class="fw-bold mb-2">
                        <i class="bi bi-pencil-square"></i> Edit Destinasi Wisata
                    </h2>
                    <p class="text-muted mb-0">Update informasi destinasi: <strong>{{ $destinasi->nama }}</strong></p>
                </div>
            </div>

            <!-- Form -->
            <form action="{{ route('destinasi.update', $destinasi->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Informasi Dasar -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">📝 Informasi Dasar</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label fw-bold">Nama Destinasi *</label>
                                <input type="text" class="form-control @error('nama') is-invalid @enderror"
                                       name="nama" value="{{ old('nama', $destinasi->nama) }}" required>
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">Kategori *</label>
                                <select class="form-select @error('kategori') is-invalid @enderror" name="kategori" required>
                                    <option value="">Pilih Kategori</option>
                                    @foreach(['Alam', 'Pantai', 'Gunung', 'Air Terjun', 'Budaya', 'Kuliner', 'Religi', 'Edukasi'] as $kat)
                                        <option value="{{ $kat }}" {{ old('kategori', $destinasi->kategori) == $kat ? 'selected' : '' }}>
                                            {{ $kat }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('kategori')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold">Deskripsi *</label>
                                <textarea class="form-control @error('deskripsi') is-invalid @enderror"
                                          name="deskripsi" rows="5" required>{{ old('deskripsi', $destinasi->deskripsi) }}</textarea>
                                @error('deskripsi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold">Alamat Lengkap *</label>
                                <textarea class="form-control @error('alamat') is-invalid @enderror"
                                          name="alamat" rows="2" required>{{ old('alamat', $destinasi->alamat) }}</textarea>
                                @error('alamat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Lokasi -->
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">📍 Koordinat Lokasi</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Latitude *</label>
                                <input type="number" step="0.00000001" class="form-control @error('latitude') is-invalid @enderror"
                                       name="latitude" value="{{ old('latitude', $destinasi->latitude) }}" required>
                                @error('latitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Longitude *</label>
                                <input type="number" step="0.00000001" class="form-control @error('longitude') is-invalid @enderror"
                                       name="longitude" value="{{ old('longitude', $destinasi->longitude) }}" required>
                                @error('longitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Harga Tiket (Rp) *</label>
                                <input type="number" class="form-control @error('harga_tiket') is-invalid @enderror"
                                       name="harga_tiket" value="{{ old('harga_tiket', $destinasi->harga_tiket) }}" min="0" required>
                                @error('harga_tiket')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">Jam Buka</label>
                                <input type="time" class="form-control @error('jam_buka') is-invalid @enderror"
                                       name="jam_buka" value="{{ old('jam_buka', $destinasi->jam_buka ? $destinasi->jam_buka->format('H:i') : '') }}">
                                @error('jam_buka')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">Jam Tutup</label>
                                <input type="time" class="form-control @error('jam_tutup') is-invalid @enderror"
                                       name="jam_tutup" value="{{ old('jam_tutup', $destinasi->jam_tutup ? $destinasi->jam_tutup->format('H:i') : '') }}">
                                @error('jam_tutup')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Nomor Telepon</label>
                                <input type="text" class="form-control @error('telepon') is-invalid @enderror"
                                       name="telepon" value="{{ old('telepon', $destinasi->telepon) }}">
                                @error('telepon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Website</label>
                                <input type="url" class="form-control @error('website') is-invalid @enderror"
                                       name="website" value="{{ old('website', $destinasi->website) }}">
                                @error('website')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold">Foto Destinasi</label>
                                @if($destinasi->foto)
                                    <div class="mb-2">
                                        <img src="{{ asset('images/destinasi/' . $destinasi->foto) }}"
                                             alt="{{ $destinasi->nama }}"
                                             class="img-thumbnail"
                                             style="max-height: 200px;">
                                        <p class="text-muted small mt-1">Foto saat ini: {{ $destinasi->foto }}</p>
                                    </div>
                                @endif
                                <input type="file" class="form-control @error('foto') is-invalid @enderror"
                                       name="foto" accept="image/jpeg,image/jpg,image/png">
                                <small class="text-muted">Upload foto baru untuk mengganti. Format: JPG, JPEG, PNG. Maksimal 2MB</small>
                                @error('foto')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold">Fasilitas</label>
                                <div class="row">
                                    @php
                                        $fasilitasOptions = ['Parkir', 'Toilet', 'Mushola', 'Warung Makan', 'Gazebo', 'Penginapan', 'Area Bermain'];
                                        $selectedFasilitas = old('fasilitas', $destinasi->fasilitas ?? []);
                                    @endphp
                                    @foreach($fasilitasOptions as $fas)
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="fasilitas[]"
                                                       value="{{ $fas }}" id="fas_{{ $loop->index }}"
                                                       {{ in_array($fas, $selectedFasilitas) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="fas_{{ $loop->index }}">
                                                    {{ $fas }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('destinasi.show', $destinasi->id) }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Update Destinasi
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
