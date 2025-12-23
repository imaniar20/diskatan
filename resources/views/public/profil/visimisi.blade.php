@extends('public.layouts.app')

@section('title', 'Visi dan Misi')

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
    
    /* ============ VISI CARD ============ */
    .visi-card {
        background: white;
        border-radius: 25px;
        box-shadow: 0 15px 50px rgba(45, 106, 79, 0.1);
        border: none;
        overflow: hidden;
        position: relative;
        transition: all 0.4s ease;
    }
    
    .visi-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 5px;
        height: 100%;
        background: linear-gradient(180deg, #2d6a4f, #40916c, #52b788);
    }
    
    .visi-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 25px 70px rgba(45, 106, 79, 0.2);
    }
    
    .visi-card .card-body {
        padding: 40px 50px;
    }
    
    .visi-card h3 {
        font-family: 'Playfair Display', serif;
        font-size: 2rem;
        font-weight: 800;
        color: #2d6a4f;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 15px;
    }
    
    .visi-card h3 i {
        font-size: 2.5rem;
        color: #52b788;
    }
    
    .visi-card p {
        font-size: 1.25rem;
        line-height: 1.8;
        color: #333;
        font-weight: 500;
        margin: 0;
        position: relative;
        padding-left: 20px;
    }
    
    .visi-card p::before {
        content: '"';
        position: absolute;
        left: 0;
        top: -40px;
        font-size: 4rem;
        color: #52b788;
        opacity: 0.3;
        font-family: Georgia, serif;
    }
    
    /* ============ MISI CARD ============ */
    .misi-card {
        background: white;
        border-radius: 25px;
        box-shadow: 0 15px 50px rgba(45, 106, 79, 0.1);
        border: none;
        overflow: hidden;
        position: relative;
        transition: all 0.4s ease;
    }
    
    .misi-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 5px;
        height: 100%;
        background: linear-gradient(180deg, #52b788, #40916c, #2d6a4f);
    }
    
    .misi-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 25px 70px rgba(45, 106, 79, 0.2);
    }
    
    .misi-card .card-body {
        padding: 40px 50px;
    }
    
    .misi-card h3 {
        font-family: 'Playfair Display', serif;
        font-size: 2rem;
        font-weight: 800;
        color: #2d6a4f;
        margin-bottom: 35px;
        display: flex;
        align-items: center;
        gap: 15px;
    }
    
    .misi-card h3 i {
        font-size: 2.5rem;
        color: #52b788;
    }
    
    /* ============ MISI LIST ============ */
    .misi-list {
        list-style: none;
        counter-reset: misi-counter;
        padding: 0;
        margin: 0;
    }
    
    .misi-list li {
        counter-increment: misi-counter;
        position: relative;
        padding: 25px 25px 25px 80px;
        margin-bottom: 20px;
        background: linear-gradient(135deg, #f8f9fa, #e8f5e9);
        border-radius: 15px;
        font-size: 1.05rem;
        line-height: 1.8;
        color: #333;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }
    
    .misi-list li:hover {
        background: white;
        border-color: #52b788;
        transform: translateX(10px);
        box-shadow: 0 5px 20px rgba(45, 106, 79, 0.1);
    }
    
    .misi-list li::before {
        content: counter(misi-counter);
        position: absolute;
        left: 20px;
        top: 50%;
        transform: translateY(-50%);
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #2d6a4f, #40916c);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        font-weight: 800;
        font-family: 'Playfair Display', serif;
        box-shadow: 0 5px 15px rgba(45, 106, 79, 0.3);
        transition: all 0.3s ease;
    }
    
    .misi-list li:hover::before {
        transform: translateY(-50%) rotate(360deg) scale(1.1);
        background: linear-gradient(135deg, #52b788, #74c69d);
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
        
        .visi-card .card-body,
        .misi-card .card-body {
            padding: 30px 25px;
        }
        
        .visi-card h3,
        .misi-card h3 {
            font-size: 1.5rem;
        }
        
        .visi-card p {
            font-size: 1.1rem;
        }
        
        .misi-list li {
            padding: 20px 20px 20px 70px;
            font-size: 0.95rem;
        }
        
        .misi-list li::before {
            width: 40px;
            height: 40px;
            font-size: 1.2rem;
            left: 15px;
        }
    }
</style>
@endpush

@section('content')
    <!-- Pattern Overlay -->
    <div class="pattern-overlay"></div>

    <!-- Page Hero -->
    <section class="page-hero" style="--hero-bg: url('{{ asset('img/background/bg-1.jpg') }}');">
        <div class="container position-relative">
            <h1 class="page-title">Visi dan Misi</h1>

            <nav class="breadcrumb-wrapper">
                <a href="{{ url('/') }}">Home</a>
                <span>›</span>
                <a href="#">Profil</a>
                <span>›</span>
                <span class="active">Visi dan Misi</span>
            </nav>
        </div>
    </section>

    <!-- VISI & MISI -->
    <section class="content-section">
        <!-- VISI -->
        <div class="container mb-5">
            <div class="row justify-content-center">
                <div class="col-lg-10" data-aos="fade-up" data-aos-duration="1000">
                    <div class="card visi-card">
                        <div class="card-body">
                            <h3>
                                <i class="bi bi-eye"></i>Visi
                            </h3>
                            <p>
                                Kuningan MAJU (Makmur, Agamis, Pinunjul) Berbasis Desa Tahun 2023
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- MISI -->
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200">
                    <div class="card misi-card">
                        <div class="card-body">
                            <h3>
                                <i class="bi bi-bullseye"></i>Misi
                            </h3>
                            <ol class="misi-list">
                                <li data-aos="fade-right" data-aos-delay="300">Membangun Tata Kelola Pemerintahan Daerah Yang Profesional, Efektif, Demokratis, Dan Terpercaya Dengan Jiwa Kepemimpinan Nu SAJATI.</li>
                                <li data-aos="fade-right" data-aos-delay="400">Mewujudkan Masyarakat Kuningan Nu SAJATI Dalam Kehidupan Beragama Dan Bernegara Dalam Bingkai Kebangsaan Dan Kebhinekaan.</li>
                                <li data-aos="fade-right" data-aos-delay="500">Mewujudkan Manajemen Layanan Pendidikan Dan Kesehatan Yang Merata, Adil, Berkualitas Dan Berkelanjutan Dalam Menciptakan Sumber Daya Manusia Nu SAJATI.</li>
                                <li data-aos="fade-right" data-aos-delay="600">Mewujudkan Pembangunan Kawasan Perdesaan Berbasis Pertanian, Wisata, Budaya Dan Potensi Lokal Untuk Mempercepat Pertumbuhan Serta Pemerataan Ekonomi Rakyat.</li>
                                <li data-aos="fade-right" data-aos-delay="700">Mewujudkan Pemerataan Infrastruktur Untuk Mendorong Investasi Dan Penciptaan Lapangan Kerja Dalam Lingkungan Yang Lestari.</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection