@extends('public.layouts.app')

@section('title', 'Tugas Pukok dan Fungsi')

@section('content')

    <!-- HERO -->
    <section class="page-hero" style="--hero-bg: url('{{ asset('img/background/bg-2.jpg') }}');">
        <div class="container position-relative">
            <h1 class="page-title">Tugas Pukok dan Fungsi</h1>

            <nav class="breadcrumb-wrapper">
                <a href="{{ url('/') }}">Home</a>
                <span>›</span>
                <a href="#">Profil</a>
                <span>›</span>
                <span class="active">Tugas Pukok dan Fungsi</span>
            </nav>
        </div>
    </section>

    <!-- CONTENT -->
    <section class="py-5 fade-up">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">

                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4 p-md-5">

                            <h3 class="section-title text-center">
                                Dinas Ketahanan Pangan dan Pertanian Kabupaten Kuningan
                            </h3>
                            <h5 class="mb-3 section-sub-title text-center">Tugas Pokok dan Fungsi</h5>

                            <p>
                                Berdasarkan Peraturan Bupati Kuningan Nomor 73 Tahun 2019 tentang Kedudukan, Susunan
                                Organisasi, Tugas Pokok, Fungsi dan Uraian Tugas serta Tata Kerja Dinas Ketahanan Pangan dan
                                Pertanian Kabupaten Kuningan mempunyai Tugas Pokok.
                            </p>

                            <p>
                                “Melaksanakan urusan pemerintahan yang menjadi kewenangan daerah dan tugas pembantuan di
                                bidang Ketahanan Pangan dan Pertanian“.
                            </p>

                            <h6 class="section-sub-title">
                                Sedangkan untuk menyelenggarakan tugas pokok tersebut di atas Dinas Pertanian mempunyai
                                fungsi yaitu :
                            </h6>

                            <ul>
                                <li>Perumusan, penetapan dan pelaksanaan kebijakan daerah dibidang ketahanan pangan dan
                                    pertanian.</li>
                                <li>Pelaksanaan kebijakan daerah dibidang ketahanan pangan dan pertanian.</li>
                                <li>Pengkoordinasian, fasilitasi dan pengendalian pelaksanaan tugas dinas.</li>
                                <li>Pembinaan terhadap Unit Pelaksana Teknis Dinas.</li>
                                <li>Pelaksanaan fungsi lain yang diberikan oleh Bupati.</li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

@endsection
