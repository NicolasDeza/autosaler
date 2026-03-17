<script setup lang="ts">
import { Palette } from 'lucide-vue-next';
import FilterGroup from '@/components/VehicleAds/FilterGroup.vue';
import { useTranslation } from '@/composables/useTranslation';
import FilterSelect from './Partials/FilterSelect.vue';

const { __ } = useTranslation();

defineProps<{
    exteriorColors: any[];
}>();

const form = defineModel<any>('form', { required: true });
</script>

<template>
    <FilterGroup
        :label="__('vehicleAd.exterior_color')"
        :icon="Palette"
        :is-active="form.exterior_color_id !== 'all'"
    >
        <FilterSelect
            v-model="form.exterior_color_id"
            :options="exteriorColors"
            option-label="code"
            :placeholder="__('ui.all_colors')"
        />
    </FilterGroup>
</template>
