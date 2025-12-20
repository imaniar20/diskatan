<!-- Custom CSS -->
<style>
    :root {
        --primary-color: #2e7d32;
        --primary-dark: #1b5e20;
        --primary-light: #4caf50;
        --secondary-color: #388e3c;
        --light-green: #e8f5e9;
        --bg-white: #ffffff;
        --bg-soft-green: #f1f8f3;
        --bg-soft-gray: #f6f8f7;
    }

    body {
        font-family: 'Inter', sans-serif;
        background-color: #f7faf7;
    }

    .fade-up {
        opacity: 0;
        transform: translateY(40px);
        transition: all .8s ease;
    }

    .fade-up.show {
        opacity: 1;
        transform: translateY(0);
    }

    .navbar {
        background: rgba(255, 255, 255, 1);
        transition: all 0.4s ease;
        z-index: 999;
        padding: 18px 0;
    }

    .navbar-scrolled {
        background: rgba(46, 125, 50, 0.85);
        backdrop-filter: blur(8px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, .2);
        padding: 10px 0;
    }

    .navbar .nav-link,
    .navbar .navbar-brand {
        color: #2e7d32 !important;
    }

    .navbar-scrolled .nav-link,
    .navbar-scrolled .navbar-brand {
        color: #fff !important;
    }

    .navbar-brand {
        font-weight: 700;
        color: white !important;
        transition: opacity .3s ease;
    }

    .navbar-brand:hover {
        opacity: .85;
    }

    .dropdown-menu {
        border: none;
        border-radius: 14px;
        padding: 10px 0;
        box-shadow: 0 20px 40px rgba(0, 0, 0, .15);
    }

    .dropdown-item {
        padding: 10px 20px;
        font-weight: 500;
        transition: background .2s ease;
    }

    .dropdown-item:hover {
        background: #e8f5e9;
        color: #1b5e20;
    }

    @media (min-width: 992px) {
        .navbar .dropdown:hover .dropdown-menu {
            display: block;
            margin-top: 8px;
        }
    }

    .navbar-scrolled .dropdown-menu {
        background: rgba(255, 255, 255, .95);
        backdrop-filter: blur(6px);
    }

    .dropdown-toggle::after {
        margin-left: 6px;
        vertical-align: middle;
    }

    .dropdown-toggle::after {
        display: none;
    }

    /* navbar belum scroll (putih) */
    .navbar:not(.navbar-scrolled) .dropdown-icon {
        color: #2e7d32;
    }

    /* navbar sudah scroll (hijau) */
    .navbar-scrolled .dropdown-icon {
        color: #fff;
    }

    /* animasi rotate */
    .dropdown.show .dropdown-icon {
        transform: rotate(180deg);
    }

    .dropdown-icon {
        transition: transform .3s ease, color .3s ease;
        font-size: .75rem;
    }

    .nav-link:hover .dropdown-icon {
        opacity: .8;
    }

    .nav-link {
        color: rgba(255, 255, 255, .9) !important;
        font-weight: 500;
        transition: color 0.3s;
    }

    .nav-link.active {
        border-bottom: 2px solid #a5d6a7;
    }

    .nav-link:hover {
        color: white !important;
    }

    .banner {
        background: linear-gradient(rgba(46, 125, 50, 0.9), rgba(46, 125, 50, 0.7));
        color: white;
        padding: 80px 0;
    }

    .section {
        position: relative;
    }

    .section::before {
        content: "";
        position: absolute;
        top: 0;
        left: 50%;
        width: 60%;
        height: 1px;
        background: rgba(0, 0, 0, .5);
        transform: translateX(-50%);
    }

    .bg-soft-green {
        background-color: var(--bg-soft-green);
        position: relative;
        z-index: 1;
        box-shadow: 0 10px 30px rgba(0, 0, 0, .08);
    }

    .section-title {
        color: var(--primary-dark);
        position: relative;
        padding-bottom: 15px;
        margin-bottom: 30px;
        font-weight: 700;
        text-align: center;
    }

    .section-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        width: 60px;
        height: 3px;
        background-color: var(--primary-light);
        left: 50%;
        transform: translateX(-50%);
    }

    .section-sub-title {
        color: var(--primary-dark);
        position: relative;
        padding-bottom: 5px;
        margin-bottom: 0px;
        font-weight: 700;
    }

    .section-sub-title::after {
        content: '';
        position: absolute;
        bottom: 0;
    }

    .card {
        border: none;
        box-shadow: 0 10px 30px rgba(0, 0, 0, .08);
        transition: transform 0.3s;
        border-radius: 16px;
        overflow: hidden;
    }

    .card:hover {
        transform: translateY(-8px);
        box-shadow: 0 16px 40px rgba(0, 0, 0, .12);
    }

    .card-img-top {
        height: 200px;
        object-fit: cover;
    }

    .btn-primary {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        padding: 10px 25px;
        font-weight: 500;
        border-radius: 999px;
        box-shadow: 0 6px 20px rgba(46, 125, 50, .3);
    }

    .btn-primary:hover {
        background-color: var(--primary-dark);
        border-color: var(--primary-dark);
    }

    .footer {
        background: linear-gradient(180deg, #1b5e20, #0d3f14);
        color: white;
        padding: 40px 0 20px;
    }

    .footer a {
        color: #c8e6c9;
        text-decoration: none;
    }

    .footer a:hover {
        color: white;
        text-decoration: underline;
    }

    .agenda-item {
        border-left: 5px solid var(--primary-color);
        padding-left: 20px;
        background: #f1f8f3;
        border-radius: 8px;
        padding-top: 15px;
        padding-bottom: 15px;
    }

    .profile-img {
        width: 200px;
        height: 200px;
        border-radius: 50%;
        object-fit: cover;
        border: 5px solid var(--primary-light);
    }

    .banner-carousel {
        height: 500px;
        overflow: hidden;
    }

    .banner-carousel img {
        height: 500px;
        object-fit: cover;
        filter: brightness(0.85);
    }

    .carousel-caption {
        background: rgba(27, 94, 32, 0.8);
        padding: 20px;
        border-radius: 10px;
    }

    .carousel-caption h2 {
        font-weight: 700;
        letter-spacing: -0.5px;
    }

    .carousel-caption p {
        font-size: 1.1rem;
        opacity: .9;
    }

    .page-hero {
        position: relative;
        height: 260px;
        background:
            linear-gradient(rgba(0, 0, 0, .55), rgba(0, 0, 0, .55)),
            var(--hero-bg) center/cover no-repeat;
        display: flex;
        align-items: center;
    }

    .page-hero::after {
        content: '';
        position: absolute;
        inset: 0;
        background-image: url('/img/shape/hero-line.svg');
        background-repeat: no-repeat;
        background-position: top right;
        opacity: .6;
        pointer-events: none;
    }

    .page-title {
        color: #fff;
        font-size: 2.2rem;
        font-weight: 700;
        margin-bottom: .5rem;
    }

    .breadcrumb-wrapper {
        display: inline-flex;
        gap: .5rem;
        align-items: center;
        padding: .35rem .75rem;
        background: rgba(255, 255, 255, .15);
        backdrop-filter: blur(6px);
        border-radius: 999px;
        color: #eee;
        font-size: .85rem;
    }

    .breadcrumb-wrapper a {
        color: #fff;
        text-decoration: none;
    }

    .breadcrumb-wrapper .active {
        opacity: .8;
    }
</style>
