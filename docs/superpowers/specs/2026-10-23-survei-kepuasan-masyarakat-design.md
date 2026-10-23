# Spesifikasi Desain: Sistem Survei Kepuasan Masyarakat DISKATAN

- **Tanggal:** 23 Oktober 2026
- **Target Sistem:** Website Resmi Dinas Ketahanan Pangan dan Pertanian Kabupaten Kuningan (`diskatan`)
- **Topik:** Integrasi dan Modernisasi Sistem SI-WAWAN ke dalam Portal Laravel dengan nama **"Survei Kepuasan Dinas Ketahanan Pangan dan Pertanian"**

---

## 1. Latar Belakang & Tujuan
Sebelumnya, sistem survei kepuasan masyarakat (SI-WAWAN) beroperasi secara independen di aplikasi PHP native (`Sipraja`). Pada website utama DISKATAN (`c:\laragon\www\diskatan`), akses layanan survei hanya berupa tautan eksternal (`http://siwawan.kuningankab.go.id/`).

Tujuan proyek ini adalah:
1. Memigrasikan sistem survei secara menyeluruh ke dalam framework Laravel 10 pada repositori `diskatan`.
2. Mengubah branding sistem menjadi **"Survei Kepuasan Dinas Ketahanan Pangan dan Pertanian"**.
3. Menghubungkan tombol akses survei di menu publik **Layanan Online** (baik di halaman Beranda/Home maupun halaman Layanan).
4. Menyediakan antarmuka publik interaktif (kuesioner multi-step & live statistik IKM) serta panel pengelolaan Admin di backoffice.

---

## 2. Arsitektur Basis Data & Model Eloquent

### 2.1. Tabel-Tabel Database
Migrasi baru dibuat untuk mendefinisikan 5 tabel:

1. **`jenis_layanan`**
   - `id`: unsignedBigInteger, primary key, auto increment.
   - `nama_layanan`: string(150), required.
   - `deskripsi`: text, nullable.
   - `status`: enum('aktif', 'nonaktif'), default 'aktif'.
   - `created_at`, `updated_at`: timestamps.
   - *Seeder awal:*
     - Informasi Harga Pangan
     - Pengendalian Organisme Pengganggu Tanaman (OPT)
     - Surat Rekomendasi Izin Pembelian Subsidi BBM
     - Distribusi Sarana dan Prasarana Pertanian
     - Surat Rekomendasi Izin Produk Pangan Segar Asal Tumbuhan (PSAT)
     - Penyebarluasan Informasi Ketahanan Pangan dan Pertanian
     - Layanan Lainnya

2. **`pertanyaan_survei`**
   - `id`: unsignedBigInteger, primary key, auto increment.
   - `teks_pertanyaan`: text, required.
   - `icon`: string(50), default 'bi-star'.
   - `kunci_pertanyaan`: string(50), required (e.g. `persyaratan`, `kecepatan`, `kualitas`, `kompetensi`, `pengaduan`).
   - `urutan`: integer, default 1.
   - `status`: enum('aktif', 'nonaktif'), default 'aktif'.
   - `created_at`, `updated_at`: timestamps.
   - *Seeder awal (5 Unsur Standar PermenPAN-RB):*
     1. Kesesuaian persyaratan dan kemudahan prosedur pelayanan tanpa berbelit-belit.
     2. Kecepatan waktu dalam memberikan pelayanan serta kesesuaian hasil dengan harapan.
     3. Kesesuaian produk/standar pelayanan dan kejelasan informasi biaya/tarif (layanan gratis).
     4. Kompetensi, keramahan, dan kesopanan petugas dalam melayani.
     5. Penanganan pengaduan, kritik, dan saran pengguna layanan ditindaklanjuti dengan baik.

3. **`responden`**
   - `id`: unsignedBigInteger, primary key, auto increment.
   - `nama_lengkap`: string(150), required.
   - `usia`: enum('17-25', '26-35', '36-45', '46-55', '56+'), required.
   - `jenis_kelamin`: enum('Laki-laki', 'Perempuan'), required.
   - `no_telepon`: string(25), nullable.
   - `pekerjaan`: string(100), nullable.
   - `created_at`, `updated_at`: timestamps.

4. **`survei_respons`**
   - `id`: unsignedBigInteger, primary key, auto increment.
   - `responden_id`: foreignId('responden_id')->constrained('responden')->onDelete('cascade').
   - `jenis_layanan_id`: foreignId('jenis_layanan_id')->constrained('jenis_layanan')->onDelete('restrict').
   - `tanggal_survei`: date, required.
   - `komentar`: text, nullable.
   - `created_at`, `updated_at`: timestamps.

