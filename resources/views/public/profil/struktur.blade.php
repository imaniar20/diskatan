@extends('public.layouts.app')

@section('title', 'Struktur Organisasi')

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
    
    /* ============ MAIN CARD ============ */
    .org-main-card {
        background: white;
        border-radius: 25px;
        box-shadow: 0 15px 50px rgba(45, 106, 79, 0.1);
        border: none;
        overflow: hidden;
        position: relative;
        padding: 60px 40px;
    }
    
    .org-main-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: linear-gradient(90deg, #2d6a4f, #40916c, #52b788, #74c69d);
    }
    
    /* ============ ORG CARDS ============ */
    .org-card {
        background: linear-gradient(135deg, #ffffff, #f8f9fa);
        border: 2px solid #e8f5e9;
        border-radius: 20px;
        padding: 30px 25px;
        transition: all 0.4s ease;
        position: relative;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(45, 106, 79, 0.08);
    }
    
    .org-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #2d6a4f, #52b788);
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.4s ease;
    }
    
    /* .org-card:hover::before {
        transform: scaleX(1);
    }
     */
    /* .org-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 40px rgba(45, 106, 79, 0.15);
        border-color: #52b788;
        background: white;
    } */
    
    /* ============ LEADER CARD (Kepala Dinas) ============ */
    .org-leader {
        max-width: 400px;
        background: linear-gradient(135deg, var(--primary-green), var(--secondary-green));
        color: white;
        border: none;
        box-shadow: 0 10px 40px rgba(45, 106, 79, 0.3);
        position: relative;
    }
    
    .org-leader::before {
        background: linear-gradient(90deg, #74c69d, #52b788);
    }
    
    .org-leader:hover {
        transform: translateY(-10px) scale(1.05);
        box-shadow: 0 20px 50px rgba(45, 106, 79, 0.4);
    }
    
    .org-leader h4 {
        color: white;
        margin: 0;
        font-size: 1.4rem;
    }
    
    .org-leader::after {
        content: '👤';
        position: absolute;
        top: -10px;
        right: -10px;
        font-size: 4rem;
        opacity: 0.1;
    }
    
    /* ============ SECRETARY CARD ============ */
    .org-secretary {
        max-width: 500px;
        background: linear-gradient(135deg, var(--primary-green), var(--secondary-green));
        color: white;
        border: none;
        box-shadow: 0 10px 35px rgba(64, 145, 108, 0.3);
    }
    
    .org-secretary::before {
        background: linear-gradient(90deg, #74c69d, #b7e4c7);
    }
    
    .org-secretary:hover {
        transform: translateY(-10px) scale(1.03);
        box-shadow: 0 18px 45px rgba(64, 145, 108, 0.4);
    }
    
    .org-secretary h5,
    .org-secretary span {
        color: white;
    }
    
    .org-secretary h5 {
        font-size: 1.3rem;
        margin-bottom: 15px;
    }
    
    .org-secretary span {
        font-size: 0.95rem;
        line-height: 1.8;
        opacity: 0.95;
    }
    
    /* ============ SECTION TITLES ============ */
    .section-sub-title {
        font-family: 'Poppins', sans-serif;
        font-weight: 700;
        color: #2d6a4f;
        margin-bottom: 15px;
    }
    
    .org-leader .section-sub-title,
    .org-secretary .section-sub-title {
        color: white;
    }
    
    .section-sub-title u {
        text-decoration: none;
        border-bottom: 3px solid #52b788;
        padding-bottom: 5px;
        line-height: 1.8;
    }
    
    .org-leader .section-sub-title u,
    .org-secretary .section-sub-title u {
        border-bottom-color: rgba(255,255,255,0.5);
    }
    
    /* ============ DEPARTMENT CARDS ============ */
    .org-department {
        height: 100%;
        min-height: 280px;
    }
    
    .org-department h6 {
        font-size: 1.1rem;
        color: #2d6a4f;
        margin-bottom: 20px;
    }
    
    .org-department ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .org-department ul li {
        position: relative;
        padding: 10px 0 10px 30px;
        color: #333;
        font-size: 0.95rem;
        line-height: 1.6;
        transition: all 0.3s ease;
    }
    
    .org-department ul li::before {
        content: '✓';
        position: absolute;
        left: 0;
        top: 10px;
        width: 20px;
        height: 20px;
        background: linear-gradient(135deg, #52b788, #74c69d);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.7rem;
        font-weight: bold;
        transition: all 0.3s ease;
    }
    
    .org-department:hover ul li::before {
        transform: rotate(360deg);
    }
    
    .org-department ul li:hover {
        padding-left: 35px;
        color: #2d6a4f;
        font-weight: 500;
    }
    
    /* ============ CONNECTION LINES ============ */
    .connection-line {
        width: 2px;
        height: 50px;
        background: linear-gradient(180deg, #52b788, #74c69d);
        margin: 0 auto;
        position: relative;
        animation: lineGrow 1s ease-out;
    }
    
    @keyframes lineGrow {
        from {
            height: 0;
        }
        to {
            height: 50px;
        }
    }
    
    .connection-line::before,
    .connection-line::after {
        content: '';
        position: absolute;
        width: 10px;
        height: 10px;
        background: #52b788;
        border-radius: 50%;
        left: 50%;
        transform: translateX(-50%);
    }
    
    .connection-line::before {
        top: -5px;
    }
    
    .connection-line::after {
        bottom: -5px;
    }
    
    /* ============ GRID SECTION TITLE ============ */
    .grid-section-title {
        text-align: center;
        margin: 60px 0 40px;
        position: relative;
    }
    
    .grid-section-title h4 {
        font-family: 'Playfair Display', serif;
        font-size: 2rem;
        font-weight: 800;
        color: #2d6a4f;
        display: inline-block;
        position: relative;
        padding-bottom: 15px;
    }
    
    .grid-section-title h4::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 100px;
        height: 4px;
        background: linear-gradient(90deg, #2d6a4f, #52b788);
        border-radius: 10px;
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
        
        .org-main-card {
            padding: 40px 25px;
        }
        
        .org-card {
            padding: 25px 20px;
        }
        
        .org-leader,
        .org-secretary {
            max-width: 100%;
        }
        
        .connection-line {
            height: 30px;
        }
        
        .org-department {
            min-height: auto;
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
            <h1 class="page-title">Struktur Organisasi</h1>

            <nav class="breadcrumb-wrapper">
                <a href="{{ url('/') }}">Home</a>
                <span>›</span>
                <a href="#">Profil</a>
                <span>›</span>
                <span class="active">Struktur Organisasi</span>
            </nav>
        </div>
    </section>

    <!-- CONTENT -->
    <section class="content-section">
        <div class="container">
            <div class="org-main-card">
                
                <!-- KEPALA DINAS -->
                <div class="text-center mb-4" data-aos="zoom-in" data-aos-duration="800">
                    <div class="org-card org-leader mx-auto">
                        <h4 class="section-sub-title"><u>Kepala Dinas</u></h4>
                    </div>
                </div>

                <!-- Connection Line -->
                <div class="connection-line" data-aos="fade-up" data-aos-delay="200"></div>

                <!-- SEKRETARIS -->
                <div class="text-center mb-4" data-aos="zoom-in" data-aos-delay="400">
                    <div class="org-card org-secretary mx-auto">
                        <h5 class="section-sub-title"><u>Sekretaris</u></h5>
                        <span class="d-block mt-2">
                            Sub Bagian Umum & Kepegawaian<br>
                            Sub Bagian Keuangan<br>
                            Sub Bagian Program
                        </span>
                    </div>
                </div>

                <!-- Connection Line -->
                <div class="connection-line" data-aos="fade-up" data-aos-delay="600"></div>

                <!-- Section Title -->
                <div class="grid-section-title" data-aos="fade-up" data-aos-delay="700">
                    <h4>Bidang & Unit Pelaksana</h4>
                </div>

                <!-- BIDANG -->
                <div class="row g-4">

                    <!-- Penyuluhan -->
                    <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="800">
                        <div class="org-card org-department">
                            <h6 class="section-sub-title"><u>Kepala Bidang Penyuluhan</u></h6>
                            <ul>
                                <li>Substansi Kelembagaan</li>
                                <li>Substansi Pengembangan SDM Pertanian</li>
                                <li>Substansi Program Penyuluhan & Supervisi</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Hortikultura -->
                    <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="900">
                        <div class="org-card org-department">
                            <h6 class="section-sub-title"><u>Kepala Bidang Hortikultura & Perkebunan</u></h6>
                            <ul>
                                <li>Substansi Hortikultura</li>
                                <li>Substansi Perkebunan</li>
                                <li>Substansi PSDT Hortikultura & Perkebunan</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Tanaman Pangan -->
                    <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="1000">
                        <div class="org-card org-department">
                            <h6 class="section-sub-title"><u>Kepala Bidang Tanaman Pangan</u></h6>
                            <ul>
                                <li>Substansi Tanaman Serealia</li>
                                <li>Substansi Aneka Kacang & Umbi</li>
                                <li>Substansi Pengembangan SD & Teknologi Tanaman Pangan</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Pengolahan -->
                    <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="1100">
                        <div class="org-card org-department">
                            <h6 class="section-sub-title"><u>Kepala Bidang Pengolahan Hasil & Konsumsi Pangan</u></h6>
                            <ul>
                                <li>Substansi Pengolahan Hasil</li>
                                <li>Substansi Konsumsi Pangan</li>
                                <li>Substansi Keamanan Pangan</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Cadangan -->
                    <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="1200">
                        <div class="org-card org-department">
                            <h6 class="section-sub-title"><u>Kepala Bidang Cadangan & Distribusi Pangan</u></h6>
                            <ul>
                                <li>Substansi Cadangan Pangan</li>
                                <li>Substansi Distribusi & Harga Pangan</li>
                                <li>Substansi Pengentasan Rawan Pangan</li>
                            </ul>
                        </div>
                    </div>

                    <!-- UPTD & JF -->
                    <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="1300">
                        <div class="org-card org-department">
                            <h6 class="section-sub-title"><u>Unit Pelaksana Teknis</u></h6>
                            <ul>
                                <li>UPTD</li>
                                <li>Kelompok Jabatan Fungsional</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection