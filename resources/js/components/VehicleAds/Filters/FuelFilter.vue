<script setup lang="ts">
import { Fuel } from 'lucide-vue-next';
import { computed } from 'vue';
import FilterCheckboxGroup from '@/components/VehicleAds/FilterCheckboxGroup.vue';
import FilterGroup from '@/components/VehicleAds/FilterGroup.vue';
import { useTranslation } from '@/composables/useTranslation';
import { useVehicleAdFieldTranslation } from '@/composables/useVehicleAdFieldTranslation';

const { __ } = useTranslation();
const { translateVehicleAdField } = useVehicleAdFieldTranslation();

const props = defineProps<{
    fuelTypes: any[];
}>();

const form = defineModel<any>('form', { required: true });

const translatedFuelTypes = computed(() =>
    props.fuelTypes.map((item) => ({
        ...item,
        label: translateVehicleAdField('fuel_types', item.code),
    })),
);
</script>

<template>
    <FilterGroup :label="__('vehicleAd.fuel')" :icon="Fuel" :is-active="form.fuel_types.length > 0">
        <FilterCheckboxGroup
            v-model="form.fuel_types"
            :options="translatedFuelTypes"
            option-label="label"
            label="fuel"
        />
    </FilterGroup>
</template>
