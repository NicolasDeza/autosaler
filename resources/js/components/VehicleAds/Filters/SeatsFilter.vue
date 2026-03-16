<script setup lang="ts">
import { Users } from 'lucide-vue-next';
import FilterGroup from '@/components/VehicleAds/FilterGroup.vue';
import { useTranslation } from '@/composables/useTranslation';
import FilterSelect from './Partials/FilterSelect.vue';

const { __ } = useTranslation();

const seatOptions = [
    { id: '2', label: '2' },
    { id: '4', label: '4' },
    { id: '5', label: '5' },
    { id: '7', label: '7' },
    { id: '9', label: '9' },
];

const form = defineModel<any>('form', { required: true });
</script>

<template>
    <FilterGroup :label="__('vehicleAd.seats')" :icon="Users" :is-active="form.seats !== 'all'">
        <FilterSelect
            v-model="form.seats"
            :options="seatOptions"
            option-label="label"
            :placeholder="__('ui.any')"
        />
    </FilterGroup>
</template>
