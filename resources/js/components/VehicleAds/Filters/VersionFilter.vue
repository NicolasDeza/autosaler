<script setup lang="ts">
import { Layers } from 'lucide-vue-next';
import { Input } from '@/components/ui/input';
import FilterGroup from '@/components/VehicleAds/FilterGroup.vue';
import { useTranslation } from '@/composables/useTranslation';

const { __ } = useTranslation();

const form = defineModel<any>('form', { required: true });
</script>

<template>
    <FilterGroup :label="__('vehicleAd.version')" :icon="Layers" :is-active="!!form.version">
        <div class="pt-2">
            <Input
                v-model="form.version"
                :placeholder="__('vehicleAd.version_placeholder')"
                class="bg-card"
            />
        </div>
    </FilterGroup>
</template>
