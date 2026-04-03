<script setup lang="ts">
import { Cog } from 'lucide-vue-next';
import { computed } from 'vue';
import FilterCheckboxGroup from '@/components/VehicleAds/FilterCheckboxGroup.vue';
import FilterGroup from '@/components/VehicleAds/FilterGroup.vue';
import { useTranslation } from '@/composables/useTranslation';
import { useVehicleAdFieldTranslation } from '@/composables/useVehicleAdFieldTranslation';

const { __ } = useTranslation();
const { translateVehicleAdField } = useVehicleAdFieldTranslation();

const props = defineProps<{
    transmissionTypes: any[];
}>();

const form = defineModel<any>('form', { required: true });

const translatedTransmissionTypes = computed(() =>
    props.transmissionTypes.map((item) => ({
        ...item,
        label: translateVehicleAdField('transmission_types', item.code),
    })),
);
</script>

<template>
    <FilterGroup
        :label="__('vehicleAd.transmission')"
        :icon="Cog"
        :is-active="form.transmission_types.length > 0"
    >
        <FilterCheckboxGroup
            v-model="form.transmission_types"
            :options="translatedTransmissionTypes"
            option-label="label"
            label="transmission"
        />
    </FilterGroup>
</template>
