<script setup lang="ts">
import { Leaf } from 'lucide-vue-next';
import FilterGroup from '@/components/VehicleAds/FilterGroup.vue';
import { useTranslation } from '@/composables/useTranslation';
import FilterSelect from './Partials/FilterSelect.vue';

const { __ } = useTranslation();

defineProps<{
    euroNorms: any[];
}>();

const form = defineModel<any>('form', { required: true });
</script>

<template>
    <FilterGroup :label="__('vehicleAd.euro_norm')" :icon="Leaf" :is-active="form.euro_norm_id !== 'all'">
        <FilterSelect
            v-model="form.euro_norm_id"
            :options="euroNorms"
            option-label="code"
            :placeholder="__('ui.any')"
        />
    </FilterGroup>
</template>
