<script setup lang="ts">
import { Star } from 'lucide-vue-next';
import { computed } from 'vue';
import FilterGroup from '@/components/VehicleAds/FilterGroup.vue';
import { useTranslation } from '@/composables/useTranslation';
import { useVehicleAdFieldTranslation } from '@/composables/useVehicleAdFieldTranslation';
import FilterCheckboxGroup from '../FilterCheckboxGroup.vue';

const { __ } = useTranslation();
const { translateVehicleAdField } = useVehicleAdFieldTranslation();

const form = defineModel<any>('form', { required: true });

const props = defineProps<{
    features: any[];
}>();

const translatedFeatures = computed(() =>
    props.features.map((feature) => {
        const code = feature?.code ?? feature?.key ?? '';

        return {
            ...feature,
            label: translateVehicleAdField('features', code),
        };
    }),
);
</script>

<template>
    <FilterGroup :label="__('vehicleAd.equipment')" :icon="Star" :is-active="form.features.length > 0">
        <FilterCheckboxGroup
            v-model="form.features"
            :options="translatedFeatures"
            option-label="label"
            label="feature"
        />
    </FilterGroup>
</template>
