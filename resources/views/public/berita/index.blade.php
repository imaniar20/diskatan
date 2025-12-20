@extends('public.layouts.app')

@section('title', 'Berita')

@section('content')

    <!-- HERO -->
    <section class="page-hero" style="--hero-bg: url('{{ asset('img/background/bg-2.jpg') }}');">
        <div class="container position-relative">
            <h1 class="page-title">Berita</h1>

            <nav class="breadcrumb-wrapper">
                <a href="{{ url('/') }}">Home</a>
                <span>›</span>
                <span class="active">Berita</span>
            </nav>
        </div>
    </section>

    <!-- CONTENT -->
    <section class="py-5">
        <div class="container">
            <div class="row">

                <!-- LIST BERITA -->
                <div class="col-lg-8">
                    <div class="row g-4">

                        {{-- Loop berita --}}
                        @for ($i = 1; $i <= 6; $i++)
                            <div class="col-md-6 col-lg-4">
                                <div class="card h-100 border-0 shadow-sm berita-card">
                                    <img src="https://picsum.photos/400/250?random={{ $i }}" class="card-img-top"
                                        alt="Berita">

                                    <div class="card-body d-flex flex-column">
                                        <small class="text-muted mb-1">
                                            <i class="bi bi-calendar-event"></i> 12 Desember 2025
                                        </small>

                                        <h6 class="card-title fw-semibold">
                                            Judul Berita {{ $i }}
                                        </h6>

                                        <p class="card-text small text-muted">
                                            Ringkasan singkat berita agar rapi dan tidak kepanjangan...
                                        </p>

                                        <a href="#" class="mt-auto btn btn-sm btn-outline-success">
                                            Baca Selengkapnya
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endfor

                    </div>

                    <!-- PAGINATION -->
                    <div class="mt-4">
                        <nav>
                            <ul class="pagination pagination-sm">
                                <li class="page-item disabled">
                                    <span class="page-link">‹</span>
                                </li>
                                <li class="page-item active">
                                    <span class="page-link">1</span>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="#">2</a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="#">›</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>

                <!-- SIDEBAR -->
                <div class="col-lg-4">

                    <!-- SEARCH -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <h6 class="fw-semibold mb-3">Cari Berita</h6>
                            <form action="#" method="GET">
                                <div class="input-group">
                                    <input type="text" name="q" class="form-control"
                                        placeholder="Ketik judul berita...">
                                    <button class="btn btn-success">
                                        <i class="bi bi-search"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <h6 class="fw-semibold mb-3">Berita Terbaru</h6>

                            @for ($i = 1; $i <= 5; $i++)
                                <a href="#" class="sidebar-news-item d-flex gap-3 mb-3 text-decoration-none">
                                    <!-- IMAGE -->
                                    <img src="https://picsum.photos/80/80?random={{ $i }}" alt=""
                                        class="sidebar-news-img">
                                    <!-- TEXT -->
                                    <div class="flex-grow-1">
                                        <div class="fw-semibold small text-dark">
                                            Judul Berita Terbaru {{ $i }}
                                        </div>
                                        <div class="text-muted small">
                                            12 Jan 2025
                                        </div>
                                    </div>
                                </a>
                            @endfor
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h6 class="fw-semibold mb-3">Paling Banyak Dilihat</h6>

                            @for ($i = 1; $i <= 5; $i++)
                                <a href="#" class="sidebar-news-item d-flex gap-3 mb-3 text-decoration-none">
                                    <!-- IMAGE -->
                                    <img src="https://picsum.photos/80/80?blur=1&random={{ $i + 5 }}" alt=""
                                        class="sidebar-news-img">

                                    <!-- TEXT -->
                                    <div class="flex-grow-1">
                                        <div class="fw-semibold small text-dark">
                                            Judul Berita Populer {{ $i }}
                                        </div>
                                        <div class="text-muted small">
                                            <i class='bx bx-show-alt'></i> {{ rand(120, 980) }} kali dilihat
                                        </div>
                                    </div>
                                </a>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
