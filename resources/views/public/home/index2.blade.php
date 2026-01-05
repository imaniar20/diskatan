<style>
    /* ============ CONTENT SECTION ============ */
.menu-section {
    min-height: 100vh;
    position: relative;
    /* padding: 80px 0 60px; */
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
    /* margin-top: -100px; */
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
    color: white;
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
    color: white;
    text-shadow:
        0 2px 3px rgba(0, 0, 0, 0.3),
        0 4px 7px rgba(0, 0, 0, 0.2);
    margin: 5px 0;
    letter-spacing: 0.5px;
    font-weight: 500;
}

/* ============ MENU GRID - OPTIMIZED FOR 22 ICONS ============ */
.menu-grid {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 20px;
    max-width: 1200px;
    margin: 0 auto 40px;
    padding: 0 10px;
    justify-items: center;
    align-items: start;
}

.menu-item {
    position: relative;
    border-radius: 18px;
    padding: 5px 15px;
    text-align: center;
    cursor: pointer;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    text-decoration: none;
    overflow: hidden;
    width: 100%;
    max-width: 180px;
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
    /* box-shadow: 0 15px 50px rgba(0, 0, 0, 0.15); */
}

.menu-item:hover::before {
    opacity: 0.1;
}

/* ============ ICON WRAPPER ============ */
.icon-wrapper {
    width: 60px;
    height: 60px;
    /* background: rgba(255, 255, 255, 0.25); */
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
    transform: rotate(360deg) scale(1);
    /* background: rgba(255, 255, 255, 0.35); */
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
    color: rgb(255, 255, 255);
    font-weight: 600;
    font-size: 0.85rem;
    margin: 0;
    text-shadow: 0 2px 10px rgba(255, 255, 255, 0.1);
    line-height: 1.3;
}

/* ============ HOME BUTTON ============ */
.home-button-wrapper {
    text-align: center;
    margin-top: 30px; 
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
    cursor: pointer;
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

/* ============ RESPONSIVE DESIGN ============ */

/* Extra Large Desktop (1600px+) - 6 columns */
@media (min-width: 1600px) {
    .menu-grid {
        grid-template-columns: repeat(6, 1fr);
        gap: 28px;
        max-width: 1400px;
    }
    
    .menu-item {
        max-width: 200px;
    }
}

/* Large Desktop (1400px - 1599px) - 6 columns */
@media (min-width: 1400px) and (max-width: 1599px) {
    .menu-grid {
        grid-template-columns: repeat(6, 1fr);
        gap: 25px;
        max-width: 1350px;
    }
}

/* Desktop (1200px - 1399px) - 6 columns */
@media (min-width: 1200px) and (max-width: 1399px) {
    .menu-grid {
        grid-template-columns: repeat(6, 1fr);
        gap: 22px;
        max-width: 1200px;
    }
}

/* Medium Desktop (992px - 1199px) - 5 columns */
@media (min-width: 992px) and (max-width: 1199px) {
    .menu-grid {
        grid-template-columns: repeat(5, 1fr);
        gap: 20px;
        max-width: 1000px;
    }

    .icon-wrapper {
        width: 55px;
        height: 55px;
    }

    .icon-wrapper i {
        font-size: 1.8rem;
    }
    
    .icon-wrapper img {
        width: 35px;
        height: 35px;
    }
}

/* Tablet Large (768px - 991px) - 4 columns */
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
        max-width: 750px;
    }

    .menu-item {
        padding: 18px 12px;
        max-width: 160px;
    }

    .icon-wrapper {
        width: 52px;
        height: 52px;
    }

    .icon-wrapper i {
        font-size: 1.7rem;
    }
    
    .icon-wrapper img {
        width: 35px;
        height: 35px;
    }

    .menu-label {
        font-size: 0.8rem;
    }
}

