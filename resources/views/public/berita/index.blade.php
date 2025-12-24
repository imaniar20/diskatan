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
            padding: 100px 0;
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
    </style>
@endpush

@section('content')
    <!-- Pattern Overlay -->
    <div class="pattern-overlay"></div>

    <!-- HERO -->
    <section class="page-hero" style="--hero-bg: url('{{ asset('img/background/bg-2.jpg') }}');">
        <div class="container position-relative">
            <h1 class="page-title">Berita</h1>

            <nav class="breadcrumb-wrapper">
                <a href="{{ url('/') }}">Home</a>
                <span>›</span>
                <span class="active">Berita</span>
            </nav>
        </div>
    </section>

    <!-- CONTENT -->
    <section class="content-section">
        <div class="container">
            <div class="row">

                <!-- LIST BERITA -->
                <div class="col-lg-8">
                    <div class="row g-4">
                        @php
                            $i = 0;
                        @endphp
                        {{-- Loop agenda --}}
                        @foreach ($news as $item)
                            @php
                                $i++;
                            @endphp
                            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ $i * 100 }}">
                                <div class="card h-100 border-0 shadow-sm berita-card">
                                    <img src="{{ asset($item->thumbnail ? 'storage/' . $item->thumbnail : 'images/img_not_found.png') }}"
                                        class="card-img-top" alt="Berita {{ $i }}">

                                    <div class="card-body d-flex flex-column">
                                        <small class="text-muted mb-2">
                                            {{ \Carbon\Carbon::parse($item->published_at)->format('d M Y') }}
                                        </small>

                                        <h6 class="card-title fw-semibold">
                                            {{ Str::limit($item->title, 40) }}
                                        </h6>

                                        <p class="card-text small text-muted">
                                            {!! Str::limit($item->content, 160) !!}
                                        </p>

                                        <a href="{{ route('berita.detail', $item->slug) }}"
                                            class="mt-auto btn btn-sm btn-outline-success">
                                            <i class="bi bi-arrow-right-circle me-1"></i>
                                            Baca Selengkapnya
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>

                    <!-- PAGINATION -->
                    {{ $news->links('vendor.pagination.agenda') }}

                </div>

                <!-- SIDEBAR -->
                <div class="col-lg-4">

                    <!-- SEARCH -->
                    <div class="card border-0 shadow-sm mb-4 sidebar-card search-card" data-aos="fade-left"
                        data-aos-delay="200">
                        <div class="card-body">
                            <h6 class="fw-semibold mb-3">
                                <i class="bi bi-search me-2"></i>Cari Berita
                            </h6>
                            <form action="#" method="GET">
                                <div class="input-group">
                                    <input type="text" name="q" class="form-control"
                                        placeholder="Ketik judul berita...">
                                    <button class="btn btn-success" type="submit">
                                        <i class="bi bi-search"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- BERITA TERBARU -->
                    <div class="card border-0 shadow-sm mb-4 sidebar-card" data-aos="fade-left" data-aos-delay="300">
                        <div class="card-body">
                            <h6 class="fw-semibold mb-3">
                                <i class="bi bi-newspaper me-2"></i>Berita Terbaru
                            </h6>

                            @for ($i = 1; $i <= 5; $i++)
                                <a href="#" class="sidebar-news-item d-flex gap-3 mb-3 text-decoration-none">
                                    <!-- IMAGE -->
                                    <img src="https://images.unsplash.com/photo-{{ 1464226184 + $i }}884fa280b87c399?w=80&h=80&fit=crop"
                                        alt="Berita {{ $i }}" class="sidebar-news-img">
                                    <!-- TEXT -->
                                    <div class="flex-grow-1">
                                        <div class="fw-semibold small text-dark">
                                            @if ($i == 1)
                                                Sosialisasi Pupuk Subsidi untuk Petani
                                            @elseif($i == 2)
                                                Panen Raya Padi Organik Sukses
                                            @elseif($i == 3)
                                                Workshop Teknologi Pertanian Modern
                                            @elseif($i == 4)
                                                Distribusi Bibit Gratis untuk Masyarakat
                                            @else
                                                Peningkatan Kualitas Hasil Panen
                                            @endif
                                        </div>
                                        <div class="text-muted small">
                                            <i class="bi bi-calendar3"></i> {{ 15 + $i }} Des 2024
                                        </div>
                                    </div>
                                </a>
                            @endfor
                        </div>
                    </div>

                    <!-- PALING BANYAK DILIHAT -->
                    <div class="card border-0 shadow-sm sidebar-card" data-aos="fade-left" data-aos-delay="400">
                        <div class="card-body">
                            <h6 class="fw-semibold mb-3">
                                <i class="bi bi-fire me-2"></i>Paling Banyak Dilihat
                            </h6>

                            @for ($i = 1; $i <= 5; $i++)
                                <a href="#" class="sidebar-news-item d-flex gap-3 mb-3 text-decoration-none">
                                    <!-- IMAGE -->
                                    <img src="https://images.unsplash.com/photo-{{ 1542273917 + $i }}363-3b1817f69a2d?w=80&h=80&fit=crop" 
                                         alt="Popular {{ $i }}"
                                         class="sidebar-news-img">

                                    <!-- TEXT -->
                                    <div class="flex-grow-1">
                                        <div class="fw-semibold small text-dark">
                                            @if($i == 1)
                                                Inovasi Pertanian Ramah Lingkungan
                                            @elseif($i == 2)
                                                Program Ketahanan Pangan Daerah
                                            @elseif($i == 3)
                                                Ekspor Hasil Pertanian Meningkat
                                            @elseif($i == 4)
                                                Pelatihan Budidaya Organik Gratis
                                            @else
                                                Bantuan Alat Pertanian Modern
                                            @endif
                                        </div>
                                        <div class="text-muted small">
                                            <i class="bi bi-eye"></i> {{ rand(450, 980) }} views
                                        </div>
                                    </div>
                                </a>
                            @endfor
                        </div>
                    </div>

                </div>
            </div>
        </div>
        </div>
    </section>

@endsection
