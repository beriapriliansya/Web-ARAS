<x-app-layout>
    <div class="container py-5">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.aras.index') }}">Perhitungan ARAS</a></li>
                <li class="breadcrumb-item active" aria-current="page">Panduan Konversi</li>
            </ol>
        </nav>

        <!-- Header Section -->
        <div class="text-center mb-5">
            <h1 class="display-5 fw-bold text-dark mb-3">📘 Panduan Konversi Kriteria</h1>
            <p class="lead text-secondary max-w-2xl mx-auto" style="max-width: 750px; margin: 0 auto;">
                Untuk memproses kalkulasi dengan Metode ARAS, data penilaian dari setiap destinasi wisata wajib dikonversi ke dalam skala numerik/skor (1 s/d 5). Gunakan tabel panduan di bawah ini saat memberikan penilaian alternatif.
            </p>
        </div>

        <!-- Conversion Cards Grid -->
        <div class="row g-4">
            <!-- C1: Harga Tiket -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden card-hover-animation" style="border-top: 4px solid #eab308 !important;">
                    <div class="card-header bg-white border-0 pt-4 pb-0 d-flex align-items-center justify-content-between">
                        <h5 class="mb-0 fw-bold text-dark d-flex align-items-center">
                            <i class="bi bi-cash-stack text-warning me-2 fs-5"></i>C1 - Harga Tiket
                        </h5>
                        <span class="badge bg-danger-subtle text-danger fw-bold px-2.5 py-1" style="font-size: 0.7rem;">Cost</span>
                    </div>
                    <div class="card-body p-4">
                        <p class="text-muted small mb-3">Besaran biaya/harga tiket masuk tempat wisata air. <strong>Kriteria Cost (Nilai konversi terbalik)</strong>.</p>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover align-middle mb-0" style="font-size: 0.85rem;">
                                <thead class="table-light text-secondary">
                                    <tr>
                                        <th>Skor</th>
                                        <th>Konversi Harga (Rp)</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="table-success-subtle fw-semibold">
                                        <td class="text-center"><span class="badge bg-success">5</span></td>
                                        <td>&le; 15.000</td>
                                        <td>Sangat Murah</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center"><span class="badge bg-primary">4</span></td>
                                        <td>16.000 - 30.000</td>
                                        <td>Murah</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center"><span class="badge bg-info">3</span></td>
                                        <td>31.000 - 45.000</td>
                                        <td>Cukup Murah</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center"><span class="badge bg-warning text-dark">2</span></td>
                                        <td>46.000 - 60.000</td>
                                        <td>Mahal</td>
                                    </tr>
                                    <tr class="table-danger-subtle">
                                        <td class="text-center"><span class="badge bg-danger">1</span></td>
                                        <td>&gt; 60.000</td>
                                        <td>Sangat Mahal</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- C2: Aksesibilitas -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden card-hover-animation" style="border-top: 4px solid #4f46e5 !important;">
                    <div class="card-header bg-white border-0 pt-4 pb-0 d-flex align-items-center justify-content-between">
                        <h5 class="mb-0 fw-bold text-dark d-flex align-items-center">
                            <i class="bi bi-signpost-split-fill text-primary me-2 fs-5"></i>C2 - Aksesibilitas
                        </h5>
                        <span class="badge bg-success-subtle text-success fw-bold px-2.5 py-1" style="font-size: 0.7rem;">Benefit</span>
                    </div>
                    <div class="card-body p-4">
                        <p class="text-muted small mb-3">Kemudahan akses jalan menuju lokasi wisata air (kondisi jalan, kemudahan dilalui kendaraan).</p>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover align-middle mb-0" style="font-size: 0.85rem;">
                                <thead class="table-light text-secondary">
                                    <tr>
                                        <th>Skor</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="table-success-subtle fw-semibold">
                                        <td class="text-center"><span class="badge bg-success">5</span></td>
                                        <td>Sangat Baik (Aspal/beton mulus, dekat jalan utama, bisa dilalui motor, mobil, dan bus)</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center"><span class="badge bg-primary">4</span></td>
                                        <td>Baik (Aspal/semen rata, agak jauh dari jalan utama, mudah dilalui mobil)</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center"><span class="badge bg-info">3</span></td>
                                        <td>Cukup Baik (Semen sebagian/berbatu, jalan pas-pasan, harus hati-hati jika pakai mobil)</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center"><span class="badge bg-warning text-dark">2</span></td>
                                        <td>Kurang Baik (Jalan tanah/berlubang parah dan sempit, hanya optimal untuk sepeda motor)</td>
                                    </tr>
                                    <tr class="table-danger-subtle">
                                        <td class="text-center"><span class="badge bg-danger">1</span></td>
                                        <td>Sangat Kurang (Jalan rusak parah atau batuan terjal, berbahaya, hanya bisa jalan kaki)</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- C3: Fasilitas Pendukung -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden card-hover-animation" style="border-top: 4px solid #0284c7 !important;">
                    <div class="card-header bg-white border-0 pt-4 pb-0 d-flex align-items-center justify-content-between">
                        <h5 class="mb-0 fw-bold text-dark d-flex align-items-center">
                            <i class="bi bi-tools text-info me-2 fs-5"></i>C3 - Fasilitas Pendukung
                        </h5>
                        <span class="badge bg-success-subtle text-success fw-bold px-2.5 py-1" style="font-size: 0.7rem;">Benefit</span>
                    </div>
                    <div class="card-body p-4">
                        <p class="text-muted small mb-3">Penilaian berdasarkan total item checklist fasilitas yang terpenuhi.</p>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover align-middle mb-0" style="font-size: 0.85rem;">
                                <thead class="table-light text-secondary">
                                    <tr>
                                        <th>Skor</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="table-success-subtle fw-semibold">
                                        <td class="text-center"><span class="badge bg-success">5</span></td>
                                        <td>Sangat Lengkap (Memiliki 10 - 12 fasilitas)</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center"><span class="badge bg-primary">4</span></td>
                                        <td>Lengkap (Memiliki 7 - 9 fasilitas)</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center"><span class="badge bg-info">3</span></td>
                                        <td>Cukup Lengkap (Memiliki 4 - 6 fasilitas)</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center"><span class="badge bg-warning text-dark">2</span></td>
                                        <td>Kurang Lengkap (Memiliki 1 - 3 fasilitas)</td>
                                    </tr>
                                    <tr class="table-danger-subtle">
                                        <td class="text-center"><span class="badge bg-danger">1</span></td>
                                        <td>Tidak Ada Fasilitas (0 fasilitas)</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- C4: Kebersihan Lingkungan -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden card-hover-animation" style="border-top: 4px solid #059669 !important;">
                    <div class="card-header bg-white border-0 pt-4 pb-0 d-flex align-items-center justify-content-between">
                        <h5 class="mb-0 fw-bold text-dark d-flex align-items-center">
                            <i class="bi bi-trash3-fill text-success me-2 fs-5"></i>C4 - Kebersihan Lingkungan
                        </h5>
                        <span class="badge bg-success-subtle text-success fw-bold px-2.5 py-1" style="font-size: 0.7rem;">Benefit</span>
                    </div>
                    <div class="card-body p-4">
                        <p class="text-muted small mb-3">Tingkat kebersihan area wisata, kejernihan air, ketersediaan tempat sampah, & pengelolaan sampah.</p>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover align-middle mb-0" style="font-size: 0.85rem;">
                                <thead class="table-light text-secondary">
                                    <tr>
                                        <th>Skor</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="table-success-subtle fw-semibold">
                                        <td class="text-center"><span class="badge bg-success">5</span></td>
                                        <td>Sangat Bersih</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center"><span class="badge bg-primary">4</span></td>
                                        <td>Bersih</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center"><span class="badge bg-info">3</span></td>
                                        <td>Cukup Bersih</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center"><span class="badge bg-warning text-dark">2</span></td>
                                        <td>Kurang Bersih</td>
                                    </tr>
                                    <tr class="table-danger-subtle">
                                        <td class="text-center"><span class="badge bg-danger">1</span></td>
                                        <td>Sangat Kotor</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- C5: Keamanan Lokasi -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden card-hover-animation" style="border-top: 4px solid #b91c1c !important;">
                    <div class="card-header bg-white border-0 pt-4 pb-0 d-flex align-items-center justify-content-between">
                        <h5 class="mb-0 fw-bold text-dark d-flex align-items-center">
                            <i class="bi bi-shield-lock-fill text-danger me-2 fs-5"></i>C5 - Keamanan Lokasi
                        </h5>
                        <span class="badge bg-success-subtle text-success fw-bold px-2.5 py-1" style="font-size: 0.7rem;">Benefit</span>
                    </div>
                    <div class="card-body p-4">
                        <p class="text-muted small mb-3">Tingkat jaminan keselamatan pengunjung (tersedianya lifeguard/penjaga pantai, pos medis, parkir aman).</p>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover align-middle mb-0" style="font-size: 0.85rem;">
                                <thead class="table-light text-secondary">
                                    <tr>
                                        <th>Skor</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="table-success-subtle fw-semibold">
                                        <td class="text-center"><span class="badge bg-success">5</span></td>
                                        <td>Sangat Aman (Ada lifeguard, pos keamanan, CCTV/penjaga aktif)</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center"><span class="badge bg-primary">4</span></td>
                                        <td>Aman (Ada pos keamanan resmi dan petugas parkir)</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center"><span class="badge bg-info">3</span></td>
                                        <td>Cukup Aman (Keamanan swadaya standar)</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center"><span class="badge bg-warning text-dark">2</span></td>
                                        <td>Kurang Aman (Rawan kehilangan/minim penerangan)</td>
                                    </tr>
                                    <tr class="table-danger-subtle">
                                        <td class="text-center"><span class="badge bg-danger">1</span></td>
                                        <td>Sangat Rawan/Tidak Aman</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- C6: Daya Tarik / Keindahan Wisata -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden card-hover-animation" style="border-top: 4px solid #ffc107 !important;">
                    <div class="card-header bg-white border-0 pt-4 pb-0 d-flex align-items-center justify-content-between">
                        <h5 class="mb-0 fw-bold text-dark d-flex align-items-center">
                            <i class="bi bi-star-fill text-warning me-2 fs-5"></i>C6 - Daya Tarik / Keindahan Wisata
                        </h5>
                        <span class="badge bg-success-subtle text-success fw-bold px-2.5 py-1" style="font-size: 0.7rem;">Benefit</span>
                    </div>
                    <div class="card-body p-4">
                        <p class="text-muted small mb-3">Tingkat keindahan, keunikan, dan daya tarik utama dari destinasi wisata air.</p>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover align-middle mb-0" style="font-size: 0.85rem;">
                                <thead class="table-light text-secondary">
                                    <tr>
                                        <th>Skor</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="table-success-subtle fw-semibold">
                                        <td class="text-center"><span class="badge bg-success">5</span></td>
                                        <td>Sangat Menarik / Sangat Indah (Memiliki banyak keunikan alam/spot utama yang viral)</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center"><span class="badge bg-primary">4</span></td>
                                        <td>Menarik / Indah (Pemandangan bagus dan terkelola dengan baik)</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center"><span class="badge bg-info">3</span></td>
                                        <td>Cukup Menarik (Standar pemandangan alam biasa)</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center"><span class="badge bg-warning text-dark">2</span></td>
                                        <td>Kurang Menarik (Gersang atau kurang terawat)</td>
                                    </tr>
                                    <tr class="table-danger-subtle">
                                        <td class="text-center"><span class="badge bg-danger">1</span></td>
                                        <td>Tidak Menarik</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Inline Styles for Animation -->
    <style>
        .card-hover-animation {
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }
        .card-hover-animation:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.1) !important;
        }
    </style>
</x-app-layout>
