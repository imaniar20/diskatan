<style>
    /* ============ KEPALA DINAS SECTION ============ */
    .kepala-dinas-section {
        padding: 100px 0;
        background: white;
        position: relative;
        overflow: hidden;
    }

    .kepala-dinas-section::before {
        content: '';
        position: absolute;
        top: -50px;
        left: 0;
        right: 0;
        height: 100px;
        background: white;
        clip-path: polygon(0 50%, 100% 0, 100% 100%, 0 100%);
    }

    /* ============ FOTO KEPALA DINAS ============ */
    .foto-kepala-wrapper {
        position: relative;
        border-radius: 30px;
        overflow: visible;
    }

    .foto-decoration {
        position: absolute;
        top: -20px;
        left: -20px;
        right: 20px;
        bottom: 20px;
        /* background: linear-gradient(135deg, rgba(45, 106, 79, 0.1), rgba(116, 198, 157, 0.1)); */
        border-radius: 30px;
        z-index: 1;
    }

    .foto-kepala {
        width: 100%;
        height: auto;
        border-radius: 30px;
        box-shadow: 0 20px 60px rgba(45, 106, 79, 0.2);
        position: center;
        z-index: 2;
        transition: all 0.4s ease;
    }

    .foto-kepala-wrapper:hover .foto-kepala {
        transform: translateY(-5px);
        box-shadow: 0 25px 70px rgba(45, 106, 79, 0.25);
    }

    .name-badge {
        position: absolute;
        bottom: -30px;
        left: 20px;
        right: 20px;
        background: linear-gradient(135deg, #16a34a, #40916c);
        color: white;
        padding: 20px 25px;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(45, 106, 79, 0.3);
        z-index: 3;
    }

    .name-badge h4 {
        font-size: 1.2rem;
        font-weight: 700;
        margin: 0;
        font-family: 'Playfair Display', serif;
    }

    .name-badge p {
        font-size: 0.9rem;
        margin: 0;
        opacity: 0.95;
    }

    /* ============ SAMBUTAN CONTENT ============ */
    .sambutan-content {
        padding-left: 30px;
    }

    .section-badge {
        display: inline-block;
        background: linear-gradient(135deg, rgba(45, 106, 79, 0.1), rgba(116, 198, 157, 0.1));
        border-left: 4px solid #16a34a;
        padding: 8px 20px;
        margin-bottom: 20px;
        border-radius: 0 10px 10px 0;
    }

    .badge-text {
        color: #16a34a;
        font-weight: 700;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .sambutan-title {
        font-family: 'Playfair Display', serif;
        font-size: 2.8rem;
        font-weight: 900;
        color: #000000;
        margin-bottom: 25px;
        line-height: 1.2;
    }

    .text-highlight {
        color: #16a34a;
        position: relative;
        display: inline-block;
    }

    .text-highlight::after {
        content: '';
        position: absolute;
        bottom: -5px;
        left: 0;
        right: 0;
        height: 3px;
        background: #16a34a;
        z-index: 5;
    }

    .text-highlight-warning {
        color: #ffc415;
        position: relative;
        display: inline-block;
    }

    .text-highlight-warning::after {
        content: '';
        position: absolute;
        bottom: -5px;
        left: 0;
        right: 0;
        height: 3px;
        background: #ffc415;
        z-index: 5;
    }

    .quote-decoration {
        font-size: 4rem;
        color: #16a34a;
        /* opacity: 0.2; */
        line-height: 1;
        margin-bottom: -20px;
    }

    .sambutan-text {
        font-size: 1.05rem;
        line-height: 1.8;
        color: #555;
        margin-bottom: 20px;
        text-align: justify;
    }

    .btn-selengkapnya {
        display: inline-flex;
        align-items: center;
        padding: 12px 30px;
        background: linear-gradient(135deg, #16a34a, #40916c);
        color: white;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s ease;
        margin-top: 10px;
        box-shadow: 0 5px 20px rgba(22, 163, 74, 0.3);
    }

    .btn-selengkapnya:hover {
        background: linear-gradient(135deg, #40916c, #52b788);
        color: white;
        transform: translateX(5px);
        box-shadow: 0 8px 30px rgba(22, 163, 74, 0.4);
    }

    /* ============ RESPONSIVE ============ */
    @media (max-width: 991px) {
        .kepala-dinas-section {
            padding: 80px 0;
        }

        .sambutan-content {
            padding-left: 0;
            margin-top: 50px;
        }

        .sambutan-title {
            font-size: 2.2rem;
        }

        .name-badge {
            position: relative;
            bottom: auto;
            left: 0;
            right: 0;
            margin-top: 20px;
        }

        .foto-decoration {
            display: none;
        }
    }

    @media (max-width: 768px) {
        .sambutan-title {
            font-size: 1.8rem;
        }

        .sambutan-text {
            font-size: 0.95rem;
        }

        .name-badge h4 {
            font-size: 1rem;
        }

        .name-badge p {
            font-size: 0.85rem;
        }
    }
</style>

<!-- Hero Carousel Section -->
<section id="home" class="hero-section">
    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
        </div>

        <div class="carousel-inner">
            <!-- Slide 1 -->
            @foreach ($news as $item)
                <div class="carousel-item active">
                    <img src="{{ asset($item->thumbnail ? 'storage/' . $item->thumbnail : 'images/img_not_found.png') }}"
                        alt="Pertanian">
                    <div class="carousel-overlay">
                        <div class="container">
                            <div class="row align-items-center" style="min-height: 85vh;">
                                <div class="col-lg-7">
                                    <div class="hero-content">
                                        <span class="welcome-badge" data-aos="fade-down">
                                            Selamat Datang di Website Resmi
                                        </span>
                                        <h1 class="hero-title" data-aos="fade-up" data-aos-delay="200">
                                            Dinas Ketahanan Pangan <span class="text-warning">dan Pertanian</span>
                                        </h1>
                                        <p class="hero-subtitle" data-aos="fade-up" data-aos-delay="400">
                                            {{ Str::limit($item->title, 40) }}
                                        </p>
                                        <p class="hero-subtitle-2" data-aos="fade-up" data-aos-delay="400">
                                            {!! Str::limit(preg_replace('/<img[^>]*>/i', '', $item->content), 100) !!}
                                        </p>

                                    </div>
                                </div>
                                <div class="col-lg-5">
                                    <div class="hero-buttons d-flex justify-content-end align-items-center"
                                        data-aos="fade-up" data-aos-delay="600">

                                        <a href="#agenda" class="btn btn-outline-light btn-lg px-4 py-3 me-3">
                                            <i class="bi bi-info-circle me-2"></i>
                                            Agenda
                                        </a>

                                        <a href="#news" class="btn btn-warning btn-lg px-4 py-3">
                                            <i class="bi bi-file-earmark-text me-2"></i>
                                            Berita
                                        </a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>
</section>

<!-- Statistics Section -->
<section class="statistics-section">
    <div class="container">
        <div class="row g-4">
            <!-- Stat 1 -->
            <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
                <div class="stat-card">
                    <div class="stat-icon bg-success">
                        <i class="bi bi-geo-alt"></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="stat-number">{{ $data->hektar_luas_tanam }}</h3>
                        <p class="stat-label">Hektar Luas Tanam</p>
                    </div>
                </div>
            </div>

            <!-- Stat 2 -->
            <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
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
            <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                <div class="stat-card">
                    <div class="stat-icon bg-primary">
                        <i class="bi bi-people"></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="stat-number">{{ $data->kelompok_tani }}</h3>
                        <p class="stat-label">Kelompok Tani</p>
                    </div>
                </div>
            </div>

            <!-- Stat 4 -->
            <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
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
        </div>
    </div>
</section>

<section id="greeting" class="kepala-dinas-section">
    <div class="container">
        <div class="row align-items-center">

            <!-- FOTO KEPALA DINAS -->
            <div class="col-lg-4 mb-4 mb-lg-0" data-aos="fade-right" data-aos-duration="1000">
                <div class="foto-kepala-wrapper">
                    <div class="foto-decoration"></div>
                    <img src="{{ asset('img/dinas/kadis.png') }}" alt="{{ $data->nama_kadis }}" class="foto-kepala" >
                    <div class="name-badge text-center">
                        <h4 class="mb-1">{{ $data->nama_kadis }}</h4>
                        <p class="mb-0">Kepala Dinas Ketahanan Pangan dan Pertanian</p>
                    </div>
                </div>
            </div>

            <!-- SAMBUTAN TEXT -->
            <div class="col-lg-8" data-aos="fade-left" data-aos-duration="1000">
                <div class="sambutan-content">
                    <div class="section-badge">
                        <span class="badge-text">Sambutan</span>
                    </div>

                    <h2 class="sambutan-title">
                        Sambutan <span class="text-highlight">Kepala Dinas</span>
                    </h2>
                    <div class="row">
                        <div class="col-1">
                            <div class="quote-decoration">
                                <i class="bi bi-quote"></i>
                            </div>

                        </div>
                        <div class="col-11">
                            <p class="sambutan-text">
                                {!! $data->ucapan !!}
                            </p>

                        </div>
                    </div>


                    <p class="sambutan-text">
                        {{-- Kami berkomitmen untuk terus meningkatkan ketahanan pangan daerah melalui
                        berbagai program inovatif dan pemberdayaan petani. Bersama-sama, kita wujudkan
                        pertanian yang maju dan berkelanjutan untuk kesejahteraan bersama. --}}
                    </p>

                    <a href="{{ url('/visi-misi') }}" class="btn-selengkapnya">
                        Selengkapnya
                        <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- About Section -->
{{-- <section id="about" class="about-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6" data-aos="fade-right" data-aos-duration="1000">
                <div class="about-img-container">
                    <img src="{{ asset('img/dinas/bg-1.jpg') }}" alt="Dinas Perhutanan">
                </div>
            </div>

            <div class="col-lg-6" data-aos="fade-left" data-aos-duration="1000">
                <div class="about-text">
                    <h2 class="section-title">Tentang Kami</h2>
                    <h4 style="color: var(--secondary-green); margin-bottom: 20px;">Dinas Ketahanan Pangan dan
                        Pertanian
                    </h4>

                    <p>Dinas Perhutanan merupakan lembaga pemerintah yang bertanggung jawab dalam pengelolaan dan
                        pelestarian hutan di wilayah kami. Dengan komitmen tinggi terhadap keberlanjutan lingkungan,
                        kami berupaya menjaga keseimbangan ekosistem hutan dan meningkatkan kesejahteraan masyarakat
                        sekitar hutan.</p>

                    <p>Sejak didirikan pada tahun 1985, kami telah mengimplementasikan berbagai program konservasi,
                        reboisasi, dan pengelolaan hutan lestari. Melalui pendekatan yang inovatif dan partisipatif,
                        kami terus berupaya mewujudkan hutan yang sehat dan produktif untuk generasi saat ini dan
                        masa
                        depan.</p>

                    <div class="stats-box" data-aos="zoom-in" data-aos-delay="200">
                        <div class="row">
                            <div class="col-4 stat-item">
                                <span class="stat-number2">250K+</span>
                                <span class="stat-label2">Hektar Hutan</span>
                            </div>
                            <div class="col-4 stat-item">
                                <span class="stat-number2">1.2K+</span>
                                <span class="stat-label2">Pohon Ditanam</span>
                            </div>
                            <div class="col-4 stat-item">
                                <span class="stat-number2">50+</span>
                                <span class="stat-label2">Program Aktif</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section> --}}

<!-- Agenda Section -->
<section id="agenda" class="agenda-section">
    <div class="container">
        <h2 class="sambutan-title">
            <span class="text-white">Agenda</span> <span class="text-highlight-warning">Kegiatan</span>
        </h2>

        <div class="row">
            @foreach ($agenda as $item)
                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="agenda-card">
                        <div class="agenda-content">
                            <div class="d-flex">
                                <div class="agenda-date">
                                    <span class="month">{{ \Carbon\Carbon::parse($item->date)->format('d') }}</span>
                                    <span class="day">{{ \Carbon\Carbon::parse($item->date)->format('M') }}</span>
                                    <span class="month">{{ \Carbon\Carbon::parse($item->date)->format('Y') }}</span>
                                </div>
                                <div class="flex-grow-1 ms-4">

                                    <h4 class="agenda-title">{{ Str::limit($item->title, 40) }}</h4>
                                    <div class="agenda-time">{!! Str::limit(preg_replace('/<img[^>]*>/i', '', $item->content), 100) !!}
                                    </div>
                                    <div class="agenda-location d-flex justify-content-between align-items-center">
                                        <span>
                                            <i class="bi bi-geo-alt me-2"></i>{{ $item->location }}
                                        </span>

                                        <a href="{{ route('agenda.detail', $item->slug) }}" class="news-link">
                                            <i class="bi bi-eye me-1"></i>
                                            Lihat Detail Agenda
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>
</section>

<!-- News Section -->
<section id="news" class="news-section">
    <div class="container">
        <h2 class="sambutan-title">
            Berita <span class="text-highlight">Terkini</span>
        </h2>

        <div class="row">
            @foreach ($news as $item)
                <div class="col-lg-4 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="news-card">
                        <img src="{{ asset($item->thumbnail ? 'storage/' . $item->thumbnail : 'images/img_not_found.png') }}"
                            alt="News 1" class="news-img">
                        <div class="news-content">
                            <span class="news-badge">Berita</span>
                            <h4 class="news-title">{{ Str::limit($item->title, 40) }}</h4>
                            <p class="news-excerpt">{!! Str::limit(preg_replace('/<img[^>]*>/i', '', $item->content), 160) !!}</p>
                            <div class="news-meta">
                                <span class="news-date"><i
                                        class="bi bi-calendar3 me-2"></i>{{ \Carbon\Carbon::parse($item->published_at)->format('d M Y') }}</span>
                                <a href="{{ route('berita.detail', $item->slug) }}" class="news-link">Baca
                                    Selengkapnya <i class="bi bi-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

@include('public.home.section.layanan')

@include('public.home.section.program')

@include('public.home.section.maps')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.querySelectorAll('#ratingStars i').forEach(star => {
        star.addEventListener('click', function() {
            const rating = this.getAttribute('data-rating');
            document.getElementById('rating').value = rating;

            document.querySelectorAll('#ratingStars i').forEach(s => {
                s.classList.remove('active');
            });

            for (let i = 0; i < rating; i++) {
                document.querySelectorAll('#ratingStars i')[i].classList.add('active');
            }
        });
    });
</script>
