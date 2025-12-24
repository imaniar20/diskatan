@extends('public.layouts.app')

@section('title', 'Sejarah Singkat')

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
    
    /* ============ HISTORY CARD ============ */
    .history-card {
        background: white;
        border-radius: 25px;
        box-shadow: 0 15px 50px rgba(45, 106, 79, 0.1);
        border: none;
        overflow: hidden;
        position: relative;
        transition: all 0.4s ease;
    }
    
    .history-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 5px;
        height: 100%;
        background: linear-gradient(180deg, #2d6a4f, #40916c, #52b788);
    }
    
    .history-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 25px 70px rgba(45, 106, 79, 0.2);
    }
    
    .history-card .card-body {
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
        margin-bottom: 40px;
    }
    
    /* ============ CONTENT PARAGRAPHS ============ */
    .history-card p {
        font-size: 1.05rem;
        line-height: 1.9;
        color: #333;
        margin-bottom: 25px;
        text-align: justify;
        position: relative;
        padding-left: 20px;
    }
    
    .history-card p::before {
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
    
    .history-card p:last-child {
        margin-bottom: 0;
    }
    
    /* ============ HIGHLIGHT TEXT ============ */
    .history-card i {
        color: #40916c;
        font-weight: 500;
    }
    
    .history-card b {
        color: #2d6a4f;
        font-weight: 700;
        background: linear-gradient(135deg, rgba(82, 183, 136, 0.1), rgba(116, 198, 157, 0.1));
        padding: 2px 8px;
        border-radius: 5px;
    }
    
    /* ============ TIMELINE DECORATION ============ */
    .timeline-marker {
        position: absolute;
        left: -30px;
        top: 50%;
        transform: translateY(-50%);
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #2d6a4f, #40916c);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        box-shadow: 0 5px 20px rgba(45, 106, 79, 0.4);
        z-index: 10;
    }
    
    /* ============ INFO BOX ============ */
    .info-box {
        background: linear-gradient(135deg, rgba(82, 183, 136, 0.1), rgba(116, 198, 157, 0.05));
        border-left: 4px solid #52b788;
        padding: 20px 25px;
        border-radius: 10px;
        margin: 30px 0;
    }
    
    .info-box i {
        color: #2d6a4f;
        font-size: 1.2rem;
        margin-right: 10px;
    }
    
    .info-box strong {
        color: #2d6a4f;
        font-weight: 700;
    }
    
    /* ============ ADDRESS HIGHLIGHT ============ */
    .address-highlight {
        background: white;
        border: 2px solid #52b788;
        padding: 20px;
        border-radius: 15px;
        margin: 25px 0;
        box-shadow: 0 5px 20px rgba(45, 106, 79, 0.1);
    }
    
    .address-highlight i {
        color: #2d6a4f !important;
        font-style: normal !important;
        font-weight: 500;
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
    
    /* ============ YEAR BADGE ============ */
    .year-badge {
        display: inline-block;
        background: linear-gradient(135deg, #2d6a4f, #40916c);
        color: white;
        padding: 8px 20px;
        border-radius: 25px;
        font-weight: 700;
        font-size: 1rem;
        margin: 0 5px;
        box-shadow: 0 3px 10px rgba(45, 106, 79, 0.3);
        transition: all 0.3s ease;
    }
    
    .year-badge:hover {
        transform: translateY(-2px) scale(1.05);
        box-shadow: 0 5px 15px rgba(45, 106, 79, 0.4);
    }
    
    /* ============ RESPONSIVE ============ */
    @media (max-width: 768px) {
        .page-title {
            font-size: 2.5rem;
        }
        
        .history-card .card-body {
            padding: 30px 25px;
        }
        
        .section-title {
            font-size: 1.5rem;
        }
        
        .section-sub-title {
            font-size: 1.1rem;
        }
        
        .history-card p {
            font-size: 0.95rem;
            padding-left: 15px;
        }
        
        .timeline-marker {
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
            <h1 class="page-title">Sejarah Singkat</h1>

            <nav class="breadcrumb-wrapper">
                <a href="{{ url('/') }}">Home</a>
                <span>›</span>
                <a href="#">Profil</a>
                <span>›</span>
                <span class="active">Sejarah Singkat</span>
            </nav>
        </div>
    </section>

    <!-- CONTENT -->
    <section class="content-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">

                    <div class="card history-card" data-aos="fade-up" data-aos-duration="1000">
                        <div class="card-body position-relative">
                            <!-- Timeline Marker -->
                            <div class="timeline-marker d-none d-lg-flex">
                                <i class="bi bi-book"></i>
                            </div>

                            <h3 class="mb-3 section-title text-center" data-aos="fade-down" data-aos-delay="200">
                                Dinas Ketahanan Pangan dan Pertanian Kabupaten Kuningan
                            </h3>
                            <h5 class="mb-4 section-sub-title text-center" data-aos="fade-down" data-aos-delay="300">
                                Sejarah Singkat
                            </h5>

                            <div class="address-highlight" data-aos="zoom-in" data-aos-delay="400">
                                <p class="mb-0">
                                    <i class="bi bi-geo-alt-fill me-2"></i>
                                    <strong>Alamat:</strong> <i>Jalan Raya Syech Manglayang No. 3 Windujanten Kecamatan Kadugede
                                    Kabupaten Kuningan Jawa Barat 45561</i>
                                    <br>
                                    <i class="bi bi-telephone-fill ms-4 me-2"></i>
                                    <strong>Telp/Fax:</strong> <i>(0232) 877096</i>
                                </p>
                            </div>

                            <p data-aos="fade-right" data-aos-delay="500">
                                Dinas Ketahanan Pangan dan Pertanian Kabupaten Kuningan sebagai salah satu wilayah di Jawa
                                Barat yang mempunyai kawasan
                                budidaya lahan sawah yang relatif luas. Dalam rangka ketahanan pangan maka fungsi lahan
                                dikawasan pertanian lahan basah, terutama lahan sawah beririgasi teknis harus tetap
                                dipertahankan, Selain itu, dinas pertanian juga memiliki beberapa tugas dan fungsi lain
                                seperti penyuluhan pertanian, merumuskan kebijakan pertanian, memutus kebijakan bidang
                                pangan, administrasi ketatausahaan pertanian, pembinaan teknis pada pihak-pihak bidang
                                pertanian, memastikan ketersedian pupuk pertanian, hingga penyaluran bantuan alat dan mesin
                                pendukung pertanian.
                            </p>

                            <p data-aos="fade-right" data-aos-delay="600">
                                Selain itu, dinas pertanian juga adalah penjamis kesejahteraan petani
                                melalui program memastikan memberikan asuransi usaha tani padi (AUPT). Oleh karenanya,
                                terkait dengan fungsi dan tugasnya, dinas pertanian memiliki wewenang untuk mengeluarkan
                                surat izin pertanian, izin alih fungsi, izin usaha pertanian, pembukaan lahan dan izin
                                lainnya terkait pertanian.
                            </p>

                            <div class="info-box" data-aos="zoom-in" data-aos-delay="700">
                                <i class="bi bi-info-circle-fill"></i>
                                <strong>Peran Penting:</strong> Dinas Ketahanan Pangan dan Pertanian merupakan salah satu institusi yang mempunyai peran
                                penting dan akses besar dalam mensukseskan pembangunan pertanian yang dalam pelaksanaanya
                                tidak terlepas dari kerjasama/ koordinasi baik lintas sektoral maupun vertikal dengan dinas,
                                instansi, badan lembaga (Dibale) terkait serta memperhatikan aspirasi masyarakat khususnya
                                para petani.
                            </div>

                            <h5 class="mt-5 mb-4" style="color: #2d6a4f; font-weight: 700;" data-aos="fade-up" data-aos-delay="800">
                                <i class="bi bi-clock-history me-2"></i>Perjalanan Sejarah
                            </h5>

                            <p data-aos="fade-right" data-aos-delay="900">
                                <i>Dalam Rangka Otonomi Daerah tahun</i> <span class="year-badge">2001</span>, 5 (lima) Intansi terdiri dari Dinas Pertanian Tanaman
                                Pangan Kabupaten Kuningan, Peternakan Kabupaten Kuningan, Dinas Perikanan Kabupaten
                                Kuningan, SPHB (Sekertariat Pengendalian Harian Bimas) Kabupaten Kuningan, dan BIPP (Balai
                                Informasi Penyuluhan Petanian) Kabupaten Kuningan digabungkan menjadi satu Instansi yang
                                bernama <b>Dinas Pertanian Kabupaten Kuningan</b>.
                            </p>

                            <p data-aos="fade-right" data-aos-delay="1000">
                                Sesuai dengan <i>Peraturan Pemerintah No. 25 tahun 2000</i> tentang Kewenangan Pemerintah dan
                                Kewenangan Provinsi sebagai Daerah Otonom dan Keputusan Mentri Dalam Negeri dan Otonomi
                                Daerah No. 50 Tahun 2000 tentang Pedoman Susunan Organisasi Perangkat Daerah, setelah itu
                                pada awal <i>tahun</i> <span class="year-badge">2009</span> <i>sesuai dengan PP 41</i> nama Dinas Pertanian Kabupaten Kuningan di rubah
                                menjadi <b>Dinas Pertanian, Peternakan dan Perikanan Kabupaten Kuningan</b>.
                            </p>

                            <p data-aos="fade-right" data-aos-delay="1100">
                                Sesuai dengan Peraturan Daerah Kabupaten Kuningan <i>No. 5 Tahun</i> <span class="year-badge">2016</span> tentang Perubahan atas
                                telah terjadi perubahan struktur Satuan Organisasi dan Tata Kerja pada <b>Dinas Pertanian
                                Kabupaten Kuningan</b>.
                            </p>

                            <p class="mb-0" data-aos="fade-right" data-aos-delay="1200">
                                Sesuai dengan Peraturan Bupati Kuningan Kabupaten Kuningan <i>No. 73 Tahun</i> <span class="year-badge">2019</span> tentang
                                Perubahan atas telah terjadi perubahan Kedudukan, Susunan Organisasi, Tugas Pokok, Fungsi
                                dan Uraian Tugas, serta tata Kerja <b>Dinas Ketahanan Pangan dan Pertanian Kabupaten Kuningan</b>.
                            </p>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

@endsection