@extends('public.layouts.app')

@section('title', 'Daftar Pejabat')

@push('styles')
    <style>
        /* ============ PAGE HERO SECTION ============ */
        .page-hero {
            position: relative;
            height: 400px;
            background: linear-gradient(135deg, rgba(45, 106, 79, 0.85), rgba(64, 145, 108, 0)),
                var(--hero-bg);
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .page-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at top right, rgba(116, 198, 157, 0.2), transparent 60%),
                radial-gradient(circle at bottom left, rgba(45, 106, 79, 0.2), transparent 60%);
            animation: gradientShift 8s ease infinite;
        }

        @keyframes gradientShift {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.7;
            }
        }

        .page-hero .container {
            z-index: 2;
        }

        .page-title {
            font-family: 'Playfair Display', serif;
            font-size: 3.5rem;
            font-weight: 900;
            color: white;
            text-align: center;
            margin-bottom: 1.5rem;
            text-shadow: 2px 2px 20px rgba(0, 0, 0, 0.3);
            animation: fadeInDown 1s ease-out;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ============ BREADCRUMB ============ */
        .breadcrumb-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-size: 1.1rem;
            animation: fadeInUp 1s ease-out 0.2s both;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .breadcrumb-wrapper a {
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .breadcrumb-wrapper a:hover {
            color: #74c69d;
            transform: translateX(-2px);
        }

        .breadcrumb-wrapper span {
            color: rgba(255, 255, 255, 0.7);
        }

        .breadcrumb-wrapper .active {
            color: #74c69d;
            font-weight: 600;
        }

        /* ============ CONTENT SECTION ============ */
        .content-section {
            padding: 100px 0;
            background: linear-gradient(135deg, #f8f9fa, #e8f5e9);
            position: relative;
        }

        .content-section::before {
            content: '';
            position: absolute;
            top: -50px;
            left: 0;
            right: 0;
            height: 100px;
            background: linear-gradient(135deg, #f8f9fa, #e8f5e9);
            clip-path: polygon(0 50%, 100% 0, 100% 100%, 0 100%);
        }

        /* ============ TABLE CARD ============ */
        .table-card {
            background: white;
            border-radius: 25px;
            box-shadow: 0 15px 50px rgba(45, 106, 79, 0.1);
            border: none;
            overflow: hidden;
            position: relative;
            transition: all 0.4s ease;
        }

        .table-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #2d6a4f, #40916c, #52b788, #74c69d);
        }

        .table-card:hover {
            box-shadow: 0 20px 60px rgba(45, 106, 79, 0.15);
        }

        .table-card .card-body {
            padding: 40px;
        }

        /* ============ DATATABLE CUSTOM STYLING ============ */
        .datatable-wrapper {
            margin-top: 20px;
        }

        /* Table Header */
        .table thead th {
            background: linear-gradient(135deg, #2d6a4f, #40916c) !important;
            color: white !important;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.9rem;
            letter-spacing: 0.5px;
            padding: 18px 15px !important;
            border: none !important;
            vertical-align: middle;
        }

        /* Table Body */
        .table tbody tr {
            transition: all 0.3s ease;
            border-bottom: 1px solid #e8f5e9;
        }

        .table tbody tr:hover {
            background: linear-gradient(135deg, rgba(82, 183, 136, 0.05), rgba(116, 198, 157, 0.05)) !important;
            transform: scale(1.01);
            box-shadow: 0 3px 10px rgba(45, 106, 79, 0.1);
        }

        .table tbody td {
            padding: 18px 15px !important;
            vertical-align: middle;
            color: #333;
            font-size: 0.95rem;
        }

        .table tbody td:first-child {
            font-weight: 700;
            color: #2d6a4f;
            font-size: 1rem;
        }

        /* Striped Rows */
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(248, 249, 250, 0.5);
        }

        /* DataTable Controls */
        .dataTables_wrapper .dataTables_length select {
            border: 2px solid #e8f5e9;
            border-radius: 8px;
            padding: 8px 35px 8px 12px;
            color: #2d6a4f;
            font-weight: 600;
            transition: all 0.3s ease;
            background: white;
        }

        .dataTables_wrapper .dataTables_length select:focus {
            border-color: #52b788;
            outline: none;
            box-shadow: 0 0 0 3px rgba(82, 183, 136, 0.1);
        }

        .dataTables_wrapper .dataTables_filter input {
            border: 2px solid #e8f5e9;
            border-radius: 8px;
            padding: 10px 15px;
            margin-left: 10px;
            transition: all 0.3s ease;
        }

        .dataTables_wrapper .dataTables_filter input:focus {
            border-color: #52b788;
            outline: none;
            box-shadow: 0 0 0 3px rgba(82, 183, 136, 0.1);
        }

        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            margin-bottom: 20px;
        }

        .dataTables_wrapper .dataTables_length label,
        .dataTables_wrapper .dataTables_filter label {
            font-weight: 600;
            color: #2d6a4f;
        }

        /* Pagination */
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 8px 15px;
            margin: 0 3px;
            border-radius: 8px;
            border: 2px solid #e8f5e9;
            background: white;
            color: #2d6a4f !important;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: linear-gradient(135deg, #52b788, #74c69d) !important;
            border-color: #52b788 !important;
            color: white !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(45, 106, 79, 0.2);
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: linear-gradient(135deg, #2d6a4f, #40916c) !important;
            border-color: #2d6a4f !important;
            color: white !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Info Text */
        .dataTables_wrapper .dataTables_info {
            padding-top: 20px;
            color: #666;
            font-weight: 500;
        }

        /* Processing Indicator */
        .dataTables_wrapper .dataTables_processing {
            background: linear-gradient(135deg, #2d6a4f, #40916c);
            color: white;
            border-radius: 10px;
            padding: 15px 30px;
            font-weight: 600;
        }

        /* No Data Message */
        .dataTables_empty {
            padding: 40px !important;
            color: #999;
            font-style: italic;
        }

        /* Responsive Table */
        .table-responsive {
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        /* Badge Styling for Specific Columns */
        .badge-golongan {
            background: linear-gradient(135deg, #2d6a4f, #40916c);
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
        }

        /* ============ DECORATIVE PATTERN ============ */
        .pattern-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            pointer-events: none;
            z-index: 1;
            opacity: 0.03;
            background-image:
                repeating-linear-gradient(45deg, transparent, transparent 35px, #2d6a4f 35px, #2d6a4f 36px),
                repeating-linear-gradient(-45deg, transparent, transparent 35px, #2d6a4f 35px, #2d6a4f 36px);
        }

        /* ============ RESPONSIVE ============ */
        @media (max-width: 768px) {
            .page-title {
                font-size: 2.5rem;
            }

            .table-card .card-body {
                padding: 25px 15px;
            }

            .table thead th,
            .table tbody td {
                font-size: 0.85rem !important;
                padding: 12px 10px !important;
            }

            .dataTables_wrapper .dataTables_length,
            .dataTables_wrapper .dataTables_filter,
            .dataTables_wrapper .dataTables_info,
            .dataTables_wrapper .dataTables_paginate {
                text-align: center;
                margin-bottom: 15px;
            }
        }
    </style>
@endpush

@section('content')
    <!-- Pattern Overlay -->
    <div class="pattern-overlay"></div>

    <!-- HERO -->
    <section class="page-hero" style="--hero-bg: url('{{ asset('img/background/bg-2.jpg') }}');">
        <div class="container position-relative">
            <h1 class="page-title">Daftar Pejabat</h1>

            <nav class="breadcrumb-wrapper">
                <a href="{{ url('/') }}">Beranda</a>
                <span>›</span>
                <a href="#">Profil</a>
                <span>›</span>
                <span class="active">Daftar Pejabat</span>
            </nav>
        </div>
    </section>

    <!-- CONTENT -->
    <section class="content-section">
        <div class="container">

            <div class="card table-card" data-aos="fade-up" data-aos-duration="1000">
                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table table-striped datatable display nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th height="40"><strong>NO</strong></th>
                                    
                                    <th><strong>Nama Lengkap</strong></th>
                                    <th><strong>Pangkat</strong></th>
                                    <th><strong>GolRu</strong></th>
                                    <th><strong>Jabatan</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td height="20">1</td>
                                    
                                    <td>Dr. WAHYU HIDAYAH, M.Si.</td>
                                    <td>Pembina Utama Muda</td>
                                    <td>IV/c</td>
                                    <td>Kepala Dinas</td>
                                </tr>
                                <tr>
                                    <td height="20">2</td>
                                    
                                    <td>H. SANUSI, SP., MP.</td>
                                    <td>Pembina</td>
                                    <td>IV/a</td>
                                    <td>Sekretaris Dinas</td>
                                </tr>
                                <tr>
                                    <td height="40">3</td>
                                    
                                    <td>SOPYAN PAMUNGKAS, S.Hut, M.Si.</td>
                                    <td>Pembina</td>
                                    <td>IV/a</td>
                                    <td>Kepala Bidang Pengolahan Hasil dan Konsumsi Pangan</td>
                                </tr>
                                <tr>
                                    <td height="40">4</td>
                                    
                                    <td>Hj. DEASY MURIAWATY, ST., M.Si.</td>
                                    <td>Pembina</td>
                                    <td>IV/a</td>
                                    <td>Kepala Bidang Tanaman Pangan</td>
                                </tr>
                                <tr>
                                    <td height="40">5</td>
                                    
                                    <td>ANDI, SE., MM.</td>
                                    <td>Pembina</td>
                                    <td>IV/a</td>
                                    <td>Kepala Bidang Hortikultura dan Perkebunan</td>
                                </tr>
                                <tr>
                                    <td height="40">6</td>
                                    
                                    <td>Hj. IIS HENY ROHAENY, S.Pi., MM.</td>
                                    <td>Pembina</td>
                                    <td>IV/a</td>
                                    <td>Kepala Bidang Penyuluhan</td>
                                </tr>
                                <tr>
                                    <td height="40">7</td>
                                    
                                    <td>ATING SETIAWAN, SP.</td>
                                    <td>Penata Tk.I</td>
                                    <td>III/d</td>
                                    <td>Kepala Bidang Cadangan dan Distribusi Pangan</td>
                                </tr>
                                <tr>
                                    <td height="40">8</td>
                                    
                                    <td>Hj. LIES ERNAWATI, S.TP., M.P.</td>
                                    <td>Pembina</td>
                                    <td>IV/a</td>
                                    <td>Kepala UPTD Ketahanan Pangan dan Pertanian Mandirancan</td>
                                </tr>
                                <tr>
                                    <td height="40">9</td>
                                    
                                    <td>NIA KURNIASARI, SE., MM.</td>
                                    <td>Pembina</td>
                                    <td>IV/a</td>
                                    <td>Kepala UPTD Brigade Alat Mesin Pertanian</td>
                                </tr>
                                <tr>
                                    <td height="40">10</td>
                                    
                                    <td>SUTARNA, SP.</td>
                                    <td>Penata Tk.I</td>
                                    <td>III/d</td>
                                    <td>Kepala UPTD Ketahanan Pangan dan Pertanian Ciniru</td>
                                </tr>
                                <tr>
                                    <td height="40">11</td>
                                    
                                    <td>IDI RUSTANDI, S.Pi.</td>
                                    <td>Penata Tk.I</td>
                                    <td>III/d</td>
                                    <td>Kepala UPTD Ketahanan Pangan dan Pertanian Kuningan</td>
                                </tr>
                                <tr>
                                    <td height="40">12</td>
                                    
                                    <td>ASEP ZULKARNAEN, SP.</td>
                                    <td>Penata Tk.I</td>
                                    <td>III/d</td>
                                    <td>Kepala UPTD Ketahanan Pangan dan Pertanian Ciawigebang</td>
                                </tr>
                                <tr>
                                    <td height="40">13</td>
                                    
                                    <td>PURNAMAWATI INDAH, SP.</td>
                                    <td>Penata Tk.I</td>
                                    <td>III/d</td>
                                    <td>Kepala UPTD Ketahanan Pangan dan Pertanian Darma</td>
                                </tr>
                                <tr>
                                    <td height="40">14</td>
                                    
                                    <td>SUNENGSIH, S.Sos.</td>
                                    <td>Penata Tk.I</td>
                                    <td>III/d</td>
                                    <td>Kepala UPTD Balai Benih Hortikultura dan Kebun Bibit Permanen</td>
                                </tr>
                                <tr>
                                    <td height="40">15</td>
                                    
                                    <td>DEDE IRAWAN, SE.</td>
                                    <td>Penata Tk.I</td>
                                    <td>III/d</td>
                                    <td>Kepala UPTD Ketahanan Pangan dan Pertanian Luragung</td>
                                </tr>
                                <tr>
                                    <td height="40">16</td>
                                    
                                    <td>DIDI RUSNADI, S.Sos., M.Si.</td>
                                    <td>Penata Tk.I</td>
                                    <td>III/d</td>
                                    <td>Kepala UPTD Ketahanan Pangan dan Pertanian Cipicung</td>
                                </tr>
                                <tr>
                                    <td height="40">17</td>
                                    
                                    <td>YUDI FRAYUDI, SP.</td>
                                    <td>Penata Tk.I</td>
                                    <td>III/d</td>
                                    <td>Kepala UPTD Brigade Proteksi Tanaman</td>
                                </tr>
                                <tr>
                                    <td height="40">18</td>
                                    
                                    <td>IRPAN TAOFIK S, S.AP.</td>
                                    <td>Penata Tk.I</td>
                                    <td>III/d</td>
                                    <td>Kepala UPTD Ketahanan Pangan dan Pertanian Cidahu</td>
                                </tr>
                                <tr>
                                    <td height="40">19</td>
                                    
                                    <td>DEDEN RAMDHANA, SE.</td>
                                    <td>Penata Tk.I</td>
                                    <td>III/d</td>
                                    <td>Kepala UPTD Ketahanan Pangan dan Pertanian Kadugede</td>
                                </tr>
                                <tr>
                                    <td height="40">20</td>
                                    
                                    <td>RUSMANA, SP.</td>
                                    <td>Penata Tk.I</td>
                                    <td>III/d</td>
                                    <td>Kepala UPTD Ketahanan Pangan dan Pertanian Lebakwangi</td>
                                </tr>
                                <tr>
                                    <td height="40">21</td>
                                    
                                    <td>H. TOTONG MUH. SOLIH, S.IP.</td>
                                    <td>Penata Tk.I</td>
                                    <td>III/d</td>
                                    <td>Kepala UPTD Ketahanan Pangan dan Pertanian Garawangi</td>
                                </tr>
                                <tr>
                                    <td height="40">22</td>
                                    
                                    <td>ROKIDIN, SE.</td>
                                    <td>Penata Tk.I</td>
                                    <td>III/d</td>
                                    <td>Kepala UPTD Ketahanan Pangan dan Pertanian Cibingbin</td>
                                </tr>
                                <tr>
                                    <td height="40">23</td>
                                    
                                    <td>OTONG SUHENDI, SP.</td>
                                    <td>Penata Tk.I</td>
                                    <td>III/d</td>
                                    <td>Kepala UPTD Ketahanan Pangan dan Pertanian Jalaksana</td>
                                </tr>
                                <tr>
                                    <td height="40">24</td>
                                    
                                    <td>CECEP HARUN SOHAR</td>
                                    <td>Penata</td>
                                    <td>III/c</td>
                                    <td>Kepala UPTD Balai Benih Padi dan Palawija</td>
                                </tr>
                                <tr>
                                    <td height="40">25</td>
                                    
                                    <td>WAWAN GUNAWAN, SE.</td>
                                    <td>Penata</td>
                                    <td>III/c</td>
                                    <td>Kepala Sub Bagian Umum dan Kepegawaian</td>
                                </tr>
                                <tr>
                                    <td height="40">26</td>
                                    
                                    <td>DIYANA, ST.</td>
                                    <td>Penata</td>
                                    <td>III/c</td>
                                    <td>Kepala UPTD Ketahanan Pangan dan Pertanian Cilimus</td>
                                </tr>
                                <tr>
                                    <td height="40">27</td>
                                    
                                    <td>CEPI FIRMANSYAH, S.AP, MM.</td>
                                    <td>Penata</td>
                                    <td>III/c</td>
                                    <td>Kepala UPTD Ketahanan Pangan dan Pertanian Subang</td>
                                </tr>
                                <tr>
                                    <td height="40">28</td>
                                    
                                    <td>ARIS OKTIANA RACHMAN, S.IP.</td>
                                    <td>Penata</td>
                                    <td>III/c</td>
                                    <td>Kepala UPTD Ketahanan Pangan dan Pertanian Ciwaru</td>
                                </tr>
                                <tr>
                                    <td height="40">29</td>
                                    
                                    <td>PULUNG, S.Sos.</td>
                                    <td>Penata Tk.I</td>
                                    <td>III/d</td>
                                    <td>Kepala Sub Bagian Tata Usaha UPTD Ketahanan Pangan dan Pertanian Ciawigebang
                                    </td>
                                </tr>
                                <tr>
                                    <td height="40">30</td>
                                    
                                    <td>ADE SRI WAHYUNI, SE.</td>
                                    <td>Penata Tk.I</td>
                                    <td>III/d</td>
                                    <td>Kepala Sub Bagian Tata Usaha UPTD Balai Benih Padi dan Palawija</td>
                                </tr>
                                <tr>
                                    <td height="40">31</td>
                                    
                                    <td>OPA SOPARIAH, SP.</td>
                                    <td>Penata Tk.I</td>
                                    <td>III/d</td>
                                    <td>Kepala Sub Bagian Tata Usaha UPTD Ketahanan Pangan dan Pertanian Mandirancan
                                    </td>
                                </tr>
                                <tr>
                                    <td height="40">32</td>
                                    
                                    <td>DADANG HERMAWAN, SH.,M.Si.</td>
                                    <td>Penata Tk.I</td>
                                    <td>III/d</td>
                                    <td>Kepala Sub Bagian Tata Usaha UPTD Ketahanan Pangan dan Pertanian Cidahu</td>
                                </tr>
                                <tr>
                                    <td height="40">33</td>
                                    
                                    <td>PIAN PUTIARANI, SP..</td>
                                    <td>Penata Tk.I</td>
                                    <td>III/d</td>
                                    <td>Kepala Sub Bagian Tata Usaha UPTD Ketahanan Pangan dan Pertanian Kuningan</td>
                                </tr>
                                <tr>
                                    <td height="40">34</td>
                                    
                                    <td>IIN INDRAYANI, SE., MM.</td>
                                    <td>Penata Tk.I</td>
                                    <td>III/d</td>
                                    <td>Kepala Sub Bagian Tata Usaha UPTD Brigade Proteksi Tanaman</td>
                                </tr>
                                <tr>
                                    <td height="40">35</td>
                                    
                                    <td>AZIZ NURHAPID, SE.</td>
                                    <td>Penata Tk.I</td>
                                    <td>III/d</td>
                                    <td>Kepala Sub Bagian Tata Usaha UPTD Ketahanan Pangan dan Pertanian Darma</td>
                                </tr>
                                <tr>
                                    <td height="40">36</td>
                                    
                                    <td>DADANG SUGANDA, S.Hut.</td>
                                    <td>Penata Tk.I</td>
                                    <td>III/d</td>
                                    <td>Kepala Sub Bagian Tata Usaha UPTD Ketahanan Pangan dan Pertanian Ciwaru</td>
                                </tr>
                                <tr>
                                    <td height="40">37</td>
                                    
                                    <td>DIAN NURDIANA, ST.</td>
                                    <td>Penata Tk.I</td>
                                    <td>III/d</td>
                                    <td>Kepala Sub Bagian Tata Usaha UPTD Ketahanan Pangan dan Pertanian Ciniru</td>
                                </tr>
                                <tr>
                                    <td height="40">38</td>
                                    
                                    <td>ADAM NURSAMSI, S.IP.</td>
                                    <td>Penata</td>
                                    <td>III/c</td>
                                    <td>Kepala Sub Bagian Tata Usaha UPTD Ketahanan Pangan dan Pertanian Cibingbin</td>
                                </tr>
                                <tr>
                                    <td height="40">39</td>
                                    
                                    <td>DIDIN SAEPUDIN, SE.</td>
                                    <td>Penata</td>
                                    <td>III/c</td>
                                    <td>Kepala Sub Bagian Tata Usaha UPTD Ketahanan Pangan dan Pertanian Luragung</td>
                                </tr>
                                <tr>
                                    <td height="40">40</td>
                                    
                                    <td>MAMAN SULAEMAN, SE.</td>
                                    <td>Penata</td>
                                    <td>III/c</td>
                                    <td>Kepala Sub Bagian Tata Usaha UPTD Balai Benih Hortikultura dan Kebun Bibit
                                        Permanen</td>
                                </tr>
                                <tr>
                                    <td height="40">41</td>
                                    
                                    <td>SUKARI, S.Hut.</td>
                                    <td>Penata</td>
                                    <td>III/c</td>
                                    <td>Kepala Sub Bagian Tata Usaha UPTD Ketahanan Pangan dan Pertanian Kadugede</td>
                                </tr>
                                <tr>
                                    <td height="40">42</td>
                                    
                                    <td>DIAN NOVITASARI, SE., MM.</td>
                                    <td>Penata</td>
                                    <td>III/c</td>
                                    <td>Kepala Sub Bagian Tata Usaha UPTD Ketahanan Pangan dan Pertanian Jalaksana</td>
                                </tr>
                                <tr>
                                    <td height="40">43</td>
                                    
                                    <td>IMAS MASRIAH, SE.</td>
                                    <td>Penata</td>
                                    <td>III/c</td>
                                    <td>Kepala Sub Bagian Tata Usaha UPTD Ketahanan Pangan dan Pertanian Cilimus</td>
                                </tr>
                                <tr>
                                    <td height="40">44</td>
                                    
                                    <td>NANA KUSMANA, S.AP.</td>
                                    <td>Penata</td>
                                    <td>III/c</td>
                                    <td>Kepala Sub Bagian Tata Usaha UPTD Ketahanan Pangan dan Pertanian Subang</td>
                                </tr>
                                <tr>
                                    <td height="40">45</td>
                                    
                                    <td>SOLIHIN, SE.</td>
                                    <td>Penata</td>
                                    <td>III/c</td>
                                    <td>Kepala Sub Bagian Tata Usaha UPTD Ketahanan Pangan dan Pertanian Garawangi</td>
                                </tr>
                                <tr>
                                    <td height="40">46</td>
                                    
                                    <td>SALEH SOLIHAT, SE.</td>
                                    <td>Penata</td>
                                    <td>III/c</td>
                                    <td>Kepala Sub Bagian Tata Usaha UPTD Ketahanan Pangan dan Pertanian Cipicung</td>
                                </tr>
                                <tr>
                                    <td height="40">47</td>
                                    
                                    <td>ANI TRISNIAWATI, S.IP.</td>
                                    <td>Penata Muda Tk.I</td>
                                    <td>III/b</td>
                                    <td>Kepala Sub Bagian Tata Usaha UPTD Ketahanan Pangan dan Pertanian Lebakwangi</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            if ($('.datatable').length && !$.fn.DataTable.isDataTable('.datatable')) {
                $('.datatable').DataTable({
                    responsive: true,
                    pageLength: 10,
                    lengthMenu: [
                        [10, 25, 50, -1],
                        [10, 25, 50, "Semua"]
                    ],
                    language: {
                        search: "Cari:",
                        lengthMenu: "Tampilkan _MENU_ data per halaman",
                        info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                        infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                        infoFiltered: "(disaring dari _MAX_ total data)",
                        paginate: {
                            first: "Pertama",
                            last: "Terakhir",
                            next: "Selanjutnya",
                            previous: "Sebelumnya"
                        },
                        zeroRecords: "Tidak ada data yang ditemukan",
                        emptyTable: "Tidak ada data tersedia"
                    },
                    order: [
                        [0, 'asc']
                    ]
                });
            }
        });
    </script>
@endpush
