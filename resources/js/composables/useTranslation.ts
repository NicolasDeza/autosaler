import { usePage } from '@inertiajs/vue3';
import axios from 'axios';
import { ref, computed } from 'vue';
import type { ExtendedPageProps } from '@/types/inertia';

const translations = ref<Record<string, string>>({});
const loadedLocale = ref<string | null>(null);

export async function loadTranslations(locale: string) {
    if (loadedLocale.value === locale) return;

    const { data } = await axios.get(`/translations/${locale}`);
    translations.value = data;
    loadedLocale.value = locale;
}

/**
 * Composable pour traductions + locale actuelle
 */
export function useTranslation() {
    const page = usePage<ExtendedPageProps>();

    // ✅ Toujours renvoyer une string
    const locale = computed<string>(
        () => loadedLocale.value ?? page.props.locale ?? 'fr',
    );

    function __(
        key: string,
        replacements: Record<string, string | number> = {},
    ) {
        // Attempt to get the translation directly
        let translation = translations.value[key];

        // If not found and key has dots, try to traverse if the route somehow failed us or for better compatibility
        if (translation === undefined && key.includes('.')) {
            const parts = key.split('.');
            let current: any = translations.value;
            for (const part of parts) {
                if (current && typeof current === 'object' && part in current) {
                    current = current[part];
                } else {
                    current = undefined;
                    break;
                }
            }
            if (typeof current === 'string') {
                translation = current;
            }
        }

        // Fallback to key
        if (translation === undefined) {
            translation = key;
        }

        Object.entries(replacements).forEach(([k, v]) => {
            translation = translation.replace(
                new RegExp(`:${k}`, 'g'),
                String(v),
            );
        });
        return translation;
    }

    return { __, locale };
}
