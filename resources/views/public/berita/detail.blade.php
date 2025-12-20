@extends('public.layouts.app')

@section('title', 'Detail Berita')

@section('content')

    <!-- HERO -->
    <section class="page-hero" style="--hero-bg: url('{{ asset('img/background/bg-2.jpg') }}');">
        <div class="container position-relative">
            <h1 class="page-title">Judul Berita</h1>

            <nav class="breadcrumb-wrapper">
                <a href="{{ url('/') }}">Beranda</a>
                <span>›</span>
                <a href="#">Berita</a>
                <span>›</span>
                <span class="active">Detail</span>
            </nav>
        </div>
    </section>

    <!-- CONTENT -->
    <section class="py-5">
        <div class="container">
            <div class="row">

                <!-- KONTEN BERITA -->
                <div class="col-lg-8">

                    <article class="card border-0 shadow-sm mb-4">
                        <div class="card-body p-4">

                            <!-- META -->
                            <div class="mb-3 text-muted small">
                                <i class="bi bi-calendar"></i> 18 Desember 2025
                                &nbsp;|&nbsp;
                                <i class="bi bi-person"></i> Admin
                                &nbsp;|&nbsp;
                                <i class="bi bi-eye"></i> 1.245x dilihat
                            </div>

                            <!-- GAMBAR -->
                            <img src="https://picsum.photos/900/450" class="img-fluid rounded mb-4" alt="Berita">

                            <!-- ISI -->
                            <p>
                                Dinas Ketahanan Pangan dan Pertanian Kabupaten Kuningan
                                melaksanakan kegiatan dalam rangka meningkatkan ketahanan
                                pangan dan kesejahteraan petani. Kegiatan ini dihadiri oleh
                                berbagai unsur terkait dan masyarakat.
                            </p>

                            <p>
                                Program ini bertujuan untuk memperkuat sektor pertanian
                                melalui optimalisasi lahan, peningkatan kualitas produksi,
                                serta pendampingan kepada petani secara berkelanjutan.
                            </p>

                            <p>
                                Dengan adanya kegiatan ini diharapkan mampu meningkatkan
                                hasil pertanian, menjaga stabilitas pangan, serta mendukung
                                pembangunan daerah Kabupaten Kuningan.
                            </p>

                        </div>
                    </article>

                    <!-- NAVIGASI -->
                    <div class="d-flex justify-content-between">
                        <a href="#" class="btn btn-outline-secondary btn-sm">
                            ← Berita Sebelumnya
                        </a>
                        <a href="#" class="btn btn-outline-secondary btn-sm">
                            Berita Selanjutnya →
                        </a>
                    </div>

                </div>

                <!-- SIDEBAR -->
                <div class="col-lg-4">

                    <!-- SEARCH -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <h6 class="fw-semibold mb-3">Cari Berita</h6>
                            <form>
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Judul berita...">
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
