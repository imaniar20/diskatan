# Desain Spesifikasi: Restrukturisasi Halaman Data Publik Pertanian & Manajemen Admin

## 1. Ringkasan & Tujuan
Restrukturisasi halaman publik data (`view.public.data.index`) untuk menghapus tab lama "Produksi", lalu memperkenalkan modul terpadu "Pertanian" yang menaungi 3 sub-sektor utama:
1. **Tanaman Pangan** (Data Utama: Padi & Palawija, Data Tahunan: Dinamis X Tahun)
2. **Hortikultura** (Data Utama: Format seperti data utama nomor 1, Data Tahunan: Sayuran & Hortikultura)
3. **Perkebunan** (Data Utama & Data Tahunan: Format identik dengan nomor 1 & 2)

Selain itu, sidebar admin dirapikan agar menu-menu terkait pertanian terstruktur secara hierarkis (Sub-sektor -> Data Utama & Data Tahunan), dan menu lama Produksi dihapus.

---

## 2. Arsitektur Data & Database

### 2.1 Skema Tabel Baru
1. **`hortikultura_utamas`** (Data Utama Hortikultura):
   - Tabel untuk data per kecamatan & realisasi bulanan (MH & MK).
   - Kolom:
     - `id`: unsigned big integer (PK)
     - `komoditas`: string (contoh: Bawang Merah, Cabe Besar, Tomat, dll)
     - `kategori`: string default 'tanam' (tanam, panen, produksi, produktivitas, puso)
     - `satuan`: string default 'Ha'
     - `tahun`: string(10) default '2026'
     - `musim_tanam`: string default 'MT 2025/2026 dan MT 2026'
     - `no_urut`: integer nullable
     - `kecamatan`: string
     - `luas_sawah`: decimal(12,2) default 0
     - `target_okmar`, `target_asep`, `target_total`: decimal(12,2) default 0
     - `realisasi_okt`, `realisasi_nop`, `realisasi_des`, `realisasi_jan`, `realisasi_feb`, `realisasi_mar`, `jumlah_mh`: decimal(12,2) default 0
     - `realisasi_apr`, `realisasi_mei`, `realisasi_jun`, `realisasi_jul`, `realisasi_ags`, `realisasi_sep`, `jumlah_mk`: decimal(12,2) default 0
     - `total_realisasi`: decimal(12,2) default 0
     - `persentase_sub`, `persentase_capaian`: decimal(8,2) default 0
     - `timestamps`

2. **`perkebunan_utamas`** (Data Utama Perkebunan):
   - Tabel untuk data per kecamatan & realisasi bulanan perkebunan.
   - Kolom identik dengan `hortikultura_utamas` dan `padi_palawijas`.

3. **`perkebunans`** (Data Tahunan Perkebunan):
   - Tabel untuk data historis tahunan perkebunan.
   - Kolom:
     - `id`: unsigned big integer (PK)
     - `komoditas`: string (Kopi, Cengkeh, Kelapa, Lada, Tembakau, Aren, Kakao, Karet, Teh, Pala, dll)
     - `tahun`: string(10)
     - `luas_tanam`: decimal(12,2) default 0
     - `luas_panen`: decimal(12,2) default 0
     - `produktivitas`: decimal(12,2) default 0
     - `produksi`: decimal(12,2) default 0
     - `satuan_luas`: string default 'Ha'
     - `satuan_produksi`: string default 'Ton'
     - `timestamps`

### 2.2 Model Eloquent
- `App\Models\HortikulturaUtama`
- `App\Models\PerkebunanUtama`
- `App\Models\Perkebunan`

---

## 3. Desain Frontend Publik (`resources/views/public/data/index.blade.php`)

### 3.1 Navigasi Tab Utama
- Nav-item `Produksi` (`#produksi`) dihapus dari DOM dan script.
- Nav-item `Statistik` (`#statistik`) dipertahankan.
- Nav-item `Ketahanan Pangan` (`#ketahanan`) dipertahankan.
- Nav-item `Pertanian` (`#pertanian`) ditambahkan sebagai wadah terpadu.

