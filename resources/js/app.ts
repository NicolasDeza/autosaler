import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { registerSW } from 'virtual:pwa-register';
import type { DefineComponent } from 'vue';
import { createApp, h, Teleport } from 'vue';
import '../css/app.css';
import 'flag-icons/css/flag-icons.min.css';
import 'vue-sonner/style.css';
import { Toaster } from '@/components/ui/sonner';
import { loadTranslations } from '@/composables/useTranslation';
import type { ExtendedPageProps } from './types/inertia';

const updateSW = registerSW({
    onNeedRefresh() {
        // Show a prompt to the user to refresh the page
        if (confirm('New version available! Reload?')) {
            updateSW(true);
        }
    },
    onOfflineReady() {
        // The app is ready to work offline
        console.log('App is ready to work offline');
    },
});

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: (name) =>
        resolvePageComponent(
            `./pages/${name}.vue`,
            import.meta.glob<DefineComponent>('./pages/**/*.vue'),
        ),
    async setup({ el, App, props, plugin }) {
        const pageProps = props.initialPage.props as ExtendedPageProps;
        const locale = pageProps.locale ?? 'en';

        await loadTranslations(locale);

        createApp({
            render: () => [
                h(App, props),
                h(Teleport, { to: 'body' }, [
                    h(Toaster, { richColors: true, position: 'bottom-right' }),
                ]),
            ],
        })
            .use(plugin)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
