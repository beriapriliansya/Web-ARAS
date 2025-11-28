<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Super Admin') }}
        </h2>
    </x-slot>

    <div class="container py-5">

        <!-- ALERT NOTIFIKASI -->
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

        <!-- 1. KARTU STATISTIK -->
        <div class="row g-4 mb-5">
            <div class="col-md-3">
                <div class="card bg-primary text-white h-100 shadow-sm border-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-uppercase mb-1 opacity-75">Total Admin</h6>
                                <h2 class="fw-bold mb-0">{{ $totalAdmin }}</h2>
                            </div>
                            <i class="bi bi-person-badge fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white h-100 shadow-sm border-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-uppercase mb-1 opacity-75">Total User</h6>
                                <h2 class="fw-bold mb-0">{{ $totalUser }}</h2>
                            </div>
                            <i class="bi bi-people-fill fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-dark h-100 shadow-sm border-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-uppercase mb-1 opacity-75">Destinasi</h6>
                                <h2 class="fw-bold mb-0">{{ $totalDestinasi }}</h2>
                            </div>
                            <i class="bi bi-map-fill fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger text-white h-100 shadow-sm border-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-uppercase mb-1 opacity-75">Bookings</h6>
                                <h2 class="fw-bold mb-0">{{ $totalBooking }}</h2>
                            </div>
                            <i class="bi bi-ticket-perforated-fill fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. MANAJEMEN DESTINASI WISATA (PRIORITAS UTAMA) -->
        <div class="card shadow-sm border-0 mb-5">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-success">
                    <i class="bi bi-map me-2"></i> Manajemen Destinasi Wisata
                </h5>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalTambahDestinasi">
                    <i class="bi bi-plus-lg"></i> Tambah Destinasi
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Nama Destinasi</th>
                                <th>Kategori</th>
                                <th>Harga Tiket</th>
                                <th>Status</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($listDestinasi as $dest)
                                <tr>
                                    <td>
                                        <span class="fw-bold">{{ $dest->nama }}</span>
                                        <div class="small text-muted">{{ Str::limit($dest->alamat, 30) }}</div>
                                    </td>
                                    <td><span class="badge bg-info text-dark">{{ $dest->kategori }}</span></td>
                                    <td>Rp {{ number_format($dest->harga_tiket, 0, ',', '.') }}</td>
                                    <td>
                                        @if($dest->status == 'aktif')
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary">Non-Aktif</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <form action="{{ route('admin.destinasi.destroy', $dest->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus destinasi ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">Belum ada data destinasi. Tambahkan dulu!</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

        <!-- 3. MANAJEMEN USER & ROLE -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-primary">
                    <i class="bi bi-person-gear me-2"></i> Manajemen User & Hak Akses
                </h5>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahUser">
                    <i class="bi bi-plus-lg"></i> Tambah User Baru
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Nama User</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Destinasi Kelolaan</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light rounded-circle d-flex justify-content-center align-items-center me-3" style="width: 40px; height: 40px;">
                                                <span class="fw-bold text-secondary">{{ substr($user->name, 0, 1) }}</span>
                                            </div>
                                            <span class="fw-bold">{{ $user->name }}</span>
                                        </div>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @if($user->role === 'superadmin')
                                            <span class="badge bg-dark">Super Admin</span>
                                        @elseif($user->role === 'admin')
                                            <span class="badge bg-primary">Admin Destinasi</span>
                                        @else
                                            <span class="badge bg-secondary">User</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($user->role === 'admin' && $user->destinasi)
                                            <span class="badge bg-success">
                                                <i class="bi bi-geo-alt-fill"></i> {{ $user->destinasi->nama }}
                                            </span>
                                        @elseif($user->role === 'admin' && !$user->destinasi)
                                            <span class="badge bg-warning text-dark">
                                                <i class="bi bi-exclamation-circle"></i> Belum di-assign
                                            </span>
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        @if($user->id !== auth()->id())
                                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus user ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">Belum ada data user.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $users->appends(['destinasi_page' => $listDestinasi->currentPage()])->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL TAMBAH DESTINASI (BARU) -->
    <div class="modal fade" id="modalTambahDestinasi" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title fw-bold">Tambah Destinasi Wisata Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.destinasi.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Destinasi</label>
                                <input type="text" name="nama" class="form-control" required placeholder="Contoh: Pantai Pahawang">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kategori</label>
                                <select name="kategori" class="form-select" required>
                                    <option value="Pantai">Pantai</option>
                                    <option value="Pulau">Pulau</option>
                                    <option value="Air Terjun">Air Terjun</option>
                                    <option value="Bukit">Bukit</option>
                                    <option value="Taman">Taman</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Harga Tiket (Rp)</label>
                            <input type="number" name="harga_tiket" class="form-control" required placeholder="Contoh: 15000">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alamat Lengkap</label>
                            <textarea name="alamat" class="form-control" rows="2" required placeholder="Alamat lokasi wisata..."></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi Singkat</label>
                            <textarea name="deskripsi" class="form-control" rows="3" required placeholder="Jelaskan keindahan tempat ini..."></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="aktif">Aktif (Tampil di Web)</option>
                                <option value="non-aktif">Non-Aktif (Sembunyikan)</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Simpan Destinasi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL TAMBAH USER (EXISTING - TAPI PAKE DATA DESTINASI DARI DATABASE) -->
    <div class="modal fade" id="modalTambahUser" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold">Tambah User / Admin Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <!-- Nama -->
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control" required placeholder="Contoh: Budi Santoso">
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required placeholder="email@contoh.com">
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label class="form-label">Password Default</label>
                            <input type="password" name="password" class="form-control" required placeholder="Minimal 8 karakter">
                        </div>

                        <!-- Pilihan Role -->
                        <div class="mb-3">
                            <label class="form-label">Role (Hak Akses)</label>
                            <select name="role" id="roleSelect" class="form-select" required>
                                <option value="user">User Biasa (Pengunjung)</option>
                                <option value="admin">Admin Destinasi (Pengelola)</option>
                                <option value="superadmin">Super Admin</option>
                            </select>
                        </div>

                        <!-- Pilihan Destinasi -->
                        <div class="mb-3 d-none" id="destinasiContainer">
                            <label class="form-label fw-bold text-primary">Kelola Destinasi Mana?</label>
                            <select name="destinasi_id" class="form-select border-primary">
                                <option value="" selected disabled>-- Pilih Destinasi Wisata --</option>
                                @foreach($listDestinasi as $dest)
                                    <option value="{{ $dest->id }}">{{ $dest->nama }}</option>
                                @endforeach
                            </select>
                            <div class="form-text text-primary">
                                <i class="bi bi-info-circle"></i> Destinasi harus dibuat dulu di tabel atas sebelum muncul di sini.
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JAVASCRIPT LOGIC -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roleSelect = document.getElementById('roleSelect');
            const destinasiContainer = document.getElementById('destinasiContainer');

            // Fungsi untuk cek role
            function toggleDestinasi() {
                if (roleSelect.value === 'admin') {
                    // Jika Admin, munculkan dropdown destinasi
                    destinasiContainer.classList.remove('d-none');
                    destinasiContainer.querySelector('select').setAttribute('required', 'required');
                } else {
                    // Jika bukan Admin, sembunyikan
                    destinasiContainer.classList.add('d-none');
                    destinasiContainer.querySelector('select').removeAttribute('required');
                }
            }

            // Jalankan saat dropdown role berubah
            roleSelect.addEventListener('change', toggleDestinasi);
        });
    </script>
</x-app-layout>
