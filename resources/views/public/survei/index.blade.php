@extends('public.layouts.app')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
    /* ============ ROOT & COLOR VARIABLES ============ */
    :root {
        --survei-primary: #10b981;
        --survei-dark: #065f46;
        --survei-light: #ecfdf5;
        --survei-accent: #f59e0b;
        --survei-gray: #f3f4f6;
        --survei-border: #e5e7eb;
    }

    .survei-page-wrapper {
        padding: 50px 0 80px 0;
        background: #f8fafc;
        min-height: 85vh;
    }

    /* Page Hero Overrides */
    .page-hero {
        position: relative;
        padding: 100px 0 60px 0;
        background: linear-gradient(rgba(6, 78, 59, 0.85), rgba(4, 120, 87, 0.85)), var(--hero-bg);
        background-size: cover;
        background-position: center;
        color: #ffffff;
        text-align: center;
    }

    .page-title {
        font-family: 'Poppins', sans-serif;
        font-size: 2.3rem;
        font-weight: 800;
        margin-bottom: 12px;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    .page-subtitle {
        font-size: 1.05rem;
        color: #d1fae5;
        max-width: 700px;
        margin: 0 auto 20px auto;
    }

    .breadcrumb-wrapper {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(8px);
        padding: 8px 22px;
        border-radius: 30px;
        font-size: 0.9rem;
    }

    .breadcrumb-wrapper a {
        color: #ffffff;
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .breadcrumb-wrapper a:hover {
        color: #fef08a;
    }

    .breadcrumb-wrapper span.active {
        color: #fef08a;
        font-weight: 600;
    }

    /* ============ NAVIGATION TABS ============ */
    .survei-nav-container {
        display: flex;
        justify-content: center;
        margin-bottom: 35px;
    }

    .survei-tabs {
        display: inline-flex;
        background: #ffffff;
        padding: 6px;
        border-radius: 50px;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.07);
        border: 1px solid var(--survei-border);
    }

    .survei-tab-btn {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 28px;
        border-radius: 40px;
        border: none;
        background: transparent;
        color: #64748b;
        font-weight: 600;
        font-size: 0.95rem;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .survei-tab-btn.active {
        background: linear-gradient(135deg, #10b981, #059669);
        color: #ffffff;
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.35);
    }

    .survei-tab-btn i {
        font-size: 1.15rem;
    }

    /* ============ CARD STYLING ============ */
    .survei-card {
        background: #ffffff;
        border-radius: 20px;
        border: 1px solid rgba(226, 232, 240, 0.8);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.02);
        padding: 40px 35px;
        margin-bottom: 30px;
    }

    @media (max-width: 768px) {
        .survei-card {
            padding: 25px 18px;
        }
        .page-title {
            font-size: 1.7rem;
        }
    }

    /* ============ STEPPER INDICATOR ============ */
    .stepper-header {
        display: flex;
        justify-content: space-between;
        position: relative;
        margin-bottom: 45px;
    }

    .stepper-header::before {
        content: '';
        position: absolute;
        top: 22px;
        left: 5%;
        right: 5%;
        height: 3px;
        background: #e2e8f0;
        z-index: 1;
    }

    .stepper-progress {
        position: absolute;
        top: 22px;
        left: 5%;
        height: 3px;
        background: var(--survei-primary);
        z-index: 2;
        transition: width 0.4s ease;
    }

    .step-item {
        position: relative;
        z-index: 3;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        width: 25%;
    }

    .step-circle {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: #ffffff;
        border: 3px solid #cbd5e1;
        color: #64748b;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    .step-item.active .step-circle {
        border-color: var(--survei-primary);
        background: var(--survei-primary);
        color: #ffffff;
        transform: scale(1.1);
        box-shadow: 0 0 0 6px rgba(16, 185, 129, 0.2);
    }

    .step-item.completed .step-circle {
        border-color: var(--survei-primary);
        background: var(--survei-primary);
        color: #ffffff;
    }

    .step-title {
        font-size: 0.85rem;
        font-weight: 600;
        color: #64748b;
        margin-top: 10px;
        transition: color 0.3s ease;
    }

    .step-item.active .step-title,
    .step-item.completed .step-title {
        color: #0f172a;
    }

    @media (max-width: 576px) {
        .step-title {
            display: none;
        }
        .step-circle {
            width: 38px;
            height: 38px;
            font-size: 0.85rem;
        }
    }

    /* ============ LAYANAN RADIO GRID (STEP 1) ============ */
    .layanan-radio-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 18px;
        margin-top: 20px;
    }

    .layanan-option-label {
        position: relative;
        display: flex;
        flex-direction: column;
        padding: 22px 18px;
        background: #ffffff;
        border: 2px solid #e2e8f0;
        border-radius: 16px;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        height: 100%;
    }

    .layanan-option-label:hover {
        border-color: #a7f3d0;
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(16, 185, 129, 0.08);
    }

    .layanan-radio-input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .layanan-radio-input:checked + .layanan-option-label {
        border-color: var(--survei-primary);
        background: #f0fdf4;
        box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.3);
    }

    .layanan-check-icon {
        position: absolute;
        top: 15px;
        right: 15px;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        border: 2px solid #cbd5e1;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        color: transparent;
        transition: all 0.2s ease;
    }

    .layanan-radio-input:checked + .layanan-option-label .layanan-check-icon {
        background: var(--survei-primary);
        border-color: var(--survei-primary);
        color: #ffffff;
    }

    .layanan-option-title {
        font-size: 1rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 6px;
        padding-right: 25px;
    }

    .layanan-option-desc {
        font-size: 0.82rem;
        color: #64748b;
        line-height: 1.4;
    }

    /* ============ STAR RATING (STEP 3) ============ */
    .pertanyaan-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 22px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .pertanyaan-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: #cbd5e1;
        transition: background 0.3s ease;
    }

    .pertanyaan-card.rated::before {
        background: var(--survei-primary);
    }

    .pertanyaan-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.04);
    }

    .pertanyaan-header {
        display: flex;
        align-items: flex-start;
        gap: 15px;
        margin-bottom: 18px;
    }

    .pertanyaan-number {
        width: 34px;
        height: 34px;
        background: #e0f2fe;
        color: #0284c7;
        font-weight: 700;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 0.95rem;
    }

    .pertanyaan-text {
        font-size: 0.98rem;
        font-weight: 600;
        color: #1e293b;
        line-height: 1.5;
    }

    .stars-group {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        margin: 15px 0 8px 0;
    }

    .star-btn {
        background: transparent;
        border: none;
        font-size: 2.2rem;
        color: #d1d5db;
        cursor: pointer;
        transition: transform 0.15s ease, color 0.15s ease;
        padding: 0 4px;
        line-height: 1;
    }

    .star-btn:hover {
        transform: scale(1.2);
    }

    .star-btn.active,
    .star-btn.hover {
        color: #f59e0b;
        text-shadow: 0 2px 8px rgba(245, 158, 11, 0.35);
    }

    .rating-label-display {
        text-align: center;
        font-size: 0.88rem;
        font-weight: 600;
        min-height: 22px;
        color: #64748b;
        transition: color 0.2s ease;
    }

    /* ============ STATISTIK & IKM STYLES ============ */
    .ikm-hero-card {
        background: linear-gradient(135deg, #065f46 0%, #047857 50%, #10b981 100%);
        border-radius: 20px;
        padding: 40px 30px;
        color: #ffffff;
        text-align: center;
        position: relative;
        overflow: hidden;
        margin-bottom: 30px;
        box-shadow: 0 20px 30px -10px rgba(5, 150, 105, 0.4);
    }

    .ikm-score-number {
        font-size: 4.5rem;
        font-weight: 900;
        line-height: 1;
        letter-spacing: -1px;
        margin: 12px 0;
        text-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }

    .ikm-mutu-badge {
        display: inline-block;
        padding: 8px 24px;
        border-radius: 30px;
        font-size: 1.15rem;
        font-weight: 700;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .stat-mini-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 24px 20px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        height: 100%;
        display: flex;
        align-items: center;
        gap: 18px;
    }

    .stat-mini-icon {
        width: 54px;
        height: 54px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
        flex-shrink: 0;
    }

    .chart-container-card {
        background: #ffffff;
        border-radius: 20px;
        border: 1px solid #e2e8f0;
        padding: 28px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
        height: 100%;
    }

    /* Buttons */
    .btn-step {
        padding: 12px 28px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.95rem;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }

    .btn-step-next {
        background: linear-gradient(135deg, #10b981, #059669);
        color: #ffffff;
        border: none;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .btn-step-next:hover {
        background: linear-gradient(135deg, #059669, #047857);
        color: #ffffff;
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(16, 185, 129, 0.4);
    }

    .btn-step-prev {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #cbd5e1;
    }

    .btn-step-prev:hover {
        background: #e2e8f0;
        color: #1e293b;
    }
</style>
@endpush

@section('content')
<!-- HERO -->
<section class="page-hero" style="--hero-bg: url('{{ asset('img/background/bg-1.jpg') }}');">
    <div class="container position-relative">
        <h1 class="page-title">Survei Kepuasan Dinas Ketahanan Pangan dan Pertanian</h1>
        <p class="page-subtitle">
            Partisipasi Anda sangat berharga untuk mengevaluasi dan meningkatkan mutu pelayanan publik DISKATAN Kabupaten Kuningan
        </p>

        <nav class="breadcrumb-wrapper">
            <a href="{{ url('/') }}">Home</a>
            <span>›</span>
            <a href="{{ url('/layanan') }}">Layanan</a>
            <span>›</span>
            <span class="active">Survei Kepuasan</span>
        </nav>
    </div>
</section>

<!-- MAIN CONTENT -->
<section class="survei-page-wrapper">
    <div class="container">

        <!-- TAB NAVIGATION -->
        <div class="survei-nav-container">
            <div class="survei-tabs">
                <button type="button" class="survei-tab-btn active" id="tabBtnForm" onclick="switchMainTab('form')">
                    <i class="bi bi-pencil-square"></i>
                    <span>Isi Survei Kepuasan</span>
                </button>
                <button type="button" class="survei-tab-btn" id="tabBtnStat" onclick="switchMainTab('stat')">
                    <i class="bi bi-bar-chart-line-fill"></i>
                    <span>Hasil & Mutu Pelayanan (IKM)</span>
                </button>
            </div>
        </div>

        <!-- ==================== TAB 1: FORMULIR SURVEI ==================== -->
        <div id="sectionFormSurvei">
            <div class="survei-card">
                <!-- STEPPER INDICATOR -->
                <div class="stepper-header">
                    <div class="stepper-progress" id="stepperProgress" style="width: 12.5%;"></div>
                    <div class="step-item active" id="stepIndicator1">
                        <div class="step-circle">1</div>
                        <div class="step-title">Pilih Layanan</div>
                    </div>
                    <div class="step-item" id="stepIndicator2">
                        <div class="step-circle">2</div>
                        <div class="step-title">Data Diri</div>
                    </div>
                    <div class="step-item" id="stepIndicator3">
                        <div class="step-circle">3</div>
                        <div class="step-title">Kuesioner</div>
                    </div>
                    <div class="step-item" id="stepIndicator4">
                        <div class="step-circle">4</div>
                        <div class="step-title">Saran & Kirim</div>
                    </div>
                </div>

                <!-- FORM -->
                <form id="formSurveiKepuasan">
                    @csrf

                    <!-- STEP 1: PILIH LAYANAN -->
                    <div class="step-content" id="stepContent1">
                        <div class="text-center mb-4">
                            <h3 class="fw-bold text-dark">Pilih Jenis Layanan yang Ingin Anda Nilai</h3>
                            <p class="text-muted">Pilih satu jenis pelayanan Dinas Ketahanan Pangan dan Pertanian yang pernah Anda gunakan:</p>
                        </div>

                        <div class="layanan-radio-grid">
                            @foreach ($layanan as $idx => $item)
                                <div>
                                    <input type="radio" name="jenis_layanan_id" id="layanan_{{ $item->id }}" value="{{ $item->id }}" class="layanan-radio-input" {{ $idx == 0 ? 'checked' : '' }}>
                                    <label for="layanan_{{ $item->id }}" class="layanan-option-label">
                                        <div class="layanan-check-icon">
                                            <i class="bi bi-check-lg"></i>
                                        </div>
                                        <div class="layanan-option-title">{{ $item->nama_layanan }}</div>
                                        <div class="layanan-option-desc">{{ $item->deskripsi ?? 'Layanan operasional DISKATAN Kuningan' }}</div>
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                            <button type="button" class="btn btn-step btn-step-next" onclick="goToStep(2)">
                                Lanjut: Data Diri
                                <i class="bi bi-arrow-right"></i>
                            </button>
                        </div>
                    </div>

                    <!-- STEP 2: DATA RESPONDEN -->
                    <div class="step-content d-none" id="stepContent2">
                        <div class="text-center mb-4">
                            <h3 class="fw-bold text-dark">Identitas Responden</h3>
                            <p class="text-muted">Informasi ini dijaga kerahasiaannya dan hanya digunakan untuk keperluan statistik pelayanan dinas:</p>
                        </div>

                        <div class="row g-3 justify-content-center">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold text-dark">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" name="nama_lengkap" id="inputNamaLengkap" class="form-control form-control-lg rounded-3" placeholder="Masukkan nama lengkap Anda..." required>
                                </div>

                                <div class="row g-3 mb-3">
                                    <div class="col-sm-6">
                                        <label class="form-label fw-semibold text-dark">Kelompok Usia <span class="text-danger">*</span></label>
                                        <select name="usia" id="inputUsia" class="form-select form-select-lg rounded-3" required>
                                            <option value="">-- Pilih Rentang Usia --</option>
                                            <option value="17-25">17 - 25 Tahun</option>
                                            <option value="26-35">26 - 35 Tahun</option>
                                            <option value="36-45">36 - 45 Tahun</option>
                                            <option value="46-55">46 - 55 Tahun</option>
                                            <option value="56+">56 Tahun ke Atas</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-label fw-semibold text-dark">Jenis Kelamin <span class="text-danger">*</span></label>
                                        <select name="jenis_kelamin" id="inputJenisKelamin" class="form-select form-select-lg rounded-3" required>
                                            <option value="">-- Pilih Jenis Kelamin --</option>
                                            <option value="Laki-laki">Laki-laki</option>
                                            <option value="Perempuan">Perempuan</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row g-3 mb-3">
                                    <div class="col-sm-6">
                                        <label class="form-label fw-semibold text-dark">Pekerjaan</label>
                                        <input type="text" name="pekerjaan" id="inputPekerjaan" class="form-control form-control-lg rounded-3" placeholder="Contoh: Petani, Wiraswasta, PNS, dll">
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-label fw-semibold text-dark">Nomor Handphone / WhatsApp</label>
                                        <input type="text" name="no_telepon" id="inputNoTelepon" class="form-control form-control-lg rounded-3" placeholder="08xxxxxxxxxx (opsional)">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                            <button type="button" class="btn btn-step btn-step-prev" onclick="goToStep(1)">
                                <i class="bi bi-arrow-left"></i>
                                Kembali
                            </button>
                            <button type="button" class="btn btn-step btn-step-next" onclick="validateAndGoToStep(3)">
                                Lanjut: Isi Kuesioner
                                <i class="bi bi-arrow-right"></i>
                            </button>
                        </div>
                    </div>

                    <!-- STEP 3: KUESIONER BINTANG 1-5 -->
                    <div class="step-content d-none" id="stepContent3">
                        <div class="text-center mb-4">
                            <h3 class="fw-bold text-dark">Penilaian Kualitas Pelayanan</h3>
                            <p class="text-muted">Berikan penilaian bintang (1 ⭐ s/d 5 ⭐) secara objektif pada setiap butir pertanyaan di bawah ini:</p>
                        </div>

                        <div class="pertanyaan-container">
                            @foreach ($pertanyaan as $pIndex => $p)
                                <div class="pertanyaan-card" id="cardPertanyaan_{{ $p->id }}">
                                    <div class="pertanyaan-header">
                                        <div class="pertanyaan-number">{{ $pIndex + 1 }}</div>
                                        <div class="pertanyaan-text">
                                            {{ $p->teks_pertanyaan }}
                                        </div>
                                    </div>

                                    <!-- Rating Stars -->
                                    <input type="hidden" name="ratings[{{ $p->id }}]" id="ratingInput_{{ $p->id }}" value="" required>
                                    
                                    <div class="stars-group" data-pertanyaan-id="{{ $p->id }}">
                                        @for ($star = 1; $star <= 5; $star++)
                                            <button type="button" class="star-btn" data-value="{{ $star }}" onclick="setRating({{ $p->id }}, {{ $star }})" onmouseenter="hoverRating({{ $p->id }}, {{ $star }})" onmouseleave="resetHover({{ $p->id }})">
                                                ★
                                            </button>
                                        @endfor
                                    </div>

                                    <div class="rating-label-display" id="labelDisplay_{{ $p->id }}">
                                        Klik bintang untuk menilai
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                            <button type="button" class="btn btn-step btn-step-prev" onclick="goToStep(2)">
                                <i class="bi bi-arrow-left"></i>
                                Kembali
                            </button>
                            <button type="button" class="btn btn-step btn-step-next" onclick="validateRatingsAndGoToStep(4)">
                                Lanjut: Saran & Masukan
                                <i class="bi bi-arrow-right"></i>
                            </button>
                        </div>
                    </div>

                    <!-- STEP 4: SARAN & KIRIM -->
                    <div class="step-content d-none" id="stepContent4">
                        <div class="text-center mb-4">
                            <h3 class="fw-bold text-dark">Kritik, Saran & Aspirasi</h3>
                            <p class="text-muted">Bantu kami menjadi lebih baik dengan menyampaikan masukan dan pesan Anda:</p>
                        </div>

                        <div class="row justify-content-center">
                            <div class="col-md-9">
                                <div class="mb-4">
                                    <label class="form-label fw-semibold text-dark">Saran & Masukan untuk DISKATAN Kuningan</label>
                                    <textarea name="komentar" id="inputKomentar" rows="5" class="form-control rounded-3" placeholder="Tuliskan saran, kritik, atau pengalaman Anda selama mendapatkan pelayanan di sini..."></textarea>
                                </div>

                                <div class="p-3 bg-light rounded-3 border mb-4">
                                    <div class="d-flex align-items-center gap-3">
                                        <i class="bi bi-shield-check text-success fs-2"></i>
                                        <div>
                                            <div class="fw-bold text-dark">Pernyataan Kejujuran</div>
                                            <small class="text-muted">Data penilaian yang Anda kirimkan akan dienkripsi dan diolah menjadi Indeks Kepuasan Masyarakat (IKM) resmi sesuai pedoman PermenPAN-RB No. 14 Tahun 2017.</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                            <button type="button" class="btn btn-step btn-step-prev" onclick="goToStep(3)">
                                <i class="bi bi-arrow-left"></i>
                                Kembali
                            </button>
                            <button type="submit" class="btn btn-step btn-step-next" id="btnSubmitSurvei">
                                <i class="bi bi-send-check"></i>
                                <span>Kirim Survei Kepuasan</span>
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>

        <!-- ==================== TAB 2: LIVE STATISTIK IKM ==================== -->
        <div id="sectionStatistikSurvei" class="d-none">
            <!-- IKM HERO CARD -->
            <div class="ikm-hero-card">
                <div class="text-uppercase fw-semibold tracking-wider text-green-100 mb-1" style="font-size: 0.95rem; letter-spacing: 1.5px;">
                    Indeks Kepuasan Masyarakat (IKM)
                </div>
                <h2 class="fw-bold text-white mb-2">Dinas Ketahanan Pangan dan Pertanian</h2>
                
                <div class="ikm-score-number" id="displayIkmScore">
                    {{ number_format($skorIkm, 2) }}
                </div>

                <div class="mb-3">
                    <span class="ikm-mutu-badge {{ $badgeClass }}" id="displayMutuBadge">
                        Mutu Pelayanan: {{ $mutuPelayanan }}
                    </span>
                </div>

                <p class="text-white-50 mx-auto mb-0" style="max-width: 650px; font-size: 0.92rem;">
                    Berdasarkan PermenPAN-RB No. 14 Tahun 2017 konversi skala 100 dengan partisipasi aktif masyarakat Kabupaten Kuningan.
                </p>
            </div>

            <!-- MINI METRICS -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="stat-mini-card">
                        <div class="stat-mini-icon bg-primary bg-opacity-10 text-primary">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <div>
                            <div class="text-muted small fw-semibold">Total Responden</div>
                            <div class="fs-3 fw-bold text-dark">{{ number_format($totalResponden) }}</div>
                            <small class="text-success"><i class="bi bi-check-circle me-1"></i>Masyarakat Terlayani</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-mini-card">
                        <div class="stat-mini-icon bg-warning bg-opacity-10 text-warning">
                            <i class="bi bi-star-fill"></i>
                        </div>
                        <div>
                            <div class="text-muted small fw-semibold">Rata-rata Rating Bintang</div>
                            <div class="fs-3 fw-bold text-dark">
                                {{ $skorIkm > 0 ? number_format($skorIkm / 20, 2) : '0.00' }} / 5.0
                            </div>
                            <small class="text-muted">Skala Penilaian 5 Bintang</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-mini-card">
                        <div class="stat-mini-icon bg-success bg-opacity-10 text-success">
                            <i class="bi bi-patch-check-fill"></i>
                        </div>
                        <div>
                            <div class="text-muted small fw-semibold">Layanan Terdaftar</div>
                            <div class="fs-3 fw-bold text-dark">{{ $layanan->count() }}</div>
                            <small class="text-muted">Layanan Aktif Dinilai</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CHARTS SECTION -->
            <div class="row g-4 mb-4">
                <div class="col-lg-7">
                    <div class="chart-container-card">
                        <h5 class="fw-bold text-dark mb-3">
                            <i class="bi bi-bar-chart-fill text-success me-2"></i>
                            Rata-Rata Skor per Unsur Pelayanan
                        </h5>
                        <p class="text-muted small mb-4">Perbandingan kepuasan warga pada setiap indikator standar pelayanan:</p>
                        <div style="height: 320px;">
                            <canvas id="chartUnsurPelayanan"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="chart-container-card">
                        <h5 class="fw-bold text-dark mb-3">
                            <i class="bi bi-pie-chart-fill text-warning me-2"></i>
                            Distribusi Bintang Kepuasan
                        </h5>
                        <p class="text-muted small mb-4">Persentase jumlah bintang yang diberikan oleh seluruh responden:</p>
                        <div style="height: 280px;">
                            <canvas id="chartDistribusiRating"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- LIST BUTIR PERTANYAAN -->
            <div class="survei-card">
                <h5 class="fw-bold text-dark mb-4">
                    <i class="bi bi-list-check text-primary me-2"></i>
                    Rincian Nilai per Indikator Pelayanan
                </h5>
                <div class="row g-3">
                    @foreach ($pertanyaan as $item)
                        @php
                            $avg = $ratingsByPertanyaan[$item->id] ?? 0;
                            $percent = ($avg / 5) * 100;
                        @endphp
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded-3 border">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-bold text-dark small">{{ $item->teks_pertanyaan }}</span>
                                    <span class="badge bg-success ms-2">{{ number_format($avg, 2) }} / 5.0</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $percent }}%;" aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

    </div>
</section>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // ============ TAB SWITCHING ============
    function switchMainTab(tab) {
        const btnForm = document.getElementById('tabBtnForm');
        const btnStat = document.getElementById('tabBtnStat');
        const secForm = document.getElementById('sectionFormSurvei');
        const secStat = document.getElementById('sectionStatistikSurvei');

        if (tab === 'form') {
            btnForm.classList.add('active');
            btnStat.classList.remove('active');
            secForm.classList.remove('d-none');
            secStat.classList.add('d-none');
        } else {
            btnStat.classList.add('active');
            btnForm.classList.remove('active');
            secStat.classList.remove('d-none');
            secForm.classList.add('d-none');
            initCharts();
        }
    }

    // ============ STEPPER LOGIC ============
    let currentStep = 1;
    const totalSteps = 4;

    function goToStep(step) {
        // Hide all
        for (let i = 1; i <= totalSteps; i++) {
            document.getElementById('stepContent' + i).classList.add('d-none');
            const ind = document.getElementById('stepIndicator' + i);
            ind.classList.remove('active');
            if (i < step) {
                ind.classList.add('completed');
            } else {
                ind.classList.remove('completed');
            }
        }

        // Show target
        document.getElementById('stepContent' + step).classList.remove('d-none');
        document.getElementById('stepIndicator' + step).classList.add('active');
        document.getElementById('stepIndicator' + step).classList.remove('completed');

        // Progress bar
        const progressPercent = ((step - 0.5) / totalSteps) * 100;
        document.getElementById('stepperProgress').style.width = progressPercent + '%';

        currentStep = step;
        window.scrollTo({ top: 350, behavior: 'smooth' });
    }

    function validateAndGoToStep(nextStep) {
        const nama = document.getElementById('inputNamaLengkap').value.trim();
        const usia = document.getElementById('inputUsia').value;
        const jk = document.getElementById('inputJenisKelamin').value;

        if (!nama) {
            Swal.fire({
                icon: 'warning',
                title: 'Data Belum Lengkap',
                text: 'Harap isi Nama Lengkap Anda sebelum melanjutkan.',
                confirmButtonColor: '#10b981'
            });
            document.getElementById('inputNamaLengkap').focus();
            return;
        }

        if (!usia) {
            Swal.fire({
                icon: 'warning',
                title: 'Data Belum Lengkap',
                text: 'Harap pilih Kelompok Usia Anda.',
                confirmButtonColor: '#10b981'
            });
            document.getElementById('inputUsia').focus();
            return;
        }

        if (!jk) {
            Swal.fire({
                icon: 'warning',
                title: 'Data Belum Lengkap',
                text: 'Harap pilih Jenis Kelamin Anda.',
                confirmButtonColor: '#10b981'
            });
            document.getElementById('inputJenisKelamin').focus();
            return;
        }

        goToStep(nextStep);
    }

    // ============ STAR RATING HANDLER ============
    const starLabels = {
        1: 'Sangat Kurang Puas 😡',
        2: 'Kurang Puas 🙁',
        3: 'Cukup Puas 😐',
        4: 'Puas 😊',
        5: 'Sangat Puas 🤩'
    };

    function setRating(pId, val) {
        document.getElementById('ratingInput_' + pId).value = val;
        document.getElementById('cardPertanyaan_' + pId).classList.add('rated');
        
        const labelDisplay = document.getElementById('labelDisplay_' + pId);
        labelDisplay.innerHTML = `<span class="text-warning fw-bold">${starLabels[val]}</span> (${val} dari 5 Bintang)`;

        const container = document.querySelector(`.stars-group[data-pertanyaan-id="${pId}"]`);
        const buttons = container.querySelectorAll('.star-btn');

        buttons.forEach((btn, index) => {
            if (index < val) {
                btn.classList.add('active');
            } else {
                btn.classList.remove('active');
            }
        });
    }

    function hoverRating(pId, val) {
        const container = document.querySelector(`.stars-group[data-pertanyaan-id="${pId}"]`);
        const buttons = container.querySelectorAll('.star-btn');
        buttons.forEach((btn, index) => {
            if (index < val) {
                btn.classList.add('hover');
            } else {
                btn.classList.remove('hover');
            }
        });
    }

    function resetHover(pId) {
        const container = document.querySelector(`.stars-group[data-pertanyaan-id="${pId}"]`);
        const buttons = container.querySelectorAll('.star-btn');
        buttons.forEach(btn => btn.classList.remove('hover'));
    }

    function validateRatingsAndGoToStep(nextStep) {
        const questions = @json($pertanyaan->pluck('id'));
        let missing = [];

        questions.forEach((id, idx) => {
            const val = document.getElementById('ratingInput_' + id).value;
            if (!val) {
                missing.push(idx + 1);
            }
        });

        if (missing.length > 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Penilaian Belum Lengkap',
                text: `Harap berikan bintang rating pada pertanyaan nomor: ${missing.join(', ')}`,
                confirmButtonColor: '#10b981'
            });
            return;
        }

        goToStep(nextStep);
    }

    // ============ SUBMIT FORM VIA AJAX ============
    document.getElementById('formSurveiKepuasan').addEventListener('submit', function(e) {
        e.preventDefault();

        const btnSubmit = document.getElementById('btnSubmitSurvei');
        const originalContent = btnSubmit.innerHTML;
        btnSubmit.disabled = true;
        btnSubmit.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Mengirim...`;

        const formData = new FormData(this);

        fetch("{{ route('survei.store') }}", {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            btnSubmit.disabled = false;
            btnSubmit.innerHTML = originalContent;

            if (data.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Survei Berhasil Dikirim!',
                    text: data.message,
                    confirmButtonColor: '#10b981',
                    confirmButtonText: 'Lihat Hasil Mutu Pelayanan'
                }).then(() => {
                    document.getElementById('formSurveiKepuasan').reset();
                    // Reset ratings
                    document.querySelectorAll('input[name^="ratings"]').forEach(input => input.value = '');
                    document.querySelectorAll('.star-btn').forEach(btn => btn.classList.remove('active'));
                    document.querySelectorAll('.rating-label-display').forEach(lbl => lbl.innerHTML = 'Klik bintang untuk menilai');
                    goToStep(1);
                    // Switch to statistik tab & reload data
                    window.location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Menyimpan Survei',
                    text: data.message || 'Terjadi kesalahan sistem.',
                    confirmButtonColor: '#ef4444'
                });
            }
        })
        .catch(err => {
            btnSubmit.disabled = false;
            btnSubmit.innerHTML = originalContent;
            Swal.fire({
                icon: 'error',
                title: 'Error Koneksi',
                text: 'Tidak dapat terhubung ke server. Periksa koneksi internet Anda.',
                confirmButtonColor: '#ef4444'
            });
        });
    });

    // ============ CHART INITIALIZATION ============
    let chartUnsur = null;
    let chartDist = null;

    function initCharts() {
        if (chartUnsur !== null) return; // already initialized

        // Chart Unsur Pelayanan
        const ctxUnsur = document.getElementById('chartUnsurPelayanan');
        if (ctxUnsur) {
            const rawPertanyaan = @json($pertanyaan);
            const rawRatings = @json($ratingsByPertanyaan);

            const labels = rawPertanyaan.map(p => {
                const words = p.teks_pertanyaan.split(' ');
                return words.slice(0, 5).join(' ') + '...';
            });
            const dataScores = rawPertanyaan.map(p => {
                const avg = rawRatings[p.id] || 0;
                return parseFloat(avg).toFixed(2);
            });

            chartUnsur = new Chart(ctxUnsur, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Skor Rata-rata (Skala 1-5)',
                        data: dataScores,
                        backgroundColor: 'rgba(16, 185, 129, 0.75)',
                        borderColor: '#059669',
                        borderWidth: 2,
                        borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 5,
                            ticks: { stepSize: 1 }
                        }
                    },
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        }

        // Chart Distribusi Bintang
        const ctxDist = document.getElementById('chartDistribusiRating');
        if (ctxDist) {
            const distData = @json($ratingDistribution);

            chartDist = new Chart(ctxDist, {
                type: 'doughnut',
                data: {
                    labels: ['5 Bintang (Sangat Puas)', '4 Bintang (Puas)', '3 Bintang (Cukup)', '2 Bintang (Kurang)', '1 Bintang (Sangat Kurang)'],
                    datasets: [{
                        data: [distData[5], distData[4], distData[3], distData[2], distData[1]],
                        backgroundColor: [
                            '#10b981',
                            '#3b82f6',
                            '#f59e0b',
                            '#f97316',
                            '#ef4444'
                        ],
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { boxWidth: 12, font: { size: 11 } }
                        }
                    }
                }
            });
        }
    }
</script>
@endpush
