<script setup lang="ts">
import { DoorOpen } from 'lucide-vue-next';
import FilterGroup from '@/components/VehicleAds/FilterGroup.vue';
import { useTranslation } from '@/composables/useTranslation';
import FilterSelect from './Partials/FilterSelect.vue';

const { __ } = useTranslation();

const doorOptions = [
    { id: '2', label: '2' },
    { id: '3', label: '3' },
    { id: '4', label: '4' },
    { id: '5', label: '5' },
];

const form = defineModel<any>('form', { required: true });
</script>

<template>
    <FilterGroup :label="__('vehicleAd.doors')" :icon="DoorOpen" :is-active="form.doors !== 'all'">
        <FilterSelect
            v-model="form.doors"
            :options="doorOptions"
            option-label="label"
            :placeholder="__('ui.any')"
        />
    </FilterGroup>
</template>
