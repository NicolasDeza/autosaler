<script setup lang="ts">
import FilterCheckboxGroup from '@/components/VehicleAds/FilterCheckboxGroup.vue';
import FilterGroup from '@/components/VehicleAds/FilterGroup.vue';

defineProps<{
    fuelTypes: any[];
}>();

const form = defineModel<any>('form', { required: true });
</script>

<template>
    <FilterGroup label="Carburant" :is-active="form.fuel_types.length > 0">
        <FilterCheckboxGroup
            v-model="form.fuel_types"
            :options="fuelTypes"
            label="fuel"
        />
    </FilterGroup>
</template>
