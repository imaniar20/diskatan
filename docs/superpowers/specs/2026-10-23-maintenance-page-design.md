# Design Spec: Halaman Pemeliharaan Sistem (Maintenance Page 503)

- **Tanggal**: 2026-10-23
- **Status**: Disetujui
- **Target File**:
  - `resources/views/errors/503.blade.php`
  - `routes/web.php`

---

## 1. Latar Belakang & Tujuan
Saat sistem DISKATAN Kabupaten Kuningan sedang dalam proses pemeliharaan berkala, pembaruan aplikasi, atau pemeliharaan database (`php artisan down`), pengguna memerlukan halaman pemberitahuan yang informatif, representatif, dan dapat diandalkan (*resilient*).

Halaman ini bertujuan untuk:
1. Menyediakan tampilan status 503 resmi bawaan Laravel saat perintah `php artisan down` aktif.
2. Menyediakan rute pratinjau `/maintenance` untuk kebutuhan pengujian visual tanpa harus menonaktifkan server produksi.
3. Mencerminkan identitas resmi Pemerintah Kabupaten Kuningan dan Dinas Ketahanan Pangan dan Pertanian (DISKATAN) dengan estetika agrikultur modern berwarna hijau.
4. Menjamin halaman selalu dapat dimuat (100% resilient) dengan meminimalisir ketergantungan pada koneksi database atau kompilasi aset eksternal.

---

## 2. Arsitektur & Struktur Rute

### 2.1 File Tampilan
- **Path**: `resources/views/errors/503.blade.php`
- **Mekanisme**: Laravel secara otomatis mencari view ini ketika merespons status HTTP 503 Service Unavailable (saat `app()->isDownForMaintenance()` bernilai true).

### 2.2 Rute Pratinjau
- **Path**: `routes/web.php`
- **Definisi**:
  ```php
  Route::get('/maintenance', function () {
      return response()->view('errors.503', [], 503);
  })->name('maintenance');
  ```
- **Karakteristik**: Mengembalikan response view `errors.503` dengan status code HTTP 503 sehingga tim pengembang/admin dapat langsung memeriksa tampilan di browser.

---

## 3. Desain Visual & Komponen Tampilan

### 3.1 Skema Warna & Tipografi
- **Warna Utama**: Hijau Alam DISKATAN (`#16a34a`, `#15803d`, `#14532d`).
- **Warna Latar**: Latar gradasi lembut (`#f8fafc` ke `#f0fdf4`) dengan aksen kartu glassmorphism (`rgba(255, 255, 255, 0.95)`).
- **Tipografi**: Menggunakan fallback modern sans-serif (`Public Sans`, `Inter`, system-ui, -apple-system, sans-serif).

### 3.2 Komponen Antarmuka
1. **Header & Logo**:
   - Logo resmi Kabupaten Kuningan dari `asset('img/logo/kuningan.png')`.
   - Teks instansi: "Dinas Ketahanan Pangan dan Pertanian" & "Pemerintah Kabupaten Kuningan".
2. **Status Badge**:
   - Badge mengambang dengan titik hijau berdenyut (*pulsing animation*): `Sedang Dalam Pemeliharaan`.
3. **Ilustrasi Hero**:
   - Vektor SVG embedded yang elegan menggambarkan pemeliharaan sistem dengan ornamen daun dan agrikultur.
4. **Konten Teks**:
   - Judul: "Kami Sedang Melakukan Peningkatan Layanan"
   - Deskripsi: Informasi bahwa sistem sedang menjalani peningkatan performa dan pembaruan rutin.
5. **Tombol Interaktif**:
   - **Muat Ulang Halaman**: Memicu `window.location.reload()` dengan animasi transisi tombol.
   - **Hubungi Kami / WhatsApp**: Tautan bantuan ke narahubung resmi dinas.
6. **Card Informasi Tambahan**:
   - Alamat kantor DISKATAN Kabupaten Kuningan.
   - Hak Cipta resmi DISKATAN Kabupaten Kuningan.

---

## 4. Keandalan & Ketahanan (Zero-Dependency Resilience)
- **Inline CSS & SVG**: Seluruh styling dan ilustrasi dimasukkan langsung dalam file blade tanpa bergantung pada bundle compile CSS/JS eksternal atau pemanggilan tabel database.
- **Responsive**: Dioptimalkan untuk layar ponsel, tablet, dan desktop.

---

## 5. Rencana Pengujian
1. Verifikasi rute `/maintenance` melalui browser: pastikan visual, logo, tombol interaktif, dan responsivitas bekerja normal.
2. Verifikasi mode `php artisan down`: pastikan saat maintenance diaktifkan, halaman publik langsung menampilkan view `503.blade.php`.
3. Jalankan `php artisan up` untuk memastikan aplikasi kembali normal.
