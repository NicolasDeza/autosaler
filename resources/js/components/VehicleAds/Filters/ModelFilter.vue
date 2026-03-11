<script setup lang="ts">
import FilterGroup from '@/components/VehicleAds/FilterGroup.vue';
import FilterSelect from './Partials/FilterSelect.vue';
import { computed } from 'vue';

const props = defineProps<{
    models: any[];
}>();

const form = defineModel<any>('form', { required: true });

const isModelActive = computed(() => form.value.model_id !== 'all');

const isDisabled = computed(
    () =>
        !form.value.brand_id ||
        form.value.brand_id === 'all' ||
        !props.models.length,
);
</script>

<template>
    <FilterGroup
        label="Modèle"
        :is-active="isModelActive"
        :disabled="isDisabled"
    >
        <FilterSelect
            v-model="form.model_id"
            :options="models"
            option-label="name"
            placeholder="Tous les modèles"
            :disabled="isDisabled"
        />
    </FilterGroup>
</template>
