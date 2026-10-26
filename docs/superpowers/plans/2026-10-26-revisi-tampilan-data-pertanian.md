# Revisi Tampilan Data Pertanian, Hortikultura, dan Komoditas Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Menyesuaikan data komoditas dan tampilan publik data pertanian (`/datadaninformasi`) sesuai 6 poin revisi: menghapus tampilan fuso/puso, menghapus 9 komoditas dari database, menyembunyikan tab perkebunan di halaman publik, menghapus kolom target/capaian pada tanaman pangan, menampilkan tabel bulanan (12 bulan) pada hortikultura, dan menghilangkan opsi produktivitas pada hortikultura.

**Architecture:** Modifikasi query dan seeder komoditas di backend Laravel; penyesuaian data passing di `HomeController::datadaninformasi`; pembaruan menyeluruh pada template Blade dan JavaScript interaktif di `resources/views/public/data/index.blade.php`.

**Tech Stack:** Laravel 10, Blade Templates, JavaScript (Vanilla / ES6), Bootstrap 5, MySQL.

## Global Constraints
- **PENTING (Instruksi Pengguna):** Jangan lakukan `git commit` sampai pengguna menyetujui hasil akhir pengujian secara keseluruhan.
- Pertahankan struktur menu Admin (Perkebunan dan komoditas di admin tetap dapat diakses di belakang layar).
- Pastikan tampilan responsif dan tidak ada error JavaScript console saat berganti sub-sektor, tahun, komoditas, atau kategori.

---

### Task 1: Pembersihan Database & Seeder untuk 9 Komoditas

**Files:**
- Modify: `database/seeders/PertanianBaruSeeder.php:218-225`
- Database Execution: Eksekusi artisan / query penghapusan record komoditas

**Interfaces:**
- Produces: Data komoditas aktif di Hortikultura hanya berisi: `['Bawang Merah', 'Bawang Daun', 'Cabe Besar', 'Cabe Rawit']`.

- [x] **Step 1: Update seeder `PertanianBaruSeeder.php`**
Ubah baris 219 `database/seeders/PertanianBaruSeeder.php`:
```php
$sayuranKomoditas = ['Bawang Merah', 'Bawang Daun', 'Cabe Besar', 'Cabe Rawit'];
```

- [x] **Step 2: Jalankan skrip Artisan untuk membersihkan 9 komoditas dari database**
Komoditas yang dihapus:
`Tomat`, `Wortel`, `Kubis`, `Petsai / Sawi`, `Rambutan`, `Mangga`, `Durian`, `Jahe`, `Kapulaga`.
Hapus dari:
1. `komoditas` (`WHERE sektor = 'hortikultura' AND nama IN (...)`)
2. `hortikultura_utamas` (`WHERE komoditas IN (...)`)
3. `sayurans` (`WHERE komoditas IN (...)`)

- [x] **Step 3: Verifikasi data tersisa di database**
Pastikan melalui `php artisan tinker` bahwa sisa komoditas Hortikultura hanya 4 jenis (`Bawang Merah`, `Bawang Daun`, `Cabe Besar`, `Cabe Rawit`).

---

### Task 2: Penyesuaian Backend `HomeController`

**Files:**
- Modify: `app/Http/Controllers/HomeController.php:570-660`

**Interfaces:**
- Produces: `$sayuranKomoditasList` yang bersih dari 9 komoditas; data bulanan Hortikultura terformat lengkap untuk frontend.

- [x] **Step 1: Periksa filter komoditas di `HomeController::datadaninformasi()`**
Pastikan `$sayuranKomoditasList` yang diambil dari database hanya menyertakan komoditas yang aktif / ada di database.
Pastikan `$allHortikulturaUtama` memuat data realisasi 12 bulan (`realisasi_jan` s/d `realisasi_des`).

- [x] **Step 2: Verifikasi response data JSON yang disematkan ke Blade**
Pastikan data JSON `hortikulturaGrouped` dan `hortikulturaSummaryGrouped` terisi dengan benar.

---

### Task 3: Penyembunyian Sub-Sektor Perkebunan di Halaman Publik

**Files:**
- Modify: `resources/views/public/data/index.blade.php:840-845, 1295-1300`

**Interfaces:**
- Produces: Tab Perkebunan tersembunyi (`d-none`) di tampilan publik, navigasi default hanya mengizinkan Tanaman Pangan dan Hortikultura.

- [x] **Step 1: Tambahkan class `d-none` pada tombol pill Sub-sektor Perkebunan**
Pada elemen `<button type="button" class="btn-subsector" data-subsector="perkebunan">`, tambahkan class `d-none`.

- [x] **Step 2: Tambahkan class `d-none` pada container `#pane-perkebunan`**
Pastikan `#pane-perkebunan` memiliki class `d-none`.

- [x] **Step 3: Pastikan JavaScript navigasi sub-sektor tidak error jika tombol perkebunan disembunyikan**
Pastikan klik tombol sub-sektor hanya beralih antara `padi` (Tanaman Pangan) dan `hortikultura`.

---

### Task 4: Revisi Tampilan Tanaman Pangan (Hapus Puso, Target, Capaian & Update 4 Kartu KPI)

