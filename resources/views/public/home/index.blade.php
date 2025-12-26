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
                <img src="{{ asset('img/background/bg-2.jpg') }}" alt="Pertanian">
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
                                        Mewujudkan ketahanan pangan yang berkelanjutan dan pertanian modern untuk
                                        kesejahteraan masyarakat.
                                    </p>
                                    <div class="hero-buttons" data-aos="fade-up" data-aos-delay="600">
                                        <a href="#about" class="btn btn-outline-light btn-lg px-4 py-3">
                                            <i class="bi bi-info-circle me-2"></i>
                                            Selengkapnya
                                        </a>
                                        <a href="#contact" class="btn btn-warning btn-lg px-4 py-3 me-3">
                                            <i class="bi bi-file-earmark-text me-2"></i>
                                            Kontak dan Saran
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 2 -->
            <div class="carousel-item">
                <img src="https://images.unsplash.com/photo-1625246333195-78d9c38ad449?w=1920&h=1080&fit=crop"
                    alt="Pangan">
                <div class="carousel-overlay">
                    <div class="container">
                        <div class="row align-items-center" style="min-height: 85vh;">
                            <div class="col-lg-7">
                                <div class="hero-content">
                                    <span class="welcome-badge" data-aos="fade-down">
                                        Agenda Resmi
                                    </span>

                                    <h1 class="hero-title" data-aos="fade-up" data-aos-delay="200">
                                        Agenda Strategis <span class="text-warning">Ketahanan Pangan</span>
                                    </h1>

                                    <p class="hero-subtitle" data-aos="fade-up" data-aos-delay="400">
                                        Komitmen bersama dalam membangun sistem pangan
                                        yang tangguh dan berkelanjutan.
                                    </p>
                                    <div class="hero-buttons" data-aos="fade-up" data-aos-delay="600">
                                        <a href="#agenda" class="btn btn-warning btn-lg px-4 py-3 me-3">
                                            <i class="bi bi-info-circle me-2"></i>
                                            Selengkapnya
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 3 -->
            <div class="carousel-item">
                <img src="https://images.unsplash.com/photo-1560493676-04071c5f467b?w=1920&h=1080&fit=crop"
                    alt="Pertanian Modern">
                <div class="carousel-overlay">
                    <div class="container">
                        <div class="row align-items-center" style="min-height: 85vh;">
                            <div class="col-lg-7">
                                <div class="hero-content">
                                    <span class="welcome-badge" data-aos="fade-down">
                                        Portal Pertanian Daerah
                                    </span>

                                    <h1 class="hero-title" data-aos="fade-up" data-aos-delay="200">
                                        Pertanian Maju <span class="text-warning">& Pangan Berkelanjutan</span>
                                    </h1>

                                    <p class="hero-subtitle" data-aos="fade-up" data-aos-delay="400">
                                        Mendorong pertanian yang inovatif, produktif,
                                        dan berdaya saing melalui informasi terpercaya.
                                    </p>
                                    <div class="hero-buttons" data-aos="fade-up" data-aos-delay="600">
                                        <a href="#news" class="btn btn-warning btn-lg px-4 py-3 me-3">
                                            <i class="bi bi-arrow-right-circle me-2"></i>
                                            Selengkapnya
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                        <h3 class="stat-number">45,678</h3>
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
                        <h3 class="stat-number">123,456</h3>
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
                        <h3 class="stat-number">2,345</h3>
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
                        <h3 class="stat-number">98,5</h3>
                        <p class="stat-label">Indeks Ketahanan Pangan</p>
                    </div>
                </div>
            </div>
        </div>
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
</section>

<!-- Agenda Section -->
<section id="agenda" class="agenda-section">
    <div class="container">
        <h2 class="section-title text-center mb-5" data-aos="fade-up">Agenda Kegiatan</h2>

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

