<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="alert alert-success border-0 shadow-sm mb-4" :status="session('status')" />

    <div class="mb-4">
        <h3 class="fw-bold text-dark mb-1">Selamat Datang Kembali! 👋</h3>
        <p class="text-muted small">Silakan masuk ke akun Anda untuk melihat atau menghitung rekomendasi wisata terbaik.</p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label fw-semibold text-secondary small">Alamat Email</label>
            <div class="input-group">
                <span class="input-group-text border-end-0 bg-white"><i class="bi bi-envelope text-muted"></i></span>
                <input type="email" class="form-control border-start-0 @error('email') is-invalid @enderror"
                       id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="nama@email.com">
                @error('email')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label fw-semibold text-secondary small">Kata Sandi</label>
            <div class="input-group">
                <span class="input-group-text border-end-0 bg-white"><i class="bi bi-lock text-muted"></i></span>
                <input type="password" class="form-control border-start-0 border-end-0 @error('password') is-invalid @enderror"
                       id="password" name="password" required autocomplete="current-password" placeholder="Masukkan kata sandi">
                <button class="btn btn-outline-light border border-start-0 text-muted bg-white" type="button" id="togglePassword" style="border-color: #e2e8f0 !important;">
                    <i class="bi bi-eye-slash" id="toggleIcon"></i>
                </button>
                @error('password')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <!-- Remember Me -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="form-check">
                <input id="remember_me" type="checkbox" class="form-check-input" name="remember" style="cursor: pointer;">
                <label for="remember_me" class="form-check-label text-secondary small" style="cursor: pointer; user-select: none;">
                    Ingat saya
                </label>
            </div>

            @if (Route::has('password.request'))
                <a class="text-blue small fw-semibold text-decoration-none" href="{{ route('password.request') }}">
                    Lupa Password?
                </a>
            @endif
        </div>

        <!-- Tombol Login -->
        <div class="d-grid mb-4">
            <button type="submit" class="btn btn-primary btn-lg shadow-sm">
                Masuk ke Aplikasi
            </button>
        </div>

        <div class="text-center">
            <p class="text-muted small mb-0">Belum memiliki akun?
                <a href="{{ route('register') }}" class="text-blue fw-bold text-decoration-none">Daftar Sekarang</a>
            </p>
        </div>
    </form>

    <script>
        document.getElementById('togglePassword').addEventListener('click', function () {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('bi-eye-slash');
                toggleIcon.classList.add('bi-eye');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('bi-eye');
                toggleIcon.classList.add('bi-eye-slash');
            }
        });
    </script>
</x-guest-layout>
