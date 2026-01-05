@extends('public.layouts.app')

@section('title', 'Home')

@section('content')    
    <style>
        :root {
            --primary-green: #16a34a;
            --secondary-green: #40916c;
            --light-green: #52b788;
            --accent-green: #74c69d;
            --cream: #f8f9fa;
            --dark: #1b4332;
        }

        #loading-screen {
            position: fixed;
            inset: 0;
            background: rgba(255, 255, 255, 1);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 99999 !important;
        }

        /* Spinner */
        .spinner {
            width: 56px;
            height: 56px;
            border: 6px solid #e5e7eb;
            border-top-color: #16a34a;
            /* hijau */
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        #curtain {
            position: fixed;
            inset: 0;
            z-index: 99999;
            pointer-events: none;
            display: none;
        }

        /* tirai */
        .curtain {
            position: absolute;
            top: 0;
            width: 50%;
            height: 100%;
            background: linear-gradient(135deg,
                    #1b4332 0%,
                    #40916c 30%,
                    #16a34a 55%,
                    #52b788 75%,
                    #74c69d 100%);
            opacity: 1;
            transition:
                transform 1s cubic-bezier(.4, 0, .2, 1),
                opacity 1s ease;
        }

        .curtain.left {
            left: 0;
            transform: translateX(0);
        }

        .curtain.right {
            right: 0;
            transform: translateX(0);
        }

        /* aktif */
        #curtain.active {
            display: block;
        }

        #curtain.active .curtain-overlay {
            opacity: 1;
        }

        #curtain.open .curtain {
            opacity: 0;
        }

        /* kebuka */
        #curtain.open .left {
            transform: translateX(-100%);
        }

        #curtain.open .right {
            transform: translateX(100%);
        }
    </style>
    <div id="loading-screen">
        <div class="spinner"></div>
    </div>
    <div id="curtain">
        <div class="curtain left"></div>
        <div class="curtain right"></div>
    </div>
    <div id="index">
        @include('public.home.index')
    </div>
    <div id="index2" style="display: none">
        @include('public.home.index2')
    </div>

    <script>
        window.addEventListener('load', () => {
            const loader = document.getElementById('loading-screen');
            const index = document.getElementById('index');
            const index2 = document.getElementById('index2');
    
            const header = document.getElementById('header-active');
            const top = document.getElementById('top-active');
            const footer = document.getElementById('footer-active');
    
    
            // saat loading selesai
            index.style.display = 'none';
            header.style.display = 'none';
            top.style.display = 'none';
            footer.style.display = 'none';
            index2.style.display = 'block';
    
            // fade out loader
            loader.style.transition = 'opacity 0.4s ease';
            loader.style.opacity = '0';
    
            setTimeout(() => {
                loader.remove();
            }, 400);
        });
    
        // tombol balik
        document.getElementById('btn-buka')?.addEventListener('click', () => {
            const curtain = document.getElementById('curtain');
            const index = document.getElementById('index');
            const index2 = document.getElementById('index2');
    
            const header = document.getElementById('header-active');
            const top = document.getElementById('top-active');
            const footer = document.getElementById('footer-active');
    
            // munculin curtain
            curtain.classList.add('active');
    
            // fade gelap dulu
            setTimeout(() => {
                curtain.classList.add('open');
            }, 400);
    
            // ganti halaman pas tirai nutup
            setTimeout(() => {
                index2.style.display = 'none';
                index.style.display = 'block';
                header.style.display = 'block';
                top.style.display = 'block';
                footer.style.display = 'block';
            }, 500);
    
            // hapus curtain setelah animasi selesai
            setTimeout(() => {
                curtain.classList.remove('active', 'open');
                curtain.style.display = 'none';
            }, 1600);
        });
    </script>
@endsection