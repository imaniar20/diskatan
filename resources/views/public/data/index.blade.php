@extends('public.layouts.app')

@section('title', 'Berita')

@push('styles')
    <style>
        /* ============ PAGE HERO SECTION ============ */
        .page-hero {
            position: relative;
            height: 400px;
            background: linear-gradient(135deg, rgba(45, 106, 79, 0.85), rgba(64, 145, 108, 0)),
                var(--hero-bg);
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .page-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at top right, rgba(116, 198, 157, 0.2), transparent 60%),
                radial-gradient(circle at bottom left, rgba(45, 106, 79, 0.2), transparent 60%);
            animation: gradientShift 8s ease infinite;
        }

        @keyframes gradientShift {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.7;
            }
        }

        .page-hero .container {
            z-index: 2;
        }

        .page-title {
            font-family: 'Playfair Display', serif;
            font-size: 3.5rem;
            font-weight: 900;
            color: white;
            text-align: center;
            margin-bottom: 1.5rem;
            text-shadow: 2px 2px 20px rgba(0, 0, 0, 0.3);
            animation: fadeInDown 1s ease-out;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ============ BREADCRUMB ============ */
        .breadcrumb-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-size: 1.1rem;
            animation: fadeInUp 1s ease-out 0.2s both;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .breadcrumb-wrapper a {
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .breadcrumb-wrapper a:hover {
            color: #74c69d;
            transform: translateX(-2px);
        }

        .breadcrumb-wrapper span {
            color: rgba(255, 255, 255, 0.7);
        }

        .breadcrumb-wrapper .active {
            color: #74c69d;
            font-weight: 600;
        }

        /* ============ CONTENT SECTION ============ */
        .content-section {
            padding: 40px 0;
            background: linear-gradient(135deg, #f8f9fa, #e8f5e9);
            position: relative;
        }

        .content-section::before {
            content: '';
            position: absolute;
            top: -50px;
            left: 0;
            right: 0;
            height: 100px;
            background: linear-gradient(135deg, #f8f9fa, #e8f5e9);
            clip-path: polygon(0 50%, 100% 0, 100% 100%, 0 100%);
        }

        /* ============ BERITA CARD ============ */
        .berita-card {
            border-radius: 20px;
            overflow: hidden;
            transition: all 0.4s ease;
            border: 2px solid transparent;
            background: white;
        }

        .berita-card:hover {
            transform: translateY(-15px);
            box-shadow: 0 25px 60px rgba(45, 106, 79, 0.2) !important;
            border-color: #52b788;
        }

        .berita-card .card-img-top {
            height: 200px;
            object-fit: cover;
            transition: all 0.5s ease;
        }

        .berita-card:hover .card-img-top {
            transform: scale(1.15);
        }

        .berita-card .card-body {
            padding: 20px;
        }

        .berita-card .card-title {
            color: #2d6a4f;
            font-family: 'Poppins', sans-serif;
            font-size: 1rem;
            font-weight: 700;
            margin-bottom: 10px;
            line-height: 1.4;
            transition: color 0.3s ease;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .berita-card:hover .card-title {
            color: #40916c;
        }

        .berita-card .card-text {
            font-size: 0.9rem;
            line-height: 1.6;
            margin-bottom: 15px;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .berita-card small {
            font-size: 0.85rem;
            color: #666;
        }

        .berita-card small i {
            color: #52b788;
            margin-right: 5px;
        }

        .berita-card .btn-outline-success {
            border: 2px solid #52b788;
            color: #2d6a4f;
            font-weight: 600;
            padding: 8px 20px;
            border-radius: 25px;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .berita-card .btn-outline-success:hover {
            background: linear-gradient(135deg, #2d6a4f, #40916c);
            border-color: #2d6a4f;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(45, 106, 79, 0.3);
        }

        /* ============ PAGINATION ============ */
        .pagination {
            justify-content: center;
            margin-top: 30px;
        }

        .pagination .page-link {
            border: 2px solid #e8f5e9;
            color: #2d6a4f;
            font-weight: 600;
            padding: 10px 18px;
            margin: 0 5px;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .pagination .page-link:hover {
            background: linear-gradient(135deg, #52b788, #74c69d);
            border-color: #52b788;
            color: white;
            transform: translateY(-2px);
        }

        .pagination .page-item.active .page-link {
            background: linear-gradient(135deg, #2d6a4f, #40916c);
            border-color: #2d6a4f;
            color: white;
        }

        .pagination .page-item.disabled .page-link {
            background: #f8f9fa;
            border-color: #e8f5e9;
            color: #999;
        }

        /* ============ SIDEBAR CARDS ============ */
        .sidebar-card {
            border-radius: 20px;
            border: none;
            overflow: hidden;
            transition: all 0.3s ease;
            background: white;
        }

        .sidebar-card:hover {
            box-shadow: 0 15px 40px rgba(45, 106, 79, 0.1) !important;
            transform: translateY(-5px);
        }

        .sidebar-card .card-body {
            padding: 25px;
        }

        .sidebar-card h6 {
            color: #2d6a4f;
            font-weight: 700;
            font-size: 1.1rem;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 3px solid #e8f5e9;
            position: relative;
        }

        .sidebar-card h6::after {
            content: '';
            position: absolute;
            bottom: -3px;
            left: 0;
            width: 50px;
            height: 3px;
            background: linear-gradient(90deg, #2d6a4f, #52b788);
        }

        /* ============ SEARCH BOX ============ */
        .search-card .input-group {
            border: 2px solid #e8f5e9;
            border-radius: 25px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .search-card .input-group:focus-within {
            border-color: #52b788;
            box-shadow: 0 0 0 3px rgba(82, 183, 136, 0.1);
        }

        .search-card .form-control {
            border: none;
            padding: 12px 20px;
        }

        .search-card .form-control:focus {
            box-shadow: none;
        }

        .search-card .btn-success {
            background: linear-gradient(135deg, #2d6a4f, #40916c);
            border: none;
            padding: 12px 25px;
            border-radius: 0;
        }

        .search-card .btn-success:hover {
            background: linear-gradient(135deg, #40916c, #52b788);
        }

        /* ============ SIDEBAR NEWS ITEM ============ */
        .sidebar-news-item {
            padding: 12px;
            border-radius: 12px;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .sidebar-news-item:hover {
            background: linear-gradient(135deg, rgba(82, 183, 136, 0.05), rgba(116, 198, 157, 0.05));
            border-color: #e8f5e9;
            transform: translateX(5px);
        }

        .sidebar-news-img {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 12px;
            border: 2px solid #e8f5e9;
            transition: all 0.3s ease;
        }

        .sidebar-news-item:hover .sidebar-news-img {
            border-color: #52b788;
            transform: scale(1.05);
        }

        .sidebar-news-item .text-dark {
            color: #2d6a4f !important;
            line-height: 1.4;
            font-weight: 600;
        }

        .sidebar-news-item:hover .text-dark {
            color: #40916c !important;
        }

        .sidebar-news-item .text-muted {
            font-size: 0.85rem;
            color: #666 !important;
        }

        .sidebar-news-item .text-muted i {
            color: #52b788;
        }

        /* ============ DECORATIVE PATTERN ============ */
        .pattern-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            pointer-events: none;
            z-index: 1;
            opacity: 0.03;
            background-image:
                repeating-linear-gradient(45deg, transparent, transparent 35px, #2d6a4f 35px, #2d6a4f 36px),
                repeating-linear-gradient(-45deg, transparent, transparent 35px, #2d6a4f 35px, #2d6a4f 36px);
        }

        /* ============ RESPONSIVE ============ */
        @media (max-width: 768px) {
            .page-title {
                font-size: 2.5rem;
            }

            .berita-card .card-body {
                padding: 15px;
            }

            .sidebar-card {
                margin-top: 30px;
            }
        }

        @media (min-width: 992px) and (max-width: 1199px) {
            .berita-card .card-title {
                font-size: 0.95rem;
            }
        }

        .pills-wrapper {
            margin-left: 5vw;
            margin-right: 5vw;
        }

        .nav-pills {
            border-radius: 50px;
            padding: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            background: linear-gradient(135deg, var(--cream), var(--secondary-cream));
        }

        .nav-pills .nav-link .text-pils {
            color: #16a34a;
        }

        .nav-pills .nav-link.active .text-pils {
            color: rgb(255, 255, 255);
        }

        .nav-pills .nav-link:hover {
            color: #16a34a;
            background-color: rgba(46, 125, 50, 0.1);
        }

        .nav-pills .nav-link.active {
            background: #16a34a;
            color: white;
            box-shadow: 0 4px 8px rgba(46, 125, 50, 0.2);
        }

        .item-produksi {
            display: flex;
            align-items: center;
            padding: 14px 12px;
            margin-bottom: 8px;
            border-radius: 8px;
            transition: background-color 0.2s;
        }

        .item-produksi:hover {
            background-color: #f8f9fa;
        }

        .item-number {
            width: 32px;
            height: 32px;
            background-color: #d4f4dd;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: #1a1a1a;
            font-size: 14px;
            flex-shrink: 0;
        }

        .item-content {
            flex: 1;
            margin-left: 16px;
        }

        .item-name {
            font-size: 15px;
            font-weight: 500;
            color: #1a1a1a;
            margin-bottom: 2px;
        }

        .item-year {
            font-size: 13px;
            color: #6b7280;
        }

        .item-value {
            text-align: right;
            flex-shrink: 0;
        }

        .value-number {
            font-size: 16px;
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 2px;
        }

        .value-unit {
            font-size: 13px;
            color: #6b7280;
        }

        #donutChart {
            margin-top: -20px;
        }

        .apexcharts-legend-text {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif !important;
        }

        .btn-komoditas-pill {
            border-radius: 30px;
            padding: 8px 18px;
            font-size: 0.9rem;
            font-weight: 500;
            border: 1px solid #16a34a;
            color: #16a34a;
            background: #fff;
            transition: all 0.3s ease;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-komoditas-pill.active,
        .btn-komoditas-pill:hover {
            background: #16a34a;
            color: #fff !important;
            box-shadow: 0 4px 12px rgba(22, 163, 74, 0.25);
        }
        .btn-padi-kat-pill {
            padding: 8px 18px;
            font-size: 0.88rem;
            font-weight: 600;
            border-radius: 30px;
            border: 1px solid #e2e8f0;
            color: #475569;
            background: #fff;
            transition: all 0.25s ease;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-padi-kat-pill:hover {
            background: #f8fafc;
            color: #1e293b;
            border-color: #cbd5e1;
        }
        .btn-padi-kat-pill.active {
            background: #16a34a;
            color: #fff !important;
            border-color: #16a34a;
            box-shadow: 0 4px 12px rgba(22, 163, 74, 0.25);
        }
        .btn-padi-kat-pill.active[data-kategori="panen"] { background: #2563eb; border-color: #2563eb; box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25); }
        .btn-padi-kat-pill.active[data-kategori="produksi"] { background: #d97706; border-color: #d97706; box-shadow: 0 4px 12px rgba(217, 119, 6, 0.25); }
        .btn-padi-kat-pill.active[data-kategori="produktivitas"] { background: #0891b2; border-color: #0891b2; box-shadow: 0 4px 12px rgba(8, 145, 178, 0.25); }
        .btn-padi-kat-pill.active[data-kategori="puso"] { background: #dc2626; border-color: #dc2626; box-shadow: 0 4px 12px rgba(220, 38, 38, 0.25); }
        .stat-card-padi {
            background: #ffffff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            border-left: 4px solid #16a34a;
            height: 100%;
        }

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
    </style>
@endpush

@section('content')
    <!-- Pattern Overlay -->
    <div class="pattern-overlay"></div>

    <!-- HERO -->
    <section class="page-hero" style="--hero-bg: url('{{ asset('img/background/bg-2.jpg') }}');">
        <div class="container position-relative">
            <h1 class="page-title">{{ $head }}</h1>

            <nav class="breadcrumb-wrapper">
                <a href="{{ url('/') }}">Home</a>
                <span>›</span>
                <span class="active">{{ $head }}</span>
            </nav>
        </div>
    </section>
    <section class="content-section">
        <div class="container">
            <div class="pills-wrapper">
                <ul class="nav nav-pills mb-3 justify-content-center" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#statistik" type="button">
                            <i class='bx bx-objects-vertical-bottom text-pils'></i>
                            <span class="ms-2 text-pils">Statistik</span>
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="pill" data-bs-target="#produksi" type="button">
                            <i class='bx bx-stats text-pils'></i>
                            <span class="ms-2 text-pils">Produksi</span>
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="pill" data-bs-target="#ketahanan" type="button">
                            <i class='bx bx-map-alt text-pils'></i>
                            <span class="ms-2 text-pils">Ketahanan Pangan</span>
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="pill" data-bs-target="#padi-palawija" type="button">
                            <i class='bx bx-spa text-pils'></i>
                            <span class="ms-2 text-pils">Padi & Palawija</span>
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="pill" data-bs-target="#sayuran" type="button">
                            <i class='bx bx-leaf text-pils'></i>
                            <span class="ms-2 text-pils">Sayuran & Hortikultura</span>
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tanaman-pangan" type="button">
                            <i class='bx bx-sun text-pils'></i>
                            <span class="ms-2 text-pils">Tanaman Pangan</span>
                        </button>
                    </li>

                    {{-- <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="pill" data-bs-target="#dokumen" type="button">
                            <i class='bx bxs-book-content text-pils'></i>
                            <span class="ms-2 text-pils">Dokumen</span>
                        </button>
                    </li> --}}
                </ul>
                <div class="tab-content mt-4" id="pills-tabContent">

                    <div class="tab-pane fade show active" id="statistik">
                        <div class="row g-4">
                            <!-- Stat 1 -->
                            <style>
                                .date-label {
                                    position: absolute;
                                    top: 10px;
                                    right: 15px;
                                    font-size: 11px;
                                    background: rgba(0, 0, 0, 0.05);
                                    padding: 3px 8px;
                                    border-radius: 6px;
                                }
                            </style>
                            <div class="col-6 col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
                                <div class="stat-card">
                                    {{-- <div class="stat-card position-relative"> --}}
                                        <p class="date-label stat-label">Jan - {{ date('M') }} {{ date('Y') }}</p>
                                        {{--
                                    </div> --}}
                                    <div class="stat-icon bg-success">
                                        <i class="bi bi-geo-alt"></i>
                                    </div>
                                    <div class="stat-content">
                                        <h3 class="stat-number">{{ number_format($data->hektar_luas_tanam) }}</h3>
                                        <p class="stat-label">Hektar Luas Tanam</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Stat 2 -->
                            <div class="col-6 col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
                                <div class="stat-card">
                                    <div class="stat-icon bg-warning">
                                        <i class="bi bi-bag-check"></i>
                                    </div>
                                    <div class="stat-content">
                                        <h3 class="stat-number">{{ $data->ton_produksi }}</h3>
                                        <p class="stat-label">Ton Produksi</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Stat 3 -->
                            <div class="col-6 col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                                <div class="stat-card">
                                    <div class="stat-icon bg-primary">
                                        <i class="bi bi-people"></i>
                                    </div>
                                    <div class="stat-content">
                                        <h3 class="stat-number">
                                            {{ $kelompok_tani !== null ? number_format($kelompok_tani) : '-' }}
                                        </h3>
                                        <p class="stat-label">Kelompok Tani</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Stat 4 -->
                            <div class="col-6 col-md-6 col-lg-3 mb-4" data-aos="fade-up" data-aos-delay="400">
                                <div class="stat-card">
                                    <div class="stat-icon bg-info">
                                        <i class="bi bi-graph-up-arrow"></i>
                                    </div>
                                    <div class="stat-content">
                                        <h3 class="stat-number">{{ $data->indeks_ketahanan_pangan }}</h3>
                                        <p class="stat-label">Indeks Ketahanan Pangan</p>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="col-12">
                                <div id="chart" class="px-2"></div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="produksi">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="card">
                                    <p class="card-header stat-label">Produksi Pertanian {{ date('Y') }}</p>
                                    <div class="card-body">
                                        <div id="chart2" class="px-2"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card">
                                    <p class="card-header stat-label">Data Produksi {{ date('Y') }}</p>
                                    <div class="card-body">
                                        @php
                                            $i = 1;
                                        @endphp
                                        @foreach ($produksi as $item)
                                            <div class="item-produksi">
                                                <div class="item-number">{{ $i++ }}</div>
                                                <div class="item-content">
                                                    <div class="item-name">{{ $item->name }}</div>
                                                    <div class="item-year">Produksi {{ $item->year }}</div>
                                                </div>
                                                <div class="item-value">
                                                    <div class="value-number">{{ number_format($item->value) }}</div>
                                                    <div class="value-unit">{{ $item->unit }}</div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="ketahanan">
                        <div class="row g-4">
                            <!-- Donut Chart -->
                            <div class="col-lg-6">
                                <div class="card">
                                    <p class="card-header stat-label">Status Ketahanan Pangan per Desa</p>
                                    <div class="card-body">
                                        <div id="donutChart"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Bar Chart -->
                            <div class="col-lg-6">
                                <div class="card">
                                    <p class="card-header stat-label">Indikator Ketahanan Pangan</p>
                                    <div class="card-body">
                                        <div id="barChart"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB PANE PADI & PALAWIJA -->
                    <div class="tab-pane fade" id="padi-palawija">
                        <!-- Selector Tahun Bar -->
                        <div
                            class="d-flex flex-wrap justify-content-between align-items-center mb-4 p-3 bg-white rounded-3 shadow-sm border border-success-subtle">
                            <div class="d-flex align-items-center gap-2 mb-2 mb-sm-0">
                                <label for="publicSelectTahun" class="fw-bold text-success mb-0 text-nowrap">
                                    <i class="bx bx-calendar me-1"></i> Pilih Tahun:
                                </label>
                                <select id="publicSelectTahun"
                                    class="form-select form-select-sm fw-bold border-success text-success"
                                    style="width: 120px;"
                                    onchange="window.location.href = '{{ url('/datadaninformasi') }}?tahun=' + this.value + '#padi-palawija'">
                                    @foreach ($availableYears as $yr)
                                        <option value="{{ $yr }}" {{ $selectedTahun == $yr ? 'selected' : '' }}>{{ $yr }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <span class="badge bg-light text-success border border-success px-3 py-2" id="padiActiveBadge">
                                    <i class="bx bx-check-shield me-1"></i> Luas Tanam - Padi Tahun {{ $selectedTahun }}
                                </span>
                            </div>
                        </div>

                        <!-- Sub Pills Kategori / Indikator Data -->
                        <div class="d-flex flex-wrap justify-content-center gap-2 mb-3">
                            <button type="button" class="btn-padi-kat-pill active" data-kategori="tanam">
                                <i class="bx bx-spa"></i> Luas Tanam (Ha)
                            </button>
                            <button type="button" class="btn-padi-kat-pill" data-kategori="panen">
                                <i class="bx bx-cut"></i> Luas Panen (Ha)
                            </button>
                            <button type="button" class="btn-padi-kat-pill" data-kategori="produksi">
                                <i class="bx bx-package"></i> Produksi (Ton)
                            </button>
                            <button type="button" class="btn-padi-kat-pill" data-kategori="produktivitas">
                                <i class="bx bx-line-chart"></i> Produktivitas (Kw/Ha)
                            </button>
                            <button type="button" class="btn-padi-kat-pill" data-kategori="puso">
                                <i class="bx bx-error-circle"></i> Puso (Ha)
                            </button>
                        </div>

                        <!-- Sub Pills Komoditas -->
                        <div class="d-flex flex-wrap justify-content-center gap-2 mb-4">
                            @foreach ($komoditasList as $idx => $kmd)
                                <button type="button" class="btn-komoditas-pill btn-padi-pill {{ $idx == 0 ? 'active' : '' }}"
                                    data-komoditas="{{ $kmd }}">
                                    <i class="bx bx-leaf"></i> {{ $kmd }}
                                </button>
                            @endforeach
                        </div>

                        <!-- 4 KPI Stat Cards -->
                        <div class="row g-3 mb-4">
                            <div class="col-6 col-md-3">
                                <div class="stat-card-padi" id="kpiCard1Border">
                                    <span class="text-muted small" id="kpiCard1Title">Luas Sawah</span>
                                    <h4 class="mt-1 mb-0 text-success fw-bold stat-number" id="kpiSawah">0 Ha</h4>
                                    <small class="text-secondary" id="kpiSawahSub">Kabupaten Kuningan</small>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="stat-card-padi" id="kpiCard2Border" style="border-left-color: #f59e0b;">
                                    <span class="text-muted small" id="kpiCard2Title">Target Tanam</span>
                                    <h4 class="mt-1 mb-0 text-warning fw-bold stat-number" id="kpiTarget">0 Ha</h4>
                                    <small class="text-secondary" id="kpiCard2Sub">Musim Hujan + Kemarau</small>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="stat-card-padi" id="kpiCard3Border" style="border-left-color: #10b981;">
                                    <span class="text-muted small" id="kpiCard3Title">Realisasi Tanam</span>
                                    <h4 class="mt-1 mb-0 text-primary fw-bold stat-number" id="kpiRealisasi">0 Ha</h4>
                                    <small class="text-secondary" id="kpiCard3Sub">Total MH + MK</small>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="stat-card-padi" id="kpiCard4Border" style="border-left-color: #06b6d4;">
                                    <span class="text-muted small" id="kpiCard4Title">Capaian Target</span>
                                    <h4 class="mt-1 mb-0 text-info fw-bold stat-number" id="kpiPersen">0%</h4>
                                    <small class="text-secondary" id="kpiCard4Sub">Persentase Target</small>
                                </div>
                            </div>
                        </div>

                        <!-- Chart Target vs Realisasi per Kecamatan -->
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center flex-wrap">
                                <h6 class="mb-0 fw-bold text-success" id="chartPadiTitle">
                                    <i class="bx bx-bar-chart-alt-2 me-1"></i> Grafik Target vs Realisasi Luas Tanam per Kecamatan (Ha)
                                </h6>
                                <span class="badge bg-success" id="chartKomoditasBadge">Padi</span>
                            </div>
                            <div class="card-body">
                                <div id="chartPadiPalawija"></div>
                            </div>
                        </div>

                        <!-- Tabel Detail Per Kecamatan -->
                        <div class="card shadow-sm">
                            <div class="card-header bg-white py-3">
                                <h6 class="mb-0 fw-bold text-success" id="tablePadiTitle">
                                    <i class="bx bx-table me-1"></i> Data Rincian Luas Tanam per Kecamatan (Tahun {{ $selectedTahun }})
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover align-middle w-100" id="tablePadiPalawija">
                                        <thead class="table-light" id="theadPadiPalawija">
                                            <tr>
                                                <th class="text-center" style="width: 4%">No</th>
                                                <th>Kecamatan</th>
                                                <th class="text-end">Luas Sawah (Ha)</th>
                                                <th class="text-end">Target MH (Ha)</th>
                                                <th class="text-end">Target MK (Ha)</th>
                                                <th class="text-end">Target Total (Ha)</th>
                                                <th class="text-end">Realisasi MH (Ha)</th>
                                                <th class="text-end">Realisasi MK (Ha)</th>
                                                <th class="text-end">Total Realisasi (Ha)</th>
                                                <th class="text-center">Capaian</th>
                                                <th class="text-center" style="width: 8%">Rincian</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbodyPadiPalawija">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Detail Bulanan Publik -->
                    <div class="modal fade" id="modalDetailBulananPublik" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header bg-light">
                                    <h5 class="modal-title fw-bold text-dark">
                                        <i class="bx bx-calendar-check text-success me-1"></i> Rincian Bulanan Kecamatan <span id="pub_detail_kecamatan" class="text-success"></span>
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3 d-flex justify-content-between align-items-center">
                                        <span class="badge bg-success fs-6" id="pub_detail_badge"></span>
                                        <span class="text-muted small">Tahun: <strong>{{ $selectedTahun }}</strong></span>
                                    </div>

                                    <!-- Semester 1 / MH -->
                                    <div class="card mb-3 border">
                                        <div class="card-header bg-lighter py-2 fw-bold text-dark d-flex justify-content-between">
                                            <span id="pub_label_sem1"><i class="bx bx-circle text-primary me-1"></i> Semester 1</span>
                                            <span class="text-primary fw-bold">Subtotal: <span id="pub_detail_sub1">0</span></span>
                                        </div>
                                        <div class="card-body p-2">
                                            <div class="row g-2 text-center" id="pub_cards_sem1"></div>
                                        </div>
                                    </div>

                                    <!-- Semester 2 / MK -->
                                    <div class="card mb-3 border">
                                        <div class="card-header bg-lighter py-2 fw-bold text-dark d-flex justify-content-between">
                                            <span id="pub_label_sem2"><i class="bx bx-circle text-warning me-1"></i> Semester 2</span>
                                            <span class="text-warning fw-bold">Subtotal: <span id="pub_detail_sub2">0</span></span>
                                        </div>
                                        <div class="card-body p-2">
                                            <div class="row g-2 text-center" id="pub_cards_sem2"></div>
                                        </div>
                                    </div>

                                    <div class="alert alert-success d-flex justify-content-between align-items-center py-2 mb-0">
                                        <strong class="fs-6">TOTAL REALISASI TAHUN {{ $selectedTahun }}:</strong>
                                        <strong class="fs-5 text-success" id="pub_detail_total">0</strong>
                                    </div>
                                </div>
                                <div class="modal-footer py-2">
                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB PANE SAYURAN & HORTIKULTURA -->
                    <div class="tab-pane fade" id="sayuran">
                        <!-- Sub Pills Komoditas Sayuran & Hortikultura -->
                        <div class="d-flex flex-wrap justify-content-center gap-2 mb-4">
                            @foreach ($sayuranKomoditasList as $idx => $kmd)
                                <button type="button"
                                    class="btn-komoditas-pill btn-sayuran-pill {{ $idx == 0 ? 'active' : '' }}"
                                    data-komoditas="{{ $kmd }}">
                                    <i class="bx bx-spa"></i> {{ $kmd }}
                                </button>
                            @endforeach
                        </div>

                        <!-- 4 KPI Stat Cards -->
                        <div class="row g-3 mb-4">
                            <div class="col-6 col-md-3">
                                <div class="stat-card-padi" style="border-left-color: #f59e0b;">
                                    <span class="text-muted small">Total Luas Tanam</span>
                                    <h4 class="mt-1 mb-0 text-warning fw-bold stat-number" id="kpiSayuranTanam">0</h4>
                                    <small class="text-secondary" id="kpiSayuranSatuanTanam">Satuan</small>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="stat-card-padi" style="border-left-color: #3b82f6;">
                                    <span class="text-muted small">Total Luas Panen</span>
                                    <h4 class="mt-1 mb-0 text-primary fw-bold stat-number" id="kpiSayuranPanen">0</h4>
                                    <small class="text-secondary" id="kpiSayuranSatuanPanen">Satuan</small>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="stat-card-padi" style="border-left-color: #06b6d4;">
                                    <span class="text-muted small">Rata-rata Produktivitas</span>
                                    <h4 class="mt-1 mb-0 text-info fw-bold stat-number" id="kpiSayuranProdv">0</h4>
                                    <small class="text-secondary">Produktivitas Rata-rata</small>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="stat-card-padi" style="border-left-color: #10b981;">
                                    <span class="text-muted small">Total Produksi</span>
                                    <h4 class="mt-1 mb-0 text-success fw-bold stat-number" id="kpiSayuranProduksi">0</h4>
                                    <small class="text-secondary" id="kpiSayuranSatuanProduksi">Satuan</small>
                                </div>
                            </div>
                        </div>

                        <!-- Grafik Tren Tahunan ApexCharts -->
                        <div class="card shadow-sm mb-4">
                            <div
                                class="card-header bg-white py-3 d-flex justify-content-between align-items-center flex-wrap">
                                <h6 class="mb-0 fw-bold text-success">
                                    <i class="bx bx-line-chart me-1"></i> Grafik Perkembangan Tahunan: <span
                                        id="chartSayuranKomoditasTitle">Bawang Merah</span>
                                </h6>
                                <span class="badge bg-success" id="chartSayuranBadge">Sayuran</span>
                            </div>
                            <div class="card-body">
                                <div id="chartSayuranPublic"></div>
                            </div>
                        </div>

                        <!-- Tabel Detail Per Tahun -->
                        <div class="card shadow-sm">
                            <div
                                class="card-header bg-white py-3 d-flex justify-content-between align-items-center flex-wrap">
                                <h6 class="mb-0 fw-bold text-success">
                                    <i class="bx bx-table me-1"></i> Data Historis Tahunan: <span
                                        id="tableSayuranTitle">Bawang Merah</span>
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover align-middle w-100"
                                        id="tableSayuranPublic">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="text-center" style="width: 5%">No</th>
                                                <th class="text-center">Tahun</th>
                                                <th class="text-end" id="thSayuranTanam">Luas Tanam</th>
                                                <th class="text-end" id="thSayuranPanen">Luas Panen</th>
                                                <th class="text-end">Produktivitas</th>
                                                <th class="text-end" id="thSayuranProduksi">Total Produksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbodySayuranPublic">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB PANE TANAMAN PANGAN (13 TAHUN) -->
                    <div class="tab-pane fade" id="tanaman-pangan">
                        <!-- Sub Pills Komoditas Tanaman Pangan -->
                        <div class="d-flex flex-wrap justify-content-center gap-2 mb-4">
                            @foreach ($tanamanPanganKomoditasList as $idx => $kmd)
                                <button type="button" class="btn-komoditas-pill btn-pangan-pill {{ $idx == 0 ? 'active' : '' }}"
                                    data-komoditas="{{ $kmd }}">
                                    <i class="bx bx-spa"></i> {{ $kmd }}
                                </button>
                            @endforeach
                        </div>

                        <!-- 4 KPI Stat Cards -->
                        <div class="row g-3 mb-4">
                            <div class="col-6 col-md-3">
                                <div class="stat-card-padi" style="border-left-color: #f59e0b;">
                                    <span class="text-muted small">Total Luas Tanam</span>
                                    <h4 class="mt-1 mb-0 text-warning fw-bold stat-number" id="kpiPanganTanam">0 Ha</h4>
                                    <small class="text-secondary">Satuan: Hektar (Ha)</small>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="stat-card-padi" style="border-left-color: #3b82f6;">
                                    <span class="text-muted small">Total Luas Panen</span>
                                    <h4 class="mt-1 mb-0 text-primary fw-bold stat-number" id="kpiPanganPanen">0 Ha</h4>
                                    <small class="text-secondary">Satuan: Hektar (Ha)</small>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="stat-card-padi" style="border-left-color: #06b6d4;">
                                    <span class="text-muted small">Rata-rata Produktivitas</span>
                                    <h4 class="mt-1 mb-0 text-info fw-bold stat-number" id="kpiPanganProdv">0 Kw/Ha</h4>
                                    <small class="text-secondary">Satuan: Kw/Ha</small>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="stat-card-padi" style="border-left-color: #10b981;">
                                    <span class="text-muted small">Total Produksi</span>
                                    <h4 class="mt-1 mb-0 text-success fw-bold stat-number" id="kpiPanganProduksi">0 Ton</h4>
                                    <small class="text-secondary">Satuan: Ton</small>
                                </div>
                            </div>
                        </div>

                        <!-- Grafik Tren Tahunan ApexCharts -->
                        <div class="card shadow-sm mb-4">
                            <div
                                class="card-header bg-white py-3 d-flex justify-content-between align-items-center flex-wrap">
                                <h6 class="mb-0 fw-bold text-success">
                                    <i class="bx bx-line-chart me-1"></i> Grafik Perkembangan Tahunan: <span
                                        id="chartPanganKomoditasTitle">Padi</span>
                                </h6>
                                <span class="badge bg-warning text-dark">Tanaman Pangan</span>
                            </div>
                            <div class="card-body">
                                <div id="chartPanganPublic"></div>
                            </div>
                        </div>

                        <!-- Tabel Detail Per Tahun -->
                        <div class="card shadow-sm">
                            <div
                                class="card-header bg-white py-3 d-flex justify-content-between align-items-center flex-wrap">
                                <h6 class="mb-0 fw-bold text-success">
                                    <i class="bx bx-table me-1"></i> Data Historis Tahunan: <span
                                        id="tablePanganTitle">Padi</span>
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover align-middle w-100"
                                        id="tablePanganPublic">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="text-center" style="width: 5%">No</th>
                                                <th class="text-center">Tahun</th>
                                                <th class="text-end">Luas Tanam (Ha)</th>
                                                <th class="text-end">Luas Panen (Ha)</th>
                                                <th class="text-end">Produktivitas (Kw/Ha)</th>
                                                <th class="text-end">Total Produksi (Ton)</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbodyPanganPublic">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="content-section">
        <div class="container">

        </div>
    </section>
    <script>
        const kelompok = @json($data_tani ?? []);

        // Hitung jumlah kelompok per kecamatan
        const mapKecamatan = {};
        if (Array.isArray(kelompok)) {
            kelompok.forEach(item => {
                const kec = item.kecamatan;
                mapKecamatan[kec] = (mapKecamatan[kec] || 0) + 1;
            });
        }

        const categories = Object.keys(mapKecamatan);
        const values = Object.values(mapKecamatan);

        renderChart(categories, values);


        function renderChart(categories, values) {
            var options = {
                series: [{
                    name: 'Jumlah Kelompok Tani',
                    data: values
                }],
                chart: {
                    height: 400,
                    type: 'bar'
                },
                colors: ['#16a34a'],
                plotOptions: {
                    bar: {
                        borderRadius: 6,
                        dataLabels: {
                            position: 'top'
                        }
                    }
                },
                dataLabels: {
                    enabled: true,
                    formatter: function (val) {
                        return val;
                    },
                    offsetY: -20,
                    style: {
                        fontSize: '12px'
                    }
                },
                xaxis: {
                    categories: categories,
                    position: 'bottom',
                    labels: {
                        rotate: -45
                    }
                },
                yaxis: {
                    labels: {
                        formatter: function (val) {
                            return Math.floor(val);
                        }
                    }
                },
                title: {
                    text: 'Jumlah Kelompok Tani per Kecamatan',
                    align: 'center'
                }
            };

            var chart = new ApexCharts(document.querySelector("#chart"), options);
            chart.render();
        }

        var options = {
            series: [{
                name: 'Produksi',
                data: [
                    <?php
    foreach ($produksi as $item) {
        echo $item->value . ',';
    }
                                        ?>
                ]
            }],
            chart: {
                type: 'bar',
                height: 350
            },
            colors: ['#16a34a'],
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    borderRadiusApplication: 'end',
                    horizontal: true,
                }
            },
            dataLabels: {
                enabled: false
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return val + ' ton';
                    }
                }
            },
            xaxis: {
                categories: [
                    <?php
    foreach ($produksi as $item) {
        echo "'" . $item->name . "',";
    }
                                        ?>
                ],
            }
        };

        let chartProduksi = null;

        document.querySelectorAll('[data-bs-toggle="pill"]').forEach(el => {
            el.addEventListener('shown.bs.tab', function (e) {
                const target = e.target.getAttribute('data-bs-target');

                if (target === '#produksi' && chartProduksi === null) {
                    chartProduksi = new ApexCharts(
                        document.querySelector("#chart2"),
                        options
                    );
                    chartProduksi.render();
                }
            });
        });

        var donutOptions = {
            series: [35, 40, 15, 8, 2],
            chart: {
                type: 'donut',
                height: 320,
                fontFamily: '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif'
            },
            labels: ['Sangat Tahan', 'Tahan', 'Agak Rawan', 'Rawan', 'Sangat Rawan'],
            colors: ['#10b981', '#84cc16', '#fbbf24', '#f97316', '#ef4444'],
            dataLabels: {
                enabled: false
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '70%',
                        labels: {
                            show: false
                        }
                    }
                }
            },
            legend: {
                position: 'right',
                offsetY: 40,
                fontSize: '13px',
                fontWeight: 500,
                markers: {
                    width: 10,
                    height: 10,
                    radius: 2
                },
                itemMargin: {
                    vertical: 8
                },
                formatter: function (seriesName, opts) {
                    return seriesName + ": " + opts.w.globals.series[opts.seriesIndex] + "%"
                }
            },
            stroke: {
                width: 0
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return val + "%"
                    }
                }
            }
        };

        var barOptions = {
            series: [{
                name: 'Persentase',
                data: [35, 40, 15, 8, 2]
            }],
            chart: {
                type: 'bar',
                height: 320,
                fontFamily: '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif',
                toolbar: {
                    show: false
                }
            },
            plotOptions: {
                bar: {
                    horizontal: true,
                    borderRadius: 6,
                    barHeight: '50%',
                    distributed: true
                }
            },
            colors: ['#10b981', '#84cc16', '#fbbf24', '#f97316', '#ef4444'],
            dataLabels: {
                enabled: true,
                formatter: function (val) {
                    return val + "%"
                },
                offsetX: 30,
                style: {
                    fontSize: '13px',
                    fontWeight: 600,
                    colors: ['#1a1a1a']
                }
            },
            xaxis: {
                categories: ['Sangat Tahan', 'Tahan', 'Agak Rawan', 'Rawan', 'Sangat Rawan'],
                labels: {
                    show: false
                },
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                }
            },
            yaxis: {
                labels: {
                    style: {
                        fontSize: '13px',
                        fontWeight: 500,
                        colors: ['#1a1a1a']
                    }
                }
            },
            grid: {
                show: false,
                padding: {
                    left: 0,
                    right: 20
                }
            },
            legend: {
                show: false
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return val + "%"
                    }
                }
            }
        };

        let donutChart = null;
        let barChart = null;

        document.querySelectorAll('[data-bs-toggle="pill"]').forEach(el => {
            el.addEventListener('shown.bs.tab', function (e) {
                const target = e.target.getAttribute('data-bs-target');

                if (target === '#ketahanan') {

                    if (!donutChart) {
                        donutChart = new ApexCharts(
                            document.querySelector("#donutChart"),
                            donutOptions
                        );
                        donutChart.render();
                    }

                    if (!barChart) {
                        barChart = new ApexCharts(
                            document.querySelector("#barChart"),
                            barOptions
                        );
                        barChart.render();
                    }

                }

                if (target === '#padi-palawija') {
                    setTimeout(() => {
                        if (!chartPadi) {
                            initPadiChart();
                        }
                        updatePadiView(currentKomoditas);
                    }, 60);
                }

                if (target === '#sayuran') {
                    setTimeout(() => {
                        if (!chartSayuran) {
                            initSayuranChart();
                        }
                        updateSayuranView(currentSayuran);
                    }, 60);
                }

                if (target === '#tanaman-pangan') {
                    setTimeout(() => {
                        if (!chartPangan) {
                            initPanganChart();
                        }
                        updateTanamanPanganView(currentPangan);
                    }, 60);
                }
            });
        });

        document.addEventListener('DOMContentLoaded', function () {
            updatePadiView('Padi');
            updateSayuranView('Bawang Merah');
            updateTanamanPanganView('Padi');

            // Auto activate tab Padi & Palawija jika hash #padi-palawija atau ada query string tahun
            if (window.location.hash === '#padi-palawija' || new URLSearchParams(window.location.search).has('tahun')) {
                const btnTabPadi = document.querySelector('[data-bs-target="#padi-palawija"]');
                if (btnTabPadi) {
                    const tabTrigger = new bootstrap.Tab(btnTabPadi);
                    tabTrigger.show();
                }
            }

            if (window.location.hash === '#sayuran') {
                const btnTabSayuran = document.querySelector('[data-bs-target="#sayuran"]');
                if (btnTabSayuran) {
                    const tabTrigger = new bootstrap.Tab(btnTabSayuran);
                    tabTrigger.show();
                }
            }

            if (window.location.hash === '#tanaman-pangan') {
                const btnTabPangan = document.querySelector('[data-bs-target="#tanaman-pangan"]');
                if (btnTabPangan) {
                    const tabTrigger = new bootstrap.Tab(btnTabPangan);
                    tabTrigger.show();
                }
            }
        });

        // ================= PADI & PALAWIJA LOGIC =================
        const padiPalawijaGrouped = @json($padiPalawijaGrouped ?? []);
        const padiPalawijaSummaryGrouped = @json($padiPalawijaSummaryGrouped ?? []);
        const padiKategoriList = @json($padiKategoriList ?? []);
        let currentKategori = 'tanam';
        let currentKomoditas = 'Padi';
        let chartPadi = null;

        function updatePadiView() {
            const katConfig = padiKategoriList[currentKategori] || {
                label: 'Luas Tanam',
                satuan: 'Ha',
                target_sub1_label: 'Target Okmar (MH)',
                target_sub2_label: 'Target Asep (MK)',
                realisasi_sub1_label: 'Realisasi MH',
                realisasi_sub2_label: 'Realisasi MK'
            };

            const katData = padiPalawijaGrouped[currentKategori] || {};
            const katSummary = padiPalawijaSummaryGrouped[currentKategori] || {};
            const summary = katSummary[currentKomoditas] || {
                total_sawah: 0,
                target_sub1: 0,
                target_sub2: 0,
                target_total: 0,
                realisasi_sub1: 0,
                realisasi_sub2: 0,
                total_realisasi: 0,
                avg_target: 0,
                avg_realisasi: 0,
                persentase: 0
            };

            const unit = katConfig.satuan;
            const rows = katData[currentKomoditas] || [];

            // Update Badge & Header Titles
            const elActiveBadge = document.getElementById('padiActiveBadge');
            if (elActiveBadge) {
                elActiveBadge.innerHTML = `<i class="bx bx-check-shield me-1"></i> ${katConfig.label} - ${currentKomoditas} Tahun {{ $selectedTahun }}`;
            }

            const chartBadge = document.getElementById('chartKomoditasBadge');
            if (chartBadge) {
                chartBadge.textContent = `${currentKomoditas} (${katConfig.label})`;
            }

            const chartTitle = document.getElementById('chartPadiTitle');
            if (chartTitle) {
                chartTitle.innerHTML = `<i class="bx bx-bar-chart-alt-2 me-1"></i> Grafik Target vs Realisasi ${katConfig.label} ${currentKomoditas} per Kecamatan (${unit})`;
            }

            const tableTitle = document.getElementById('tablePadiTitle');
            if (tableTitle) {
                tableTitle.innerHTML = `<i class="bx bx-table me-1"></i> Data Rincian ${katConfig.label} ${currentKomoditas} per Kecamatan (Tahun {{ $selectedTahun }})`;
            }

            // Update 4 KPI Cards
            const c1Title = document.getElementById('kpiCard1Title');
            const c1Val = document.getElementById('kpiSawah');
            const c1Sub = document.getElementById('kpiSawahSub');

            const c2Title = document.getElementById('kpiCard2Title');
            const c2Val = document.getElementById('kpiTarget');
            const c2Sub = document.getElementById('kpiCard2Sub');

            const c3Title = document.getElementById('kpiCard3Title');
            const c3Val = document.getElementById('kpiRealisasi');
            const c3Sub = document.getElementById('kpiCard3Sub');

            const c4Title = document.getElementById('kpiCard4Title');
            const c4Val = document.getElementById('kpiPersen');
            const c4Sub = document.getElementById('kpiCard4Sub');

            if (currentKategori === 'produktivitas') {
                if (c1Title) c1Title.textContent = 'Baku Lahan';
                if (c1Val) c1Val.textContent = Number(summary.total_sawah || 0).toLocaleString('id-ID') + ' Ha';
                if (c1Sub) c1Sub.textContent = 'Kabupaten Kuningan';

                if (c2Title) c2Title.textContent = 'Target Rata-rata';
                if (c2Val) c2Val.textContent = Number(summary.avg_target || 0).toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' Kw/Ha';
                if (c2Sub) c2Sub.textContent = 'Target Produktivitas';

                if (c3Title) c3Title.textContent = 'Rata-rata Tahunan';
                if (c3Val) c3Val.textContent = Number(summary.avg_realisasi || 0).toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' Kw/Ha';
                if (c3Sub) c3Sub.textContent = 'Realisasi Capaian';

                if (c4Title) c4Title.textContent = 'Capaian';
                if (c4Val) c4Val.textContent = Number(summary.persentase || 0).toFixed(2) + '%';
                if (c4Sub) c4Sub.textContent = 'Terhadap Target';
            } else if (currentKategori === 'puso') {
                const affected = rows.filter(r => Number(r.total_realisasi || 0) > 0).length;

                if (c1Title) c1Title.textContent = 'Baku Lahan';
                if (c1Val) c1Val.textContent = Number(summary.total_sawah || 0).toLocaleString('id-ID') + ' Ha';
                if (c1Sub) c1Sub.textContent = 'Kabupaten Kuningan';

                if (c2Title) c2Title.textContent = 'Puso Semester 1';
                if (c2Val) c2Val.textContent = Number(summary.realisasi_sub1 || 0).toLocaleString('id-ID') + ' Ha';
                if (c2Sub) c2Sub.textContent = 'Januari - Juni';

                if (c3Title) c3Title.textContent = 'Puso Semester 2';
                if (c3Val) c3Val.textContent = Number(summary.realisasi_sub2 || 0).toLocaleString('id-ID') + ' Ha';
                if (c3Sub) c3Sub.textContent = 'Juli - Desember';

                if (c4Title) c4Title.textContent = 'Total Puso';
                if (c4Val) c4Val.textContent = Number(summary.total_realisasi || 0).toLocaleString('id-ID') + ' Ha';
                if (c4Sub) c4Sub.textContent = `${affected} Kecamatan Terdampak`;
            } else {
                if (c1Title) c1Title.textContent = (currentKategori === 'tanam' ? 'Luas Sawah' : 'Baku Lahan');
                if (c1Val) c1Val.textContent = Number(summary.total_sawah || 0).toLocaleString('id-ID') + ' Ha';
                if (c1Sub) c1Sub.textContent = 'Kabupaten Kuningan';

                if (c2Title) c2Title.textContent = `Target ${katConfig.label}`;
                if (c2Val) c2Val.textContent = Number(summary.target_total || 0).toLocaleString('id-ID') + ' ' + unit;
                if (c2Sub) c2Sub.textContent = currentKategori === 'tanam' ? 'Okmar + Asep' : 'Jan-Jun + Jul-Des';

                if (c3Title) c3Title.textContent = `Realisasi ${katConfig.label}`;
                if (c3Val) c3Val.textContent = Number(summary.total_realisasi || 0).toLocaleString('id-ID') + ' ' + unit;
                if (c3Sub) c3Sub.textContent = currentKategori === 'tanam' ? 'MH + MK' : 'Semester 1 + 2';

                if (c4Title) c4Title.textContent = 'Capaian Target';
                if (c4Val) c4Val.textContent = Number(summary.persentase || 0).toFixed(2) + '%';
                if (c4Sub) c4Sub.textContent = 'Persentase Capaian';
            }

            // Update Table Header
            const thead = document.getElementById('theadPadiPalawija');
            if (thead) {
                if (currentKategori === 'puso') {
                    thead.innerHTML = `
                        <tr>
                            <th class="text-center" style="width: 4%">No</th>
                            <th>Kecamatan</th>
                            <th class="text-end">Baku Lahan (Ha)</th>
                            <th class="text-end">Puso Jan-Jun (Ha)</th>
                            <th class="text-end">Puso Jul-Des (Ha)</th>
                            <th class="text-end">Total Puso (Ha)</th>
                            <th class="text-center">Status</th>
                            <th class="text-center" style="width: 8%">Rincian</th>
                        </tr>
                    `;
                } else {
                    thead.innerHTML = `
                        <tr>
                            <th class="text-center" style="width: 4%">No</th>
                            <th>Kecamatan</th>
                            <th class="text-end">${currentKategori === 'tanam' ? 'Luas Sawah (Ha)' : 'Baku Lahan (Ha)'}</th>
                            <th class="text-end">${katConfig.target_sub1_label}</th>
                            <th class="text-end">${katConfig.target_sub2_label}</th>
                            <th class="text-end">Target Total</th>
                            <th class="text-end">${katConfig.realisasi_sub1_label}</th>
                            <th class="text-end">${katConfig.realisasi_sub2_label}</th>
                            <th class="text-end">Total Realisasi</th>
                            <th class="text-center">Capaian</th>
                            <th class="text-center" style="width: 8%">Rincian</th>
                        </tr>
                    `;
                }
            }

            // Update Table Body & Chart
            const tbody = document.getElementById('tbodyPadiPalawija');
            if (tbody) {
                tbody.innerHTML = '';
                const categories = [];
                const targetData = [];
                const realisasiData = [];

                rows.forEach((row, idx) => {
                    categories.push(row.kecamatan);
                    targetData.push(Number(row.target_total || 0));
                    realisasiData.push(Number(row.total_realisasi || 0));

                    const p = Number(row.persentase_capaian || 0);
                    let badgeClass = 'badge-capaian-low';
                    if (p >= 100) badgeClass = 'badge-capaian-high';
                    else if (p >= 50) badgeClass = 'badge-capaian-medium';

                    const tr = document.createElement('tr');
                    const rowJson = JSON.stringify(row).replace(/'/g, "&apos;");

                    if (currentKategori === 'puso') {
                        const totPuso = Number(row.total_realisasi || 0);
                        const statusBadge = totPuso > 0 
                            ? `<span class="badge bg-danger">Terdampak Puso</span>`
                            : `<span class="badge bg-success">Aman (Nihil)</span>`;

                        tr.innerHTML = `
                            <td class="text-center">${row.no_urut || idx + 1}</td>
                            <td><strong>${row.kecamatan}</strong></td>
                            <td class="text-end">${Number(row.luas_sawah || 0).toLocaleString('id-ID')}</td>
                            <td class="text-end">${Number(row.jumlah_mh || 0).toLocaleString('id-ID')}</td>
                            <td class="text-end">${Number(row.jumlah_mk || 0).toLocaleString('id-ID')}</td>
                            <td class="text-end ${totPuso > 0 ? 'text-danger fw-bold' : 'text-success'}">${totPuso.toLocaleString('id-ID')}</td>
                            <td class="text-center">${statusBadge}</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-outline-success btn-pub-detail" data-row='${rowJson}'>
                                    <i class="bx bx-calendar"></i>
                                </button>
                            </td>
                        `;
                    } else {
                        const isDec = currentKategori === 'produktivitas';
                        const formatVal = (v) => isDec ? Number(v || 0).toFixed(2) : Number(v || 0).toLocaleString('id-ID');

                        tr.innerHTML = `
                            <td class="text-center">${row.no_urut || idx + 1}</td>
                            <td><strong>${row.kecamatan}</strong></td>
                            <td class="text-end">${Number(row.luas_sawah || 0).toLocaleString('id-ID')}</td>
                            <td class="text-end text-muted">${formatVal(row.target_okmar)}</td>
                            <td class="text-end text-muted">${formatVal(row.target_asep)}</td>
                            <td class="text-end text-primary fw-semibold">${formatVal(row.target_total)}</td>
                            <td class="text-end">${formatVal(row.jumlah_mh)}</td>
                            <td class="text-end">${formatVal(row.jumlah_mk)}</td>
                            <td class="text-end text-success fw-bold">${formatVal(row.total_realisasi)}</td>
                            <td class="text-center"><span class="badge ${badgeClass} px-2 py-1">${p}%</span></td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-outline-success btn-pub-detail" data-row='${rowJson}'>
                                    <i class="bx bx-calendar"></i>
                                </button>
                            </td>
                        `;
                    }
                    tbody.appendChild(tr);
                });

                // Attach modal detail listener
                document.querySelectorAll('.btn-pub-detail').forEach(b => {
                    b.addEventListener('click', function () {
                        const r = JSON.parse(this.getAttribute('data-row'));
                        openPubDetailModal(r, katConfig);
                    });
                });

                // Update Chart
                if (chartPadi) {
                    chartPadi.updateOptions({
                        xaxis: { categories: categories },
                        yaxis: {
                            title: { text: `${katConfig.label} (${unit})` }
                        },
                        tooltip: {
                            y: {
                                formatter: function (val) {
                                    return Number(val).toLocaleString('id-ID', { maximumFractionDigits: 2 }) + ' ' + unit;
                                }
                            }
                        }
                    });

                    if (currentKategori === 'puso') {
                        chartPadi.updateSeries([
                            { name: `Luas Puso (${unit})`, data: realisasiData }
                        ]);
                    } else {
                        chartPadi.updateSeries([
                            { name: `Target (${unit})`, data: targetData },
                            { name: `Realisasi (${unit})`, data: realisasiData }
                        ]);
                    }
                }
            }
        }

        function openPubDetailModal(row, katConfig) {
            const sat = katConfig.satuan;
            const isTanam = currentKategori === 'tanam';

            document.getElementById('pub_detail_kecamatan').textContent = row.kecamatan;
            document.getElementById('pub_detail_badge').textContent = `${katConfig.label} - ${currentKomoditas} (${sat})`;

            const formatNum = (n) => {
                const num = parseFloat(n) || 0;
                return num.toLocaleString('id-ID', { maximumFractionDigits: 2 });
            };

            document.getElementById('pub_detail_sub1').textContent = formatNum(row.jumlah_mh) + ' ' + sat;
            document.getElementById('pub_detail_sub2').textContent = formatNum(row.jumlah_mk) + ' ' + sat;
            document.getElementById('pub_detail_total').textContent = formatNum(row.total_realisasi) + ' ' + sat;

            document.getElementById('pub_label_sem1').innerHTML = isTanam 
                ? '<i class="bx bx-circle text-primary me-1"></i> Musim Hujan (MH)' 
                : '<i class="bx bx-circle text-primary me-1"></i> Semester 1 (Jan - Jun)';
            document.getElementById('pub_label_sem2').innerHTML = isTanam 
                ? '<i class="bx bx-circle text-warning me-1"></i> Musim Kemarau (MK)' 
                : '<i class="bx bx-circle text-warning me-1"></i> Semester 2 (Jul - Des)';

            let mSem1 = [];
            let mSem2 = [];

            if (isTanam) {
                mSem1 = [
                    { label: 'Okt', val: row.realisasi_okt },
                    { label: 'Nop', val: row.realisasi_nop },
                    { label: 'Des', val: row.realisasi_des },
                    { label: 'Jan', val: row.realisasi_jan },
                    { label: 'Feb', val: row.realisasi_feb },
                    { label: 'Mar', val: row.realisasi_mar },
                ];
                mSem2 = [
                    { label: 'Apr', val: row.realisasi_apr },
                    { label: 'Mei', val: row.realisasi_mei },
                    { label: 'Jun', val: row.realisasi_jun },
                    { label: 'Jul', val: row.realisasi_jul },
                    { label: 'Ags', val: row.realisasi_ags },
                    { label: 'Sep', val: row.realisasi_sep },
                ];
            } else {
                mSem1 = [
                    { label: 'Jan', val: row.realisasi_jan },
                    { label: 'Feb', val: row.realisasi_feb },
                    { label: 'Mar', val: row.realisasi_mar },
                    { label: 'Apr', val: row.realisasi_apr },
                    { label: 'Mei', val: row.realisasi_mei },
                    { label: 'Jun', val: row.realisasi_jun },
                ];
                mSem2 = [
                    { label: 'Jul', val: row.realisasi_jul },
                    { label: 'Ags', val: row.realisasi_ags },
                    { label: 'Sep', val: row.realisasi_sep },
                    { label: 'Okt', val: row.realisasi_okt },
                    { label: 'Nov', val: row.realisasi_nop },
                    { label: 'Des', val: row.realisasi_des },
                ];
            }

            document.getElementById('pub_cards_sem1').innerHTML = mSem1.map(m => `
                <div class="col-4 col-sm-2">
                    <div class="p-2 border rounded bg-white">
                        <div class="text-muted small">${m.label}</div>
                        <div class="fw-bold text-dark fs-7">${formatNum(m.val)}</div>
                    </div>
                </div>
            `).join('');

            document.getElementById('pub_cards_sem2').innerHTML = mSem2.map(m => `
                <div class="col-4 col-sm-2">
                    <div class="p-2 border rounded bg-white">
                        <div class="text-muted small">${m.label}</div>
                        <div class="fw-bold text-dark fs-7">${formatNum(m.val)}</div>
                    </div>
                </div>
            `).join('');

            const modalEl = document.getElementById('modalDetailBulananPublik');
            if (modalEl) {
                const modal = new bootstrap.Modal(modalEl);
                modal.show();
            }
        }

        function initPadiChart() {
            if (chartPadi) return;
            const katData = padiPalawijaGrouped[currentKategori] || {};
            const rows = katData[currentKomoditas] || [];
            const categories = rows.map(r => r.kecamatan);
            const targetData = rows.map(r => Number(r.target_total || 0));
            const realisasiData = rows.map(r => Number(r.total_realisasi || 0));

            const options = {
                series: [
                    { name: 'Target (Ha)', data: targetData },
                    { name: 'Realisasi (Ha)', data: realisasiData }
                ],
                chart: {
                    type: 'bar',
                    height: 400,
                    fontFamily: 'Poppins, sans-serif',
                    toolbar: { show: true }
                },
                colors: ['#f59e0b', '#16a34a'],
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '55%',
                        borderRadius: 3
                    }
                },
                dataLabels: { enabled: false },
                stroke: { show: true, width: 2, colors: ['transparent'] },
                xaxis: {
                    categories: categories,
                    labels: {
                        rotate: -45,
                        style: { fontSize: '11px' }
                    }
                },
                yaxis: {
                    title: { text: 'Luas (Hektar)' }
                },
                fill: { opacity: 1 },
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return Number(val).toLocaleString('id-ID') + ' Ha';
                        }
                    }
                }
            };

            const chartEl = document.querySelector("#chartPadiPalawija");
            if (chartEl) {
                chartPadi = new ApexCharts(chartEl, options);
                chartPadi.render();
            }
        }

        // Sub-pills listener Komoditas Padi
        document.querySelectorAll('.btn-padi-pill').forEach(btn => {
            btn.addEventListener('click', function () {
                document.querySelectorAll('.btn-padi-pill').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                currentKomoditas = this.getAttribute('data-komoditas');
                updatePadiView();
            });
        });

        // Sub-pills listener Kategori Padi
        document.querySelectorAll('.btn-padi-kat-pill').forEach(btn => {
            btn.addEventListener('click', function () {
                document.querySelectorAll('.btn-padi-kat-pill').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                currentKategori = this.getAttribute('data-kategori');
                updatePadiView();
            });
        });

        // ================= SAYURAN & HORTIKULTURA LOGIC =================
        const sayuranData = @json($sayuranData ?? []);
        const sayuranSummary = @json($sayuranSummary ?? []);
        let currentSayuran = 'Bawang Merah';
        let chartSayuran = null;

        function updateSayuranView(komoditas) {
            currentSayuran = komoditas;
            const summary = sayuranSummary[komoditas] || {
                total_tanam: 0,
                total_panen: 0,
                avg_produktivitas: 0,
                total_produksi: 0,
                satuan_luas: 'Ha',
                satuan_produksi: 'Kuintal',
                kategori: 'Sayuran'
            };

            const sLuas = summary.satuan_luas || 'Ha';
            const sProd = summary.satuan_produksi || 'Kuintal';

            // KPI Update
            const elTanam = document.getElementById('kpiSayuranTanam');
            const elPanen = document.getElementById('kpiSayuranPanen');
            const elProdv = document.getElementById('kpiSayuranProdv');
            const elProduksi = document.getElementById('kpiSayuranProduksi');
            const elBadge = document.getElementById('chartSayuranBadge');
            const elChartTitle = document.getElementById('chartSayuranKomoditasTitle');
            const elTableTitle = document.getElementById('tableSayuranTitle');

            if (elTanam) elTanam.textContent = Number(summary.total_tanam || 0).toLocaleString('id-ID') + ' ' + sLuas;
            if (elPanen) elPanen.textContent = Number(summary.total_panen || 0).toLocaleString('id-ID') + ' ' + sLuas;
            if (elProdv) elProdv.textContent = Number(summary.avg_produktivitas || 0).toFixed(2);
            if (elProduksi) elProduksi.textContent = Number(summary.total_produksi || 0).toLocaleString('id-ID') + ' ' + sProd;
            if (elBadge) elBadge.textContent = summary.kategori || 'Sayuran';
            if (elChartTitle) elChartTitle.textContent = komoditas;
            if (elTableTitle) elTableTitle.textContent = komoditas;

            const elSatTanam = document.getElementById('kpiSayuranSatuanTanam');
            const elSatPanen = document.getElementById('kpiSayuranSatuanPanen');
            const elSatProd = document.getElementById('kpiSayuranSatuanProduksi');
            if (elSatTanam) elSatTanam.textContent = `Satuan: ${sLuas}`;
            if (elSatPanen) elSatPanen.textContent = `Satuan: ${sLuas}`;
            if (elSatProd) elSatProd.textContent = `Satuan: ${sProd}`;

            // Update Header Tabel
            const thTanam = document.getElementById('thSayuranTanam');
            const thPanen = document.getElementById('thSayuranPanen');
            const thProduksi = document.getElementById('thSayuranProduksi');
            if (thTanam) thTanam.textContent = `Luas Tanam (${sLuas})`;
            if (thPanen) thPanen.textContent = `Luas Panen (${sLuas})`;
            if (thProduksi) thProduksi.textContent = `Total Produksi (${sProd})`;

            // Render Table Rows
            const rows = sayuranData[komoditas] || [];
            const tbody = document.getElementById('tbodySayuranPublic');
            if (tbody) {
                tbody.innerHTML = '';
                const categories = [];
                const tanamSeries = [];
                const panenSeries = [];
                const produksiSeries = [];

                rows.forEach((row, idx) => {
                    categories.push(row.tahun);
                    tanamSeries.push(Number(row.luas_tanam || 0));
                    panenSeries.push(Number(row.luas_panen || 0));
                    produksiSeries.push(Number(row.produksi || 0));

                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                                            <td class="text-center">${idx + 1}</td>
                                            <td class="text-center fw-bold text-success">${row.tahun}</td>
                                            <td class="text-end">${Number(row.luas_tanam || 0).toLocaleString('id-ID')}</td>
                                            <td class="text-end">${Number(row.luas_panen || 0).toLocaleString('id-ID')}</td>
                                            <td class="text-end text-primary fw-semibold">${Number(row.produktivitas || 0).toFixed(2)}</td>
                                            <td class="text-end text-success fw-bold">${Number(row.produksi || 0).toLocaleString('id-ID')}</td>
                                        `;
                    tbody.appendChild(tr);
                });

                // Update Chart
                if (chartSayuran) {
                    chartSayuran.updateOptions({
                        xaxis: { categories: categories },
                        yaxis: [
                            { title: { text: `Luas (${sLuas})` } },
                            { opposite: true, title: { text: `Produksi (${sProd})` } }
                        ]
                    });
                    chartSayuran.updateSeries([
                        { name: `Luas Tanam (${sLuas})`, type: 'column', data: tanamSeries },
                        { name: `Luas Panen (${sLuas})`, type: 'column', data: panenSeries },
                        { name: `Produksi (${sProd})`, type: 'line', data: produksiSeries }
                    ]);
                }
            }
        }

        function initSayuranChart() {
            if (chartSayuran) return;
            const rows = sayuranData[currentSayuran] || [];
            const categories = rows.map(r => r.tahun);
            const tanamSeries = rows.map(r => Number(r.luas_tanam || 0));
            const panenSeries = rows.map(r => Number(r.luas_panen || 0));
            const produksiSeries = rows.map(r => Number(r.produksi || 0));

            const summary = sayuranSummary[currentSayuran] || {};
            const sLuas = summary.satuan_luas || 'Ha';
            const sProd = summary.satuan_produksi || 'Kuintal';

            const options = {
                series: [
                    { name: `Luas Tanam (${sLuas})`, type: 'column', data: tanamSeries },
                    { name: `Luas Panen (${sLuas})`, type: 'column', data: panenSeries },
                    { name: `Produksi (${sProd})`, type: 'line', data: produksiSeries }
                ],
                chart: {
                    height: 380,
                    type: 'line',
                    fontFamily: 'Poppins, sans-serif',
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
                    { title: { text: `Luas (${sLuas})` } },
                    { opposite: true, title: { text: `Produksi (${sProd})` } }
                ],
                tooltip: {
                    shared: true,
                    intersect: false
                },
                legend: {
                    position: 'top'
                }
            };

            const chartEl = document.querySelector("#chartSayuranPublic");
            if (chartEl) {
                chartSayuran = new ApexCharts(chartEl, options);
                chartSayuran.render();
            }
        }

        // Sub-pills listener Sayuran
        document.querySelectorAll('.btn-sayuran-pill').forEach(btn => {
            btn.addEventListener('click', function () {
                document.querySelectorAll('.btn-sayuran-pill').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                const komoditas = this.getAttribute('data-komoditas');
                updateSayuranView(komoditas);
            });
        });

        // ================= TANAMAN PANGAN (13 TAHUN) LOGIC =================
        const tanamanPanganData = @json($tanamanPanganData ?? []);
        const tanamanPanganSummary = @json($tanamanPanganSummary ?? []);
        let currentPangan = 'Padi';
        let chartPangan = null;

        function updateTanamanPanganView(komoditas) {
            currentPangan = komoditas;
            const summary = tanamanPanganSummary[komoditas] || {
                total_tanam: 0,
                total_panen: 0,
                avg_produktivitas: 0,
                total_produksi: 0,
                latest_year: 2025
            };

            const elTanam = document.getElementById('kpiPanganTanam');
            const elPanen = document.getElementById('kpiPanganPanen');
            const elProdv = document.getElementById('kpiPanganProdv');
            const elProduksi = document.getElementById('kpiPanganProduksi');
            const elChartTitle = document.getElementById('chartPanganKomoditasTitle');
            const elTableTitle = document.getElementById('tablePanganTitle');

            if (elTanam) elTanam.innerText = Number(summary.total_tanam || 0).toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 2 }) + ' Ha';
            if (elPanen) elPanen.innerText = Number(summary.total_panen || 0).toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 2 }) + ' Ha';
            if (elProdv) elProdv.innerText = Number(summary.avg_produktivitas || 0).toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' Kw/Ha';
            if (elProduksi) elProduksi.innerText = Number(summary.total_produksi || 0).toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 2 }) + ' Ton';

            if (elChartTitle) elChartTitle.innerText = komoditas;
            if (elTableTitle) elTableTitle.innerText = komoditas;

            // Render table
            const tbody = document.getElementById('tbodyPanganPublic');
            if (tbody) {
                const rows = tanamanPanganData[komoditas] || [];
                if (rows.length === 0) {
                    tbody.innerHTML = `<tr><td colspan="6" class="text-center py-4 text-muted">Belum ada data untuk komoditas ini.</td></tr>`;
                } else {
                    let html = '';
                    rows.forEach((r, idx) => {
                        html += `
                                                <tr>
                                                    <td class="text-center">${idx + 1}</td>
                                                    <td class="text-center fw-bold text-success">${r.tahun}</td>
                                                    <td class="text-end">${Number(r.luas_tanam || 0).toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 2 })}</td>
                                                    <td class="text-end">${Number(r.luas_panen || 0).toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 2 })}</td>
                                                    <td class="text-end">${Number(r.produktivitas || 0).toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                                                    <td class="text-end fw-semibold text-primary">${Number(r.produksi || 0).toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 2 })}</td>
                                                </tr>
                                            `;
                    });
                    tbody.innerHTML = html;
                }
            }

            // Update chart
            if (chartPangan) {
                const rows = tanamanPanganData[komoditas] || [];
                const categories = rows.map(r => r.tahun);
                const tanamSeries = rows.map(r => Number(r.luas_tanam || 0));
                const panenSeries = rows.map(r => Number(r.luas_panen || 0));
                const produksiSeries = rows.map(r => Number(r.produksi || 0));

                chartPangan.updateOptions({
                    xaxis: { categories: categories }
                });
                chartPangan.updateSeries([
                    { name: 'Luas Tanam (Ha)', type: 'column', data: tanamSeries },
                    { name: 'Luas Panen (Ha)', type: 'column', data: panenSeries },
                    { name: 'Produksi (Ton)', type: 'line', data: produksiSeries }
                ]);
            }
        }

        function initPanganChart() {
            if (chartPangan) return;
            const rows = tanamanPanganData[currentPangan] || [];
            const categories = rows.map(r => r.tahun);
            const tanamSeries = rows.map(r => Number(r.luas_tanam || 0));
            const panenSeries = rows.map(r => Number(r.luas_panen || 0));
            const produksiSeries = rows.map(r => Number(r.produksi || 0));

            const options = {
                series: [
                    { name: 'Luas Tanam (Ha)', type: 'column', data: tanamSeries },
                    { name: 'Luas Panen (Ha)', type: 'column', data: panenSeries },
                    { name: 'Produksi (Ton)', type: 'line', data: produksiSeries }
                ],
                chart: {
                    height: 380,
                    type: 'line',
                    fontFamily: 'Poppins, sans-serif',
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
                    { title: { text: 'Luas (Ha)' } },
                    { opposite: true, title: { text: 'Produksi (Ton)' } }
                ],
                tooltip: {
                    shared: true,
                    intersect: false
                },
                legend: {
                    position: 'top'
                }
            };

            const chartEl = document.querySelector("#chartPanganPublic");
            if (chartEl) {
                chartPangan = new ApexCharts(chartEl, options);
                chartPangan.render();
            }
        }

        // Sub-pills listener Tanaman Pangan
        document.querySelectorAll('.btn-pangan-pill').forEach(btn => {
            btn.addEventListener('click', function () {
                document.querySelectorAll('.btn-pangan-pill').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                const komoditas = this.getAttribute('data-komoditas');
                updateTanamanPanganView(komoditas);
            });
        });
    </script>

@endsection