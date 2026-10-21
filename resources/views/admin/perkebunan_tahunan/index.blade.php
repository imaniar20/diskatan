@extends('admin.layouts.app')

@section('Content')
    <style>
        .nav-pills .nav-link.active {
            background-color: #16a34a !important;
            color: #fff !important;
        }
        .nav-pills .nav-link {
            color: #4b5563;
            font-weight: 500;
            padding: 8px 16px;
            border-radius: 8px;
            white-space: nowrap;
        }
        .nav-pills .nav-link:hover {
            background-color: #e8f5e9;
            color: #16a34a;
        }
    </style>

    <!-- Top Action Bar -->
    <div class="card shadow-sm mb-3">
        <div class="card-body py-2 px-3">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div class="d-flex align-items-center gap-2">
                    <h5 class="mb-0 fw-bold text-dark">
                        <i class="bx bx-spa text-success me-1"></i> Data Historis Perkebunan: {{ $selectedKomoditas }}
                    </h5>
                    <span class="badge bg-label-success px-2 py-1">
                        Perkebunan Tahunan
                    </span>
                </div>

                <div class="d-flex flex-wrap align-items-center gap-2">
                    <a href="{{ route('admin-perkebunan-tahunan.export') }}" class="btn btn-outline-success btn-sm">
                        <i class="bx bx-download me-1"></i> Export Excel
                    </a>

                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalImport">
                        <i class="bx bx-upload me-1"></i> Import Excel
                    </button>

                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
                        <i class="bx bx-plus me-1"></i> Tambah Data Tahun
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Nav Tabs Komoditas -->
    <div class="card shadow-sm mb-3">
        <div class="card-body p-2">
            <div class="d-flex gap-2 overflow-auto pb-1" style="scrollbar-width: thin;">
                @foreach ($komoditasList as $kmd)
                    <a class="nav-link {{ $selectedKomoditas == $kmd ? 'active' : '' }}"
                       href="{{ route('admin-perkebunan-tahunan.index', ['komoditas' => $kmd]) }}">
                        <i class="bx bx-spa me-1"></i> {{ $kmd }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- KPI Summary Cards -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card card-border-shadow-primary h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2 pb-1">
                        <div class="avatar me-2">
                            <span class="avatar-initial rounded bg-label-primary"><i class="bx bx-area"></i></span>
                        </div>
                        <h4 class="ms-1 mb-0">{{ number_format($stats['totalTanam']) }} Ha</h4>
                    </div>
                    <p class="mb-0 text-muted">Total Luas Tanam (Kumulatif)</p>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card card-border-shadow-warning h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2 pb-1">
                        <div class="avatar me-2">
                            <span class="avatar-initial rounded bg-label-warning"><i class="bx bx-basket"></i></span>
                        </div>
                        <h4 class="ms-1 mb-0">{{ number_format($stats['totalPanen']) }} Ha</h4>
                    </div>
                    <p class="mb-0 text-muted">Total Luas Panen (Kumulatif)</p>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card card-border-shadow-info h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2 pb-1">
                        <div class="avatar me-2">
                            <span class="avatar-initial rounded bg-label-info"><i class="bx bx-line-chart"></i></span>
                        </div>
                        <h4 class="ms-1 mb-0">{{ $stats['avgProduktivitas'] }} Kw/Ha</h4>
                    </div>
                    <p class="mb-0 text-muted">Rata-rata Produktivitas</p>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card card-border-shadow-success h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2 pb-1">
                        <div class="avatar me-2">
                            <span class="avatar-initial rounded bg-label-success"><i class="bx bx-box"></i></span>
                        </div>
                        <h4 class="ms-1 mb-0">{{ number_format($stats['totalProduksi']) }} Ton</h4>
                    </div>
                    <p class="mb-0 text-muted">Total Produksi (Kumulatif)</p>
                </div>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bx bx-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bx bx-error-circle me-1"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Data Table Card -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="mb-0 fw-bold text-dark">
                <i class="bx bx-table text-success me-1"></i> Tabel Historis Tahunan {{ $selectedKomoditas }}
            </h5>
        </div>
        <div class="table-responsive text-nowrap p-3">
            <table class="table table-striped table-hover datatable align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 5%">No</th>
                        <th class="text-center">Tahun</th>
                        <th class="text-end">Luas Tanam (Ha)</th>
                        <th class="text-end">Luas Panen (Ha)</th>
                        <th class="text-end">Produktivitas (Kw/Ha)</th>
                        <th class="text-end">Produksi (Ton)</th>
                        <th class="text-center" style="width: 15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 1; @endphp
                    @foreach ($items as $item)
                        <tr>
                            <td class="text-center">{{ $no++ }}</td>
                            <td class="text-center fw-bold text-primary">{{ $item->tahun }}</td>
                            <td class="text-end">{{ number_format($item->luas_tanam, 2) }}</td>
                            <td class="text-end">{{ number_format($item->luas_panen, 2) }}</td>
                            <td class="text-end fw-semibold text-info">{{ number_format($item->produktivitas, 2) }}</td>
                            <td class="text-end fw-bold text-success">{{ number_format($item->produksi, 2) }}</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-outline-primary btn-edit-tahunan"
                                    data-bs-toggle="modal" data-bs-target="#modalEdit"
                                    data-id="{{ $item->id }}"
                                    data-tahun="{{ $item->tahun }}"
                                    data-tanam="{{ $item->luas_tanam }}"
                                    data-panen="{{ $item->luas_panen }}"
                                    data-produktivitas="{{ $item->produktivitas }}"
                                    data-produksi="{{ $item->produksi }}">
                                    <i class="bx bx-edit-alt"></i> Edit
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger"
                                    onclick="confirmDelete('{{ route('admin-perkebunan-tahunan.destroy', $item->id) }}', 'Tahun {{ $item->tahun }}')">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Tambah Data -->
    <div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin-perkebunan-tahunan.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="komoditas" value="{{ $selectedKomoditas }}">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title text-white"><i class="bx bx-plus me-1"></i> Tambah Data Tahunan ({{ $selectedKomoditas }})</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tahun</label>
                            <input type="number" name="tahun" class="form-control" placeholder="Contoh: 2026" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Luas Tanam (Ha)</label>
                            <input type="number" step="0.01" name="luas_tanam" class="form-control" value="0">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Luas Panen (Ha)</label>
                            <input type="number" step="0.01" name="luas_panen" class="form-control" value="0">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Produktivitas (Kw/Ha)</label>
                            <input type="number" step="0.01" name="produktivitas" class="form-control" value="0">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Produksi (Ton)</label>
                            <input type="number" step="0.01" name="produksi" class="form-control" value="0">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Data -->
    <div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title text-white"><i class="bx bx-edit me-1"></i> Edit Data Tahunan: <span id="edit_tahun_label"></span></h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Luas Tanam (Ha)</label>
                            <input type="number" step="0.01" name="luas_tanam" id="edit_luas_tanam" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Luas Panen (Ha)</label>
                            <input type="number" step="0.01" name="luas_panen" id="edit_luas_panen" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Produktivitas (Kw/Ha)</label>
                            <input type="number" step="0.01" name="produktivitas" id="edit_produktivitas" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Produksi (Ton)</label>
                            <input type="number" step="0.01" name="produksi" id="edit_produksi" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Import Excel -->
    <div class="modal fade" id="modalImport" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin-perkebunan-tahunan.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title text-white"><i class="bx bx-upload me-1"></i> Import Excel Data Tahunan Perkebunan</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted small">Pilih file Excel (.xlsx atau .xls) untuk memperbarui data Perkebunan Tahunan.</p>
                        <div class="mb-3">
                            <label class="form-label fw-bold">File Excel</label>
                            <input type="file" name="file_excel" class="form-control" accept=".xlsx,.xls" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Mulai Import</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Hidden form for delete -->
    <form id="deleteForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const editButtons = document.querySelectorAll('.btn-edit-tahunan');
            editButtons.forEach(btn => {
                btn.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    const tahun = this.getAttribute('data-tahun');
                    const form = document.getElementById('editForm');
                    form.action = `/admin-perkebunan-tahunan/${id}`;

                    document.getElementById('edit_tahun_label').textContent = tahun;
                    document.getElementById('edit_luas_tanam').value = this.getAttribute('data-tanam');
                    document.getElementById('edit_luas_panen').value = this.getAttribute('data-panen');
                    document.getElementById('edit_produktivitas').value = this.getAttribute('data-produktivitas');
                    document.getElementById('edit_produksi').value = this.getAttribute('data-produksi');
                });
            });
        });

        function confirmDelete(url, info) {
            if (confirm(`Yakin ingin menghapus data ${info}?`)) {
                const form = document.getElementById('deleteForm');
                form.action = url;
                form.submit();
            }
        }
    </script>
@endsection
