<script setup lang="ts">
import {
    DateFormatter,
    getLocalTimeZone,
    parseDate,
    today,
} from '@internationalized/date';
import { CalendarIcon } from 'lucide-vue-next';
import type { DateValue } from 'reka-ui';
import { ref, watch, computed } from 'vue';

import { Button } from '@/components/ui/button';
import { Calendar } from '@/components/ui/calendar';
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from '@/components/ui/popover';
import { useTranslation } from '@/composables/useTranslation';
import { cn } from '@/lib/utils';

const props = defineProps<{
    modelValue?: string | null;
}>();

const emit = defineEmits<{
    (e: 'update:modelValue', value: string): void;
}>();

const { __, locale } = useTranslation();

const defaultPlaceholder = today(getLocalTimeZone());

// Try parsing initial modelValue
let initialDate: DateValue | undefined = undefined;
if (props.modelValue) {
    try {
        initialDate = parseDate(props.modelValue);
    } catch {
        initialDate = undefined;
    }
}

const date = ref<DateValue | undefined>(initialDate);

const df = computed(() => {
    return new DateFormatter(locale.value, {
        dateStyle: 'long',
    });
});

watch(date, (newVal) => {
    if (newVal) {
        emit('update:modelValue', newVal.toString());
    } else {
        emit('update:modelValue', '');
    }
});

watch(
    () => props.modelValue,
    (newVal) => {
        if (!newVal) {
            date.value = undefined;
            return;
        }
        if (newVal !== date.value?.toString()) {
            try {
                date.value = parseDate(newVal);
            } catch {
                date.value = undefined;
            }
        }
    },
);
</script>

<template>
    <Popover v-slot="{ close }">
        <PopoverTrigger as-child>
            <Button
                variant="outline"
                :class="
                    cn(
                        'w-full justify-start text-left font-normal',
                        !date && 'text-muted-foreground',
                    )
                "
            >
                <CalendarIcon class="mr-2 h-4 w-4" />
                {{
                    date
                        ? df.format(date.toDate(getLocalTimeZone()))
                        : __('ui.select_date')
                }}
            </Button>
        </PopoverTrigger>
        <PopoverContent class="w-auto p-0" align="start">
            <Calendar
                v-model="date as any"
                :default-placeholder="defaultPlaceholder"
                :locale="locale"
                layout="month-and-year"
                initial-focus
                @update:model-value="close"
            />
        </PopoverContent>
    </Popover>
</template>
