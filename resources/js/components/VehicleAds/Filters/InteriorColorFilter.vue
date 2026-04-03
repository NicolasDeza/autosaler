<script setup lang="ts">
import { PaintBucket } from 'lucide-vue-next';
import { computed } from 'vue';
import FilterGroup from '@/components/VehicleAds/FilterGroup.vue';
import { useTranslation } from '@/composables/useTranslation';
import { useVehicleAdFieldTranslation } from '@/composables/useVehicleAdFieldTranslation';
import FilterSelect from './Partials/FilterSelect.vue';

const { __ } = useTranslation();
const { translateVehicleAdField } = useVehicleAdFieldTranslation();

const props = defineProps<{
    interiorColors: any[];
}>();

const form = defineModel<any>('form', { required: true });

const translatedInteriorColors = computed(() =>
    props.interiorColors.map((item) => ({
        ...item,
        label: translateVehicleAdField('interior_colors', item.code),
    })),
);
</script>

<template>
    <FilterGroup
        :label="__('vehicleAd.interior_color')"
        :icon="PaintBucket"
        :is-active="form.interior_color_id !== 'all'"
    >
        <FilterSelect
            v-model="form.interior_color_id"
            :options="translatedInteriorColors"
            option-label="label"
            :placeholder="__('ui.all_colors')"
        />
    </FilterGroup>
</template>
