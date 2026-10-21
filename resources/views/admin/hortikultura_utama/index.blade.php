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
        .nav-pills-komoditas .nav-link.active {
            background-color: #16a34a !important;
            color: #fff !important;
            box-shadow: 0 4px 6px -1px rgba(22, 163, 74, 0.25);
        }
        .nav-pills-komoditas .nav-link {
            color: #4b5563;
            font-weight: 600;
            border-radius: 8px;
            padding: 8px 14px;
            transition: all 0.2s ease;
        }
        .nav-pills-komoditas .nav-link:hover:not(.active) {
            background-color: #f0fdf4;
            color: #15803d;
        }
        .category-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 30px;
            font-weight: 600;
            font-size: 0.875rem;
            text-decoration: none;
            transition: all 0.2s ease;
            border: 1px solid #e5e7eb;
            background: #fff;
            color: #4b5563;
        }
        .category-pill:hover {
            border-color: #93c5fd;
            color: #1e40af;
            background: #f8fafc;
        }
        .category-pill.active {
            background: #1e293b;
            color: #fff;
            border-color: #1e293b;
            box-shadow: 0 4px 6px -1px rgba(30, 41, 59, 0.2);
        }
        .category-pill.active.pill-tanam { background: #16a34a; border-color: #16a34a; }
        .category-pill.active.pill-panen { background: #2563eb; border-color: #2563eb; }
        .category-pill.active.pill-produksi { background: #d97706; border-color: #d97706; }
        .category-pill.active.pill-produktivitas { background: #0891b2; border-color: #0891b2; }
        .category-pill.active.pill-puso { background: #dc2626; border-color: #dc2626; }
    </style>

    <!-- Top Action Bar -->
    <div class="card shadow-sm mb-3">
        <div class="card-body py-2 px-3">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                <form method="GET" action="{{ route('admin-hortikultura-utama.index') }}" class="d-flex align-items-center gap-2">
                    <input type="hidden" name="komoditas" value="{{ $selectedKomoditas }}">
                    <input type="hidden" name="kategori" value="{{ $selectedKategori }}">
                    <label class="fw-bold text-dark text-nowrap mb-0">
                        <i class="bx bx-calendar text-success me-1"></i> Tahun Data:
                    </label>
                    <select name="tahun" class="form-select form-select-sm fw-bold border-success text-success" style="width: 120px;" onchange="this.form.submit()">
                        @foreach ($availableYears as $yr)
                            <option value="{{ $yr }}" {{ $selectedTahun == $yr ? 'selected' : '' }}>{{ $yr }}</option>
                        @endforeach
                    </select>
                </form>

                <div class="d-flex flex-wrap align-items-center gap-2">
                    <a href="{{ route('admin-hortikultura-utama.export', ['tahun' => $selectedTahun]) }}" class="btn btn-outline-success btn-sm">
                        <i class="bx bx-download me-1"></i> Export Excel ({{ $selectedTahun }})
                    </a>

                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalImport">
                        <i class="bx bx-upload me-1"></i> Import Excel
                    </button>

                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
                        <i class="bx bx-plus me-1"></i> Tambah Data ({{ $activeConfig['label'] }})
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Nav Tabs Komoditas -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body p-2">
                    <div class="d-flex gap-2 overflow-auto pb-1" style="scrollbar-width: thin;">
                        @foreach ($komoditasList as $kmd)
                            <a class="nav-link {{ $selectedKomoditas == $kmd ? 'active' : '' }} btn btn-sm btn-outline-success px-3 py-2 text-nowrap"
                               href="{{ route('admin-hortikultura-utama.index', ['komoditas' => $kmd, 'kategori' => $selectedKategori, 'tahun' => $selectedTahun]) }}">
                                <i class="bx bx-leaf me-1"></i> {{ $kmd }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Kategori Selector -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body py-2 px-3">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                        <div class="d-flex align-items-center gap-1">
                            <span class="text-muted fw-bold small me-2 text-uppercase">
                                <i class="bx bx-layer text-primary me-1"></i> Kategori Data:
                            </span>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach ($kategoriConfig as $katKey => $kat)
                                    <a href="{{ route('admin-hortikultura-utama.index', ['komoditas' => $selectedKomoditas, 'kategori' => $katKey, 'tahun' => $selectedTahun]) }}"
                                       class="category-pill pill-{{ $katKey }} {{ $selectedKategori === $katKey ? 'active' : '' }}">
                                        <i class="bx {{ $kat['icon'] }}"></i>
                                        <span>{{ $kat['label'] }}</span>
                                        <span class="badge bg-white text-dark ms-1 opacity-75" style="font-size: 0.7rem;">{{ $kat['satuan'] }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <span class="badge bg-label-dark fs-7 py-1 px-2">
                                <i class="bx bx-check-double text-success me-1"></i>
                                Komoditas: <strong>{{ $selectedKomoditas }}</strong> &bull; Indikator: <strong>{{ $activeConfig['label'] }}</strong>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- KPI Summary Cards -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card card-border-shadow-primary h-100 shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2 pb-1">
                        <div class="avatar me-2">
                            <span class="avatar-initial rounded {{ $stats['card1_badge'] }}"><i class="bx {{ $stats['card1_icon'] }}"></i></span>
                        </div>
                        <h4 class="ms-1 mb-0 fw-bold">{{ $stats['card1_value'] }}</h4>
                    </div>
                    <p class="mb-0 text-muted small fw-semibold">{{ $stats['card1_title'] }}</p>
                    <small class="text-secondary" style="font-size: 0.75rem;">{{ $stats['card1_sub'] }}</small>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card card-border-shadow-warning h-100 shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2 pb-1">
                        <div class="avatar me-2">
                            <span class="avatar-initial rounded {{ $stats['card2_badge'] }}"><i class="bx {{ $stats['card2_icon'] }}"></i></span>
                        </div>
                        <h4 class="ms-1 mb-0 fw-bold">{{ $stats['card2_value'] }}</h4>
                    </div>
                    <p class="mb-0 text-muted small fw-semibold">{{ $stats['card2_title'] }}</p>
                    <small class="text-secondary" style="font-size: 0.75rem;">{{ $stats['card2_sub'] }}</small>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card card-border-shadow-success h-100 shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2 pb-1">
                        <div class="avatar me-2">
                            <span class="avatar-initial rounded {{ $stats['card3_badge'] }}"><i class="bx {{ $stats['card3_icon'] }}"></i></span>
                        </div>
                        <h4 class="ms-1 mb-0 fw-bold">{{ $stats['card3_value'] }}</h4>
                    </div>
                    <p class="mb-0 text-muted small fw-semibold">{{ $stats['card3_title'] }}</p>
                    <small class="text-secondary" style="font-size: 0.75rem;">{{ $stats['card3_sub'] }}</small>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card card-border-shadow-info h-100 shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2 pb-1">
                        <div class="avatar me-2">
                            <span class="avatar-initial rounded {{ $stats['card4_badge'] }}"><i class="bx {{ $stats['card4_icon'] }}"></i></span>
                        </div>
                        <h4 class="ms-1 mb-0 fw-bold">{{ $stats['card4_value'] }}</h4>
                    </div>
                    <p class="mb-0 text-muted small fw-semibold">{{ $stats['card4_title'] }}</p>
                    <small class="text-secondary" style="font-size: 0.75rem;">{{ $stats['card4_sub'] }}</small>
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
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center flex-wrap gap-2 border-bottom">
            <div>
                <h5 class="mb-0 fw-bold text-dark">
                    <i class="bx {{ $activeConfig['icon'] }} text-success me-1"></i>
                    Data {{ $activeConfig['label'] }} - {{ $selectedKomoditas }} (Tahun {{ $selectedTahun }})
                </h5>
                <small class="text-muted">Mencakup 32 Kecamatan di Kabupaten Kuningan &bull; Satuan: <strong>{{ $activeConfig['satuan'] }}</strong></small>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge {{ $activeConfig['badge'] }} fs-7 px-3 py-2">
                    {{ $activeConfig['label'] }}
                </span>
                <span class="badge bg-label-dark fs-7 px-3 py-2">
                    <i class="bx bx-calendar me-1"></i> {{ $selectedTahun }}
                </span>
            </div>
        </div>

        <div class="table-responsive text-nowrap p-3">
            <table class="table table-striped table-hover datatable align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 4%">No</th>
                        <th>Kecamatan</th>
                        <th class="text-end">Luas Baku (Ha)</th>
                        @if ($selectedKategori !== 'puso')
                            <th class="text-end">{{ $activeConfig['target_sub1_label'] }}</th>
                            <th class="text-end">{{ $activeConfig['target_sub2_label'] }}</th>
                            <th class="text-end">Total Target</th>
                        @endif
                        <th class="text-end">{{ $activeConfig['realisasi_sub1_label'] }}</th>
                        <th class="text-end">{{ $activeConfig['realisasi_sub2_label'] }}</th>
                        <th class="text-end">Total Realisasi</th>
                        @if ($selectedKategori !== 'puso')
                            <th class="text-center">Capaian</th>
                        @endif
                        <th class="text-center" style="width: 14%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php $i = 1; @endphp
                    @foreach ($items as $row)
                        <tr>
                            <td class="text-center">{{ $row->no_urut ?: $i++ }}</td>
                            <td><strong>{{ $row->kecamatan }}</strong></td>
                            <td class="text-end">{{ number_format($row->luas_sawah, 1) }}</td>
                            @if ($selectedKategori !== 'puso')
                                <td class="text-end text-muted">{{ number_format($row->target_okmar, 1) }}</td>
                                <td class="text-end text-muted">{{ number_format($row->target_asep, 1) }}</td>
                                <td class="text-end fw-semibold text-warning">{{ number_format($row->target_total, 1) }}</td>
                            @endif
                            <td class="text-end">{{ number_format($row->jumlah_mh, 1) }}</td>
                            <td class="text-end">{{ number_format($row->jumlah_mk, 1) }}</td>
                            <td class="text-end fw-bold text-success">{{ number_format($row->total_realisasi, 1) }}</td>
                            @if ($selectedKategori !== 'puso')
                                <td class="text-center">
                                    @php
                                        $badgeClass = 'badge-capaian-low';
                                        if ($row->persentase_capaian >= 80) $badgeClass = 'badge-capaian-high';
                                        elseif ($row->persentase_capaian >= 50) $badgeClass = 'badge-capaian-medium';
                                    @endphp
                                    <span class="badge {{ $badgeClass }} px-2 py-1">
                                        {{ number_format($row->persentase_capaian, 1) }}%
                                    </span>
                                </td>
                            @endif
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-outline-info btn-detail-bulan"
                                        data-bs-toggle="modal" data-bs-target="#modalDetailBulanan"
                                        data-kecamatan="{{ $row->kecamatan }}"
                                        data-kategori="{{ $activeConfig['label'] }}"
                                        data-satuan="{{ $activeConfig['satuan'] }}"
                                        data-sub1="{{ $row->jumlah_mh }}"
                                        data-sub2="{{ $row->jumlah_mk }}"
                                        data-total="{{ $row->total_realisasi }}"
                                        data-m1="{{ $row->realisasi_jan }}" data-m2="{{ $row->realisasi_feb }}" data-m3="{{ $row->realisasi_mar }}"
                                        data-m4="{{ $row->realisasi_apr }}" data-m5="{{ $row->realisasi_mei }}" data-m6="{{ $row->realisasi_jun }}"
                                        data-m7="{{ $row->realisasi_jul }}" data-m8="{{ $row->realisasi_ags }}" data-m9="{{ $row->realisasi_sep }}"
                                        data-m10="{{ $row->realisasi_okt }}" data-m11="{{ $row->realisasi_nop }}" data-m12="{{ $row->realisasi_des }}">
                                        <i class="bx bx-calendar-detail"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-primary btn-edit-data"
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
                                        <i class="bx bx-edit-alt"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                        onclick="confirmDelete('{{ route('admin-hortikultura-utama.destroy', $row->id) }}', '{{ $row->kecamatan }}')">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Tambah Data -->
    <div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('admin-hortikultura-utama.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="komoditas" value="{{ $selectedKomoditas }}">
                    <input type="hidden" name="kategori" value="{{ $selectedKategori }}">
                    <input type="hidden" name="tahun" value="{{ $selectedTahun }}">
                    <input type="hidden" name="satuan" value="{{ $activeConfig['satuan'] }}">

                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title text-white">
                            <i class="bx bx-plus me-1"></i> Tambah Data Hortikultura: {{ $selectedKomoditas }} ({{ $activeConfig['label'] }})
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Pilih Kecamatan</label>
                                <select name="kecamatan" class="form-select" required>
                                    <option value="">-- Pilih Kecamatan --</option>
                                    @foreach ($masterKecamatan as $kc)
                                        <option value="{{ $kc['nama'] }}">{{ $kc['nama'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Luas Baku Lahan (Ha)</label>
                                <input type="number" step="0.01" name="luas_sawah" class="form-control" value="0">
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ $activeConfig['target_sub1_label'] }}</label>
                                <input type="number" step="0.01" name="target_okmar" class="form-control" value="0">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ $activeConfig['target_sub2_label'] }}</label>
                                <input type="number" step="0.01" name="target_asep" class="form-control" value="0">
                            </div>
                        </div>

                        <h6 class="fw-bold mt-3 mb-2 text-primary border-bottom pb-1">
                            Realisasi Bulanan ({{ $activeConfig['satuan'] }})
                        </h6>
                        <div class="row g-2">
                            <div class="col-4 col-sm-2"><label class="small">Okt</label><input type="number" step="0.01" name="realisasi_okt" class="form-control form-control-sm" value="0"></div>
                            <div class="col-4 col-sm-2"><label class="small">Nop</label><input type="number" step="0.01" name="realisasi_nop" class="form-control form-control-sm" value="0"></div>
                            <div class="col-4 col-sm-2"><label class="small">Des</label><input type="number" step="0.01" name="realisasi_des" class="form-control form-control-sm" value="0"></div>
                            <div class="col-4 col-sm-2"><label class="small">Jan</label><input type="number" step="0.01" name="realisasi_jan" class="form-control form-control-sm" value="0"></div>
                            <div class="col-4 col-sm-2"><label class="small">Feb</label><input type="number" step="0.01" name="realisasi_feb" class="form-control form-control-sm" value="0"></div>
                            <div class="col-4 col-sm-2"><label class="small">Mar</label><input type="number" step="0.01" name="realisasi_mar" class="form-control form-control-sm" value="0"></div>
                            <div class="col-4 col-sm-2"><label class="small">Apr</label><input type="number" step="0.01" name="realisasi_apr" class="form-control form-control-sm" value="0"></div>
                            <div class="col-4 col-sm-2"><label class="small">Mei</label><input type="number" step="0.01" name="realisasi_mei" class="form-control form-control-sm" value="0"></div>
                            <div class="col-4 col-sm-2"><label class="small">Jun</label><input type="number" step="0.01" name="realisasi_jun" class="form-control form-control-sm" value="0"></div>
                            <div class="col-4 col-sm-2"><label class="small">Jul</label><input type="number" step="0.01" name="realisasi_jul" class="form-control form-control-sm" value="0"></div>
                            <div class="col-4 col-sm-2"><label class="small">Ags</label><input type="number" step="0.01" name="realisasi_ags" class="form-control form-control-sm" value="0"></div>
                            <div class="col-4 col-sm-2"><label class="small">Sep</label><input type="number" step="0.01" name="realisasi_sep" class="form-control form-control-sm" value="0"></div>
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
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title text-white">
                            <i class="bx bx-edit me-1"></i> Edit Data Kecamatan: <span id="edit_kecamatan_name"></span>
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Luas Baku Lahan (Ha)</label>
                                <input type="number" step="0.01" name="luas_sawah" id="edit_luas_sawah" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">{{ $activeConfig['target_sub1_label'] }}</label>
                                <input type="number" step="0.01" name="target_okmar" id="edit_target_okmar" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">{{ $activeConfig['target_sub2_label'] }}</label>
                                <input type="number" step="0.01" name="target_asep" id="edit_target_asep" class="form-control">
                            </div>
                        </div>

                        <h6 class="fw-bold mt-3 mb-2 text-primary border-bottom pb-1">Realisasi Bulanan</h6>
                        <div class="row g-2">
                            <div class="col-4 col-sm-2"><label class="small">Okt</label><input type="number" step="0.01" name="realisasi_okt" id="edit_realisasi_okt" class="form-control form-control-sm"></div>
                            <div class="col-4 col-sm-2"><label class="small">Nop</label><input type="number" step="0.01" name="realisasi_nop" id="edit_realisasi_nop" class="form-control form-control-sm"></div>
                            <div class="col-4 col-sm-2"><label class="small">Des</label><input type="number" step="0.01" name="realisasi_des" id="edit_realisasi_des" class="form-control form-control-sm"></div>
                            <div class="col-4 col-sm-2"><label class="small">Jan</label><input type="number" step="0.01" name="realisasi_jan" id="edit_realisasi_jan" class="form-control form-control-sm"></div>
                            <div class="col-4 col-sm-2"><label class="small">Feb</label><input type="number" step="0.01" name="realisasi_feb" id="edit_realisasi_feb" class="form-control form-control-sm"></div>
                            <div class="col-4 col-sm-2"><label class="small">Mar</label><input type="number" step="0.01" name="realisasi_mar" id="edit_realisasi_mar" class="form-control form-control-sm"></div>
                            <div class="col-4 col-sm-2"><label class="small">Apr</label><input type="number" step="0.01" name="realisasi_apr" id="edit_realisasi_apr" class="form-control form-control-sm"></div>
                            <div class="col-4 col-sm-2"><label class="small">Mei</label><input type="number" step="0.01" name="realisasi_mei" id="edit_realisasi_mei" class="form-control form-control-sm"></div>
                            <div class="col-4 col-sm-2"><label class="small">Jun</label><input type="number" step="0.01" name="realisasi_jun" id="edit_realisasi_jun" class="form-control form-control-sm"></div>
                            <div class="col-4 col-sm-2"><label class="small">Jul</label><input type="number" step="0.01" name="realisasi_jul" id="edit_realisasi_jul" class="form-control form-control-sm"></div>
                            <div class="col-4 col-sm-2"><label class="small">Ags</label><input type="number" step="0.01" name="realisasi_ags" id="edit_realisasi_ags" class="form-control form-control-sm"></div>
                            <div class="col-4 col-sm-2"><label class="small">Sep</label><input type="number" step="0.01" name="realisasi_sep" id="edit_realisasi_sep" class="form-control form-control-sm"></div>
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
                <form action="{{ route('admin-hortikultura-utama.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="tahun" value="{{ $selectedTahun }}">
                    <input type="hidden" name="komoditas" value="{{ $selectedKomoditas }}">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title text-white"><i class="bx bx-upload me-1"></i> Import Excel Data Utama Hortikultura</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted small">Pilih file Excel (.xlsx atau .xls) untuk memperbarui data Hortikultura Utama Tahun {{ $selectedTahun }}.</p>
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

    <!-- Modal Detail Bulanan -->
    <div class="modal fade" id="modalDetailBulanan" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold text-dark">
                        <i class="bx bx-calendar-check text-success me-1"></i> Rincian Bulanan Kecamatan <span id="detail_kecamatan" class="text-success"></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        <span class="badge bg-success fs-6" id="detail_kategori"></span>
                        <span class="text-muted small">Tahun: <strong>{{ $selectedTahun }}</strong></span>
                    </div>
                    <div class="card mb-3 border">
                        <div class="card-header bg-lighter py-2 fw-bold text-dark d-flex justify-content-between">
                            <span><i class="bx bx-circle text-primary me-1"></i> Semester 1 (Okt - Mar)</span>
                            <span class="text-primary fw-bold">Subtotal: <span id="detail_sub1">0</span></span>
                        </div>
                        <div class="card-body p-2">
                            <div class="row g-2 text-center" id="detail_cards_sem1"></div>
                        </div>
                    </div>
                    <div class="card mb-3 border">
                        <div class="card-header bg-lighter py-2 fw-bold text-dark d-flex justify-content-between">
                            <span><i class="bx bx-circle text-warning me-1"></i> Semester 2 (Apr - Sep)</span>
                            <span class="text-warning fw-bold">Subtotal: <span id="detail_sub2">0</span></span>
                        </div>
                        <div class="card-body p-2">
                            <div class="row g-2 text-center" id="detail_cards_sem2"></div>
                        </div>
                    </div>
                    <div class="alert alert-success d-flex justify-content-between align-items-center py-2 mb-0">
                        <strong class="fs-6">TOTAL REALISASI TAHUN {{ $selectedTahun }}:</strong>
                        <strong class="fs-5 text-success" id="detail_total">0</strong>
                    </div>
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                </div>
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
            // Edit modal population
            const editButtons = document.querySelectorAll('.btn-edit-data');
            editButtons.forEach(btn => {
                btn.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    const form = document.getElementById('editForm');
                    form.action = `/admin-hortikultura-utama/${id}`;

                    document.getElementById('edit_kecamatan_name').textContent = this.getAttribute('data-kecamatan');
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

            // Detail Bulanan Modal
            const detailButtons = document.querySelectorAll('.btn-detail-bulan');
            detailButtons.forEach(btn => {
                btn.addEventListener('click', function () {
                    const kec = this.getAttribute('data-kecamatan');
                    const kat = this.getAttribute('data-kategori');
                    const sat = this.getAttribute('data-satuan');

                    document.getElementById('detail_kecamatan').textContent = kec;
                    document.getElementById('detail_kategori').textContent = kat;

                    const formatNum = (n) => {
                        const num = parseFloat(n) || 0;
                        return num.toLocaleString('id-ID', { maximumFractionDigits: 2 });
                    };

                    document.getElementById('detail_sub1').textContent = formatNum(this.getAttribute('data-sub1')) + ' ' + sat;
                    document.getElementById('detail_sub2').textContent = formatNum(this.getAttribute('data-sub2')) + ' ' + sat;
                    document.getElementById('detail_total').textContent = formatNum(this.getAttribute('data-total')) + ' ' + sat;

                    const cSem1 = document.getElementById('detail_cards_sem1');
                    const cSem2 = document.getElementById('detail_cards_sem2');

                    const mSem1 = [
                        { label: 'Okt', val: this.getAttribute('data-m10') },
                        { label: 'Nop', val: this.getAttribute('data-m11') },
                        { label: 'Des', val: this.getAttribute('data-m12') },
                        { label: 'Jan', val: this.getAttribute('data-m1') },
                        { label: 'Feb', val: this.getAttribute('data-m2') },
                        { label: 'Mar', val: this.getAttribute('data-m3') },
                    ];
                    const mSem2 = [
                        { label: 'Apr', val: this.getAttribute('data-m4') },
                        { label: 'Mei', val: this.getAttribute('data-m5') },
                        { label: 'Jun', val: this.getAttribute('data-m6') },
                        { label: 'Jul', val: this.getAttribute('data-m7') },
                        { label: 'Ags', val: this.getAttribute('data-m8') },
                        { label: 'Sep', val: this.getAttribute('data-m9') },
                    ];

                    cSem1.innerHTML = mSem1.map(m => `
                        <div class="col-4 col-sm-2">
                            <div class="p-2 border rounded bg-white">
                                <div class="text-muted small">${m.label}</div>
                                <div class="fw-bold text-dark fs-7">${formatNum(m.val)}</div>
                            </div>
                        </div>
                    `).join('');

                    cSem2.innerHTML = mSem2.map(m => `
                        <div class="col-4 col-sm-2">
                            <div class="p-2 border rounded bg-white">
                                <div class="text-muted small">${m.label}</div>
                                <div class="fw-bold text-dark fs-7">${formatNum(m.val)}</div>
                            </div>
                        </div>
                    `).join('');
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
