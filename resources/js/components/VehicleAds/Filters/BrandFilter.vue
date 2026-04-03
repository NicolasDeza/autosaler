<script setup lang="ts">
import { Car } from 'lucide-vue-next';
import { computed } from 'vue';
import SearchSelect from '@/components/SearchSelect.vue';
import FilterGroup from '@/components/VehicleAds/FilterGroup.vue';
import { useTranslation } from '@/composables/useTranslation';

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
        <SearchSelect
            v-model="form.brand_id"
            :options="brands"
            option-label="name"
            :placeholder="__('ui.all_brands')"
            search-input-class="text-base md:text-sm"
        />
    </FilterGroup>
</template>
