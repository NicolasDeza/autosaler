import axios from 'axios';
import { ref } from 'vue';
import { loadTranslations } from './useTranslation';

export const currentLocale = ref<string>('fr');

export async function setLocale(locale: string) {
    if (currentLocale.value === locale) return;

    await axios.post('/locale', { locale });

    await loadTranslations(locale);

    currentLocale.value = locale;
}
