<script setup lang="ts">
import FilterGroup from '@/components/VehicleAds/FilterGroup.vue';
import FilterSelect from './Partials/FilterSelect.vue';

defineProps<{
    interiorTypes: any[];
}>();

const form = defineModel<any>('form', { required: true });
</script>

<template>
    <FilterGroup
        label="Matériaux intérieur"
        :is-active="form.interior_type_id !== 'all'"
    >
        <FilterSelect
            v-model="form.interior_type_id"
            :options="interiorTypes"
            option-label="code"
            placeholder="Tous les matériaux"
        />
    </FilterGroup>
</template>
