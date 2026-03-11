<script setup lang="ts">
import FilterGroup from '@/components/VehicleAds/FilterGroup.vue';
import FilterSelect from './Partials/FilterSelect.vue';

defineProps<{
    versions: any[];
}>();

const form = defineModel<any>('form', { required: true });
</script>

<template>
    <FilterGroup
        v-if="form.model_id && form.model_id !== 'all'"
        label="Version"
        :is-active="form.version_id !== 'all'"
    >
        <FilterSelect
            v-model="form.version_id"
            :options="versions"
            option-label="name"
            placeholder="Toutes les versions"
        />
    </FilterGroup>
</template>
