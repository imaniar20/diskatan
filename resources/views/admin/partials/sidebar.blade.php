<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="/" class="app-brand-link">
            <span class="app-brand-logo demo pl-2">
                <img src="{{ asset('img/logo/logo.png') }}" style="width: 50px;" alt="">
            </span>
            <h4 class=" menu-text fw-bolder ms-2 mt-3"> Kabupaten Kuningan</h4>
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
        @if (session('user')->username == 'admin')
            <li class="menu-item {{ $title == 'Produksi' ? 'active' : '' }}">
                <a href="/admin-produksi" class="menu-link">
                    <i class='menu-icon bx bx-stats' ></i>
                    <div data-i18n="Analytics">Produksi</div>
                </a>
            </li>
            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Menu</span>
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
