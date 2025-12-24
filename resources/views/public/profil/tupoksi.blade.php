@extends('public.layouts.app')

@section('title', 'Tugas Pokok dan Fungsi')

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
    
    /* ============ TUPOKSI CARD ============ */
    .tupoksi-card {
        background: white;
        border-radius: 25px;
        box-shadow: 0 15px 50px rgba(45, 106, 79, 0.1);
        border: none;
        overflow: hidden;
        position: relative;
        transition: all 0.4s ease;
    }
    
    .tupoksi-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 5px;
        height: 100%;
        background: linear-gradient(180deg, #2d6a4f, #40916c, #52b788);
    }
    
    .tupoksi-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 25px 70px rgba(45, 106, 79, 0.2);
    }
    
    .tupoksi-card .card-body {
        padding: 50px;
    }
    
    /* ============ SECTION TITLES ============ */
    .section-title {
        font-family: 'Playfair Display', serif;
        font-size: 2rem;
        font-weight: 800;
        color: #2d6a4f;
        margin-bottom: 15px;
        position: relative;
        display: inline-block;
    }
    
    .section-title::after {
        content: '';
        position: absolute;
        bottom: -8px;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 4px;
        background: linear-gradient(90deg, #2d6a4f, #52b788);
        border-radius: 10px;
    }
    
    .section-sub-title {
        font-family: 'Poppins', sans-serif;
        font-size: 1.3rem;
        font-weight: 600;
        color: #40916c;
        margin-bottom: 30px;
    }
    
    /* ============ PARAGRAPHS ============ */
    .tupoksi-card p {
        font-size: 1.05rem;
        line-height: 1.9;
        color: #333;
        margin-bottom: 25px;
        text-align: justify;
        position: relative;
        padding-left: 20px;
    }
    
    .tupoksi-card p::before {
        content: '';
        position: absolute;
        left: 0;
        top: 8px;
        width: 8px;
        height: 8px;
        background: linear-gradient(135deg, #52b788, #74c69d);
        border-radius: 50%;
        opacity: 0.6;
    }
    
    /* ============ QUOTE/MAIN TASK BOX ============ */
    .main-task-box {
        background: linear-gradient(135deg, #2d6a4f, #40916c);
        color: white;
        padding: 30px 40px;
        border-radius: 20px;
        margin: 30px 0;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(45, 106, 79, 0.3);
    }
    
    .main-task-box::before {
        content: '"';
        position: absolute;
        top: -20px;
        left: 20px;
        font-size: 8rem;
        color: rgba(255,255,255,0.1);
        font-family: Georgia, serif;
        line-height: 1;
    }
    
    .main-task-box p {
        margin: 0;
        font-size: 1.3rem;
        line-height: 1.8;
        font-weight: 500;
        position: relative;
        z-index: 2;
        padding-left: 0;
        text-align: center;
    }
    
    .main-task-box p::before {
        display: none;
    }
    
    /* ============ FUNCTION SECTION ============ */
    .function-section {
        background: linear-gradient(135deg, rgba(82, 183, 136, 0.05), rgba(116, 198, 157, 0.05));
        border: 2px solid #e8f5e9;
        border-radius: 20px;
        padding: 35px 40px;
        margin-top: 40px;
    }
    
    .function-section h6 {
        color: #2d6a4f;
        font-size: 1.2rem;
        font-weight: 700;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .function-section h6::before {
        content: '⚙️';
        font-size: 1.5rem;
    }
    
    /* ============ FUNCTION LIST ============ */
    .function-list {
        list-style: none;
        counter-reset: function-counter;
        padding: 0;
        margin: 0;
    }
    
    .function-list li {
        counter-increment: function-counter;
        position: relative;
        padding: 20px 20px 20px 70px;
        margin-bottom: 15px;
        background: white;
        border-radius: 15px;
        font-size: 1.05rem;
        line-height: 1.8;
        color: #333;
        transition: all 0.4s ease;
        border: 2px solid transparent;
        box-shadow: 0 5px 15px rgba(45, 106, 79, 0.05);
    }
    
    .function-list li:hover {
        background: white;
        border-color: #52b788;
        transform: translateX(10px);
        box-shadow: 0 8px 25px rgba(45, 106, 79, 0.15);
    }
    
    .function-list li::before {
        content: counter(function-counter);
        position: absolute;
        left: 20px;
        top: 50%;
        transform: translateY(-50%);
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #2d6a4f, #40916c);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        font-weight: 800;
        font-family: 'Playfair Display', serif;
        box-shadow: 0 5px 15px rgba(45, 106, 79, 0.3);
        transition: all 0.4s ease;
    }
    
    .function-list li:hover::before {
        transform: translateY(-50%) rotate(360deg) scale(1.15);
        background: linear-gradient(135deg, #52b788, #74c69d);
    }
    
    /* ============ BASE REGULATION BOX ============ */
    .regulation-box {
        background: linear-gradient(135deg, rgba(116, 198, 157, 0.1), rgba(82, 183, 136, 0.05));
        border-left: 4px solid #52b788;
        padding: 25px 30px;
        border-radius: 15px;
        margin-bottom: 30px;
    }
    
    .regulation-box p {
        margin: 0;
        padding-left: 0;
    }
    
    .regulation-box p::before {
        display: none;
    }
    
    .regulation-box strong {
        color: #2d6a4f;
        font-size: 1.1rem;
    }
    
    /* ============ ICON DECORATIONS ============ */
    .icon-decoration {
        position: absolute;
        font-size: 4rem;
        opacity: 0.05;
        z-index: 1;
    }
    
    .icon-decoration.icon-1 {
        top: 50px;
        right: 50px;
    }
    
    .icon-decoration.icon-2 {
        bottom: 50px;
        left: 50px;
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
        
        .tupoksi-card .card-body {
            padding: 30px 25px;
        }
        
        .section-title {
            font-size: 1.5rem;
        }
        
        .section-sub-title {
            font-size: 1.1rem;
        }
        
        .tupoksi-card p,
        .function-list li {
            font-size: 0.95rem;
            padding-left: 15px;
        }
        
        .function-list li {
            padding: 18px 18px 18px 60px;
        }
        
        .function-list li::before {
            width: 35px;
            height: 35px;
            font-size: 1rem;
            left: 15px;
        }
        
        .main-task-box p {
            font-size: 1.1rem;
        }
        
        .main-task-box {
            padding: 25px 25px;
        }
        
        .function-section {
            padding: 25px 20px;
        }
        
        .icon-decoration {
            display: none;
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
            <h1 class="page-title">Tugas Pokok dan Fungsi</h1>

            <nav class="breadcrumb-wrapper">
                <a href="{{ url('/') }}">Home</a>
                <span>›</span>
                <a href="#">Profil</a>
                <span>›</span>
                <span class="active">Tugas Pokok dan Fungsi</span>
            </nav>
        </div>
    </section>

    <!-- CONTENT -->
    <section class="content-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">

                    <div class="card tupoksi-card" data-aos="fade-up" data-aos-duration="1000">
                        <div class="card-body position-relative">
                            
                            <!-- Icon Decorations -->
                            <div class="icon-decoration icon-1">📋</div>
                            <div class="icon-decoration icon-2">⚖️</div>

                            <h3 class="section-title text-center" data-aos="fade-down" data-aos-delay="200">
                                Dinas Ketahanan Pangan dan Pertanian Kabupaten Kuningan
                            </h3>
                            <h5 class="mb-4 section-sub-title text-center" data-aos="fade-down" data-aos-delay="300">
                                Tugas Pokok dan Fungsi
                            </h5>

                            <div class="regulation-box" data-aos="zoom-in" data-aos-delay="400">
                                <p>
                                    <i class="bi bi-file-earmark-text me-2"></i>
                                    <strong>Dasar Hukum:</strong> Berdasarkan Peraturan Bupati Kuningan Nomor 73 Tahun 2019 tentang Kedudukan, Susunan
                                    Organisasi, Tugas Pokok, Fungsi dan Uraian Tugas serta Tata Kerja Dinas Ketahanan Pangan dan
                                    Pertanian Kabupaten Kuningan mempunyai Tugas Pokok.
                                </p>
                            </div>

                            <h5 class="mt-4 mb-3" style="color: #2d6a4f; font-weight: 700;" data-aos="fade-right" data-aos-delay="500">
                                <i class="bi bi-briefcase me-2"></i>Tugas Pokok
                            </h5>

                            <div class="main-task-box" data-aos="zoom-in" data-aos-delay="600">
                                <p>
                                    Melaksanakan urusan pemerintahan yang menjadi kewenangan daerah dan tugas pembantuan di
                                    bidang Ketahanan Pangan dan Pertanian
                                </p>
                            </div>

                            <div class="function-section" data-aos="fade-up" data-aos-delay="700">
                                <h6 class="section-sub-title">
                                    Sedangkan untuk menyelenggarakan tugas pokok tersebut di atas Dinas Pertanian mempunyai
                                    fungsi yaitu :
                                </h6>

                                <ul class="function-list">
                                    <li data-aos="fade-right" data-aos-delay="800">
                                        Perumusan, penetapan dan pelaksanaan kebijakan daerah dibidang ketahanan pangan dan
                                        pertanian.
                                    </li>
                                    <li data-aos="fade-right" data-aos-delay="900">
                                        Pelaksanaan kebijakan daerah dibidang ketahanan pangan dan pertanian.
                                    </li>
                                    <li data-aos="fade-right" data-aos-delay="1000">
                                        Pengkoordinasian, fasilitasi dan pengendalian pelaksanaan tugas dinas.
                                    </li>
                                    <li data-aos="fade-right" data-aos-delay="1100">
                                        Pembinaan terhadap Unit Pelaksana Teknis Dinas.
                                    </li>
                                    <li data-aos="fade-right" data-aos-delay="1200">
                                        Pelaksanaan fungsi lain yang diberikan oleh Bupati.
                                    </li>
                                </ul>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

@endsection