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
            <!-- C1: Aksesibilitas -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden card-hover-animation" style="border-top: 4px solid #4f46e5 !important;">
                    <div class="card-header bg-white border-0 pt-4 pb-0 d-flex align-items-center justify-content-between">
                        <h5 class="mb-0 fw-bold text-dark d-flex align-items-center">
                            <i class="bi bi-signpost-split-fill text-primary me-2 fs-5"></i>C1 - Aksesibilitas
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
                                        <td>Sangat Baik (Jalan aspal mulus & dekat jalan raya utama)</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center"><span class="badge bg-primary">4</span></td>
                                        <td>Baik (Jalan aspal baik & mudah dilalui mobil)</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center"><span class="badge bg-info">3</span></td>
                                        <td>Cukup (Jalan semen/berbatu, dapat dilalui)</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center"><span class="badge bg-warning text-dark">2</span></td>
                                        <td>Kurang (Jalan tanah/berlubang, sulit dilalui)</td>
                                    </tr>
                                    <tr class="table-danger-subtle">
                                        <td class="text-center"><span class="badge bg-danger">1</span></td>
                                        <td>Sangat Kurang (Jalan rusak parah / terjal)</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- C2: Fasilitas -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden card-hover-animation" style="border-top: 4px solid #0284c7 !important;">
                    <div class="card-header bg-white border-0 pt-4 pb-0 d-flex align-items-center justify-content-between">
                        <h5 class="mb-0 fw-bold text-dark d-flex align-items-center">
                            <i class="bi bi-water text-info me-2 fs-5"></i>C2 - Fasilitas
                        </h5>
                        <span class="badge bg-success-subtle text-success fw-bold px-2.5 py-1" style="font-size: 0.7rem;">Benefit</span>
                    </div>
                    <div class="card-body p-4">
                        <p class="text-muted small mb-3">Kelengkapan sarana pendukung (toilet, mushola, tempat bilas, gazebo, warung makan, parkir).</p>
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
                                        <td>Sangat Lengkap (Semua fasilitas utama & penunjang tersedia)</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center"><span class="badge bg-primary">4</span></td>
                                        <td>Lengkap (Toilet, mushola, area parkir, kantin tersedia)</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center"><span class="badge bg-info">3</span></td>
                                        <td>Cukup Lengkap (Toilet, parkir, warung makan tersedia)</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center"><span class="badge bg-warning text-dark">2</span></td>
                                        <td>Kurang Lengkap (Hanya toilet & area parkir darurat)</td>
                                    </tr>
                                    <tr class="table-danger-subtle">
                                        <td class="text-center"><span class="badge bg-danger">1</span></td>
                                        <td>Tidak Lengkap (Hampir tidak ada fasilitas)</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- C3: Kebersihan -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden card-hover-animation" style="border-top: 4px solid #059669 !important;">
                    <div class="card-header bg-white border-0 pt-4 pb-0 d-flex align-items-center justify-content-between">
                        <h5 class="mb-0 fw-bold text-dark d-flex align-items-center">
                            <i class="bi bi-trash3-fill text-success me-2 fs-5"></i>C3 - Kebersihan
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
                                        <td>Sangat Bersih (Bebas sampah plastik, air jernih, asri)</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center"><span class="badge bg-primary">4</span></td>
                                        <td>Bersih (Petugas aktif, tempat sampah memadai)</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center"><span class="badge bg-info">3</span></td>
                                        <td>Cukup (Ada sedikit sampah alami/daun, air cukup jernih)</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center"><span class="badge bg-warning text-dark">2</span></td>
                                        <td>Kurang Bersih (Sampah plastik terlihat menumpuk)</td>
                                    </tr>
                                    <tr class="table-danger-subtle">
                                        <td class="text-center"><span class="badge bg-danger">1</span></td>
                                        <td>Sangat Kotor (Sampah berserakan & air tercemar)</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- C4: Keamanan -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden card-hover-animation" style="border-top: 4px solid #b91c1c !important;">
                    <div class="card-header bg-white border-0 pt-4 pb-0 d-flex align-items-center justify-content-between">
                        <h5 class="mb-0 fw-bold text-dark d-flex align-items-center">
                            <i class="bi bi-shield-lock-fill text-danger me-2 fs-5"></i>C4 - Keamanan
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
                                        <td>Sangat Aman (Penjaga pantai bersertifikat & pos medis aktif)</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center"><span class="badge bg-primary">4</span></td>
                                        <td>Aman (Pengelola lokal siaga & parkir terpantau)</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center"><span class="badge bg-info">3</span></td>
                                        <td>Cukup Aman (Kerawanan rendah, pengawasan swadaya)</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center"><span class="badge bg-warning text-dark">2</span></td>
                                        <td>Kurang Aman (Minim rambu bahaya & pengawasan)</td>
                                    </tr>
                                    <tr class="table-danger-subtle">
                                        <td class="text-center"><span class="badge bg-danger">1</span></td>
                                        <td>Sangat Rawan (Sering terjadi kehilangan/tanpa pengawas)</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- C5: Harga Tiket -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden card-hover-animation" style="border: 2px solid #eab308 !important; border-top: 4px solid #eab308 !important;">
                    <div class="card-header bg-white border-0 pt-4 pb-0 d-flex align-items-center justify-content-between">
                        <h5 class="mb-0 fw-bold text-dark d-flex align-items-center">
                            <i class="bi bi-cash-stack text-warning me-2 fs-5"></i>C5 - Harga Tiket
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
                                        <td>x < 3.000</td>
                                        <td>Sangat Murah</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center"><span class="badge bg-primary">4</span></td>
                                        <td>3.000 s/d &le; 5.000</td>
                                        <td>Murah</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center"><span class="badge bg-info">3</span></td>
                                        <td>> 5.000 s/d &le; 10.000</td>
                                        <td>Cukup Murah</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center"><span class="badge bg-warning text-dark">2</span></td>
                                        <td>> 10.000 s/d &le; 15.000</td>
                                        <td>Mahal</td>
                                    </tr>
                                    <tr class="table-danger-subtle">
                                        <td class="text-center"><span class="badge bg-danger">1</span></td>
                                        <td>x &ge; 15.000</td>
                                        <td>Sangat Mahal</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- C6: Jumlah Pengunjung -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden card-hover-animation" style="border-top: 4px solid #4b5563 !important;">
                    <div class="card-header bg-white border-0 pt-4 pb-0 d-flex align-items-center justify-content-between">
                        <h5 class="mb-0 fw-bold text-dark d-flex align-items-center">
                            <i class="bi bi-people-fill text-secondary me-2 fs-5"></i>C6 - Jumlah Pengunjung
                        </h5>
                        <span class="badge bg-success-subtle text-success fw-bold px-2.5 py-1" style="font-size: 0.7rem;">Benefit</span>
                    </div>
                    <div class="card-body p-4">
                        <p class="text-muted small mb-3">Tingkat kepadatan atau jumlah pengunjung wisata air (indeks popularitas & daya tarik wisata).</p>
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
                                        <td>Sangat Ramai (Destinasi sangat populer & padat)</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center"><span class="badge bg-primary">4</span></td>
                                        <td>Ramai (Destinasi populer & ramai pada akhir pekan)</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center"><span class="badge bg-info">3</span></td>
                                        <td>Cukup Ramai (Pengunjung stabil, cukup populer)</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center"><span class="badge bg-warning text-dark">2</span></td>
                                        <td>Sepi (Pengunjung musiman / jarang dikunjungi)</td>
                                    </tr>
                                    <tr class="table-danger-subtle">
                                        <td class="text-center"><span class="badge bg-danger">1</span></td>
                                        <td>Sangat Sepi (Hampir tidak ada pengunjung)</td>
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
