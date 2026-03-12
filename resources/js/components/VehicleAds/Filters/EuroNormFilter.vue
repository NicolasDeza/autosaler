<script setup lang="ts">
import { Leaf } from 'lucide-vue-next';
import FilterGroup from '@/components/VehicleAds/FilterGroup.vue';
import FilterSelect from './Partials/FilterSelect.vue';

defineProps<{
    euroNorms: any[];
}>();

const form = defineModel<any>('form', { required: true });
</script>

<template>
    <FilterGroup label="Norme Euro" :icon="Leaf" :is-active="form.euro_norm_id !== 'all'">
        <FilterSelect
            v-model="form.euro_norm_id"
            :options="euroNorms"
            option-label="code"
            placeholder="Peu importe"
        />
    </FilterGroup>
</template>
