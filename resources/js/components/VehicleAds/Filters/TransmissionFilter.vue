<script setup lang="ts">
import { Cog } from 'lucide-vue-next';
import FilterGroup from '@/components/VehicleAds/FilterGroup.vue';
import FilterCheckboxGroup from '@/components/VehicleAds/FilterCheckboxGroup.vue';

defineProps<{
    transmissionTypes: any[];
}>();

const form = defineModel<any>('form', { required: true });
</script>

<template>
    <FilterGroup
        label="Boîte de vitesse"
        :icon="Cog"
        :is-active="form.transmission_types.length > 0"
    >
        <FilterCheckboxGroup
            v-model="form.transmission_types"
            :options="transmissionTypes"
            label="transmission"
        />
    </FilterGroup>
</template>
