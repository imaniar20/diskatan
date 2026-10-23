# Halaman Pemeliharaan Sistem (Maintenance Page 503) Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Membangun halaman pemeliharaan sistem (HTTP 503) resmi Laravel dengan tema modern agrikultur DISKATAN Kabupaten Kuningan yang mandiri (*zero-dependency*) serta rute pratinjau `/maintenance`.

**Architecture:** Template Blade mandiri (`resources/views/errors/503.blade.php`) dengan embedded modern CSS dan SVG agrikultur, dilengkapi rute pratinjau di `routes/web.php` untuk kemudahan pengujian tanpa mematikan aplikasi.

**Tech Stack:** Laravel Blade, CSS3, SVG, Vanilla JavaScript.

## Global Constraints
- Harus mandiri (*zero external dependencies*) agar dapat ditampilkan tanpa database atau bundler CSS/JS.
- Menggunakan palet warna hijau alam khas DISKATAN (`#16a34a`, `#15803d`).
- Responsif di perangkat desktop, tablet, dan ponsel.

---

### Task 1: Buat View Pemeliharaan Sistem `resources/views/errors/503.blade.php`

**Files:**
- Create: `resources/views/errors/503.blade.php`

**Interfaces:**
- Produces: View Blade `errors.503` yang otomatis dipanggil Laravel saat maintenance mode atau melalui `response()->view('errors.503', [], 503)`.

- [x] **Step 1: Buat file `resources/views/errors/503.blade.php`**
  Membuat file lengkap berisi struktur HTML5, styling embedded modern glassmorphism, logo Kabupaten Kuningan (`img/logo/kuningan.png`), pulsing status badge, SVG ilustrasi agrikultur modern, pesan peningkatan sistem, tombol reload dengan JS, dan kontak bantuan DISKATAN.

- [x] **Step 2: Validasi sintaks Blade**
  Jalankan `php artisan view:cache` untuk memastikan tidak ada kesalahan sintaksis pada view baru.

- [x] **Step 3: Commit perubahan Task 1**
  Commit dengan pesan: `feat: create 503 maintenance page view for DISKATAN`

---

### Task 2: Tambahkan Rute Preview `/maintenance` di `routes/web.php`

**Files:**
- Modify: `routes/web.php`

**Interfaces:**
- Consumes: View `errors.503` dari Task 1.
- Produces: Endpoint `GET /maintenance` dengan HTTP response status 503.

- [x] **Step 1: Tambahkan rute preview di `routes/web.php`**
  ```php
  Route::get('/maintenance', function () {
      return response()->view('errors.503', [], 503);
  })->name('maintenance');
  ```

- [x] **Step 2: Validasi rute dengan artisan**
  Jalankan `php artisan route:list --path=maintenance` untuk memastikan rute terdaftar dengan benar.

- [x] **Step 3: Commit perubahan Task 2**
  Commit dengan pesan: `feat: add /maintenance preview route`

---

### Task 3: Verifikasi Visual & Pengujian Mode Maintenance

**Files:**
- Test via Browser & Artisan CLI

- [x] **Step 1: Uji rute preview `/maintenance`**
  Akses URL `http://127.0.0.1:8000/maintenance` dan pastikan halaman ter-render sempurna dengan logo, tombol muat ulang, dan tata letak responsif.

- [x] **Step 2: Simulasi `php artisan down` dan `php artisan up`**
  Jalankan `php artisan down --render="errors.503"` untuk memverifikasi bahwa halaman publik menampilkan view 503 baru, lalu kembalikan sistem ke status online dengan `php artisan up`.

- [x] **Step 3: Dokumentasikan hasil pengujian dan laporkan ke pengguna**
