@extends('public.layouts.app')

@section('title', 'Home')

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

        /* 
           KONDISI DEFAULT (SEBELUM TOMBOL BERANDA DITEKAN):
           - Tampilan sambutan (#index2) TAMPIL LANGSUNG sejak awal tanpa flicker.
           - Website utama (#index), Top bar (#top-active), Navbar (#header-active),
             Footer (#footer-active), serta ornamen daun (.leaf-decoration)
             DISEMBUNYIKAN SECARA TOTAL DI LEVEL CSS SEJAK AWAL PARSING.
        */
        body:not(.main-active) #index,
        body:not(.main-active) #header-active,
        body:not(.main-active) #top-active,
        body:not(.main-active) #footer-active,
        body:not(.main-active) .leaf-decoration {
            display: none !important;
        }

        body:not(.main-active) #index2 {
            display: block !important;
        }

        /* 
           KONDISI SETELAH TOMBOL BERANDA DITEKAN (.main-active ditambahkan ke <body>):
           - Sambutan (#index2) disembunyikan.
           - Website utama (#index), Header, Top Bar, dan Footer ditampilkan dengan animasi halus.
        */
        body.main-active #index2 {
            display: none !important;
        }

        body.main-active #index {
            display: block !important;
            animation: fadeInMainContent 0.5s ease-out forwards;
        }

        body.main-active #header-active,
        body.main-active #top-active,
        body.main-active #footer-active {
            display: block !important;
        }

        body.main-active .leaf-decoration {
            display: block !important;
        }

        @keyframes fadeInMainContent {
            from {
                opacity: 0;
                transform: translateY(12px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ============ CURTAIN ANIMATION ============ */
        #curtain {
            position: fixed;
            inset: 0;
            z-index: 999999;
            pointer-events: none;
            display: none;
        }

        .curtain {
            position: absolute;
            top: 0;
            width: 50%;
            height: 100%;
            background: linear-gradient(135deg,
                    #1b4332 0%,
                    #40916c 30%,
                    #16a34a 55%,
                    #52b788 75%,
                    #74c69d 100%);
            opacity: 1;
            transition: transform 0.8s cubic-bezier(.4, 0, .2, 1), opacity 0.8s ease;
        }

        .curtain.left {
            left: 0;
            transform: translateX(0);
        }

        .curtain.right {
            right: 0;
            transform: translateX(0);
        }

        #curtain.active {
            display: block;
        }

        #curtain.open .curtain {
            opacity: 0;
        }

        #curtain.open .left {
            transform: translateX(-100%);
        }

        #curtain.open .right {
            transform: translateX(100%);
        }
    </style>
@endpush

@section('content')
    <!-- Tirai Animasi Pembuka -->
    <div id="curtain">
        <div class="curtain left"></div>
        <div class="curtain right"></div>
    </div>

    <!-- TAMPILAN SAMBUTAN / PORTAL (SELALU DI DEPAN SECARA DEFAULT) -->
    <div id="index2">
        @include('public.home.index2')
    </div>

    <!-- TAMPILAN WEBSITE UTAMA (TERKUNCI / DISEMBUNYIKAN SEBELUM TOMBOL DITEKAN) -->
    <div id="index" style="display: none;">
        @include('public.home.index')
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const btnBuka = document.getElementById('btn-buka');
            const curtain = document.getElementById('curtain');
            const index = document.getElementById('index');
            const index2 = document.getElementById('index2');

            // Fungsi untuk membuka website utama dengan efek tirai
            function bukaWebsiteUtama() {
                if (!curtain) {
                    document.body.classList.add('main-active');
                    if (index) index.style.display = 'block';
                    if (index2) index2.style.display = 'none';
                    return;
                }

                // Munculkan tirai
                curtain.classList.remove('open');
                curtain.classList.add('active');

                // Buka tirai ke kiri dan kanan
                setTimeout(() => {
                    curtain.classList.add('open');
                }, 300);

                // Ganti tampilan saat tirai mulai terbuka
                setTimeout(() => {
                    document.body.classList.add('main-active');
                    if (index2) index2.style.display = 'none';
                    if (index) index.style.display = 'block';

                    // Scroll ke atas website utama
                    window.scrollTo({ top: 0, behavior: 'instant' });

                    // Refresh animasi AOS dan layout carousel/swiper/peta
                    if (typeof AOS !== 'undefined') {
                        AOS.refresh();
                    }
                    window.dispatchEvent(new Event('resize'));
                }, 450);

                // Sembunyikan tirai setelah animasi selesai
                setTimeout(() => {
                    curtain.classList.remove('active', 'open');
                }, 1200);
            }

            // Pasang event listener ke tombol Halaman Beranda
            if (btnBuka) {
                btnBuka.addEventListener('click', (e) => {
                    e.preventDefault();
                    bukaWebsiteUtama();
                });
            }

            // Jika pengguna sudah berada di website utama dan mengklik menu Home di navbar,
            // scroll halus ke atas halaman utama tanpa reload/kembali ke sambutan
            const homeNavLink = document.querySelector('.navbar-nav .nav-link[href="{{ url('/') }}"]');
            if (homeNavLink) {
                homeNavLink.addEventListener('click', (e) => {
                    if (document.body.classList.contains('main-active')) {
                        e.preventDefault();
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    }
                });
            }

            // Jika pengguna mengklik tombol Portal di navbar, kembali ke tampilan sambutan dengan efek tirai
            const btnKembaliPortal = document.getElementById('btn-kembali-portal');
            if (btnKembaliPortal) {
                btnKembaliPortal.addEventListener('click', (e) => {
                    if (document.body.classList.contains('main-active')) {
                        e.preventDefault();
                        if (!curtain) {
                            document.body.classList.remove('main-active');
                            if (index) index.style.display = 'none';
                            if (index2) index2.style.display = 'block';
                            return;
                        }

                        curtain.classList.remove('open');
                        curtain.classList.add('active');

                        setTimeout(() => {
                            curtain.classList.add('open');
                        }, 300);

                        setTimeout(() => {
                            document.body.classList.remove('main-active');
                            if (index) index.style.display = 'none';
                            if (index2) index2.style.display = 'block';
                            window.scrollTo({ top: 0, behavior: 'instant' });
                        }, 450);

                        setTimeout(() => {
                            curtain.classList.remove('active', 'open');
                        }, 1200);
                    }
                });
            }
        });
    </script>
@endpush
