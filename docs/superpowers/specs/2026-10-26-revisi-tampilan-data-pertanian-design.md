# Spesifikasi Desain: Revisi Tampilan Data Pertanian, Hortikultura, dan Komoditas DISKATAN

## 1. Ringkasan Kebutuhan
Berdasarkan arahan perbaikan data dan tampilan publik DISKATAN, dilakukan penyesuaian terhadap modul Data & Informasi Pertanian (`/datadaninformasi`):
1. **Penghapusan Tampilan Puso/Fuso**: Opsi dan tampilan data gagal panen (Puso/Fuso) ditiadakan dari tampilan publik sub-sektor Tanaman Pangan dan Hortikultura.
2. **Pembersihan Komoditas**: Menghapus 9 komoditas (Tomat, Wortel, Kubis, Petsai/Sawi, Rambutan, Mangga, Durian, Jahe, Kapulaga) dari master komoditas dan basis data. Komoditas aktif Hortikultura disederhanakan menjadi 4 komoditas pokok: Bawang Merah, Bawang Daun, Cabe Besar, dan Cabe Rawit.
3. **Penyembunyian Sub-Sektor Perkebunan**: Menyembunyikan tombol tab dan panel sub-sektor Perkebunan dari halaman publik pengunjung (`/datadaninformasi`), sementara menu pengelolaan di Admin Sidebar tetap dipertahankan.
4. **Penyederhanaan Tampilan Tanaman Pangan**: Menghapus kolom Target (Okmar, Asep, Total) dan kolom Capaian (%) dari tabel publik, serta mengubah 4 Kartu KPI menjadi fokus realisasi musim (MH, MK, Total Realisasi).
5. **Tampilan Bulanan Hortikultura**: Mengubah penyajian tabel data utama Hortikultura menjadi tampilan langsung 12 kolom bulan kalender (Januari s/d Desember) + Total Tahunan.
6. **Penghapusan Produktivitas di Hortikultura**: Menghilangkan tombol kategori Produktivitas (Kw/Ha) pada sub-sektor Hortikultura, sehingga menyisakan Luas Tanam, Luas Panen, dan Produksi.

---

## 2. Perubahan Data & Database

### 2.1 Pembersihan Baris Komoditas
- Menghapus record pada tabel `komoditas` untuk `sektor = 'hortikultura'` yang memiliki nama komoditas berikut:
  - Tomat
  - Wortel
  - Kubis
  - Petsai / Sawi
  - Rambutan
  - Mangga
  - Durian
  - Jahe
  - Kapulaga
- Menghapus record terkait di tabel:
  - `hortikultura_utamas` where `komoditas IN (...)`
  - `sayurans` where `komoditas IN (...)`
- Memperbarui seeder `database/seeders/PertanianBaruSeeder.php` agar daftar komoditas sayuran hanya:
  ```php
  $sayuranKomoditas = ['Bawang Merah', 'Bawang Daun', 'Cabe Besar', 'Cabe Rawit'];
  ```

---

## 3. Desain Frontend Publik (`resources/views/public/data/index.blade.php`)

### 3.1 Sub-Sektor Perkebunan (Poin 3)
- Memberikan class `d-none` pada tombol pill navigasi sub-sektor Perkebunan:
  ```html
  <button type="button" class="btn-subsector d-none" data-subsector="perkebunan">
  ```
- Memberikan class `d-none` pada container `#pane-perkebunan`.
- Memastikan navigasi default aktif berada pada `Tanaman Pangan` dan beralih ke `Hortikultura` tanpa ada error referensi subsektor perkebunan.

### 3.2 Tampilan Sub-Sektor Tanaman Pangan (Poin 1 & 4)
- **Kategori Pills**:
  - Menghapus tombol pill `Puso (Ha)` (`data-kategori="puso"`).
  - Kategori tersisa: `tanam`, `panen`, `produksi`, `produktivitas`.
