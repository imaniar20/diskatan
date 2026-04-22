<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if (isset($news))
        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="article">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:title" content="{{ $news->title }}">
        <meta property="og:description" content="{{ Str::limit(strip_tags($news->content), 155) }}">
        <meta property="og:image" content="{{ asset('storage/' . $news->thumbnail) }}">
        <meta property="og:image:width" content="1200">
        <meta property="og:image:height" content="630"> <!-- Twitter -->
        <meta property="twitter:card" content="summary_large_image">
        <meta property="twitter:url" content="{{ url()->current() }}">
        <meta property="twitter:title" content="{{ $news->title }}">
        <meta property="twitter:description" content="{{ Str::limit(strip_tags($news->content), 155) }}">
        <meta property="twitter:image" content="{{ asset('storage/' . $news->thumbnail) }}">
    @endif
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

    <div id="footer-active">
        @include('public.partials.footer')
    </div>

    @stack('before-script')
        @include('public.partials.js')
    @stack('after-script')

    @stack('scripts')
</body>

</html>
