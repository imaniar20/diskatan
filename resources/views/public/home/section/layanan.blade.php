<style>
    /* ============ LAYANAN ONLINE SECTION ============ */
    .layanan-section {
        padding: 100px 0;
        background: linear-gradient(135deg, var(--primary-green), var(--secondary-green));
        position: relative;
    }
    
    .layanan-section::before {
        content: '';
        position: absolute;
        top: -50px;
        left: 0;
        right: 0;
        height: 100px;
        background: linear-gradient(135deg, #f8f9fa, #e8f5e9);
        clip-path: polygon(0 50%, 100% 0, 100% 100%, 0 100%);
    }
    
    /* ============ SECTION HEADER ============ */
    .layanan-header {
        text-align: center;
        margin-bottom: 60px;
    }
    
    .layanan-badge {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 15px;
    }
    
    .badge-line {
        width: 40px;
        height: 3px;
        background: linear-gradient(90deg, transparent, #16a34a);
    }
    
    .badge-text {
        color: #16a34a;
        font-weight: 700;
        font-size: 0.95rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    .layanan-title {
        font-family: 'Poppins', sans-serif;
        font-size: 2.5rem;
        font-weight: 800;
        color: #1b4332;
        margin-bottom: 15px;
    }
    
    .layanan-title .text-highlight {
        color: #16a34a;
    }
    
    .layanan-subtitle {
        font-size: 1.05rem;
        color: #666;
        max-width: 600px;
        margin: 0 auto;
    }
    
    /* ============ LAYANAN CARDS ============ */
    .layanan-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 30px;
        margin-bottom: 50px;
    }
    
    .layanan-card {
        background: white;
        border-radius: 20px;
        padding: 35px 25px;
        text-align: center;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 5px 25px rgba(0, 0, 0, 0.1);
        border: 2px solid transparent;
        position: relative;
        overflow: hidden;
    }
    
    .layanan-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--card-color), transparent);
        transform: scaleX(0);
        transition: transform 0.4s ease;
    }
    
    .layanan-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        border-color: var(--card-color);
    }
    
    .layanan-card:hover::before {
        transform: scaleX(1);
    }
    
    /* Card Colors */
    .layanan-card.green { --card-color: #16a34a; }
    .layanan-card.blue { --card-color: #2563eb; }
    .layanan-card.orange { --card-color: #f59e0b; }
    .layanan-card.purple { --card-color: #9333ea; }
    
    /* ============ CARD ICON ============ */
    .layanan-icon {
        width: 80px;
        height: 80px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 25px;
        transition: all 0.4s ease;
        position: relative;
    }
    
    .layanan-card.green .layanan-icon {
        background: linear-gradient(135deg, #16a34a, #22c55e);
    }
    
    .layanan-card.blue .layanan-icon {
        background: linear-gradient(135deg, #2563eb, #3b82f6);
    }
    
    .layanan-card.orange .layanan-icon {
        background: linear-gradient(135deg, #f59e0b, #fbbf24);
    }
    
    .layanan-card.purple .layanan-icon {
        background: linear-gradient(135deg, #9333ea, #a855f7);
    }
    
    .layanan-card:hover .layanan-icon {
        transform: rotateY(360deg) scale(1.1);
    }
    
    .layanan-icon i {
        font-size: 2.5rem;
        color: white;
    }
    
    /* ============ CARD CONTENT ============ */
    .layanan-card-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: #1b4332;
        margin-bottom: 12px;
        font-family: 'Poppins', sans-serif;
    }
    
    .layanan-card-text {
        font-size: 0.95rem;
        color: #666;
        line-height: 1.6;
        margin-bottom: 20px;
    }
    
    .layanan-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--card-color);
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        font-size: 0.95rem;
    }
    
    .layanan-link i {
        transition: transform 0.3s ease;
    }
    
    .layanan-link:hover {
        gap: 12px;
    }
    
    .layanan-link:hover i {
        transform: translateX(5px);
    }
    
    /* ============ TRACKING BUTTON ============ */
    .tracking-wrapper {
        text-align: center;
    }
    
    .btn-tracking {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        padding: 15px 40px;
        background: #ffc107;
        border: 2px solid #ffffff;
        color: #ffffff;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s ease;
    }
    
    .btn-tracking i {
        font-size: 1.2rem;
    }
    
    .btn-tracking:hover {
        background: linear-gradient(135deg, #16a34a, #22c55e);
        border-color: #16a34a;
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(22, 163, 74, 0.3);
    }
    
    /* ============ RESPONSIVE ============ */
    @media (max-width: 1200px) {
        .layanan-cards {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (max-width: 768px) {
        .layanan-section {
            padding: 80px 0;
        }
        
        .layanan-title {
            font-size: 2rem;
        }
        
        .layanan-subtitle {
            font-size: 0.95rem;
        }
        
        .layanan-cards {
            grid-template-columns: 1fr;
            gap: 20px;
        }
        
        .layanan-card {
            padding: 30px 20px;
        }
        
        .layanan-icon {
            width: 70px;
            height: 70px;
        }
        
        .layanan-icon i {
            font-size: 2rem;
        }
        
        .btn-tracking {
            padding: 12px 30px;
            font-size: 0.95rem;
        }
    }
    
    @media (max-width: 576px) {
        .layanan-header {
            margin-bottom: 40px;
        }
        
        .layanan-title {
            font-size: 1.8rem;
        }
        
        .layanan-card-title {
            font-size: 1.1rem;
        }
        
        .layanan-card-text {
            font-size: 0.9rem;
        }
    }
</style>
<section id="service" class="layanan-section">
    <div class="container">
        <h2 class="sambutan-title">
            <span class="text-white">Layanan</span> <span class="text-highlight-warning">Online</span>
        </h2>
        <!-- Cards Grid -->
        <div class="layanan-cards">
            
            {{-- Card 1: Permohonan Bantuan --}}
            <div class="layanan-card green" data-aos="fade-up" data-aos-delay="100">
                <div class="layanan-icon">
                    <i class="bi bi-clipboard-check"></i>
                </div>
                <h3 class="layanan-card-title">Permohonan Bantuan</h3>
                <p class="layanan-card-text">
                    Ajukan permohonan bantuan alat, bibit, atau pupuk pertanian
                </p>
                <a href="#" class="layanan-link">
                    Ajukan
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            
            {{-- Card 2: Pengaduan Masyarakat --}}
            <div class="layanan-card blue" data-aos="fade-up" data-aos-delay="200">
                <div class="layanan-icon">
                    <i class="bi bi-chat-left-text"></i>
                </div>
                <h3 class="layanan-card-title">Pengaduan Masyarakat</h3>
                <p class="layanan-card-text">
                    Sampaikan keluhan atau pengaduan terkait layanan kami
                </p>
                <a href="#" class="layanan-link">
                    Ajukan
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            
            {{-- Card 3: Konsultasi Pertanian --}}
            <div class="layanan-card orange" data-aos="fade-up" data-aos-delay="300">
                <div class="layanan-icon">
                    <i class="bi bi-question-circle"></i>
                </div>
                <h3 class="layanan-card-title">Konsultasi Pertanian</h3>
                <p class="layanan-card-text">
                    Konsultasikan masalah pertanian Anda dengan ahli kami
                </p>
                <a href="#" class="layanan-link">
                    Ajukan
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            
            {{-- Card 4: Permohonan Data --}}
            <div class="layanan-card purple" data-aos="fade-up" data-aos-delay="400">
                <div class="layanan-icon">
                    <i class="bi bi-file-earmark-text"></i>
                </div>
                <h3 class="layanan-card-title">Permohonan Data</h3>
                <p class="layanan-card-text">
                    Ajukan permohonan data dan informasi publik
                </p>
                <a href="#" class="layanan-link">
                    Ajukan
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            
        </div>
        <div class="tracking-wrapper" data-aos="fade-up" data-aos-delay="500">
            <a href="http://siwawan.kuningankab.go.id/" target="_blank" class="btn-tracking">
                <i class="bi bi-search"></i>
                Buka Layanan Online
            </a>
        </div>
    </div>
</section>