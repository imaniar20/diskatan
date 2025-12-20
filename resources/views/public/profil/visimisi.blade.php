@extends('public.layouts.app')

@section('title', 'Visi dan Misi')

@section('content')

    <style>
        .misi-list {
            padding-left: 1.2rem;
        }

        .misi-list li {
            margin-bottom: .55rem;
            line-height: 1.7;
        }
    </style>
    <!-- HERO -->
    <section class="page-hero" style="--hero-bg: url('{{ asset('img/background/bg-1.jpg') }}');">
        <div class="container position-relative">
            <h1 class="page-title">Visi dan Misi</h1>

            <nav class="breadcrumb-wrapper">
                <a href="{{ url('/') }}">Home</a>
                <span>›</span>
                <a href="#">Profil</a>
                <span>›</span>
                <span class="active">Visi dan Misi</span>
            </nav>
        </div>
    </section>

    <!-- VISI -->
    <section class="py-5">
        <div class="container mb-4">
            <div class="row justify-content-center">
                <div class="col-lg-10" data-aos="fade-up">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4 p-md-5">
                            <h3 class="mb-3 text-success">
                                <i class="bi bi-eye me-2"></i>Visi
                            </h3>
                            <p class="mb-0 fs-5">
                                Kuningan MAJU (Makmur, Agamis, Pinunjul) Berbasis Desa Tahun 2023
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10" data-aos="fade-up" data-aos-delay="100">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4 p-md-5">
                            <h3 class="mb-4 text-success">
                                <i class="bi bi-bullseye me-2"></i>Misi
                            </h3>
                            <ol class="misi-list">
                                <li>Membangun Tata Kelola Pemerintahan Daerah Yang Profesional, Efektif, Demokratis, Dan Terpercaya Dengan Jiwa Kepemimpinan Nu SAJATI.</li>
                                <li>Mewujudkan Masyarakat Kuningan Nu SAJATI Dalam Kehidupan Beragama Dan Bernegara Dalam Bingkai Kebangsaan Dan Kebhinekaan.</li>
                                <li>Mewujudkan Manajemen Layanan Pendidikan Dan Kesehatan Yang Merata, Adil, Berkualitas Dan Berkelanjutan Dalam Menciptakan Sumber Daya Manusia Nu SAJATI.</li>
                                <li>Mewujudkan Pembangunan Kawasan Perdesaan Berbasis Pertanian, Wisata, Budaya Dan Potensi Lokal Untuk Mempercepat Pertumbuhan Serta Pemerataan Ekonomi Rakyat.</li>
                                <li>Mewujudkan Pemerataan Infrastruktur Untuk Mendorong Investasi Dan Penciptaan Lapangan Kerja Dalam Lingkungan Yang Lestari.</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