- **4 Kartu KPI Realisasi**:
  - Card 1: Luas Sawah / Baku Lahan (Ha)
  - Card 2: Realisasi MH / Okmar (Ha / Ton / Kw)
  - Card 3: Realisasi MK / Asep (Ha / Ton / Kw)
  - Card 4: Total Realisasi (Ha / Ton / Kw)
- **Tabel Data Utama Padi & Palawija**:
  - Header:
    ```html
    <tr>
        <th class="text-center" style="width: 4%">No</th>
        <th>Kecamatan</th>
        <th class="text-end">Luas Sawah (Ha)</th>
        <th class="text-end">Realisasi MH (Ha)</th>
        <th class="text-end">Realisasi MK (Ha)</th>
        <th class="text-end">Total Realisasi (Ha)</th>
        <th class="text-center" style="width: 8%">Rincian</th>
    </tr>
    ```
  - Kolom `Target Okmar`, `Target Asep`, `Target Total`, dan `Capaian (%)` dihapus dari `thead` dan `tbody`.
  - Tombol aksi popup rincian 12 bulan (`btn-pub-detail`) tetap ada untuk eksplorasi bulanan.
- **Grafik Batang**:
  - Menampilkan series Total Realisasi per Kecamatan.

### 3.3 Tampilan Sub-Sektor Hortikultura (Poin 1, 5, & 6)
- **Kategori Pills**:
  - Menghapus tombol pill `Produktivitas (Kw/Ha)` (`data-kategori="produktivitas"`).
  - Menghapus tombol pill `Puso (Ha)` (`data-kategori="puso"`).
  - Kategori tersisa: `tanam` (Luas Tanam), `panen` (Luas Panen), `produksi` (Produksi).
- **Sub-Pills Komoditas**:
  - Hanya menampilkan 4 komoditas aktif: Bawang Merah, Bawang Daun, Cabe Besar, Cabe Rawit.
- **Tabel Data Utama Hortikultura (Tampilan Bulanan)**:
  - Header tabel:
    ```html
    <tr>
        <th class="text-center" style="width: 4%">No</th>
        <th>Kecamatan</th>
        <th class="text-end">Jan</th>
        <th class="text-end">Feb</th>
        <th class="text-end">Mar</th>
        <th class="text-end">Apr</th>
        <th class="text-end">Mei</th>
        <th class="text-end">Jun</th>
        <th class="text-end">Jul</th>
        <th class="text-end">Ags</th>
        <th class="text-end">Sep</th>
        <th class="text-end">Okt</th>
        <th class="text-end">Nop</th>
        <th class="text-end">Des</th>
        <th class="text-end text-success fw-bold">Total Tahunan</th>
    </tr>
    ```
  - Isi baris mengambil data per kecamatan: `realisasi_jan` s/d `realisasi_des` dan `total_realisasi`.
  - Format angka menggunakan format bilangan Indonesia (`toLocaleString('id-ID')`).
- **4 Kartu KPI Hortikultura**:
  - Disesuaikan menjadi KPI Realisasi Bulanan & Tahunan (Total Realisasi Semester 1, Semester 2, dan Total Tahunan).

---

## 4. Pengujian & Verifikasi
1. Verifikasi query basis data: memastikan 9 komoditas berhasil dihapus tanpa foreign key error.
2. Verifikasi halaman publik `/datadaninformasi#pertanian`:
   - Tab Perkebunan tidak terlihat oleh pengguna.
   - Tanaman Pangan tidak menampilkan kolom Target Okmar/Asep/Total, Capaian, atau Puso.
   - Hortikultura tidak memiliki opsi Produktivitas atau Puso.
   - Tabel Hortikultura menampilkan 12 kolom bulan kalender + Total Tahunan secara responsif dan data terisi dengan presisi.
3. Tidak melakukan git commit sebelum mendapat instruksi lebih lanjut dari pengguna.
