<x-app-layout>

    <div class="container py-5">
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
        @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="bi bi-info-circle-fill me-2"></i> {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card shadow border-0 rounded-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold text-primary"><i class="bi bi-ticket-detailed-fill me-2"></i>Riwayat Pemesanan Tiket</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Kode Booking</th>
                                <th>Destinasi Wisata</th>
                                <th class="text-center">Tanggal Kunjungan</th>
                                <th class="text-center">Jumlah Tiket</th>
                                <th class="text-end">Total Harga</th>
                                <th class="text-center">Status</th>
                                <th class="text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bookings as $booking)
                                <tr>
                                    <td class="ps-4 fw-bold font-monospace text-secondary">{{ $booking->kode_booking }}</td>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ $booking->destinasi->nama }}</div>
                                        <small class="text-muted">{{ $booking->destinasi->kategori }}</small>
                                    </td>
                                    <td class="text-center">{{ $booking->tanggal_kunjungan->format('d M Y') }}</td>
                                    <td class="text-center">{{ $booking->jumlah_tiket }} Orang</td>
                                    <td class="text-end fw-bold text-success">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</td>
                                    <td class="text-center">
                                        @if($booking->status == 'pending')
                                            <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">Pending</span>
                                        @elseif($booking->status == 'menunggu_konfirmasi')
                                            <span class="badge bg-info text-dark px-3 py-2 rounded-pill">Menunggu Verifikasi</span>
                                        @elseif($booking->status == 'confirmed')
                                            <span class="badge bg-primary px-3 py-2 rounded-pill">Confirmed</span>
                                        @elseif($booking->status == 'completed')
                                            <span class="badge bg-success px-3 py-2 rounded-pill">Completed</span>
                                        @elseif($booking->status == 'cancelled')
                                            <span class="badge bg-danger px-3 py-2 rounded-pill">Cancelled</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{ route('booking.show', $booking->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                                Detail
                                            </a>
                                            
                                            @if($booking->status == 'pending')
                                                <form action="{{ route('booking.cancel', $booking->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?');" class="d-inline m-0">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                                        Batal
                                                    </button>
                                                </form>
                                            @endif

                                            @if($booking->status == 'completed')
                                                <!-- Pengecekan apakah sudah diulas dapat dihandle model accessor atau diabaikan, controller akan memvalidasi ulang -->
                                                <a href="{{ route('ulasan.create', $booking->id) }}" class="btn btn-sm btn-warning rounded-pill px-3 text-dark fw-bold">
                                                    <i class="bi bi-star-fill"></i> Beri Ulasan
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="bi bi-ticket-perforated fs-1"></i>
                                        <p class="mt-2 mb-3">Anda belum memiliki riwayat pemesanan tiket.</p>
                                        <a href="{{ route('destinasi.index') }}" class="btn btn-primary rounded-pill">Cari Destinasi Wisata</a>
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
