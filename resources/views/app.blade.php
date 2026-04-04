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
        @php
            $siteUrl = 'https://dev.autosaler.be';
            $currentPath = request()->getPathInfo();
            $canonicalUrl = $currentPath === '/' ? $siteUrl : rtrim($siteUrl, '/') . $currentPath;
            $component = $page['component'] ?? null;

            $ogType = 'website';
            $ogTitle = config('app.name', 'AutoSaler');
            $ogDescription = null;
            $metaDescription = null;
            $ogImage = rtrim($siteUrl, '/') . '/images/og-autosaler.jpg';
            $ogImageWidth = '1600';
            $ogImageHeight = '750';

            if ($component === 'VehicleAds/Show') {
                $ad = $page['props']['ad'] ?? [];
                $metaDescription = "Consultez cette annonce auto d'occasion sur AutoSaler. Découvrez les photos, les caractéristiques et contactez rapidement le vendeur.";
                $ogDescription = $metaDescription;

                $brand = data_get($ad, 'brand.name');
                $model = data_get($ad, 'model.name');
                $version = data_get($ad, 'vehicle_version_name') ?: data_get($ad, 'vehicle_version.name');
                $mileage = data_get($ad, 'mileage');
                $fuelType = data_get($ad, 'fuel_type.code');

                $ogType = 'product';
                $titleParts = array_filter([$brand, $model, $version]);

                if ($titleParts !== []) {
                    $ogTitle = implode(' ', $titleParts).' | AutoSaler';
                }

                $vehicleLabel = trim(implode(' ', array_filter([$brand, $model])));
                $specs = [];

                if (is_numeric($mileage)) {
                    $specs[] = number_format((int) $mileage, 0, ',', ' ').' km';
                }

                if (is_string($fuelType) && $fuelType !== '') {
                    $specs[] = $fuelType;
                }

                if ($vehicleLabel !== '') {
                    $metaDescription = "Découvrez cette {$vehicleLabel} d'occasion sur AutoSaler.";

                    if ($specs !== []) {
                        $metaDescription .= ' '.implode(' - ', $specs).'.';
                    }

                    $metaDescription .= ' Contactez rapidement le vendeur.';
                    $ogDescription = $metaDescription;
                }

                $vehicleImagePath = data_get($ad, 'primary_image.large')
                    ?? data_get($ad, 'primary_image.card')
                    ?? data_get($ad, 'primary_image.thumb');

                if (is_string($vehicleImagePath) && $vehicleImagePath !== '') {
                    $ogImage = \Illuminate\Support\Str::startsWith($vehicleImagePath, ['http://', 'https://'])
                        ? $vehicleImagePath
                        : rtrim($siteUrl, '/').$vehicleImagePath;
                    $ogImageWidth = '1600';
                    $ogImageHeight = '1200';
                }
            }
        @endphp
        <link rel="canonical" href="{{ $canonicalUrl }}" />
        @if ($metaDescription)
            <meta name="description" content="{{ $metaDescription }}" />
        @endif
        <meta property="og:type" content="{{ $ogType }}" />
        <meta property="og:site_name" content="{{ config('app.name') }}" />
        <meta property="og:url" content="{{ $canonicalUrl }}" />
        @if ($component === 'VehicleAds/Show')
            <meta property="og:title" content="{{ $ogTitle }}" />
        @endif
        @if ($component === 'VehicleAds/Show' && $ogDescription)
            <meta property="og:description" content="{{ $ogDescription }}" />
        @endif
        <meta property="og:image" content="{{ $ogImage }}" />
        <meta property="og:image:secure_url" content="{{ $ogImage }}" />
        <meta property="og:image:width" content="{{ $ogImageWidth }}" />
        <meta property="og:image:height" content="{{ $ogImageHeight }}" />
        <meta name="twitter:card" content="summary_large_image" />
        @if ($component === 'VehicleAds/Show')
            <meta name="twitter:title" content="{{ $ogTitle }}" />
        @endif
        @if ($component === 'VehicleAds/Show' && $ogDescription)
            <meta name="twitter:description" content="{{ $ogDescription }}" />
        @endif
        <meta name="twitter:image" content="{{ $ogImage }}" />

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
