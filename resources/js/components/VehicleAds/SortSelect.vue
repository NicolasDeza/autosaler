<template>
    <div class="flex flex-col gap-1.5">
        <Select v-model="model">
            <SelectTrigger class="h-inherit w-full bg-card">
                <SelectValue />
            </SelectTrigger>
            <SelectContent>
                <SelectItem value="latest">
                    {{ __('ui.sorting.latest') }}
                </SelectItem>
                <SelectItem value="oldest">
                    {{ __('ui.sorting.oldest') }}
                </SelectItem>
                <SelectItem value="price_asc">
                    {{ __('ui.sorting.price_asc') }}
                </SelectItem>
                <SelectItem value="price_desc">
                    {{ __('ui.sorting.price_desc') }}
                </SelectItem>
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
import { useTranslation } from '@/composables/useTranslation';

const model = defineModel<string>({ default: 'latest' });
const { __ } = useTranslation();
</script>
