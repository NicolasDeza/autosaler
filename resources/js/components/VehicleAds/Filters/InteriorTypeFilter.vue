<script setup lang="ts">
import { Sofa } from 'lucide-vue-next';
import FilterGroup from '@/components/VehicleAds/FilterGroup.vue';
import { useTranslation } from '@/composables/useTranslation';
import FilterSelect from './Partials/FilterSelect.vue';

const { __ } = useTranslation();

defineProps<{
    interiorTypes: any[];
}>();

const form = defineModel<any>('form', { required: true });
</script>

<template>
    <FilterGroup
        :label="__('vehicleAd.interior_material')"
        :icon="Sofa"
        :is-active="form.interior_type_id !== 'all'"
    >
        <FilterSelect
            v-model="form.interior_type_id"
            :options="interiorTypes"
            option-label="code"
            :placeholder="__('ui.all_materials')"
        />
    </FilterGroup>
</template>
