<script setup lang="ts">
import { Star } from 'lucide-vue-next';
import FilterGroup from '@/components/VehicleAds/FilterGroup.vue';
import FilterCheckboxGroup from '../FilterCheckboxGroup.vue';

defineProps<{
    features: any[];
}>();

const form = defineModel<any>('form', { required: true });
</script>

<template>
    <FilterGroup label="Equipements" :icon="Star" :is-active="form.features.length > 0">
        <FilterCheckboxGroup
            v-model="form.features"
            :options="features"
            option-label="key"
            label="feature"
        />
    </FilterGroup>
</template>
