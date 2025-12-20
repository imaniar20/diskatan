@extends('public.layouts.app')

@section('title', 'Agenda')

@section('content')

    <!-- HERO -->
    <section class="page-hero" style="--hero-bg: url('{{ asset('img/background/bg-1.jpg') }}');">
        <div class="container position-relative">
            <h1 class="page-title">Agenda Kegiatan</h1>

            <nav class="breadcrumb-wrapper">
                <a href="{{ url('/') }}">Home</a>
                <span>›</span>
                <span class="active">Agenda</span>
            </nav>
        </div>
    </section>

    <!-- CONTENT -->
    <section class="py-5">
        <div class="container">
            <div class="row">

                <!-- LIST AGENDA -->
                <div class="col-lg-8">

                    {{-- Loop agenda --}}
                    @for ($i = 1; $i <= 5; $i++)
                        <div class="card border-0 shadow-sm mb-4 agenda-card">
                            <div class="row g-0 align-items-center">

                                <!-- FOTO -->
                                <div class="col-md-4">
                                    <img src="https://picsum.photos/500/350?random={{ $i }}"
                                        class="img-fluid h-100 agenda-img" alt="Agenda">
                                </div>

                                <!-- KONTEN -->
                                <div class="col-md-8">
                                    <div class="card-body">
                                        <small class="text-muted">
                                            <i class="bi bi-calendar-event"></i>
                                            20 Desember 2025
                                            &nbsp;|&nbsp;
                                            <i class="bi bi-geo-alt"></i>
                                            Aula Dinas
                                        </small>

                                        <h5 class="mt-2 fw-semibold">
                                            Judul Agenda Kegiatan {{ $i }}
                                        </h5>

                                        <p class="text-muted small">
                                            Deskripsi singkat agenda kegiatan. Jelaskan tujuan, peserta,
                                            dan gambaran umum kegiatan agar informatif tapi tetap ringkas.
                                        </p>

                                        <a href="#" class="btn btn-sm btn-outline-success">
                                            Lihat Detail Agenda
                                        </a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    @endfor

                    <!-- PAGINATION -->
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

                <!-- SIDEBAR -->
                <div class="col-lg-4">

                    <!-- SEARCH -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <h6 class="fw-semibold mb-3">Cari Agenda</h6>
                            <form>
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Judul agenda...">
                                    <button class="btn btn-success">
                                        <i class="bi bi-search"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <h6 class="fw-semibold mb-3">Agenda Terdekat</h6>

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
                            <h6 class="fw-semibold mb-3">Agenda Paling Banyak Dilihat</h6>

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
