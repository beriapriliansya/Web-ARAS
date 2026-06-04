<x-app-layout>
    <div class="container py-5">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.bookings.index') }}">Manajemen Pemesanan</a></li>
                <li class="breadcrumb-item active" aria-current="page">Detail Booking #{{ $booking->kode_booking }}</li>
            </ol>
        </nav>

        <div class="row g-4">
            <!-- Kolom Kiri: Rincian Pemesanan -->
            <div class="col-lg-7">
                <div class="card shadow border-0 rounded-4 overflow-hidden mb-4">
                    <div class="card-header bg-dark text-white py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold"><i class="bi bi-receipt me-2 text-warning"></i>Informasi Transaksi</h5>
                        <span class="badge bg-warning text-dark font-monospace">{{ $booking->kode_booking }}</span>
                    </div>
                    <div class="card-body p-4">
                        <h6 class="fw-bold text-secondary mb-3 border-bottom pb-2">Rincian Pemesan & Kunjungan</h6>
                        <table class="table table-borderless align-middle">
                            <tr>
                                <td class="text-muted" width="35%">Nama Pemesan</td>
                                <td class="fw-bold text-dark">: {{ $booking->user->name }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Email Pemesan</td>
                                <td>: {{ $booking->user->email }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Destinasi Wisata</td>
                                <td class="fw-bold text-primary">: {{ $booking->destinasi->nama }} ({{ $booking->destinasi->kategori }})</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Tanggal Kunjungan</td>
                                <td class="fw-bold text-dark">: {{ $booking->tanggal_kunjungan->format('d F Y') }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Jumlah Tiket</td>
                                <td class="fw-bold">: {{ $booking->jumlah_tiket }} Orang</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Harga Tiket per Orang</td>
                                <td>: Rp {{ number_format($booking->destinasi->harga_tiket, 0, ',', '.') }}</td>
                            </tr>
                            <tr class="border-top">
                                <td class="fw-bold text-dark pt-3">Total Harus Dibayar</td>
                                <td class="fw-bold text-success fs-4 pt-3">: Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Status Transaksi</td>
                                <td class="pt-2">
                                    : 
                                    @if($booking->status == 'pending')
                                        <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">Pending (Menunggu Bayar)</span>
                                    @elseif($booking->status == 'menunggu_konfirmasi')
                                        <span class="badge bg-info text-dark px-3 py-2 rounded-pill">Menunggu Konfirmasi Admin</span>
                                    @elseif($booking->status == 'confirmed')
                                        <span class="badge bg-primary px-3 py-2 rounded-pill">Confirmed</span>
                                    @elseif($booking->status == 'completed')
                                        <span class="badge bg-success px-3 py-2 rounded-pill">Completed (Selesai/Lunas)</span>
                                    @elseif($booking->status == 'cancelled')
                                        <span class="badge bg-danger px-3 py-2 rounded-pill">Cancelled (Dibatalkan)</span>
                                    @endif
                                </td>
                            </tr>
                        </table>

                        @if($booking->status == 'menunggu_konfirmasi')
                            <div class="mt-4 pt-3 border-top d-flex gap-2">
                                <form action="{{ route('admin.bookings.complete', $booking->id) }}" method="POST" onsubmit="return confirm('Konfirmasi pembayaran dan selesaikan pesanan ini?');" class="flex-grow-1 m-0">
                                    @csrf
                                    <button type="submit" class="btn btn-success w-100 py-3 fw-bold rounded-3 shadow-sm">
                                        <i class="bi bi-check-circle-fill me-2"></i> Konfirmasi & Selesaikan Booking
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Bukti Pembayaran -->
            <div class="col-lg-5">
                <div class="card shadow border-0 rounded-4 overflow-hidden">
                    <div class="card-header bg-light py-3 border-bottom">
                        <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-image me-2 text-primary"></i>Bukti Transfer Pembayaran</h6>
                    </div>
                    <div class="card-body p-4 text-center">
                        @if($booking->bukti_pembayaran)
                            <div class="border rounded p-2 bg-light mb-3 shadow-sm">
                                <img src="{{ asset('storage/' . $booking->bukti_pembayaran) }}" class="img-fluid rounded" alt="Bukti Transfer">
                            </div>
                            <a href="{{ asset('storage/' . $booking->bukti_pembayaran) }}" target="_blank" class="btn btn-outline-primary btn-sm w-100">
                                <i class="bi bi-box-arrow-up-right me-1"></i> Buka Gambar Ukuran Penuh
                            </a>
                        @else
                            <div class="py-5 text-muted">
                                <i class="bi bi-image-alt" style="font-size: 4rem;"></i>
                                <p class="mt-3 mb-0 small">Belum ada bukti pembayaran yang diupload oleh pengunjung.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