5. **`detail_rating`**
   - `id`: unsignedBigInteger, primary key, auto increment.
   - `survei_respons_id`: foreignId('survei_respons_id')->constrained('survei_respons')->onDelete('cascade').
   - `pertanyaan_id`: foreignId('pertanyaan_id')->constrained('pertanyaan_survei')->onDelete('restrict').
   - `rating`: tinyInteger (1–5), required.
   - `created_at`, `updated_at`: timestamps.

### 2.2. Relasi Eloquent
- **`App\Models\JenisLayanan`**: `hasMany(SurveiRespons::class, 'jenis_layanan_id')`
- **`App\Models\PertanyaanSurvei`**: `hasMany(DetailRating::class, 'pertanyaan_id')`
- **`App\Models\Responden`**: `hasMany(SurveiRespons::class, 'responden_id')`
- **`App\Models\SurveiRespons`**:
  - `belongsTo(Responden::class, 'responden_id')`
  - `belongsTo(JenisLayanan::class, 'jenis_layanan_id')`
  - `hasMany(DetailRating::class, 'survei_respons_id')`
- **`App\Models\DetailRating`**:
  - `belongsTo(SurveiRespons::class, 'survei_respons_id')`
  - `belongsTo(PertanyaanSurvei::class, 'pertanyaan_id')`

---

## 3. Antarmuka Publik (`public/survei/index.blade.php`)

### 3.1. Tata Letak & Branding
- Terintegrasi dengan master layout `public.layouts.app` (navbar, hero banner dengan breadcrumb, footer resmi DISKATAN).
- Judul Halaman: **"Survei Kepuasan Dinas Ketahanan Pangan dan Pertanian"**.
- Menggunakan palet warna hijau alami DISKATAN (`#10b981`, `#059669`, `#16a34a`), micro-animations, dan shadow modern.

### 3.2. Komponen Tab
1. **Tab Formulir Survei (Wizard Multi-Step):**
   - **Step 1: Pilih Layanan:** Grid kartu jenis layanan yang aktif.
   - **Step 2: Data Responden:** Nama, Usia, Jenis Kelamin, No. Telp, Pekerjaan.
   - **Step 3: Penilaian Kuesioner:** Bintang 1–5 interaktif per pertanyaan dengan teks label keterangan dinamis (Sangat Tidak Puas s/d Sangat Puas).
   - **Step 4: Kritik & Saran:** Masukan terbuka untuk dinas.
   - **Submit:** Validasi frontend + AJAX submit ke controller, alert sukses SweetAlert2, otomatis reset form dan beralih ke tab hasil.
2. **Tab Hasil & Statistik IKM:**
   - **Kalkulasi Mutu Pelayanan PermenPAN-RB:**
     - Nilai Rata-rata per Unsur (NRR).
     - IKM Konversi = Total NRR x 20 (skala 25–100).
     - Predikat Mutu:
       - 88.31 – 100.00: **A (Sangat Baik)**
       - 76.61 – 88.30: **B (Baik)**
       - 65.00 – 76.60: **C (Kurang Baik)**
       - 25.00 – 64.99: **D (Tidak Baik)**
   - **Total Responden Terverifikasi.**
   - **Visualisasi Chart.js:**
     - Radar/Bar chart skor rata-rata per unsur pertanyaan.
     - Donut chart distribusi rating bintang.

---

## 4. Antarmuka Admin (`admin/survei/...`)

### 4.1. Integrasi Sidebar
Di `resources/views/admin/partials/sidebar.blade.php`:
- Ditambahkan menu **"Survei Kepuasan"** di grup *Public* dengan icon `bx bx-poll`.
- Sub-menu:
  1. *Rekap Responden & Hasil* (`/admin-survei-kepuasan`)
  2. *Master Pertanyaan* (`/admin-survei-kepuasan/pertanyaan`)
  3. *Master Layanan* (`/admin-survei-kepuasan/layanan`)

### 4.2. Fitur Modul Admin
1. **Rekap Responden:**
   - Ringkasan KPI: Total Responden, Skor IKM saat ini, Mutu Pelayanan.
   - DataTables daftar responden: No, Tanggal, Nama, Jenis Kelamin, Usia, Layanan yang Dinilai, Rata-rata Bintang, Aksi (Detail & Hapus).
   - Modal Detail Respon: Menampilkan rincian nilai bintang 1-5 per pertanyaan yang diberikan responden tersebut beserta komentar/sarannya.
   - Tombol Export Data Responden ke Excel.
2. **Master Pertanyaan:**
   - Tabel butir pertanyaan kuesioner.
   - Form Tambah / Edit Teks Pertanyaan & Urutan.
   - Toggle status Aktif / Nonaktif.
