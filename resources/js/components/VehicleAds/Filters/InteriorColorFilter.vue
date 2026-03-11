<script setup lang="ts">
import FilterGroup from '@/components/VehicleAds/FilterGroup.vue';
import FilterSelect from './Partials/FilterSelect.vue';

defineProps<{
    interiorColors: any[];
}>();

const form = defineModel<any>('form', { required: true });
</script>

<template>
    <FilterGroup
        label="Couleur intérieur"
        :is-active="form.interior_color_id !== 'all'"
    >
        <FilterSelect
            v-model="form.interior_color_id"
            :options="interiorColors"
            option-label="code"
            placeholder="Toutes les couleurs"
        />
    </FilterGroup>
</template>
