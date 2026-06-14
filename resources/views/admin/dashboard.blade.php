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

        <div class="row g-4 mb-5">
            <div class="col-md-6">
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
            <div class="col-md-6">
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
        </div>



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
                                        @else
                                            <span class="badge bg-secondary">User (Wisatawan)</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        @if($user->id !== auth()->id())
                                            <button class="btn btn-sm btn-outline-warning btn-edit-user"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalEditUser"
                                                data-id="{{ $user->id }}"
                                                data-name="{{ $user->name }}"
                                                data-email="{{ $user->email }}"
                                                data-role="{{ $user->role }}">
                                                <i class="bi bi-pencil"></i>
                                            </button>
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

    <div class="modal fade" id="modalBuatDestinasi" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title fw-bold">Tambah Destinasi Wisata Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.destinasi.store') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menambahkan destinasi wisata baru ini?');">
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
                                    <option value="Pantai" selected>Pantai</option>
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
                                <option value="nonaktif">Non-Aktif (Sembunyikan)</option>
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

    <div class="modal fade" id="modalEditDestinasi" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title fw-bold">Edit Destinasi Wisata</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formEditDestinasi" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin memperbarui destinasi wisata ini?');">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Destinasi</label>
                                <input type="text" name="nama" id="edit_nama" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kategori</label>
                                <select name="kategori" id="edit_kategori" class="form-select" required>
                                    <option value="Pantai" selected>Pantai</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Harga Tiket (Rp)</label>
                            <input type="number" name="harga_tiket" id="edit_harga_tiket" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alamat Lengkap</label>
                            <textarea name="alamat" id="edit_alamat" class="form-control" rows="2" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi Singkat</label>
                            <textarea name="deskripsi" id="edit_deskripsi" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" id="edit_status" class="form-select" required>
                                <option value="aktif">Aktif (Tampil di Web)</option>
                                <option value="nonaktif">Non-Aktif (Sembunyikan)</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning">Update Destinasi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalTambahUser" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold">Tambah User / Admin Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.users.store') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menambahkan user/admin baru ini?');">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control" required placeholder="Contoh: Budi Santoso">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required placeholder="email@contoh.com">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password Default</label>
                            <input type="password" name="password" class="form-control" required placeholder="Minimal 8 karakter">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Role (Hak Akses)</label>
                            <select name="role" id="roleSelect" class="form-select" required>
                                <option value="user">User Biasa (Pengunjung / Wisatawan)</option>
                                <option value="superadmin">Super Admin</option>
                            </select>
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

    <div class="modal fade" id="modalEditUser" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title fw-bold">Edit User / Hak Akses</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formEditUser" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menyimpan perubahan data user ini?');">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="name" id="edit_user_name" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" id="edit_user_email" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password Baru (Kosongkan jika tidak diubah)</label>
                            <input type="password" name="password" class="form-control" placeholder="Minimal 8 karakter">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Role (Hak Akses)</label>
                            <select name="role" id="edit_user_role" class="form-select" required>
                                <option value="user">User Biasa (Pengunjung / Wisatawan)</option>
                                <option value="superadmin">Super Admin</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning">Update User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- LOGIKA EDIT DESTINASI ---
            document.querySelectorAll('.btn-edit-destinasi').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const nama = this.getAttribute('data-nama');
                    const kategori = this.getAttribute('data-kategori');
                    const hargaTiket = this.getAttribute('data-harga_tiket');
                    const alamat = this.getAttribute('data-alamat');
                    const deskripsi = this.getAttribute('data-deskripsi');
                    const status = this.getAttribute('data-status');

                    // 1. Set Action URL Form
                    const form = document.getElementById('formEditDestinasi');
                    // Pastikan route ini sesuai di routes/web.php
                    form.action = `{{ url('admin/destinasi') }}/${id}`;

                    // 2. Isi data ke form modal
                    document.getElementById('edit_nama').value = nama;
                    document.getElementById('edit_kategori').value = kategori;
                    document.getElementById('edit_harga_tiket').value = hargaTiket;
                    document.getElementById('edit_alamat').value = alamat;
                    document.getElementById('edit_deskripsi').value = deskripsi;
                    document.getElementById('edit_status').value = status;
                });
            });

            // --- LOGIKA EDIT USER ---
            document.querySelectorAll('.btn-edit-user').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const name = this.getAttribute('data-name');
                    const email = this.getAttribute('data-email');
                    const role = this.getAttribute('data-role');

                    // Elemen di modal edit user
                    const form = document.getElementById('formEditUser');
                    const editRoleSelect = document.getElementById('edit_user_role');

                    // 1. Set Action URL Form
                    // Pastikan route ini sesuai di routes/web.php
                    form.action = `{{ url('admin/users') }}/${id}`;

                    // 2. Isi data ke form modal
                    document.getElementById('edit_user_name').value = name;
                    document.getElementById('edit_user_email').value = email;
                    editRoleSelect.value = role;
                });
            });

        });
    </script>
</x-app-layout>
