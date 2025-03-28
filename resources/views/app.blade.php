<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="Admin Panel">
        <meta name="author" content="AGA Dev">

        <title inertia>{{ $page['props']['title'] ?? '' }}</title>

        <link rel="icon" type="image/x-icon" href="/logo.ico">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&amp;display=swap" rel="stylesheet">

        @php
        $lightCss = Vite::asset('resources/assets/css/light.css');
        $darkCss = Vite::asset('resources/assets/css/dark.css');
        @endphp

        <script>
        window.themeUrls = {
            light: "{{ $lightCss }}",
            dark: "{{ $darkCss }}"
        };
        </script>

        <link class="js-stylesheet" href="{{ $lightCss }}" data-light="{{ $lightCss }}" data-dark="{{ $darkCss }}" rel="stylesheet">
        @vite([
        'resources/assets/js/settings.js',
        ])

        @routes
        @vite([
            'resources/js/app.js',
            "resources/js/Pages/{$page['component']}.vue"
            ])
        @inertiaHead
    </head>
    <body data-theme="default" data-layout="fluid" data-sidebar-position="left" data-sidebar-layout="default">
        @inertia
        @vite(['resources/assets/js/app.js'])
    </body>
</html>
