<script setup lang="ts">
import FilterCheckboxGroup from '@/components/VehicleAds/FilterCheckboxGroup.vue';
import FilterGroup from '@/components/VehicleAds/FilterGroup.vue';

defineProps<{
    bodyTypes: any[];
}>();

const form = defineModel<any>('form', { required: true });
</script>

<template>
    <FilterGroup label="Carrosserie" :is-active="form.body_types.length > 0">
        <FilterCheckboxGroup
            v-model="form.body_types"
            :options="bodyTypes"
            label="body"
        />
    </FilterGroup>
</template>
