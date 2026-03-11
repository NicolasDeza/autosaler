<script setup lang="ts">
import FilterGroup from '@/components/VehicleAds/FilterGroup.vue';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';

const form = defineModel<any>('form', { required: true });
</script>

<template>
    <FilterGroup label="Kilométrage" :is-active="form.max_mileage !== 'all'">
        <Select v-model="form.max_mileage">
            <SelectTrigger
                class="w-full border-slate-700 bg-slate-800 text-white"
            >
                <SelectValue placeholder="Peu importe" />
            </SelectTrigger>
            <SelectContent class="border-slate-700 bg-slate-800 text-white">
                <SelectItem value="all">Peu importe</SelectItem>
                <SelectItem value="10000">10 000 km</SelectItem>
                <SelectItem value="25000">25 000 km</SelectItem>
                <SelectItem value="50000">50 000 km</SelectItem>
                <SelectItem value="100000">100 000 km</SelectItem>
                <SelectItem value="150000">150 000 km</SelectItem>
                <SelectItem value="200000">200 000 km</SelectItem>
            </SelectContent>
        </Select>
    </FilterGroup>
</template>
