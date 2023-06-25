<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
        @hasSection('title')
            <title>@yield('title') - {{ config('app.name') }}</title>
        @else
            <title>{{ config('app.name') }}</title>
        @endif

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}" />

        <!-- Favicon -->
        <link rel="shortcut icon" href="{{ url(asset('favicon.ico')) }}">

        <!-- Telegram widget -->
        <script src="https://telegram.org/js/telegram-web-app.js"></script>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles

        <!-- Fonts -->
        @if (filled($fontsUrl = config('clover.google_fonts')))
            <link rel="preconnect" href="https://fonts.googleapis.com" />
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
            <link href="{{ $fontsUrl }}" rel="stylesheet" />
        @endif
    </head>

    <body>
        @yield('content')

        @livewireScripts
    </body>
</html>
