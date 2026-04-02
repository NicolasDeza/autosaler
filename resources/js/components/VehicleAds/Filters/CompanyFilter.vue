<script setup lang="ts">
import { Building2 } from 'lucide-vue-next';
import { computed } from 'vue';
import SearchSelect from '@/components/SearchSelect.vue';
import FilterGroup from '@/components/VehicleAds/FilterGroup.vue';
import { useTranslation } from '@/composables/useTranslation';

const { __ } = useTranslation();

defineProps<{
    companies: any[];
}>();

const form = defineModel<any>('form', { required: true });

const isCompanyActive = computed(() => form.value.company_id && form.value.company_id !== 'all');
</script>

<template>
    <FilterGroup
        :label="__('vehicleAd.company') || 'Société'"
        :icon="Building2"
        :is-active="isCompanyActive"
    >
        <SearchSelect
            v-model="form.company_id"
            :options="companies"
            option-label="name"
            :placeholder="__('ui.all_companies') || 'Toutes les sociétés'"
        />
    </FilterGroup>
</template>
