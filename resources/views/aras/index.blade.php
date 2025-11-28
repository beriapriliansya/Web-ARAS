@extends('layouts.app')

@section('title', 'Perhitungan Metode ARAS')

@section('content')
<!-- Page Header -->
<div class="bg-primary text-white py-5">
    <div class="container">
        <h1 class="display-4 fw-bold mb-2">
            <i class="bi bi-calculator"></i> Perhitungan Metode ARAS
        </h1>
        <p class="lead mb-0">
            Additive Ratio Assessment - Metode untuk ranking destinasi wisata terbaik
        </p>
    </div>
</div>

<!-- Info ARAS -->
<div class="container my-5">
    <div class="card mb-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0"><i class="bi bi-info-circle"></i> Tentang Metode ARAS</h5>
        </div>
        <div class="card-body">
            <p class="mb-0">
                <strong>ARAS (Additive Ratio Assessment)</strong> adalah metode pengambilan keputusan multi-kriteria
                yang digunakan untuk menentukan ranking alternatif berdasarkan utilitas (degree of utility).
                Metode ini membandingkan setiap alternatif dengan solusi optimal untuk menghasilkan ranking terbaik.
            </p>
        </div>
    </div>

    <!-- Data Kriteria -->
    <div class="card mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="bi bi-list-check"></i> Kriteria Penilaian ({{ $kriteria->count() }})</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Kode</th>
                            <th>Nama Kriteria</th>
                            <th>Bobot</th>
                            <th>Tipe</th>
                            <th>Satuan</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($kriteria as $k)
                            <tr>
                                <td><span class="badge bg-primary">{{ $k->kode }}</span></td>
                                <td class="fw-bold">{{ $k->nama_kriteria }}</td>
                                <td>
                                    <strong>{{ number_format($k->bobot, 4) }}</strong>
                                    <small class="text-muted">({{ number_format($k->bobot * 100, 2) }}%)</small>
                                </td>
                                <td>
                                    @if($k->tipe == 'benefit')
                                        <span class="badge bg-success">
                                            <i class="bi bi-arrow-up"></i> Benefit
                                        </span>
                                    @else
                                        <span class="badge bg-warning">
                                            <i class="bi bi-arrow-down"></i> Cost
                                        </span>
                                    @endif
                                </td>
                                <td>{{ $k->satuan }}</td>
                                <td class="small text-muted">{{ $k->keterangan }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="2" class="text-end fw-bold">Total Bobot:</td>
                            <td colspan="4">
                                <strong>{{ number_format($kriteria->sum('bobot'), 4) }}</strong>
                                <small class="text-muted">(100%)</small>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- Data Destinasi -->
    <div class="card mb-4">
        <div class="card-header bg-warning">
            <h5 class="mb-0"><i class="bi bi-pin-map"></i> Destinasi Wisata ({{ $destinasi->count() }})</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Destinasi</th>
                            @foreach($kriteria as $k)
                                <th class="text-center">
                                    {{ $k->kode }}<br>
                                    <small class="text-muted">{{ $k->satuan }}</small>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($destinasi as $d)
                            <tr>
                                <td class="fw-bold">{{ $d->nama }}</td>
                                @foreach($kriteria as $k)
                                    @php
                                        $alt = $d->alternatif->where('kriteria_id', $k->id)->first();
                                        $nilai = $alt ? $alt->nilai : '-';
                                    @endphp
                                    <td class="text-center">{{ $nilai }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Tombol Hitung -->
    <div class="card">
        <div class="card-body text-center py-5">
            <h3 class="mb-4">Proses Perhitungan ARAS</h3>
            <p class="text-muted mb-4">
                Klik tombol di bawah untuk memulai perhitungan metode ARAS dan mendapatkan ranking destinasi wisata terbaik
            </p>
            <form action="{{ route('admin.aras.hitung') }}" method="POST">
                  onsubmit="return confirm('Yakin ingin menghitung ulang? Data hasil sebelumnya akan ditimpa.')">
                @csrf
                <button type="submit" class="btn btn-primary btn-lg px-5">
                    <i class="bi bi-calculator"></i> Hitung Metode ARAS
                </button>
            </form>
        </div>
    </div>

    <!-- Hasil ARAS (jika sudah ada) -->
    @if($hasilAras->count() > 0)
        <div class="card mt-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-trophy"></i> Hasil Perhitungan ARAS</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-success">
                    <i class="bi bi-check-circle"></i>
                    Perhitungan terakhir: <strong>{{ $hasilAras->first()->tanggal_hitung->format('d M Y H:i') }}</strong>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Ranking</th>
                                <th>Destinasi</th>
                                <th>Kategori</th>
                                <th>Nilai Utilitas (Ki)</th>
                                <th>Persentase</th>
                                <th>Kategori Performa</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($hasilAras as $hasil)
                                <tr>
                                    <td>
                                        @if($hasil->ranking == 1)
                                            <span class="fs-4">🥇</span>
                                        @elseif($hasil->ranking == 2)
                                            <span class="fs-4">🥈</span>
                                        @elseif($hasil->ranking == 3)
                                            <span class="fs-4">🥉</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $hasil->ranking }}</span>
                                        @endif
                                    </td>
                                    <td class="fw-bold">{{ $hasil->destinasi->nama }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $hasil->destinasi->kategori }}</span>
                                    </td>
                                    <td>
                                        <strong>{{ number_format($hasil->utilitas, 4) }}</strong>
                                    </td>
                                    <td>
                                        <div class="progress" style="height: 25px;">
                                            <div class="progress-bar bg-success"
                                                 role="progressbar"
                                                 style="width: {{ $hasil->persentase }}%">
                                                {{ number_format($hasil->persentase, 2) }}%
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge
                                            @if($hasil->kategori_performa == 'Sangat Baik') bg-success
                                            @elseif($hasil->kategori_performa == 'Baik') bg-primary
                                            @elseif($hasil->kategori_performa == 'Cukup') bg-warning
                                            @else bg-danger
                                            @endif">
                                            {{ $hasil->kategori_performa }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('destinasi.show', $hasil->destinasi->id) }}"
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="text-center mt-3">
                    <a href="{{ route('aras.ranking') }}" class="btn btn-success">
                        <i class="bi bi-stars"></i> Lihat Halaman Rekomendasi Lengkap
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
