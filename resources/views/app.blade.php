<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        {{-- Inline style to set the HTML background color --}}
        <style>
            html {
                background-color: oklch(1 0 0);
            }
        </style>

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96" />
        <link rel="icon" type="image/svg+xml" href="/favicon.svg" />
        <link rel="shortcut icon" href="/favicon.ico" />
        <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png" />
        <meta name="apple-mobile-web-app-title" content="AutoSaler" />

        <link rel="manifest" href="/manifest.webmanifest">
        @if (($page['component'] ?? null) === 'Home/Index')
            <link
                rel="preload"
                as="image"
                href="{{ Vite::asset('resources/assets/images/hero-1-mobile.webp') }}"
                media="(max-width: 767px)"
                fetchpriority="high"
            />
            <link
                rel="preload"
                as="image"
                href="{{ Vite::asset('resources/assets/images/hero-1.webp') }}"
                media="(min-width: 768px)"
                fetchpriority="high"
            />
        @endif

        @vite(['resources/js/app.ts', "resources/js/pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
