<x-app-layout>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <!-- Header -->
                <div class="card mb-4 shadow-sm border-0 border-start border-primary border-5">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="fw-bold mb-1 text-primary">
                                <i class="bi bi-grid-fill"></i> Kelola Fasilitas
                            </h2>
                            <p class="text-muted mb-0">Manajemen Master Fasilitas Destinasi Wisata</p>
                        </div>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
                        </a>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="row g-4">
                    <!-- Form Tambah / Edit -->
                    <div class="col-md-4">
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-white py-3">
                                <h5 class="mb-0 fw-bold text-dark" id="form-title">
                                    <i class="bi bi-plus-circle text-success me-1"></i> Tambah Fasilitas
                                </h5>
                            </div>
                            <div class="card-body">
                                <form id="fasilitas-form" action="{{ route('admin.fasilitas.store') }}" method="POST">
                                    @csrf
                                    <div id="method-container"></div>
                                    
                                    <div class="mb-3">
                                        <label for="nama_fasilitas" class="form-label fw-bold">Nama Fasilitas *</label>
                                        <input type="text" 
                                               class="form-control @error('nama_fasilitas') is-invalid @enderror" 
                                               id="nama_fasilitas" 
                                               name="nama_fasilitas" 
                                               value="{{ old('nama_fasilitas') }}" 
                                               required 
                                               placeholder="Contoh: Toilet, Gazebo, Kolam Renang">
                                        @error('nama_fasilitas')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-primary fw-bold" id="submit-btn">
                                            <i class="bi bi-save"></i> Simpan Fasilitas
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary btn-sm fw-bold d-none" id="cancel-edit-btn">
                                            <i class="bi bi-x-circle"></i> Batal Edit
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Tabel Daftar Fasilitas -->
                    <div class="col-md-8">
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-white py-3">
                                <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-list-ul me-1"></i> Daftar Fasilitas</h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th width="10%" class="text-center">#</th>
                                                <th>Nama Fasilitas</th>
                                                <th width="30%" class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($fasilitas as $index => $item)
                                                <tr>
                                                    <td class="text-center text-muted fw-bold">
                                                        {{ ($fasilitas->currentPage() - 1) * $fasilitas->perPage() + $loop->iteration }}
                                                    </td>
                                                    <td class="fw-semibold text-dark">{{ $item->nama_fasilitas }}</td>
                                                    <td class="text-center">
                                                        <div class="d-flex justify-content-center gap-2">
                                                            <!-- Edit Button (trigger JS swap) -->
                                                            <button type="button" 
                                                                    class="btn btn-warning btn-sm rounded-circle d-flex align-items-center justify-content-center edit-btn"
                                                                    style="width: 32px; height: 32px;"
                                                                    data-id="{{ $item->id }}"
                                                                    data-name="{{ $item->nama_fasilitas }}"
                                                                    title="Edit">
                                                                <i class="bi bi-pencil-fill text-white fs-6"></i>
                                                            </button>

                                                            <!-- Delete Form -->
                                                            <form action="{{ route('admin.fasilitas.destroy', $item->id) }}" 
                                                                  method="POST" 
                                                                  class="d-inline mb-0"
                                                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus fasilitas ini?');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" 
                                                                        class="btn btn-danger btn-sm rounded-circle d-flex align-items-center justify-content-center"
                                                                        style="width: 32px; height: 32px;"
                                                                        title="Hapus">
                                                                    <i class="bi bi-trash-fill text-white fs-6"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3" class="text-center py-4 text-muted">
                                                        <i class="bi bi-info-circle fs-3 d-block mb-2 text-warning"></i>
                                                        Belum ada data fasilitas yang tersimpan.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Pagination -->
                                @if($fasilitas->hasPages())
                                    <div class="card-footer bg-white border-0 py-3">
                                        {{ $fasilitas->links() }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Script for Dynamic Form Toggle -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('fasilitas-form');
            const formTitle = document.getElementById('form-title');
            const inputNama = document.getElementById('nama_fasilitas');
            const submitBtn = document.getElementById('submit-btn');
            const cancelEditBtn = document.getElementById('cancel-edit-btn');
            const methodContainer = document.getElementById('method-container');
            const storeUrl = "{{ route('admin.fasilitas.store') }}";

            // Event listener for edit buttons
            document.querySelectorAll('.edit-btn').forEach(button => {
                button.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    const name = this.getAttribute('data-name');
                    
                    // Set form action URL for update
                    form.action = `/admin/fasilitas/${id}`;
                    
                    // Insert method PUT for Laravel resource update
                    methodContainer.innerHTML = '<input type="hidden" name="_method" value="PUT">';
                    
                    // Set values
                    inputNama.value = name;
                    
                    // Update UI texts
                    formTitle.innerHTML = '<i class="bi bi-pencil-square text-warning me-1"></i> Edit Fasilitas';
                    submitBtn.className = 'btn btn-warning text-white fw-bold';
                    submitBtn.innerHTML = '<i class="bi bi-save"></i> Perbarui Fasilitas';
                    
                    // Show cancel button
                    cancelEditBtn.classList.remove('d-none');
                    
                    // Focus input
                    inputNama.focus();
                });
            });

            // Event listener for cancel edit button
            cancelEditBtn.addEventListener('click', function () {
                // Reset form action back to store
                form.action = storeUrl;
                
                // Clear PUT method
                methodContainer.innerHTML = '';
                
                // Reset inputs
                inputNama.value = '';
                
                // Reset UI texts
                formTitle.innerHTML = '<i class="bi bi-plus-circle text-success me-1"></i> Tambah Fasilitas';
                submitBtn.className = 'btn btn-primary fw-bold';
                submitBtn.innerHTML = '<i class="bi bi-save"></i> Simpan Fasilitas';
                
                // Hide cancel button
                this.classList.add('d-none');
            });
        });
    </script>
</x-app-layout>
