<script setup lang="ts">
import { ref, watch } from 'vue';
import FilterGroup from '@/components/VehicleAds/FilterGroup.vue';
import { Slider } from '@/components/ui/slider';

const form = defineModel<any>('form', { required: true });

const priceRange = ref([form.value.min_price, form.value.max_price]);

const onPriceChange = (values: number[] | undefined) => {
    if (!values) return;
    form.value.min_price = values[0];
    form.value.max_price = values[1];
    priceRange.value = [...values];
};

watch(
    () => [form.value.min_price, form.value.max_price],
    (newVal) => {
        priceRange.value = [newVal[0] as number, newVal[1] as number];
    },
    { deep: true },
);
</script>

<template>
    <FilterGroup
        label="Prix"
        :is-active="form.min_price > 0 || form.max_price < 200000"
    >
        <div class="space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-xs text-slate-400">
                    {{ form.min_price?.toLocaleString() }} -
                    {{
                        form.max_price >= 200000
                            ? '200k+'
                            : form.max_price?.toLocaleString()
                    }}
                </span>
            </div>
            <Slider
                v-model="priceRange"
                :max="200000"
                :min="0"
                :step="1000"
                class="py-4"
                @update:model-value="onPriceChange"
            />
        </div>
    </FilterGroup>
</template>
