<template>
    <div class="flex items-center gap-2">
        <Select id="locale-select" v-model="selected">
            <SelectTrigger
                class="flex h-8 w-12 items-center justify-center border-0 focus-visible:ring-0 focus-visible:ring-offset-0 dark:bg-transparent [&_svg]:hidden"
                size="sm"
            >
                <span
                    v-if="selectedFlag"
                    :class="selectedFlag"
                    class="h-4 w-6"
                ></span>
            </SelectTrigger>

            <SelectContent>
                <SelectItem
                    v-for="lang in availableLocales"
                    :key="lang.value"
                    :value="lang.value"
                    class="flex items-center gap-2"
                >
                    <span :class="lang.flagClass" class="h-4 w-6"></span>
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
    { value: 'fr', label: 'Français', flagClass: 'fi fi-fr' },
    { value: 'en', label: 'English', flagClass: 'fi fi-gb' },
];

const selected = ref<string>(locale.value ?? 'fr');
currentLocale.value = selected.value;

const selectedFlag = computed(() => {
    return (
        availableLocales.find((l) => l.value === selected.value)?.flagClass ??
        ''
    );
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
