# Survei Kepuasan Dinas Ketahanan Pangan dan Pertanian Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Memigrasikan sistem SI-WAWAN native ke dalam portal Laravel `diskatan` dengan nama **"Survei Kepuasan Dinas Ketahanan Pangan dan Pertanian"**, menyediakan halaman publik interaktif (kuesioner multi-step & live statistik IKM), panel admin di dashboard, serta memperbarui tombol akses pada grid Layanan Online di Home dan Halaman Layanan.

**Architecture:** Menggunakan arsitektur MVC Laravel 10 dengan 5 model Eloquent (`JenisLayanan`, `PertanyaanSurvei`, `Responden`, `SurveiRespons`, `DetailRating`) dalam relasi terelasi dan transaksi database. Sisi publik dibangun dengan layout Blade resmi DISKATAN, formulir multi-step wizard, dan visualisasi Chart.js, sedangkan sisi admin terintegrasi ke dalam tema Sneat Admin DISKATAN lengkap dengan DataTables dan manajemen master.

**Tech Stack:** Laravel 10, PHP 8.2+, MySQL, Blade Templating, Bootstrap 5 / Custom DISKATAN Theme, Chart.js, SweetAlert2, DataTables.net, PhpSpreadsheet / Laravel Excel.

## Global Constraints
- Target database: MySQL `diskatan` pada Laragon.
- Proteksi form publik menggunakan token CSRF dan validasi transaksi `DB::transaction`.
- Rumus IKM mengacu pada PermenPAN-RB No. 14 Tahun 2017 (skala 25–100 dengan predikat Mutu A, B, C, D).
- Tampilan kartu pada menu "Layanan Online" di halaman Beranda (`/`) dan Layanan (`/layanan`) menggantikan Card 2 ("Pengaduan Masyarakat") menjadi "Survei Kepuasan Masyarakat".

---

### Task 1: Database Migrations, Seeders & Eloquent Models

**Files:**
- Create: `database/migrations/2026_10_23_000001_create_survei_kepuasan_tables.php`
- Create: `app/Models/JenisLayanan.php`
- Create: `app/Models/PertanyaanSurvei.php`
- Create: `app/Models/Responden.php`
- Create: `app/Models/SurveiRespons.php`
- Create: `app/Models/DetailRating.php`
- Create: `database/seeders/SurveiKepuasanSeeder.php`

**Interfaces:**
- Produces: 5 Model Eloquent (`JenisLayanan`, `PertanyaanSurvei`, `Responden`, `SurveiRespons`, `DetailRating`) dengan relasi lengkap:
  - `JenisLayanan::hasMany(SurveiRespons::class)`
  - `PertanyaanSurvei::hasMany(DetailRating::class)`
  - `Responden::hasMany(SurveiRespons::class)`
  - `SurveiRespons::belongsTo(Responden::class)`, `SurveiRespons::belongsTo(JenisLayanan::class)`, `SurveiRespons::hasMany(DetailRating::class)`
  - `DetailRating::belongsTo(SurveiRespons::class)`, `DetailRating::belongsTo(PertanyaanSurvei::class)`

- [ ] **Step 1: Buat migration file untuk 5 tabel survei**

```php
// database/migrations/2026_10_23_000001_create_survei_kepuasan_tables.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jenis_layanan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_layanan', 150);
            $table->text('deskripsi')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
        });

        Schema::create('pertanyaan_survei', function (Blueprint $table) {
            $table->id();
            $table->text('teks_pertanyaan');
            $table->string('icon', 50)->default('bi-star');
            $table->string('kunci_pertanyaan', 50);
            $table->integer('urutan')->default(1);
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
        });

        Schema::create('responden', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap', 150);
            $table->enum('usia', ['17-25', '26-35', '36-45', '46-55', '56+']);
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->string('no_telepon', 25)->nullable();
            $table->string('pekerjaan', 100)->nullable();
            $table->timestamps();
        });

        Schema::create('survei_respons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('responden_id')->constrained('responden')->onDelete('cascade');
            $table->foreignId('jenis_layanan_id')->constrained('jenis_layanan')->onDelete('restrict');
            $table->date('tanggal_survei');
            $table->text('komentar')->nullable();
            $table->timestamps();
        });

        Schema::create('detail_rating', function (Blueprint $table) {
            $table->id();
            $table->foreignId('survei_respons_id')->constrained('survei_respons')->onDelete('cascade');
            $table->foreignId('pertanyaan_id')->constrained('pertanyaan_survei')->onDelete('restrict');
            $table->tinyInteger('rating');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_rating');
        Schema::dropIfExists('survei_respons');
        Schema::dropIfExists('responden');
        Schema::dropIfExists('pertanyaan_survei');
        Schema::dropIfExists('jenis_layanan');
    }
};
```

