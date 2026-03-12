<script setup lang="ts">
import { Users } from 'lucide-vue-next';
import FilterGroup from '@/components/VehicleAds/FilterGroup.vue';
import FilterSelect from './Partials/FilterSelect.vue';

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
    <FilterGroup label="Sièges" :icon="Users" :is-active="form.seats !== 'all'">
        <FilterSelect
            v-model="form.seats"
            :options="seatOptions"
            option-label="label"
            placeholder="Peu importe"
        />
    </FilterGroup>
</template>
