@php
    $i = 0;
@endphp
<style>
    .kategori-badge {
        position: absolute;
        top: 12px;
        left: 12px;
        z-index: 2;
        background: rgba(45, 106, 79, 0.9);
        color: #fff;
        padding: 6px 12px;
        font-size: 0.7rem;
        border-radius: 20px;
        backdrop-filter: blur(4px);
    }

    .berita-card {
        overflow: hidden;
    }

    .kategori-badge {
        background: linear-gradient(135deg, #2d6a4f, #40916c);
    }
</style>
{{-- Loop agenda --}}
@foreach ($news as $item)
    @php
        $i++;
    @endphp
    <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ $i * 100 }}">
        <div class="card h-100 border-0 shadow-sm berita-card position-relative">
            <span class="badge kategori-badge">
                {{ $item->program->name ?? 'Umum' }}
            </span>

            <img src="{{ asset($item->thumbnail ? 'storage/' . $item->thumbnail : 'images/img_not_found.png') }}"
                class="card-img-top" alt="Berita {{ $i }}">

            <div class="card-body d-flex flex-column">
                <small class="text-muted mb-2">
                    {{ \Carbon\Carbon::parse($item->published_at)->format('d M Y') }}
                </small>

                <h6 class="card-title fw-semibold">
                    {{ Str::limit($item->title, 40) }}
                </h6>

                <p class="card-text small text-muted">
                    {!! Str::limit(preg_replace('/<img[^>]*>/i', '', $item->content), 160) !!}
                </p>

                <a href="{{ route('berita.detail', $item->slug) }}" class="mt-auto btn btn-sm btn-outline-success">
                    <i class="bi bi-arrow-right-circle me-1"></i>
                    Baca Selengkapnya
                </a>
            </div>
        </div>
    </div>
@endforeach