- [ ] **Step 2: Buat 5 Model Eloquent**

```php
// app/Models/JenisLayanan.php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisLayanan extends Model
{
    protected $table = 'jenis_layanan';
    protected $fillable = ['nama_layanan', 'deskripsi', 'status'];

    public function surveiRespons()
    {
        return $this->hasMany(SurveiRespons::class, 'jenis_layanan_id');
    }
}
```

```php
// app/Models/PertanyaanSurvei.php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PertanyaanSurvei extends Model
{
    protected $table = 'pertanyaan_survei';
    protected $fillable = ['teks_pertanyaan', 'icon', 'kunci_pertanyaan', 'urutan', 'status'];

    public function detailRating()
    {
        return $this->hasMany(DetailRating::class, 'pertanyaan_id');
    }
}
```

```php
// app/Models/Responden.php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Responden extends Model
{
    protected $table = 'responden';
    protected $fillable = ['nama_lengkap', 'usia', 'jenis_kelamin', 'no_telepon', 'pekerjaan'];

    public function surveiRespons()
    {
        return $this->hasMany(SurveiRespons::class, 'responden_id');
    }
}
```

```php
// app/Models/SurveiRespons.php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurveiRespons extends Model
{
    protected $table = 'survei_respons';
    protected $fillable = ['responden_id', 'jenis_layanan_id', 'tanggal_survei', 'komentar'];

    public function responden()
    {
        return $this->belongsTo(Responden::class, 'responden_id');
    }

    public function jenisLayanan()
    {
        return $this->belongsTo(JenisLayanan::class, 'jenis_layanan_id');
    }

    public function detailRating()
    {
        return $this->hasMany(DetailRating::class, 'survei_respons_id');
    }
}
```

```php
// app/Models/DetailRating.php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailRating extends Model
{
    protected $table = 'detail_rating';
    protected $fillable = ['survei_respons_id', 'pertanyaan_id', 'rating'];

    public function surveiRespons()
    {
        return $this->belongsTo(SurveiRespons::class, 'survei_respons_id');
    }

    public function pertanyaan()
    {
        return $this->belongsTo(PertanyaanSurvei::class, 'pertanyaan_id');
    }
}
```

- [ ] **Step 3: Buat Database Seeder awal**

