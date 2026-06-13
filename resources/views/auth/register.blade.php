<x-guest-layout>
    <div class="mb-4">
        <h3 class="fw-bold text-dark mb-1">Daftar Akun Baru 🚀</h3>
        <p class="text-muted small">Buat akun Anda untuk mulai menjelajahi dan menentukan destinasi wisata terbaik.</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="mb-3">
            <label for="name" class="form-label fw-semibold text-secondary small">Nama Lengkap</label>
            <div class="input-group">
                <span class="input-group-text border-end-0 bg-white"><i class="bi bi-person text-muted"></i></span>
                <input type="text" class="form-control border-start-0 @error('name') is-invalid @enderror"
                       id="name" name="name" value="{{ old('name') }}" required autofocus placeholder="John Doe">
                @error('name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label fw-semibold text-secondary small">Alamat Email</label>
            <div class="input-group">
                <span class="input-group-text border-end-0 bg-white"><i class="bi bi-envelope text-muted"></i></span>
                <input type="email" class="form-control border-start-0 @error('email') is-invalid @enderror"
                       id="email" name="email" value="{{ old('email') }}" required placeholder="nama@email.com">
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
                       id="password" name="password" required autocomplete="new-password" placeholder="Minimal 8 karakter">
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

        <!-- Confirm Password -->
        <div class="mb-4">
            <label for="password_confirmation" class="form-label fw-semibold text-secondary small">Konfirmasi Kata Sandi</label>
            <div class="input-group">
                <span class="input-group-text border-end-0 bg-white"><i class="bi bi-lock-fill text-muted"></i></span>
                <input type="password" class="form-control border-start-0 border-end-0 @error('password_confirmation') is-invalid @enderror"
                       id="password_confirmation" name="password_confirmation" required placeholder="Masukkan kembali kata sandi">
                <button class="btn btn-outline-light border border-start-0 text-muted bg-white" type="button" id="toggleConfirmPassword" style="border-color: #e2e8f0 !important;">
                    <i class="bi bi-eye-slash" id="toggleConfirmIcon"></i>
                </button>
                @error('password_confirmation')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <!-- Tombol Register -->
        <div class="d-grid mb-4">
            <button type="submit" class="btn btn-primary btn-lg shadow-sm">
                Daftar Akun Baru
            </button>
        </div>

        <div class="text-center">
            <p class="text-muted small mb-0">Sudah memiliki akun?
                <a href="{{ route('login') }}" class="text-blue fw-bold text-decoration-none">Login di sini</a>
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

        document.getElementById('toggleConfirmPassword').addEventListener('click', function () {
            const confirmInput = document.getElementById('password_confirmation');
            const toggleConfirmIcon = document.getElementById('toggleConfirmIcon');
            
            if (confirmInput.type === 'password') {
                confirmInput.type = 'text';
                toggleConfirmIcon.classList.remove('bi-eye-slash');
                toggleConfirmIcon.classList.add('bi-eye');
            } else {
                confirmInput.type = 'password';
                toggleConfirmIcon.classList.remove('bi-eye');
                toggleConfirmIcon.classList.add('bi-eye-slash');
            }
        });
    </script>
</x-guest-layout>
