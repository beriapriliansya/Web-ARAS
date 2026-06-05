<x-app-layout>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('destinasi.index') }}">Destinasi</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('destinasi.show', $destinasi->id) }}">{{ $destinasi->nama }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Pesan Tiket</li>
                    </ol>
                </nav>

                <div class="card shadow border-0 rounded-4 overflow-hidden">
                    <div class="card-header bg-primary text-white py-3">
                        <h5 class="mb-0 fw-bold"><i class="bi bi-ticket-perforated me-2"></i>Formulir Pemesanan Tiket</h5>
                    </div>
                    <div class="card-body p-4">
                        
                        <!-- Destinasi Info -->
                        <div class="d-flex align-items-center p-3 bg-light rounded-3 mb-4 border">
                            <div class="me-3" style="width: 80px; height: 80px; overflow: hidden; border-radius: 10px;">
                                @if($destinasi->foto)
                                    <img src="{{ asset('images/destinasi/' . $destinasi->foto) }}" class="w-100 h-100 object-cover">
                                @else
                                    <div class="bg-secondary w-100 h-100 d-flex align-items-center justify-content-center text-white">
                                        <i class="bi bi-image" style="font-size: 1.5rem;"></i>
                                    </div>
                                @endif
                            </div>
                            <div>
                                <span class="badge bg-primary mb-1">{{ $destinasi->kategori }}</span>
                                <h5 class="fw-bold mb-0 text-dark">{{ $destinasi->nama }}</h5>
                                <small class="text-muted"><i class="bi bi-geo-alt"></i> {{ Str::limit($destinasi->alamat, 45) }}</small>
                            </div>
                        </div>

                        <form action="{{ route('booking.store') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin memproses pemesanan tiket ini?');">
                            @csrf
                            <input type="hidden" name="destinasi_id" value="{{ $destinasi->id }}">

                            <!-- Tanggal Kunjungan -->
                            <div class="mb-3">
                                <label for="tanggal_kunjungan" class="form-label fw-bold text-dark">Tanggal Kunjungan *</label>
                                <input type="date" class="form-control" 
                                       name="tanggal_kunjungan" 
                                       id="tanggal_kunjungan" 
                                       min="{{ date('Y-m-d', strtotime('+1 day')) }}" 
                                       value="{{ old('tanggal_kunjungan') }}" 
                                       required>
                                <div class="form-text small">Pemesanan minimal H-1 dari tanggal kunjungan.</div>
                                @error('tanggal_kunjungan')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Jumlah Tiket -->
                            <div class="mb-4">
                                <label for="jumlah_tiket" class="form-label fw-bold text-dark">Jumlah Tiket (Orang) *</label>
                                <div class="input-group">
                                    <button class="btn btn-outline-secondary" type="button" id="btnMinus"><i class="bi bi-dash-lg"></i></button>
                                    <input type="number" class="form-control text-center fw-bold fs-5 text-dark" 
                                           name="jumlah_tiket" 
                                           id="jumlah_tiket" 
                                           min="1" 
                                           value="{{ old('jumlah_tiket', 1) }}" 
                                           required readonly>
                                    <button class="btn btn-outline-secondary" type="button" id="btnPlus"><i class="bi bi-plus-lg"></i></button>
                                </div>
                                @error('jumlah_tiket')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Ringkasan Pembayaran -->
                            <div class="p-3 bg-light rounded-3 mb-4 border">
                                <h6 class="fw-bold mb-3 text-dark border-bottom pb-2">Ringkasan Pembayaran</h6>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Harga Tiket per Orang</span>
                                    <span class="fw-semibold">Rp {{ number_format($destinasi->harga_tiket, 0, ',', '.') }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Jumlah Tiket</span>
                                    <span class="fw-semibold" id="ticketCountLabel">1 Orang</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold text-dark">Total Pembayaran</span>
                                    <span class="fs-4 fw-bold text-success" id="totalPaymentLabel">Rp {{ number_format($destinasi->harga_tiket, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            <!-- Buttons -->
                            <div class="d-flex justify-content-end gap-2 border-top pt-3">
                                <a href="{{ route('destinasi.show', $destinasi->id) }}" class="btn btn-secondary px-4">
                                    Batal
                                </a>
                                <button type="submit" class="btn btn-primary px-4 fw-bold">
                                    <i class="bi bi-cart-check"></i> Proses Booking
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JS untuk real-time price calculation -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ticketInput = document.getElementById('jumlah_tiket');
            const btnMinus = document.getElementById('btnMinus');
            const btnPlus = document.getElementById('btnPlus');
            const ticketCountLabel = document.getElementById('ticketCountLabel');
            const totalPaymentLabel = document.getElementById('totalPaymentLabel');
            
            const ticketPrice = {{ $destinasi->harga_tiket }};

            function updateSummary() {
                const count = parseInt(ticketInput.value || 1);
                const total = count * ticketPrice;

                ticketCountLabel.innerText = count + ' Orang';
                
                // Format rupiah
                const formatter = new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                });
                totalPaymentLabel.innerText = formatter.format(total).replace('IDR', 'Rp');
            }

            btnMinus.addEventListener('click', function() {
                let val = parseInt(ticketInput.value || 1);
                if (val > 1) {
                    ticketInput.value = val - 1;
                    updateSummary();
                }
            });

            btnPlus.addEventListener('click', function() {
                let val = parseInt(ticketInput.value || 1);
                ticketInput.value = val + 1;
                updateSummary();
            });

            // Run once on load
            updateSummary();
        });
    </script>
</x-app-layout>
