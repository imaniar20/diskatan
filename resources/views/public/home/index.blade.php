<div class="page-wrapper">


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
                @php
                    $i = 0;
                @endphp
                @foreach ($news as $item)
                    <div class="carousel-item {{ $i == 0 ? 'active' : '' }}">
                        @php
                            $i++;
                        @endphp
                        <img src="{{ asset($item->thumbnail ? 'storage/' . $item->thumbnail : 'images/img_not_found.png') }}"
                            alt="Pertanian">
                        <div class="carousel-overlay">
                            <div class="container">
                                <div class="row align-items-center">
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
                                        <div class="hero-buttons d-flex justify-content-end align-items-center mb-3"
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
                                        <div class="hero-buttons d-flex justify-content-end align-items-center"
                                            data-aos="fade-up" data-aos-delay="600">

                                            <a href="https://www.facebook.com/diskatankuningan/" target="_blank"
                                                class="btn btn-primary d-flex align-items-center justify-content-center"
                                                style="width: 50px; height: 50px; padding: 0; border-radius: 50%; margin-right: 15px;">
                                                <i class="bx bxl-facebook" style="font-size: 25px;"></i>
                                            </a>
                                            <a href="https://www.tiktok.com/@diskatankuningan" target="_blank"
                                                class="btn btn-warning d-flex align-items-center justify-content-center"
                                                style="width: 50px; height: 50px; padding: 0; border-radius: 50%; margin-right: 15px;">
                                                <i class="bx bxl-instagram" style="font-size: 25px; color: white;"></i>
                                            </a>
                                            <a href="https://www.instagram.com/diskatankuningan/" target="_blank"
                                                class="btn btn-dark d-flex align-items-center justify-content-center"
                                                style="width: 50px; height: 50px; padding: 0; border-radius: 50%; margin-right: 15px;">
                                                <i class="bx bxl-tiktok" style="font-size: 25px;"></i>
                                            </a>
                                            <a href="https://www.youtube.com/@diskatankuningan" target="_blank"
                                                class="btn btn-danger d-flex align-items-center justify-content-center"
                                                style="width: 50px; height: 50px; padding: 0; border-radius: 50%; margin-right: 15px;">
                                                <i class="bx bxl-youtube" style="font-size: 25px;"></i>
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
                <div class="col-6 col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
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
                <div class="col-6 col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
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
                <div class="col-6 col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                    <div class="stat-card">
                        <div class="stat-icon bg-primary">
                            <i class="bi bi-people"></i>
                        </div>
                        <div class="stat-content">
                            <h3 class="stat-number">{{ number_format($kelompok_tani) }}</h3>
                            <p class="stat-label">Kelompok Tani</p>
                        </div>
                    </div>
                </div>

                <!-- Stat 4 -->
                <div class="col-6 col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
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
                        <div class="text-center">
                            <img src="{{ asset('storage/' . $data->foto_kadis) }}" alt="{{ $data->nama_kadis }}"
                                class="foto-kepala">
                        </div>
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

                        <div class="visitor-box d-flex justify-content-between align-items-center">
                            <div class="visitor-info quote-decoration mb-1">
                                <div class="visitor-label">Total Pengunjung</div>
                                <div class="visitor-number">
                                    <span id="visitorCount" data-count="{{ $visitor }}">0</span>
                                    <i class='bx bx-group'></i>
                                </div>
                            </div>

                            <a href="{{ url('/visi-misi') }}" class="btn-selengkapnya">
                                Selengkapnya
                                <i class="bi bi-arrow-right ms-2"></i>
                            </a>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

    @include('public.home.section.youtube')

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
                                        <span
                                            class="month">{{ \Carbon\Carbon::parse($item->date)->format('d') }}</span>
                                        <span
                                            class="day">{{ \Carbon\Carbon::parse($item->date)->format('M') }}</span>
                                        <span
                                            class="month">{{ \Carbon\Carbon::parse($item->date)->format('Y') }}</span>
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
</div>
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

    document.addEventListener("DOMContentLoaded", () => {
        const counter = document.getElementById("visitorCount");
        const target = +counter.dataset.count;
        let current = 0;

        const increment = Math.ceil(target / 60);

        const updateCounter = () => {
            current += increment;
            if (current >= target) {
                counter.textContent = target;
            } else {
                counter.textContent = current;
                requestAnimationFrame(updateCounter);
            }
        };

        updateCounter();
    });
</script>