<!-- Stats & Survey Section -->
<section id="stats" class="stats-survey-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-5 mb-4 mb-lg-0" data-aos="fade-right" data-aos-duration="1000">
                <div class="visitor-counter">
                    <h3 class="counter-title">Total Pengunjung Website</h3>
                    <span class="counter-number" id="">{{ $total }}</span>
                    <p class="counter-label">Pengunjung sejak Januari 2026</p>
                </div>
            </div>

            <div class="col-lg-7" data-aos="fade-left" data-aos-duration="1000">
                <div class="survey-box">
                    <h3 class="survey-title">Survey Kepuasan Layanan</h3>
                    <p style="color: #666; margin-bottom: 30px;">Bantu kami meningkatkan pelayanan dengan
                        memberikan
                        penilaian Anda</p>

                    <div class="rating-group">
                        <label style="display: block; margin-bottom: 10px; font-weight: 600;">Bagaimana pengalaman
                            Anda?</label>
                        <div class="rating-stars" id="ratingStars">
                            <input type="hidden" id="rating" value="0">
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
                        <textarea class="form-control" id="suggestion" rows="3" placeholder="Tulis saran Anda di sini..."></textarea>
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
                    <h4 style="color: var(--primary-green); margin-bottom: 30px; font-weight: 700;">Kirim Pesan
                        atau
                        Saran</h4>

                    <form id="contactForm">
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" id="name" name="name" class="form-control"
                                    placeholder="Nama Lengkap *" required>
                            </div>
                            <div class="col-md-6">
                                <input type="email" id="email" name="email" class="form-control"
                                    placeholder="Email *" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <input type="tel" id="phone" name="phone" class="form-control"
                                    placeholder="No. Telepon *" required>
                            </div>
                            <div class="col-md-6">
                                <select id="category" name="category" class="form-select" required>
                                    <option value="">Pilih Kategori</option>
                                    <option value="Pertanyaan Umum">Pertanyaan Umum</option>
                                    <option value="Pengaduan">Pengaduan</option>
                                    <option value="Saran & Kritik">Saran & Kritik</option>
                                    <option value="Kerjasama">Kerjasama</option>
                                </select>
                            </div>
                        </div>

                        <textarea id="message" name="message" class="form-control" rows="5" placeholder="Pesan Anda *" required></textarea>

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
                        <p>Jl. Raya Syech Manglayang No. 3<br>Kecamatan Kadugede Kabupaten Kuningan Provinsi Jawa Barat
                            Kode Pos 45561</p>
                    </div>

                    <div class="contact-info-item">
                        <i class="bi bi-telephone-fill"></i>
                        <h5>Telepon</h5>
                        <p>(0232) 877096</p>
                    </div>

                    {{-- <div class="contact-info-item">
                        <i class="bi bi-envelope-fill"></i>
                        <h5>Email</h5>
                        <p>info@dinasperhutanan.go.id<br>humas@dinasperhutanan.go.id</p>
                    </div> --}}

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

    function submitSurvey() {
        const rating = document.getElementById('rating').value;
        const suggestionEl = document.getElementById('suggestion');

        if (rating == 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Oops',
                text: 'Silakan pilih rating terlebih dahulu'
            });
            return;
        }

        fetch("{{ route('survey.store') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    rating: rating,
                    suggestion: suggestionEl.value
                })
            })
            .then(res => res.json())
            .then(res => {
                if (res.status) {
                    // reset form
                    suggestionEl.value = '';
                    document.getElementById('rating').value = 0;

                    // reset star (kalau pake class active)
                    document.querySelectorAll('#ratingStars i').forEach(star => {
                        star.classList.remove('active');
                    });

                    Swal.fire({
                        icon: 'success',
                        title: 'Terima kasih atas penilaian Anda 🙏',
                        text: res.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                }
            })
            .catch(() => {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Terjadi kesalahan, coba lagi'
                });
            });
    }

    document.getElementById('contactForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const form = this;
        const formData = new FormData(form);

        fetch("{{ route('contact.store') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: formData
            })
            .then(res => res.json())
            .then(res => {
                if (res.status) {
                    form.reset();

                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: res.message,
                        timer: 2500,
                        showConfirmButton: false
                    });
                }
            })
            .catch(err => {
                console.error(err);
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Pesan gagal dikirim'
                });
            });
    });
</script>
