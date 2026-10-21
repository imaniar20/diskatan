# Restrukturisasi Data Pertanian Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Merestrukturisasi halaman data publik (`view.public.data.index`) dengan menghapus nav-item Produksi dan membuat tab Pertanian terpadu (Tanaman Pangan, Hortikultura, Perkebunan dengan opsi Data Utama dan Data Tahunan dinamis), serta merapikan sidebar admin secara hierarkis dan menyediakan CRUD lengkap untuk data baru.

**Architecture:** 
- Database: Tabel `hortikultura_utamas`, `perkebunan_utamas` (format bulanan/kecamatan) dan `perkebunans` (format historis tahunan).
- Backend: Controllers terstruktur untuk admin CRUD dan Excel Import/Export, serta data preparation dinamis di `HomeController@dataDanInformasi`.
- Frontend: Blade view dengan sub-tabs hierarkis dan segmented switcher modern untuk Data Utama vs Data Tahunan ber-ApexCharts.

**Tech Stack:** Laravel 10 / PHP 8.2, Blade, MySQL/MariaDB, ApexCharts, Bootstrap 5, Sneat Admin Template, PhpSpreadsheet.

---

### Task 1: Database Migrations & Eloquent Models

**Files:**
- Create: `database/migrations/2026_10_21_000001_create_hortikultura_utamas_table.php`
- Create: `database/migrations/2026_10_21_000002_create_perkebunan_utamas_table.php`
- Create: `database/migrations/2026_10_21_000003_create_perkebunans_table.php`
- Create: `app/Models/HortikulturaUtama.php`
- Create: `app/Models/PerkebunanUtama.php`
- Create: `app/Models/Perkebunan.php`
- Create: `database/seeders/PertanianBaruSeeder.php`

- [ ] **Step 1: Buat file migrasi `hortikultura_utamas`**
- [ ] **Step 2: Buat file migrasi `perkebunan_utamas`**
- [ ] **Step 3: Buat file migrasi `perkebunans`**
- [ ] **Step 4: Jalankan migrasi `php artisan migrate`**
- [ ] **Step 5: Buat Model `HortikulturaUtama`, `PerkebunanUtama`, dan `Perkebunan`**
- [ ] **Step 6: Buat seeder data awal dan jalankan untuk testing**

---

### Task 2: Backend Admin Controllers & Excel Services

**Files:**
- Create: `app/Http/Controllers/AdminHortikulturaUtamaController.php`
- Create: `app/Http/Controllers/AdminPerkebunanUtamaController.php`
- Create: `app/Http/Controllers/AdminPerkebunanController.php`
- Create: `app/Services/HortikulturaUtamaExcelService.php`
- Create: `app/Services/PerkebunanUtamaExcelService.php`
- Create: `app/Services/PerkebunanExcelService.php`

- [ ] **Step 1: Buat Excel Service untuk Hortikultura Utama & Perkebunan Utama & Perkebunan Tahunan**
- [ ] **Step 2: Buat `AdminHortikulturaUtamaController` dengan aksi index, create, store, edit, update, destroy, import, export**
- [ ] **Step 3: Buat `AdminPerkebunanUtamaController` dengan aksi identik**
- [ ] **Step 4: Buat `AdminPerkebunanController` untuk data tahunan perkebunan**

---

### Task 3: Admin Views & UI untuk CRUD Baru

**Files:**
- Create: `resources/views/admin/hortikultura_utama/index.blade.php`
- Create: `resources/views/admin/perkebunan_utama/index.blade.php`
- Create: `resources/views/admin/perkebunan_tahunan/index.blade.php`

- [ ] **Step 1: Buat view `admin/hortikultura_utama/index.blade.php` dengan filter komoditas/tahun/kategori, tabel kecamatan, dan modal tambah/edit/import**
- [ ] **Step 2: Buat view `admin/perkebunan_utama/index.blade.php` dengan fitur identik**
- [ ] **Step 3: Buat view `admin/perkebunan_tahunan/index.blade.php` dengan filter komoditas, tabel historis tahunan, dan modal CRUD/import**

---

### Task 4: Admin Sidebar & Routes Restructuring

**Files:**
- Modify: `routes/web.php`
- Modify: `resources/views/admin/partials/sidebar.blade.php`

- [ ] **Step 1: Daftarkan rute resource dan import/export untuk controller baru di `routes/web.php`**
- [ ] **Step 2: Perbarui `sidebar.blade.php` untuk mengelompokkan menu pertanian dengan sub-menu Sneat yang rapi dan menghapus menu Produksi lama**

---

### Task 5: HomeController (`dataDanInformasi`) Updates

**Files:**
- Modify: `app/Http/Controllers/HomeController.php`

- [ ] **Step 1: Query dan proses data `HortikulturaUtama` (grouping kategori & komoditas)**
- [ ] **Step 2: Query dan proses data `PerkebunanUtama`**
- [ ] **Step 3: Query dan proses data `Perkebunan` tahunan**
- [ ] **Step 4: Hitung jumlah tahun dinamis (`tanamanPanganTahunCount`, `sayuranTahunCount`, `perkebunanTahunCount`)**
- [ ] **Step 5: Kirimkan seluruh data baru ke view `public.data.index`**

---

### Task 6: Restrukturisasi Halaman Publik `resources/views/public/data/index.blade.php`

**Files:**
- Modify: `resources/views/public/data/index.blade.php`

- [ ] **Step 1: Hapus tab-nav `#produksi` dan tab-pane `#produksi` beserta chart-nya**
- [ ] **Step 2: Tambahkan nav-pill `#pertanian` di navigasi utama**
- [ ] **Step 3: Bangun struktur sub-tabs di `#pertanian`: Tanaman Pangan, Hortikultura, Perkebunan**
- [ ] **Step 4: Tambahkan Segmented Switcher "Data Utama" vs "Data Tahunan (X Tahun dinamis)" di tiap sub-sektor**
- [ ] **Step 5: Integrasikan script JS untuk filtering, modal rincian bulanan, dan ApexCharts untuk semua sub-sektor**

---

### Task 7: Verifikasi & Pengujian Fungsional

- [ ] **Step 1: Uji rute admin baru dan sidebar menu di browser / HTTP test**
- [ ] **Step 2: Uji halaman publik `/datadaninformasi` untuk memastikan navigasi tab Pertanian dan switcher Data Utama vs Tahunan berjalan lancar**
- [ ] **Step 3: Verifikasi responsivitas dan konsistensi data**
