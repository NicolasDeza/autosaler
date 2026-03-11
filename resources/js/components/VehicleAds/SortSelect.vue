<template>
    <div class="flex min-w-[200px] flex-col gap-1.5">
        <Select v-model="model">
            <SelectTrigger class="w-full bg-card">
                <SelectValue />
            </SelectTrigger>
            <SelectContent>
                <SelectItem value="latest"> Plus récentes </SelectItem>
                <SelectItem value="oldest"> Plus anciennes </SelectItem>
                <SelectItem value="price_asc"> Prix croissant </SelectItem>
                <SelectItem value="price_desc"> Prix décroissant </SelectItem>
            </SelectContent>
        </Select>
    </div>
</template>

<script setup lang="ts">
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';

const model = defineModel<string>({ default: 'latest' });
</script>