/* Tablet Small (577px - 767px) - 4 columns */
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
        grid-template-columns: repeat(4, 1fr);
        gap: 15px;
        padding: 0 15px;
        max-width: 600px;
    }

    .menu-item {
        padding: 15px 10px;
        border-radius: 15px;
        max-width: 140px;
    }

    .icon-wrapper {
        width: 50px;
        height: 50px;
        margin-bottom: 10px;
    }

    .icon-wrapper i {
        font-size: 1.5rem;
    }
    
    .icon-wrapper img {
        width: 32px;
        height: 32px;
    }

    .menu-label {
        font-size: 0.75rem;
    }
    
    .home-button {
        padding: 12px 28px;
        font-size: 1rem;
    }
}

/* Mobile Large (481px - 576px) - 4 columns */
@media (min-width: 481px) and (max-width: 576px) {
    .menu-section {
        padding: 50px 0 35px;
    }

    .logo-container {
        margin-bottom: 35px;
    }

    .logo-wrapper img {
        width: 65px;
        height: 65px;
    }

    .floating-text {
        font-size: 1.3rem;
        margin-bottom: 5px;
    }

    .floating-subtext {
        font-size: 0.9rem;
        margin: 3px 0;
    }

    .menu-grid {
        grid-template-columns: repeat(4, 1fr);
        gap: 12px;
        max-width: 100%;
        padding: 0 12px;
        margin-bottom: 30px;
    }

    .menu-item {
        padding: 12px 8px;
        border-radius: 12px;
        max-width: 120px;
    }

    .icon-wrapper {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        margin-bottom: 8px;
    }

    .icon-wrapper i {
        font-size: 1.4rem;
    }

    .icon-wrapper img {
        width: 32px;
        height: 32px;
    }

    .menu-label {
        font-size: 0.72rem;
        line-height: 1.2;
    }

    .home-button {
        padding: 11px 26px;
        font-size: 0.95rem;
    }
}

