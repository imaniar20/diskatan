@if ($paginator->hasPages())
    <nav data-aos="fade-up" data-aos-delay="600">
        <ul class="pagination pagination-sm justify-content-center">
            @php
                $current = $paginator->currentPage();
                $last = $paginator->lastPage();
                $limit = 5;

                $half = floor($limit / 2);
                $start = max(1, $current - $half);
                $end = min($last, $start + $limit - 1);

                if ($end - $start + 1 < $limit) {
                    $start = max(1, $end - $limit + 1);
                }
            @endphp

            {{-- Previous Page --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link">‹ Prev</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}">‹ Prev</a>
                </li>
            @endif

            {{-- First --}}
            @if ($start > 1)
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->url(1) }}">1</a>
                </li>
                @if ($start > 2)
                    <li class="page-item disabled">
                        <span class="page-link">…</span>
                    </li>
                @endif
            @endif

            {{-- Page Numbers --}}
            @for ($page = $start; $page <= $end; $page++)
                @if ($page == $current)
                    <li class="page-item active">
                        <span class="page-link">{{ $page }}</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->url($page) }}">{{ $page }}</a>
                    </li>
                @endif
            @endfor

            {{-- Last --}}
            @if ($end < $last)
                @if ($end < $last - 1)
                    <li class="page-item disabled">
                        <span class="page-link">…</span>
                    </li>
                @endif
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->url($last) }}">{{ $last }}</a>
                </li>
            @endif

            {{-- Next Page --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}">Next ›</a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link">Next ›</span>
                </li>
            @endif

        </ul>
    </nav>
@endif
