@extends('public.layouts.app')

@section('title', 'Home')

@section('content')
    <!-- Banner Carousel -->
    <section class="banner-carousel fade-up">
        <div id="bannerCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#bannerCarousel" data-bs-slide-to="0" class="active"></button>
                <button type="button" data-bs-target="#bannerCarousel" data-bs-slide-to="1"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="https://images.unsplash.com/photo-1500382017468-9049fed747ef?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80"
                        class="d-block w-100" alt="Pertanian">
                    <div class="carousel-caption d-none d-md-block">
                        <h2>Selamat Datang di Website Dinas Pertanian</h2>
                        <p>Membangun pertanian yang maju, mandiri, dan modern</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="https://images.unsplash.com/photo-1574943320219-553eb213f72d?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80"
                        class="d-block w-100" alt="Petani">
                    <div class="carousel-caption d-none d-md-block">
                        <h2>Meningkatkan Kesejahteraan Petani</h2>
                        <p>Program pemberdayaan dan pelatihan untuk petani</p>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#bannerCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>
    </section>

    <!-- Sejarah -->
    <section id="profil" class="py-5 bg-light fade-up">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-4 text-center mb-4 mb-md-0">
                    <img src="{{ asset('img/dinas/bg-1.jpg') }}" alt="Ketua Dinas Pertanian" class="profile-img">
                    <h4 class="section-title text-center mt-3 mb-1">Dinas Ketahanan Pangan dan Pertanian</h4>
                    {{-- <h4 class="text-warning">Kabupaten Kuningan</h4> --}}
                </div>
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="section-title text-center">Sejarah Singkat:</h5>
                            <p class="card-text">
                                "Sejarah Singkat Dinas Ketahanan Pangan dan Pertanian Kabupaten Kuningan

                                Dinas Ketahanan Pangan dan Pertanian Kabupaten Kuningan sebagai salah satu wilayah di Jawa
                                Barat beralamat di Jalan Raya Syech Manglayang No. 3 Windujanten Kecamatan Kadugede
                                Kabupaten Kuningan Jawa Barat 45561, Telp/Fax. (0232) 877096, yang mempunyai kawasan
                                budidaya lahan sawah yang relatif luas. Dalam rangka ketahanan pangan maka fungsi lahan
                                dikawasan pertanian lahan basah, terutama lahan sawah beririgasi teknis harus tetap
                                dipertahankan, Selain itu, dinas pertanian juga memiliki beberapa tugas dan fungsi lain
                                seperti penyuluhan pertanian, merumuskan kebijakan pertanian, memutus kebijakan bidang
                                pangan, administrasi ketatausahaan pertanian, pembinaan teknis pada pihak-pihak bidang
                                pertanian, memastikan ketersedian pupuk pertanian, hingga penyaluran bantuan alat dan mesin
                                pendukung pertanian. Selain itu, dinas pertanian juga adalah penjamis kesejahteraan petani
                                melalui program memastikan memberikan asuransi usaha tani padi (AUPT). Oleh karenanya,
                                terkait dengan fungsi dan tugasnya, dinas pertanian memiliki wewenang untuk mengeluarkan
                                surat izin pertanian, izin alih fungsi, izin usaha pertanian, pembukaan lahan dan izin
                                lainnya terkait pertanian."
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Agenda Kegiatan -->
    <section id="agenda" class="py-5 bg-soft-green fade-up">
        <div class="container">
            <h2 class="section-title text-center">Agenda Kegiatan</h2>
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="agenda-item">
                                <h5 class="mb-1">Pelatihan Pertanian Organik</h5>
                                <p class="text-muted mb-2">
                                    <i class="bi bi-calendar-event me-1"></i> 15 Januari 2024
                                    <i class="bi bi-clock ms-3 me-1"></i> 08:00 - 15:00 WIB
                                </p>
                                <p class="mb-1">
                                    <i class="bi bi-geo-alt me-1"></i> Balai Desa Sukamaju
                                </p>
                                <p>Pelatihan tentang teknik pertanian organik untuk peningkatan hasil panen</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="agenda-item">
                                <h5 class="mb-1">Penyerahan Bantuan Bibit Unggul</h5>
                                <p class="text-muted mb-2">
                                    <i class="bi bi-calendar-event me-1"></i> 20 Januari 2024
                                    <i class="bi bi-clock ms-3 me-1"></i> 09:00 - 12:00 WIB
                                </p>
                                <p class="mb-1">
                                    <i class="bi bi-geo-alt me-1"></i> Kantor Dinas Pertanian
                                </p>
                                <p>Penyerahan bantuan bibit tanaman unggul kepada kelompok tani</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="agenda-item">
                                <h5 class="mb-1">Workshop Pengolahan Hasil Pertanian</h5>
                                <p class="text-muted mb-2">
                                    <i class="bi bi-calendar-event me-1"></i> 25 Januari 2024
                                    <i class="bi bi-clock ms-3 me-1"></i> 10:00 - 16:00 WIB
                                </p>
                                <p class="mb-1">
                                    <i class="bi bi-geo-alt me-1"></i> Aula Kecamatan
                                </p>
                                <p>Workshop pengolahan produk pertanian untuk nilai tambah</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="agenda-item">
                                <h5 class="mb-1">Monitoring Program Pertanian</h5>
                                <p class="text-muted mb-2">
                                    <i class="bi bi-calendar-event me-1"></i> 30 Januari 2024
                                    <i class="bi bi-clock ms-3 me-1"></i> 07:00 - 17:00 WIB
                                </p>
                                <p class="mb-1">
                                    <i class="bi bi-geo-alt me-1"></i> Desa Tani Makmur
                                </p>
                                <p>Monitoring dan evaluasi program pembangunan pertanian</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center mt-4">
                <a href="#" class="btn btn-primary">
                    <i class="bi bi-calendar-week me-2"></i>Lihat Agenda Lengkap
                </a>
            </div>
        </div>
    </section>

    <!-- Berita -->
    <section id="berita" class="py-5 bg-light fade-up">
        <div class="container">
            <h2 class="section-title text-center">Berita Terbaru</h2>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <img src="https://images.unsplash.com/photo-1592982537447-7444dc31f8e8?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80"
                            class="card-img-top" alt="Berita 1">
                        <div class="card-body">
                            <span class="badge bg-success mb-2">Teknologi</span>
                            <h5 class="card-title">Penerapan Smart Farming di Desa Makmur</h5>
                            <p class="card-text">
                                Dinas Pertanian memperkenalkan sistem smart farming untuk
                                meningkatkan produktivitas pertanian...
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="bi bi-calendar me-1"></i> 10 Jan 2024
                                </small>
                                <a href="#" class="btn btn-sm btn-outline-success">Baca Selengkapnya</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <img src="https://images.unsplash.com/photo-1574943320219-553eb213f72d?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80"
                            class="card-img-top" alt="Berita 2">
                        <div class="card-body">
                            <span class="badge bg-success mb-2">Program</span>
                            <h5 class="card-title">Bantuan Bibit Unggul untuk 1000 Petani</h5>
                            <p class="card-text">
                                Program distribusi bibit unggul telah menjangkau 1000 petani
                                di wilayah kecamatan se-Kabupaten...
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="bi bi-calendar me-1"></i> 5 Jan 2024
                                </small>
                                <a href="#" class="btn btn-sm btn-outline-success">Baca Selengkapnya</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <img src="https://images.unsplash.com/photo-1500382017468-9049fed747ef?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80"
                            class="card-img-top" alt="Berita 3">
                        <div class="card-body">
                            <span class="badge bg-success mb-2">Prestasi</span>
                            <h5 class="card-title">Petani Lokal Raih Penghargaan Nasional</h5>
                            <p class="card-text">
                                Kelompok tani binaan Dinas Pertanian meraih penghargaan
                                nasional dalam lomba inovasi pertanian...
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="bi bi-calendar me-1"></i> 2 Jan 2024
                                </small>
                                <a href="#" class="btn btn-sm btn-outline-success">Baca Selengkapnya</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center mt-4">
                <a href="#" class="btn btn-primary">
                    <i class="bi bi-newspaper me-2"></i>Lihat Semua Berita
                </a>
            </div>
        </div>
    </section>

    <section id="pengunjung" class="py-5 bg-soft-green fade-up">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title text-center">Statistik Pengunjung dan Survey Kepuasan</h2>
            </div>

            <div class="row g-4">

                <!-- SURVEY -->
                <div class="col-lg-6">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body">

                            <h6 class="fw-semibold section-sub-title mb-3">
                                Survey Kepuasan Pengunjung
                            </h6>

                            <div class="row align-items-center">
                                <div class="col-md-6">

                                    <p class="small fw-semibold">
                                        Menurut Anda, bagaimana tampilan, fitur dan isi konten dari website ini?
                                    </p>

                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="survey">
                                        <label class="form-check-label">Sangat Bagus</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="survey">
                                        <label class="form-check-label">Bagus</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="survey">
                                        <label class="form-check-label">Cukup Bagus</label>
                                    </div>
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="radio" name="survey">
                                        <label class="form-check-label">Kurang</label>
                                    </div>

                                    <button class="btn btn-dark w-100">
                                        Kirim
                                    </button>
                                </div>

                                <div class="col-md-6">
                                    <div id="chartSurvey"></div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- STAT PENGUNJUNG -->
                <div class="col-lg-6">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body">
                            <h6 class="fw-semibold section-sub-title mb-3">Pengunjung</h6>

                            <ul class="list-group list-group-flush small">
                                <li class="list-group-item d-flex justify-content-between">
                                    Total <span class="badge bg-secondary">128</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    Tahun Ini <span class="badge bg-secondary">80</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    Bulan Ini <span class="badge bg-secondary">32</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    Minggu Ini <span class="badge bg-secondary">12</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    Hari Ini <span class="badge bg-danger">4</span>
                                </li>
                            </ul>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Kontak -->
    <section id="kontak" class="py-5 bg-light fade-up">
        <div class="container">
            <h2 class="section-title text-center">Kontak Kami</h2>
            <div class="row">
                <div class="col-md-8 mx-auto">
                    <div class="card">
                        <div class="card-body">
                            <form>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="nama" class="form-label">Nama Lengkap</label>
                                        <input type="text" class="form-control" id="nama" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="subjek" class="form-label">Subjek</label>
                                    <input type="text" class="form-control" id="subjek" required>
                                </div>
                                <div class="mb-3">
                                    <label for="pesan" class="form-label">Pesan</label>
                                    <textarea class="form-control" id="pesan" rows="5" required></textarea>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-send me-2"></i>Kirim Pesan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@push('after-script')
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            var options = {
                chart: {
                    type: 'donut',
                    height: 260
                },
                series: [20, 40, 20, 20],
                labels: ['Cukup Bagus', 'Bagus', 'Sangat Bagus', 'Kurang'],
                legend: {
                    position: 'bottom',
                    fontSize: '13px'
                },
                dataLabels: {
                    enabled: true,
                    formatter: function(val) {
                        return val.toFixed(0) + "%";
                    }
                }
            };

            var chart = new ApexCharts(
                document.querySelector("#chartSurvey"),
                options
            );

            chart.render();
        });
    </script>
@endpush