/* Mobile Medium (376px - 480px) - 3 columns */
@media (min-width: 376px) and (max-width: 480px) {
    .menu-section {
        padding: 45px 0 30px;
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
        max-width: 110px;
    }

    .icon-wrapper {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        margin-bottom: 8px;
    }

    .icon-wrapper i {
        font-size: 1.35rem;
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

/* Mobile Small (320px - 375px) - 3 columns */
@media (min-width: 320px) and (max-width: 375px) {
    .menu-section {
        padding: 40px 0 25px;
    }

    .logo-container {
        margin-bottom: 25px;
    }

    .logo-wrapper img {
        width: 55px;
        height: 55px;
    }

    .floating-text {
        font-size: 1.1rem;
        margin-bottom: 4px;
    }

    .floating-subtext {
        font-size: 0.8rem;
        margin: 2px 0;
    }

    .menu-grid {
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
        padding: 0 10px;
        margin-bottom: 20px;
    }

    .menu-item {
        padding: 10px 6px;
        border-radius: 10px;
        max-width: 100px;
    }

    .icon-wrapper {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        margin-bottom: 6px;
    }

    .icon-wrapper i {
        font-size: 1.2rem;
    }

    .icon-wrapper img {
        width: 28px;
        height: 28px;
    }

    .menu-label {
        font-size: 0.65rem;
        line-height: 1.15;
    }

    .home-button {
        padding: 9px 22px;
        font-size: 0.85rem;
    }

    .home-button i {
        font-size: 0.85rem;
    }
}

/* Extra Small Mobile (<320px) - 3 columns */
@media (max-width: 319px) {
    .menu-section {
        padding: 35px 0 20px;
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
        grid-template-columns: repeat(3, 1fr);
        gap: 8px;
        padding: 0 8px;
        margin-bottom: 18px;
    }

    .menu-item {
        padding: 8px 5px;
        border-radius: 10px;
    }

    .icon-wrapper {
        width: 38px;
        height: 38px;
        border-radius: 8px;
        margin-bottom: 5px;
    }

    .icon-wrapper i {
        font-size: 1.1rem;
    }

    .icon-wrapper img {
        width: 26px;
        height: 26px;
    }

    .menu-label {
        font-size: 0.6rem;
        line-height: 1.1;
    }

    .home-button {
        padding: 8px 20px;
        font-size: 0.8rem;
    }

    .home-button i {
        font-size: 0.8rem;
    }
}

/* Landscape Mode untuk Mobile (max-width: 900px) */
@media (max-width: 900px) and (orientation: landscape) {
    .menu-section {
        padding: 25px 0 20px;
        height: auto;
        min-height: 100vh;
    }

    .logo-container {
        margin-bottom: 18px;
    }

    .logo-wrapper img {
        width: 45px;
        height: 45px;
    }

    .floating-text {
        font-size: 0.95rem;
        margin-bottom: 3px;
    }

    .floating-subtext {
        font-size: 0.7rem;
        margin: 2px 0;
    }

    .menu-grid {
        grid-template-columns: repeat(6, 1fr);
        gap: 10px;
        margin-bottom: 15px;
        max-width: 95%;
    }

    .menu-item {
        padding: 10px 6px;
        max-width: 100%;
    }

    .icon-wrapper {
        width: 38px;
        height: 38px;
        margin-bottom: 5px;
    }

    .icon-wrapper i {
        font-size: 1.15rem;
    }
    
    .icon-wrapper img {
        width: 26px;
        height: 26px;
    }

    .menu-label {
        font-size: 0.62rem;
        line-height: 1.1;
    }

    .home-button {
        padding: 8px 20px;
        font-size: 0.82rem;
    }
    
    .home-button i {
        font-size: 0.82rem;
    }
}

/* Very Short Landscape (height < 500px) */
@media (max-height: 500px) and (orientation: landscape) {
    .menu-section {
        padding: 15px 0 15px;
    }

    .logo-container {
        margin-bottom: 12px;
    }

    .logo-wrapper img {
        width: 40px;
        height: 40px;
    }

    .floating-text {
        font-size: 0.85rem;
        margin-bottom: 2px;
    }

    .floating-subtext {
        font-size: 0.65rem;
        margin: 1px 0;
    }

    .menu-grid {
        grid-template-columns: repeat(8, 1fr);
        gap: 8px;
        margin-bottom: 12px;
    }

    .menu-item {
        padding: 8px 4px;
    }

    .icon-wrapper {
        width: 35px;
        height: 35px;
        margin-bottom: 4px;
    }

    .icon-wrapper i {
        font-size: 1rem;
    }
    
    .icon-wrapper img {
        width: 24px;
        height: 24px;
    }

    .menu-label {
        font-size: 0.58rem;
    }

    .home-button {
        padding: 6px 18px;
        font-size: 0.75rem;
    }
}
</style>
<!-- Video Background -->
<div class="video-background">
    <video autoplay muted loop playsinline>
        <source src="https://siwawan.kuningankab.go.id/assets/other/video.mp4" type="video/mp4">
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

            <a href="https://kuningan.go.id" target="_blank" class="menu-item" data-aos="fade-up"
                data-aos-delay="900">
                <div class="icon-wrapper">
                    <img src="{{ asset('img/logo/kuningan.png') }}"
                        alt="KUNINGAN">

                </div>
                <p class="menu-label">PEMKABKUININGAN</p>
            </a>
            
            {{-- 1. Emonev Kementan --}}
            <a href="https://emonev.pertanian.go.id" target="_blank" class="menu-item" data-aos="fade-up"
                data-aos-delay="50">
                <div class="icon-wrapper">
                    <img src="{{ asset('img/logo/emonev.png') }}" alt="Emonev">
                </div>
                <p class="menu-label">Emonev Kementan</p>
            </a>

            {{-- 2. Varietas Lokal --}}
            <a href="https://ap-pvt.pertanian.go.id" target="_blank" class="menu-item" data-aos="fade-up"
                data-aos-delay="100">
                <div class="icon-wrapper">
                    <img src="{{ asset('img/logo/emonev.png') }}"
                        alt="Varietas Lokal">
                </div>
                <p class="menu-label">Varietas Lokal</p>
            </a>

            {{-- 3. Model Pelaporan Online --}}
            <a href="https://simluh.pertanian.go.id/" target="_blank" class="menu-item" data-aos="fade-up"
                data-aos-delay="600">
                <div class="icon-wrapper">
                    <img src="{{ asset('img/logo/emonev.png') }}" alt="LKPP">

                </div>
                <p class="menu-label">SIMLUHTAN</p>
            </a>

            {{-- 5. BASTBANPEM --}}
            <a href="https://bastbanpem.pertanian.go.id/login/" target="_blank" class="menu-item"
                data-aos="fade-up" data-aos-delay="250">
                <div class="icon-wrapper">
                    <img src="{{ asset('img/logo/emonev.png') }}"
                        alt="BASTBANPEM">

                </div>
                <p class="menu-label">BASTBANPEM</p>
            </a>

            {{-- 6. AKSARA --}}
            <a href="https://pupukbersubsidi.pertanian.go.id/" target="_blank" class="menu-item" data-aos="fade-up"
                data-aos-delay="300">
                <div class="icon-wrapper">
                    <img src="{{ asset('img/logo/emonev.png') }}" alt="AKSARA">

                </div>
                <p class="menu-label">IPUBERS</p>
            </a>

            {{-- 16. SADATA --}}
            <a href="https://sadata.kuningankab.go.id" target="_blank" class="menu-item" data-aos="fade-up"
                data-aos-delay="800">
                <div class="icon-wrapper">
                    <img src="https://www.google.com/s2/favicons?sz=64&domain=sadata.kuningankab.go.id"
                        alt="SADATA">

                </div>
                <p class="menu-label">SADATA</p>
            </a>

            {{-- 8. SIJAPATI --}}
            <a href="https://sijapati.kuningankab.go.id" target="_blank" class="menu-item" data-aos="fade-up"
                data-aos-delay="400">
                <div class="icon-wrapper">
                    <img src="{{ asset('img/logo/kuningan.png') }}"
                        alt="SIJAPATI">

                </div>
                <p class="menu-label">SIJAPATI</p>
            </a>

            {{-- 4. Omspan TKD --}}
            <a href="https://spanint.kemenkeu.go.id/tkd" target="_blank" class="menu-item" data-aos="fade-up"
                data-aos-delay="200">
                <div class="icon-wrapper">
                    <img src="{{ asset('img/logo/Omspan.png') }}" alt="Omspan TKD">

                </div>
                <p class="menu-label">Omspan TKD</p>
            </a>

            <a href="https://mpo.psp.pertanian.go.id" target="_blank" class="menu-item" data-aos="fade-up"
                data-aos-delay="150">
                <div class="icon-wrapper">
                    <img src="https://www.google.com/s2/favicons?sz=64&domain=mpo.psp.pertanian.go.id"
                        alt="Model Pelaporan Online">
                </div>
                <p class="menu-label">Pelaporan Online</p>
            </a>

            {{-- 7. SRIKANDI --}}
            <a href="https://srikandi.arsip.go.id" target="_blank" class="menu-item" data-aos="fade-up"
                data-aos-delay="350">
                <div class="icon-wrapper">
                    <img src="{{ asset('img/logo/srikandi.png') }}" alt="SRIKANDI">

                </div>
                <p class="menu-label">SRIKANDI</p>
            </a>


            {{-- 9. ASN DIGITAL --}}
            <a href="https://asndigital.bkn.go.id/" target="_blank" class="menu-item" data-aos="fade-up"
                data-aos-delay="450">
                <div class="icon-wrapper">
                    <img src="https://www.google.com/s2/favicons?sz=64&domain=asndigital.bkn.go.id" alt="ASN Digital">

                </div>
                <p class="menu-label">ASN DIGITAL</p>
            </a>

            {{-- 10. SIAGA ASN --}}
            <a href="https://siaga-asn.kuningankab.go.id" target="_blank" class="menu-item" data-aos="fade-up"
                data-aos-delay="500">
                <div class="icon-wrapper">
                    <img src="https://www.google.com/s2/favicons?sz=64&domain=siaga-asn.kuningankab.go.id"
                        alt="SIAGA ASN">

                </div>
                <p class="menu-label">SIAGA ASN</p>
            </a>

            {{-- 11. SKPG --}}
            <a href="https://skpg.badanpangan.go.id/" target="_blank" class="menu-item" data-aos="fade-up"
                data-aos-delay="550">
                <div class="icon-wrapper">
                    <img src="https://www.google.com/s2/favicons?sz=64&domain=skpg.badanpangan.go.id" alt="SKPG">

                </div>
                <p class="menu-label">SKPG</p>
            </a>

            {{-- 12. LKPP --}}
            <a href="https://simluh.pertanian.go.id/" target="_blank" class="menu-item" data-aos="fade-up"
                data-aos-delay="600">
                <div class="icon-wrapper">
                    <img src="{{ asset('img/logo/emonev.png') }}" alt="LKPP">

                </div>
                <p class="menu-label">SIMLUHTAN</p>
            </a>

            {{-- 13. BIMA --}}
            <a href="https://bima.pertanian.go.id" target="_blank" class="menu-item" data-aos="fade-up"
                data-aos-delay="650">
                <div class="icon-wrapper">
                    <img src="{{ asset('img/logo/bima.png') }}" alt="BIMA">

                </div>
                <p class="menu-label">BIMA</p>
            </a>

            {{-- 14. KRISNA --}}
            <a href="https://kuningankab.krisna.systems" target="_blank" class="menu-item" data-aos="fade-up"
                data-aos-delay="700">
                <div class="icon-wrapper">
                    <img src="{{ asset('img/logo/krisna.png') }}"
                        alt="KRISNA">

                </div>
                <p class="menu-label">KRISNA</p>
            </a>

            {{-- 15. OSS --}}
            <a href="https://oss.go.id" target="_blank" class="menu-item" data-aos="fade-up"
                data-aos-delay="750">
                <div class="icon-wrapper">
                    <img src="{{ asset('img/logo/oss.png') }}" alt="OSS">

                </div>
                <p class="menu-label">OSS</p>
            </a>

            {{-- 17. SiRUP --}}
            <a href="https://sirup.inaproc.id" target="_blank" class="menu-item" data-aos="fade-up"
                data-aos-delay="850">
                <div class="icon-wrapper">
                    <img src="{{ asset('img/logo/sirup.png') }}" alt="SiRUP">

                </div>
                <p class="menu-label">SiRUP</p>
            </a>

            {{-- 18. SIPD --}}
            <a href="https://sipd-ri.kemendagri.go.id" target="_blank" class="menu-item" data-aos="fade-up"
                data-aos-delay="900">
                <div class="icon-wrapper">
                    <img src="{{ asset('img/logo/sipd.png') }}"
                        alt="SIPD">

                </div>
                <p class="menu-label">SIPD</p>
            </a>

            {{-- <a href="https://sipd-ri.kemendagri.go.id" target="_blank" class="menu-item" data-aos="fade-up"
                data-aos-delay="900">
                <div class="icon-wrapper">
                    <img src="{{ asset('img/logo/sipd.png') }}"
                        alt="SIPD">

                </div>
                <p class="menu-label">EPUSLUH</p>
            </a>

            <a href="https://sipd-ri.kemendagri.go.id" target="_blank" class="menu-item" data-aos="fade-up"
                data-aos-delay="900">
                <div class="icon-wrapper">
                    <img src="{{ asset('img/logo/sipd.png') }}"
                        alt="SIPD">

                </div>
                <p class="menu-label">SIPDRI</p>
            </a> --}}

            <a href="https://xstar.bphmigas.go.id/login" target="_blank" class="menu-item" data-aos="fade-up"
                data-aos-delay="900">
                <div class="icon-wrapper">
                    <img src="{{ asset('img/logo/bbm.png') }}"
                        alt="SIPD">

                </div>
                <p class="menu-label">BBM</p>
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
