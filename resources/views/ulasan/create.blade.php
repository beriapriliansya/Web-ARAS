<x-app-layout>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('booking.index') }}">Booking Saya</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Beri Ulasan</li>
                    </ol>
                </nav>

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="card shadow border-0 rounded-4 overflow-hidden">
                    <div class="card-header bg-warning text-dark py-3">
                        <h5 class="mb-0 fw-bold"><i class="bi bi-star-fill me-2"></i>Tulis Ulasan & Penilaian</h5>
                    </div>
                    <div class="card-body p-4">
                        
                        <!-- Destinasi Info Card -->
                        <div class="d-flex align-items-center p-3 bg-light rounded-3 mb-4 border">
                            <div class="me-3" style="width: 70px; height: 70px; overflow: hidden; border-radius: 10px;">
                                @if($booking->destinasi->foto)
                                    <img src="{{ asset('images/destinasi/' . $booking->destinasi->foto) }}" class="w-100 h-100 object-cover">
                                @else
                                    <div class="bg-secondary w-100 h-100 d-flex align-items-center justify-content-center text-white">
                                        <i class="bi bi-image" style="font-size: 1.5rem;"></i>
                                    </div>
                                @endif
                            </div>
                            <div>
                                <span class="badge bg-primary mb-1">{{ $booking->destinasi->kategori }}</span>
                                <h6 class="fw-bold mb-0 text-dark">{{ $booking->destinasi->nama }}</h6>
                                <small class="text-muted"><i class="bi bi-calendar-check"></i> Dikunjungi: {{ $booking->tanggal_kunjungan->format('d M Y') }}</small>
                            </div>
                        </div>

                        <form action="{{ route('ulasan.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="booking_id" value="{{ $booking->id }}">

                            <!-- Rating Selector -->
                            <div class="mb-4 text-center">
                                <label class="form-label fw-bold d-block text-dark mb-2">Berapa bintang yang Anda berikan?</label>
                                <div class="rating-stars fs-1 text-secondary mb-2" style="cursor: pointer;">
                                    <i class="bi bi-star star-btn" data-value="1"></i>
                                    <i class="bi bi-star star-btn" data-value="2"></i>
                                    <i class="bi bi-star star-btn" data-value="3"></i>
                                    <i class="bi bi-star star-btn" data-value="4"></i>
                                    <i class="bi bi-star star-btn" data-value="5"></i>
                                </div>
                                <input type="hidden" name="rating" id="ratingValue" value="" required>
                                <div class="fw-bold text-primary" id="ratingLabel">- Pilih Rating -</div>
                                @error('rating')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Komentar -->
                            <div class="mb-4">
                                <label for="komentar" class="form-label fw-bold text-dark">Tulis Komentar & Ulasan Anda *</label>
                                <textarea name="komentar" id="komentar" class="form-control" rows="4" 
                                          placeholder="Ceritakan pengalaman menyenangkan Anda di destinasi ini (fasilitas, kebersihan, pemandangan, dll)..." 
                                          required>{{ old('komentar') }}</textarea>
                                @error('komentar')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Buttons -->
                            <div class="d-flex justify-content-end gap-2 border-top pt-3">
                                <a href="{{ route('booking.index') }}" class="btn btn-secondary px-4">
                                    Batal
                                </a>
                                <button type="submit" class="btn btn-warning px-4 fw-bold text-dark">
                                    Kirim Ulasan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Rating Script -->
    <style>
        .rating-stars .bi-star-fill {
            color: #ffc107 !important;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const stars = document.querySelectorAll('.star-btn');
            const ratingInput = document.getElementById('ratingValue');
            const ratingLabel = document.getElementById('ratingLabel');

            const labels = {
                1: 'Buruk Sekali 😞',
                2: 'Kurang Bagus 😐',
                3: 'Cukup Memuaskan 🙂',
                4: 'Sangat Bagus! 😀',
                5: 'Luar Biasa Sempurna! 😍'
            };

            stars.forEach(star => {
                star.addEventListener('click', function() {
                    const value = parseInt(this.getAttribute('data-value'));
                    ratingInput.value = value;
                    ratingLabel.innerText = labels[value];

                    // Reset and highlight stars
                    stars.forEach((s, idx) => {
                        if (idx < value) {
                            s.classList.remove('bi-star');
                            s.classList.add('bi-star-fill');
                        } else {
                            s.classList.remove('bi-star-fill');
                            s.classList.add('bi-star');
                        }
                    });
                });

                star.addEventListener('mouseover', function() {
                    const value = parseInt(this.getAttribute('data-value'));
                    stars.forEach((s, idx) => {
                        if (idx < value) {
                            s.classList.add('text-warning');
                        }
                    });
                });

                star.addEventListener('mouseout', function() {
                    stars.forEach(s => {
                        s.classList.remove('text-warning');
                    });
                });
            });
        });
    </script>
</x-app-layout>
