<script setup lang="ts">
import { Leaf } from 'lucide-vue-next';
import { computed } from 'vue';
import FilterGroup from '@/components/VehicleAds/FilterGroup.vue';
import { useTranslation } from '@/composables/useTranslation';
import { useVehicleAdFieldTranslation } from '@/composables/useVehicleAdFieldTranslation';
import FilterSelect from './Partials/FilterSelect.vue';

const { __ } = useTranslation();
const { translateVehicleAdField } = useVehicleAdFieldTranslation();

const props = defineProps<{
    euroNorms: any[];
}>();

const form = defineModel<any>('form', { required: true });

const translatedEuroNorms = computed(() =>
    props.euroNorms.map((item) => ({
        ...item,
        label: translateVehicleAdField('euro_norms', item.code),
    })),
);
</script>

<template>
    <FilterGroup :label="__('vehicleAd.euro_norm')" :icon="Leaf" :is-active="form.euro_norm_id !== 'all'">
        <FilterSelect
            v-model="form.euro_norm_id"
            :options="translatedEuroNorms"
            option-label="label"
            :placeholder="__('ui.any')"
        />
    </FilterGroup>
</template>
