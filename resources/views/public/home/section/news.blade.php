<style>
    /* === NEWS SECTION === */

    /* Featured Card (Kiri) */
    .news-card--featured {
        display: flex;
        flex-direction: column;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.10);
        transition: box-shadow 0.3s ease, transform 0.3s ease;
    }

    .news-card--featured:hover {
        box-shadow: 0 8px 36px rgba(0, 0, 0, 0.16);
        transform: translateY(-4px);
    }

    .news-card__img-wrap {
        position: relative;
        width: 100%;
    }

    .news-img--featured {
        width: 100%;
        height: 420px;
        object-fit: cover;
        display: block;
    }

    .news-badge--overlay {
        position: absolute;
        top: 16px;
        left: 16px;
        z-index: 2;
    }

    .news-content--featured {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 24px;
    }

    .news-title--featured {
        font-size: 1.3rem;
        font-weight: 700;
        line-height: 1.4;
        margin-bottom: 12px;
    }

    .news-excerpt--featured {
        font-size: 0.95rem;
        color: #666;
        line-height: 1.7;
        flex: 1;
        margin-bottom: 16px;
    }

    /* List Cards (Kanan) */
    .news-card--list {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.07);
        transition: box-shadow 0.3s ease, transform 0.3s ease;
        background: #fff;
    }

    .news-card--list:hover {
        box-shadow: 0 6px 24px rgba(0, 0, 0, 0.13);
        transform: translateY(-2px);
    }

    .news-img--list {
        width: 100%;
        height: 210px;
        object-fit: cover;
        display: block;
    }

    .news-content--list {
        padding: 12px 16px;
    }

    .news-title--list {
        font-size: 0.92rem;
        font-weight: 600;
        line-height: 1.4;
        margin: 6px 0 10px;
    }

    .news-meta--list {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 6px;
    }

    .news-link--sm {
        font-size: 0.82rem;
    }
</style>
<section id="news" class="news-section">
    <div class="container">
        <h2 class="sambutan-title">
            Berita <span class="text-highlight">Terkini</span>
        </h2>

        <div class="row g-4">
            {{-- Kolom Kiri: Berita Utama (item pertama) --}}
            @if ($news->count() > 0)
                @php $featured = $news->first(); @endphp
                <div class="col-lg-6" data-aos="fade-right">
                    <div class="news-card news-card--featured h-100">
                        <div class="news-card__img-wrap">
                            <img src="{{ asset($featured->thumbnail ? 'storage/' . $featured->thumbnail : 'images/img_not_found.png') }}"
                                alt="{{ $featured->title }}" class="news-img news-img--featured">
                            <span class="news-badge news-badge--overlay">Berita Utama</span>
                        </div>
                        <div class="news-content news-content--featured">
                            <h3 class="news-title news-title--featured">{{ Str::limit($featured->title, 80) }}</h3>
                            <p class="news-excerpt news-excerpt--featured">
                                {!! Str::limit(preg_replace('/<img[^>]*>/i', '', $featured->content), 1200) !!}
                            </p>
                            <div class="news-meta">
                                <span class="news-date">
                                    <i class="bi bi-calendar3 me-2"></i>
                                    {{ \Carbon\Carbon::parse($featured->published_at)->format('d M Y') }}
                                </span>
                                <a href="{{ route('berita.detail', $featured->slug) }}" class="news-link">
                                    Baca Selengkapnya <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Kolom Kanan: List berita setelahnya --}}
            @if ($news->count() > 1)
                <div class="col-lg-6" data-aos="fade-left">
                    <div class="news-list d-flex flex-column gap-3 h-100">
                        @foreach ($news->skip(1) as $item)
                            <div class="news-card news-card--list" data-aos="fade-up"
                                data-aos-delay="{{ $loop->index * 100 }}">
                                <div class="row g-0 align-items-center">
                                    <div class="col-4">
                                        <img src="{{ asset($item->thumbnail ? 'storage/' . $item->thumbnail : 'images/img_not_found.png') }}"
                                            alt="{{ $item->title }}" class="news-img news-img--list">
                                    </div>
                                    <div class="col-8">
                                        <div class="news-content news-content--list">
                                            <h5 class="news-title news-title--list">{{ Str::limit($item->title, 60) }}
                                            </h5>
                                            <p class="news-excerpt news-excerpt--featured">
                                                {!! Str::limit(preg_replace('/<img[^>]*>/i', '', $item->content), 300) !!}
                                            </p>
                                            <div class="news-meta news-meta--list">
                                                <span class="news-date">
                                                    <i class="bi bi-calendar3 me-1"></i>
                                                    {{ \Carbon\Carbon::parse($item->published_at)->format('d M Y') }}
                                                </span>
                                                <a href="{{ route('berita.detail', $item->slug) }}"
                                                    class="news-link news-link--sm">
                                                    Baca <i class="bi bi-arrow-right"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>
