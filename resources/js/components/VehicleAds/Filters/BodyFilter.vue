<script setup lang="ts">
import { Truck } from 'lucide-vue-next';
import { computed } from 'vue';
import FilterCheckboxGroup from '@/components/VehicleAds/FilterCheckboxGroup.vue';
import FilterGroup from '@/components/VehicleAds/FilterGroup.vue';
import { useTranslation } from '@/composables/useTranslation';
import { useVehicleAdFieldTranslation } from '@/composables/useVehicleAdFieldTranslation';

const { __ } = useTranslation();
const { translateVehicleAdField } = useVehicleAdFieldTranslation();

const props = defineProps<{
    bodyTypes: any[];
}>();

const form = defineModel<any>('form', { required: true });

const translatedBodyTypes = computed(() =>
    props.bodyTypes.map((item) => ({
        ...item,
        label: translateVehicleAdField('body_types', item.code),
    })),
);
</script>

<template>
    <FilterGroup :label="__('vehicleAd.body')" :icon="Truck" :is-active="form.body_types.length > 0">
        <FilterCheckboxGroup
            v-model="form.body_types"
            :options="translatedBodyTypes"
            option-label="label"
            label="body"
        />
    </FilterGroup>
</template>
