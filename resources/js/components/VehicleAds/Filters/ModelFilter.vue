<script setup lang="ts">
import { CarFront } from 'lucide-vue-next';
import { computed } from 'vue';
import FilterGroup from '@/components/VehicleAds/FilterGroup.vue';
import { useTranslation } from '@/composables/useTranslation';
import FilterSelect from './Partials/FilterSelect.vue';

const { __ } = useTranslation();

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
        :label="__('vehicleAd.model')"
        :icon="CarFront"
        :is-active="isModelActive"
        :disabled="isDisabled"
    >
        <FilterSelect
            v-model="form.model_id"
            :options="models"
            option-label="name"
            :placeholder="__('ui.all_models')"
            :disabled="isDisabled"
        />
    </FilterGroup>
</template>
