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
                        <i class="bx bx-sun text-warning me-1"></i> Data Tanaman Pangan: {{ $selectedKomoditas }}
                    </h5>
                    <span class="badge bg-label-warning px-2 py-1">
                        Tanaman Pangan
                    </span>
                </div>

                <!-- Tombol Export, Import, Tambah -->
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <a href="{{ route('admin-tanaman-pangan.export') }}" class="btn btn-outline-success btn-sm">
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
                       href="{{ route('admin-tanaman-pangan.index', ['komoditas' => $kmd]) }}">
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
                        <h4 class="ms-1 mb-0">{{ number_format($stats['avgProduktivitas'], 2) }} Kw/Ha</h4>
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
                            <span class="avatar-initial rounded bg-label-success"><i class="bx bx-cube"></i></span>
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

    <!-- Grafik Tren Tahunan -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-bold text-success">
                <i class="bx bx-line-chart me-1"></i> Grafik Perkembangan Tahunan: {{ $selectedKomoditas }}
            </h6>
            <span class="badge bg-label-success">2013 - {{ end($chart['categories']) ?: 'Terbaru' }}</span>
        </div>
        <div class="card-body">
            <div id="chartTanamanPangan"></div>
        </div>
    </div>

    <!-- Data Table Card -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h5 class="mb-0">Rincian Data Tahunan: {{ $selectedKomoditas }}</h5>
                <small class="text-muted">Satuan: Luas (Ha), Produktivitas (Kw/Ha), Produksi (Ton)</small>
            </div>
            <div>
                <span class="badge bg-label-primary px-3 py-2">
                    Total {{ $items->count() }} Tahun Tercatat
                </span>
            </div>
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
                    @php $i = 1; @endphp
                    @foreach ($items as $row)
                        <tr>
                            <td class="text-center">{{ $i++ }}</td>
                            <td class="text-center fw-bold text-success">{{ $row->tahun }}</td>
                            <td class="text-end">{{ number_format($row->luas_tanam, 0) }}</td>
                            <td class="text-end">{{ number_format($row->luas_panen, 0) }}</td>
                            <td class="text-end fw-semibold text-primary">{{ number_format($row->produktivitas, 2) }}</td>
                            <td class="text-end fw-bold text-success">{{ number_format($row->produksi, 0) }}</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-warning text-white btn-edit"
                                    data-bs-toggle="modal" data-bs-target="#modalEdit"
                                    data-id="{{ $row->id }}"
                                    data-tahun="{{ $row->tahun }}"
                                    data-luas_tanam="{{ $row->luas_tanam }}"
                                    data-luas_panen="{{ $row->luas_panen }}"
                                    data-produktivitas="{{ $row->produktivitas }}"
                                    data-produksi="{{ $row->produksi }}">
                                    <i class="bx bx-edit"></i> Edit
                                </button>
                                <button type="button" class="btn btn-sm btn-danger text-white"
                                    onclick="confirmDelete('{{ route('admin-tanaman-pangan.destroy', $row->id) }}', '{{ $row->tahun }}')">
                                    <i class="bx bx-trash"></i> Hapus
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
            <form class="modal-content" action="{{ route('admin-tanaman-pangan.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title text-white"><i class="bx bx-upload me-1"></i> Import Data Excel Tanaman Pangan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info py-2 small mb-3">
                        <i class="bx bx-info-circle me-1"></i>
                        Format file Excel harus berisi 7 sheet komoditas (<code>padi</code>, <code>jagung</code>, <code>kedele</code>, <code>k tanah</code>, <code>k hijau</code>, <code>ubi kayu</code>, <code>ubi jalar</code>).
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Opsi Pembaruan Tahun</label>
                        <select name="tahun" class="form-select">
                            <option value="ALL">Perbarui Semua Tahun dari File Excel</option>
                            @foreach ($availableYears as $yr)
                                <option value="{{ $yr }}">Khusus Tahun {{ $yr }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted">Data lama pada tahun yang dipilih akan digantikan (replace) dengan data baru dari file.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Pilih File Excel (.xls / .xlsx) <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" name="file_excel" accept=".xls,.xlsx" required>
                    </div>

                    <div class="card bg-lighter border p-2 small">
                        <span class="text-muted">
                            <i class="bx bx-bulb text-warning me-1"></i> <strong>Tips:</strong>
                            Anda dapat mengunduh seluruh data saat ini melalui tombol <strong>Export Excel</strong>, melakukan pengeditan di Microsoft Excel, lalu mengunggahnya kembali.
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

    <!-- Modal Tambah Data Tahun -->
    <div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" action="{{ route('admin-tanaman-pangan.store') }}" method="POST">
                @csrf
                <input type="hidden" name="komoditas" value="{{ $selectedKomoditas }}">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Data {{ $selectedKomoditas }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Tahun <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="tahun" value="{{ (int)date('Y') }}" min="2000" max="2060" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Luas Tanam (Ha)</label>
                            <input type="number" step="0.01" class="form-control" name="luas_tanam" value="0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Luas Panen (Ha)</label>
                            <input type="number" step="0.01" class="form-control" name="luas_panen" value="0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Produksi (Ton)</label>
                            <input type="number" step="0.01" class="form-control" name="produksi" value="0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Produktivitas (Kw/Ha)</label>
                            <input type="number" step="0.01" class="form-control" name="produktivitas" value="0" placeholder="0 = otomatis">
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
        <div class="modal-dialog">
            <form class="modal-content" id="formEdit" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data: {{ $selectedKomoditas }} (<span id="edit_tahun_badge"></span>)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Luas Tanam (Ha)</label>
                            <input type="number" step="0.01" class="form-control" id="edit_luas_tanam" name="luas_tanam_edit">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Luas Panen (Ha)</label>
                            <input type="number" step="0.01" class="form-control" id="edit_luas_panen" name="luas_panen_edit">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Produksi (Ton)</label>
                            <input type="number" step="0.01" class="form-control" id="edit_produksi" name="produksi_edit">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Produktivitas (Kw/Ha)</label>
                            <input type="number" step="0.01" class="form-control" id="edit_produktivitas" name="produktivitas_edit">
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

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Inisialisasi Grafik ApexCharts
            const categories = @json($chart['categories'] ?? []);
            const tanamData = @json($chart['tanam'] ?? []);
            const panenData = @json($chart['panen'] ?? []);
            const produksiData = @json($chart['produksi'] ?? []);

            const options = {
                series: [
                    { name: 'Luas Tanam (Ha)', type: 'column', data: tanamData },
                    { name: 'Luas Panen (Ha)', type: 'column', data: panenData },
                    { name: 'Produksi (Ton)', type: 'line', data: produksiData }
                ],
                chart: {
                    height: 350,
                    type: 'line',
                    toolbar: { show: true }
                },
                stroke: {
                    width: [0, 0, 3],
                    curve: 'smooth'
                },
                plotOptions: {
                    bar: {
                        columnWidth: '50%',
                        borderRadius: 3
                    }
                },
                colors: ['#f59e0b', '#3b82f6', '#10b981'],
                xaxis: {
                    categories: categories,
                    labels: { style: { fontSize: '12px' } }
                },
                yaxis: [
                    {
                        title: { text: 'Luas (Ha)' }
                    },
                    {
                        opposite: true,
                        title: { text: 'Produksi (Ton)' }
                    }
                ],
                tooltip: {
                    shared: true,
                    intersect: false
                },
                legend: {
                    position: 'top'
                }
            };

            const chartEl = document.querySelector("#chartTanamanPangan");
            if (chartEl && categories.length > 0) {
                const chart = new ApexCharts(chartEl, options);
                chart.render();
            }

            // Edit modal data filling
            const editButtons = document.querySelectorAll('.btn-edit');
            const formEdit = document.getElementById('formEdit');

            editButtons.forEach(btn => {
                btn.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    formEdit.action = `/admin-tanaman-pangan/${id}`;

                    const tahun = this.getAttribute('data-tahun');
                    document.getElementById('edit_tahun_badge').textContent = `Tahun ${tahun}`;
                    document.getElementById('edit_luas_tanam').value = this.getAttribute('data-luas_tanam') || 0;
                    document.getElementById('edit_luas_panen').value = this.getAttribute('data-luas_panen') || 0;
                    document.getElementById('edit_produktivitas').value = this.getAttribute('data-produktivitas') || 0;
                    document.getElementById('edit_produksi').value = this.getAttribute('data-produksi') || 0;
                });
            });
        });

        function confirmDelete(url, tahun) {
            if (confirm(`Yakin ingin menghapus data Tahun ${tahun}?`)) {
                const form = document.getElementById('deleteForm');
                form.action = url;
                form.submit();
            }
        }
    </script>
@endsection
