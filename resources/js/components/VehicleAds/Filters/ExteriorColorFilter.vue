<script setup lang="ts">
import { Palette } from 'lucide-vue-next';
import { computed } from 'vue';
import FilterGroup from '@/components/VehicleAds/FilterGroup.vue';
import { useTranslation } from '@/composables/useTranslation';
import { useVehicleAdFieldTranslation } from '@/composables/useVehicleAdFieldTranslation';
import FilterSelect from './Partials/FilterSelect.vue';

const { __ } = useTranslation();
const { translateVehicleAdField } = useVehicleAdFieldTranslation();

const props = defineProps<{
    exteriorColors: any[];
}>();

const form = defineModel<any>('form', { required: true });

const translatedExteriorColors = computed(() =>
    props.exteriorColors.map((item) => ({
        ...item,
        label: translateVehicleAdField('exterior_colors', item.code),
    })),
);
</script>

<template>
    <FilterGroup
        :label="__('vehicleAd.exterior_color')"
        :icon="Palette"
        :is-active="form.exterior_color_id !== 'all'"
    >
        <FilterSelect
            v-model="form.exterior_color_id"
            :options="translatedExteriorColors"
            option-label="label"
            :placeholder="__('ui.all_colors')"
        />
    </FilterGroup>
</template>
