@extends('public.layouts.app')

@section('title', 'Detail Agenda')

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
        height: 450px;
        object-fit: cover;
        border-radius: 20px;
        margin-bottom: 30px;
        box-shadow: 0 10px 40px rgba(45, 106, 79, 0.15);
    }
    
    /* ============ TITLE SECTION ============ */
    .detail-title {
        font-family: 'Playfair Display', serif;
        font-size: 2.5rem;
        font-weight: 900;
        color: #2d6a4f;
        margin-bottom: 20px;
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
    
    /* ============ INFO BOXES ============ */
    .info-boxes {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
    
    .info-box {
        background: linear-gradient(135deg, rgba(82, 183, 136, 0.05), rgba(116, 198, 157, 0.05));
        border-left: 4px solid #52b788;
        padding: 20px;
        border-radius: 15px;
        transition: all 0.3s ease;
    }
    
    .info-box:hover {
        background: white;
        box-shadow: 0 5px 20px rgba(45, 106, 79, 0.1);
        transform: translateY(-5px);
    }
    
    .info-box-icon {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #2d6a4f, #40916c);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        margin-bottom: 15px;
    }
    
    .info-box-label {
        font-size: 0.9rem;
        color: #666;
        margin-bottom: 5px;
        font-weight: 500;
    }
    
    .info-box-value {
        font-size: 1.1rem;
        color: #2d6a4f;
        font-weight: 700;
    }
    
    /* ============ CONTENT TEXT ============ */
    .detail-content {
        font-size: 1.05rem;
        line-height: 1.9;
        color: #333;
        text-align: justify;
    }
    
    .detail-content p {
        margin-bottom: 20px;
    }
    
    .detail-content h5 {
        color: #2d6a4f;
        font-weight: 700;
        margin-top: 30px;
        margin-bottom: 15px;
        font-family: 'Playfair Display', serif;
    }
    
    .detail-content ul,
    .detail-content ol {
        margin-bottom: 20px;
        padding-left: 25px;
    }
    
    .detail-content li {
        margin-bottom: 10px;
        line-height: 1.8;
    }
    
    /* ============ SHARE BUTTONS ============ */
    .share-section {
        margin-top: 40px;
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
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    
    .share-btn.facebook { background: #1877f2; }
    .share-btn.twitter { background: #1da1f2; }
    .share-btn.whatsapp { background: #25d366; }
    .share-btn.telegram { background: #0088cc; }
    .share-btn.email { background: #ea4335; }
    
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
            font-size: 1.8rem;
        }
        
        .featured-image {
            height: 300px;
        }
        
        .meta-info {
            flex-direction: column;
            gap: 15px;
        }
        
        .info-boxes {
            grid-template-columns: 1fr;
        }
        
        .detail-content {
            font-size: 0.95rem;
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
            <h1 class="page-title">Detail Agenda</h1>

            <nav class="breadcrumb-wrapper">
                <a href="{{ url('/') }}">Home</a>
                <span>›</span>
                <a href="{{ url('/agenda') }}">Agenda</a>
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
                    
                    <a href="{{ url('/agenda') }}" class="back-button" data-aos="fade-right">
                        <i class="bi bi-arrow-left"></i>
                        Kembali ke Daftar Agenda
                    </a>

                    <div class="card detail-card" data-aos="fade-up" data-aos-duration="1000">
                        <div class="card-body">
                            
                            <!-- Featured Image -->
                            <img src="https://images.unsplash.com/photo-1560493676-04071c5f467b?w=1200&h=600&fit=crop" 
                                 alt="Sosialisasi Program Reboisasi" 
                                 class="featured-image"
                                 data-aos="zoom-in"
                                 data-aos-delay="200">
                            
                            <!-- Title -->
                            <h1 class="detail-title" data-aos="fade-up" data-aos-delay="300">
                                Sosialisasi Program Reboisasi 2025
                            </h1>
                            
                            <!-- Meta Info -->
                            <div class="meta-info" data-aos="fade-up" data-aos-delay="400">
                                <div class="meta-item">
                                    <i class="bi bi-calendar-event"></i>
                                    <div>
                                        <small class="d-block" style="font-size: 0.85rem; color: #999;">Tanggal</small>
                                        <strong>28 Desember 2024</strong>
                                    </div>
                                </div>
                                <div class="meta-item">
                                    <i class="bi bi-clock"></i>
                                    <div>
                                        <small class="d-block" style="font-size: 0.85rem; color: #999;">Waktu</small>
                                        <strong>09:00 - 15:00 WIB</strong>
                                    </div>
                                </div>
                                <div class="meta-item">
                                    <i class="bi bi-geo-alt"></i>
                                    <div>
                                        <small class="d-block" style="font-size: 0.85rem; color: #999;">Lokasi</small>
                                        <strong>Aula Dinas Pertanian</strong>
                                    </div>
                                </div>
                            </div>

                            <!-- Info Boxes -->
                            <div class="info-boxes" data-aos="fade-up" data-aos-delay="500">
                                <div class="info-box">
                                    <div class="info-box-icon">
                                        <i class="bi bi-people"></i>
                                    </div>
                                    <div class="info-box-label">Peserta</div>
                                    <div class="info-box-value">100 Orang</div>
                                </div>
                                <div class="info-box">
                                    <div class="info-box-icon">
                                        <i class="bi bi-person-badge"></i>
                                    </div>
                                    <div class="info-box-label">Narasumber</div>
                                    <div class="info-box-value">Dr. Ahmad Fauzi</div>
                                </div>
                                <div class="info-box">
                                    <div class="info-box-icon">
                                        <i class="bi bi-bookmark-check"></i>
                                    </div>
                                    <div class="info-box-label">Status</div>
                                    <div class="info-box-value">Akan Datang</div>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="detail-content" data-aos="fade-up" data-aos-delay="600">
                                <h5>Tentang Kegiatan</h5>
                                <p>
                                    Dinas Ketahanan Pangan dan Pertanian Kabupaten Kuningan mengadakan kegiatan sosialisasi Program Reboisasi 2025 
                                    sebagai upaya meningkatkan kesadaran masyarakat dalam menjaga kelestarian hutan dan lingkungan hidup. 
                                    Program ini merupakan bagian dari komitmen pemerintah daerah dalam mendukung pembangunan berkelanjutan 
                                    dan pelestarian sumber daya alam.
                                </p>

                                <p>
                                    Kegiatan ini akan menghadirkan para ahli di bidang kehutanan dan lingkungan yang akan membagikan 
                                    pengetahuan dan pengalaman mereka mengenai pentingnya reboisasi, teknik penanaman yang baik, 
                                    serta manfaat jangka panjang dari program ini bagi masyarakat dan lingkungan.
                                </p>

                                <h5>Tujuan Kegiatan</h5>
                                <ul>
                                    <li>Meningkatkan pemahaman masyarakat tentang pentingnya reboisasi untuk kelestarian lingkungan</li>
                                    <li>Memberikan pengetahuan teknis tentang cara menanam dan merawat pohon dengan benar</li>
                                    <li>Membangun kesadaran kolektif dalam menjaga hutan dan lahan hijau di Kabupaten Kuningan</li>
                                    <li>Mendorong partisipasi aktif masyarakat dalam program penghijauan</li>
                                    <li>Menyosialisasikan target penanaman 500 ribu pohon pada tahun 2025</li>
                                </ul>

                                <h5>Materi yang Akan Disampaikan</h5>
                                <ol>
                                    <li>Kondisi hutan dan lingkungan di Kabupaten Kuningan saat ini</li>
                                    <li>Manfaat reboisasi bagi masyarakat dan lingkungan</li>
                                    <li>Teknik penanaman dan pemilihan bibit yang tepat</li>
                                    <li>Perawatan pohon untuk pertumbuhan optimal</li>
                                    <li>Program insentif bagi peserta aktif reboisasi</li>
                                </ol>

                                <h5>Sasaran Peserta</h5>
                                <p>
                                    Kegiatan ini terbuka untuk umum dengan prioritas kepada:
                                </p>
                                <ul>
                                    <li>Kelompok tani dan masyarakat sekitar hutan</li>
                                    <li>Tokoh masyarakat dan pemuda karang taruna</li>
                                    <li>Lembaga swadaya masyarakat (LSM) peduli lingkungan</li>
                                    <li>Pegawai instansi pemerintah terkait</li>
                                    <li>Mahasiswa dan pelajar yang tertarik dengan isu lingkungan</li>
                                </ul>

                                <h5>Pendaftaran</h5>
                                <p>
                                    Peserta yang berminat mengikuti kegiatan ini dapat mendaftar melalui:
                                </p>
                                <ul>
                                    <li><strong>Langsung:</strong> Datang ke Kantor Dinas Pertanian Kabupaten Kuningan</li>
                                    <li><strong>Telepon:</strong> (0232) 877096</li>
                                    <li><strong>Email:</strong> info@dinasperhutanan.kuningankab.go.id</li>
                                    <li><strong>Batas Pendaftaran:</strong> 25 Desember 2024</li>
                                </ul>

                                <p>
                                    Peserta yang terdaftar akan mendapatkan sertifikat kehadiran, materi sosialisasi, 
                                    dan bibit pohon gratis untuk ditanam di lingkungan masing-masing.
                                </p>
                            </div>

                            <!-- Share Section -->
                            <div class="share-section" data-aos="fade-up" data-aos-delay="700">
                                <h6 class="share-title">
                                    <i class="bi bi-share"></i>
                                    Bagikan Agenda Ini
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

                    <!-- AGENDA TERDEKAT -->
                    <div class="card border-0 shadow-sm mb-4 sidebar-card" data-aos="fade-left" data-aos-delay="300">
                        <div class="card-body">
                            <h6 class="fw-semibold mb-3">
                                <i class="bi bi-clock-history me-2"></i>Agenda Terdekat
                            </h6>

                            @for ($i = 1; $i <= 5; $i++)
                                <a href="#" class="sidebar-news-item d-flex gap-3 mb-3 text-decoration-none">
                                    <img src="https://images.unsplash.com/photo-{{ 1464226184 + $i }}884fa280b87c399?w=80&h=80&fit=crop" 
                                         alt="Agenda {{ $i }}"
                                         class="sidebar-news-img">
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

                    <!-- AGENDA POPULER -->
                    <div class="card border-0 shadow-sm sidebar-card" data-aos="fade-left" data-aos-delay="400">
                        <div class="card-body">
                            <h6 class="fw-semibold mb-3">
                                <i class="bi bi-fire me-2"></i>Agenda Populer
                            </h6>

                            @for ($i = 1; $i <= 5; $i++)
                                <a href="#" class="sidebar-news-item d-flex gap-3 mb-3 text-decoration-none">
                                    <img src="https://images.unsplash.com/photo-{{ 1542273917 + $i }}363-3b1817f69a2d?w=80&h=80&fit=crop" 
                                         alt="Popular {{ $i }}"
                                         class="sidebar-news-img">
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