

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
        }

        /* ============ CONTENT SECTION ============ */
        .menu-section {
            min-height: 100vh;
            position: relative;
            padding: 60px 0;
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
            margin-bottom: 40px;
            padding: 0 15px;
        }

        .logo-wrapper {
            display: inline-block;
        }

        .logo-wrapper img {
            width: 120px;
            height: 120px;
            object-fit: contain;
            filter: drop-shadow(0 10px 30px rgba(45, 106, 79, 0.2));
        }

        .header-text {
            text-align: center;
            padding: 15px 10px;
        }

        .floating-text {
            font-size: 2.8rem;
            font-weight: 700;
            text-shadow:
                0 1px 0 #ccc,
                0 2px 0 #c9c9c9,
                0 3px 0 #bbb,
                0 4px 0 #b9b9b9,
                0 5px 0 #aaa,
                0 6px 1px rgba(0, 0, 0, .1),
                0 0 5px rgba(0, 0, 0, .1),
                0 1px 3px rgba(0, 0, 0, .3),
                0 3px 5px rgba(0, 0, 0, .2),
                0 5px 10px rgba(0, 0, 0, .25);
            letter-spacing: 0.5px;
            margin-bottom: 10px;
            transition: all 0.3s ease;
            color: white;
        }

        .floating-subtext {
            font-size: 1.9rem;
            text-shadow:
                0 2px 3px rgba(0, 0, 0, 0.3),
                0 4px 7px rgba(0, 0, 0, 0.2);
            margin: 5px 0;
            letter-spacing: 0.3px;
            font-weight: 500;
            color: white;
        }

        /* ============ MENU GRID ============ */
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 25px;
            max-width: 1000px;
            margin: 0 auto 40px;
            padding: 0 20px;
        }

        .menu-item {
            position: relative;
            background: white;
            border-radius: 20px;
            padding: 30px 15px;
            text-align: center;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            text-decoration: none;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .menu-item:hover {
            transform: translateY(-10px) scale(1.03);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        }

        /* ============ ICON COLORS ============ */
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

        /* ============ ICON WRAPPER ============ */
        .icon-wrapper {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            transition: all 0.4s ease;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .menu-item:hover .icon-wrapper {
            transform: rotate(360deg) scale(1.1);
            background: rgba(255, 255, 255, 0.35);
        }

        .icon-wrapper i {
            font-size: 2.5rem;
            color: white;
        }

        /* ============ MENU LABEL ============ */
        .menu-label {
            color: white;
            font-weight: 700;
            font-size: 1rem;
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

        /* ============ PATTERN OVERLAY ============ */
        .pattern-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            pointer-events: none;
            z-index: 0;
            opacity: 0.03;
            background-image:
                repeating-linear-gradient(45deg, transparent, transparent 35px, #2d6a4f 35px, #2d6a4f 36px),
                repeating-linear-gradient(-45deg, transparent, transparent 35px, #2d6a4f 35px, #2d6a4f 36px);
        }

        /* ============ RESPONSIVE - TABLET ============ */
        @media (max-width: 1024px) {
            .menu-grid {
                grid-template-columns: repeat(3, 1fr);
                gap: 20px;
            }

            .floating-text {
                font-size: 1.6rem;
            }

            .floating-subtext {
                font-size: 1rem;
            }
        }

        /* ============ RESPONSIVE - MOBILE LANDSCAPE ============ */
        @media (max-width: 768px) {
            .menu-section {
                padding: 40px 0;
            }

            .logo-wrapper img {
                width: 90px;
                height: 90px;
            }

            .floating-text {
                font-size: 1.3rem;
                letter-spacing: 0.3px;
            }

            .floating-subtext {
                font-size: 0.9rem;
            }

            .menu-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 15px;
                padding: 0 15px;
            }

            .menu-item {
                padding: 25px 12px;
                border-radius: 15px;
            }

            .icon-wrapper {
                width: 65px;
                height: 65px;
                margin-bottom: 12px;
            }

            .icon-wrapper i {
                font-size: 2rem;
            }

            .menu-label {
                font-size: 0.85rem;
            }

            .home-button {
                padding: 12px 28px;
                font-size: 1rem;
            }

            .home-button i {
                font-size: 1rem !important;
            }
        }

        /* ============ RESPONSIVE - SMALL MOBILE ============ */
        @media (max-width: 480px) {
            .menu-section {
                padding: 30px 0;
            }

            .logo-container {
                margin-bottom: 25px;
            }

            .logo-wrapper img {
                width: 70px;
                height: 70px;
            }

            .header-text {
                padding: 10px 5px;
            }

            .floating-text {
                font-size: 1.1rem;
                margin-bottom: 8px;
            }

            .floating-subtext {
                font-size: 0.75rem;
                margin: 3px 0;
            }

            .menu-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
                padding: 0 12px;
                margin-bottom: 30px;
            }

            .menu-item {
                padding: 20px 10px;
                border-radius: 12px;
            }

            .icon-wrapper {
                width: 55px;
                height: 55px;
                margin-bottom: 10px;
                border-radius: 12px;
            }

            .icon-wrapper i {
                font-size: 1.6rem;
            }

            .menu-label {
                font-size: 0.75rem;
                line-height: 1.2;
            }

            .home-button-wrapper {
                padding: 0 15px;
            }

            .home-button {
                padding: 10px 24px;
                font-size: 0.9rem;
                gap: 8px;
            }

            .home-button span {
                font-size: 0.9rem;
            }

            .home-button i {
                font-size: 0.9rem !important;
            }
        }

        /* ============ RESPONSIVE - EXTRA SMALL ============ */
        @media (max-width: 360px) {
            .floating-text {
                font-size: 1rem;
            }

            .floating-subtext {
                font-size: 0.7rem;
            }

            .menu-grid {
                gap: 10px;
                padding: 0 10px;
            }

            .menu-item {
                padding: 18px 8px;
            }

            .icon-wrapper {
                width: 50px;
                height: 50px;
            }

            .icon-wrapper i {
                font-size: 1.4rem;
            }

            .menu-label {
                font-size: 0.7rem;
            }

            .home-button {
                padding: 10px 20px;
                font-size: 0.85rem;
            }
        }

        /* ============ LANDSCAPE ORIENTATION ============ */
        @media (max-height: 600px) and (orientation: landscape) {
            .menu-section {
                padding: 20px 0;
                min-height: auto;
            }

            .logo-container {
                margin-bottom: 20px;
            }

            .logo-wrapper img {
                width: 60px;
                height: 60px;
            }

            .header-text {
                padding: 8px;
            }

            .floating-text {
                font-size: 1rem;
                margin-bottom: 5px;
            }

            .floating-subtext {
                font-size: 0.75rem;
                margin: 2px 0;
            }

            .menu-grid {
                grid-template-columns: repeat(4, 1fr);
                gap: 12px;
                margin-bottom: 20px;
            }

            .menu-item {
                padding: 15px 10px;
            }

            .icon-wrapper {
                width: 50px;
                height: 50px;
                margin-bottom: 8px;
            }

            .icon-wrapper i {
                font-size: 1.5rem;
            }

            .menu-label {
                font-size: 0.7rem;
            }

            .home-button {
                padding: 8px 20px;
                font-size: 0.85rem;
            }
        }
    </style>

    <!-- Video Background -->
    <div class="video-background">
        <video autoplay muted loop playsinline>
            <source src="{{ asset('videos/flash.mp4') }}" type="video/mp4">
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
            <div class="logo-container">
                <div class="logo-wrapper">
                    <img src="{{ asset('img/logo/kuningan.png') }}" alt="Logo Kabupaten Kuningan">
                    <div class="header-text">
                        <h2 class="floating-text">WEBSITE RESMI</h2>
                        <h4 class="floating-subtext">DINAS KETAHANAN PANGAN DAN PERTANIAN</h4>
                        <h4 class="floating-subtext">KABUPATEN KUNINGAN</h4>
                    </div>
                </div>
            </div>

            <!-- Menu Grid -->
            <div class="menu-grid">

                <!-- Menu Item 1 -->
                <a href="https://kuningankab.go.id/" target="_blank" class="menu-item purple">
                    <div class="icon-wrapper">
                        <img src="{{ asset('img/logo/kuningan.png') }}" style="height: 60px;" alt="">
                    </div>
                    <p class="menu-label">Kabupaten Kuningan</p>
                </a>

                <!-- Menu Item 2 -->
                <a href="https://emonev.pertanian.go.id/" target="_blank" class="menu-item cyan">
                    <div class="icon-wrapper">
                        <img src="{{ asset('img/logo/emonev.png') }}" style="height: 60px;" alt="">
                    </div>
                    <p class="menu-label">Emonev Kementan</p>
                </a>

                <!-- Menu Item 7 -->
                <a href="https://siwawan.kuningankab.go.id/" target="_blank" class="menu-item yellow">
                    <div class="icon-wrapper">
                        <img src="{{ asset('img/logo/kuningan.png') }}" style="height: 60px;" alt="">
                    </div>
                    <p class="menu-label">SI-Wawan</p>
                </a>

                <!-- Menu Item 8 -->
                <a href="https://siwawan.kuningankab.go.id/dashboard.php" target="_blank" class="menu-item teal">
                    <div class="icon-wrapper">
                        <img src="{{ asset('img/logo/sipraja.png') }}" style="height: 60px;" alt="">
                    </div>
                    <p class="menu-label">SIPRAJA</p>
                </a>

                <!-- Menu Item 3 -->
                <a href="https://mpo.psp.pertanian.go.id/" target="_blank" class="menu-item green">
                    <div class="icon-wrapper">
                        <img src="{{ asset('img/logo/mpo.png') }}" style="width: 60px;" alt="">
                    </div>
                    <p class="menu-label">Model Pelaporan Online</p>
                </a>

                <!-- Menu Item 4 -->
                <a href="https://pprk.bappenas.go.id/" target="_blank" class="menu-item blue">
                    <div class="icon-wrapper">
                        <img src="{{ asset('img/logo/pprk.png') }}" style="height: 60px;" alt="">
                    </div>
                    <p class="menu-label">Aksara</p>
                </a>

                <!-- Menu Item 5 -->
                <a href="https://www.lkpp.go.id/" target="_blank" class="menu-item orange">
                    <div class="icon-wrapper">
                        <img src="{{ asset('img/logo/lkpp.png') }}" style="width: 60px;" alt="">
                    </div>
                    <p class="menu-label">LKPP</p>
                </a>

                <!-- Menu Item 6 -->
                <a href="https://opendata.kuningankab.go.id/" target="_blank" class="menu-item lime">
                    <div class="icon-wrapper">
                        <img src="{{ asset('img/logo/opendata.png') }}" style="height: 60px;" alt="">
                    </div>
                    <p class="menu-label">Open Data Kuningan</p>
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

