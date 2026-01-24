@extends('public.layouts.app')

@section('title', 'Agenda Kegiatan')

@push('styles')
    <style>
        :root {
            --primary-green: #16a34a;
            --secondary-green: #138817;
            --light-green: #18c938;
            --accent-green: #74c69d;
            --cream: #f8f9fa;
            --dark: #1b4332;
        }

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

        <style>

        /* ============ LAYANAN ONLINE SECTION ============ */
        .layanan-section {
            padding: 70px 0;
            background: linear-gradient(135deg, var(--primary-green), var(--secondary-green));
            position: relative;
        }

        .layanan-section::before {
            content: '';
            position: absolute;
            top: -50px;
            left: 0;
            right: 0;
            height: 100px;
            background: linear-gradient(135deg, #f8f9fa, #e8f5e9);
            clip-path: polygon(0 50%, 100% 0, 100% 100%, 0 100%);
        }

        /* ============ SECTION HEADER ============ */
        .layanan-header {
            text-align: center;
            margin-bottom: 50px;
        }

        .sambutan-title {
            font-family: 'Poppins', sans-serif;
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 15px;
        }

        .sambutan-title .text-white {
            color: #ffffff;
        }

        .sambutan-title .text-highlight-warning {
            color: #ffc107;
        }

        /* ============ LAYANAN CARDS ============ */
        .layanan-cards {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 20px;
            margin-bottom: 50px;
        }

        /* Center last 3 items when grid is 5 columns */
        @media (min-width: 1401px) {
            .layanan-card:nth-child(11) {
                grid-column: 2;
            }
        }

        /* Center last 1 item when grid is 4 columns */
        @media (max-width: 1400px) and (min-width: 1201px) {
            .layanan-card:nth-child(13) {
                grid-column: 2 / span 1;
            }
        }

        /* Center last 1 item when grid is 3 columns */
        @media (max-width: 1200px) and (min-width: 993px) {
            .layanan-card:nth-child(13) {
                grid-column: 2 / span 1;
            }
        }

        .layanan-card {
            background: white;
            border-radius: 16px;
            padding: 25px 18px;
            text-align: center;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 2px solid transparent;
            position: relative;
            overflow: hidden;
        }

        .layanan-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--card-color), transparent);
            transform: scaleX(0);
            transition: transform 0.4s ease;
        }

        .layanan-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.12);
            border-color: var(--card-color);
        }

        .layanan-card:hover::before {
            transform: scaleX(1);
        }

        /* Card Colors */
        .layanan-card.green {
            --card-color: #16a34a;
        }

        .layanan-card.blue {
            --card-color: #2563eb;
        }

        .layanan-card.orange {
            --card-color: #f59e0b;
        }

        .layanan-card.purple {
            --card-color: #9333ea;
        }

        .layanan-card.teal {
            --card-color: #14b8a6;
        }

        .layanan-card.red {
            --card-color: #dc2626;
        }

        .layanan-card.indigo {
            --card-color: #4f46e5;
        }

        .layanan-card.pink {
            --card-color: #ec4899;
        }

        .layanan-card.cyan {
            --card-color: #06b6d4;
        }

        .layanan-card.lime {
            --card-color: #84cc16;
        }

        .layanan-card.amber {
            --card-color: #f59e0b;
        }

        .layanan-card.emerald {
            --card-color: #10b981;
        }

        .layanan-card.violet {
            --card-color: #8b5cf6;
        }

        /* ============ CARD ICON ============ */
        .layanan-icon {
            width: 60px;
            height: 60px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 18px;
            transition: all 0.4s ease;
            position: relative;
        }

        .layanan-card.green .layanan-icon {
            background: linear-gradient(135deg, #16a34a, #22c55e);
        }

        .layanan-card.blue .layanan-icon {
            background: linear-gradient(135deg, #2563eb, #3b82f6);
        }

        .layanan-card.orange .layanan-icon {
            background: linear-gradient(135deg, #f59e0b, #fbbf24);
        }

        .layanan-card.purple .layanan-icon {
            background: linear-gradient(135deg, #9333ea, #a855f7);
        }

        .layanan-card.teal .layanan-icon {
            background: linear-gradient(135deg, #14b8a6, #2dd4bf);
        }

        .layanan-card.red .layanan-icon {
            background: linear-gradient(135deg, #dc2626, #ef4444);
        }

        .layanan-card.indigo .layanan-icon {
            background: linear-gradient(135deg, #4f46e5, #6366f1);
        }

        .layanan-card.pink .layanan-icon {
            background: linear-gradient(135deg, #ec4899, #f472b6);
        }

        .layanan-card.cyan .layanan-icon {
            background: linear-gradient(135deg, #06b6d4, #22d3ee);
        }

        .layanan-card.lime .layanan-icon {
            background: linear-gradient(135deg, #84cc16, #a3e635);
        }

        .layanan-card.amber .layanan-icon {
            background: linear-gradient(135deg, #f59e0b, #fbbf24);
        }

        .layanan-card.emerald .layanan-icon {
            background: linear-gradient(135deg, #10b981, #34d399);
        }

        .layanan-card.violet .layanan-icon {
            background: linear-gradient(135deg, #8b5cf6, #a78bfa);
        }

        .layanan-card:hover .layanan-icon {
            transform: rotateY(360deg) scale(1.1);
        }

        .layanan-icon i {
            font-size: 1.8rem;
            color: white;
        }

        /* ============ CARD CONTENT ============ */
        .layanan-card-title {
            font-size: 0.95rem;
            font-weight: 700;
            color: #1b4332;
            margin-bottom: 10px;
            font-family: 'Poppins', sans-serif;
            line-height: 1.3;
            min-height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .layanan-card-text {
            font-size: 0.82rem;
            color: #666;
            line-height: 1.5;
            margin-bottom: 15px;
            min-height: 40px;
        }

        .layanan-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--card-color);
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 0.85rem;
        }

        .layanan-link i {
            transition: transform 0.3s ease;
            font-size: 0.85rem;
        }

        .layanan-link:hover {
            gap: 10px;
        }

        .layanan-link:hover i {
            transform: translateX(5px);
        }

        /* ============ TRACKING BUTTON ============ */
        .tracking-wrapper {
            text-align: center;
        }

        .btn-tracking {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            padding: 15px 40px;
            background: #ffc107;
            border: 2px solid #ffffff;
            color: #ffffff;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .btn-tracking i {
            font-size: 1.2rem;
        }

        .btn-tracking:hover {
            background: linear-gradient(135deg, #16a34a, #22c55e);
            border-color: #16a34a;
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(22, 163, 74, 0.3);
        }

        /* ============ RESPONSIVE ============ */
        @media (max-width: 1400px) {
            .layanan-cards {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        @media (max-width: 1200px) {
            .layanan-cards {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 992px) {
            .layanan-section {
                padding: 60px 0;
            }

            .sambutan-title {
                font-size: 2.2rem;
            }

            .layanan-cards {
                grid-template-columns: repeat(3, 1fr);
                gap: 18px;
            }

            .layanan-card {
                padding: 22px 15px;
            }

            .layanan-icon {
                width: 55px;
                height: 55px;
            }

            .layanan-icon i {
                font-size: 1.6rem;
            }
        }

        @media (max-width: 768px) {
            .layanan-section {
                padding: 50px 0;
            }

            .sambutan-title {
                font-size: 2rem;
            }

            .layanan-cards {
                grid-template-columns: repeat(2, 1fr);
                gap: 15px;
            }

            .layanan-card {
                padding: 20px 12px;
            }

            .layanan-card-title {
                font-size: 0.88rem;
                min-height: 36px;
            }

            .layanan-card-text {
                font-size: 0.78rem;
                min-height: 38px;
            }

            .btn-tracking {
                padding: 12px 30px;
                font-size: 0.95rem;
            }
        }

        @media (max-width: 576px) {
            .layanan-header {
                margin-bottom: 35px;
            }

            .sambutan-title {
                font-size: 1.75rem;
            }

            .layanan-card {
                padding: 25px 20px;
            }

            .layanan-icon {
                width: 60px;
                height: 60px;
            }

            .layanan-icon i {
                font-size: 1.8rem;
            }

            .layanan-card-title {
                font-size: 1rem;
                min-height: auto;
            }

            .layanan-card-text {
                font-size: 0.85rem;
                min-height: auto;
            }

            .btn-tracking {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
    </style>
@endpush

@section('content')
    <!-- Pattern Overlay -->
    <div class="pattern-overlay"></div>

    <!-- HERO -->
    <section class="page-hero" style="--hero-bg: url('{{ asset('img/background/bg-1.jpg') }}');">
        <div class="container position-relative">
            <h1 class="page-title">Layanan</h1>

            <nav class="breadcrumb-wrapper">
                <a href="{{ url('/') }}">Home</a>
                <span>›</span>
                <span class="active">Layanan</span>
            </nav>
        </div>
    </section>

    <!-- CONTENT -->
    <section class="content-section">
        <div class="container">
            <div class="layanan-cards">

                {{-- Card 1: Permohonan Bantuan --}}
                <div class="layanan-card green" data-aos="fade-up" data-aos-delay="100">
                    <div class="layanan-icon">
                        <i class="bi bi-clipboard-check"></i>
                    </div>
                    <h3 class="layanan-card-title">Permohonan Bantuan</h3>
                    <p class="layanan-card-text">
                        Ajukan permohonan bantuan alat, bibit, atau pupuk pertanian
                    </p>
                    <a href="#" class="layanan-link">
                        Ajukan
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

                {{-- Card 2: Pengaduan Masyarakat --}}
                <div class="layanan-card blue" data-aos="fade-up" data-aos-delay="150">
                    <div class="layanan-icon">
                        <i class="bi bi-chat-left-text"></i>
                    </div>
                    <h3 class="layanan-card-title">Pengaduan Masyarakat</h3>
                    <p class="layanan-card-text">
                        Sampaikan keluhan atau pengaduan terkait layanan kami
                    </p>
                    <a href="http://siwawan.kuningankab.go.id/" target="_blank" class="layanan-link">
                        Ajukan
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

                {{-- Card 3: Konsultasi Pertanian --}}
                <div class="layanan-card orange" data-aos="fade-up" data-aos-delay="200">
                    <div class="layanan-icon">
                        <i class="bi bi-question-circle"></i>
                    </div>
                    <h3 class="layanan-card-title">Konsultasi Pertanian</h3>
                    <p class="layanan-card-text">
                        Konsultasikan masalah pertanian Anda dengan ahli kami
                    </p>
                    <a href="#" class="layanan-link">
                        Ajukan
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

                {{-- Card 4: Permohonan Data --}}
                <div class="layanan-card purple" data-aos="fade-up" data-aos-delay="250">
                    <div class="layanan-icon">
                        <i class="bi bi-file-earmark-text"></i>
                    </div>
                    <h3 class="layanan-card-title">Permohonan Data</h3>
                    <p class="layanan-card-text">
                        Ajukan permohonan data dan informasi publik
                    </p>
                    <a href="#" class="layanan-link">
                        Ajukan
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

                {{-- Card 5: Perizinan --}}
                <div class="layanan-card teal" data-aos="fade-up" data-aos-delay="300">
                    <div class="layanan-icon">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <h3 class="layanan-card-title">Perizinan</h3>
                    <p class="layanan-card-text">
                        Layanan perizinan usaha dan kegiatan pertanian
                    </p>
                    <a href="https://perizinan.pertanian.go.id/app/" target="_blank" class="layanan-link">
                        Ajukan
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

                {{-- Card 6: Perbenihan --}}
                <div class="layanan-card emerald" data-aos="fade-up" data-aos-delay="350">
                    <div class="layanan-icon">
                        <i class="bi bi-flower1"></i>
                    </div>
                    <h3 class="layanan-card-title">Perbenihan</h3>
                    <p class="layanan-card-text">
                        Informasi dan layanan terkait benih unggul bersertifikat
                    </p>
                    <a href="#" class="layanan-link">
                        Ajukan
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

                {{-- Card 7: Data Penerima Pupuk --}}
                <div class="layanan-card lime" data-aos="fade-up" data-aos-delay="400">
                    <div class="layanan-icon">
                        <i class="bi bi-database"></i>
                    </div>
                    <h3 class="layanan-card-title">Data Penerima Pupuk</h3>
                    <p class="layanan-card-text">
                        Cek data dan informasi penerima pupuk bersubsidi
                    </p>
                    <a href="https://pupukbersubsidi.pertanian.go.id/ceksubsidi/search" target="_blank"
                        class="layanan-link">
                        Lihat
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

                {{-- Card 8: Informasi Harga Pangan --}}
                <div class="layanan-card amber" data-aos="fade-up" data-aos-delay="450">
                    <div class="layanan-icon">
                        <i class="bi bi-graph-up-arrow"></i>
                    </div>
                    <h3 class="layanan-card-title">Informasi Harga Pangan</h3>
                    <p class="layanan-card-text">
                        Update harga komoditas pangan dan pertanian terkini
                    </p>
                    <a href="#" class="layanan-link">
                        Lihat
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

                {{-- Card 9: Pengendalian OPT --}}
                <div class="layanan-card red" data-aos="fade-up" data-aos-delay="500">
                    <div class="layanan-icon">
                        <i class="bi bi-bug"></i>
                    </div>
                    <h3 class="layanan-card-title">Pengendalian OPT</h3>
                    <p class="layanan-card-text">
                        Layanan pengendalian organisme pengganggu tanaman
                    </p>
                    <a href="#" class="layanan-link">
                        Ajukan
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

                {{-- Card 10: Rekomendasi BBM Subsidi --}}
                <div class="layanan-card indigo" data-aos="fade-up" data-aos-delay="550">
                    <div class="layanan-icon">
                        <i class="bi bi-fuel-pump"></i>
                    </div>
                    <h3 class="layanan-card-title">Rekomendasi BBM Subsidi</h3>
                    <p class="layanan-card-text">
                        Surat rekomendasi izin pembelian BBM bersubsidi
                    </p>
                    <a href="#" class="layanan-link">
                        Ajukan
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

                {{-- Card 11: Distribusi Sarana Prasarana --}}
                <div class="layanan-card cyan" data-aos="fade-up" data-aos-delay="600">
                    <div class="layanan-icon">
                        <i class="bi bi-truck"></i>
                    </div>
                    <h3 class="layanan-card-title">Distribusi Sarana Prasarana</h3>
                    <p class="layanan-card-text">
                        Layanan distribusi alat dan sarana prasarana pertanian
                    </p>
                    <a href="#" class="layanan-link">
                        Ajukan
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

                {{-- Card 12: Rekomendasi Produk Pangan --}}
                <div class="layanan-card pink" data-aos="fade-up" data-aos-delay="650">
                    <div class="layanan-icon">
                        <i class="bi bi-clipboard2-check"></i>
                    </div>
                    <h3 class="layanan-card-title">Rekomendasi Produk Pangan</h3>
                    <p class="layanan-card-text">
                        Surat rekomendasi izin produk pangan segar asal tumbuhan
                    </p>
                    <a href="#" class="layanan-link">
                        Ajukan
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

                {{-- Card 13: Penyebarluasan Informasi --}}
                <div class="layanan-card violet" data-aos="fade-up" data-aos-delay="700">
                    <div class="layanan-icon">
                        <i class="bi bi-megaphone"></i>
                    </div>
                    <h3 class="layanan-card-title">Penyebarluasan Informasi</h3>
                    <p class="layanan-card-text">
                        Informasi ketahanan pangan dan pertanian terkini
                    </p>
                    <a href="#" class="layanan-link">
                        Lihat
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

            </div>
        </div>
    </section>

@endsection