```php
// database/seeders/SurveiKepuasanSeeder.php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JenisLayanan;
use App\Models\PertanyaanSurvei;

class SurveiKepuasanSeeder extends Seeder
{
    public function run(): void
    {
        $layananList = [
            ['nama_layanan' => 'Informasi Harga Pangan', 'deskripsi' => 'Layanan informasi perkembangan harga komoditas pangan terkini', 'status' => 'aktif'],
            ['nama_layanan' => 'Pengendalian Organisme Pengganggu Tanaman (OPT)', 'deskripsi' => 'Layanan penanganan serangan hama dan penyakit tanaman', 'status' => 'aktif'],
            ['nama_layanan' => 'Surat Rekomendasi Izin Pembelian Subsidi BBM', 'deskripsi' => 'Layanan rekomendasi pembelian BBM subsidi pertanian di SPBU', 'status' => 'aktif'],
            ['nama_layanan' => 'Distribusi Sarana dan Prasarana Pertanian', 'deskripsi' => 'Layanan bantuan sarana dan prasarana pertanian bagi kelompok tani', 'status' => 'aktif'],
            ['nama_layanan' => 'Surat Rekomendasi Izin Produk Pangan Segar Asal Tumbuhan (PSAT)', 'deskripsi' => 'Layanan perizinan dan sertifikasi beras serta sayuran konsumsi', 'status' => 'aktif'],
            ['nama_layanan' => 'Penyebarluasan Informasi Ketahanan Pangan dan Pertanian', 'deskripsi' => 'Layanan edukasi dan publikasi informasi ketahanan pangan', 'status' => 'aktif'],
            ['nama_layanan' => 'Lainnya', 'deskripsi' => 'Layanan umum kedinasan lainnya', 'status' => 'aktif'],
        ];

        foreach ($layananList as $item) {
            JenisLayanan::firstOrCreate(['nama_layanan' => $item['nama_layanan']], $item);
        }

        $pertanyaanList = [
            [
                'teks_pertanyaan' => 'Bagaimana pendapat Anda tentang kesesuaian persyaratan dan kemudahan prosedur pelayanan yang diberikan (tidak berbelit-belit)?',
                'icon' => 'bi-speedometer2',
                'kunci_pertanyaan' => 'prosedur',
                'urutan' => 1,
                'status' => 'aktif'
            ],
            [
                'teks_pertanyaan' => 'Bagaimana pendapat Anda tentang kecepatan waktu dalam memberikan pelayanan serta kesesuaian hasil dengan harapan Anda?',
                'icon' => 'bi-stopwatch',
                'kunci_pertanyaan' => 'kecepatan',
                'urutan' => 2,
                'status' => 'aktif'
            ],
            [
                'teks_pertanyaan' => 'Bagaimana pendapat Anda tentang kesesuaian produk pelayanan dengan standar yang ditetapkan serta kejelasan informasi biaya/tarif (layanan gratis)?',
                'icon' => 'bi-award',
                'kunci_pertanyaan' => 'produk',
                'urutan' => 3,
                'status' => 'aktif'
            ],
            [
                'teks_pertanyaan' => 'Bagaimana pendapat Anda tentang kompetensi, kesopanan, dan keramahan petugas dalam memberikan pelayanan?',
                'icon' => 'bi-person-check',
                'kunci_pertanyaan' => 'petugas',
                'urutan' => 4,
                'status' => 'aktif'
            ],
            [
                'teks_pertanyaan' => 'Bagaimana pendapat Anda tentang penanganan pengaduan, saran, dan masukan pengguna layanan yang ditindaklanjuti dengan baik?',
                'icon' => 'bi-chat-heart',
                'kunci_pertanyaan' => 'pengaduan',
                'urutan' => 5,
                'status' => 'aktif'
            ],
        ];

        foreach ($pertanyaanList as $item) {
            PertanyaanSurvei::firstOrCreate(['kunci_pertanyaan' => $item['kunci_pertanyaan']], $item);
        }
    }
}
```

- [ ] **Step 4: Jalankan Migration & Seeder**

Run:
```bash
php artisan migrate --path=/database/migrations/2026_10_23_000001_create_survei_kepuasan_tables.php
php artisan db:seed --class=SurveiKepuasanSeeder
```
Expected: Migrations created & seeder completed successfully.

- [ ] **Step 5: Commit task**
```bash
git add database/migrations/2026_10_23_000001_create_survei_kepuasan_tables.php app/Models/ database/seeders/SurveiKepuasanSeeder.php
git commit -m "feat(survei): create migrations, models, and initial seeders for survei kepuasan"
```

---

### Task 2: Public Survey Controller, Calculation Logic & Routes

**Files:**
- Create: `app/Http/Controllers/Public/SurveiKepuasanPublicController.php`
- Modify: `routes/web.php`

**Interfaces:**
- Produces:
  - `SurveiKepuasanPublicController@index`: Mengambil data master layanan aktif, pertanyaan aktif, serta menghitung agregasi statistik IKM (Skor IKM, Mutu Pelayanan, Total Responden, Distribusi Bintang).
  - `SurveiKepuasanPublicController@store`: Validasi data responden & rating, insert transaksi `DB::transaction()`, return JSON `{ status: 'success', message: '...' }`.

- [ ] **Step 1: Buat Controller `SurveiKepuasanPublicController`**

