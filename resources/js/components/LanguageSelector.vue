<template>
    <div class="flex items-center gap-2">
        <Select id="locale-select" v-model="selected">
            <SelectTrigger
                class="flex h-8 w-auto cursor-pointer! items-center gap-1.5 rounded-md border-0 px-2 opacity-80 transition-[colors,opacity] duration-150 hover:bg-foreground/10 hover:opacity-100 focus-visible:ring-0 focus-visible:ring-offset-0 dark:bg-transparent [&[data-state=open]_svg:last-child]:rotate-180 [&_svg:last-child]:size-3 [&_svg:last-child]:opacity-60 [&_svg:last-child]:transition-transform [&_svg:last-child]:duration-200"
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
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
} from '@/components/ui/select';
import { currentLocale, setLocale } from '@/composables/useLocale';
import { useTranslation } from '@/composables/useTranslation';


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
