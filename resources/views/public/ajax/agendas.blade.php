@php
    $i = 0;
@endphp
{{-- Loop agenda --}}
@foreach ($agenda as $item)
    @php
        $i++;
    @endphp
    <div class="card border-0 shadow-sm mb-4 agenda-card" data-aos="fade-up" data-aos-delay="{{ $i * 100 }}">
        <div class="row g-0 align-items-center">

            <!-- FOTO -->
            <div class="col-md-4">
                <img src="{{ asset($item->thumbnail ? 'storage/' . $item->thumbnail : 'images/img_not_found.png') }}"
                    class="img-fluid agenda-img" alt="Agenda {{ $i }}">
            </div>

            <!-- KONTEN -->
            <div class="col-md-8">
                <div class="card-body">
                    <small class="text-muted">
                        <i class="bi bi-calendar-event"></i>
                        {{ \Carbon\Carbon::parse($item->date)->format('d M Y') }}
                        &nbsp;|&nbsp;
                        <i class="bi bi-geo-alt"></i>
                        {{ $item->location }}
                    </small>

                    <h5 class="mt-2 fw-semibold">
                        {{ Str::limit($item->title, 40) }}
                    </h5>

                    <p class="text-muted small">
                        {!! Str::limit(
                            preg_replace('/<img[^>]*>/i', '', $item->content),
                            160
                        ) !!}                        
                    </p>

                    <a href="{{ route('agenda.detail', $item->slug) }}" class="btn btn-sm btn-outline-success">
                        <i class="bi bi-eye me-1"></i>
                        Lihat Detail Agenda
                    </a>
                </div>
            </div>

        </div>
    </div>
@endforeach
