@extends('admin.layouts.app')

@section('Content')
    <style>
        .badge-capaian-high {
            background-color: #d1fae5;
            color: #065f46;
            font-weight: 600;
        }
        .badge-capaian-medium {
            background-color: #fef3c7;
            color: #92400e;
            font-weight: 600;
        }
        .badge-capaian-low {
            background-color: #fee2e2;
            color: #991b1b;
            font-weight: 600;
        }
        .nav-pills .nav-link.active {
            background-color: #16a34a !important;
            color: #fff !important;
        }
        .nav-pills .nav-link {
            color: #4b5563;
            font-weight: 500;
        }
        .table-sticky th {
            white-space: nowrap;
        }
    </style>

    <!-- Top Action Bar: Filter Tahun & Tombol Export/Import -->
    <div class="card shadow-sm mb-3">
        <div class="card-body py-2 px-3">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                <!-- Filter Tahun -->
                <form method="GET" action="{{ route('admin-padi-palawija.index') }}" class="d-flex align-items-center gap-2">
                    <input type="hidden" name="komoditas" value="{{ $selectedKomoditas }}">
                    <label class="fw-bold text-dark text-nowrap mb-0">
                        <i class="bx bx-calendar text-success me-1"></i> Tahun Data:
                    </label>
                    <select name="tahun" class="form-select form-select-sm fw-bold border-success text-success" style="width: 120px;" onchange="this.form.submit()">
                        @foreach ($availableYears as $yr)
                            <option value="{{ $yr }}" {{ $selectedTahun == $yr ? 'selected' : '' }}>{{ $yr }}</option>
                        @endforeach
                    </select>
                </form>

                <!-- Tombol Export & Import Excel -->
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <a href="{{ route('admin-padi-palawija.export', ['tahun' => $selectedTahun]) }}" class="btn btn-outline-success btn-sm">
                        <i class="bx bx-download me-1"></i> Export Excel ({{ $selectedTahun }})
                    </a>

                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalImport">
                        <i class="bx bx-upload me-1"></i> Import Excel
                    </button>

                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
                        <i class="bx bx-plus me-1"></i> Tambah Kecamatan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Nav Tabs Komoditas -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body p-2">
                    <ul class="nav nav-pills nav-fill flex-column flex-sm-row gap-1">
                        @foreach ($komoditasList as $komoditas)
                            <li class="nav-item">
                                <a class="nav-link {{ $selectedKomoditas == $komoditas ? 'active' : '' }}"
                                   href="{{ route('admin-padi-palawija.index', ['komoditas' => $komoditas, 'tahun' => $selectedTahun]) }}">
                                    <i class="bx bx-spa me-1"></i> {{ $komoditas }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
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
                            <span class="avatar-initial rounded bg-label-primary"><i class="bx bx-map-alt"></i></span>
                        </div>
                        <h4 class="ms-1 mb-0">{{ number_format($stats['totalSawah']) }} Ha</h4>
                    </div>
                    <p class="mb-0 text-muted">Total Luas Sawah ({{ $selectedTahun }})</p>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card card-border-shadow-warning h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2 pb-1">
                        <div class="avatar me-2">
                            <span class="avatar-initial rounded bg-label-warning"><i class="bx bx-target-lock"></i></span>
                        </div>
                        <h4 class="ms-1 mb-0">{{ number_format($stats['totalTarget']) }} Ha</h4>
                    </div>
                    <p class="mb-0 text-muted">Target {{ $selectedKomoditas }} ({{ $selectedTahun }})</p>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card card-border-shadow-success h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2 pb-1">
                        <div class="avatar me-2">
                            <span class="avatar-initial rounded bg-label-success"><i class="bx bx-check-circle"></i></span>
                        </div>
                        <h4 class="ms-1 mb-0">{{ number_format($stats['totalRealisasi']) }} Ha</h4>
                    </div>
                    <p class="mb-0 text-muted">Realisasi (MH + MK)</p>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card card-border-shadow-info h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2 pb-1">
                        <div class="avatar me-2">
                            <span class="avatar-initial rounded bg-label-info"><i class="bx bx-trending-up"></i></span>
                        </div>
                        <h4 class="ms-1 mb-0">{{ $stats['persentase'] }}%</h4>
                    </div>
                    <p class="mb-0 text-muted">Persentase Capaian Target</p>
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

    @if (isset($errors) && $errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Data Table Card -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h5 class="mb-0">Data Target & Realisasi {{ $selectedKomoditas }} Tahun {{ $selectedTahun }}</h5>
                <small class="text-muted">Mencakup 32 Kecamatan di Kabupaten Kuningan</small>
            </div>
            <div>
                <span class="badge bg-label-success fs-6 px-3 py-2">
                    <i class="bx bx-calendar me-1"></i> Tahun {{ $selectedTahun }}
                </span>
            </div>
        </div>

        <div class="table-responsive text-nowrap p-3">
            <table class="table table-striped table-hover datatable align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 4%">No</th>
                        <th>Kecamatan</th>
                        <th class="text-end">Luas Sawah (Ha)</th>
                        <th class="text-end">Target Okmar (Ha)</th>
                        <th class="text-end">Target Asep (Ha)</th>
                        <th class="text-end">Target Total (Ha)</th>
                        <th class="text-end">Realisasi MH (Ha)</th>
                        <th class="text-end">Realisasi MK (Ha)</th>
                        <th class="text-end">Total Realisasi (Ha)</th>
                        <th class="text-center">Capaian</th>
                        <th class="text-center" style="width: 12%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php $i = 1; @endphp
                    @foreach ($items as $row)
                        <tr>
                            <td class="text-center">{{ $row->no_urut ?: $i++ }}</td>
                            <td><strong>{{ $row->kecamatan }}</strong></td>
                            <td class="text-end">{{ number_format($row->luas_sawah, 0) }}</td>
                            <td class="text-end text-muted">{{ number_format($row->target_okmar, 0) }}</td>
                            <td class="text-end text-muted">{{ number_format($row->target_asep, 0) }}</td>
                            <td class="text-end text-primary fw-semibold">{{ number_format($row->target_total, 0) }}</td>
                            <td class="text-end">{{ number_format($row->jumlah_mh, 0) }}</td>
                            <td class="text-end">{{ number_format($row->jumlah_mk, 0) }}</td>
                            <td class="text-end text-success fw-bold">{{ number_format($row->total_realisasi, 0) }}</td>
                            <td class="text-center">
                                @php
                                    $badgeClass = 'badge-capaian-low';
                                    if ($row->persentase_capaian >= 100) {
                                        $badgeClass = 'badge-capaian-high';
                                    } elseif ($row->persentase_capaian >= 50) {
                                        $badgeClass = 'badge-capaian-medium';
                                    }
                                @endphp
                                <span class="badge {{ $badgeClass }} px-2 py-1">
                                    {{ $row->persentase_capaian }}%
                                </span>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-warning text-white btn-edit"
                                    data-bs-toggle="modal" data-bs-target="#modalEdit"
                                    data-id="{{ $row->id }}"
                                    data-kecamatan="{{ $row->kecamatan }}"
                                    data-luas_sawah="{{ $row->luas_sawah }}"
                                    data-target_okmar="{{ $row->target_okmar }}"
                                    data-target_asep="{{ $row->target_asep }}"
                                    data-realisasi_okt="{{ $row->realisasi_okt }}"
                                    data-realisasi_nop="{{ $row->realisasi_nop }}"
                                    data-realisasi_des="{{ $row->realisasi_des }}"
                                    data-realisasi_jan="{{ $row->realisasi_jan }}"
                                    data-realisasi_feb="{{ $row->realisasi_feb }}"
                                    data-realisasi_mar="{{ $row->realisasi_mar }}"
                                    data-realisasi_apr="{{ $row->realisasi_apr }}"
                                    data-realisasi_mei="{{ $row->realisasi_mei }}"
                                    data-realisasi_jun="{{ $row->realisasi_jun }}"
                                    data-realisasi_jul="{{ $row->realisasi_jul }}"
                                    data-realisasi_ags="{{ $row->realisasi_ags }}"
                                    data-realisasi_sep="{{ $row->realisasi_sep }}">
                                    <i class="bx bx-edit"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-danger text-white"
                                    onclick="confirmDelete('{{ route('admin-padi-palawija.destroy', $row->id) }}', '{{ $row->kecamatan }}')">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Import Excel -->
    <div class="modal fade" id="modalImport" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" action="{{ route('admin-padi-palawija.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title text-white"><i class="bx bx-upload me-1"></i> Import Data Excel Padi & Palawija</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info py-2 small mb-3">
                        <i class="bx bx-info-circle me-1"></i>
                        File Excel harus memiliki format 7 sheet komoditas (<code>padi</code>, <code>jagung</code>, <code>kedele</code>, <code>k.tanah</code>, <code>k.hijau</code>, <code>u.kayu</code>, <code>u.jalar</code>).
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Tentukan Tahun Data <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="tahun" value="{{ $selectedTahun }}" min="2020" max="2050" required>
                        <small class="text-muted">Data untuk tahun ini akan digantikan (replace) dengan data baru dari file Excel.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Pilih File Excel (.xls / .xlsx) <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" name="file_excel" accept=".xls,.xlsx" required>
                    </div>

                    <div class="card bg-lighter border p-2 small">
                        <span class="text-muted">
                            <i class="bx bx-bulb text-warning me-1"></i> <strong>Tips:</strong>
                            Ingin format yang pasti cocok? Klik tombol <strong>Export Excel ({{ $selectedTahun }})</strong> terlebih dahulu, lalu edit isinya di Microsoft Excel dan unggah kembali di sini.
                        </span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="bx bx-check me-1"></i> Mulai Import & Replace</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Tambah -->
    <div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form class="modal-content" action="{{ route('admin-padi-palawija.store') }}" method="POST">
                @csrf
                <input type="hidden" name="tahun" value="{{ $selectedTahun }}">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Data Target & Realisasi Tanam (Tahun {{ $selectedTahun }})</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Komoditas</label>
                            <input type="text" class="form-control" name="komoditas" value="{{ $selectedKomoditas }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nama Kecamatan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control text-uppercase" name="kecamatan" required placeholder="Contoh: KUNINGAN">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Luas Sawah (Ha)</label>
                            <input type="number" step="0.01" class="form-control" name="luas_sawah" value="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Target Okmar / MH (Ha)</label>
                            <input type="number" step="0.01" class="form-control" name="target_okmar" value="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Target Asep / MK (Ha)</label>
                            <input type="number" step="0.01" class="form-control" name="target_asep" value="0">
                        </div>

                        <!-- Realisasi MH -->
                        <div class="col-12 mt-4">
                            <h6 class="text-primary border-bottom pb-2 mb-2"><i class="bx bx-cloud-rain me-1"></i> Realisasi Musim Hujan (MH)</h6>
                        </div>
                        <div class="col-md-2 col-4">
                            <label class="form-label">Okt</label>
                            <input type="number" step="0.01" class="form-control" name="realisasi_okt" value="0">
                        </div>
                        <div class="col-md-2 col-4">
                            <label class="form-label">Nop</label>
                            <input type="number" step="0.01" class="form-control" name="realisasi_nop" value="0">
                        </div>
                        <div class="col-md-2 col-4">
                            <label class="form-label">Des</label>
                            <input type="number" step="0.01" class="form-control" name="realisasi_des" value="0">
                        </div>
                        <div class="col-md-2 col-4">
                            <label class="form-label">Jan</label>
                            <input type="number" step="0.01" class="form-control" name="realisasi_jan" value="0">
                        </div>
                        <div class="col-md-2 col-4">
                            <label class="form-label">Feb</label>
                            <input type="number" step="0.01" class="form-control" name="realisasi_feb" value="0">
                        </div>
                        <div class="col-md-2 col-4">
                            <label class="form-label">Mar</label>
                            <input type="number" step="0.01" class="form-control" name="realisasi_mar" value="0">
                        </div>

                        <!-- Realisasi MK -->
                        <div class="col-12 mt-4">
                            <h6 class="text-warning border-bottom pb-2 mb-2"><i class="bx bx-sun me-1"></i> Realisasi Musim Kemarau (MK)</h6>
                        </div>
                        <div class="col-md-2 col-4">
                            <label class="form-label">Apr</label>
                            <input type="number" step="0.01" class="form-control" name="realisasi_apr" value="0">
                        </div>
                        <div class="col-md-2 col-4">
                            <label class="form-label">Mei</label>
                            <input type="number" step="0.01" class="form-control" name="realisasi_mei" value="0">
                        </div>
                        <div class="col-md-2 col-4">
                            <label class="form-label">Jun</label>
                            <input type="number" step="0.01" class="form-control" name="realisasi_jun" value="0">
                        </div>
                        <div class="col-md-2 col-4">
                            <label class="form-label">Jul</label>
                            <input type="number" step="0.01" class="form-control" name="realisasi_jul" value="0">
                        </div>
                        <div class="col-md-2 col-4">
                            <label class="form-label">Ags</label>
                            <input type="number" step="0.01" class="form-control" name="realisasi_ags" value="0">
                        </div>
                        <div class="col-md-2 col-4">
                            <label class="form-label">Sep</label>
                            <input type="number" step="0.01" class="form-control" name="realisasi_sep" value="0">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit -->
    <div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form class="modal-content" id="formEdit" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data Target & Realisasi Tanam</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Kecamatan</label>
                            <input type="text" class="form-control text-uppercase" id="edit_kecamatan" name="kecamatan_edit" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Luas Sawah (Ha)</label>
                            <input type="number" step="0.01" class="form-control" id="edit_luas_sawah" name="luas_sawah_edit">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Target Okmar / MH (Ha)</label>
                            <input type="number" step="0.01" class="form-control" id="edit_target_okmar" name="target_okmar_edit">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Target Asep / MK (Ha)</label>
                            <input type="number" step="0.01" class="form-control" id="edit_target_asep" name="target_asep_edit">
                        </div>

                        <!-- Realisasi MH -->
                        <div class="col-12 mt-4">
                            <h6 class="text-primary border-bottom pb-2 mb-2"><i class="bx bx-cloud-rain me-1"></i> Realisasi Musim Hujan (MH)</h6>
                        </div>
                        <div class="col-md-2 col-4">
                            <label class="form-label">Okt</label>
                            <input type="number" step="0.01" class="form-control" id="edit_realisasi_okt" name="realisasi_okt_edit">
                        </div>
                        <div class="col-md-2 col-4">
                            <label class="form-label">Nop</label>
                            <input type="number" step="0.01" class="form-control" id="edit_realisasi_nop" name="realisasi_nop_edit">
                        </div>
                        <div class="col-md-2 col-4">
                            <label class="form-label">Des</label>
                            <input type="number" step="0.01" class="form-control" id="edit_realisasi_des" name="realisasi_des_edit">
                        </div>
                        <div class="col-md-2 col-4">
                            <label class="form-label">Jan</label>
                            <input type="number" step="0.01" class="form-control" id="edit_realisasi_jan" name="realisasi_jan_edit">
                        </div>
                        <div class="col-md-2 col-4">
                            <label class="form-label">Feb</label>
                            <input type="number" step="0.01" class="form-control" id="edit_realisasi_feb" name="realisasi_feb_edit">
                        </div>
                        <div class="col-md-2 col-4">
                            <label class="form-label">Mar</label>
                            <input type="number" step="0.01" class="form-control" id="edit_realisasi_mar" name="realisasi_mar_edit">
                        </div>

                        <!-- Realisasi MK -->
                        <div class="col-12 mt-4">
                            <h6 class="text-warning border-bottom pb-2 mb-2"><i class="bx bx-sun me-1"></i> Realisasi Musim Kemarau (MK)</h6>
                        </div>
                        <div class="col-md-2 col-4">
                            <label class="form-label">Apr</label>
                            <input type="number" step="0.01" class="form-control" id="edit_realisasi_apr" name="realisasi_apr_edit">
                        </div>
                        <div class="col-md-2 col-4">
                            <label class="form-label">Mei</label>
                            <input type="number" step="0.01" class="form-control" id="edit_realisasi_mei" name="realisasi_mei_edit">
                        </div>
                        <div class="col-md-2 col-4">
                            <label class="form-label">Jun</label>
                            <input type="number" step="0.01" class="form-control" id="edit_realisasi_jun" name="realisasi_jun_edit">
                        </div>
                        <div class="col-md-2 col-4">
                            <label class="form-label">Jul</label>
                            <input type="number" step="0.01" class="form-control" id="edit_realisasi_jul" name="realisasi_jul_edit">
                        </div>
                        <div class="col-md-2 col-4">
                            <label class="form-label">Ags</label>
                            <input type="number" step="0.01" class="form-control" id="edit_realisasi_ags" name="realisasi_ags_edit">
                        </div>
                        <div class="col-md-2 col-4">
                            <label class="form-label">Sep</label>
                            <input type="number" step="0.01" class="form-control" id="edit_realisasi_sep" name="realisasi_sep_edit">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Hidden Form Delete -->
    <form id="deleteForm" method="POST" action="" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Edit modal data filling
            const editButtons = document.querySelectorAll('.btn-edit');
            const formEdit = document.getElementById('formEdit');

            editButtons.forEach(btn => {
                btn.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    formEdit.action = `/admin-padi-palawija/${id}`;

                    document.getElementById('edit_kecamatan').value = this.getAttribute('data-kecamatan') || '';
                    document.getElementById('edit_luas_sawah').value = this.getAttribute('data-luas_sawah') || 0;
                    document.getElementById('edit_target_okmar').value = this.getAttribute('data-target_okmar') || 0;
                    document.getElementById('edit_target_asep').value = this.getAttribute('data-target_asep') || 0;

                    document.getElementById('edit_realisasi_okt').value = this.getAttribute('data-realisasi_okt') || 0;
                    document.getElementById('edit_realisasi_nop').value = this.getAttribute('data-realisasi_nop') || 0;
                    document.getElementById('edit_realisasi_des').value = this.getAttribute('data-realisasi_des') || 0;
                    document.getElementById('edit_realisasi_jan').value = this.getAttribute('data-realisasi_jan') || 0;
                    document.getElementById('edit_realisasi_feb').value = this.getAttribute('data-realisasi_feb') || 0;
                    document.getElementById('edit_realisasi_mar').value = this.getAttribute('data-realisasi_mar') || 0;

                    document.getElementById('edit_realisasi_apr').value = this.getAttribute('data-realisasi_apr') || 0;
                    document.getElementById('edit_realisasi_mei').value = this.getAttribute('data-realisasi_mei') || 0;
                    document.getElementById('edit_realisasi_jun').value = this.getAttribute('data-realisasi_jun') || 0;
                    document.getElementById('edit_realisasi_jul').value = this.getAttribute('data-realisasi_jul') || 0;
                    document.getElementById('edit_realisasi_ags').value = this.getAttribute('data-realisasi_ags') || 0;
                    document.getElementById('edit_realisasi_sep').value = this.getAttribute('data-realisasi_sep') || 0;
                });
            });
        });

        function confirmDelete(url, kecamatan) {
            if (confirm(`Yakin ingin menghapus data Kecamatan ${kecamatan}?`)) {
                const form = document.getElementById('deleteForm');
                form.action = url;
                form.submit();
            }
        }
    </script>
@endsection
