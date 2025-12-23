<style>
    :root {
        --primary-green: #2d6a4f;
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

    /* ============ TOP DATE TIME BAR ============ */
    .top-bar {
        background: linear-gradient(135deg, var(--cream), var(--cream));
        color: var(--primary-green);
        padding: 8px 0;
        font-size: 0.9rem;
        position: relative;
        z-index: 1030;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .top-bar i {
        margin-right: 8px;
        animation: pulse 2s infinite;
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
        background: linear-gradient(135deg, var(--primary-green), var(--secondary-green));
        padding: 1rem 0;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .navbar.scrolled {
        background: rgba(255, 255, 255, 0.7);
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

    .dropdown-item.active, .dropdown-item:active{
        background-color: var(--secondary-green);
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
        background: linear-gradient(135deg, rgba(45, 106, 79, 0.7), rgba(64, 145, 108, 0.5));
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .hero-content {
        text-align: center;
        color: white;
        z-index: 10;
    }

    .hero-title {
        font-size: 4rem;
        font-weight: 900;
        margin-bottom: 1.5rem;
        text-shadow: 2px 2px 20px rgba(0, 0, 0, 0.3);
        animation: fadeInUp 1s ease-out;
    }

    .hero-subtitle {
        font-size: 1.5rem;
        font-weight: 300;
        margin-bottom: 2rem;
        animation: fadeInUp 1.2s ease-out;
    }

    .hero-btn {
        padding: 15px 40px;
        font-size: 1.1rem;
        font-weight: 600;
        border-radius: 50px;
        background: white;
        color: var(--primary-green);
        border: none;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
        animation: fadeInUp 1.4s ease-out;
    }

    .hero-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
        background: var(--accent-green);
        color: white;
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
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
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
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        transition: all 0.4s ease;
        border-left: 5px solid var(--primary-green);
    }

    .agenda-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 60px rgba(45, 106, 79, 0.15);
    }

    .agenda-date {
        background: linear-gradient(135deg, var(--primary-green), var(--secondary-green));
        color: white;
        padding: 15px;
        border-radius: 15px;
        text-align: center;
        margin-bottom: 20px;
        width: 100px;
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
    }

    .agenda-time {
        color: #666;
        margin-bottom: 10px;
    }

    .agenda-location {
        color: #888;
        font-size: 0.95rem;
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
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
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
        background: var(--dark);
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
