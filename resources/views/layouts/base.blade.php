@props([
    'title' => null,
])

<!DOCTYPE html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    dir="{{ __('filament::layout.direction') ?? 'ltr' }}"
    class="min-h-screen antialiased"
>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />

        @if ($favicon = config('clover.favicon'))
            <link rel="icon" href="{{ $favicon }}" />
        @endif

        <title>
            {{ $title ? "{$title} - " : null }}
        </title>

        @livewireStyles

        @if (filled($fontsUrl = config('clover.google_fonts')))
            <link rel="preconnect" href="https://fonts.googleapis.com" />
            <link
                rel="preconnect"
                href="https://fonts.gstatic.com"
                crossorigin
            />
            <link href="{{ $fontsUrl }}" rel="stylesheet" />
        @endif
    </head>

    <body>

        {{ $slot }}

        @livewireScripts
    </body>
</html>