```php
// app/Http/Controllers/Public/SurveiKepuasanPublicController.php
<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\JenisLayanan;
use App\Models\PertanyaanSurvei;
use App\Models\Responden;
use App\Models\SurveiRespons;
use App\Models\DetailRating;

class SurveiKepuasanPublicController extends Controller
{
    public function index()
    {
        $layanan = JenisLayanan::where('status', 'aktif')->orderBy('id')->get();
        $pertanyaan = PertanyaanSurvei::where('status', 'aktif')->orderBy('urutan')->get();

        // Hitung Statistik IKM
        $totalResponden = Responden::count();
        $totalRespons = SurveiRespons::count();

        // Rata-rata per pertanyaan
        $ratingsByPertanyaan = DetailRating::select('pertanyaan_id', DB::raw('AVG(rating) as avg_rating'), DB::raw('COUNT(id) as total_votes'))
            ->groupBy('pertanyaan_id')
            ->pluck('avg_rating', 'pertanyaan_id')
            ->toArray();

        $nrrTotal = 0;
        $pertanyaanAktifCount = $pertanyaan->count();

        foreach ($pertanyaan as $item) {
            $avg = $ratingsByPertanyaan[$item->id] ?? 0;
            $nrrTotal += $avg;
        }

        // Skor IKM Konversi = (Total NRR / Jumlah Unsur) * 25 jika skala 1-4, atau jika skala 1-5 = (Total NRR / Jumlah Unsur) * 20
        $skorIkm = 0;
        $mutuPelayanan = 'Belum Ada Data';
        $badgeClass = 'bg-secondary';

        if ($pertanyaanAktifCount > 0 && $totalRespons > 0) {
            $nrrTertimbang = $nrrTotal / $pertanyaanAktifCount;
            $skorIkm = round($nrrTertimbang * 20, 2); // Skala 100

            if ($skorIkm >= 88.31) {
                $mutuPelayanan = 'A (Sangat Baik)';
                $badgeClass = 'bg-success';
            } elseif ($skorIkm >= 76.61) {
                $mutuPelayanan = 'B (Baik)';
                $badgeClass = 'bg-primary';
            } elseif ($skorIkm >= 65.00) {
                $mutuPelayanan = 'C (Kurang Baik)';
                $badgeClass = 'bg-warning text-dark';
            } else {
                $mutuPelayanan = 'D (Tidak Baik)';
                $badgeClass = 'bg-danger';
            }
        }

        // Distribusi Bintang Keseluruhan (1-5)
        $ratingCounts = DetailRating::select('rating', DB::raw('COUNT(*) as total'))
            ->groupBy('rating')
            ->pluck('total', 'rating')
            ->toArray();

        $ratingDistribution = [
            5 => $ratingCounts[5] ?? 0,
            4 => $ratingCounts[4] ?? 0,
            3 => $ratingCounts[3] ?? 0,
            2 => $ratingCounts[2] ?? 0,
            1 => $ratingCounts[1] ?? 0,
        ];

        return view('public.survei.index', compact(
            'layanan',
            'pertanyaan',
            'totalResponden',
            'skorIkm',
            'mutuPelayanan',
            'badgeClass',
            'ratingsByPertanyaan',
            'ratingDistribution'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap'     => 'required|string|max:150',
            'usia'             => 'required|in:17-25,26-35,36-45,46-55,56+',
            'jenis_kelamin'    => 'required|in:Laki-laki,Perempuan',
            'no_telepon'       => 'nullable|string|max:25',
            'pekerjaan'        => 'nullable|string|max:100',
            'jenis_layanan_id' => 'required|exists:jenis_layanan,id',
            'ratings'          => 'required|array',
            'komentar'         => 'nullable|string|max:1000',
        ]);

        $pertanyaanAktif = PertanyaanSurvei::where('status', 'aktif')->pluck('id')->toArray();
        foreach ($pertanyaanAktif as $pId) {
            if (!isset($request->ratings[$pId]) || $request->ratings[$pId] < 1 || $request->ratings[$pId] > 5) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Harap berikan penilaian bintang untuk semua pertanyaan yang tersedia!'
                ], 422);
            }
        }

        DB::beginTransaction();
        try {
            // 1. Simpan data responden
            $responden = Responden::create([
                'nama_lengkap'  => $request->nama_lengkap,
                'usia'          => $request->usia,
                'jenis_kelamin' => $request->jenis_kelamin,
                'no_telepon'    => $request->no_telepon,
                'pekerjaan'     => $request->pekerjaan,
            ]);

            // 2. Simpan respon survei
            $respons = SurveiRespons::create([
                'responden_id'     => $responden->id,
                'jenis_layanan_id' => $request->jenis_layanan_id,
                'tanggal_survei'   => now()->toDateString(),
                'komentar'         => $request->komentar,
            ]);

            // 3. Simpan detail rating
            foreach ($pertanyaanAktif as $pId) {
                DetailRating::create([
                    'survei_respons_id' => $respons->id,
                    'pertanyaan_id'     => $pId,
                    'rating'            => (int) $request->ratings[$pId],
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Terima kasih! Survei kepuasan Anda telah berhasil dikirimkan.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan sistem saat menyimpan survei: ' . $e->getMessage()
            ], 500);
        }
    }
}
```

