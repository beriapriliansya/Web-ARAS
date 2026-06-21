<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Hasil Perhitungan ARAS - Wisata Pesawaran</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            color: #000;
            background-color: #fff;
            padding: 20px;
        }
        .header-print {
            border-bottom: 3px double #000;
            padding-bottom: 10px;
            margin-bottom: 30px;
        }
        .title-report {
            text-align: center;
            text-transform: uppercase;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .table-print {
            border-color: #000 !important;
            font-size: 0.9rem;
        }
        .table-print th, .table-print td {
            border: 1px solid #000 !important;
            padding: 6px 10px !important;
        }
        .signature-area {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }
        .signature-box {
            text-align: center;
            width: 250px;
        }
        .signature-space {
            height: 80px;
        }
        
        /* Print styling rules */
        @media print {
            body {
                padding: 0;
            }
            .no-print {
                display: none !important;
            }
            .page-break {
                page-break-before: always;
            }
        }
    </style>
</head>
<body>

    <!-- Tombol Aksi (Hanya Tampil di Browser) -->
    <div class="container no-print mb-4 p-3 bg-light border rounded d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0 fw-bold text-primary">Pratinjau Cetak Laporan</h5>
            <small class="text-muted">Siap dicetak ke printer atau disimpan sebagai file PDF.</small>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.aras.index') }}" class="btn btn-secondary fw-bold">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <button onclick="window.print()" class="btn btn-primary fw-bold">
                Cetak / Simpan PDF
            </button>
        </div>
    </div>

    <!-- Kop Surat Dinas Pariwisata -->
    <div class="header-print text-center">
        <h4 class="mb-0 fw-bold">PEMERINTAH KABUPATEN PESAWARAN</h4>
        <h3 class="mb-1 fw-bold">DINAS PARIWISATA KABUPATEN PESAWARAN</h3>
        <p class="mb-0 small">Jl. Raya Way Ratai No. 1, Kabupaten Pesawaran, Lampung</p>
        <p class="mb-0 small">Telepon: (0721) 123456 | Email: pariwisata@pesawarankab.go.id</p>
    </div>

    <!-- Judul Laporan -->
    <div class="title-report">
        <h5>LAPORAN HASIL PERHITUNGAN METODE ARAS</h5>
        <h6>REKOMENDASI DESTINASI WISATA AIR TERBAIK</h6>
        <p class="small text-muted" style="text-transform: none;">Tanggal Cetak: {{ date('d F Y H:i') }}</p>
    </div>

    <!-- 1. Matriks Keputusan (X) -->
    <h5 class="fw-bold mb-2">1. Matriks Keputusan (X) & Nilai Optimum (A0)</h5>
    <table class="table table-bordered table-print text-center align-middle mb-4">
        <thead class="table-light">
            <tr>
                <th>Alternatif / Kode</th>
                @foreach($kriteria as $k)
                    <th>{{ $k->kode }}<br><small>({{ ucfirst($k->tipe) }})</small></th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            <!-- Baris Optimal A0 -->
            <tr class="fw-bold bg-light">
                <td>A0 (Optimal)</td>
                @foreach($kriteria as $k)
                    <td>{{ $result['x0'][$k->id] }}</td>
                @endforeach
            </tr>
            <!-- Baris Destinasi -->
            @foreach($destinasi as $d)
                <tr>
                    <td class="text-start font-monospace">A{{ $destinasi->pluck('id')->indexOf($d->id) + 1 }} - {{ $d->nama }}</td>
                    @foreach($kriteria as $k)
                        <td>{{ $result['matriks'][$d->id][$k->id] }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- 2. Matriks Normalisasi (R) -->
    <h5 class="fw-bold mb-2">2. Matriks Normalisasi (R)</h5>
    <table class="table table-bordered table-print text-center align-middle mb-4">
        <thead class="table-light">
            <tr>
                <th>Alternatif</th>
                @foreach($kriteria as $k)
                    <th>{{ $k->kode }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            <!-- Baris Optimal A0 -->
            <tr class="fw-bold bg-light">
                <td>A0</td>
                @foreach($kriteria as $k)
                    <td>{{ number_format($result['matriksR']['A0'][$k->id], 4) }}</td>
                @endforeach
            </tr>
            <!-- Baris Destinasi -->
            @foreach($destinasi as $d)
                <tr>
                    <td class="text-start font-monospace">A{{ $destinasi->pluck('id')->indexOf($d->id) + 1 }} - {{ $d->nama }}</td>
                    @foreach($kriteria as $k)
                        <td>{{ number_format($result['matriksR'][$d->id][$k->id], 4) }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="page-break"></div>

    <!-- Kop Surat untuk Halaman 2 (Optional but professional) -->
    <div class="header-print text-center no-print">
        <h4 class="mb-0 fw-bold">PEMERINTAH KABUPATEN PESAWARAN</h4>
        <h3 class="mb-1 fw-bold">DINAS PARIWISATA</h3>
    </div>

    <!-- 3. Matriks Ternormalisasi Terbobot (V) -->
    <h5 class="fw-bold mb-2">3. Matriks Ternormalisasi Berbobot (V)</h5>
    <table class="table table-bordered table-print text-center align-middle mb-4">
        <thead class="table-light">
            <tr>
                <th>Alternatif</th>
                @foreach($kriteria as $k)
                    <th>{{ $k->kode }}<br><small>(W: {{ $result['bobotUsed'][$k->id] }})</small></th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            <!-- Baris Optimal A0 -->
            <tr class="fw-bold bg-light">
                <td>A0</td>
                @foreach($kriteria as $k)
                    <td>{{ number_format($result['matriksV']['A0'][$k->id], 4) }}</td>
                @endforeach
            </tr>
            <!-- Baris Destinasi -->
            @foreach($destinasi as $d)
                <tr>
                    <td class="text-start font-monospace">A{{ $destinasi->pluck('id')->indexOf($d->id) + 1 }} - {{ $d->nama }}</td>
                    @foreach($kriteria as $k)
                        <td>{{ number_format($result['matriksV'][$d->id][$k->id], 4) }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- 4. Hasil Akhir (S & K) & Perankingan -->
    <h5 class="fw-bold mb-2">4. Hasil Akhir & Perankingan</h5>
    <table class="table table-bordered table-print align-middle mb-5">
        <thead class="table-light text-center">
            <tr>
                <th width="10%">Rank</th>
                <th>Alternatif (Destinasi Wisata)</th>
                <th width="30%" class="text-center">Nilai S (Fungsi Optimasi)</th>
                <th width="30%" class="text-center">Nilai K (Derajat Utilitas)</th>
            </tr>
        </thead>
        <tbody>
            @php $rank = 1; @endphp
            @foreach($result['hasilSorted'] as $id => $nilaiK)
                @php $d = $destinasi->firstWhere('id', $id); @endphp
                <tr>
                    <td class="text-center fw-bold">#{{ $rank++ }}</td>
                    <td class="fw-bold">A{{ $destinasi->pluck('id')->indexOf($d->id) + 1 }} - {{ $d->nama }}</td>
                    <td class="text-center">{{ number_format($result['nilaiS'][$d->id], 4) }}</td>
                    <td class="text-center fw-bold text-primary">{{ number_format($nilaiK, 4) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Keterangan Singkat Kriteria -->
    <div class="mb-4 small">
        <h6 class="fw-bold mb-1">Catatan Kriteria:</h6>
        <div class="row">
            @foreach($kriteria as $k)
                <div class="col-md-4 col-6">
                    <strong>{{ $k->kode }}</strong>: {{ $k->nama_kriteria }} ({{ ucfirst($k->tipe) }})
                </div>
            @endforeach
        </div>
    </div>

    <!-- Tanda Tangan Pengesahan -->
    <div class="signature-area">
        <div class="signature-box">
            <p>Mengetahui,</p>
            <p class="fw-bold">Kepala Dinas Pariwisata</p>
            <div class="signature-space"></div>
            <p class="mb-0 text-decoration-underline fw-bold">Berry, S. Kom</p>
            <p class="text-muted small">NIM. 2271020089</p>
        </div>
        <div class="signature-box">
            <p>Pesawaran, {{ date('d F Y') }}</p>
            <p class="fw-bold">Petugas Pengelola SPK</p>
            <div class="signature-space"></div>
            <p class="mb-0 text-decoration-underline fw-bold">{{ auth()->user()->name }}</p>
            <p class="text-muted small">NIP. - / Admin Dinas</p>
        </div>
    </div>

</body>
</html>
