<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - DISKATAN</title>
    <link rel="icon" type="image" href="{{ asset('img/logo/kuningan.png') }}" />

    @include('public.partials.header')

    @include('public.partials.css')

    @stack('styles')
</head>

<body>
    <!-- Decorative Leaves -->
    <div class="leaf-decoration leaf-1">🌿</div>
    <div class="leaf-decoration leaf-2">🍃</div>
    <div class="leaf-decoration leaf-3">🌿</div>
    <div class="leaf-decoration leaf-4">🍃</div>

    @include('public.partials.navbar')

    <main>
        @yield('content')
    </main>

    @include('public.partials.footer')

    @stack('before-script')
        @include('public.partials.js')
    @stack('after-script')

    @stack('scripts')
</body>

</html>
