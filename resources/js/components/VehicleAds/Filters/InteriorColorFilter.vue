<script setup lang="ts">
import { PaintBucket } from 'lucide-vue-next';
import FilterGroup from '@/components/VehicleAds/FilterGroup.vue';
import { useTranslation } from '@/composables/useTranslation';
import FilterSelect from './Partials/FilterSelect.vue';

const { __ } = useTranslation();

defineProps<{
    interiorColors: any[];
}>();

const form = defineModel<any>('form', { required: true });
</script>

<template>
    <FilterGroup
        :label="__('vehicleAd.interior_color')"
        :icon="PaintBucket"
        :is-active="form.interior_color_id !== 'all'"
    >
        <FilterSelect
            v-model="form.interior_color_id"
            :options="interiorColors"
            option-label="code"
            :placeholder="__('ui.all_colors')"
        />
    </FilterGroup>
</template>
