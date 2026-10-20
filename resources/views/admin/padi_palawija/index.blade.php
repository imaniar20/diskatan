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

                <!-- Tombol Export & Import Excel -->
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <a href="{{ route('admin-padi-palawija.export', ['tahun' => $selectedTahun]) }}" class="btn btn-outline-success btn-sm">
                        <i class="bx bx-download me-1"></i> Export Excel Lengkap ({{ $selectedTahun }})
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

    <!-- Nav Tabs 7 Komoditas -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body p-2">
                    <ul class="nav nav-pills nav-fill nav-pills-komoditas flex-column flex-sm-row gap-1">
                        @foreach ($komoditasList as $komoditas)
                            <li class="nav-item">
                                <a class="nav-link {{ $selectedKomoditas == $komoditas ? 'active' : '' }}"
                                   href="{{ route('admin-padi-palawija.index', ['komoditas' => $komoditas, 'kategori' => $selectedKategori, 'tahun' => $selectedTahun]) }}">
                                    <i class="bx bx-spa me-1"></i> {{ $komoditas }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Kategori / Indikator Data Selector -->
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
                                    <a href="{{ route('admin-padi-palawija.index', ['komoditas' => $selectedKomoditas, 'kategori' => $katKey, 'tahun' => $selectedTahun]) }}"
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

    <!-- KPI Summary Cards (Dinamis Berdasarkan Kategori) -->
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
                        <th class="text-end">{{ $selectedKategori === 'tanam' ? 'Luas Sawah (Ha)' : 'Baku Lahan (Ha)' }}</th>
                        
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
                            <td class="text-end">{{ number_format($row->luas_sawah, 0) }}</td>

                            @if ($selectedKategori !== 'puso')
                                <td class="text-end text-muted">{{ number_format($row->target_okmar, 0) }}</td>
                                <td class="text-end text-muted">{{ number_format($row->target_asep, 0) }}</td>
                                <td class="text-end text-primary fw-semibold">{{ number_format($row->target_total, 0) }}</td>
                            @endif

                            <td class="text-end">
                                {{ $selectedKategori === 'produktivitas' ? number_format($row->jumlah_mh, 2) : number_format($row->jumlah_mh, 0) }}
                            </td>
                            <td class="text-end">
                                {{ $selectedKategori === 'produktivitas' ? number_format($row->jumlah_mk, 2) : number_format($row->jumlah_mk, 0) }}
                            </td>
                            <td class="text-end {{ $selectedKategori === 'puso' && $row->total_realisasi > 0 ? 'text-danger fw-bold' : 'text-success fw-bold' }}">
                                {{ $selectedKategori === 'produktivitas' ? number_format($row->total_realisasi, 2) : number_format($row->total_realisasi, 0) }}
                            </td>

                            @if ($selectedKategori !== 'puso')
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
                            @endif

                            <td class="text-center">
                                <button type="button" class="btn btn-xs btn-outline-info me-1 btn-detail-bulan"
                                    title="Lihat Detail Bulanan"
                                    data-bs-toggle="modal" data-bs-target="#modalDetailBulanan"
                                    data-kecamatan="{{ $row->kecamatan }}"
                                    data-kategori="{{ $activeConfig['label'] }}"
                                    data-satuan="{{ $activeConfig['satuan'] }}"
                                    data-m1="{{ $row->realisasi_jan }}"
                                    data-m2="{{ $row->realisasi_feb }}"
                                    data-m3="{{ $row->realisasi_mar }}"
                                    data-m4="{{ $row->realisasi_apr }}"
                                    data-m5="{{ $row->realisasi_mei }}"
                                    data-m6="{{ $row->realisasi_jun }}"
                                    data-m7="{{ $row->realisasi_jul }}"
                                    data-m8="{{ $row->realisasi_ags }}"
                                    data-m9="{{ $row->realisasi_sep }}"
                                    data-m10="{{ $row->realisasi_okt }}"
                                    data-m11="{{ $row->realisasi_nop }}"
                                    data-m12="{{ $row->realisasi_des }}"
                                    data-sub1="{{ $row->jumlah_mh }}"
                                    data-sub2="{{ $row->jumlah_mk }}"
                                    data-total="{{ $row->total_realisasi }}">
                                    <i class="bx bx-calendar"></i>
                                </button>

                                <button type="button" class="btn btn-xs btn-warning text-white btn-edit me-1"
                                    title="Edit Data"
                                    data-bs-toggle="modal" data-bs-target="#modalEdit"
                                    data-id="{{ $row->id }}"
                                    data-kecamatan="{{ $row->kecamatan }}"
                                    data-luas_sawah="{{ $row->luas_sawah }}"
                                    data-target_okmar="{{ $row->target_okmar }}"
                                    data-target_asep="{{ $row->target_asep }}"
                                    data-target_total="{{ $row->target_total }}"
                                    data-realisasi_jan="{{ $row->realisasi_jan }}"
                                    data-realisasi_feb="{{ $row->realisasi_feb }}"
                                    data-realisasi_mar="{{ $row->realisasi_mar }}"
                                    data-realisasi_apr="{{ $row->realisasi_apr }}"
                                    data-realisasi_mei="{{ $row->realisasi_mei }}"
                                    data-realisasi_jun="{{ $row->realisasi_jun }}"
                                    data-realisasi_jul="{{ $row->realisasi_jul }}"
                                    data-realisasi_ags="{{ $row->realisasi_ags }}"
                                    data-realisasi_sep="{{ $row->realisasi_sep }}"
                                    data-realisasi_okt="{{ $row->realisasi_okt }}"
                                    data-realisasi_nop="{{ $row->realisasi_nop }}"
                                    data-realisasi_des="{{ $row->realisasi_des }}"
                                    data-total_realisasi="{{ $row->total_realisasi }}">
                                    <i class="bx bx-edit"></i>
                                </button>

                                <button type="button" class="btn btn-xs btn-danger text-white"
                                    title="Hapus Data"
                                    onclick="confirmDelete('{{ route('admin-padi-palawija.destroy', $row->id) }}', '{{ $row->kecamatan }} ({{ $activeConfig['label'] }})')">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Detail Bulanan -->
    <div class="modal fade" id="modalDetailBulanan" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold text-dark">
                        <i class="bx bx-calendar-check text-primary me-1"></i> Rincian Bulanan Kecamatan <span id="detail_kecamatan" class="text-primary"></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        <span class="badge bg-label-info fs-6">Kategori: <strong id="detail_kategori"></strong></span>
                        <span class="text-muted small">Satuan: <strong id="detail_satuan"></strong></span>
                    </div>

                    <!-- Semester 1 / MH -->
                    <div class="card mb-3 border">
                        <div class="card-header bg-lighter py-2 fw-bold text-dark d-flex justify-content-between">
                            <span><i class="bx bx-circle text-primary me-1"></i> {{ $selectedKategori === 'tanam' ? 'Musim Hujan (MH)' : 'Semester 1 (Januari - Juni)' }}</span>
                            <span class="text-primary fw-bold">Subtotal: <span id="detail_sub1">0</span></span>
                        </div>
                        <div class="card-body p-2">
                            <div class="row g-2 text-center" id="detail_cards_sem1">
                                <!-- Generated via JS -->
                            </div>
                        </div>
                    </div>

                    <!-- Semester 2 / MK -->
                    <div class="card mb-3 border">
                        <div class="card-header bg-lighter py-2 fw-bold text-dark d-flex justify-content-between">
                            <span><i class="bx bx-circle text-warning me-1"></i> {{ $selectedKategori === 'tanam' ? 'Musim Kemarau (MK)' : 'Semester 2 (Juli - Desember)' }}</span>
                            <span class="text-warning fw-bold">Subtotal: <span id="detail_sub2">0</span></span>
                        </div>
                        <div class="card-body p-2">
                            <div class="row g-2 text-center" id="detail_cards_sem2">
                                <!-- Generated via JS -->
                            </div>
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

    <!-- Modal Import Excel -->
    <div class="modal fade" id="modalImport" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" action="{{ route('admin-padi-palawija.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title text-white"><i class="bx bx-upload me-1"></i> Import Excel Padi & Palawija</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info py-2 small mb-3">
                        <i class="bx bx-info-circle me-1"></i>
                        Sistem sekarang otomatis mendeteksi dan mengimpor <strong>semua tabel</strong> (Tanam, Panen, Produksi, Produktivitas, Puso) pada 7 sheet komoditas (<code>padi</code>, <code>jagung</code>, <code>kedele</code>, <code>k.tanah</code>, <code>k.hijau</code>, <code>u.kayu</code>, <code>u.jalar</code>).
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Tentukan Tahun Data <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="tahun" value="{{ $selectedTahun }}" min="2020" max="2050" required>
                        <small class="text-muted">Data untuk tahun ini akan digantikan (replace) secara terstruktur dengan data file Excel yang diunggah.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Pilih File Excel (.xls / .xlsx) <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" name="file_excel" accept=".xls,.xlsx" required>
                    </div>

                    <div class="card bg-lighter border p-2 small">
                        <span class="text-muted">
                            <i class="bx bx-bulb text-warning me-1"></i> <strong>File Rekomendasi:</strong>
                            File dinas seperti <code>realisasi dan target padi palawija 2026.xls</code> dapat langsung diunggah di sini tanpa perlu diedit atau dipisah-pisah sheetnya!
                        </span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="bx bx-check me-1"></i> Mulai Import Lengkap</button>
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
                <input type="hidden" name="kategori" value="{{ $selectedKategori }}">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Data {{ $activeConfig['label'] }} - {{ $selectedKomoditas }} ({{ $selectedTahun }})</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Komoditas</label>
                            <input type="text" class="form-control" name="komoditas" value="{{ $selectedKomoditas }}" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Kategori</label>
                            <input type="text" class="form-control" value="{{ $activeConfig['label'] }} ({{ $activeConfig['satuan'] }})" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Nama Kecamatan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control text-uppercase" name="kecamatan" required placeholder="Contoh: KUNINGAN">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">{{ $selectedKategori === 'tanam' ? 'Luas Sawah (Ha)' : 'Baku Lahan (Ha)' }}</label>
                            <input type="number" step="0.01" class="form-control" name="luas_sawah" value="0">
                        </div>

                        @if ($selectedKategori !== 'puso')
                            <div class="col-md-3">
                                <label class="form-label">{{ $activeConfig['target_sub1_label'] }}</label>
                                <input type="number" step="0.01" class="form-control target-calc" id="add_target_sub1" name="target_okmar" value="0">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">{{ $activeConfig['target_sub2_label'] }}</label>
                                <input type="number" step="0.01" class="form-control target-calc" id="add_target_sub2" name="target_asep" value="0">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Total Target</label>
                                <input type="number" step="0.01" class="form-control fw-bold bg-lighter" id="add_target_total" name="target_total" value="0">
                            </div>
                        @endif

                        <!-- Section Sub 1 -->
                        <div class="col-12 mt-4">
                            <h6 class="text-primary border-bottom pb-2 mb-2">
                                <i class="bx bx-circle me-1"></i> {{ $selectedKategori === 'tanam' ? 'Realisasi Musim Hujan (MH)' : 'Realisasi Semester 1 (Jan - Jun)' }}
                            </h6>
                        </div>

                        @if ($selectedKategori === 'tanam')
                            <div class="col-md-2 col-4"><label class="form-label">Okt</label><input type="number" step="0.01" class="form-control r-sub1" name="realisasi_okt" value="0"></div>
                            <div class="col-md-2 col-4"><label class="form-label">Nop</label><input type="number" step="0.01" class="form-control r-sub1" name="realisasi_nop" value="0"></div>
                            <div class="col-md-2 col-4"><label class="form-label">Des</label><input type="number" step="0.01" class="form-control r-sub1" name="realisasi_des" value="0"></div>
                            <div class="col-md-2 col-4"><label class="form-label">Jan</label><input type="number" step="0.01" class="form-control r-sub1" name="realisasi_jan" value="0"></div>
                            <div class="col-md-2 col-4"><label class="form-label">Feb</label><input type="number" step="0.01" class="form-control r-sub1" name="realisasi_feb" value="0"></div>
                            <div class="col-md-2 col-4"><label class="form-label">Mar</label><input type="number" step="0.01" class="form-control r-sub1" name="realisasi_mar" value="0"></div>
                        @else
                            <div class="col-md-2 col-4"><label class="form-label">Jan</label><input type="number" step="0.01" class="form-control r-sub1" name="realisasi_jan" value="0"></div>
                            <div class="col-md-2 col-4"><label class="form-label">Feb</label><input type="number" step="0.01" class="form-control r-sub1" name="realisasi_feb" value="0"></div>
                            <div class="col-md-2 col-4"><label class="form-label">Mar</label><input type="number" step="0.01" class="form-control r-sub1" name="realisasi_mar" value="0"></div>
                            <div class="col-md-2 col-4"><label class="form-label">Apr</label><input type="number" step="0.01" class="form-control r-sub1" name="realisasi_apr" value="0"></div>
                            <div class="col-md-2 col-4"><label class="form-label">Mei</label><input type="number" step="0.01" class="form-control r-sub1" name="realisasi_mei" value="0"></div>
                            <div class="col-md-2 col-4"><label class="form-label">Jun</label><input type="number" step="0.01" class="form-control r-sub1" name="realisasi_jun" value="0"></div>
                        @endif

                        <!-- Section Sub 2 -->
                        <div class="col-12 mt-4">
                            <h6 class="text-warning border-bottom pb-2 mb-2">
                                <i class="bx bx-circle me-1"></i> {{ $selectedKategori === 'tanam' ? 'Realisasi Musim Kemarau (MK)' : 'Realisasi Semester 2 (Jul - Des)' }}
                            </h6>
                        </div>

                        @if ($selectedKategori === 'tanam')
                            <div class="col-md-2 col-4"><label class="form-label">Apr</label><input type="number" step="0.01" class="form-control r-sub2" name="realisasi_apr" value="0"></div>
                            <div class="col-md-2 col-4"><label class="form-label">Mei</label><input type="number" step="0.01" class="form-control r-sub2" name="realisasi_mei" value="0"></div>
                            <div class="col-md-2 col-4"><label class="form-label">Jun</label><input type="number" step="0.01" class="form-control r-sub2" name="realisasi_jun" value="0"></div>
                            <div class="col-md-2 col-4"><label class="form-label">Jul</label><input type="number" step="0.01" class="form-control r-sub2" name="realisasi_jul" value="0"></div>
                            <div class="col-md-2 col-4"><label class="form-label">Ags</label><input type="number" step="0.01" class="form-control r-sub2" name="realisasi_ags" value="0"></div>
                            <div class="col-md-2 col-4"><label class="form-label">Sep</label><input type="number" step="0.01" class="form-control r-sub2" name="realisasi_sep" value="0"></div>
                        @else
                            <div class="col-md-2 col-4"><label class="form-label">Jul</label><input type="number" step="0.01" class="form-control r-sub2" name="realisasi_jul" value="0"></div>
                            <div class="col-md-2 col-4"><label class="form-label">Ags</label><input type="number" step="0.01" class="form-control r-sub2" name="realisasi_ags" value="0"></div>
                            <div class="col-md-2 col-4"><label class="form-label">Sep</label><input type="number" step="0.01" class="form-control r-sub2" name="realisasi_sep" value="0"></div>
                            <div class="col-md-2 col-4"><label class="form-label">Okt</label><input type="number" step="0.01" class="form-control r-sub2" name="realisasi_okt" value="0"></div>
                            <div class="col-md-2 col-4"><label class="form-label">Nop</label><input type="number" step="0.01" class="form-control r-sub2" name="realisasi_nop" value="0"></div>
                            <div class="col-md-2 col-4"><label class="form-label">Des</label><input type="number" step="0.01" class="form-control r-sub2" name="realisasi_des" value="0"></div>
                        @endif
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
                    <h5 class="modal-title">Edit Data {{ $activeConfig['label'] }} - {{ $selectedKomoditas }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Kecamatan</label>
                            <input type="text" class="form-control text-uppercase" id="edit_kecamatan" name="kecamatan_edit" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ $selectedKategori === 'tanam' ? 'Luas Sawah (Ha)' : 'Baku Lahan (Ha)' }}</label>
                            <input type="number" step="0.01" class="form-control" id="edit_luas_sawah" name="luas_sawah_edit">
                        </div>

                        @if ($selectedKategori !== 'puso')
                            <div class="col-md-4">
                                <label class="form-label">{{ $activeConfig['target_sub1_label'] }}</label>
                                <input type="number" step="0.01" class="form-control edit-target-calc" id="edit_target_okmar" name="target_okmar_edit">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">{{ $activeConfig['target_sub2_label'] }}</label>
                                <input type="number" step="0.01" class="form-control edit-target-calc" id="edit_target_asep" name="target_asep_edit">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Total Target</label>
                                <input type="number" step="0.01" class="form-control fw-bold bg-lighter" id="edit_target_total" name="target_total_edit">
                            </div>
                        @endif

                        <!-- Section Sub 1 -->
                        <div class="col-12 mt-4">
                            <h6 class="text-primary border-bottom pb-2 mb-2">
                                <i class="bx bx-circle me-1"></i> {{ $selectedKategori === 'tanam' ? 'Realisasi Musim Hujan (MH)' : 'Realisasi Semester 1 (Jan - Jun)' }}
                            </h6>
                        </div>

                        @if ($selectedKategori === 'tanam')
                            <div class="col-md-2 col-4"><label class="form-label">Okt</label><input type="number" step="0.01" class="form-control" id="edit_realisasi_okt" name="realisasi_okt_edit"></div>
                            <div class="col-md-2 col-4"><label class="form-label">Nop</label><input type="number" step="0.01" class="form-control" id="edit_realisasi_nop" name="realisasi_nop_edit"></div>
                            <div class="col-md-2 col-4"><label class="form-label">Des</label><input type="number" step="0.01" class="form-control" id="edit_realisasi_des" name="realisasi_des_edit"></div>
                            <div class="col-md-2 col-4"><label class="form-label">Jan</label><input type="number" step="0.01" class="form-control" id="edit_realisasi_jan" name="realisasi_jan_edit"></div>
                            <div class="col-md-2 col-4"><label class="form-label">Feb</label><input type="number" step="0.01" class="form-control" id="edit_realisasi_feb" name="realisasi_feb_edit"></div>
                            <div class="col-md-2 col-4"><label class="form-label">Mar</label><input type="number" step="0.01" class="form-control" id="edit_realisasi_mar" name="realisasi_mar_edit"></div>
                        @else
                            <div class="col-md-2 col-4"><label class="form-label">Jan</label><input type="number" step="0.01" class="form-control" id="edit_realisasi_jan" name="realisasi_jan_edit"></div>
                            <div class="col-md-2 col-4"><label class="form-label">Feb</label><input type="number" step="0.01" class="form-control" id="edit_realisasi_feb" name="realisasi_feb_edit"></div>
                            <div class="col-md-2 col-4"><label class="form-label">Mar</label><input type="number" step="0.01" class="form-control" id="edit_realisasi_mar" name="realisasi_mar_edit"></div>
                            <div class="col-md-2 col-4"><label class="form-label">Apr</label><input type="number" step="0.01" class="form-control" id="edit_realisasi_apr" name="realisasi_apr_edit"></div>
                            <div class="col-md-2 col-4"><label class="form-label">Mei</label><input type="number" step="0.01" class="form-control" id="edit_realisasi_mei" name="realisasi_mei_edit"></div>
                            <div class="col-md-2 col-4"><label class="form-label">Jun</label><input type="number" step="0.01" class="form-control" id="edit_realisasi_jun" name="realisasi_jun_edit"></div>
                        @endif

                        <!-- Section Sub 2 -->
                        <div class="col-12 mt-4">
                            <h6 class="text-warning border-bottom pb-2 mb-2">
                                <i class="bx bx-circle me-1"></i> {{ $selectedKategori === 'tanam' ? 'Realisasi Musim Kemarau (MK)' : 'Realisasi Semester 2 (Jul - Des)' }}
                            </h6>
                        </div>

                        @if ($selectedKategori === 'tanam')
                            <div class="col-md-2 col-4"><label class="form-label">Apr</label><input type="number" step="0.01" class="form-control" id="edit_realisasi_apr" name="realisasi_apr_edit"></div>
                            <div class="col-md-2 col-4"><label class="form-label">Mei</label><input type="number" step="0.01" class="form-control" id="edit_realisasi_mei" name="realisasi_mei_edit"></div>
                            <div class="col-md-2 col-4"><label class="form-label">Jun</label><input type="number" step="0.01" class="form-control" id="edit_realisasi_jun" name="realisasi_jun_edit"></div>
                            <div class="col-md-2 col-4"><label class="form-label">Jul</label><input type="number" step="0.01" class="form-control" id="edit_realisasi_jul" name="realisasi_jul_edit"></div>
                            <div class="col-md-2 col-4"><label class="form-label">Ags</label><input type="number" step="0.01" class="form-control" id="edit_realisasi_ags" name="realisasi_ags_edit"></div>
                            <div class="col-md-2 col-4"><label class="form-label">Sep</label><input type="number" step="0.01" class="form-control" id="edit_realisasi_sep" name="realisasi_sep_edit"></div>
                        @else
                            <div class="col-md-2 col-4"><label class="form-label">Jul</label><input type="number" step="0.01" class="form-control" id="edit_realisasi_jul" name="realisasi_jul_edit"></div>
                            <div class="col-md-2 col-4"><label class="form-label">Ags</label><input type="number" step="0.01" class="form-control" id="edit_realisasi_ags" name="realisasi_ags_edit"></div>
                            <div class="col-md-2 col-4"><label class="form-label">Sep</label><input type="number" step="0.01" class="form-control" id="edit_realisasi_sep" name="realisasi_sep_edit"></div>
                            <div class="col-md-2 col-4"><label class="form-label">Okt</label><input type="number" step="0.01" class="form-control" id="edit_realisasi_okt" name="realisasi_okt_edit"></div>
                            <div class="col-md-2 col-4"><label class="form-label">Nop</label><input type="number" step="0.01" class="form-control" id="edit_realisasi_nop" name="realisasi_nop_edit"></div>
                            <div class="col-md-2 col-4"><label class="form-label">Des</label><input type="number" step="0.01" class="form-control" id="edit_realisasi_des" name="realisasi_des_edit"></div>
                        @endif

                        @if ($selectedKategori === 'produktivitas')
                            <div class="col-12 mt-3">
                                <label class="form-label">Total Produktivitas Tahunan (Kw/Ha)</label>
                                <input type="number" step="0.01" class="form-control fw-bold" id="edit_total_realisasi" name="total_realisasi_edit">
                            </div>
                        @endif
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
            const isTanam = '{{ $selectedKategori }}' === 'tanam';

            // Auto calculate target total on Add
            const t1 = document.getElementById('add_target_sub1');
            const t2 = document.getElementById('add_target_sub2');
            const tTot = document.getElementById('add_target_total');
            if (t1 && t2 && tTot) {
                [t1, t2].forEach(input => {
                    input.addEventListener('input', () => {
                        const val1 = parseFloat(t1.value) || 0;
                        const val2 = parseFloat(t2.value) || 0;
                        tTot.value = (val1 + val2).toFixed(2);
                    });
                });
            }

            // Auto calculate target total on Edit
            const et1 = document.getElementById('edit_target_okmar');
            const et2 = document.getElementById('edit_target_asep');
            const etTot = document.getElementById('edit_target_total');
            if (et1 && et2 && etTot) {
                [et1, et2].forEach(input => {
                    input.addEventListener('input', () => {
                        const val1 = parseFloat(et1.value) || 0;
                        const val2 = parseFloat(et2.value) || 0;
                        etTot.value = (val1 + val2).toFixed(2);
                    });
                });
            }

            // Edit modal data filling
            const editButtons = document.querySelectorAll('.btn-edit');
            const formEdit = document.getElementById('formEdit');

            editButtons.forEach(btn => {
                btn.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    formEdit.action = `/admin-padi-palawija/${id}`;

                    document.getElementById('edit_kecamatan').value = this.getAttribute('data-kecamatan') || '';
                    document.getElementById('edit_luas_sawah').value = this.getAttribute('data-luas_sawah') || 0;
                    
                    if (et1) et1.value = this.getAttribute('data-target_okmar') || 0;
                    if (et2) et2.value = this.getAttribute('data-target_asep') || 0;
                    if (etTot) etTot.value = this.getAttribute('data-target_total') || 0;

                    const elJan = document.getElementById('edit_realisasi_jan');
                    const elFeb = document.getElementById('edit_realisasi_feb');
                    const elMar = document.getElementById('edit_realisasi_mar');
                    const elApr = document.getElementById('edit_realisasi_apr');
                    const elMei = document.getElementById('edit_realisasi_mei');
                    const elJun = document.getElementById('edit_realisasi_jun');
                    const elJul = document.getElementById('edit_realisasi_jul');
                    const elAgs = document.getElementById('edit_realisasi_ags');
                    const elSep = document.getElementById('edit_realisasi_sep');
                    const elOkt = document.getElementById('edit_realisasi_okt');
                    const elNop = document.getElementById('edit_realisasi_nop');
                    const elDes = document.getElementById('edit_realisasi_des');

                    if (elJan) elJan.value = this.getAttribute('data-realisasi_jan') || 0;
                    if (elFeb) elFeb.value = this.getAttribute('data-realisasi_feb') || 0;
                    if (elMar) elMar.value = this.getAttribute('data-realisasi_mar') || 0;
                    if (elApr) elApr.value = this.getAttribute('data-realisasi_apr') || 0;
                    if (elMei) elMei.value = this.getAttribute('data-realisasi_mei') || 0;
                    if (elJun) elJun.value = this.getAttribute('data-realisasi_jun') || 0;
                    if (elJul) elJul.value = this.getAttribute('data-realisasi_jul') || 0;
                    if (elAgs) elAgs.value = this.getAttribute('data-realisasi_ags') || 0;
                    if (elSep) elSep.value = this.getAttribute('data-realisasi_sep') || 0;
                    if (elOkt) elOkt.value = this.getAttribute('data-realisasi_okt') || 0;
                    if (elNop) elNop.value = this.getAttribute('data-realisasi_nop') || 0;
                    if (elDes) elDes.value = this.getAttribute('data-realisasi_des') || 0;

                    const elTotRel = document.getElementById('edit_total_realisasi');
                    if (elTotRel) elTotRel.value = this.getAttribute('data-total_realisasi') || 0;
                });
            });

            // Detail Bulanan Modal filling
            const detailButtons = document.querySelectorAll('.btn-detail-bulan');
            detailButtons.forEach(btn => {
                btn.addEventListener('click', function () {
                    const kec = this.getAttribute('data-kecamatan');
                    const kat = this.getAttribute('data-kategori');
                    const sat = this.getAttribute('data-satuan');

                    document.getElementById('detail_kecamatan').textContent = kec;
                    document.getElementById('detail_kategori').textContent = kat;
                    document.getElementById('detail_satuan').textContent = sat;

                    const formatNum = (n) => {
                        const num = parseFloat(n) || 0;
                        return num.toLocaleString('id-ID', { maximumFractionDigits: 2 });
                    };

                    document.getElementById('detail_sub1').textContent = formatNum(this.getAttribute('data-sub1')) + ' ' + sat;
                    document.getElementById('detail_sub2').textContent = formatNum(this.getAttribute('data-sub2')) + ' ' + sat;
                    document.getElementById('detail_total').textContent = formatNum(this.getAttribute('data-total')) + ' ' + sat;

                    const cSem1 = document.getElementById('detail_cards_sem1');
                    const cSem2 = document.getElementById('detail_cards_sem2');

                    let mSem1 = [];
                    let mSem2 = [];

                    if (isTanam) {
                        mSem1 = [
                            { label: 'Okt', val: this.getAttribute('data-m10') },
                            { label: 'Nop', val: this.getAttribute('data-m11') },
                            { label: 'Des', val: this.getAttribute('data-m12') },
                            { label: 'Jan', val: this.getAttribute('data-m1') },
                            { label: 'Feb', val: this.getAttribute('data-m2') },
                            { label: 'Mar', val: this.getAttribute('data-m3') },
                        ];
                        mSem2 = [
                            { label: 'Apr', val: this.getAttribute('data-m4') },
                            { label: 'Mei', val: this.getAttribute('data-m5') },
                            { label: 'Jun', val: this.getAttribute('data-m6') },
                            { label: 'Jul', val: this.getAttribute('data-m7') },
                            { label: 'Ags', val: this.getAttribute('data-m8') },
                            { label: 'Sep', val: this.getAttribute('data-m9') },
                        ];
                    } else {
                        mSem1 = [
                            { label: 'Jan', val: this.getAttribute('data-m1') },
                            { label: 'Feb', val: this.getAttribute('data-m2') },
                            { label: 'Mar', val: this.getAttribute('data-m3') },
                            { label: 'Apr', val: this.getAttribute('data-m4') },
                            { label: 'Mei', val: this.getAttribute('data-m5') },
                            { label: 'Jun', val: this.getAttribute('data-m6') },
                        ];
                        mSem2 = [
                            { label: 'Jul', val: this.getAttribute('data-m7') },
                            { label: 'Ags', val: this.getAttribute('data-m8') },
                            { label: 'Sep', val: this.getAttribute('data-m9') },
                            { label: 'Okt', val: this.getAttribute('data-m10') },
                            { label: 'Nov', val: this.getAttribute('data-m11') },
                            { label: 'Des', val: this.getAttribute('data-m12') },
                        ];
                    }

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
