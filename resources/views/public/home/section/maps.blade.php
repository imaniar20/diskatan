<style>
    /* ============ MAPS SECTION ============ */
    .maps-section {
        padding: 100px 0;
        background: white;
        position: relative;
    }

    /* ============ SECTION HEADER ============ */
    .maps-header {
        text-align: center;
        margin-bottom: 50px;
    }

    .maps-badge {
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

    .maps-title {
        font-family: 'Playfair Display', serif;
        font-size: 2.5rem;
        font-weight: 900;
        color: #1b4332;
        margin-bottom: 15px;
    }

    .maps-title .text-highlight {
        color: #2d6a4f;
    }

    .maps-subtitle {
        font-size: 1.05rem;
        color: #666;
        max-width: 600px;
        margin: 0 auto;
    }

    /* ============ MAPS CONTENT ============ */
    .maps-content {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: 40px;
        align-items: start;
    }

    /* ============ ADDRESS INFO ============ */
    .address-info {
        background: linear-gradient(135deg, #f8f9fa, #e8f5e9);
        padding: 40px;
        border-radius: 25px;
        border-left: 5px solid #2d6a4f;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
    }

    .address-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1b4332;
        margin-bottom: 25px;
        font-family: 'Playfair Display', serif;
    }

    .info-item {
        display: flex;
        align-items: flex-start;
        gap: 15px;
        margin-bottom: 25px;
        padding-bottom: 25px;
        border-bottom: 1px solid rgba(45, 106, 79, 0.1);
    }

    .info-item:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }

    .info-icon {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #2d6a4f, #40916c);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .info-icon i {
        font-size: 1.5rem;
        color: white;
    }

    .info-content h4 {
        font-size: 1rem;
        font-weight: 700;
        color: #2d6a4f;
        margin-bottom: 5px;
    }

    .info-content p {
        font-size: 0.95rem;
        color: #555;
        line-height: 1.6;
        margin: 0;
    }

    .info-content a {
        color: #2d6a4f;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .info-content a:hover {
        color: #40916c;
    }

    /* ============ DIRECTION BUTTON ============ */
    .btn-direction {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        width: 100%;
        justify-content: center;
        padding: 15px 30px;
        background: linear-gradient(135deg, #16a34a, #22c55e);
        color: white;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s ease;
        margin-top: 20px;
        box-shadow: 0 5px 20px rgba(22, 163, 74, 0.3);
    }

    .btn-direction:hover {
        background: linear-gradient(135deg, #22c55e, #4ade80);
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 8px 30px rgba(22, 163, 74, 0.4);
    }

    /* ============ MAP CONTAINER ============ */
    .map-container {
        border-radius: 25px;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        position: relative;
        height: 500px;
    }

    .map-container iframe {
        width: 100%;
        height: 100%;
        border: none;
    }

    /* Map Loading State */
    .map-loading {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, #f8f9fa, #e8f5e9);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        gap: 15px;
    }

    .map-loading i {
        font-size: 3rem;
        color: #2d6a4f;
        animation: pulse 1.5s ease-in-out infinite;
    }

    @keyframes pulse {

        0%,
        100% {
            opacity: 1;
            transform: scale(1);
        }

        50% {
            opacity: 0.5;
            transform: scale(0.95);
        }
    }

    .map-loading p {
        color: #666;
        font-weight: 600;
    }

    /* ============ RESPONSIVE ============ */
    @media (max-width: 992px) {
        .maps-content {
            grid-template-columns: 1fr;
            gap: 30px;
        }

        .map-container {
            height: 400px;
        }
    }

    @media (max-width: 768px) {
        .maps-section {
            padding: 80px 0;
        }

        .maps-title {
            font-size: 2rem;
        }

        .maps-subtitle {
            font-size: 0.95rem;
        }

        .address-info {
            padding: 30px 25px;
        }

        .address-title {
            font-size: 1.3rem;
        }

        .info-item {
            gap: 12px;
            margin-bottom: 20px;
            padding-bottom: 20px;
        }

        .info-icon {
            width: 45px;
            height: 45px;
        }

        .info-icon i {
            font-size: 1.3rem;
        }

        .info-content h4 {
            font-size: 0.95rem;
        }

        .info-content p {
            font-size: 0.9rem;
        }

        .map-container {
            height: 350px;
        }
    }

    @media (max-width: 576px) {
        .maps-header {
            margin-bottom: 40px;
        }

        .maps-title {
            font-size: 1.8rem;
        }

        .address-info {
            padding: 25px 20px;
        }

        .map-container {
            height: 300px;
        }

        .btn-direction {
            padding: 12px 25px;
            font-size: 0.95rem;
        }
    }
</style>

<!-- Maps Section -->
<section class="maps-section">
    <div class="container">

        <h2 class="sambutan-title">
            Lokasi <span class="text-highlight">Kantor</span>
        </h2>

        <!-- Content -->
        <div class="maps-content">

            <!-- Address Info -->
            <div class="address-info" data-aos="fade-right" data-aos-duration="1000">
                <h3 class="address-title">Informasi Kontak</h3>

                <!-- Alamat -->
                <div class="info-item">
                    <div class="info-icon">
                        <i class="bi bi-geo-alt-fill"></i>
                    </div>
                    <div class="info-content">
                        <h4>Alamat Kantor</h4>
                        <p>
                            Jl. Raya Syech Manglayang No. 3<br>Kecamatan Kadugede Kabupaten Kuningan Provinsi Jawa Barat
                            Kode Pos 45561
                        </p>
                    </div>
                </div>

                <!-- Telepon -->
                <div class="info-item">
                    <div class="info-icon">
                        <i class="bi bi-telephone-fill"></i>
                    </div>
                    <div class="info-content">
                        <h4>Telepon</h4>
                        <p>
                            <a href="tel:+622321234567">(0232) 123-4567</a>
                        </p>
                    </div>
                </div>

                <!-- Jam Kerja -->
                <div class="info-item">
                    <div class="info-icon">
                        <i class="bi bi-clock-fill"></i>
                    </div>
                    <div class="info-content">
                        <h4>Jam Operasional</h4>
                        <p>
                            Senin - Jumat: 08:00 - 16:00 WIB<br>
                            Sabtu - Minggu: Libur
                        </p>
                    </div>
                </div>

                <!-- Direction Button -->
                <a href="https://www.google.com/maps/dir/?api=1&destination=-6.9899687439176805,108.4632952235174"
                    target="_blank" class="btn-direction">
                    <i class="bi bi-signpost-2"></i>
                    Petunjuk Arah
                </a>
            </div>

            <!-- Map -->
            <div class="map-container" data-aos="fade-left" data-aos-duration="1000">
                <!-- Loading State -->
                <div class="map-loading">
                    <i class="bi bi-geo-alt"></i>
                    <p>Memuat peta...</p>
                </div>

                <!-- Google Maps Embed -->
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.123456789!2d108.4632952235174!3d-6.9899687439176805!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNsKwNTknMjMuOSJTIDEwOMKwMjcnNDcuOSJF!5e0!3m2!1sid!2sid!4v1234567890123!5m2!1sid!2sid"
                    loading="lazy" referrerpolicy="no-referrer-when-downgrade"
                    onload="this.previousElementSibling.style.display='none'">
                </iframe>
            </div>

        </div>

    </div>
</section>
