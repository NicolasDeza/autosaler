<template>
    <div class="flex items-center gap-2">
        <Select id="locale-select" v-model="selected">
            <SelectTrigger
                class="flex h-6 w-8 items-center justify-center border-0 p-0 focus-visible:ring-0 focus-visible:ring-offset-0 dark:bg-transparent [&_svg]:hidden"
            >
                <span
                    v-if="selectedFlag"
                    :class="`fi fi-${selectedFlag}`"
                    class="h-4 w-6 rounded"
                ></span>
            </SelectTrigger>

            <SelectContent align="end">
                <SelectItem
                    v-for="lang in availableLocales"
                    :key="lang.value"
                    :value="lang.value"
                    class="flex items-center gap-2"
                >
                    <span :class="`fi fi-${lang.flag}`" class="h-4 w-6"></span>
                    <span>{{ lang.label }}</span>
                </SelectItem>
            </SelectContent>
        </Select>
    </div>
</template>

<script setup lang="ts">
import { ref, computed, watch } from 'vue';
import { currentLocale, setLocale } from '@/composables/useLocale';
import { useTranslation } from '@/composables/useTranslation';

import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
} from '@/components/ui/select';

const { locale } = useTranslation();

const availableLocales = [
    { value: 'fr', label: 'Français', flag: 'fr' },
    { value: 'en', label: 'English', flag: 'gb' },
];

const selected = ref<string>(locale.value ?? 'fr');
currentLocale.value = selected.value;

const selectedFlag = computed(() => {
    return availableLocales.find((l) => l.value === selected.value)?.flag ?? '';
});

watch(selected, async (newValue) => {
    if (newValue && newValue !== currentLocale.value) {
        await setLocale(newValue);
        currentLocale.value = newValue;
    }
});

watch(locale, (newLocale) => {
    if (newLocale) selected.value = newLocale;
});
</script>
