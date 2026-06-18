<x-app-layout>
    <div class="container py-5">
        
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.aras.index') }}">Perhitungan ARAS</a></li>
                <li class="breadcrumb-item active" aria-current="page">Penilaian Objek Wisata</li>
            </ol>
        </nav>

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

        <!-- LANGKAH 1: Pilih Objek Wisata -->
        <div class="card shadow border-0 rounded-4 overflow-hidden mb-4">
            <div class="card-header bg-primary text-white py-3">
                <h6 class="mb-0 fw-bold"><i class="bi bi-check2-square me-2"></i>Langkah 1: Pilih Objek Wisata</h6>
            </div>
            <div class="card-body p-4">
                <label class="form-label fw-bold text-secondary mb-2">Pilih wisata yang akan dinilai:</label>
                <form id="destinasiSelectForm" action="{{ route('admin.aras.penilaian') }}" method="GET" class="m-0">
                    <select name="destinasi_id" class="form-select form-select-lg" onchange="document.getElementById('destinasiSelectForm').submit()">
                        <option value="" selected disabled>-- Pilih Objek Wisata --</option>
                        @foreach($destinasi as $d)
                            <option value="{{ $d->id }}" {{ $selectedDestinasiId == $d->id ? 'selected' : '' }}>
                                {{ $d->nama }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>

        <!-- LANGKAH 2: Beri Penilaian -->
        @if($selectedDestinasi)
            <div class="card shadow border-0 rounded-4 overflow-hidden">
                <div class="card-header bg-primary text-white py-3">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-pencil-square me-2"></i>Langkah 2: Beri Penilaian ({{ $selectedDestinasi->nama }})</h6>
                </div>
                <form action="{{ route('admin.aras.penilaian.store') }}" method="POST" class="m-0" onsubmit="return confirm('Apakah Anda yakin ingin menyimpan penilaian objek wisata ini?');">
                    @csrf
                    <input type="hidden" name="destinasi_id" value="{{ $selectedDestinasi->id }}">

                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" width="8%">No</th>
                                        <th class="text-start" width="42%">Kriteria</th>
                                        <th class="text-start" width="50%">Nilai Kriteria</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($kriteria as $index => $k)
                                        <tr>
                                            <td class="text-center text-secondary">{{ $index + 1 }}</td>
                                            <td class="text-start">
                                                <span class="fw-bold text-dark">{{ $k->nama_kriteria }}</span>
                                                <span class="badge {{ $k->tipe == 'benefit' ? 'bg-success' : 'bg-danger' }} ms-2" style="font-size: 0.65rem;">
                                                    {{ ucfirst($k->tipe) }}
                                                </span>
                                                <div class="small text-muted">{{ $k->keterangan }}</div>
                                            </td>
                                            <td class="text-start">
                                                @php
                                                    $existingNilai = isset($alternatifValues[$k->id]) ? (float)$alternatifValues[$k->id] : null;
                                                    
                                                    // Map kriteria to guidelines
                                                    $options = [];
                                                    if ($k->kode == 'C1') {
                                                        $options = [
                                                            5 => 'Sangat Baik (Jalan aspal mulus & dekat jalan raya utama)',
                                                            4 => 'Baik (Jalan aspal baik & mudah dilalui mobil)',
                                                            3 => 'Cukup (Jalan semen/berbatu, dapat dilalui)',
                                                            2 => 'Kurang (Jalan tanah/berlubang, sulit dilalui)',
                                                            1 => 'Sangat Kurang (Jalan rusak parah / terjal)',
                                                        ];
                                                    } elseif ($k->kode == 'C2') {
                                                        $options = [
                                                            5 => 'Sangat Lengkap (Semua fasilitas utama & penunjang tersedia)',
                                                            4 => 'Lengkap (Toilet, mushola, area parkir, kantin tersedia)',
                                                            3 => 'Cukup Lengkap (Toilet, parkir, warung makan tersedia)',
                                                            2 => 'Kurang Lengkap (Hanya toilet & area parkir darurat)',
                                                            1 => 'Tidak Lengkap (Hampir tidak ada fasilitas)',
                                                        ];
                                                    } elseif ($k->kode == 'C3') {
                                                        $options = [
                                                            5 => 'Sangat Bersih (Bebas sampah plastik, air jernih, asri)',
                                                            4 => 'Bersih (Petugas aktif, tempat sampah memadai)',
                                                            3 => 'Cukup (Ada sedikit sampah alami/daun, air cukup jernih)',
                                                            2 => 'Kurang Bersih (Sampah plastik terlihat menumpuk)',
                                                            1 => 'Sangat Kotor (Sampah berserakan & air tercemar)',
                                                        ];
                                                    } elseif ($k->kode == 'C4') {
                                                        $options = [
                                                            5 => 'Sangat Aman (Penjaga pantai bersertifikat & pos medis aktif)',
                                                            4 => 'Aman (Pengelola lokal siaga & parkir terpantau)',
                                                            3 => 'Cukup Aman (Kerawanan rendah, pengawasan swadaya)',
                                                            2 => 'Kurang Aman (Minim rambu bahaya & pengawasan)',
                                                            1 => 'Sangat Rawan (Sering terjadi kehilangan/tanpa pengawas)',
                                                        ];
                                                    } elseif ($k->kode == 'C5') {
                                                        $options = [
                                                            1 => 'Sangat Murah (Tiket < Rp 3.000)',
                                                            2 => 'Murah (Rp 3.000 s/d <= Rp 5.000)',
                                                            3 => 'Cukup Murah (> Rp 5.000 s/d <= Rp 10.000)',
                                                            4 => 'Mahal (> Rp 10.000 s/d <= Rp 15.000)',
                                                            5 => 'Sangat Mahal (>= Rp 15.000)',
                                                        ];
                                                    } elseif ($k->kode == 'C6') {
                                                        $options = [
                                                            5 => 'Sangat Ramai (Destinasi sangat populer & padat)',
                                                            4 => 'Ramai (Destinasi populer & ramai pada akhir pekan)',
                                                            3 => 'Cukup Ramai (Pengunjung stabil, cukup populer)',
                                                            2 => 'Sepi (Pengunjung musiman / jarang dikunjungi)',
                                                            1 => 'Sangat Sepi (Hampir tidak ada pengunjung)',
                                                        ];
                                                    }
                                                @endphp

                                                @if(empty($options))
                                                    <div class="input-group">
                                                        @if($k->satuan == 'Rp')
                                                            <span class="input-group-text">Rp</span>
                                                        @endif
                                                        
                                                        <input type="number" step="any" name="nilai_{{ $k->id }}" class="form-control fw-semibold" 
                                                               value="{{ $existingNilai }}" required 
                                                               placeholder="Masukkan nilai {{ strtolower($k->nama_kriteria) }}">
                                                        
                                                        @if($k->satuan && $k->satuan != 'Rp')
                                                            <span class="input-group-text">{{ $k->satuan }}</span>
                                                        @endif
                                                    </div>
                                                @else
                                                    <select name="nilai_{{ $k->id }}" class="form-select fw-semibold" required>
                                                        <option value="" disabled {{ $existingNilai === null ? 'selected' : '' }}>-- Pilih Deskripsi Nilai --</option>
                                                        @foreach($options as $skor => $desc)
                                                            <option value="{{ $skor }}" {{ ($existingNilai !== null && abs((float)$existingNilai - $skor) < 0.001) ? 'selected' : '' }}>
                                                                Skor {{ $skor }} : {{ $desc }}
                                                            </option>
                                                        @endforeach
                                                        @if($existingNilai !== null)
                                                            @php
                                                                $matched = false;
                                                                foreach(array_keys($options) as $skor) {
                                                                    if(abs((float)$existingNilai - $skor) < 0.001) {
                                                                        $matched = true;
                                                                        break;
                                                                    }
                                                                }
                                                            @endphp
                                                            @if(!$matched)
                                                                <option value="{{ $existingNilai }}" selected>
                                                                    Skor {{ $existingNilai }} : Nilai Kustom Sebelumnya
                                                                </option>
                                                            @endif
                                                        @endif
                                                    </select>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div class="card-footer bg-white py-3 d-flex justify-content-end border-top">
                        <button type="submit" class="btn btn-primary px-4 fw-bold">
                            <i class="bi bi-save me-1"></i> Simpan Penilaian
                        </button>
                    </div>
                </form>
            </div>
        @else
            <!-- Info Alert: Please select a destination -->
            <div class="card border-0 shadow rounded-4 overflow-hidden">
                <div class="card-body p-5 text-center text-muted">
                    <i class="bi bi-card-list fs-1 text-primary mb-3"></i>
                    <h5 class="fw-semibold text-dark">Silakan pilih objek wisata pada Langkah 1 untuk memulai proses penilaian.</h5>
                    <p class="small text-secondary mb-0">Nilai kriteria yang Anda berikan akan digunakan sebagai basis data alternatif pada kalkulasi Metode ARAS.</p>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
