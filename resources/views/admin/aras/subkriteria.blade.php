<x-app-layout>
    <div class="container py-5">
        
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.aras.index') }}">Perhitungan ARAS</a></li>
                <li class="breadcrumb-item active" aria-current="page">Kelola Sub-Kriteria</li>
            </ol>
        </nav>

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

        <div class="row g-4">
            <!-- KOLOM KIRI: Form Tambah/Edit -->
            <div class="col-lg-4">
                <div class="card shadow border-0 rounded-4 overflow-hidden">
                    <div class="card-header bg-primary text-white py-3">
                        <h6 class="mb-0 fw-bold" id="formTitle"><i class="bi bi-plus-circle me-2"></i>Tambah Sub-Kriteria Baru</h6>
                    </div>
                    <div class="card-body p-4">
                        <form id="subKriteriaForm" action="{{ route('admin.aras.subkriteria.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="_method" id="formMethod" value="POST">
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold text-secondary">Kriteria Induk</label>
                                <select name="kriteria_id" id="edit_kriteria_id" class="form-select" required>
                                    <option value="" selected disabled>-- Pilih Kriteria --</option>
                                    @foreach($kriteria as $k)
                                        <option value="{{ $k->id }}">{{ $k->nama_kriteria }} ({{ $k->kode }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold text-secondary">Keterangan Sub-Kriteria</label>
                                <input type="text" name="keterangan" id="edit_keterangan" class="form-control" required 
                                       placeholder="Contoh: Sangat Bersih">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold text-secondary">Nilai Bobot</label>
                                <input type="number" step="0.01" min="0" max="100" name="nilai" id="edit_nilai" class="form-control" required 
                                       placeholder="Contoh: 1 atau 0.75">
                            </div>

                            <div class="d-flex gap-2 mt-4">
                                <button type="button" id="btnCancelEdit" class="btn btn-secondary w-50 d-none">Batal</button>
                                <button type="submit" id="btnSubmitForm" class="btn btn-primary px-4">Simpan Data</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- KOLOM KANAN: Tabel Daftar Sub Kriteria -->
            <div class="col-lg-8">
                <div class="card shadow border-0 rounded-4 overflow-hidden">
                    <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <h6 class="mb-0 fw-bold"><i class="bi bi-list-stars me-2"></i>Daftar Sub Kriteria</h6>
                        
                        <!-- Filter Kriteria -->
                        <form action="{{ route('admin.aras.subkriteria') }}" method="GET" id="filterForm" class="m-0">
                            <select name="kriteria_filter" class="form-select form-select-sm text-dark bg-white border-0" onchange="document.getElementById('filterForm').submit()">
                                <option value="">Tampilkan Semua Kriteria</option>
                                @foreach($kriteria as $k)
                                    <option value="{{ $k->id }}" {{ $filterKriteriaId == $k->id ? 'selected' : '' }}>
                                        {{ $k->nama_kriteria }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" width="8%">No</th>
                                        <th width="32%">Kriteria Induk</th>
                                        <th width="35%">Keterangan</th>
                                        <th class="text-center" width="10%">Nilai</th>
                                        <th class="text-center" width="15%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($subKriteria as $index => $sub)
                                        <tr>
                                            <td class="text-center text-secondary small">{{ $index + 1 }}</td>
                                            <td class="fw-semibold text-dark">{{ $sub->kriteria->nama_kriteria }}</td>
                                            <td>{{ $sub->keterangan }}</td>
                                            <td class="text-center fw-bold text-primary">{{ number_format($sub->nilai, 2) }}</td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center gap-1">
                                                    <!-- Edit Button -->
                                                    <button type="button" class="btn btn-sm btn-outline-warning btn-edit-sub" 
                                                            data-id="{{ $sub->id }}"
                                                            data-kriteria_id="{{ $sub->kriteria_id }}"
                                                            data-keterangan="{{ $sub->keterangan }}"
                                                            data-nilai="{{ $sub->nilai }}">
                                                        <i class="bi bi-pencil-fill"></i>
                                                    </button>
                                                    
                                                    <!-- Delete Button -->
                                                    <form action="{{ route('admin.aras.subkriteria.destroy', $sub->id) }}" 
                                                          method="POST" class="d-inline m-0"
                                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus sub-kriteria ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                                            <i class="bi bi-trash-fill"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-5 text-muted">
                                                <i class="bi bi-clipboard-x fs-2 mb-2 d-block"></i>
                                                Belum ada data sub-kriteria.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JS untuk handles Edit mode secara client-side -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const editButtons = document.querySelectorAll('.btn-edit-sub');
            const form = document.getElementById('subKriteriaForm');
            const formTitle = document.getElementById('formTitle');
            const formMethod = document.getElementById('formMethod');
            const btnSubmit = document.getElementById('btnSubmitForm');
            const btnCancel = document.getElementById('btnCancelEdit');

            const selectKriteria = document.getElementById('edit_kriteria_id');
            const inputKeterangan = document.getElementById('edit_keterangan');
            const inputNilai = document.getElementById('edit_nilai');

            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const kriteriaId = this.getAttribute('data-kriteria_id');
                    const keterangan = this.getAttribute('data-keterangan');
                    const nilai = this.getAttribute('data-nilai');

                    // Set form action ke update route
                    form.action = `{{ url('admin/aras/subkriteria') }}/${id}`;
                    formMethod.value = 'PUT';
                    
                    // Set input values
                    selectKriteria.value = kriteriaId;
                    inputKeterangan.value = keterangan;
                    inputNilai.value = nilai;

                    // Ganti UI form ke Edit Mode
                    formTitle.innerHTML = '<i class="bi bi-pencil-square me-2"></i>Edit Sub-Kriteria';
                    formTitle.parentElement.classList.replace('bg-primary', 'bg-warning');
                    formTitle.parentElement.classList.replace('text-white', 'text-dark');
                    btnSubmit.classList.replace('btn-primary', 'btn-warning');
                    btnSubmit.innerText = 'Update Data';
                    btnCancel.classList.remove('d-none');
                });
            });

            // Batal Edit
            btnCancel.addEventListener('click', function() {
                form.action = `{{ route('admin.aras.subkriteria.store') }}`;
                formMethod.value = 'POST';

                form.reset();

                formTitle.innerHTML = '<i class="bi bi-plus-circle me-2"></i>Tambah Sub-Kriteria Baru';
                formTitle.parentElement.classList.replace('bg-warning', 'bg-primary');
                formTitle.parentElement.classList.replace('text-dark', 'text-white');
                btnSubmit.classList.replace('btn-warning', 'btn-primary');
                btnSubmit.innerText = 'Simpan Data';
                btnCancel.classList.add('d-none');
            });
        });
    </script>
</x-app-layout>
