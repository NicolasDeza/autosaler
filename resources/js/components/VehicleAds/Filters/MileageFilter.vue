<script setup lang="ts">
import { Gauge } from 'lucide-vue-next';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import FilterGroup from '@/components/VehicleAds/FilterGroup.vue';

const form = defineModel<any>('form', { required: true });
</script>

<template>
    <FilterGroup label="Kilométrage" :icon="Gauge" :is-active="form.max_mileage !== 'all'">
        <Select v-model="form.max_mileage">
            <SelectTrigger
                class="w-full border-input bg-background text-foreground"
            >
                <SelectValue placeholder="Peu importe" />
            </SelectTrigger>
            <SelectContent class="border-border bg-popover text-popover-foreground">
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
