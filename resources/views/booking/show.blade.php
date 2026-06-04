<x-app-layout>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('booking.index') }}">Booking Saya</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Detail Booking</li>
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

                <div class="card shadow border-0 rounded-4 overflow-hidden mb-4">
                    <div class="card-header bg-dark text-white py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold"><i class="bi bi-receipt me-2 text-warning"></i>Detail Pemesanan Tiket</h5>
                        <span class="badge bg-warning text-dark font-monospace">{{ $booking->kode_booking }}</span>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <!-- Info Destinasi -->
                            <div class="col-md-6 border-end">
                                <h6 class="fw-bold text-secondary mb-3">Informasi Wisata</h6>
                                <div class="d-flex align-items-center mb-3">
                                    <div class="me-3" style="width: 60px; height: 60px; overflow: hidden; border-radius: 8px;">
                                        @if($booking->destinasi->foto)
                                            <img src="{{ asset('images/destinasi/' . $booking->destinasi->foto) }}" class="w-100 h-100 object-cover">
                                        @else
                                            <div class="bg-secondary w-100 h-100 d-flex align-items-center justify-content-center text-white">
                                                <i class="bi bi-image"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <h5 class="fw-bold mb-0 text-dark">{{ $booking->destinasi->nama }}</h5>
                                        <span class="badge bg-primary text-xs">{{ $booking->destinasi->kategori }}</span>
                                    </div>
                                </div>
                                <p class="mb-1 text-muted"><i class="bi bi-geo-alt-fill text-danger me-1"></i> {{ $booking->destinasi->alamat }}</p>
                                <p class="mb-0 small text-muted"><i class="bi bi-clock me-1"></i> Jam Operasional: {{ $booking->destinasi->jam_buka ? $booking->destinasi->jam_buka->format('H:i') : '08:00' }} - {{ $booking->destinasi->jam_tutup ? $booking->destinasi->jam_tutup->format('H:i') : '17:00' }} WIB</p>
                            </div>

                            <!-- Detail Booking -->
                            <div class="col-md-6">
                                <h6 class="fw-bold text-secondary mb-3">Informasi Pemesanan</h6>
                                <div class="table-responsive">
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <td class="text-muted" width="40%">Status</td>
                                            <td class="fw-bold">
                                                @if($booking->status == 'pending')
                                                    <span class="text-warning"><i class="bi bi-hourglass-split"></i> Pending</span>
                                                @elseif($booking->status == 'menunggu_konfirmasi')
                                                    <span class="text-info"><i class="bi bi-file-earmark-arrow-up"></i> Menunggu Konfirmasi</span>
                                                @elseif($booking->status == 'confirmed')
                                                    <span class="text-primary"><i class="bi bi-check-circle-fill"></i> Terkonfirmasi</span>
                                                @elseif($booking->status == 'completed')
                                                    <span class="text-success"><i class="bi bi-check-all"></i> Selesai Dikunjungi</span>
                                                @elseif($booking->status == 'cancelled')
                                                    <span class="text-danger"><i class="bi bi-x-circle-fill"></i> Dibatalkan</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Tanggal Kunjungan</td>
                                            <td class="fw-bold text-dark">{{ $booking->tanggal_kunjungan->format('d F Y') }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Jumlah Tiket</td>
                                            <td class="fw-bold text-dark">{{ $booking->jumlah_tiket }} Orang</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Harga per Tiket</td>
                                            <td class="fw-semibold">Rp {{ number_format($booking->destinasi->harga_tiket, 0, ',', '.') }}</td>
                                        </tr>
                                        <tr class="border-top">
                                            <td class="fw-bold text-dark pt-2">Total Bayar</td>
                                            <td class="fw-bold text-success fs-5 pt-2">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bagian Pembayaran -->
                @if($booking->status == 'pending' || $booking->status == 'menunggu_konfirmasi')
                    <div class="row g-4">
                        <!-- Instruksi Transfer Bank -->
                        <div class="col-md-6">
                            <div class="card shadow border-0 rounded-4 h-100">
                                <div class="card-header bg-light py-3">
                                    <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-bank me-2 text-primary"></i>Instruksi Pembayaran</h6>
                                </div>
                                <div class="card-body">
                                    <p class="small text-secondary mb-3">Silakan transfer pembayaran tiket Anda ke rekening bank berikut:</p>
                                    <div class="p-3 bg-light rounded border mb-3">
                                        <div class="fw-bold text-dark mb-1">BANK MANDIRI (Dinas Pariwisata)</div>
                                        <div class="fs-5 fw-bold font-monospace text-primary mb-1">114-00-123456-78</div>
                                        <small class="text-muted">Atas Nama: BAPPENDA PARIWISATA PESAWARAN</small>
                                    </div>
                                    <div class="alert alert-warning py-2 mb-0 small">
                                        <i class="bi bi-info-circle-fill"></i> Pastikan nominal transfer persis <strong>Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</strong>.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Upload Bukti Pembayaran -->
                        <div class="col-md-6">
                            <div class="card shadow border-0 rounded-4 h-100">
                                <div class="card-header bg-light py-3">
                                    <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-cloud-upload me-2 text-primary"></i>Bukti Pembayaran</h6>
                                </div>
                                <div class="card-body">
                                    @if($booking->bukti_pembayaran)
                                        <div class="text-center py-2">
                                            <p class="text-success small mb-2"><i class="bi bi-check-circle"></i> Bukti Pembayaran Telah Diupload</p>
                                            <div class="border rounded p-2 bg-light mb-3" style="max-height: 150px; overflow: hidden;">
                                                <img src="{{ asset('storage/' . $booking->bukti_pembayaran) }}" class="img-fluid rounded" style="max-height: 130px;">
                                            </div>
                                            @if($booking->status == 'menunggu_konfirmasi')
                                                <p class="small text-muted mb-0">Menunggu admin memverifikasi pembayaran Anda.</p>
                                            @endif
                                        </div>
                                    @endif

                                    @if($booking->status == 'pending')
                                        <form action="{{ route('booking.upload_payment', $booking->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="mb-3">
                                                <label for="bukti_bayar" class="form-label small text-secondary">Upload file gambar bukti transfer (JPG, JPEG, PNG, max 2MB):</label>
                                                <input class="form-control" type="file" name="bukti_bayar" id="bukti_bayar" accept="image/*" required>
                                                @error('bukti_bayar')
                                                    <span class="text-danger small">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <button type="submit" class="btn btn-primary w-100 fw-bold">
                                                <i class="bi bi-upload"></i> Upload Bukti
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif($booking->status == 'confirmed' || $booking->status == 'completed')
                    <div class="card shadow border-0 rounded-4 bg-light text-center py-4 mb-4">
                        <div class="card-body">
                            <i class="bi bi-check-circle-fill text-success" style="font-size: 3rem;"></i>
                            <h4 class="fw-bold text-dark mt-2">Pemesanan Terverifikasi</h4>
                            <p class="text-secondary mb-3">Pembayaran Anda telah diverifikasi oleh pengelola pariwisata. Tunjukkan kode booking atau cetak bukti ini saat tiba di pintu masuk destinasi.</p>
                            @if($booking->bukti_pembayaran)
                                <a href="{{ asset('storage/' . $booking->bukti_pembayaran) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-image"></i> Lihat Bukti Pembayaran Anda
                                </a>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
