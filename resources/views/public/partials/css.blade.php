<style>
    :root {
        --primary-green: #16a34a;
        --secondary-green: #40916c;
        --light-green: #52b788;
        --accent-green: #74c69d;
        --cream: #f8f9fa;
        --dark: #1b4332;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Poppins', sans-serif;
        overflow-x: hidden;
        background: var(--cream);
    }

    h1,
    h2,
    h3,
    h4,
    h5,
    h6 {
        font-family: 'Playfair Display', serif;
    }

    .hidden {
        opacity: 0;
        height: 0;
        overflow: hidden;
        transition: opacity .6s ease, height .6s ease;
    }

    .show {
        opacity: 1;
        height: auto;
    }


    /* ============ TOP DATE TIME BAR ============ */
    .top-bar {
        background: linear-gradient(135deg, var(--cream), var(--secondary-cream));
        color: var(--secondary-green);
        padding: 8px 0;
        font-size: 1rem;
        position: relative;
        z-index: 1030;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.8);
    }

    .top-bar i {
        margin-right: 8px;
        animation: pulse 2s infinite;
    }

    .social {
        transition: color .2s;
        color: var(--secondary-green);
    }

    .social:hover {
        color: var(--dark);
    }

    @keyframes pulse {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0.6;
        }
    }

    /* ============ NAVBAR ============ */
    .navbar {
        position: sticky;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1020;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        background: linear-gradient(135deg, var(--secondary-green), var(--primary-green));
        padding: 1rem 0;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .navbar.scrolled {
        background: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(10px);
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.15);
        padding: 0.5rem 0;
    }

    .navbar-brand {
        font-weight: 800;
        font-size: 1rem;
        color: white !important;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: all 0.3s ease;
    }

    .navbar-brand img {
        transition: transform 0.3s ease;
    }

    .navbar-brand:hover img {
        transform: rotate(360deg);
    }

    .brand-subtitle {
        font-size: 0.75rem;
        font-weight: 400;
        opacity: 0.95;
        font-family: 'Poppins', sans-serif;
    }

    .brand-title {
        font-size: 0.95rem;
        font-weight: 700;
        font-family: 'Poppins', sans-serif;
    }

    .navbar.scrolled .navbar-brand {
        color: var(--primary-green) !important;
    }

    .navbar.scrolled .brand-subtitle,
    .navbar.scrolled .brand-title {
        color: var(--primary-green) !important;
    }

    .nav-link {
        color: white !important;
        font-weight: 500;
        margin: 0 10px;
        position: relative;
        transition: all 0.3s ease;
    }

    .navbar.scrolled .nav-link {
        color: var(--dark) !important;
    }

    .nav-link.active {
        position: relative;
    }

    .nav-link.active::after {
        content: '';
        position: absolute;
        bottom: -5px;
        left: 50%;
        transform: translateX(-50%);
        width: 80%;
        height: 3px;
        background: var(--accent-green);
    }

    .navbar.scrolled .nav-link.active::after {
        background: var(--primary-green);
    }

    .nav-link:hover {
        opacity: 0.8;
    }

    /* Dropdown Styles */
    .dropdown-toggle-custom {
        cursor: pointer;
    }

    .dropdown-icon {
        font-size: 0.75rem;
        transition: transform 0.3s ease;
    }

    .dropdown.show .dropdown-icon {
        transform: rotate(180deg);
    }

    .dropdown-menu {
        background: white;
        border: none;
        border-radius: 15px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
        padding: 10px 0;
        margin-top: 10px;
        animation: dropdownSlide 0.3s ease;
    }

    .dropdown-menu .dropdown-item.active {
        background-color: var(--primary-green);;
        color: #ffffff;
        /* opsional */
    }

    @keyframes dropdownSlide {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .dropdown-item {
        padding: 10px 25px;
        color: var(--dark);
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .dropdown-item:hover {
        background: linear-gradient(135deg, var(--light-green), var(--accent-green));
        color: white;
        padding-left: 30px;
    }

    .navbar-toggler {
        border-color: rgba(255, 255, 255, 0.5);
    }

    .navbar.scrolled .navbar-toggler {
        border-color: var(--primary-green);
    }

    .navbar-toggler-icon {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(255, 255, 255, 1)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
    }

    .navbar.scrolled .navbar-toggler-icon {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(45, 106, 79, 1)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
    }

    /* ============ HERO CAROUSEL ============ */
    .hero-section {
        position: relative;
        overflow: hidden;
    }

    .carousel-item {
        height: 85vh;
        position: relative;
    }

    .carousel-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        filter: brightness(0.7);
    }

    .carousel-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(20, 82, 40, 0.7), rgba(64, 145, 108, 0));
        display: flex;
        align-items: center;
    }

    .hero-content {
        color: white;
        z-index: 10;
        text-align: left;
    }

    .welcome-badge {
        display: inline-block;
        background: rgba(255, 193, 7, 0.2);
        color: #ffc107;
        padding: 10px 25px;
        border-radius: 30px;
        font-size: 0.95rem;
        font-weight: 600;
        margin-bottom: 20px;
        border: 2px solid rgba(255, 193, 7, 0.3);
    }

    .hero-title {
        font-family: 'Poppins', sans-serif;
        font-size: 3.5rem;
        font-weight: 800;
        color: white;
        margin-bottom: 20px;
        line-height: 1.2;
        animation: fadeInUp 1s ease-out;
    }

    .hero-title .text-warning {
        color: #ffc107 !important;
    }

    .hero-subtitle {
        font-size: 1.2rem;
        color: rgba(255, 255, 255, 0.9);
        margin-bottom: 35px;
        line-height: 1.8;
        max-width: 600px;
        font-weight: 300;
        animation: fadeInUp 1.2s ease-out;
    }

    .hero-buttons .btn {
        border-radius: 30px;
        font-weight: 600;
        transition: all 0.3s ease;
        animation: fadeInUp 1.4s ease-out;
    }

    .hero-buttons .btn-warning {
        background: #ffc107;
        border: none;
        color: #1b4332;
    }

    .hero-buttons .btn-warning:hover {
        background: #ffca2c;
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(255, 193, 7, 0.4);
    }

    .hero-buttons .btn-outline-light {
        border: 2px solid white;
    }

    .hero-buttons .btn-outline-light:hover {
        background: white;
        color: #1b4332;
        transform: translateY(-3px);
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

    /* ============ STATISTICS SECTION ============ */
    .statistics-section {
        margin-top: -80px;
        margin-bottom: -80px;
        position: relative;
        z-index: 10;
        padding: 0 0 100px 0;
    }

    .stat-card {
        background: white;
        border-radius: 20px;
        padding: 35px 25px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        transition: all 0.4s ease;
        border: 2px solid transparent;
        text-align: center;
    }

    .stat-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 60px rgba(45, 106, 79, 0.15);
        border-color: #e8f5e9;
    }

    .stat-icon {
        width: 70px;
        height: 70px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        font-size: 2rem;
        color: white;
    }

    .stat-icon.bg-success {
        background: linear-gradient(135deg, #2d6a4f, #40916c);
    }

    .stat-icon.bg-warning {
        background: linear-gradient(135deg, #ffc107, #ffca2c);
    }

    .stat-icon.bg-primary {
        background: linear-gradient(135deg, #4285f4, #5a95f5);
    }

    .stat-icon.bg-info {
        background: linear-gradient(135deg, #00bcd4, #26c6da);
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--primary-green);
        margin-bottom: 5px;
        font-family: 'Poppins', sans-serif;
    }

    .stat-label {
        font-size: 0.95rem;
        color: var(--primary-green);
        margin: 0;
        font-weight: 500;
    }

    .stat-number2 {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--cream);
        margin-bottom: 5px;
        font-family: 'Poppins', sans-serif;
    }

    .stat-label2 {
        font-size: 0.95rem;
        color: var(--cream);
        margin: 0;
        font-weight: 500;
    }


    /* ============ ABOUT SECTION ============ */
    .about-section {
        padding: 100px 0;
        background: white;
        position: relative;
    }

    .about-section::before {
        content: '';
        position: absolute;
        top: -50px;
        left: 0;
        right: 0;
        height: 100px;
        background: white;
        clip-path: polygon(0 50%, 100% 0, 100% 100%, 0 100%);
    }

    .section-title {
        font-size: 3rem;
        font-weight: 900;
        color: var(--primary-green);
        margin-bottom: 3rem;
        position: relative;
        display: inline-block;
    }

    .section-title::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 0;
        width: 60%;
        height: 5px;
        background: linear-gradient(90deg, var(--primary-green), var(--accent-green));
        border-radius: 10px;
    }

    .about-img-container {
        position: relative;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(45, 106, 79, 0.2);
    }

    .about-img-container img {
        width: 100%;
        height: 500px;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .about-img-container:hover img {
        transform: scale(1.05);
    }

    .about-text {
        padding: 0 30px;
    }

    .about-text p {
        font-size: 1.1rem;
        line-height: 1.8;
        color: #555;
        text-align: justify;
    }

    .stats-box {
        background: linear-gradient(135deg, var(--primary-green), var(--secondary-green));
        padding: 30px;
        border-radius: 15px;
        color: white;
        margin-top: 30px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .stat-item {
        text-align: center;
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 800;
        display: block;
    }

    .stat-label {
        font-size: 0.9rem;
        opacity: 0.9;
    }

    /* ============ AGENDA SECTION ============ */
    .agenda-section {
        padding: 100px 0;
        background: linear-gradient(135deg, #e8f5e9, #f1f8f4);
        position: relative;
    }

    .agenda-card {
        background: white;
        border-radius: 20px;
        padding: 0;
        margin-bottom: 30px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.8);
        transition: all 0.4s ease;
        border-left: 5px solid var(--primary-green);
        overflow: hidden;
        position: relative;
        min-height: 200px;
    }

    .agenda-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-size: cover;
        background-position: center;
        filter: blur(8px) brightness(0.4);
        transition: all 0.4s ease;
        z-index: 1;
    }

    .agenda-card:hover::before {
        filter: blur(3px) brightness(0.6);
        transform: scale(1.05);
    }

    .agenda-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 60px rgba(45, 106, 79, 0.2);
    }

    .agenda-content {
        position: relative;
        z-index: 2;
        padding: 30px;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(255, 255, 255, 0.9));
        backdrop-filter: blur(10px);
        border-radius: 20px;
        transition: all 0.4s ease;
    }

    .agenda-card:hover .agenda-content {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.85), rgba(255, 255, 255, 0.75));
    }

    .agenda-date {
        background: linear-gradient(135deg, var(--primary-green), var(--secondary-green));
        color: white;
        padding: 15px;
        border-radius: 15px;
        text-align: center;
        margin-bottom: 20px;
        width: 100px;
        box-shadow: 0 5px 15px rgba(45, 106, 79, 0.3);
        transition: transform 0.3s ease;
    }

    .agenda-card:hover .agenda-date {
        transform: scale(1.05);
    }

    .agenda-date .day {
        font-size: 2rem;
        font-weight: 800;
        display: block;
    }

    .agenda-date .month {
        font-size: 0.9rem;
        text-transform: uppercase;
    }

    .agenda-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--primary-green);
        margin-bottom: 10px;
        text-shadow: 1px 1px 2px rgba(255, 255, 255, 0.8);
    }

    .agenda-time {
        color: #333;
        margin-bottom: 10px;
        font-weight: 600;
    }

    .agenda-location {
        color: #555;
        font-size: 0.95rem;
        font-weight: 500;
    }

    .agenda-time i,
    .agenda-location i {
        color: var(--secondary-green);
    }

    /* ============ NEWS SECTION ============ */
    .news-section {
        padding: 100px 0;
        background: white;
    }

    .news-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.8);
        transition: all 0.4s ease;
        height: 100%;
    }

    .news-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 60px rgba(45, 106, 79, 0.15);
    }

    .news-img {
        width: 100%;
        height: 250px;
        object-fit: cover;
    }

    .news-content {
        padding: 25px;
    }

    .news-badge {
        background: var(--accent-green);
        color: white;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 0.85rem;
        display: inline-block;
        margin-bottom: 15px;
    }

    .news-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 15px;
        line-height: 1.4;
    }

    .news-excerpt {
        color: #666;
        margin-bottom: 20px;
        line-height: 1.6;
    }

    .news-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 15px;
        border-top: 1px solid #eee;
    }

    .news-date {
        color: #888;
        font-size: 0.9rem;
    }

    .news-link {
        color: var(--primary-green);
        font-weight: 600;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .news-link:hover {
        color: var(--secondary-green);
    }

    /* ============ STATS & SURVEY SECTION ============ */
    .stats-survey-section {
        padding: 100px 0;
        background: linear-gradient(135deg, var(--primary-green), var(--secondary-green));
        color: white;
    }

    .visitor-counter {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        padding: 40px;
        border-radius: 20px;
        text-align: center;
        border: 2px solid rgba(255, 255, 255, 0.2);
    }

    .counter-title {
        font-size: 1.3rem;
        margin-bottom: 20px;
        opacity: 0.9;
    }

    .counter-number {
        font-size: 4rem;
        font-weight: 900;
        display: block;
        margin-bottom: 10px;
    }

    .counter-label {
        font-size: 1rem;
        opacity: 0.8;
    }

    .survey-box {
        background: white;
        padding: 40px;
        border-radius: 20px;
        color: var(--dark);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
    }

    .survey-title {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--primary-green);
        margin-bottom: 20px;
    }

    .rating-group {
        margin-bottom: 20px;
    }

    .rating-stars {
        font-size: 2rem;
        color: #ddd;
        cursor: pointer;
        display: inline-flex;
        gap: 10px;
    }

    .rating-stars i:hover,
    .rating-stars i.active {
        color: #ffc107;
    }

    /* ============ CONTACT SECTION ============ */
    .contact-section {
        padding: 100px 0;
        background: linear-gradient(135deg, #e8f5e9, #f1f8f4);
    }

    .contact-form {
        background: white;
        padding: 50px;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
    }

    .form-control,
    .form-select {
        padding: 15px;
        border-radius: 10px;
        border: 2px solid #e0e0e0;
        margin-bottom: 20px;
        transition: all 0.3s ease;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: var(--primary-green);
        box-shadow: 0 0 0 0.2rem rgba(45, 106, 79, 0.1);
    }

    .submit-btn {
        width: 100%;
        padding: 15px;
        background: linear-gradient(135deg, var(--primary-green), var(--secondary-green));
        color: white;
        border: none;
        border-radius: 10px;
        font-size: 1.1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .submit-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(45, 106, 79, 0.3);
    }

    .contact-info-box {
        background: linear-gradient(135deg, var(--primary-green), var(--secondary-green));
        color: white;
        padding: 40px;
        border-radius: 20px;
        height: 100%;
    }

    .contact-info-item {
        margin-bottom: 30px;
    }

    .contact-info-item i {
        font-size: 2rem;
        margin-bottom: 15px;
    }

    .contact-info-item h5 {
        font-size: 1.2rem;
        margin-bottom: 10px;
    }

    .contact-info-item p {
        opacity: 0.9;
        line-height: 1.6;
    }

    /* ============ FOOTER ============ */
    .footer {
        background: linear-gradient(135deg, var(--secondary-green), var(--primary-green));
        color: white;
        padding: 60px 0 20px;
    }

    .footer-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 20px;
    }

    .footer-links {
        list-style: none;
        padding: 0;
    }

    .footer-links li {
        margin-bottom: 10px;
    }

    .footer-links a {
        color: rgba(255, 255, 255, 0.7);
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .footer-links a:hover {
        color: var(--accent-green);
    }

    .social-icons {
        display: flex;
        gap: 15px;
        margin-top: 20px;
    }

    .social-icon {
        width: 45px;
        height: 45px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2rem;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .social-icon:hover {
        background: var(--accent-green);
        transform: translateY(-3px);
    }

    .footer-bottom {
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        margin-top: 40px;
        padding-top: 20px;
        text-align: center;
        opacity: 0.7;
    }

    /* ============ DECORATIVE ELEMENTS ============ */
    .leaf-decoration {
        position: fixed;
        font-size: 3rem;
        opacity: 0.1;
        z-index: 1;
        animation: float 20s infinite;
    }

    @keyframes float {

        0%,
        100% {
            transform: translateY(0) rotate(0deg);
        }

        50% {
            transform: translateY(-30px) rotate(180deg);
        }
    }

    .leaf-1 {
        top: 10%;
        left: 5%;
        animation-delay: 0s;
    }

    .leaf-2 {
        top: 30%;
        right: 8%;
        animation-delay: 3s;
    }

    .leaf-3 {
        top: 60%;
        left: 10%;
        animation-delay: 6s;
    }

    .leaf-4 {
        top: 80%;
        right: 5%;
        animation-delay: 9s;
    }

    /* ============ RESPONSIVE ============ */
    @media (max-width: 768px) {
        .hero-title {
            font-size: 2.5rem;
        }

        .hero-subtitle {
            font-size: 1.1rem;
        }

        .section-title {
            font-size: 2rem;
        }

        .contact-form {
            padding: 30px 20px;
        }
    }
</style>
