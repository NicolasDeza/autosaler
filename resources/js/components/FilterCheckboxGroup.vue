<script setup lang="ts">
import { Checkbox } from '@/components/ui/checkbox';
import { Label } from '@/components/ui/label';

const model = defineModel<string[]>({ default: [] });

const props = defineProps<{
    options: any[];
    optionLabel?: string;
    label?: string;
}>();

const prefix = props.label || 'filter';
const labelName = props.optionLabel || 'code';

const isChecked = (id: string) => {
    return model.value.includes(id);
};

const toggle = (id: string, checked: boolean | string) => {
    const isChecked = checked === true || checked === 'indeterminate';
    if (isChecked) {
        if (!model.value.includes(id)) {
            model.value.push(id);
        }
    } else {
        model.value = model.value.filter((v) => v !== id);
    }
};
</script>

<template>
    <div class="space-y-2 pt-2">
        <div
            v-for="option in options"
            :key="option.id"
            class="flex items-center space-x-2"
        >
            <Checkbox
                :id="`${prefix}-${option.id}`"
                :checked="isChecked(String(option.id))"
                @update:modelValue="(v) => toggle(String(option.id), v)"
            />

            <Label
                :for="`${prefix}-${option.id}`"
                class="cursor-pointer text-sm text-slate-300"
            >
                {{ option[labelName] }}
            </Label>
        </div>
    </div>
</template>
