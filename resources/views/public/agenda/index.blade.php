@extends('public.layouts.app')

@section('title', 'Agenda Kegiatan')

@push('styles')
<style>
    /* ============ PAGE HERO SECTION ============ */
    .page-hero {
        position: relative;
        height: 400px;
        background: linear-gradient(135deg, rgba(45, 106, 79, 0.85), rgba(64, 145, 108, 0.75)), 
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
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
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
        text-shadow: 2px 2px 20px rgba(0,0,0,0.3);
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
        color: rgba(255,255,255,0.9);
        text-decoration: none;
        transition: all 0.3s ease;
        font-weight: 500;
    }
    
    .breadcrumb-wrapper a:hover {
        color: #74c69d;
        transform: translateX(-2px);
    }
    
    .breadcrumb-wrapper span {
        color: rgba(255,255,255,0.7);
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
    
    /* ============ AGENDA CARD ============ */
    .agenda-card {
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.4s ease;
        border: 2px solid transparent;
        background: white;
    }
    
    .agenda-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 50px rgba(45, 106, 79, 0.15) !important;
        border-color: #52b788;
    }
    
    .agenda-img {
        object-fit: cover;
        height: 100%;
        min-height: 250px;
        transition: all 0.4s ease;
    }
    
    .agenda-card:hover .agenda-img {
        transform: scale(1.1);
    }
    
    .agenda-card .col-md-4 {
        overflow: hidden;
    }
    
    .agenda-card .card-body {
        padding: 25px;
    }
    
    .agenda-card h5 {
        color: #2d6a4f;
        font-family: 'Playfair Display', serif;
        font-weight: 700;
        transition: color 0.3s ease;
    }
    
    .agenda-card:hover h5 {
        color: #40916c;
    }
    
    .agenda-card small {
        font-size: 0.9rem;
    }
    
    .agenda-card small i {
        color: #52b788;
    }
    
    .agenda-card .btn-outline-success {
        border: 2px solid #52b788;
        color: #2d6a4f;
        font-weight: 600;
        padding: 8px 20px;
        border-radius: 25px;
        transition: all 0.3s ease;
    }
    
    .agenda-card .btn-outline-success:hover {
        background: linear-gradient(135deg, #2d6a4f, #40916c);
        border-color: #2d6a4f;
        color: white;
        transform: translateX(5px);
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
        
        .agenda-card .row {
            flex-direction: column;
        }
        
        .agenda-img {
            min-height: 200px;
        }
        
        .agenda-card .card-body {
            padding: 20px;
        }
        
        .sidebar-card {
            margin-top: 30px;
        }
    }
</style>
@endpush

@section('content')
    <!-- Pattern Overlay -->
    <div class="pattern-overlay"></div>

    <!-- HERO -->
    <section class="page-hero" style="--hero-bg: url('{{ asset('img/background/bg-1.jpg') }}');">
        <div class="container position-relative">
            <h1 class="page-title">Agenda Kegiatan</h1>

            <nav class="breadcrumb-wrapper">
                <a href="{{ url('/') }}">Home</a>
                <span>›</span>
                <span class="active">Agenda</span>
            </nav>
        </div>
    </section>

    <!-- CONTENT -->
    <section class="content-section">
        <div class="container">
            <div class="row">

                <!-- LIST AGENDA -->
                <div class="col-lg-8">

                    {{-- Loop agenda --}}
                    @for ($i = 1; $i <= 5; $i++)
                        <div class="card border-0 shadow-sm mb-4 agenda-card" data-aos="fade-up" data-aos-delay="{{ $i * 100 }}">
                            <div class="row g-0 align-items-center">

                                <!-- FOTO -->
                                <div class="col-md-4">
                                    <img src="https://images.unsplash.com/photo-{{ 1560493676 + $i }}04071c5f467b?w=500&h=350&fit=crop"
                                        class="img-fluid agenda-img" alt="Agenda {{ $i }}">
                                </div>

                                <!-- KONTEN -->
                                <div class="col-md-8">
                                    <div class="card-body">
                                        <small class="text-muted">
                                            <i class="bi bi-calendar-event"></i>
                                            {{ 20 + $i }} Desember 2024
                                            &nbsp;|&nbsp;
                                            <i class="bi bi-geo-alt"></i>
                                            Aula Dinas Pertanian
                                        </small>

                                        <h5 class="mt-2 fw-semibold">
                                            @if($i == 1)
                                                Sosialisasi Program Reboisasi 2025
                                            @elseif($i == 2)
                                                Pelatihan Konservasi Tanah dan Air
                                            @elseif($i == 3)
                                                Aksi Tanam Pohon Bersama Masyarakat
                                            @elseif($i == 4)
                                                Workshop Pengelolaan Hasil Hutan Lestari
                                            @else
                                                Rapat Koordinasi Program Ketahanan Pangan
                                            @endif
                                        </h5>

                                        <p class="text-muted small">
                                            @if($i == 1)
                                                Dinas Pertanian mengadakan sosialisasi program reboisasi untuk meningkatkan kesadaran masyarakat dalam menjaga kelestarian hutan dan lingkungan.
                                            @elseif($i == 2)
                                                Pelatihan untuk meningkatkan kapasitas petani dalam mengelola konservasi tanah dan air dengan metode modern dan ramah lingkungan.
                                            @elseif($i == 3)
                                                Kegiatan penanaman pohon bersama masyarakat di kawasan hutan lindung sebagai bentuk kepedulian terhadap lingkungan hidup.
                                            @elseif($i == 4)
                                                Workshop pengelolaan hasil hutan berkelanjutan untuk meningkatkan nilai tambah produk kehutanan masyarakat.
                                            @else
                                                Koordinasi program ketahanan pangan dengan seluruh stakeholder untuk memastikan ketersediaan pangan di wilayah Kuningan.
                                            @endif
                                        </p>

                                        <a href="#" class="btn btn-sm btn-outline-success">
                                            <i class="bi bi-eye me-1"></i>
                                            Lihat Detail Agenda
                                        </a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    @endfor

                    <!-- PAGINATION -->
                    <nav data-aos="fade-up" data-aos-delay="600">
                        <ul class="pagination pagination-sm">
                            <li class="page-item disabled">
                                <span class="page-link">‹ Prev</span>
                            </li>
                            <li class="page-item active">
                                <span class="page-link">1</span>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">2</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">3</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">Next ›</a>
                            </li>
                        </ul>
                    </nav>

                </div>

                <!-- SIDEBAR -->
                <div class="col-lg-4">

                    <!-- SEARCH -->
                    <div class="card border-0 shadow-sm mb-4 sidebar-card search-card" data-aos="fade-left" data-aos-delay="200">
                        <div class="card-body">
                            <h6 class="fw-semibold mb-3">
                                <i class="bi bi-search me-2"></i>Cari Agenda
                            </h6>
                            <form>
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Cari judul agenda...">
                                    <button class="btn btn-success" type="submit">
                                        <i class="bi bi-search"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- AGENDA TERDEKAT -->
                    <div class="card border-0 shadow-sm mb-4 sidebar-card" data-aos="fade-left" data-aos-delay="300">
                        <div class="card-body">
                            <h6 class="fw-semibold mb-3">
                                <i class="bi bi-clock-history me-2"></i>Agenda Terdekat
                            </h6>

                            @for ($i = 1; $i <= 5; $i++)
                                <a href="#" class="sidebar-news-item d-flex gap-3 mb-3 text-decoration-none">
                                    <!-- IMAGE -->
                                    <img src="https://images.unsplash.com/photo-{{ 1464226184 + $i }}884fa280b87c399?w=80&h=80&fit=crop" 
                                         alt="Agenda {{ $i }}"
                                         class="sidebar-news-img">
                                    <!-- TEXT -->
                                    <div class="flex-grow-1">
                                        <div class="fw-semibold small text-dark">
                                            @if($i == 1)
                                                Pelatihan Budidaya Tanaman Organik
                                            @elseif($i == 2)
                                                Sosialisasi Pupuk Subsidi
                                            @elseif($i == 3)
                                                Pameran Hasil Pertanian
                                            @elseif($i == 4)
                                                Rapat Evaluasi Program
                                            @else
                                                Monitoring Lahan Pertanian
                                            @endif
                                        </div>
                                        <div class="text-muted small">
                                            <i class="bi bi-calendar3"></i> {{ 10 + $i }} Jan 2025
                                        </div>
                                    </div>
                                </a>
                            @endfor
                        </div>
                    </div>

                    <!-- AGENDA PALING BANYAK DILIHAT -->
                    <div class="card border-0 shadow-sm sidebar-card" data-aos="fade-left" data-aos-delay="400">
                        <div class="card-body">
                            <h6 class="fw-semibold mb-3">
                                <i class="bi bi-fire me-2"></i>Agenda Populer
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
                                                Panen Raya Padi Organik
                                            @elseif($i == 2)
                                                Festival Pertanian Kuningan
                                            @elseif($i == 3)
                                                Lomba Inovasi Pertanian
                                            @elseif($i == 4)
                                                Gelar Teknologi Pertanian
                                            @else
                                                Kampanye Pertanian Ramah Lingkungan
                                            @endif
                                        </div>
                                        <div class="text-muted small">
                                            <i class="bi bi-eye"></i> {{ rand(250, 850) }} views
                                        </div>
                                    </div>
                                </a>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection