<!-- Top Date Time Bar -->
<div class="top-bar" id="top-active">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <i class="bi bi-calendar-event"></i>
                <span id="current-date"></span>
            </div>
            <div class="flex items-center gap-3">
                <i class="bi bi-clock"></i>
                <span id="current-time"></span>
                <span> | </span>
                <a href="https://www.facebook.com/diskatankuningan/" target="_blank" class="social"><i class='bx bxl-facebook'></i></a>
                <a href="https://www.instagram.com/diskatankuningan/" target="_blank" class="social"><i class='bx bxl-instagram'></i></a>
                <a href="https://www.tiktok.com/@diskatankuningan" target="_blank" class="social"><i class='bx bxl-tiktok'></i></a>
                <a href="https://www.youtube.com/@diskatankuningan" target="_blank" class="social"><i class='bx bxl-youtube'></i></a>
            </div>
        </div>
    </div>
</div>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark sticky-top" id="header-active">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
            <img src="{{ asset('img/logo/logo.png') }}" alt="" class="me-3" style="width: 50px;">
            <div class="lh-sm">
                <span class="brand-subtitle">Dinas Ketahanan Pangan dan Pertanian</span><br>
                <span class="brand-title text-warning">Kabupaten Kuningan</span>
            </div>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link {{ $menu == 'Home' ? 'active' : '' }}" href="{{ url('/') }}">Home</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link d-flex align-items-center gap-1 dropdown-toggle-custom {{ $title == 'Profil' ? 'active' : '' }}"
                        href="#" id="profilDropdown" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Profil
                        <i class="bi bi-chevron-down dropdown-icon"></i>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="profilDropdown">
                        <li><a class="dropdown-item {{ $menu == 'Visi & Misi' ? 'active' : '' }}"
                                href="{{ url('/visi-misi') }}">Visi & Misi</a></li>
                        <li><a class="dropdown-item {{ $menu == 'Sejarah' ? 'active' : '' }}"
                                href="{{ url('/sejarah') }}">Sejarah</a></li>
                        <li><a class="dropdown-item {{ $menu == 'Struktur Organisasi' ? 'active' : '' }}"
                                href="{{ url('/struktur') }}">Struktur Organisasi</a></li>
                        <li><a class="dropdown-item {{ $menu == 'Tugas Pokok dan Fungsi' ? 'active' : '' }}"
                                href="{{ url('/tupoksi') }}">Tugas Pokok dan Fungsi</a></li>
                        <li><a class="dropdown-item {{ $menu == 'Daftar Pejabat' ? 'active' : '' }}"
                                href="{{ url('/daftar-pejabat') }}">Daftar Pejabat</a></li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ $menu == 'Agenda' ? 'active' : '' }}" href="{{ url('/agenda') }}">Agenda</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ $menu == 'Berita' ? 'active' : '' }}" href="{{ url('/berita') }}">Berita</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ $menu == 'Program' ? 'active' : '' }}" href="{{ url('/program') }}">Program</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ $menu == 'Data' ? 'active' : '' }}" href="javascript:void(0);">Data dan Informasi</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ $menu == 'Layanan' ? 'active' : '' }}" href="javascript:void(0);">Layanan</a>
                </li>

                {{-- <li class="nav-item dropdown">
                    <a class="nav-link d-flex align-items-center gap-1 dropdown-toggle-custom {{ $menu == 'Informasi' ? 'active' : '' }}"
                        href="#" id="informasiDropdown" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Informasi
                        <i class="bi bi-chevron-down dropdown-icon"></i>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="informasiDropdown">
                        <li><a class="dropdown-item {{ $menu == 'Komoditas Pertanian' ? 'active' : '' }}"
                                href="#struktur">Komoditas Pertanian</a></li>
                        <li><a class="dropdown-item {{ $menu == 'Harga Komoditas Pertanian' ? 'active' : '' }}"
                                href="#struktur">Harga Komoditas Pertanian</a></li>
                        <li><a class="dropdown-item {{ $menu == 'Ruang Lingkup Pertanian' ? 'active' : '' }}"
                                href="#struktur">Ruang Lingkup Pertanian</a></li>
                    </ul>
                </li> --}}

                {{-- <li class="nav-item dropdown">
                    <a class="nav-link d-flex align-items-center gap-1 dropdown-toggle-custom {{ $menu == 'Direktori' ? 'active' : '' }}"
                        href="#" id="direktoriDropdown" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Direktori
                        <i class="bi bi-chevron-down dropdown-icon"></i>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="direktoriDropdown">
                        <li><a class="dropdown-item {{ $menu == 'Sekretariat' ? 'active' : '' }}"
                                href="#profil">Sekretariat</a></li>
                        <li><a class="dropdown-item {{ $menu == 'Bidang Tanaman Pangan' ? 'active' : '' }}"
                                href="#struktur">Bidang Tanaman Pangan</a></li>
                        <li><a class="dropdown-item {{ $menu == 'Bidang Hortikulatura dan Perkebunan' ? 'active' : '' }}"
                                href="#struktur">Bidang Hortikultura dan Perkebunan</a></li>
                        <li><a class="dropdown-item {{ $menu == 'Bidang Pengolajan Hasil dan Konsumsi Pangan' ? 'active' : '' }}"
                                href="#struktur">Bidang Pengolahan Hasil dan Konsumsi Pangan</a>
                        </li>
                        <li><a class="dropdown-item {{ $menu == 'Bidang Cadangan dan Distribusi Pangan' ? 'active' : '' }}"
                                href="#struktur">Bidang Cadangan dan Distribusi Pangan</a></li>
                        <li><a class="dropdown-item {{ $menu == 'Bidang Penyuluhan' ? 'active' : '' }}"
                                href="#struktur">Bidang Penyuluhan</a></li>
                    </ul>
                </li> --}}
            </ul>
        </div>
    </div>
</nav>
