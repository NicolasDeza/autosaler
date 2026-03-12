<script setup lang="ts">
import { Layers } from 'lucide-vue-next';
import { Input } from '@/components/ui/input';
import FilterGroup from '@/components/VehicleAds/FilterGroup.vue';

const form = defineModel<any>('form', { required: true });
</script>

<template>
    <FilterGroup label="Version" :icon="Layers" :is-active="!!form.version">
        <div class="pt-2">
            <Input
                v-model="form.version"
                placeholder="Ex: Sport, Edition, ..."
                class="bg-card"
            />
        </div>
    </FilterGroup>
</template>
