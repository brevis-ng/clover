<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta name="format-detection" content="telephone=no" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="MobileOptimized" content="176" />
        <meta name="HandheldFriendly" content="True" />
        <meta name="robots" content="noindex,nofollow" />
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}" />

        <!-- Favicon -->
        <link rel="shortcut icon" href="{{ url(asset('favicon.ico')) }}">

        <!-- Telegram widget -->
        <script src="https://telegram.org/js/telegram-web-app.js"></script>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>

    <body class="min-h-screen container bg-[#F1EEE9] dark:bg-[#041C32] no-scrollbar">
        {{ $slot }}

        @livewireScripts
        @stack('scripts')
        <script>
            Telegram.WebApp.ready();
            Telegram.WebApp.enableClosingConfirmation();

            function setThemeClass() {
                document.documentElement.className = Telegram.WebApp.colorScheme;
            }
            Telegram.WebApp.onEvent("themeChanged", setThemeClass);
            setThemeClass();

            Telegram.WebApp.onEvent("mainButtonClicked", () => {
                Telegram.WebApp.HapticFeedback.impactOccurred('soft');
            });

            if (!Telegram.WebApp.isExpanded) {
                setTimeout(function() {
                    Telegram.WebApp.showConfirm("{{ __('frontend.expand_confirm') }}", (result) => result && Telegram.WebApp.expand());
                }, 500);
            }
        </script>
    </body>
</html>
