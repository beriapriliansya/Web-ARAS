<x-app-layout>
    <!-- Page Header -->
    <div class="bg-primary text-white py-5" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
        <div class="container text-center py-3">
            <h1 class="display-4 fw-bold mb-2">
                <i class="bi bi-person-fill-gear"></i> Pengaturan Profil
            </h1>
            <p class="lead mb-0 opacity-75">
                Kelola informasi akun dan pengaturan keamanan Anda
            </p>
        </div>
    </div>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                
                @if (session('status') === 'profile-updated')
                    <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm mb-4" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i> {{ __('Informasi profil berhasil diperbarui!') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('status') === 'password-updated')
                    <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm mb-4" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i> {{ __('Kata sandi berhasil diperbarui!') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if ($errors->updatePassword->any())
                    <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm mb-4" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ __('Gagal memperbarui kata sandi. Silakan periksa kembali input Anda.') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Card 1: Informasi Profil -->
                <div class="card shadow border-0 rounded-4 overflow-hidden mb-4">
                    <div class="card-header bg-primary text-white py-3">
                        <h5 class="mb-0 fw-bold"><i class="bi bi-person-badge-fill me-2"></i>{{ __('Informasi Profil') }}</h5>
                    </div>
                    <div class="card-body p-4 p-md-5 bg-white">
                        <p class="text-secondary small mb-4">
                            {{ __("Perbarui nama dan alamat email akun Anda.") }}
                        </p>

                        <form method="post" action="{{ route('profile.update') }}">
                            @csrf
                            @method('patch')

                            <div class="mb-3">
                                <label for="name" class="form-label fw-bold text-secondary small">{{ __('Nama Lengkap') }}</label>
                                <input id="name" name="name" type="text" class="form-control rounded-3 @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="email" class="form-label fw-bold text-secondary small">{{ __('Alamat Email') }}</label>
                                <input id="email" name="email" type="email" class="form-control rounded-3 @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required autocomplete="username">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                    <div class="mt-3 p-3 bg-warning-subtle text-warning-emphasis rounded-3 small">
                                        {{ __('Alamat email Anda belum diverifikasi.') }}
                                        <button form="send-verification" class="btn btn-link p-0 text-warning-emphasis fw-bold text-decoration-underline small">
                                            {{ __('Klik di sini untuk mengirim ulang email verifikasi.') }}
                                        </button>

                                        @if (session('status') === 'verification-link-sent')
                                            <p class="mt-2 text-success fw-bold">
                                                {{ __('Link verifikasi baru telah dikirim ke alamat email Anda.') }}
                                            </p>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <div class="d-flex justify-content-end mt-4">
                                <button type="submit" class="btn btn-primary px-4 fw-bold rounded-pill shadow-sm">
                                    <i class="bi bi-save me-1"></i> {{ __('Simpan Perubahan') }}
                                </button>
                            </div>
                        </form>

                        <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                            @csrf
                        </form>
                    </div>
                </div>

                <!-- Card 2: Perbarui Kata Sandi -->
                <div class="card shadow border-0 rounded-4 overflow-hidden mb-4">
                    <div class="card-header bg-primary text-white py-3">
                        <h5 class="mb-0 fw-bold"><i class="bi bi-shield-lock-fill me-2"></i>{{ __('Perbarui Kata Sandi') }}</h5>
                    </div>
                    <div class="card-body p-4 p-md-5 bg-white">
                        <p class="text-secondary small mb-4">
                            {{ __('Pastikan akun Anda menggunakan kata sandi yang kuat dan aman.') }}
                        </p>

                        <form method="post" action="{{ route('password.update') }}">
                            @csrf
                            @method('put')

                            <div class="mb-3">
                                <label for="update_password_current_password" class="form-label fw-bold text-secondary small">{{ __('Kata Sandi Saat Ini') }}</label>
                                <input id="update_password_current_password" name="current_password" type="password" class="form-control rounded-3 @if($errors->updatePassword->has('current_password')) is-invalid @endif" autocomplete="current-password">
                                @if($errors->updatePassword->has('current_password'))
                                    <div class="invalid-feedback">{{ $errors->updatePassword->first('current_password') }}</div>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label for="update_password_password" class="form-label fw-bold text-secondary small">{{ __('Kata Sandi Baru') }}</label>
                                <input id="update_password_password" name="password" type="password" class="form-control rounded-3 @if($errors->updatePassword->has('password')) is-invalid @endif" autocomplete="new-password">
                                @if($errors->updatePassword->has('password'))
                                    <div class="invalid-feedback">{{ $errors->updatePassword->first('password') }}</div>
                                @endif
                            </div>

                            <div class="mb-4">
                                <label for="update_password_password_confirmation" class="form-label fw-bold text-secondary small">{{ __('Konfirmasi Kata Sandi Baru') }}</label>
                                <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="form-control rounded-3 @if($errors->updatePassword->has('password_confirmation')) is-invalid @endif" autocomplete="new-password">
                                @if($errors->updatePassword->has('password_confirmation'))
                                    <div class="invalid-feedback">{{ $errors->updatePassword->first('password_confirmation') }}</div>
                                @endif
                            </div>

                            <div class="d-flex justify-content-end mt-4">
                                <button type="submit" class="btn btn-primary px-4 fw-bold rounded-pill shadow-sm">
                                    <i class="bi bi-key me-1"></i> {{ __('Perbarui Kata Sandi') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Card 3: Hapus Akun -->
                <div class="card shadow border-0 rounded-4 overflow-hidden">
                    <div class="card-header bg-danger text-white py-3">
                        <h5 class="mb-0 fw-bold"><i class="bi bi-exclamation-triangle-fill me-2"></i>{{ __('Hapus Akun') }}</h5>
                    </div>
                    <div class="card-body p-4 p-md-5 bg-white">
                        <p class="text-secondary small mb-4">
                            {{ __('Setelah akun Anda dihapus, semua sumber daya dan datanya akan dihapus secara permanen. Tindakan ini tidak dapat dibatalkan.') }}
                        </p>

                        <div class="d-flex justify-content-start">
                            <button type="button" class="btn btn-danger px-4 fw-bold rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#confirmUserDeletionModal">
                                <i class="bi bi-trash3 me-1"></i> {{ __('Hapus Akun Anda') }}
                            </button>
                        </div>

                        <!-- Bootstrap Modal Confirmation -->
                        <div class="modal fade" id="confirmUserDeletionModal" tabindex="-1" aria-labelledby="confirmUserDeletionModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content rounded-4 border-0 shadow-lg">
                                    <div class="modal-header bg-danger text-white py-3">
                                        <h5 class="modal-title fw-bold" id="confirmUserDeletionModalLabel">
                                            <i class="bi bi-exclamation-octagon me-2"></i>{{ __('Apakah Anda Yakin?') }}
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form method="post" action="{{ route('profile.destroy') }}">
                                        @csrf
                                        @method('delete')
                                        <div class="modal-body p-4">
                                            <p class="text-secondary mb-3">
                                                {{ __('Semua data Anda akan dihapus secara permanen. Silakan masukkan kata sandi Anda untuk mengonfirmasi tindakan ini.') }}
                                            </p>
                                            <div class="mb-3">
                                                <label for="password" class="form-label fw-bold text-secondary small">{{ __('Kata Sandi Anda') }}</label>
                                                <input id="password" name="password" type="password" class="form-control rounded-3 @if($errors->userDeletion->has('password')) is-invalid @endif" placeholder="{{ __('Masukkan kata sandi') }}" required>
                                                @if($errors->userDeletion->has('password'))
                                                    <div class="invalid-feedback d-block">{{ $errors->userDeletion->first('password') }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="modal-footer bg-light p-3 d-flex justify-content-end gap-2 border-0">
                                            <button type="button" class="btn btn-secondary px-3 fw-bold rounded-pill" data-bs-dismiss="modal">
                                                {{ __('Batal') }}
                                            </button>
                                            <button type="submit" class="btn btn-danger px-4 fw-bold rounded-pill">
                                                {{ __('Hapus Akun') }}
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Script to auto-open delete modal on validation failure -->
    @if ($errors->userDeletion->isNotEmpty())
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var deleteModal = new bootstrap.Modal(document.getElementById('confirmUserDeletionModal'));
                deleteModal.show();
            });
        </script>
    @endif
</x-app-layout>
