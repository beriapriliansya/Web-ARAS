<x-app-layout>
    <!-- Page Header -->
    <div class="bg-primary text-white py-5" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
        <div class="container text-center py-3">
            <h1 class="display-4 fw-bold mb-2">
                <i class="bi bi-bell-fill"></i> Notifikasi Anda
            </h1>
            <p class="lead mb-0 opacity-75">
                Lihat riwayat aktivitas, pembaruan berita, dan keamanan akun Anda
            </p>
        </div>
    </div>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm mb-4" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="card shadow border-0 rounded-4 overflow-hidden mb-4">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                        <h5 class="mb-0 fw-bold text-primary">
                            <i class="bi bi-envelope-open-fill me-2"></i>{{ __('Daftar Notifikasi') }}
                        </h5>
                        
                        @php
                            $unreadCount = $notifications->where('read_at', null)->count();
                        @endphp

                        @if($unreadCount > 0)
                            <form action="{{ route('notifications.readAll') }}" method="POST" class="m-0">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-primary fw-bold rounded-pill px-3">
                                    <i class="bi bi-check2-all me-1"></i> {{ __('Tandai Semua Dibaca') }}
                                </button>
                            </form>
                        @endif
                    </div>
                    <div class="card-body p-0 bg-white">
                        <div class="list-group list-group-flush">
                            @forelse($notifications as $notif)
                                <div class="list-group-item p-4 transition-all" style="background-color: #ffffff; @if(!$notif->read_at) border-left: 4px solid #0d6efd !important; @endif">
                                    <div class="d-flex justify-content-between align-items-start gap-2 mb-2 flex-wrap">
                                        <div class="d-flex align-items-center gap-2">
                                            <!-- Notification Icon based on type -->
                                            @if($notif->type === 'news')
                                                <span class="badge rounded-circle p-2 bg-success text-white d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;"><i class="bi bi-newspaper fs-6"></i></span>
                                            @elseif($notif->type === 'security_email' || $notif->type === 'security_password')
                                                <span class="badge rounded-circle p-2 bg-warning text-dark d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;"><i class="bi bi-shield-lock-fill fs-6"></i></span>
                                            @else
                                                <span class="badge rounded-circle p-2 bg-secondary text-white d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;"><i class="bi bi-info-circle-fill fs-6"></i></span>
                                            @endif
                                            
                                            <div class="ms-2">
                                                <h6 class="fw-bold mb-0 text-dark">{{ $notif->title }}</h6>
                                                <small class="text-muted" style="font-size: 0.75rem;">{{ $notif->created_at->diffForHumans() }}</small>
                                            </div>
                                        </div>

                                        @if(!$notif->read_at)
                                            <form action="{{ route('notifications.read', $notif->id) }}" method="POST" class="m-0">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-light border rounded-pill px-2 py-1 small" title="Tandai Dibaca" style="font-size: 0.75rem;">
                                                    <i class="bi bi-check-lg text-success"></i> Tandai Dibaca
                                                </button>
                                            </form>
                                        @else
                                            <span class="badge bg-light text-secondary border py-1 px-3 rounded-pill small" style="font-size: 0.7rem;">Sudah Dibaca</span>
                                        @endif
                                    </div>
                                    <p class="mb-0 text-secondary mt-2 ms-0 ms-sm-5" style="font-size: 0.95rem; line-height: 1.6;">{{ $notif->message }}</p>
                                </div>
                            @empty
                                <div class="text-center py-5 text-muted bg-white">
                                    <i class="bi bi-bell-slash fs-1 mb-3 d-block text-secondary"></i>
                                    <h5 class="fw-bold">Tidak Ada Notifikasi</h5>
                                    <p class="small mb-0">Semua pemberitahuan baru akan muncul di sini.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $notifications->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
