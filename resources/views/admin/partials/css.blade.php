<!-- Favicon -->
<link rel="icon" type="image" href="{{ asset('img/logo/kuningan.png') }}" style="width: 100%" />

<!-- Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link
    href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
    rel="stylesheet" />

<!-- Icons. Uncomment required icon fonts -->
{{-- <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}" /> --}}
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

<!-- Core CSS -->
<link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
<link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}" class="template-customizer-theme-css" />
<link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />

<!-- Vendors CSS -->
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

{{-- <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css" /> --}}

<!-- Page CSS -->

<!-- Helpers -->
<script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>

<!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
<!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
<script src="{{ asset('assets/js/config.js') }}"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Vendors JS -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/2.2.1/css/dataTables.dataTables.css">

{{-- datatable --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<!-- CSS Select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- Dropify CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.css">

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<style>
    .dropify-wrapper .dropify-message p {
        font-size: 14px;
        /* Ubah ukuran teks */
        color: #6c757d;
        /* Sesuaikan warna jika diperlukan */
        text-align: center;
        /* Pastikan teks rata tengah */
    }

    .swal2-container {
        z-index: 99999 !important;
    }

    .loading {
        animation: spin 2s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(180deg);
        }
    }

    .warning {
        animation: blink 1s infinite alternate;
    }

    @keyframes blink {
        0% {
            opacity: 1;
        }

        100% {
            opacity: 0.3;
        }
    }

    .done {
        animation: pop 0.5s ease-in-out;
    }

    @keyframes pop {
        0% {
            transform: scale(0);
            opacity: 0;
        }

        100% {
            transform: scale(1);
            opacity: 1;
        }
    }

    .label-badge {
        background-color: white;
        color: black;
        font-weight: bold;
        border: 1px solid black;
        padding: 4px 8px;
        border-radius: 6px;
        white-space: nowrap;
        /* Agar tidak pindah baris */
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
        font-size: 14px;
    }
</style>
