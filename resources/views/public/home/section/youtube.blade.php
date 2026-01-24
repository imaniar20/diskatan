<!-- ============ YOUTUBE GALLERY SECTION ============ -->
<section id="youtube-gallery" class="youtube-gallery-section">
    <div class="container">

        <h2 class="sambutan-title">
            Galeri <span class="text-highlight text-danger">YouTube</span>
        </h2>

        <!-- YouTube Videos Grid -->
        <div class="row g-4">
            @foreach ($videos as $video)
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="youtube-card">
                        <div class="youtube-thumbnail" onclick="playVideo(this, '{{ $video['id'] }}')">
                            <img src="{{ $video['thumbnail'] }}" alt="{{ $video['title'] }}">
                            <div class="play-button">
                                <i class="bi bi-play-circle-fill"></i>
                            </div>
                            <div class="video-duration" data-duration="{{ $video['duration'] }}">
                                00:00
                            </div>
                        </div>

                        <div class="youtube-content">
                            <h3 class="youtube-title">{{ $video['title'] }}</h3>

                            <div class="youtube-meta">
                                <span class="views">
                                    <i class="bi bi-eye"></i>
                                    {{ number_format($video['views']) }} views
                                </span>

                                <span class="date">
                                    <i class="bi bi-calendar"></i>
                                    {{ \Carbon\Carbon::parse($video['published_at'])->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach


        </div>

        <div class="text-center mt-5" data-aos="fade-up" data-aos-delay="700">
            <a href="https://www.youtube.com/@diskatankuningan" target="_blank" class="btn-youtube">
                <i class="bi bi-youtube"></i>
                Lihat Semua Video
            </a>
        </div>
    </div>
</section>
<script>
    function formatDuration(iso) {
        const match = iso.match(/PT(?:(\d+)H)?(?:(\d+)M)?(?:(\d+)S)?/);

        const hours = parseInt(match[1] || 0);
        const minutes = parseInt(match[2] || 0);
        const seconds = parseInt(match[3] || 0);

        const totalMinutes = hours * 60 + minutes;

        return String(totalMinutes).padStart(2, '0') + ':' +
            String(seconds).padStart(2, '0');
    }

    document.querySelectorAll('.video-duration').forEach(el => {
        el.textContent = formatDuration(el.dataset.duration);
    });
</script>
<style>
    /* ============ YOUTUBE GALLERY SECTION ============ */
    .page-wrapper {
        overflow-x: hidden;
    }
    .youtube-gallery-section {
        padding: 80px 0;
        background: linear-gradient(135deg, #f8f9fa, #e8f5e9);
        position: relative;
        overflow: hidden;
    }

    .youtube-gallery-section::before {
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
    .youtube-header {
        margin-bottom: 60px;
    }

    .section-badge {
        display: inline-flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 20px;
    }

    .badge-line {
        width: 50px;
        height: 3px;
        background: linear-gradient(90deg, transparent, #dc2626);
    }

    .badge-text {
        color: #dc2626;
        font-weight: 700;
        font-size: 0.95rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .section-title-youtube {
        font-family: 'Playfair Display', serif;
        font-size: 2.8rem;
        font-weight: 900;
        color: #1b4332;
        margin-bottom: 15px;
    }

    .section-title-youtube .text-highlight {
        color: #dc2626;
        position: relative;
    }

    .section-title-youtube .text-highlight::after {
        content: '';
        position: absolute;
        bottom: -5px;
        left: 0;
        right: 0;
        height: 3px;
        background: #dc2626;
    }

    .section-subtitle {
        font-size: 1.05rem;
        color: #666;
        max-width: 600px;
        margin: 0 auto;
    }

    /* ============ YOUTUBE CARD ============ */
    .youtube-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        height: 100%;
    }

    .youtube-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 60px rgba(220, 38, 38, 0.2);
    }

    /* ============ YOUTUBE THUMBNAIL ============ */
    .youtube-thumbnail {
        position: relative;
        width: 100%;
        padding-top: 56.25%;
        /* 16:9 Aspect Ratio */
        overflow: hidden;
        cursor: pointer;
        background: #000;
    }

    .youtube-thumbnail img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: all 0.4s ease;
    }

    .youtube-thumbnail:hover img {
        transform: scale(1.1);
        opacity: 0.8;
    }

    /* Play Button */
    .play-button {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 4rem;
        color: #dc2626;
        background: rgba(255, 255, 255, 0.95);
        border-radius: 50%;
        width: 80px;
        height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
    }

    .youtube-thumbnail:hover .play-button {
        transform: translate(-50%, -50%) scale(1.1);
        background: #dc2626;
        color: white;
    }

    /* Video Duration */
    .video-duration {
        position: absolute;
        bottom: 10px;
        right: 10px;
        background: rgba(0, 0, 0, 0.8);
        color: white;
        padding: 4px 8px;
        border-radius: 5px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    /* ============ YOUTUBE CONTENT ============ */
    .youtube-content {
        padding: 20px;
    }

    .youtube-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1b4332;
        margin-bottom: 12px;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 56px;
    }

    .youtube-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.85rem;
        color: #666;
    }

    .youtube-meta span {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .youtube-meta i {
        color: #dc2626;
    }

    /* ============ VIEW MORE BUTTON ============ */
    .btn-youtube {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 15px 40px;
        background: linear-gradient(135deg, var(--primary-green), var(--secondary-green));
        color: white;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s ease;
        box-shadow: 0 5px 20px rgba(220, 38, 38, 0.3);
    }

    .btn-youtube:hover {
        background: linear-gradient(135deg, #dc2626, #991b1b);
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(220, 38, 38, 0.4);
    }

    .btn-youtube i {
        font-size: 1.5rem;
    }

    /* ============ IFRAME STYLING ============ */
    .youtube-thumbnail iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: none;
    }

    /* ============ RESPONSIVE ============ */
    @media (max-width: 992px) {
        .section-title-youtube {
            font-size: 2.2rem;
        }

        .play-button {
            width: 70px;
            height: 70px;
            font-size: 3.5rem;
        }
    }

    @media (max-width: 768px) {
        .youtube-gallery-section {
            padding: 60px 0;
        }

        .youtube-header {
            margin-bottom: 40px;
        }

        .section-title-youtube {
            font-size: 1.8rem;
        }

        .section-subtitle {
            font-size: 0.95rem;
        }

        .play-button {
            width: 60px;
            height: 60px;
            font-size: 3rem;
        }

        .youtube-title {
            font-size: 1rem;
            min-height: 52px;
        }

        .youtube-content {
            padding: 15px;
        }

        .btn-youtube {
            padding: 12px 30px;
            font-size: 0.95rem;
        }
    }

    @media (max-width: 576px) {
        .section-title-youtube {
            font-size: 1.6rem;
        }

        .badge-line {
            width: 30px;
        }

        .badge-text {
            font-size: 0.85rem;
        }

        .youtube-title {
            font-size: 0.95rem;
            min-height: 48px;
        }

        .youtube-meta {
            font-size: 0.75rem;
            flex-direction: column;
            align-items: flex-start;
            gap: 5px;
        }
    }
</style>

<script>
    // Function to play YouTube video
    function playVideo(element, videoId) {
        // Create iframe
        const iframe = document.createElement('iframe');
        iframe.setAttribute('src', `https://www.youtube.com/embed/${videoId}?autoplay=1&rel=0`);
        iframe.setAttribute('frameborder', '0');
        iframe.setAttribute('allow',
            'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture');
        iframe.setAttribute('allowfullscreen', '');

        // Replace thumbnail with iframe
        element.innerHTML = '';
        element.appendChild(iframe);
    }
</script>
