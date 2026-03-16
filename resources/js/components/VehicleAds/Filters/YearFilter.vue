<script setup lang="ts">
import { Calendar } from 'lucide-vue-next';
import { ref, watch } from 'vue';
import { Slider } from '@/components/ui/slider';
import FilterGroup from '@/components/VehicleAds/FilterGroup.vue';
import { useTranslation } from '@/composables/useTranslation';

const { __ } = useTranslation();

const form = defineModel<any>('form', { required: true });

const currentYear = new Date().getFullYear();
const yearRange = ref([form.value.min_year, form.value.max_year]);

const onYearChange = (values: number[] | undefined) => {
    if (!values) return;
    form.value.min_year = values[0];
    form.value.max_year = values[1];
    yearRange.value = [...values];
};

watch(
    () => [form.value.min_year, form.value.max_year],
    (newVal) => {
        yearRange.value = [newVal[0] as number, newVal[1] as number];
    },
    { deep: true },
);
</script>

<template>
    <FilterGroup
        :label="__('vehicleAd.first_registration')"
        :icon="Calendar"
        :is-active="form.min_year > 1980 || form.max_year < currentYear"
    >
        <div class="space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-xs text-muted-foreground">
                    {{ form.min_year }} - {{ form.max_year }}
                </span>
            </div>
            <Slider
                v-model="yearRange"
                :max="currentYear"
                :min="1980"
                :step="1"
                class="py-4"
                @update:model-value="onYearChange"
            />
        </div>
    </FilterGroup>
</template>
