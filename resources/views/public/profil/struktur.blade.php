@extends('public.layouts.app')

@section('title', 'Struktur Organisasi')

@section('content')

    <!-- HERO -->
    <section class="page-hero" style="--hero-bg: url('{{ asset('img/background/bg-1.jpg') }}');">
        <div class="container position-relative">
            <h1 class="page-title">Struktur Organisasi</h1>

            <nav class="breadcrumb-wrapper">
                <a href="{{ url('/') }}">Home</a>
                <span>›</span>
                <a href="#">Profil</a>
                <span>›</span>
                <span class="active">Struktur Organisasi</span>
            </nav>
        </div>
    </section>

    <!-- CONTENT -->
    <section class="py-5 fade-up">
        <div class="container">
            <div class="card">
                <div class="card-body">
                    <!-- PIMPINAN -->
                    <div class="text-center mb-4">
                        <div class="org-card mx-auto">
                            <h4 class="section-sub-title"><u>Kepala Dinas</u></h4>
                        </div>
                    </div>

                    <!-- SEKRETARIS -->
                    <div class="text-center mb-5">
                        <div class="org-card mx-auto">
                            <h5 class="section-sub-title"><u>Sekretaris</u></h5>
                            <span class="d-block mt-2">
                                Sub Bagian Umum & Kepegawaian<br>
                                Sub Bagian Keuangan<br>
                                Sub Bagian Program
                            </span>
                        </div>
                    </div>

                    <!-- BIDANG -->
                    <div class="row g-4">

                        <!-- Penyuluhan -->
                        <div class="col-md-6 col-lg-4">
                            <div class="org-card h-100">
                                <h6 class="section-sub-title"><u>Kepala Bidang Penyuluhan</u></h6>
                                <ul>
                                    <li>Substansi Kelembagaan</li>
                                    <li>Substansi Pengembangan SDM Pertanian</li>
                                    <li>Substansi Program Penyuluhan & Supervisi</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Hortikultura -->
                        <div class="col-md-6 col-lg-4">
                            <div class="org-card h-100">
                                <h6 class="section-sub-title"><u>Kepala Bidang Hortikultura & Perkebunan</u></h6>
                                <ul>
                                    <li>Substansi Hortikultura</li>
                                    <li>Substansi Perkebunan</li>
                                    <li>Substansi PSDT Hortikultura & Perkebunan</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Tanaman Pangan -->
                        <div class="col-md-6 col-lg-4">
                            <div class="org-card h-100">
                                <h6 class="section-sub-title"><u>Kepala Bidang Tanaman Pangan</u></h6>
                                <ul>
                                    <li>Substansi Tanaman Serealia</li>
                                    <li>Substansi Aneka Kacang & Umbi</li>
                                    <li>Substansi Pengembangan SD & Teknologi Tanaman Pangan</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Pengolahan -->
                        <div class="col-md-6 col-lg-4">
                            <div class="org-card h-100">
                                <h6 class="section-sub-title"><u>Kepala Bidang Pengolahan Hasil & Konsumsi Pangan</u></h6>
                                <ul>
                                    <li>Substansi Pengolahan Hasil</li>
                                    <li>Substansi Konsumsi Pangan</li>
                                    <li>Substansi Keamanan Pangan</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Cadangan -->
                        <div class="col-md-6 col-lg-4">
                            <div class="org-card h-100">
                                <h6 class="section-sub-title"><u>Kepala Bidang Cadangan & Distribusi Pangan</u></h6>
                                <ul>
                                    <li>Substansi Cadangan Pangan</li>
                                    <li>Substansi Distribusi & Harga Pangan</li>
                                    <li>Substansi Pengentasan Rawan Pangan</li>
                                </ul>
                            </div>
                        </div>

                        <!-- UPTD & JF -->
                        <div class="col-md-6 col-lg-4">
                            <div class="org-card h-100">
                                <h6 class="section-sub-title"><u>Unit Pelaksana Teknis</u></h6>
                                <ul>
                                    <li>UPTD</li>
                                    <li>Kelompok Jabatan Fungsional</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