**Files:**
- Modify: `resources/views/public/data/index.blade.php:895-905, 915-950, 2040-2160, 2170-2210`

**Interfaces:**
- Consumes: JSON data Tanaman Pangan (Padi & Palawija)
- Produces: Tabel tanpa kolom target/capaian; 4 kartu KPI fokus realisasi (Baku Lahan, MH, MK, Total); grafik realisasi per kecamatan.

- [x] **Step 1: Hapus pill kategori Puso dari Tanaman Pangan**
Hapus elemen button `data-kategori="puso"` dari daftar pill kategori Tanaman Pangan (sekitar baris 897).

- [x] **Step 2: Ubah 4 Kartu KPI Atas Tanaman Pangan**
Ubah label dan nilai:
- Kartu 1: Luas Sawah / Baku Lahan (Ha)
- Kartu 2: Realisasi Musim Hujan (MH) (Ha / Ton / Kw)
- Kartu 3: Realisasi Musim Kemarau (MK) (Ha / Ton / Kw)
- Kartu 4: Total Realisasi (Ha / Ton / Kw)
Hapus teks rasio Capaian Target (%) pada kartu.

- [x] **Step 3: Ubah `thead` dan `tbody` tabel Tanaman Pangan**
Hapus kolom `Target Okmar`, `Target Asep`, `Target Total`, dan `Capaian (%)`.
Kolom baru:
`No | Kecamatan | Luas Sawah (Ha) | Realisasi MH (Ha) | Realisasi MK (Ha) | Total Realisasi (Ha) | Rincian Bulanan`
Tombol popup rincian 12 bulan tetap dipertahankan.

- [x] **Step 4: Sesuaikan grafik batang Tanaman Pangan**
Grafik hanya menampilkan 1 series: Total Realisasi per Kecamatan.

---

### Task 5: Revisi Tampilan Hortikultura (Tampilan Bulanan 12 Kolom & Hapus Produktivitas/Puso)

**Files:**
- Modify: `resources/views/public/data/index.blade.php:1110-1160, 2480-2620`

**Interfaces:**
- Consumes: JSON data `hortikulturaGrouped`
- Produces: Tabel bulanan 12 kolom kalender (Jan - Des) + Total; pill kategori tanpa Produktivitas dan Puso; kartu KPI realisasi.

- [x] **Step 1: Hapus pill kategori Produktivitas dan Puso pada Hortikultura**
Hapus elemen button:
- `data-kategori="produktivitas"`
- `data-kategori="puso"`
Kategori aktif hanya tersisa: `tanam` (Luas Tanam), `panen` (Luas Panen), dan `produksi` (Produksi).

- [x] **Step 2: Ubah `thead` tabel Hortikultura menjadi 12 kolom bulan**
Header kolom:
`No | Kecamatan | Jan | Feb | Mar | Apr | Mei | Jun | Jul | Ags | Sep | Okt | Nop | Des | Total Tahunan`

- [x] **Step 3: Ubah render `tbody` Hortikultura di JavaScript**
Render baris dengan membaca field:
`realisasi_jan`, `realisasi_feb`, `realisasi_mar`, `realisasi_apr`, `realisasi_mei`, `realisasi_jun`, `realisasi_jul`, `realisasi_ags`, `realisasi_sep`, `realisasi_okt`, `realisasi_nop`, `realisasi_des`, dan `total_realisasi`.
Format angka rapi dengan `Number(val || 0).toLocaleString('id-ID')`.

- [x] **Step 4: Sesuaikan 4 Kartu KPI Atas Hortikultura**
Ubah kartu KPI agar menampilkan:
- Kartu 1: Baku Lahan (Ha)
- Kartu 2: Realisasi Semester 1 / Jan-Jun (unit)
- Kartu 3: Realisasi Semester 2 / Jul-Des (unit)
- Kartu 4: Total Realisasi Tahunan (unit)

---

### Task 6: Pengujian & Validasi Komprehensif

**Files:**
- Browser Validation: `http://127.0.0.1:8000/datadaninformasi#pertanian`

- [x] **Step 1: Jalankan `php artisan view:clear` dan `php artisan cache:clear`**
- [x] **Step 2: Buka halaman `/datadaninformasi#pertanian` via browser**
Verifikasi secara visual:
1. Tab Perkebunan tidak terlihat di halaman publik.
2. Tanaman Pangan:
   - Tidak ada tombol Puso.
   - 4 Kartu KPI menampilkan Realisasi.
   - Tabel menampilkan kolom: No, Kecamatan, Luas Sawah, Realisasi MH, Realisasi MK, Total Realisasi, Rincian. Kolom Target dan Capaian hilang.
3. Hortikultura:
   - Tidak ada tombol Produktivitas dan Puso.
   - Sub-pills komoditas hanya menampilkan 4 komoditas (Bawang Merah, Bawang Daun, Cabe Besar, Cabe Rawit).
   - Tabel menampilkan 12 kolom bulan kalender (Jan s/d Des) + Total Tahunan dengan data terisi rapi.
4. Pastikan tidak ada JavaScript console error saat berganti tab atau komoditas.
- [x] **Step 3: Pastikan TIDAK ADA git commit yang dijalankan** (menjaga instruksi pengguna).
