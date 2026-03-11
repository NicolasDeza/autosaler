<script setup lang="ts">
import { Search } from 'lucide-vue-next';
import { ref, computed } from 'vue';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';

const props = withDefaults(
    defineProps<{
        label?: string;
        modelValue?: string;
        options?: any[];
        optionLabel?: string;
        placeholder?: string;
        disabled?: boolean;
    }>(),
    {
        optionLabel: 'code',
        placeholder: 'Tous',
        disabled: false,
        options: () => [],
    },
);

const emit = defineEmits(['update:modelValue']);

const searchQuery = ref('');

const filteredOptions = computed(() => {
    const query = searchQuery.value.toLowerCase().trim();
    if (!query) return props.options;
    return props.options.filter((o: any) =>
        o[props.optionLabel]?.toLowerCase().includes(query),
    );
});

const handleInput = (e: Event) => {
    searchQuery.value = (e.target as HTMLInputElement).value;
};

const stopPropagation = (e: Event) => {
    e.stopPropagation();
};
</script>

<template>
    <div class="space-y-3">
        <Label v-if="label" class="text-sm font-semibold text-slate-200">{{
            label
        }}</Label>
        <Select
            :model-value="modelValue"
            @update:model-value="emit('update:modelValue', $event)"
            :disabled="disabled"
        >
            <SelectTrigger
                class="w-full border-slate-700 bg-slate-800 text-white disabled:opacity-50"
            >
                <SelectValue :placeholder="placeholder" />
            </SelectTrigger>
            <SelectContent class="border-slate-700 bg-slate-800 text-white">
                <div
                    class="sticky top-0 z-10 border-b border-slate-700 bg-slate-800 p-2"
                >
                    <div
                        class="flex items-center gap-2 rounded-md border border-slate-600 bg-slate-900 px-2"
                    >
                        <Search class="h-3.5 w-3.5 shrink-0 text-slate-400" />
                        <input
                            type="text"
                            class="h-8 w-full bg-transparent text-sm text-white placeholder-slate-400 outline-none"
                            placeholder="Rechercher..."
                            :value="searchQuery"
                            @input="handleInput"
                            @click="stopPropagation"
                            @pointerdown="stopPropagation"
                            @keydown="stopPropagation"
                        />
                    </div>
                </div>
                <SelectItem value="all">{{ placeholder }}</SelectItem>
                <SelectItem
                    v-for="o in filteredOptions"
                    :key="o.id"
                    :value="String(o.id)"
                >
                    {{ o[optionLabel] }}
                </SelectItem>
            </SelectContent>
        </Select>
    </div>
</template>