3. **Master Layanan:**
   - Tabel jenis layanan publik.
   - Form Tambah / Edit Nama Layanan & Deskripsi.
   - Toggle status Aktif / Nonaktif.

---

## 5. Rute (Routes) & Titik Integrasi

### 5.1. Routes (`routes/web.php`)
```php
// Sisi Publik
Route::get('/layanan/survei-kepuasan', [SurveiKepuasanPublicController::class, 'index'])->name('survei.index');
Route::post('/layanan/survei-kepuasan', [SurveiKepuasanPublicController::class, 'store'])->name('survei.store');

// Sisi Admin (dalam middleware auth admin)
Route::prefix('admin-survei-kepuasan')->name('admin.survei.')->group(function() {
    Route::get('/', [SurveiKepuasanAdminController::class, 'index'])->name('index');
    Route::get('/detail/{id}', [SurveiKepuasanAdminController::class, 'detail'])->name('detail');
    Route::delete('/destroy/{id}', [SurveiKepuasanAdminController::class, 'destroy'])->name('destroy');
    Route::get('/export-excel', [SurveiKepuasanAdminController::class, 'exportExcel'])->name('export');
    
    // Master Pertanyaan
    Route::get('/pertanyaan', [SurveiPertanyaanAdminController::class, 'index'])->name('pertanyaan.index');
    Route::post('/pertanyaan', [SurveiPertanyaanAdminController::class, 'store'])->name('pertanyaan.store');
    Route::put('/pertanyaan/{id}', [SurveiPertanyaanAdminController::class, 'update'])->name('pertanyaan.update');
    Route::delete('/pertanyaan/{id}', [SurveiPertanyaanAdminController::class, 'destroy'])->name('pertanyaan.destroy');

    // Master Layanan
    Route::get('/layanan', [SurveiLayananAdminController::class, 'index'])->name('layanan.index');
    Route::post('/layanan', [SurveiLayananAdminController::class, 'store'])->name('layanan.store');
    Route::put('/layanan/{id}', [SurveiLayananAdminController::class, 'update'])->name('layanan.update');
    Route::delete('/layanan/{id}', [SurveiLayananAdminController::class, 'destroy'])->name('layanan.destroy');
});
```

### 5.2. Pembaruan Card Menu "Layanan Online"
1. **`resources/views/public/home/section/layanan.blade.php` (Card 2):**
   - Judul: *Survei Kepuasan Masyarakat*
   - Teks: *Sampaikan penilaian kepuasan Anda terhadap pelayanan Dinas Ketahanan Pangan dan Pertanian*
   - Link: `route('survei.index')` / `/layanan/survei-kepuasan`
   - Icon: `bi bi-clipboard2-check` (menggantikan icon chat pengaduan)
   - Teks Tombol: *Isi Survei*
2. **`resources/views/public/layanan/index.blade.php` (Card 2):**
   - Mengalami pembaruan yang sama persis agar konsisten.

---

## 6. Penanganan Error & Validasi
- **Validasi Formulir:** Nama wajib diisi, usia & jenis kelamin valid, jenis layanan harus aktif, semua butir pertanyaan aktif wajib memiliki rating (1–5).
- **Keamanan:** Dilindungi oleh `@csrf` token Laravel.
- **Transaksi Database:** Penyimpanan responden, survei respons, dan detail rating dibungkus dalam `DB::transaction()` untuk menjamin konsistensi data.
- **Respons JSON:** Untuk request AJAX dari formulir survei publik, mengembalikan JSON format `{status: 'success'|'error', message: '...'}`.

---

## 7. Rencana Pengujian & Verifikasi
1. Verifikasi migrasi database dan pemanggilan seeder berjalan tanpa error.
2. Pengujian formulir publik:
   - Akses via Card 2 di Home (`/`) $\rightarrow$ Berhasil membuka halaman survei.
   - Akses via Card 2 di Halaman Layanan (`/layanan`) $\rightarrow$ Berhasil membuka halaman survei.
   - Pengisian form valid $\rightarrow$ Tersimpan di database dan SweetAlert sukses muncul.
   - Validasi kelengkapan form jika ada butir yang belum diberi bintang rating.
   - Statistik IKM terhitung dan ter-update otomatis.
3. Pengujian panel Admin:
   - Login admin $\rightarrow$ Klik menu Survei Kepuasan.
   - Data responden yang baru mengisi survei muncul di DataTables.
   - Modal detail respon dapat menampilkan rincian bintang tiap butir pertanyaan.
   - CRUD Master Pertanyaan dan Master Layanan berfungsi dengan baik.
