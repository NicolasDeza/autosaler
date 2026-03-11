<script setup lang="ts">
import FilterGroup from '@/components/VehicleAds/FilterGroup.vue';
import FilterSelect from './Partials/FilterSelect.vue';

defineProps<{
    exteriorColors: any[];
}>();

const form = defineModel<any>('form', { required: true });
</script>

<template>
    <FilterGroup
        label="Couleur extérieur"
        :is-active="form.exterior_color_id !== 'all'"
    >
        <FilterSelect
            v-model="form.exterior_color_id"
            :options="exteriorColors"
            option-label="code"
            placeholder="Toutes les couleurs"
        />
    </FilterGroup>
</template>
