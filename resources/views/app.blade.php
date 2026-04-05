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
            $siteUrl = rtrim(request()->getSchemeAndHttpHost(), '/');
            $currentPath = request()->getPathInfo();
            $canonicalUrl = $currentPath === '/' ? $siteUrl : $siteUrl.'/'.ltrim($currentPath, '/');
            $component = $page['component'] ?? null;

            $defaultTitle = "Véhicules d'occasion et neufs en Belgique";
            $defaultDescription = "Trouvez votre prochaine voiture d'occasion ou neuve en Belgique avec AutoSaler. Comparez les annonces, les prix et le kilométrage, puis contactez les garages et les concessionnaires.";

            $isVehicleShow = $component === 'VehicleAds/Show';
            $ad = $isVehicleShow ? ($page['props']['ad'] ?? []) : [];
            $pageOgImage = $isVehicleShow ? data_get($page, 'props.ogImage') : null;
            $pageOgImageType = $isVehicleShow ? data_get($page, 'props.ogImageType') : null;

            $ogType = $isVehicleShow ? 'product' : 'website';
            $ogTitle = $defaultTitle;
            $ogDescription = $defaultDescription;

            if ($isVehicleShow) {
                $brand = trim((string) data_get($ad, 'brand.name', ''));
                $model = trim((string) data_get($ad, 'model.name', ''));
                $version = trim((string) (data_get($ad, 'vehicle_version_name') ?? data_get($ad, 'vehicle_version.name', '')));
                $vehicleName = trim(implode(' ', array_filter([$brand, $model, $version])));

                if ($vehicleName !== '') {
                    $ogTitle = $vehicleName;
                    $ogDescription = "Découvrez cette {$vehicleName} d'occasion sur AutoSaler. Contactez rapidement le vendeur.";
                }
            }

            $ogImage = $siteUrl.'/images/og-autosaler.jpg';
            $ogImageWidth = '1600';
            $ogImageHeight = '750';
            $ogImageType = 'image/jpeg';

            $vehicleImagePath = $pageOgImage
                ?? data_get($ad, 'primary_image.original')
                ?? data_get($ad, 'gallery.0.original')
                ?? data_get($ad, 'primary_image.large')
                ?? data_get($ad, 'primary_image.card')
                ?? data_get($ad, 'primary_image.thumb')
                ?? data_get($ad, 'gallery.0.large')
                ?? data_get($ad, 'gallery.0.card')
                ?? data_get($ad, 'gallery.0.thumb');

            if (is_string($vehicleImagePath) && $vehicleImagePath !== '') {
                $ogImage = \Illuminate\Support\Str::startsWith($vehicleImagePath, ['http://', 'https://'])
                    ? $vehicleImagePath
                    : $siteUrl.$vehicleImagePath;
                $ogImageWidth = '1600';
                $ogImageHeight = '1200';
            }

            $vehicleImageType = $pageOgImageType
                ?? data_get($ad, 'primary_image.mime_type')
                ?? data_get($ad, 'gallery.0.mime_type');

            if (is_string($vehicleImageType) && $vehicleImageType !== '') {
                $ogImageType = $vehicleImageType;
            }
        @endphp

        <link rel="canonical" href="{{ $canonicalUrl }}" inertia="canonical" />
        <meta name="description" content="{{ $ogDescription }}" inertia="description" />
        <meta property="og:type" content="{{ $ogType }}" />
        <meta property="og:site_name" content="{{ config('app.name') }}" />
        <meta property="og:url" content="{{ $canonicalUrl }}" inertia="og:url" />
        <meta property="og:title" content="{{ $ogTitle }}" inertia="og:title" />
        <meta property="og:description" content="{{ $ogDescription }}" inertia="og:description" />
        <meta property="og:image" content="{{ $ogImage }}" />
        <meta property="og:image:type" content="{{ $ogImageType }}" />
        <meta property="og:image:secure_url" content="{{ $ogImage }}" />
        <meta property="og:image:width" content="{{ $ogImageWidth }}" />
        <meta property="og:image:height" content="{{ $ogImageHeight }}" />

        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" content="{{ $ogTitle }}" inertia="twitter:title" />
        <meta name="twitter:description" content="{{ $ogDescription }}" inertia="twitter:description" />
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
