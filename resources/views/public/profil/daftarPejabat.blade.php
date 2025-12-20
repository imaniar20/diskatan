@extends('public.layouts.app')

@section('title', 'Daftar Pejabat')

@section('content')

    <!-- HERO -->
    <section class="page-hero" style="--hero-bg: url('{{ asset('img/background/bg-2.jpg') }}');">
        <div class="container position-relative">
            <h1 class="page-title">Daftar Pejabat</h1>

            <nav class="breadcrumb-wrapper">
                <a href="{{ url('/') }}">Beranda</a>
                <span>›</span>
                <a href="#">Profil</a>
                <span>›</span>
                <span class="active">Daftar Pejabat</span>
            </nav>
        </div>
    </section>

    <!-- CONTENT -->
    <section class="py-5 fade-up">
        <div class="container">

            <div class="card border-0 shadow-sm">
                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table table-striped datatable display nowrap">
                            <thead class="table-success">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Lengkap</th>
                                    <th>Pangkat</th>
                                    <th>Pangkat / Gol</th>
                                    <th>Jabatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Contoh static, nanti bisa dari DB --}}
                                <tr>
                                    <td>1</td>
                                    <td>Dr. WAHYU HIDAYAH, M.Si.</td>
                                    <td>Pembina Utama Muda</td>
                                    <td>IV/c</td>
                                    <td>Kepala Dinas</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
