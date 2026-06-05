<x-app-layout>
    <div class="container py-5">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.aras.index') }}">Perhitungan ARAS</a></li>
                <li class="breadcrumb-item active" aria-current="page">Kelola Bobot</li>
            </ol>
        </nav>

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card shadow border-0 rounded-4 overflow-hidden">
            <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold"><i class="bi bi-sliders me-2"></i>Kelola Bobot & Tipe Kriteria</h5>
                <a href="{{ route('admin.aras.index') }}" class="btn btn-sm btn-light fw-bold text-primary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
            <div class="card-body p-4">
                <p class="text-secondary small">
                    Ubah bobot preferensi kriteria global untuk perhitungan ARAS resmi dinas. 
                    Input dapat berupa desimal (misal 0.20) atau persentase (misal 20). 
                    <strong>Total bobot wajib berjumlah 1.0 (jika desimal) atau 100 (jika persen).</strong>
                </p>

                <form action="{{ route('admin.aras.updateKriteria') }}" method="POST" id="formKriteria" onsubmit="return confirm('Apakah Anda yakin ingin menyimpan perubahan bobot dan tipe kriteria ini?');">
                    @csrf
                    
                    <div class="table-responsive mb-4">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th width="10%">Kode</th>
                                    <th width="25%">Nama Kriteria</th>
                                    <th width="20%">Tipe Kriteria</th>
                                    <th width="25%">Bobot Preferensi</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($kriteria as $k)
                                    <tr>
                                        <td class="fw-bold text-primary">{{ $k->kode }}</td>
                                        <td>
                                            <span class="fw-semibold">{{ $k->nama_kriteria }}</span>
                                            <br>
                                            <small class="text-muted">Satuan: {{ $k->satuan ?? '-' }}</small>
                                        </td>
                                        <td>
                                            <select name="tipe_{{ $k->id }}" class="form-select">
                                                <option value="benefit" {{ $k->tipe == 'benefit' ? 'selected' : '' }}>Benefit (Semakin tinggi semakin baik)</option>
                                                <option value="cost" {{ $k->tipe == 'cost' ? 'selected' : '' }}>Cost (Semakin rendah semakin baik)</option>
                                            </select>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <input type="number" step="0.0001" min="0" 
                                                       name="bobot_{{ $k->id }}" 
                                                       id="bobot_{{ $k->id }}" 
                                                       class="form-control weight-input fw-bold text-end text-primary" 
                                                       value="{{ $k->bobot }}" 
                                                       required>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="small text-secondary">{{ $k->keterangan }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Status Bar Total Bobot -->
                    <div class="p-3 bg-light rounded-3 mb-4 border d-flex justify-content-between align-items-center">
                        <div>
                            <span class="fw-bold text-secondary">Total Bobot Terinput saat ini:</span>
                            <span class="fs-4 fw-bold ms-2 text-primary" id="totalWeightVal">0.0</span>
                        </div>
                        <div class="fw-bold fs-6" id="validationStatus">
                            <!-- JS will inject validation status here -->
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.aras.index') }}" class="btn btn-secondary px-4">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-primary px-4 fw-bold" id="btnSave">
                            <i class="bi bi-save"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript Real-time Sum Validation -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('.weight-input');
            const totalText = document.getElementById('totalWeightVal');
            const statusText = document.getElementById('validationStatus');
            const btnSave = document.getElementById('btnSave');

            function checkSum() {
                let total = 0.0;
                inputs.forEach(input => {
                    total += parseFloat(input.value || 0);
                });

                // Format total to maximum 4 decimal places
                total = Math.round(total * 10000) / 10000;
                totalText.innerText = total;

                // Validate if it is equal to 1.0 or 100
                const isOne = Math.abs(total - 1.0) < 0.0001;
                const isHundred = Math.abs(total - 100.0) < 0.0001;

                if (isOne || isHundred) {
                    statusText.innerHTML = '<span class="text-success"><i class="bi bi-check-circle-fill"></i> Total valid (Pas ' + total + ')</span>';
                    btnSave.removeAttribute('disabled');
                } else {
                    statusText.innerHTML = '<span class="text-danger"><i class="bi bi-exclamation-triangle-fill"></i> Harus pas 1.0 atau 100</span>';
                    btnSave.setAttribute('disabled', 'disabled');
                }
            }

            inputs.forEach(input => {
                input.addEventListener('input', checkSum);
            });

            checkSum(); // Run initially
        });
    </script>
</x-app-layout>
