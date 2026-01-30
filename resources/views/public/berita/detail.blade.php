@extends('public.layouts.app')

@section('title', 'Detail Berita')

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
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }
    
    .page-hero .container {
        z-index: 2;
    }
    
    .page-title {
        font-family: 'Poppins', sans-serif;
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
    
    /* ============ DETAIL CARD ============ */
    .detail-card {
        background: white;
        border-radius: 25px;
        box-shadow: 0 15px 50px rgba(45, 106, 79, 0.1);
        border: none;
        overflow: hidden;
        position: relative;
        transition: all 0.4s ease;
    }
    
    .detail-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: linear-gradient(90deg, #2d6a4f, #40916c, #52b788, #74c69d);
    }
    
    .detail-card:hover {
        box-shadow: 0 20px 60px rgba(45, 106, 79, 0.15);
    }
    
    .detail-card .card-body {
        padding: 40px;
    }
    
    /* ============ FEATURED IMAGE ============ */
    .featured-image {
        width: 100%;
        height: 500px;
        object-fit: cover;
        border-radius: 20px;
        margin-bottom: 30px;
        box-shadow: 0 10px 40px rgba(45, 106, 79, 0.15);
    }
    
    /* ============ TITLE SECTION ============ */
    .detail-title {
        font-family: 'Poppins', sans-serif;
        font-size: 2.8rem;
        font-weight: 900;
        color: #2d6a4f;
        margin-bottom: 25px;
        line-height: 1.3;
    }
    
    /* ============ META INFO ============ */
    .meta-info {
        display: flex;
        flex-wrap: wrap;
        gap: 25px;
        margin-bottom: 30px;
        padding-bottom: 25px;
        border-bottom: 2px solid #e8f5e9;
    }
    
    .meta-item {
        display: flex;
        align-items: center;
        gap: 10px;
        color: #666;
        font-weight: 500;
        font-size: 0.95rem;
    }
    
    .meta-item i {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, rgba(82, 183, 136, 0.1), rgba(116, 198, 157, 0.1));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #2d6a4f;
        font-size: 1.2rem;
    }
    
    /* ============ CATEGORY BADGE ============ */
    .category-badge {
        display: inline-block;
        background: linear-gradient(135deg, #2d6a4f, #40916c);
        color: white;
        padding: 8px 20px;
        border-radius: 25px;
        font-weight: 600;
        font-size: 0.9rem;
        margin-bottom: 20px;
        box-shadow: 0 5px 15px rgba(45, 106, 79, 0.3);
    }
    
    /* ============ CONTENT TEXT ============ */
    .detail-content {
        font-size: 1.1rem;
        line-height: 1.9;
        color: #333;
        text-align: justify;
    }
    
    .detail-content p {
        margin-bottom: 25px;
    }
    
    .detail-content p:first-of-type::first-letter {
        font-size: 4rem;
        font-weight: 900;
        float: left;
        line-height: 0.8;
        margin: 10px 15px 0 0;
        color: #2d6a4f;
        font-family: 'Poppins', sans-serif;
    }
    
    .detail-content h5 {
        color: #2d6a4f;
        font-weight: 700;
        margin-top: 35px;
        margin-bottom: 20px;
        font-family: 'Poppins', sans-serif;
        font-size: 1.5rem;
    }
    
    .detail-content blockquote {
        background: linear-gradient(135deg, rgba(82, 183, 136, 0.08), rgba(116, 198, 157, 0.05));
        border-left: 5px solid #52b788;
        padding: 25px 30px;
        margin: 30px 0;
        border-radius: 15px;
        font-style: italic;
        color: #2d6a4f;
        font-size: 1.15rem;
        position: relative;
    }
    
    .detail-content blockquote::before {
        content: '"';
        position: absolute;
        top: -20px;
        left: 20px;
        font-size: 5rem;
        color: #52b788;
        opacity: 0.2;
        font-family: Georgia, serif;
    }
    
    .detail-content ul,
    .detail-content ol {
        margin-bottom: 25px;
        padding-left: 30px;
    }
    
    .detail-content li {
        margin-bottom: 12px;
        line-height: 1.8;
    }
    
    .detail-content img {
        width: 100%;
        height: 100%;
        border-radius: 15px;
        margin: 30px 0;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    
    /* ============ TAGS ============ */
    .tags-section {
        margin-top: 40px;
        padding-top: 30px;
        border-top: 2px solid #e8f5e9;
    }
    
    .tags-title {
        font-weight: 700;
        color: #2d6a4f;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .tag-item {
        display: inline-block;
        background: #f8f9fa;
        color: #2d6a4f;
        padding: 8px 18px;
        border-radius: 20px;
        margin: 5px;
        text-decoration: none;
        font-weight: 500;
        font-size: 0.9rem;
        border: 2px solid #e8f5e9;
        transition: all 0.3s ease;
    }
    
    .tag-item:hover {
        background: linear-gradient(135deg, #2d6a4f, #40916c);
        color: white;
        border-color: #2d6a4f;
        transform: translateY(-2px);
    }
    
    /* ============ SHARE BUTTONS ============ */
    .share-section {
        margin-top: 30px;
        padding-top: 30px;
        border-top: 2px solid #e8f5e9;
    }
    
    .share-title {
        font-weight: 700;
        color: #2d6a4f;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .share-buttons {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }
    
    .share-btn {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        transition: all 0.3s ease;
        text-decoration: none;
        font-size: 1.2rem;
    }
    
    .share-btn:hover {
        transform: translateY(-5px) scale(1.1);
        box-shadow: 0 8px 20px rgba(0,0,0,0.25);
    }
    
    .share-btn.facebook { background: #1877f2; }
    .share-btn.twitter { background: #1da1f2; }
    .share-btn.whatsapp { background: #25d366; }
    .share-btn.telegram { background: #0088cc; }
    .share-btn.email { background: #ea4335; }
    .share-btn.linkedin { background: #0077b5; }
    
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
    
    /* ============ BACK BUTTON ============ */
    .back-button {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 25px;
        background: linear-gradient(135deg, #2d6a4f, #40916c);
        color: white;
        border-radius: 25px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        margin-bottom: 20px;
    }
    
    .back-button:hover {
        background: linear-gradient(135deg, #40916c, #52b788);
        color: white;
        transform: translateX(-5px);
        box-shadow: 0 5px 20px rgba(45, 106, 79, 0.3);
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
        
        .detail-card .card-body {
            padding: 25px 20px;
        }
        
        .detail-title {
            font-size: 2rem;
        }
        
        .featured-image {
            height: 300px;
        }
        
        .meta-info {
            flex-direction: column;
            gap: 15px;
        }
        
        .detail-content {
            font-size: 1rem;
        }
        
        .detail-content p:first-of-type::first-letter {
            font-size: 3rem;
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
    <section class="page-hero" style="--hero-bg: url('{{ asset('img/background/bg-2.jpg') }}');">
        <div class="container position-relative">
            <h1 class="page-title">Detail Berita</h1>

            <nav class="breadcrumb-wrapper">
                <a href="{{ url('/') }}">Home</a>
                <span>›</span>
                <a href="{{ url('/berita') }}">Berita</a>
                <span>›</span>
                <span class="active">Detail</span>
            </nav>
        </div>
    </section>

    <!-- CONTENT -->
    <section class="content-section">
        <div class="container">
            <div class="row">

                <!-- MAIN CONTENT -->
                <div class="col-lg-8">
                    
                    <a href="{{ url('/berita') }}" class="back-button" data-aos="fade-right">
                        <i class="bi bi-arrow-left"></i>
                        Kembali ke Daftar Berita
                    </a>

                    <div class="card detail-card" data-aos="fade-up" data-aos-duration="1000">
                        <div class="card-body">
                            
                            <!-- Category Badge -->
                            {{-- <span class="category-badge" data-aos="fade-up" data-aos-delay="100">
                                <i class="bi bi-bookmark-fill me-1"></i>
                                Program Unggulan
                            </span> --}}
                            
                            <!-- Title -->
                            <h1 class="detail-title" data-aos="fade-up" data-aos-delay="200">
                                {{ $news->title }}
                            </h1>
                            
                            <!-- Meta Info -->
                            <div class="meta-info" data-aos="fade-up" data-aos-delay="300">
                                <div class="meta-item">
                                    <i class="bi bi-calendar-event"></i>
                                    <span>{{ \Carbon\Carbon::parse($news->published_at)->format('d-M-Y') }}</span>
                                </div>
                                <div class="meta-item">
                                    <i class="bi bi-person-circle"></i>
                                    <span>Admin Dinas</span>
                                </div>
                                <div class="meta-item">
                                    <i class="bi bi-eye"></i>
                                    <span>{{ $news->visitor_count }} Views</span>
                                </div>
                            </div>

                            <!-- Featured Image -->
                            <img src="{{ asset('storage/' . $news->thumbnail) }}"
                                 alt="Program Reboisasi" 
                                 class="featured-image"
                                 data-aos="zoom-in"
                                 data-aos-delay="400">
                            
                            <!-- Content -->
                            <div class="detail-content" data-aos="fade-up" data-aos-delay="500">
                                <p>
                                    {!! $news->content !!}
                                </p>
                                @foreach ($news->contents as $item)
                                    <img class="alignnone size-full wp-image-9331" src="../../storage/{{ $item->file }}" sizes="(max-width: 1280px) 100vw, 1280px" alt="" width="1280" loading="lazy">
                                @endforeach
                            </div>

                            <!-- Tags -->
                            <div class="tags-section" data-aos="fade-up" data-aos-delay="600">
                                <h6 class="tags-title">
                                    <i class="bi bi-tags"></i>
                                    Tags
                                </h6>
                                <a href="#" class="tag-item">#Reboisasi</a>
                                <a href="#" class="tag-item">#Lingkungan</a>
                                <a href="#" class="tag-item">#ProgramUnggulan</a>
                                <a href="#" class="tag-item">#Kuningan</a>
                                <a href="#" class="tag-item">#Pertanian</a>
                            </div>

                            <!-- Share Section -->
                            <div class="share-section" data-aos="fade-up" data-aos-delay="700">
                                <h6 class="share-title">
                                    <i class="bi bi-share"></i>
                                    Bagikan Berita Ini
                                </h6>
                                <div class="share-buttons">
                                    <a href="#" class="share-btn facebook" title="Share to Facebook">
                                        <i class="bi bi-facebook"></i>
                                    </a>
                                    <a href="#" class="share-btn twitter" title="Share to Twitter">
                                        <i class="bi bi-twitter"></i>
                                    </a>
                                    <a href="#" class="share-btn whatsapp" title="Share to WhatsApp">
                                        <i class="bi bi-whatsapp"></i>
                                    </a>
                                    <a href="#" class="share-btn telegram" title="Share to Telegram">
                                        <i class="bi bi-telegram"></i>
                                    </a>
                                    <a href="#" class="share-btn linkedin" title="Share to LinkedIn">
                                        <i class="bi bi-linkedin"></i>
                                    </a>
                                    <a href="#" class="share-btn email" title="Share via Email">
                                        <i class="bi bi-envelope"></i>
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

                <!-- SIDEBAR -->
                <div class="col-lg-4">

                    <!-- BERITA TERBARU -->
                    <div class="card border-0 shadow-sm mb-4 sidebar-card" data-aos="fade-left" data-aos-delay="300">
                        <div class="card-body">
                            <h6 class="fw-semibold mb-3">
                                <i class="bi bi-newspaper me-2"></i>Berita Terbaru
                            </h6>
                            @foreach ($lastest as $item)
                                <a href="{{ route('berita.detail', $item->slug) }}" class="sidebar-news-item d-flex gap-3 mb-3 text-decoration-none">
                                    <!-- IMAGE -->
                                    <img src="{{ asset($item->thumbnail ? 'storage/' . $item->thumbnail : 'images/img_not_found.png') }}"
                                        alt="Berita" class="sidebar-news-img">
                                    <!-- TEXT -->
                                    <div class="flex-grow-1">
                                        <div class="fw-semibold small text-dark">
                                            {{ Str::limit($item->title, 40) }}
                                        </div>
                                        <div class="text-muted small">
                                            <i class="bi bi-calendar3"></i> {{ \Carbon\Carbon::parse($item->published_at)->format('d M Y') }}
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- PALING BANYAK DILIHAT -->
                    <div class="card border-0 shadow-sm sidebar-card" data-aos="fade-left" data-aos-delay="400">
                        <div class="card-body">
                            <h6 class="fw-semibold mb-3">
                                <i class="bi bi-fire me-2"></i>Paling Banyak Dilihat
                            </h6>

                            @foreach ($popular as $item)
                                <a href="{{ route('berita.detail', $item->slug) }}" class="sidebar-news-item d-flex gap-3 mb-3 text-decoration-none">
                                    <!-- IMAGE -->
                                    <img src="{{ asset($item->thumbnail ? 'storage/' . $item->thumbnail : 'images/img_not_found.png') }}"
                                        alt="Berita" class="sidebar-news-img">
                                    <!-- TEXT -->
                                    <div class="flex-grow-1">
                                        <div class="fw-semibold small text-dark">
                                            {{ Str::limit($item->title, 40) }}
                                        </div>
                                        <div class="text-muted small">
                                            <i class="bi bi-eye"></i> {{ $item->visitor_count }} dilihat
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection