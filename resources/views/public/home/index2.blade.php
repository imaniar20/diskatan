<style>
    /* ============ CONTENT SECTION ============ */
    .menu-section {
        min-height: 100vh;
        position: relative;
        padding: 80px 0 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    /* ============ VIDEO BACKGROUND ============ */
    .video-background {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        overflow: hidden;
        z-index: -2;
    }

    .video-background video {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .video-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: -1;
    }

    /* ============ LOGO SECTION ============ */
    .logo-container {
        text-align: center;
        margin-bottom: 50px;
    }

    .logo-wrapper {
        display: inline-block;
    }

    .logo-wrapper img {
        width: 100px;
        height: 100px;
        object-fit: contain;
        filter: drop-shadow(0 10px 30px rgba(45, 106, 79, 0.2));
    }

    .header-text {
        text-align: center;
        padding: 15px;
    }

    .floating-text {
        font-size: 2rem;
        font-weight: 700;
        text-shadow:
            0 1px 0 #ccc,
            0 2px 0 #c9c9c9,
            0 3px 0 #bbb,
            0 4px 5px rgba(0, 0, 0, 0.3);
        letter-spacing: 1px;
        margin-bottom: 10px;
        transition: all 0.3s ease;
    }

    .floating-subtext {
        font-size: 1.3rem;
        text-shadow:
            0 2px 3px rgba(0, 0, 0, 0.3),
            0 4px 7px rgba(0, 0, 0, 0.2);
        margin: 5px 0;
        letter-spacing: 0.5px;
        font-weight: 500;
    }

    /* ============ MENU GRID - CENTER ALIGNED ============ */
    .menu-grid {
        display: grid;
        /* Desktop: 6 kolom untuk 18 icon (3 baris: 6+6+6) */
        grid-template-columns: repeat(6, 1fr);
        gap: 20px;
        max-width: 1200px;
        margin: 0 auto 40px;
        padding: 0 20px;
        justify-items: center;
        /* Center items horizontally */
        align-items: start;
        /* Align items to top */
    }

    /* Center incomplete rows */
    .menu-grid::after {
        content: '';
        grid-column: 1 / -1;
    }

    .menu-item {
        position: relative;
        background: white;
        border-radius: 18px;
        padding: 20px 15px;
        text-align: center;
        cursor: pointer;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        text-decoration: none;
        overflow: hidden;
        width: 100%;
        /* Full width of grid cell */
        max-width: 180px;
        /* Maximum width */
    }

    .menu-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: inherit;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .menu-item:hover {
        transform: translateY(-10px) scale(1.05);
        box-shadow: 0 15px 50px rgba(0, 0, 0, 0.15);
    }

    .menu-item:hover::before {
        opacity: 0.1;
    }

    /* ============ ICON COLORS - 18 VARIANTS ============ */
    .menu-item.purple {
        background: linear-gradient(135deg, #9b7ef5, #7c5ce8);
    }

    .menu-item.cyan {
        background: linear-gradient(135deg, #6dd5ed, #2193b0);
    }

    .menu-item.green {
        background: linear-gradient(135deg, #56d68f, #2ecc71);
    }

    .menu-item.blue {
        background: linear-gradient(135deg, #667eea, #4568dc);
    }

    .menu-item.orange {
        background: linear-gradient(135deg, #f8a170, #f87a4a);
    }

    .menu-item.lime {
        background: linear-gradient(135deg, #9cdc7c, #7cb342);
    }

    .menu-item.yellow {
        background: linear-gradient(135deg, #ffd86f, #ffc107);
    }

    .menu-item.teal {
        background: linear-gradient(135deg, #4dd0e1, #26c6da);
    }

    .menu-item.pink {
        background: linear-gradient(135deg, #f093fb, #f5576c);
    }

    .menu-item.indigo {
        background: linear-gradient(135deg, #4facfe, #00f2fe);
    }

    .menu-item.red {
        background: linear-gradient(135deg, #fa709a, #fee140);
    }

    .menu-item.emerald {
        background: linear-gradient(135deg, #30cfd0, #330867);
    }

    .menu-item.violet {
        background: linear-gradient(135deg, #a8edea, #fed6e3);
    }

    .menu-item.coral {
        background: linear-gradient(135deg, #ff9a56, #ff6a88);
    }

    .menu-item.sky {
        background: linear-gradient(135deg, #38f9d7, #43e97b);
    }

    .menu-item.rose {
        background: linear-gradient(135deg, #eb3349, #f45c43);
    }

    .menu-item.mint {
        background: linear-gradient(135deg, #11998e, #38ef7d);
    }

    .menu-item.navy {
        background: linear-gradient(135deg, #2c5364, #203a43);
    }

    /* ============ ICON WRAPPER ============ */
    .icon-wrapper {
        width: 60px;
        height: 60px;
        background: rgba(255, 255, 255, 0.25);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 12px;
        transition: all 0.4s ease;
        border: 2px solid rgba(255, 255, 255, 0.3);
    }

    .menu-item:hover .icon-wrapper {
        transform: rotate(360deg) scale(1.1);
        background: rgba(255, 255, 255, 0.35);
    }

    .icon-wrapper i {
        font-size: 2rem;
        color: white;
    }

    .icon-wrapper img {
        width: 40px;
        height: 40px;
        object-fit: contain;
    }

    /* ============ MENU LABEL ============ */
    .menu-label {
        color: white;
        font-weight: 600;
        font-size: 0.85rem;
        margin: 0;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        line-height: 1.3;
    }

    /* ============ HOME BUTTON ============ */
    .home-button-wrapper {
        text-align: center;
        padding: 0 20px;
    }

    .home-button {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 15px 35px;
        background: linear-gradient(135deg, #16a34a, #40916c);
        color: white;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        font-size: 1.1rem;
        transition: all 0.4s ease;
        box-shadow: 0 10px 30px rgba(45, 106, 79, 0.3);
        border: none;
    }

    .home-button:hover {
        background: linear-gradient(135deg, #40916c, #52b788);
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 15px 40px rgba(45, 106, 79, 0.4);
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

    /* Large Desktop */
    @media (min-width: 1400px) {
        .menu-grid {
            grid-template-columns: repeat(6, 1fr);
            gap: 25px;
            max-width: 1400px;
        }
    }

    /* Desktop */
    @media (min-width: 1200px) and (max-width: 1399px) {
        .menu-grid {
            grid-template-columns: repeat(6, 1fr);
            gap: 22px;
        }
    }

    /* Tablet Large */
    @media (min-width: 992px) and (max-width: 1199px) {
        .menu-grid {
            grid-template-columns: repeat(5, 1fr);
            gap: 20px;
        }

        .icon-wrapper {
            width: 55px;
            height: 55px;
        }

        .icon-wrapper i {
            font-size: 1.8rem;
        }
    }

    /* Tablet */
    @media (min-width: 768px) and (max-width: 991px) {
        .menu-section {
            padding: 70px 0 50px;
        }

        .logo-wrapper img {
            width: 80px;
            height: 80px;
        }

        .floating-text {
            font-size: 1.6rem;
        }

        .floating-subtext {
            font-size: 1.1rem;
        }

        .menu-grid {
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
        }

        .menu-item {
            padding: 18px 12px;
        }

        .icon-wrapper {
            width: 50px;
            height: 50px;
        }

        .icon-wrapper i {
            font-size: 1.6rem;
        }

        .menu-label {
            font-size: 0.8rem;
        }
    }

    /* Mobile Large */
    @media (min-width: 577px) and (max-width: 767px) {
        .menu-section {
            padding: 60px 0 40px;
        }

        .logo-wrapper img {
            width: 70px;
            height: 70px;
        }

        .floating-text {
            font-size: 1.4rem;
        }

        .floating-subtext {
            font-size: 0.95rem;
        }

        .menu-grid {
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            padding: 0 15px;
        }

        .menu-item {
            padding: 15px 10px;
            border-radius: 15px;
        }

        .icon-wrapper {
            width: 50px;
            height: 50px;
            margin-bottom: 10px;
        }

        .icon-wrapper i {
            font-size: 1.5rem;
        }

        .menu-label {
            font-size: 0.75rem;
        }
    }

    /* Mobile */
    @media (max-width: 576px) {
        .menu-section {
            padding: 50px 0 30px;
            min-height: auto;
        }

        .logo-container {
            margin-bottom: 30px;
        }

        .logo-wrapper img {
            width: 60px;
            height: 60px;
        }

        .floating-text {
            font-size: 1.2rem;
            margin-bottom: 5px;
        }

        .floating-subtext {
            font-size: 0.85rem;
            margin: 3px 0;
        }

        .menu-grid {
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            max-width: 100%;
            padding: 0 12px;
            margin-bottom: 25px;
        }

        .menu-item {
            padding: 12px 8px;
            border-radius: 12px;
        }

        .icon-wrapper {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            margin-bottom: 8px;
        }

        .icon-wrapper i {
            font-size: 1.3rem;
        }

        .icon-wrapper img {
            width: 30px;
            height: 30px;
        }

        .menu-label {
            font-size: 0.7rem;
            line-height: 1.2;
        }

        .home-button {
            padding: 10px 25px;
            font-size: 0.9rem;
        }

        .home-button i {
            font-size: 0.9rem;
        }
    }

    /* Landscape mode untuk mobile */
    @media (max-width: 900px) and (orientation: landscape) {
        .menu-section {
            padding: 30px 0 20px;
            height: auto;
        }

        .logo-container {
            margin-bottom: 20px;
        }

        .logo-wrapper img {
            width: 50px;
            height: 50px;
        }

        .floating-text {
            font-size: 1rem;
            margin-bottom: 3px;
        }

        .floating-subtext {
            font-size: 0.75rem;
            margin: 2px 0;
        }

        .menu-grid {
            grid-template-columns: repeat(6, 1fr);
            gap: 10px;
            margin-bottom: 15px;
        }

        .menu-item {
            padding: 10px 6px;
        }

        .icon-wrapper {
            width: 40px;
            height: 40px;
            margin-bottom: 6px;
        }

        .icon-wrapper i {
            font-size: 1.2rem;
        }

        .menu-label {
            font-size: 0.65rem;
        }

        .home-button {
            padding: 8px 20px;
            font-size: 0.85rem;
        }
    }

    /* Extra small devices */
    @media (max-width: 375px) {
        .menu-grid {
            gap: 10px;
        }

        .menu-item {
            padding: 10px 6px;
        }

        .icon-wrapper {
            width: 42px;
            height: 42px;
        }

        .icon-wrapper i {
            font-size: 1.2rem;
        }

        .menu-label {
            font-size: 0.68rem;
        }
    }
</style>
<!-- Video Background -->
<div class="video-background">
    <video autoplay muted loop playsinline>
        <source src="https://kuningankab.go.id/asset/flash.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>
</div>

<!-- Video Overlay -->
<div class="video-overlay"></div>

<!-- Pattern Overlay -->
<div class="pattern-overlay"></div>

<!-- MENU SECTION -->
<section class="menu-section">
    <div class="container">

        <!-- Logo -->
        <div class="logo-container" data-aos="zoom-in" data-aos-duration="800">
            <div class="logo-wrapper">
                <img src="{{ asset('img/logo/kuningan.png') }}" alt="Logo Kabupaten Kuningan">
                <div class="header-text">
                    <h2 class="text-white floating-text">WEBSITE RESMI</h2>
                    <h4 class="text-white floating-subtext">DINAS KETAHANAN PANGAN DAN PERTANIAN</h4>
                    <h4 class="text-white floating-subtext">KABUPATEN KUNINGAN</h4>
                </div>
            </div>
        </div>

        <!-- Menu Grid - 18 Systems -->
        <div class="menu-grid">

            {{-- 1. Emonev Kementan --}}
            <a href="https://emonev.pertanian.go.id" target="_blank" class="menu-item purple" data-aos="fade-up"
                data-aos-delay="50">
                <div class="icon-wrapper">
                    <img src="{{ asset('img/logo/emonev.png') }}" alt="Emonev">
                </div>
                <p class="menu-label">Emonev Kementan</p>
            </a>

            {{-- 2. Varietas Lokal --}}
            <a href="https://ap-pvt.pertanian.go.id" target="_blank" class="menu-item cyan" data-aos="fade-up"
                data-aos-delay="100">
                <div class="icon-wrapper">
                    <img src="{{ asset('img/logo/emonev.png') }}"
                        alt="Varietas Lokal">
                </div>
                <p class="menu-label">Varietas Lokal</p>
            </a>

            {{-- 3. Model Pelaporan Online --}}
            <a href="https://mpo.psp.pertanian.go.id" target="_blank" class="menu-item green" data-aos="fade-up"
                data-aos-delay="150">
                <div class="icon-wrapper">
                    <img src="https://www.google.com/s2/favicons?sz=64&domain=mpo.psp.pertanian.go.id"
                        alt="Model Pelaporan Online">
                </div>
                <p class="menu-label">Pelaporan Online</p>
            </a>

            {{-- 4. Omspan TKD --}}
            <a href="https://spanint.kemenkeu.go.id/tkd" target="_blank" class="menu-item blue" data-aos="fade-up"
                data-aos-delay="200">
                <div class="icon-wrapper">
                    <img src="{{ asset('img/logo/Omspan.png') }}" alt="Omspan TKD">

                </div>
                <p class="menu-label">Omspan TKD</p>
            </a>

            {{-- 5. BASTBANPEM --}}
            <a href="https://bastbanpem.pertanian.go.id/login/" target="_blank" class="menu-item orange"
                data-aos="fade-up" data-aos-delay="250">
                <div class="icon-wrapper">
                    <img src="{{ asset('img/logo/emonev.png') }}"
                        alt="BASTBANPEM">

                </div>
                <p class="menu-label">BASTBANPEM</p>
            </a>

            {{-- 6. AKSARA --}}
            <a href="https://pprk.bappenas.go.id/aksara" target="_blank" class="menu-item lime" data-aos="fade-up"
                data-aos-delay="300">
                <div class="icon-wrapper">
                    <img src="{{ asset('img/logo/pprk.png') }}" alt="AKSARA">

                </div>
                <p class="menu-label">AKSARA</p>
            </a>

            {{-- 7. SRIKANDI --}}
            <a href="https://srikandi.arsip.go.id" target="_blank" class="menu-item yellow" data-aos="fade-up"
                data-aos-delay="350">
                <div class="icon-wrapper">
                    <img src="{{ asset('img/logo/srikandi.png') }}" alt="SRIKANDI">

                </div>
                <p class="menu-label">SRIKANDI</p>
            </a>

            {{-- 8. SIJAPATI --}}
            <a href="https://sijapati.kuningankab.go.id" target="_blank" class="menu-item teal" data-aos="fade-up"
                data-aos-delay="400">
                <div class="icon-wrapper">
                    <img src="{{ asset('img/logo/kuningan.png') }}"
                        alt="SIJAPATI">

                </div>
                <p class="menu-label">SIJAPATI</p>
            </a>

            {{-- 9. ASN DIGITAL --}}
            <a href="https://asndigital.bkn.go.id/" target="_blank" class="menu-item pink" data-aos="fade-up"
                data-aos-delay="450">
                <div class="icon-wrapper">
                    <img src="https://www.google.com/s2/favicons?sz=64&domain=asndigital.bkn.go.id" alt="ASN Digital">

                </div>
                <p class="menu-label">ASN DIGITAL</p>
            </a>

            {{-- 10. SIAGA ASN --}}
            <a href="https://siaga-asn.kuningankab.go.id" target="_blank" class="menu-item indigo" data-aos="fade-up"
                data-aos-delay="500">
                <div class="icon-wrapper">
                    <img src="https://www.google.com/s2/favicons?sz=64&domain=siaga-asn.kuningankab.go.id"
                        alt="SIAGA ASN">

                </div>
                <p class="menu-label">SIAGA ASN</p>
            </a>

            {{-- 11. SKPG --}}
            <a href="https://skpg.badanpangan.go.id/" target="_blank" class="menu-item red" data-aos="fade-up"
                data-aos-delay="550">
                <div class="icon-wrapper">
                    <img src="https://www.google.com/s2/favicons?sz=64&domain=skpg.badanpangan.go.id" alt="SKPG">

                </div>
                <p class="menu-label">SKPG</p>
            </a>

            {{-- 12. LKPP --}}
            <a href="https://www.lkpp.go.id/" target="_blank" class="menu-item emerald" data-aos="fade-up"
                data-aos-delay="600">
                <div class="icon-wrapper">
                    <img src="{{ asset('img/logo/lkpp.png') }}" alt="LKPP">

                </div>
                <p class="menu-label">LKPP</p>
            </a>

            {{-- 13. BIMA --}}
            <a href="https://bima.pertanian.go.id" target="_blank" class="menu-item violet" data-aos="fade-up"
                data-aos-delay="650">
                <div class="icon-wrapper">
                    <img src="{{ asset('img/logo/bima.png') }}" alt="BIMA">

                </div>
                <p class="menu-label text-danger">BIMA</p>
            </a>

            {{-- 14. KRISNA --}}
            <a href="https://kuningankab.krisna.systems" target="_blank" class="menu-item coral" data-aos="fade-up"
                data-aos-delay="700">
                <div class="icon-wrapper">
                    <img src="{{ asset('img/logo/krisna.png') }}"
                        alt="KRISNA">

                </div>
                <p class="menu-label">KRISNA</p>
            </a>

            {{-- 15. OSS --}}
            <a href="https://oss.go.id" target="_blank" class="menu-item sky" data-aos="fade-up"
                data-aos-delay="750">
                <div class="icon-wrapper">
                    <img src="{{ asset('img/logo/oss.png') }}" alt="OSS">

                </div>
                <p class="menu-label">OSS</p>
            </a>

            {{-- 16. SADATA --}}
            <a href="https://sadata.kuningankab.go.id" target="_blank" class="menu-item rose" data-aos="fade-up"
                data-aos-delay="800">
                <div class="icon-wrapper">
                    <img src="https://www.google.com/s2/favicons?sz=64&domain=sadata.kuningankab.go.id"
                        alt="SADATA">

                </div>
                <p class="menu-label">SADATA</p>
            </a>

            {{-- 17. SiRUP --}}
            <a href="https://sirup.inaproc.id" target="_blank" class="menu-item mint" data-aos="fade-up"
                data-aos-delay="850">
                <div class="icon-wrapper">
                    <img src="{{ asset('img/logo/sirup.png') }}" alt="SiRUP">

                </div>
                <p class="menu-label">SiRUP</p>
            </a>

            {{-- 18. SIPD --}}
            <a href="https://sipd-ri.kemendagri.go.id" target="_blank" class="menu-item navy" data-aos="fade-up"
                data-aos-delay="900">
                <div class="icon-wrapper">
                    <img src="{{ asset('img/logo/sipd.png') }}"
                        alt="SIPD">

                </div>
                <p class="menu-label">SIPD</p>
            </a>

        </div>

        <!-- Home Button -->
        <div class="home-button-wrapper">
            <button id="btn-buka" class="home-button">
                <i class="bi bi-house-door-fill"></i>
                <span>Halaman Beranda</span>
            </button>
        </div>

    </div>
</section>
