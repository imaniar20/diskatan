<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemeliharaan Sistem - DISKATAN Kabupaten Kuningan</title>
    <link rel="icon" type="image/png" href="{{ asset('img/logo/kuningan.png') }}">
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Public Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #ecfdf5 50%, #f0fdf4 100%);
            color: #1e293b;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
            position: relative;
            overflow-x: hidden;
        }

        /* Decorative background elements */
        .bg-shape {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            z-index: 0;
            pointer-events: none;
            opacity: 0.6;
        }

        .bg-shape-1 {
            width: 380px;
            height: 380px;
            background: rgba(34, 197, 94, 0.2);
            top: -100px;
            left: -100px;
        }

        .bg-shape-2 {
            width: 320px;
            height: 320px;
            background: rgba(16, 185, 129, 0.15);
            bottom: -80px;
            right: -80px;
        }

        /* Floating leaf decorative accents */
        .leaf-accent {
            position: absolute;
            opacity: 0.15;
            z-index: 0;
            pointer-events: none;
        }

        .leaf-1 {
            top: 12%;
            right: 8%;
            width: 70px;
            height: 70px;
            transform: rotate(25deg);
        }

        .leaf-2 {
            bottom: 14%;
            left: 6%;
            width: 60px;
            height: 60px;
            transform: rotate(-35deg);
        }

        /* Main Container Card */
        .maintenance-card {
            position: relative;
            z-index: 1;
            background: rgba(255, 255, 255, 0.94);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(22, 163, 74, 0.15);
            border-radius: 24px;
            box-shadow: 0 20px 40px -15px rgba(22, 101, 52, 0.08), 0 0 1px 1px rgba(0, 0, 0, 0.03);
            max-width: 620px;
            width: 100%;
            padding: 44px 36px 36px;
            text-align: center;
        }

        /* Logo and Header */
        .brand-header {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 14px;
            margin-bottom: 24px;
        }

        .brand-logo {
            height: 58px;
            width: auto;
            object-fit: contain;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.06));
        }

        .brand-info {
            text-align: left;
        }

        .brand-title {
            font-size: 15px;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.25;
            letter-spacing: -0.01em;
        }

        .brand-subtitle {
            font-size: 12.5px;
            color: #16a34a;
            font-weight: 600;
            margin-top: 2px;
            letter-spacing: 0.02em;
            text-transform: uppercase;
        }

        /* Status Badge */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #15803d;
            font-size: 12.5px;
            font-weight: 600;
            padding: 6px 14px;
            border-radius: 9999px;
            margin-bottom: 24px;
        }

        .pulse-dot {
            width: 8px;
            height: 8px;
            background-color: #22c55e;
            border-radius: 50%;
            position: relative;
        }

        .pulse-dot::after {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            width: 12px;
            height: 12px;
            background-color: rgba(34, 197, 94, 0.5);
            border-radius: 50%;
            animation: pulse-ring 1.8s cubic-bezier(0.24, 0, 0.38, 1) infinite;
        }

        @keyframes pulse-ring {
            0% {
                transform: scale(0.6);
                opacity: 1;
            }

            80%,
            100% {
                transform: scale(2.2);
                opacity: 0;
            }
        }

        /* Hero Illustration SVG */
        .illustration-wrapper {
            margin: 0 auto 24px;
            width: 100%;
            max-width: 220px;
        }

        .illustration-svg {
            width: 100%;
            height: auto;
            display: block;
        }

        /* Typography */
        .headline {
            font-size: 24px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.02em;
            line-height: 1.3;
            margin-bottom: 12px;
        }

        .description {
            font-size: 14.5px;
            color: #64748b;
            line-height: 1.6;
            margin-bottom: 28px;
            max-width: 520px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Action Buttons */
        .button-group {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 28px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-size: 14px;
            font-weight: 600;
            padding: 11px 22px;
            border-radius: 12px;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
            outline: none;
            border: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(22, 163, 74, 0.28);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #15803d 0%, #166534 100%);
            box-shadow: 0 6px 16px rgba(22, 163, 74, 0.38);
            transform: translateY(-1px);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .btn-outline {
            background: #ffffff;
            color: #1e293b;
            border: 1px solid #e2e8f0;
        }

        .btn-outline:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            color: #0f172a;
            transform: translateY(-1px);
        }

        /* Spinner for Reload */
        .spinner {
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top-color: #ffffff;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            display: none;
        }

        .btn.loading .btn-icon {
            display: none;
        }

        .btn.loading .spinner {
            display: inline-block;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Footer / Info Box */
        .card-footer {
            border-top: 1px dashed #e2e8f0;
            padding-top: 20px;
            font-size: 12.5px;
            color: #94a3b8;
            line-height: 1.5;
        }

        .card-footer strong {
            color: #475569;
        }

        @media (max-width: 480px) {
            .maintenance-card {
                padding: 32px 20px 24px;
                border-radius: 20px;
            }

            .headline {
                font-size: 21px;
            }

            .description {
                font-size: 13.5px;
            }

            .button-group {
                flex-direction: column;
                width: 100%;
            }

            .btn {
                width: 100%;
            }

            .brand-header {
                flex-direction: column;
                text-align: center;
                gap: 8px;
            }

            .brand-info {
                text-align: center;
            }
        }
    </style>
</head>

<body>
    <div class="bg-shape bg-shape-1"></div>
    <div class="bg-shape bg-shape-2"></div>

    <!-- Decorative SVG leaf 1 -->
    <svg class="leaf-accent leaf-1" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="1.5">
        <path d="M12 2C6.5 2 2 6.5 2 12C2 17.5 6.5 22 12 22C17.5 22 22 17.5 22 12C22 6.5 17.5 2 12 2Z" fill="#16a34a"
            fill-opacity="0.1" />
        <path d="M2 12C12 12 12 2 12 2C12 12 22 12 22 12C12 12 12 22 12 22C12 12 2 12 2 12Z" />
    </svg>

    <!-- Decorative SVG leaf 2 -->
    <svg class="leaf-accent leaf-2" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="1.5">
        <path d="M17.5 6.5C14.5 3.5 9 4.5 6 7.5C3 10.5 4 16 7 19C10 22 15.5 21 18.5 18C21.5 15 20.5 9.5 17.5 6.5Z"
            fill="#10b981" fill-opacity="0.1" />
        <path d="M6 18L18 6" />
    </svg>

    <main class="maintenance-card">
        <!-- Logo and Brand -->
        <header class="brand-header">
            <img src="{{ asset('img/logo/kuningan.png') }}" alt="Logo Kabupaten Kuningan" class="brand-logo"
                onerror="this.style.display='none'">
            <div class="brand-info">
                <h2 class="brand-title">Dinas Ketahanan Pangan dan Pertanian</h2>
                <p class="brand-subtitle">Pemerintah Kabupaten Kuningan</p>
            </div>
        </header>

        <!-- Status Badge -->
        <div class="status-badge">
            <span class="pulse-dot"></span>
            <span>Sistem Sedang Dalam Pemeliharaan</span>
        </div>

        <!-- Agricultural / Server SVG Illustration -->
        <div class="illustration-wrapper">
            <svg class="illustration-svg" viewBox="0 0 200 160" fill="none" xmlns="http://www.w3.org/2000/svg">
                <!-- Base Ground & Organic Shapes -->
                <ellipse cx="100" cy="142" rx="76" ry="12" fill="#dcfce7" />

                <!-- Server / Tech Tower -->
                <rect x="70" y="44" width="60" height="90" rx="8" fill="#ffffff" stroke="#16a34a" stroke-width="2.5" />
                <line x1="70" y1="74" x2="130" y2="74" stroke="#e2e8f0" stroke-width="2" />
                <line x1="70" y1="104" x2="130" y2="104" stroke="#e2e8f0" stroke-width="2" />

                <!-- Server Slots & Lights -->
                <circle cx="82" cy="59" r="3" fill="#22c55e" />
                <circle cx="91" cy="59" r="3" fill="#e2e8f0" />
                <line x1="100" y1="59" x2="118" y2="59" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" />

                <circle cx="82" cy="89" r="3" fill="#22c55e" />
                <circle cx="91" cy="89" r="3" fill="#22c55e" />
                <line x1="100" y1="89" x2="118" y2="89" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" />

                <circle cx="82" cy="119" r="3" fill="#f59e0b" />
                <circle cx="91" cy="119" r="3" fill="#e2e8f0" />
                <line x1="100" y1="119" x2="118" y2="119" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" />

                <!-- Sprout / Plant on Server (Agriculture theme) -->
                <path d="M100 44V26" stroke="#16a34a" stroke-width="3" stroke-linecap="round" />
                <path d="M100 28C100 28 90 26 88 18C96 16 100 24 100 28Z" fill="#22c55e" stroke="#16a34a"
                    stroke-width="1.5" />
                <path d="M100 32C100 32 110 30 112 22C104 20 100 28 100 32Z" fill="#16a34a" stroke="#15803d"
                    stroke-width="1.5" />

                <!-- Maintenance Gear / Tool -->
                <circle cx="138" cy="46" r="14" fill="#ffffff" stroke="#f59e0b" stroke-width="2.5" />
                <path
                    d="M138 35V38M138 54V57M127 46H130M146 46H149M130 38L132 40M144 52L146 54M130 54L132 52M144 40L146 38"
                    stroke="#f59e0b" stroke-width="2.5" stroke-linecap="round" />
                <circle cx="138" cy="46" r="5" fill="#fef3c7" stroke="#f59e0b" stroke-width="2" />

                <!-- Small Floating Leaf -->
                <path d="M60 88C52 86 50 78 54 74C60 76 62 84 60 88Z" fill="#86efac" stroke="#22c55e"
                    stroke-width="1.2" />
            </svg>
        </div>

        <!-- Headline & Description -->
        <h1 class="headline">Peningkatan Layanan Sedang Berlangsung</h1>
        <p class="description">
            Website dan sistem DISKATAN Kabupaten Kuningan saat ini sedang menjalani proses pemeliharaan berkala untuk
            meningkatkan performa, stabilitas, dan keamanan data layanan publik. Kami akan segera kembali online.
        </p>

        <!-- Actions -->
        <div class="button-group">
            <button type="button" class="btn btn-primary" id="btnReload" onclick="reloadPage()">
                <svg class="btn-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21.5 2v6h-6M2.5 22v-6h6M2 11.5a10 10 0 0 1 18.8-4.3M22 12.5a10 10 0 0 1-18.8 4.2" />
                </svg>
                <span class="spinner"></span>
                <span>Muat Ulang Halaman</span>
            </button>
            <!-- <a href="https://wa.me/62811222333" target="_blank" rel="noopener noreferrer" class="btn btn-outline">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                </svg>
                <span>Hubungi Layanan</span>
            </a> -->
        </div>

        <!-- Footer Notice -->
        <footer class="card-footer">
            <p><strong>Dinas Ketahanan Pangan dan Pertanian Kabupaten Kuningan</strong></p>
            <p>Jl. Ir. H. Juanda No. 91, Kuningan, Jawa Barat | &copy; {{ date('Y') }} Hak Cipta Dilindungi</p>
        </footer>
    </main>

    <script>
        function reloadPage() {
            const btn = document.getElementById('btnReload');
            if (btn) {
                btn.classList.add('loading');
                btn.disabled = true;
            }
            setTimeout(function () {
                window.location.reload();
            }, 600);
        }
    </script>
</body>

</html>