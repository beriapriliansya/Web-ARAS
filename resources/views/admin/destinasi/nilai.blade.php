<x-app-layout>
    <div class="container py-5">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.destinasi.index') }}">Destinasi</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.destinasi.show', $destinasi->id) }}">{{ $destinasi->nama }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Kelola Nilai Kriteria</li>
            </ol>
        </nav>

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow border-0 rounded-4 overflow-hidden">
                    <div class="card-header bg-success text-white py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold"><i class="bi bi-pencil-square me-2"></i>Kelola Nilai Alternatif (Kriteria)</h5>
                        <a href="{{ route('admin.destinasi.show', $destinasi->id) }}" class="btn btn-sm btn-light fw-bold text-success">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-4 text-center">
                            <h4 class="fw-bold mb-1">{{ $destinasi->nama }}</h4>
                            <span class="badge bg-secondary">{{ $destinasi->kategori }}</span>
                        </div>
                        <hr>
                        <p class="text-secondary small mb-4">
                            Masukkan nilai kriteria untuk alternatif destinasi ini. Nilai ini akan digunakan dalam perhitungan normalisasi dan optimasi matriks keputusan metode ARAS.
                        </p>

                        <form action="{{ route('admin.destinasi.nilai.update', $destinasi->id) }}" method="POST">
                            @csrf
                            
                            @foreach($kriteria as $k)
                                @php
                                    $currentVal = isset($alternatifValues[$k->id]) ? $alternatifValues[$k->id] : '';
                                    
                                    // Set default input helper berdasarkan jenis kriteria
                                    $placeholder = 'Masukkan nilai...';
                                    if ($k->kode == 'C5') {
                                        $placeholder = 'Contoh: 15000 (untuk harga tiket masuk)';
                                    } elseif ($k->satuan == 'Skor') {
                                        $placeholder = 'Skala 1 - 5 (Contoh: 4.5)';
                                    }
                                @endphp
                                <div class="mb-4 p-3 bg-light rounded border-start border-4 border-success">
                                    <div class="d-flex justify-content-between mb-2">
                                        <label class="fw-bold text-dark" for="nilai_{{ $k->id }}">
                                            <span class="badge bg-dark me-2">{{ $k->kode }}</span>
                                            {{ $k->nama_kriteria }}
                                        </label>
                                        <span class="badge {{ $k->tipe == 'benefit' ? 'bg-success' : 'bg-danger' }}">
                                            {{ ucfirst($k->tipe) }}
                                        </span>
                                    </div>
                                    
                                    <div class="input-group">
                                        @if($k->satuan == 'Rp')
                                            <span class="input-group-text">Rp</span>
                                        @endif
                                        
                                        <input type="number" step="0.01" class="form-control fw-bold" 
                                               name="nilai_{{ $k->id }}" 
                                               id="nilai_{{ $k->id }}"
                                               value="{{ old('nilai_'.$k->id, $currentVal) }}" 
                                               placeholder="{{ $placeholder }}"
                                               required>
                                               
                                        @if($k->satuan && $k->satuan != 'Rp')
                                            <span class="input-group-text">{{ $k->satuan }}</span>
                                        @endif
                                    </div>
                                    <div class="form-text small mt-1 text-muted">{{ $k->keterangan }}</div>
                                </div>
                            @endforeach

                            <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                                <a href="{{ route('admin.destinasi.show', $destinasi->id) }}" class="btn btn-secondary px-4">
                                    Batal
                                </a>
                                <button type="submit" class="btn btn-success px-4 fw-bold">
                                    <i class="bi bi-save"></i> Simpan Nilai Kriteria
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
