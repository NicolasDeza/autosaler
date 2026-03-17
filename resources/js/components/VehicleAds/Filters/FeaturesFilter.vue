<script setup lang="ts">
import { Star } from 'lucide-vue-next';
import FilterGroup from '@/components/VehicleAds/FilterGroup.vue';
import { useTranslation } from '@/composables/useTranslation';
import FilterCheckboxGroup from '../FilterCheckboxGroup.vue';

const { __ } = useTranslation();

defineProps<{
    features: any[];
}>();

const form = defineModel<any>('form', { required: true });
</script>

<template>
    <FilterGroup :label="__('vehicleAd.equipment')" :icon="Star" :is-active="form.features.length > 0">
        <FilterCheckboxGroup
            v-model="form.features"
            :options="features"
            option-label="code"
            label="feature"
        />
    </FilterGroup>
</template>
