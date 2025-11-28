<x-guest-layout>
    <div class="text-center mb-4">
        <h4 class="fw-bold">Buat Akun Baru 🚀</h4>
        <p class="text-muted">Bergabunglah untuk menjelajahi Pesawaran!</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="mb-3">
            <label for="name" class="form-label fw-semibold">Nama Lengkap</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror"
                   id="name" name="name" value="{{ old('name') }}" required autofocus placeholder="John Doe">
            @error('name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label fw-semibold">Email</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror"
                   id="email" name="email" value="{{ old('email') }}" required placeholder="nama@email.com">
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
                   id="password" name="password" required autocomplete="new-password" placeholder="Minimal 8 karakter">
            @error('password')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="mb-4">
            <label for="password_confirmation" class="form-label fw-semibold">Konfirmasi Password</label>
            <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                   id="password_confirmation" name="password_confirmation" required placeholder="Ulangi password">
            @error('password_confirmation')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Tombol Register -->
        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary btn-lg fw-bold">
                Daftar Akun
            </button>
        </div>

        <div class="text-center mt-4">
            <p class="text-muted mb-0">Sudah punya akun?
                <a href="{{ route('login') }}" class="text-primary fw-bold text-decoration-none">Login di sini</a>
            </p>
        </div>
    </form>
</x-guest-layout>
