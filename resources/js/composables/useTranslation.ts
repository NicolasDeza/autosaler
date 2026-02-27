import { usePage } from '@inertiajs/vue3';
import axios from 'axios';
import { ref } from 'vue';

const translations = ref<Record<string, string>>({});
const loadedLocale = ref<string | null>(null);

/**
 * Charge les traductions depuis le backend pour la locale donnée.
 * Ne recharge que si la locale change.
 */
export async function loadTranslations(locale: string) {
    if (loadedLocale.value === locale) return;

    try {
        const { data } = await axios.get(`/translations/${locale}`);
        translations.value = data;
        loadedLocale.value = locale;
    } catch (e) {
        console.error('Failed to load translations for locale: ', locale);
    }
}

/**
 * Composable pour traduire les clés
 */
export function useTranslation() {
    /**
     * Traduit une clé donnée avec remplacement de variables.
     * Exemple : __('auth.welcome', { name: 'Sébastien' })
     */
    function __(
        key: string,
        replacements: Record<string, string | number> = {},
    ) {
        let translation = translations.value[key] ?? key;

        Object.entries(replacements).forEach(([k, v]) => {
            translation = translation.replace(
                new RegExp(`:${k}`, 'g'),
                String(v),
            );
        });

        return translation;
    }

    const currentLocale = () => {
        return usePage().props.locale || loadedLocale.value || 'en';
    };

    return { __, currentLocale, loadedLocale };
}
