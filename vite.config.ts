import { wayfinder } from '@laravel/vite-plugin-wayfinder';
import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import { defineConfig } from 'vite';
import { VitePWA } from 'vite-plugin-pwa';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.ts'],
            ssr: 'resources/js/ssr.ts',
            refresh: true,
        }),
        tailwindcss(),
        wayfinder({
            formVariants: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        VitePWA({
            registerType: 'autoUpdate',
            workbox: {
                navigateFallback: null,
                runtimeCaching: [
                    {
                        urlPattern: ({ request }) =>
                            request.mode === 'navigate',
                        handler: 'NetworkFirst',
                        options: {
                            cacheName: 'pages',
                        },
                    },
                    {
                        urlPattern: ({ request }) =>
                            request.destination === 'script' ||
                            request.destination === 'style',
                        handler: 'StaleWhileRevalidate',
                        options: {
                            cacheName: 'assets',
                        },
                    },
                    {
                        urlPattern: ({ request }) =>
                            request.destination === 'image',
                        handler: 'CacheFirst',
                        options: {
                            cacheName: 'images',
                        },
                    },
                ],
            },
            manifest: {
                name: 'Autosaler',
                short_name: 'Autosaler',
                start_url: '/',
                display: 'standalone',
                background_color: '#ffffff',
                theme_color: '#1c2431',
                icons: [
                    {
                        src: '/icon-192x192.png',
                        sizes: '192x192',
                        type: 'image/png',
                    },
                    {
                        src: '/icon-512x512.png',
                        sizes: '512x512',
                        type: 'image/png',
                    },
                ],
            },
            buildBase: '/build/',
        }),
    ],
    resolve: {
        alias: {
            '@assets': '/resources/assets',
        },
    },
});
