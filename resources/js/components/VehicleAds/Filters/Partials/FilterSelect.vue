<script setup lang="ts">
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';

defineProps<{
    label?: string;
    modelValue?: string;
    options?: any[];
    optionLabel?: string;
    placeholder?: string;
    disabled?: boolean;
}>();

const emit = defineEmits(['update:modelValue']);
</script>

<template>
    <div class="space-y-3">
        <Label v-if="label" class="text-sm font-semibold text-foreground">{{
            label
        }}</Label>
        <Select
            :model-value="modelValue"
            @update:model-value="emit('update:modelValue', $event)"
            :disabled="disabled"
        >
            <SelectTrigger
                class="w-full border-input bg-background text-foreground disabled:opacity-50"
            >
                <SelectValue :placeholder="placeholder || 'Tous'" />
            </SelectTrigger>
            <SelectContent class="border-border bg-popover text-popover-foreground">
                <SelectItem value="all">{{ placeholder || 'Tous' }}</SelectItem>
                <SelectItem
                    v-for="o in options || []"
                    :key="o.id"
                    :value="String(o.id)"
                >
                    {{ o[optionLabel || 'code'] }}
                </SelectItem>
            </SelectContent>
        </Select>
    </div>
</template>
