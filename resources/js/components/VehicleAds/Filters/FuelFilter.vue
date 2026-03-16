<script setup lang="ts">
import { Fuel } from 'lucide-vue-next';
import FilterCheckboxGroup from '@/components/VehicleAds/FilterCheckboxGroup.vue';
import FilterGroup from '@/components/VehicleAds/FilterGroup.vue';
import { useTranslation } from '@/composables/useTranslation';

const { __ } = useTranslation();

defineProps<{
    fuelTypes: any[];
}>();

const form = defineModel<any>('form', { required: true });
</script>

<template>
    <FilterGroup :label="__('vehicleAd.fuel')" :icon="Fuel" :is-active="form.fuel_types.length > 0">
        <FilterCheckboxGroup
            v-model="form.fuel_types"
            :options="fuelTypes"
            label="fuel"
        />
    </FilterGroup>
</template>