- [ ] **Step 2: Daftarkan Route Publik di `routes/web.php`**

```php
// Tambahkan di routes/web.php:
use App\Http\Controllers\Public\SurveiKepuasanPublicController;

Route::get('/layanan/survei-kepuasan', [SurveiKepuasanPublicController::class, 'index'])->name('survei.index');
Route::post('/layanan/survei-kepuasan', [SurveiKepuasanPublicController::class, 'store'])->name('survei.store');
```

- [ ] **Step 3: Uji Rute dengan artisan route:list**
Run: `php artisan route:list --name=survei`
Expected: Rute `survei.index` dan `survei.store` terdaftar dengan method GET dan POST.

- [ ] **Step 4: Commit task**
```bash
git add app/Http/Controllers/Public/SurveiKepuasanPublicController.php routes/web.php
git commit -m "feat(survei): add public survey controller and routes"
```

---

### Task 3: Public Survey Blade View (Formulir Multi-Step & Live IKM Statistics)

**Files:**
- Create: `resources/views/public/survei/index.blade.php`

**Interfaces:**
- Menampilkan halaman lengkap menggunakan `@extends('public.layouts.app')`
- Menyediakan UI wizard kuesioner interaktif (Step 1-4) dengan star rating 1-5 bintang yang berubah warna saat di-hover dan diklik.
- Menampilkan grafik radar dan donut Chart.js untuk statistik IKM.
- Mengirim form dengan AJAX dan feedback SweetAlert2.

- [ ] **Step 1: Buat view `resources/views/public/survei/index.blade.php`**
Implementasikan template Blade lengkap mencakup styling CSS, header, hero breadcrumb, tab navigasi, stepper wizard, dan script Chart.js + AJAX submit.

- [ ] **Step 2: Jalankan web browser / cek sintaks blade**
Run: `php artisan view:cache`
Expected: Views cached successfully without syntax errors.

- [ ] **Step 3: Commit task**
```bash
git add resources/views/public/survei/index.blade.php
git commit -m "feat(survei): create public survey view with wizard form and live IKM dashboard"
```

---

### Task 4: Update Card 2 in Layanan Online Sections (Home & Layanan Pages)

**Files:**
- Modify: `resources/views/public/home/section/layanan.blade.php:412-426`
- Modify: `resources/views/public/layanan/index.blade.php:605-618`

**Interfaces:**
- Mengubah Card 2 pada kedua file dari "Pengaduan Masyarakat" (link eksternal `http://siwawan.kuningankab.go.id/`) menjadi:
  - Judul: **Survei Kepuasan Masyarakat**
  - Teks: **Survei Kepuasan Dinas Ketahanan Pangan dan Pertanian Kabupaten Kuningan**
  - Link: `url('/layanan/survei-kepuasan')` / `{{ route('survei.index') }}`
  - Icon: `bi bi-clipboard2-check`
  - Tombol: **Isi Survei**

- [ ] **Step 1: Perbarui Card 2 di `resources/views/public/home/section/layanan.blade.php`**
Ganti baris 412–426:
```blade
            {{-- Card 2: Survei Kepuasan Masyarakat --}}
            <div class="layanan-card blue" data-aos="fade-up" data-aos-delay="150">
                <div class="layanan-icon">
                    <i class="bi bi-clipboard2-check"></i>
                </div>
                <h3 class="layanan-card-title">Survei Kepuasan Masyarakat</h3>
                <p class="layanan-card-text">
                    Survei Kepuasan Dinas Ketahanan Pangan dan Pertanian Kabupaten Kuningan
                </p>
                <a href="{{ route('survei.index') }}" class="layanan-link">
                    Isi Survei
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>
```

- [ ] **Step 2: Perbarui Card 2 di `resources/views/public/layanan/index.blade.php`**
Ganti baris 605–618 dengan kode yang sama persis.

- [ ] **Step 3: Commit task**
```bash
git add resources/views/public/home/section/layanan.blade.php resources/views/public/layanan/index.blade.php
git commit -m "feat(survei): link card 2 in Layanan Online section to survei kepuasan"
```

