<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Destinasi') }}
        </h2>
    </x-slot>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Header -->
                <div class="card mb-4 shadow-sm border-0">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="fw-bold mb-1 text-primary">
                                <i class="bi bi-pencil-square"></i> Edit Destinasi
                            </h2>
                            <p class="text-muted mb-0">Update informasi: <strong>{{ $destinasi->nama }}</strong></p>
                        </div>
                        <a href="{{ route('admin.destinasi.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>

                <!-- Form -->
                <form action="{{ route('admin.destinasi.update', $destinasi->id) }}" method="POST" enctype="multipart/form-data" onsubmit="return confirm('Apakah Anda yakin ingin memperbarui data destinasi wisata ini?');">
                    @csrf
                    @method('PUT')

                    <!-- Informasi Dasar -->
                    <div class="card mb-4 shadow-sm border-0">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fw-bold text-dark">📝 Informasi Dasar</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-8">
                                    <label class="form-label fw-bold">Nama Destinasi *</label>
                                    <input type="text" class="form-control" name="nama" value="{{ old('nama', $destinasi->nama) }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Kategori *</label>
                                    <select class="form-select" name="kategori" required>
                                        <option value="">Pilih Kategori</option>
                                        @foreach(['Alam', 'Pantai', 'Gunung', 'Air Terjun', 'Budaya', 'Kuliner', 'Religi', 'Edukasi'] as $kat)
                                            <option value="{{ $kat }}" {{ old('kategori', $destinasi->kategori) == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold">Deskripsi *</label>
                                    <textarea class="form-control" name="deskripsi" rows="5" required>{{ old('deskripsi', $destinasi->deskripsi) }}</textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold">Alamat Lengkap *</label>
                                    <textarea class="form-control" name="alamat" rows="2" required>{{ old('alamat', $destinasi->alamat) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Lokasi -->
                    <div class="card mb-4 shadow-sm border-0">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fw-bold text-dark">📍 Koordinat Lokasi</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Latitude *</label>
                                    <input type="number" step="0.00000001" class="form-control" name="latitude" value="{{ old('latitude', $destinasi->latitude) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Longitude *</label>
                                    <input type="number" step="0.00000001" class="form-control" name="longitude" value="{{ old('longitude', $destinasi->longitude) }}" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Info Tambahan -->
                    <div class="card mb-4 shadow-sm border-0">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fw-bold text-dark">ℹ️ Informasi Tambahan</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Harga Tiket (Rp) *</label>
                                    <input type="number" class="form-control" name="harga_tiket" value="{{ old('harga_tiket', $destinasi->harga_tiket) }}" min="0" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Jam Buka</label>
                                    <input type="time" class="form-control" name="jam_buka" value="{{ old('jam_buka', $destinasi->jam_buka ? \Carbon\Carbon::parse($destinasi->jam_buka)->format('H:i') : '') }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Jam Tutup</label>
                                    <input type="time" class="form-control" name="jam_tutup" value="{{ old('jam_tutup', $destinasi->jam_tutup ? \Carbon\Carbon::parse($destinasi->jam_tutup)->format('H:i') : '') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Nomor Telepon</label>
                                    <input type="text" class="form-control" name="telepon" value="{{ old('telepon', $destinasi->telepon) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Website</label>
                                    <input type="url" class="form-control" name="website" value="{{ old('website', $destinasi->website) }}">
                                </div>

                                <!-- Foto -->
                                <div class="col-12">
                                    <label class="form-label fw-bold">Foto Destinasi</label>
                                    @if($destinasi->foto)
                                        <div class="mb-2 p-2 border rounded bg-light d-inline-block">
                                            <img src="{{ asset('images/destinasi/' . $destinasi->foto) }}" class="img-thumbnail" style="max-height: 150px;">
                                            <div class="text-muted small mt-1 text-center">Foto Saat Ini</div>
                                        </div>
                                    @endif
                                    <input type="file" class="form-control" name="foto" accept="image/jpeg,image/jpg,image/png">
                                </div>

                                <!-- Status -->
                                <div class="col-12">
                                    <label class="form-label fw-bold">Status Publikasi</label>
                                    <select name="status" class="form-select bg-light">
                                        <option value="aktif" {{ $destinasi->status == 'aktif' ? 'selected' : '' }}>✅ Aktif</option>
                                        <option value="non-aktif" {{ $destinasi->status == 'non-aktif' ? 'selected' : '' }}>❌ Non-Aktif</option>
                                    </select>
                                </div>

                                <!-- Fasilitas -->
                                <div class="col-12">
                                    <label class="form-label fw-bold mb-3">Fasilitas</label>
                                    <div class="row">
                                        @php
                                            $fasilitasOptions = ['Parkir', 'Toilet', 'Mushola', 'Warung Makan', 'Gazebo', 'Penginapan', 'Area Bermain', 'Spot Foto', 'WiFi'];
                                            $selectedFasilitas = $destinasi->fasilitas ?? [];
                                        @endphp
                                        @foreach($fasilitasOptions as $fas)
                                            <div class="col-md-3 col-6 mb-2">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="fasilitas[]"
                                                           value="{{ $fas }}" id="fas_{{ $loop->index }}"
                                                           {{ in_array($fas, $selectedFasilitas) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="fas_{{ $loop->index }}">{{ $fas }}</label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="card shadow-sm border-0 mb-5">
                        <div class="card-body d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.destinasi.show', $destinasi->id) }}" class="btn btn-secondary px-4">
                                <i class="bi bi-x-circle"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary px-4 fw-bold">
                                <i class="bi bi-save"></i> Update Destinasi
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
