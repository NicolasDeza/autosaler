<script setup lang="ts">
import { Sofa } from 'lucide-vue-next';
import { computed } from 'vue';
import FilterGroup from '@/components/VehicleAds/FilterGroup.vue';
import { useTranslation } from '@/composables/useTranslation';
import { useVehicleAdFieldTranslation } from '@/composables/useVehicleAdFieldTranslation';
import FilterSelect from './Partials/FilterSelect.vue';

const { __ } = useTranslation();
const { translateVehicleAdField } = useVehicleAdFieldTranslation();

const props = defineProps<{
    interiorTypes: any[];
}>();

const form = defineModel<any>('form', { required: true });

const translatedInteriorTypes = computed(() =>
    props.interiorTypes.map((item) => ({
        ...item,
        label: translateVehicleAdField('interior_types', item.code),
    })),
);
</script>

<template>
    <FilterGroup
        :label="__('vehicleAd.interior_material')"
        :icon="Sofa"
        :is-active="form.interior_type_id !== 'all'"
    >
        <FilterSelect
            v-model="form.interior_type_id"
            :options="translatedInteriorTypes"
            option-label="label"
            :placeholder="__('ui.all_materials')"
        />
    </FilterGroup>
</template>
