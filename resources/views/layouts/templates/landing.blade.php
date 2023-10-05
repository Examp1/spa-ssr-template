<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" type="image/png" href="https://owlweb.com.ua/media/images/favicon-16x16/portfolio/favicon.png" sizes="16x16">
    <link rel="icon" type="image/png" href="https://owlweb.com.ua/media/images/favicon-32x32/portfolio/favicon.png" sizes="32x32">
    <link rel="icon" type="image/png" href="https://owlweb.com.ua/media/images/favicon-192x192/portfolio/favicon.png" sizes="192x192">
    <link rel="apple-touch-icon" type="image/png" href="https://owlweb.com.ua/media/images/favicon-apple-touch-180x180/portfolio/favicon.png" sizes="180x180">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ env('APP_NAME') }}</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flickity/2.2.2/flickity.min.css">
{{--    <link rel="stylesheet" href="{{ asset('/css/front/style.css') }}">--}}
    <link rel="stylesheet" href="{{ asset('/css/front/main.css') }}">

    @stack('styles')
</head>
<body>
<div class="mainWrap">
    <main>
        @yield('content')
    </main>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous"></script>
@stack('scripts')
</body>
</html>
