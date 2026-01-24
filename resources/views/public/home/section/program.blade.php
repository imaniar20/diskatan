<style>
    /* ============ PROGRAM SECTION ============ */
    .page-wrapper {
        overflow-x: hidden;
    }
    .program-section {
        padding: 40px 0;
        background: linear-gradient(135deg, #f8f9fa, #e8f5e9);
        position: relative;
    }

    /* Decorative Circles */
    .program-section::before {
        content: '';
        position: absolute;
        top: -100px;
        left: -100px;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 50%;
    }

    .program-section::after {
        content: '';
        position: absolute;
        bottom: -150px;
        right: -150px;
        width: 400px;
        height: 400px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 50%;
    }

    /* ============ SECTION HEADER ============ */
    .program-header {
        text-align: center;
        margin-bottom: 60px;
        position: relative;
        z-index: 0;
    }

    .program-badge {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 15px;
    }

    .badge-line {
        width: 50px;
        height: 2px;
        background: #ffc107;
    }

    .badge-text {
        color: #ffc107;
        font-weight: 700;
        font-size: 0.95rem;
        text-transform: uppercase;
        letter-spacing: 1.5px;
    }

    .program-title {
        font-family: 'Poppins', sans-serif;
        font-size: 2.8rem;
        font-weight: 800;
        color: white;
        margin-bottom: 15px;
    }

    .program-subtitle {
        font-size: 1.05rem;
        color: rgba(255, 255, 255, 0.9);
        max-width: 700px;
        margin: 0 auto;
        line-height: 1.6;
    }

    /* ============ PROGRAM CARDS ============ */
    .program-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 25px;
        margin-bottom: 50px;
        position: relative;
        z-index: 0;
    }

    .program-card {
        background: rgba(255, 255, 255, 0.08);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        padding: 35px 25px;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
    }

    .program-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), transparent);
        opacity: 0;
        transition: opacity 0.4s ease;
    }

    .program-card:hover {
        transform: translateY(-10px);
        background: rgba(255, 255, 255, 0.12);
        border-color: rgba(255, 255, 255, 0.2);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    }

    .program-card:hover::before {
        opacity: 1;
    }

    /* ============ CARD ICON ============ */
    .program-icon {
        width: 70px;
        height: 70px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 25px;
        transition: all 0.4s ease;
        position: relative;
    }

    .program-card:nth-child(1) .program-icon {
        background: linear-gradient(135deg, #fbbf24, #f59e0b);
    }

    .program-card:nth-child(2) .program-icon {
        background: linear-gradient(135deg, #34d399, #10b981);
    }

    .program-card:nth-child(3) .program-icon {
        background: linear-gradient(135deg, #f87171, #ef4444);
    }

    .program-card:nth-child(4) .program-icon {
        background: linear-gradient(135deg, #fb923c, #f97316);
    }

    .program-card:nth-child(5) .program-icon {
        background: linear-gradient(135deg, #60a5fa, #3b82f6);
    }

    .program-card:hover .program-icon {
        transform: scale(1.1) rotate(5deg);
    }

    .program-icon i {
        font-size: 2rem;
        color: white;
    }

    /* ============ CARD CONTENT ============ */
    .program-card-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #16a34a;
        margin-bottom: 15px;
        font-family: 'Poppins', sans-serif;
    }

    .program-card-text {
        font-size: 0.95rem;
        /* color: rgba(255, 255, 255, 0.8); */
        line-height: 1.6;
        margin-bottom: 20px;
    }

    .program-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #ffc107;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        font-size: 0.95rem;
        position: relative;
        z-index: 5;
    }

    .program-link i {
        transition: transform 0.3s ease;
    }

    .program-link:hover {
        color: #ffd54f;
        gap: 12px;
    }

    .program-link:hover i {
        transform: translateX(5px);
    }

    /* ============ VIEW ALL BUTTON ============ */
    .program-footer {
        text-align: center;
        position: relative;
        z-index: 0;
    }

    .btn-view-all {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        padding: 15px 40px;
        background: #ffc107;
        color: #1b4332;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 700;
        font-size: 1rem;
        transition: all 0.3s ease;
        box-shadow: 0 10px 30px rgba(255, 193, 7, 0.3);
    }

    .btn-view-all i {
        font-size: 1.1rem;
    }

    .btn-view-all:hover {
        background: #ffca28;
        color: #1b4332;
        transform: translateY(-3px);
        box-shadow: 0 15px 40px rgba(255, 193, 7, 0.4);
    }

    /* ============ RESPONSIVE ============ */
    @media (max-width: 1200px) {
        .program-cards {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 992px) {
        .program-cards {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .program-section {
            padding: 80px 0;
        }

        .program-title {
            font-size: 2.2rem;
        }

        .program-subtitle {
            font-size: 0.95rem;
        }

        .program-cards {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .program-card {
            padding: 30px 20px;
        }

        .program-icon {
            width: 60px;
            height: 60px;
        }

        .program-icon i {
            font-size: 1.7rem;
        }

        .btn-view-all {
            padding: 12px 35px;
            font-size: 0.95rem;
        }
    }

    @media (max-width: 576px) {
        .program-header {
            margin-bottom: 40px;
        }

        .program-title {
            font-size: 1.9rem;
        }

        .program-card-title {
            font-size: 1.15rem;
        }

        .program-card-text {
            font-size: 0.9rem;
        }

        .badge-line {
            width: 40px;
        }
    }
</style>

<!-- Program & Kegiatan Section -->
<section id="program" class="program-section">
    <div class="container">
        <h2 class="sambutan-title">
            Program <span class="text-highlight">& Kegiatan</span>
        </h2>

        <!-- Cards Grid -->
        <div class="program-cards">

            @foreach ($kategori as $item)
                <div class="program-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="program-icon">
                        {!! $item->icon !!}
                    </div>
                    <h3 class="program-card-title">{{ $item->nama }}</h3>
                    <p class="program-card-text">
                        {{ $item->description }}
                    </p>
                    <a href="/program/{{ $item->slug }}" class="program-link">
                        Selengkapnya
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            @endforeach

        </div>

        <!-- View All Button -->
        <div class="program-footer" data-aos="fade-up" data-aos-delay="600">
            <a href="/program" class="btn-view-all text-white">
                Lihat Semua Program
                <i class="bi bi-arrow-right"></i>
            </a>
        </div>

    </div>
</section>
