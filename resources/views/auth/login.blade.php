<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="alert alert-success mb-4" :status="session('status')" />

    <div class="text-center mb-4">
        <h4 class="fw-bold">Selamat Datang Kembali! 👋</h4>
        <p class="text-muted">Silakan login untuk mengakses akun Anda.</p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label fw-semibold">Email</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror"
                   id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="nama@email.com">
            @error('email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label fw-semibold">Password</label>
            <input type="password" class="form-control @error('password') is-invalid @enderror"
                   id="password" name="password" required autocomplete="current-password" placeholder="********">
            @error('password')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="form-check">
                <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                <label for="remember_me" class="form-check-label text-sm text-secondary">
                    Ingat Saya
                </label>
            </div>

            @if (Route::has('password.request'))
                <a class="text-decoration-none text-sm text-primary" href="{{ route('password.request') }}">
                    Lupa Password?
                </a>
            @endif
        </div>

        <!-- Tombol Login -->
        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary btn-lg fw-bold">
                Log in
            </button>
        </div>

        <div class="text-center mt-4">
            <p class="text-muted mb-0">Belum punya akun?
                <a href="{{ route('register') }}" class="text-primary fw-bold text-decoration-none">Daftar Sekarang</a>
            </p>
        </div>
    </form>
</x-guest-layout>