---

### Task 5: Admin Survey Controllers & Routes

**Files:**
- Create: `app/Http/Controllers/Admin/SurveiKepuasanAdminController.php`
- Create: `app/Http/Controllers/Admin/SurveiPertanyaanAdminController.php`
- Create: `app/Http/Controllers/Admin/SurveiLayananAdminController.php`
- Modify: `routes/web.php`

**Interfaces:**
- Produces:
  - `SurveiKepuasanAdminController@index`: Menampilkan daftar responden, rekap skor IKM, filter tanggal & layanan.
  - `SurveiKepuasanAdminController@detail`: Mengambil data detail survei dan rating responden (JSON untuk modal).
  - `SurveiKepuasanAdminController@destroy`: Menghapus data responden & respon terkait.
  - `SurveiKepuasanAdminController@exportExcel`: Download spreadsheet laporan responden survei.
  - `SurveiPertanyaanAdminController`: CRUD untuk `pertanyaan_survei`.
  - `SurveiLayananAdminController`: CRUD untuk `jenis_layanan`.

- [ ] **Step 1: Buat `SurveiKepuasanAdminController`**
- [ ] **Step 2: Buat `SurveiPertanyaanAdminController`**
- [ ] **Step 3: Buat `SurveiLayananAdminController`**
- [ ] **Step 4: Daftarkan Routes admin di `routes/web.php`**
- [ ] **Step 5: Verifikasi route list admin**
Run: `php artisan route:list --name=admin.survei`
Expected: Seluruh rute admin terdaftar dengan benar.
- [ ] **Step 6: Commit task**
```bash
git add app/Http/Controllers/Admin/ routes/web.php
git commit -m "feat(survei): add admin survey controllers and routes"
```

---

### Task 6: Admin Survey Views & Sidebar Integration

**Files:**
- Modify: `resources/views/admin/partials/sidebar.blade.php`
- Create: `resources/views/admin/survei/index.blade.php` (Rekapitulasi Responden & Modal Detail)
- Create: `resources/views/admin/survei/pertanyaan.blade.php` (Kelola Butir Pertanyaan)
- Create: `resources/views/admin/survei/layanan.blade.php` (Kelola Jenis Layanan)

**Interfaces:**
- Mengintegrasikan menu "Survei Kepuasan" di bawah grup "Public" di sidebar.
- Menampilkan tampilan bertema Sneat Admin dengan tabel DataTables, badge status, modal detail respon beranimasi bintang, form tambah/edit modal, dan SweetAlert konfirmasi hapus.

- [ ] **Step 1: Tambahkan menu di sidebar `resources/views/admin/partials/sidebar.blade.php`**
- [ ] **Step 2: Buat view `resources/views/admin/survei/index.blade.php`**
- [ ] **Step 3: Buat view `resources/views/admin/survei/pertanyaan.blade.php`**
- [ ] **Step 4: Buat view `resources/views/admin/survei/layanan.blade.php`**
- [ ] **Step 5: Commit task**
```bash
git add resources/views/admin/partials/sidebar.blade.php resources/views/admin/survei/
git commit -m "feat(survei): add admin survey views and sidebar menu"
```

---

### Task 7: End-to-End System Verification

**Files:**
- Verify: Halaman Utama (`/`) -> section Layanan Online -> Klik "Isi Survei"
- Verify: Halaman Layanan (`/layanan`) -> section Layanan Online -> Klik "Isi Survei"
- Verify: Formulir Publik (`/layanan/survei-kepuasan`) -> Pengisian form kuesioner
- Verify: Dashboard Admin (`/admin-survei-kepuasan`) -> Pengecekan data masuk

- [ ] **Step 1: Tes navigasi tombol di Home dan Halaman Layanan**
Memastikan link membuka `/layanan/survei-kepuasan`.
- [ ] **Step 2: Lakukan simulasi pengisian survei di browser**
Isi kuesioner dengan data pengujian, berikan rating bintang, submit, dan pastikan notifikasi sukses muncul serta nilai IKM terhitung.
- [ ] **Step 3: Buka Dashboard Admin**
Cek bahwa data responden baru muncul di tabel, buka modal detail untuk memastikan nilai bintang per butir pertanyaan tampil sesuai.
- [ ] **Step 4: Commit task**
```bash
git commit --allow-empty -m "chore(survei): end-to-end verification completed"
```
