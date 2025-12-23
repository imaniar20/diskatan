@extends('public.layouts.app')

@section('title', 'Home')

@section('content')

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
                <div class="carousel-item active">
                    <img src="https://images.unsplash.com/photo-1542273917363-3b1817f69a2d?w=1600&h=900&fit=crop"
                        alt="Forest">
                    <div class="carousel-overlay">
                        <div class="hero-content">
                            <h1 class="hero-title">Selamat Datang</h1>
                            <p class="hero-subtitle">Dinas Ketahanan Pangan dan Pertanian</p>
                            <a href="#about" class="hero-btn">Jelajahi Lebih Lanjut</a>
                        </div>
                    </div>
                </div>

                <!-- Slide 2 -->
                <div class="carousel-item">
                    <img src="https://images.unsplash.com/photo-1511497584788-876760111969?w=1600&h=900&fit=crop"
                        alt="Green Forest">
                    <div class="carousel-overlay">
                        <div class="hero-content">
                            <h1 class="hero-title">Membangun Tata Kelola Pemerintahan Daerah</h1>
                            <p class="hero-subtitle">Profesional, Efektif, Demokratis, Dan Terpercaya Dengan Jiwa
                                Kepemimpinan Nu SAJATI.</p>
                            <a href="#about" class="hero-btn">Pelajari Program Kami</a>
                        </div>
                    </div>
                </div>

                <!-- Slide 3 -->
                <div class="carousel-item">
                    <img src="https://images.unsplash.com/photo-1473448912268-2022ce9509d8?w=1600&h=900&fit=crop"
                        alt="Nature">
                    <div class="carousel-overlay">
                        <div class="hero-content">
                            <h1 class="hero-title">Mewujudkan Masyarakat Kuningan Nu SAJATI</h1>
                            <p class="hero-subtitle">Dalam Kehidupan Beragama Dan Bernegara Dalam Bingkai Kebangsaan Dan
                                Kebhinekaan.</p>
                            <a href="#about" class="hero-btn">Lihat Kegiatan</a>
                        </div>
                    </div>
                </div>
            </div>

            <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="about-section">
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
                        <h4 style="color: var(--secondary-green); margin-bottom: 20px;">Dinas Ketahanan Pangan dan Pertanian
                        </h4>

                        <p>Dinas Perhutanan merupakan lembaga pemerintah yang bertanggung jawab dalam pengelolaan dan
                            pelestarian hutan di wilayah kami. Dengan komitmen tinggi terhadap keberlanjutan lingkungan,
                            kami berupaya menjaga keseimbangan ekosistem hutan dan meningkatkan kesejahteraan masyarakat
                            sekitar hutan.</p>

                        <p>Sejak didirikan pada tahun 1985, kami telah mengimplementasikan berbagai program konservasi,
                            reboisasi, dan pengelolaan hutan lestari. Melalui pendekatan yang inovatif dan partisipatif,
                            kami terus berupaya mewujudkan hutan yang sehat dan produktif untuk generasi saat ini dan masa
                            depan.</p>

                        <div class="stats-box" data-aos="zoom-in" data-aos-delay="200">
                            <div class="row">
                                <div class="col-4 stat-item">
                                    <span class="stat-number">250K+</span>
                                    <span class="stat-label">Hektar Hutan</span>
                                </div>
                                <div class="col-4 stat-item">
                                    <span class="stat-number">1.2K+</span>
                                    <span class="stat-label">Pohon Ditanam</span>
                                </div>
                                <div class="col-4 stat-item">
                                    <span class="stat-number">50+</span>
                                    <span class="stat-label">Program Aktif</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Agenda Section -->
    <section id="agenda" class="agenda-section">
        <div class="container">
            <h2 class="section-title text-center mb-5" data-aos="fade-up">Agenda Kegiatan</h2>

            <div class="row">
                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="agenda-card">
                        <div class="agenda-content">
                            <div class="d-flex">
                                <div class="agenda-date">
                                    <span class="day">28</span>
                                    <span class="month">Des</span>
                                </div>
                                <div class="flex-grow-1 ms-4">
                                    <h4 class="agenda-title">Sosialisasi Program Reboisasi 2025</h4>
                                    <div class="agenda-time">
                                        <i class="bi bi-clock me-2"></i>09:00 - 15:00 WIB
                                    </div>
                                    <div class="agenda-location">
                                        <i class="bi bi-geo-alt me-2"></i>Aula Dinas Perhutanan
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="agenda-card">
                        <div class="agenda-content">
                            <div class="d-flex">
                                <div class="agenda-date">
                                    <span class="day">05</span>
                                    <span class="month">Jan</span>
                                </div>
                                <div class="flex-grow-1 ms-4">
                                    <h4 class="agenda-title">Pelatihan Konservasi Tanah dan Air</h4>
                                    <div class="agenda-time">
                                        <i class="bi bi-clock me-2"></i>08:00 - 16:00 WIB
                                    </div>
                                    <div class="agenda-location">
                                        <i class="bi bi-geo-alt me-2"></i>Balai Latihan Kehutanan
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="agenda-card">
                        <div class="agenda-content">
                            <div class="d-flex">
                                <div class="agenda-date">
                                    <span class="day">12</span>
                                    <span class="month">Jan</span>
                                </div>
                                <div class="flex-grow-1 ms-4">
                                    <h4 class="agenda-title">Aksi Tanam Pohon Bersama Masyarakat</h4>
                                    <div class="agenda-time">
                                        <i class="bi bi-clock me-2"></i>07:00 - 12:00 WIB
                                    </div>
                                    <div class="agenda-location">
                                        <i class="bi bi-geo-alt me-2"></i>Kawasan Hutan Lindung Gunung Hijau
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="400">
                    <div class="agenda-card">
                        <div class="agenda-content">
                            <div class="d-flex">
                                <div class="agenda-date">
                                    <span class="day">20</span>
                                    <span class="month">Jan</span>
                                </div>
                                <div class="flex-grow-1 ms-4">
                                    <h4 class="agenda-title">Workshop Pengelolaan Hasil Hutan Lestari</h4>
                                    <div class="agenda-time">
                                        <i class="bi bi-clock me-2"></i>09:00 - 17:00 WIB
                                    </div>
                                    <div class="agenda-location">
                                        <i class="bi bi-geo-alt me-2"></i>Hotel Rimba Raya
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- News Section -->
    <section id="news" class="news-section">
        <div class="container">
            <h2 class="section-title text-center mb-5" data-aos="fade-up">Berita Terkini</h2>

            <div class="row">
                @foreach ($news as $item)
                    <div class="col-lg-4 mb-4" data-aos="fade-up" data-aos-delay="100">
                        <div class="news-card">
                            <img src="{{ asset($item->thumbnail ? 'storage/' . $item->thumbnail : 'images/img_not_found.png') }}"
                                alt="News 1" class="news-img">
                            <div class="news-content">
                                <span class="news-badge">Berita</span>
                                <h4 class="news-title">{{ Str::limit($item->title, 40) }}</h4>
                                <p class="news-excerpt">{!! Str::limit($item->content, 160) !!}</p>
                                <div class="news-meta">
                                    <span class="news-date"><i class="bi bi-calendar3 me-2"></i>{{ \Carbon\Carbon::parse($item->published_at)->format('d M Y') }}</span>
                                    <a href="{{ route('berita.detail', $item->slug) }}" class="news-link">Baca Selengkapnya <i
                                            class="bi bi-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Stats & Survey Section -->
    <section id="stats" class="stats-survey-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-5 mb-4 mb-lg-0" data-aos="fade-right" data-aos-duration="1000">
                    <div class="visitor-counter">
                        <h3 class="counter-title">Total Pengunjung Website</h3>
                        <span class="counter-number" id="visitorCount">125,847</span>
                        <p class="counter-label">Pengunjung sejak Januari 2024</p>
                    </div>
                </div>

                <div class="col-lg-7" data-aos="fade-left" data-aos-duration="1000">
                    <div class="survey-box">
                        <h3 class="survey-title">Survey Kepuasan Layanan</h3>
                        <p style="color: #666; margin-bottom: 30px;">Bantu kami meningkatkan pelayanan dengan memberikan
                            penilaian Anda</p>

                        <div class="rating-group">
                            <label style="display: block; margin-bottom: 10px; font-weight: 600;">Bagaimana pengalaman
                                Anda?</label>
                            <div class="rating-stars" id="ratingStars">
                                <i class="bi bi-star-fill" data-rating="1"></i>
                                <i class="bi bi-star-fill" data-rating="2"></i>
                                <i class="bi bi-star-fill" data-rating="3"></i>
                                <i class="bi bi-star-fill" data-rating="4"></i>
                                <i class="bi bi-star-fill" data-rating="5"></i>
                            </div>
                        </div>

                        <div class="form-group mt-4">
                            <label style="display: block; margin-bottom: 10px; font-weight: 600;">Saran & Masukan
                                (Opsional)</label>
                            <textarea class="form-control" rows="3" placeholder="Tulis saran Anda di sini..."></textarea>
                        </div>

                        <button class="btn submit-btn mt-3" onclick="submitSurvey()">
                            <i class="bi bi-send me-2"></i>Kirim Survey
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="contact-section">
        <div class="container">
            <h2 class="section-title text-center mb-5" data-aos="fade-up">Hubungi Kami</h2>

            <div class="row">
                <div class="col-lg-7 mb-4 mb-lg-0" data-aos="fade-right" data-aos-duration="1000">
                    <div class="contact-form">
                        <h4 style="color: var(--primary-green); margin-bottom: 30px; font-weight: 700;">Kirim Pesan atau
                            Saran</h4>

                        <form id="contactForm">
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="text" class="form-control" placeholder="Nama Lengkap *" required>
                                </div>
                                <div class="col-md-6">
                                    <input type="email" class="form-control" placeholder="Email *" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <input type="tel" class="form-control" placeholder="No. Telepon *" required>
                                </div>
                                <div class="col-md-6">
                                    <select class="form-select">
                                        <option selected>Pilih Kategori</option>
                                        <option>Pertanyaan Umum</option>
                                        <option>Pengaduan</option>
                                        <option>Saran & Kritik</option>
                                        <option>Kerjasama</option>
                                    </select>
                                </div>
                            </div>

                            <textarea class="form-control" rows="5" placeholder="Pesan Anda *" required></textarea>

                            <button type="submit" class="submit-btn">
                                <i class="bi bi-send me-2"></i>Kirim Pesan
                            </button>
                        </form>
                    </div>
                </div>

                <div class="col-lg-5" data-aos="fade-left" data-aos-duration="1000">
                    <div class="contact-info-box">
                        <div class="contact-info-item">
                            <i class="bi bi-geo-alt-fill"></i>
                            <h5>Alamat Kantor</h5>
                            <p>Jl. Raya Kehutanan No. 123<br>Kuningan, Jawa Barat 45511</p>
                        </div>

                        <div class="contact-info-item">
                            <i class="bi bi-telephone-fill"></i>
                            <h5>Telepon</h5>
                            <p>(0232) 123456<br>(0232) 123457</p>
                        </div>

                        <div class="contact-info-item">
                            <i class="bi bi-envelope-fill"></i>
                            <h5>Email</h5>
                            <p>info@dinasperhutanan.go.id<br>humas@dinasperhutanan.go.id</p>
                        </div>

                        <div class="contact-info-item">
                            <i class="bi bi-clock-fill"></i>
                            <h5>Jam Operasional</h5>
                            <p>Senin - Jumat: 08:00 - 16:00 WIB<br>Sabtu - Minggu: Libur</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
