<script setup lang="ts">
import { Car } from 'lucide-vue-next';
import { computed } from 'vue';
import FilterGroup from '@/components/VehicleAds/FilterGroup.vue';
import { useTranslation } from '@/composables/useTranslation';
import FilterSearchSelect from './Partials/FilterSearchSelect.vue';

const { __ } = useTranslation();

defineProps<{
    brands: any[];
}>();

const form = defineModel<any>('form', { required: true });

const isBrandActive = computed(() => form.value.brand_id !== 'all');
</script>

<template>
    <FilterGroup
        :label="__('vehicleAd.brand')"
        :icon="Car"
        :is-active="isBrandActive"
    >
        <FilterSearchSelect
            v-model="form.brand_id"
            :options="brands"
            option-label="name"
            :placeholder="__('ui.all_brands')"
        />
    </FilterGroup>
</template>
