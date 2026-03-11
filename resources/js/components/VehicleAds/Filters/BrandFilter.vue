<script setup lang="ts">
import { computed } from 'vue';
import FilterGroup from '@/components/VehicleAds/FilterGroup.vue';
import FilterSearchSelect from './Partials/FilterSearchSelect.vue';

defineProps<{
    brands: any[];
}>();

const form = defineModel<any>('form', { required: true });

const isBrandActive = computed(() => form.value.brand_id !== 'all');
</script>

<template>
    <FilterGroup label="Marque" :is-active="isBrandActive">
        <FilterSearchSelect
            v-model="form.brand_id"
            :options="brands"
            option-label="name"
            placeholder="Toutes les marques"
        />
    </FilterGroup>
</template>
