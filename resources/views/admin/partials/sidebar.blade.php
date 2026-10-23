<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="/" class="app-brand-link">
            <span class="app-brand-logo demo pl-2">
                <img src="{{ asset('img/logo/logo.png') }}" style="width: 50px;" alt="">
            </span>
            <h4 class="menu-text fw-bolder ms-2 mt-3"> Kabupaten Kuningan</h4>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1 mt-4">
        <!-- Dashboard -->
        <li class="menu-item {{ $title == 'Dashboard' ? 'active' : '' }}">
            <a href="/admin-dashboard" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Analytics">Dashboard</div>
            </a>
        </li>

        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Public</span>
        </li>
        <!-- Layouts -->
        <li class="menu-item {{ $title == 'Data' ? 'active' : '' }}">
            <a href="/admin-data" class="menu-link">
                <i class="menu-icon tf-icons bx bx-data"></i>
                <div data-i18n="Analytics">Data Dashboard</div>
            </a>
        </li>
        <li class="menu-item {{ $title == 'Agenda' ? 'active' : '' }}">
            <a href="/admin-agenda" class="menu-link">
                <i class="menu-icon tf-icons bx bx-bookmarks"></i>
                <div data-i18n="Analytics">Agenda</div>
            </a>
        </li>
        <li class="menu-item {{ $title == 'Berita' ? 'active' : '' }}">
            <a href="/admin-berita" class="menu-link">
                <i class="menu-icon tf-icons bx bx-news"></i>
                <div data-i18n="Analytics">Berita</div>
            </a>
        </li>
        <li class="menu-item {{ $title == 'Program' ? 'active' : '' }}">
            <a href="/admin-program" class="menu-link">
                <i class="menu-icon tf-icons bx bx-cog"></i>
                <div data-i18n="Analytics">Program</div>
            </a>
        </li>

        <!-- Survei Kepuasan -->
        @php
            $isSurvei = in_array($title, ['Survei Kepuasan', 'Master Pertanyaan', 'Master Layanan']);
        @endphp
        <li class="menu-item {{ $isSurvei ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-poll"></i>
                <div data-i18n="Survei Kepuasan">Survei Kepuasan</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ $title == 'Survei Kepuasan' ? 'active' : '' }}">
                    <a href="/admin-survei-kepuasan" class="menu-link">
                        <i class="bx bx-radio-circle me-1"></i>
                        <div data-i18n="Rekap Responden">Rekap Responden</div>
                    </a>
                </li>
                <li class="menu-item {{ $title == 'Master Pertanyaan' ? 'active' : '' }}">
                    <a href="/admin-survei-kepuasan/pertanyaan" class="menu-link">
                        <i class="bx bx-radio-circle me-1"></i>
                        <div data-i18n="Master Pertanyaan">Master Pertanyaan</div>
                    </a>
                </li>
                <li class="menu-item {{ $title == 'Master Layanan' ? 'active' : '' }}">
                    <a href="/admin-survei-kepuasan/layanan" class="menu-link">
                        <i class="bx bx-radio-circle me-1"></i>
                        <div data-i18n="Master Layanan">Master Layanan</div>
                    </a>
                </li>
            </ul>
        </li>

        @if (session('user')->username == 'admin')
            <!-- DATA PERTANIAN SECTION -->
            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Data Pertanian</span>
            </li>

            <!-- Tanaman Pangan -->
            @php
                $isTanamanPangan = in_array($title, ['Padi & Palawija', 'Tanaman Pangan']);
                $isHortikultura = in_array($title, ['Hortikultura Utama', 'Sayuran & Hortikultura', 'Sayuran']);
                $isPerkebunan = in_array($title, ['Perkebunan Utama', 'Perkebunan Tahunan', 'Perkebunan']);
            @endphp
            <li class="menu-item {{ $isTanamanPangan ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-sun"></i>
                    <div data-i18n="Tanaman Pangan">Tanaman Pangan</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ $title == 'Padi & Palawija' ? 'active' : '' }}">
                        <a href="/admin-padi-palawija" class="menu-link">
                            <i class="bx bx-radio-circle me-1"></i>
                            <div data-i18n="Data Utama">Data Utama (Padi & Palawija)</div>
                        </a>
                    </li>
                    <li class="menu-item {{ $title == 'Tanaman Pangan' ? 'active' : '' }}">
                        <a href="/admin-tanaman-pangan" class="menu-link">
                            <i class="bx bx-radio-circle me-1"></i>
                            <div data-i18n="Data Tahunan">Data Tahunan</div>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Hortikultura -->
            <li class="menu-item {{ $isHortikultura ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-leaf"></i>
                    <div data-i18n="Hortikultura">Hortikultura</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ $title == 'Hortikultura Utama' ? 'active' : '' }}">
                        <a href="/admin-hortikultura-utama" class="menu-link">
                            <i class="bx bx-radio-circle me-1"></i>
                            <div data-i18n="Data Utama">Data Utama</div>
                        </a>
                    </li>
                    <li class="menu-item {{ in_array($title, ['Sayuran & Hortikultura', 'Sayuran']) ? 'active' : '' }}">
                        <a href="/admin-sayuran" class="menu-link">
                            <i class="bx bx-radio-circle me-1"></i>
                            <div data-i18n="Data Tahunan">Data Tahunan (Sayuran)</div>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Perkebunan -->
            <li class="menu-item {{ $isPerkebunan ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-spa"></i>
                    <div data-i18n="Perkebunan">Perkebunan</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ $title == 'Perkebunan Utama' ? 'active' : '' }}">
                        <a href="/admin-perkebunan-utama" class="menu-link">
                            <i class="bx bx-radio-circle me-1"></i>
                            <div data-i18n="Data Utama">Data Utama</div>
                        </a>
                    </li>
                    <li class="menu-item {{ in_array($title, ['Perkebunan Tahunan', 'Perkebunan']) ? 'active' : '' }}">
                        <a href="/admin-perkebunan-tahunan" class="menu-link">
                            <i class="bx bx-radio-circle me-1"></i>
                            <div data-i18n="Data Tahunan">Data Tahunan</div>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Pengaturan</span>
            </li>
            <li class="menu-item {{ Request::is('admin-komoditas*') ? 'active' : '' }}">
                <a href="/admin-komoditas" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-list-check"></i>
                    <div data-i18n="Analytics">Master Komoditas</div>
                </a>
            </li>
            <li class="menu-item {{ $title == 'Kategori' ? 'active' : '' }}">
                <a href="/admin-kategori" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-line-chart"></i>
                    <div data-i18n="Analytics">Kategori</div>
                </a>
            </li>
            <li class="menu-item {{ $title == 'Bidang' ? 'active' : '' }}">
                <a href="/admin-bidang" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-cabinet"></i>
                    <div data-i18n="Analytics">Bidang</div>
                </a>
            </li>
            <li class="menu-item {{ $title == 'User' ? 'active' : '' }}">
                <a href="/admin-user" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-user"></i>
                    <div data-i18n="Analytics">User</div>
                </a>
            </li>
        @endif
    </ul>
</aside>