### 3.2 Struktur di dalam Tab `#pertanian`
- **Sub-Sektor Pills** (Horizontal Tabs):
  - 🌾 Tanaman Pangan
  - 🥦 Hortikultura
  - 🌴 Perkebunan
- **Switcher Data Utama vs Data Tahunan**:
  - Tombol segmented pill di setiap sub-sektor:
    - `[ 📊 Data Utama ]` (Menampilkan rincian per kecamatan, filter tahun, 5 kategori, 4 KPI cards, chart target vs realisasi, tabel rincian kecamatan, dan modal popup rincian bulanan)
    - `[ 📈 Data Tahunan (Dinamis X Tahun) ]` (Menampilkan label dinamis misal "Tanaman Pangan 13 Tahun", filter komoditas, 4 KPI cards, chart tren historis multi-tahun, dan tabel historis tahunan)
- **Kompatibilitas Responsif & Dynamic Switcher**:
  - Tab pills dan switcher dirancang dengan Bootstrap tabs/pills dan JavaScript switcher tanpa reload bila berganti mode, serta sinkronisasi filter tahun/komoditas yang mulus.

---

## 4. Desain Admin & CRUD

### 4.1 Controllers Baru & Eksisting
1. **Tanaman Pangan**:
   - Data Utama: `AdminPadiPalawijaController` (Route: `/admin-padi-palawija`)
   - Data Tahunan: `AdminTanamanPanganController` (Route: `/admin-tanaman-pangan`)
2. **Hortikultura**:
   - Data Utama: `AdminHortikulturaUtamaController` (Route: `/admin-hortikultura-utama`)
     - Fitur: Index, Create, Store, Edit, Update, Destroy, Export Excel, Import Excel, auto-calculate totals & percentages.
   - Data Tahunan: `AdminSayuranController` (Route: `/admin-sayuran`)
3. **Perkebunan**:
   - Data Utama: `AdminPerkebunanUtamaController` (Route: `/admin-perkebunan-utama`)
     - Fitur: Index, Create, Store, Edit, Update, Destroy, Export Excel, Import Excel.
   - Data Tahunan: `AdminPerkebunanController` (Route: `/admin-perkebunan-tahunan`)
     - Fitur: Index, Create, Store, Edit, Update, Destroy, Export Excel, Import Excel.

### 4.2 Restrukturisasi Sidebar Admin (`resources/views/admin/partials/sidebar.blade.php`)
Menu admin dikelompokkan dengan struktur hierarki yang jelas:
```html
- Dashboard
- PUBLIC
  - Data Dashboard (/admin-data)
  - Agenda (/admin-agenda)
  - Berita (/admin-berita)
  - Program (/admin-program)
- DATA PERTANIAN (Collapsible Sub-menu)
  - Tanaman Pangan:
    - Data Utama (/admin-padi-palawija)
    - Data Tahunan (/admin-tanaman-pangan)
  - Hortikultura:
    - Data Utama (/admin-hortikultura-utama)
    - Data Tahunan (/admin-sayuran)
  - Perkebunan:
    - Data Utama (/admin-perkebunan-utama)
    - Data Tahunan (/admin-perkebunan-tahunan)
- PENGATURAN
  - Kategori (/admin-kategori)
  - Bidang (/admin-bidang)
  - User (/admin-user)
```
Menu `/admin-produksi` dihapus dari sidebar.

---

## 5. HomeController Updates
- Update metode `dataDanInformasi`:
  - Mengambil data `HortikulturaUtama` dan summary-nya berdasarkan tahun dan komoditas.
  - Mengambil data `PerkebunanUtama` dan summary-nya.
  - Mengambil data `Perkebunan` (tahunan) dan summary-nya.
  - Menghitung rentang tahun dinamis:
    - `tanamanPanganTahunCount` (misal 13 Tahun)
    - `hortikulturaTahunCount`
    - `perkebunanTahunCount`
  - Mengoper seluruh dataset yang dibutuhkan ke view `public.data.index`.
