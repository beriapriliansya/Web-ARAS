<x-app-layout>
    <div class="container py-5">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Manajemen Pemesanan Tiket</li>
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

        <div class="card shadow border-0 rounded-4">
            <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold"><i class="bi bi-ticket-detailed me-2"></i>Daftar Pemesanan Tiket Pengunjung</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Kode Booking</th>
                                <th>Pengunjung</th>
                                <th>Destinasi</th>
                                <th class="text-center">Tgl Kunjungan</th>
                                <th class="text-center">Tiket</th>
                                <th class="text-end">Total Harga</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Pembayaran</th>
                                <th class="text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bookings as $booking)
                                <tr>
                                    <td class="ps-4 fw-bold font-monospace text-secondary">{{ $booking->kode_booking }}</td>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ $booking->user->name }}</div>
                                        <small class="text-muted">{{ $booking->user->email }}</small>
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ $booking->destinasi->nama }}</div>
                                        <small class="text-muted">{{ $booking->destinasi->kategori }}</small>
                                    </td>
                                    <td class="text-center">{{ $booking->tanggal_kunjungan->format('d M Y') }}</td>
                                    <td class="text-center">{{ $booking->jumlah_tiket }} Orang</td>
                                    <td class="text-end fw-bold text-success">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</td>
                                    <td class="text-center">
                                        @if($booking->status == 'pending')
                                            <span class="badge bg-warning text-dark rounded-pill px-3 py-1">Pending</span>
                                        @elseif($booking->status == 'menunggu_konfirmasi')
                                            <span class="badge bg-info text-dark rounded-pill px-3 py-1">Verifikasi</span>
                                        @elseif($booking->status == 'confirmed')
                                            <span class="badge bg-primary rounded-pill px-3 py-1">Confirmed</span>
                                        @elseif($booking->status == 'completed')
                                            <span class="badge bg-success rounded-pill px-3 py-1">Completed</span>
                                        @elseif($booking->status == 'cancelled')
                                            <span class="badge bg-danger rounded-pill px-3 py-1">Cancelled</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($booking->bukti_pembayaran)
                                            <a href="{{ asset('storage/' . $booking->bukti_pembayaran) }}" target="_blank" class="btn btn-xs btn-outline-info p-1 py-0" style="font-size: 0.75rem;">
                                                <i class="bi bi-image"></i> Lihat Bukti
                                            </a>
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{ route('admin.bookings.show', $booking->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                                Detail
                                            </a>

                                            @if($booking->status == 'menunggu_konfirmasi')
                                                <!-- Konfirmasi Pembayaran Form -->
                                                <form action="{{ route('admin.bookings.complete', $booking->id) }}" method="POST" onsubmit="return confirm('Konfirmasi pembayaran dan selesaikan pesanan ini?');" class="d-inline m-0">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success rounded-pill px-3">
                                                        Konfirmasi
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5 text-muted">
                                        <i class="bi bi-ticket-perforated fs-1"></i>
                                        <p class="mt-2 mb-0">Belum ada pemesanan tiket dari pengunjung.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($bookings->hasPages())
                <div class="card-footer bg-white border-top py-3 d-flex justify-content-center">
                    {{ $bookings->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
