<style>
    .navbar:not(.navbar-scrolled) .nav-link {
        color: #2e7d32 !important;
        position: relative;
    }

    /* hover */
    .navbar:not(.navbar-scrolled) .nav-link:hover {
        color: #1b5e20 !important;
    }

    /* underline animasi */
    .navbar:not(.navbar-scrolled) .nav-link::after {
        content: "";
        position: absolute;
        left: 50%;
        bottom: -6px;
        width: 0;
        height: 2px;
        background: #2e7d32;
        transition: all 0.3s ease;
        transform: translateX(-50%);
    }

    .navbar:not(.navbar-scrolled) .nav-link:hover::after {
        width: 70%;
    }

    /* kondisi scroll */
    .navbar-scrolled .nav-link {
        color: rgba(255, 255, 255, .9) !important;
    }

    /* hover */
    .navbar-scrolled .nav-link:hover {
        color: #fff !important;
    }

    /* background hover soft */
    .navbar-scrolled .nav-link:hover {
        background: rgba(255, 255, 255, .15);
        border-radius: 999px;
    }

    .nav-link.active {
        font-weight: 600;
    }

    /* aktif saat navbar putih */
    .navbar:not(.navbar-scrolled) .nav-link.active::after {
        width: 70%;
    }

    /* aktif saat navbar hijau */
    .navbar-scrolled .nav-link.active {
        background: rgba(255, 255, 255, .2);
        border-radius: 999px;
    }

    .brand-title {
        font-weight: 700;
        font-size: 1.2rem;
        letter-spacing: -0.3px;
    }

    .brand-subtitle {
        font-size: 1rem;
        opacity: 1rem;
    }
</style>

<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
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
                    <a class="nav-link active" href="{{ url('/') }}">Home</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link d-flex align-items-center gap-1 dropdown-toggle-custom" href="#"
                        id="profilDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Profil
                        <i class="bi bi-chevron-down dropdown-icon"></i>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="profilDropdown">
                        <li><a class="dropdown-item" href="{{ url('/visi-misi') }}">Visi & Misi</a></li>
                        <li><a class="dropdown-item" href="{{ url('/sejarah') }}">Sejarah</a></li>
                        <li><a class="dropdown-item" href="{{ url('/struktur') }}">Struktur Organisasi</a></li>
                        <li><a class="dropdown-item" href="{{ url('/tupoksi') }}">Tugas Pokok dan Fungsi</a></li>
                        <li><a class="dropdown-item" href="{{ url('/daftar-pejabat') }}">Daftar Pejabat</a></li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/agenda') }}">Agenda</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/berita') }}">Berita</a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link d-flex align-items-center gap-1 dropdown-toggle-custom" href="#"
                        id="informasiDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Informasi
                        <i class="bi bi-chevron-down dropdown-icon"></i>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="informasiDropdown">
                        <li><a class="dropdown-item" href="#struktur">Komoditas Pertanian</a></li>
                        <li><a class="dropdown-item" href="#struktur">Harga Komoditas Pertanian</a></li>
                        <li><a class="dropdown-item" href="#struktur">Ruang Lingkup Pertanian</a></li>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link d-flex align-items-center gap-1 dropdown-toggle-custom" href="#"
                        id="direktoriDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Direktori
                        <i class="bi bi-chevron-down dropdown-icon"></i>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="direktoriDropdown">
                        <li><a class="dropdown-item" href="#profil">Sekretariat</a></li>
                        <li><a class="dropdown-item" href="#struktur">Bidang Tanaman Pangan</a></li>
                        <li><a class="dropdown-item" href="#struktur">Bidang Hortikultura dan Perkebunan</a></li>
                        <li><a class="dropdown-item" href="#struktur">Bidang Pengolahan Hasil dan Konsumsi Pangan</a></li>
                        <li><a class="dropdown-item" href="#struktur">Bidang Cadangan dan Distribusi Pangan</a></li>
                        <li><a class="dropdown-item" href="#struktur">Bidang Penyuluhan</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const navbar = document.querySelector(".navbar");

        window.addEventListener("scroll", function() {
            if (window.scrollY > 0) {
                navbar.classList.add("navbar-scrolled");
            } else {
                navbar.classList.remove("navbar-scrolled");
            }
        });
    });
</script>
