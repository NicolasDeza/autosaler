<script setup lang="ts">
import { Search } from 'lucide-vue-next';
import { ref, computed } from 'vue';
import { Input } from '@/components/ui/input';
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
        modelValue?: string | number | null;
        options?: any[];
        optionLabel?: string;
        optionValue?: string;
        placeholder?: string;
        disabled?: boolean;
        triggerClass?: string;
        contentClass?: string;
        showAllOption?: boolean;
        allOptionValue?: string;
        searchPlaceholder?: string;
    }>(),
    {
        optionLabel: 'name',
        optionValue: 'id',
        placeholder: 'Tous',
        disabled: false,
        options: () => [],
        triggerClass: '',
        contentClass: '',
        showAllOption: true,
        allOptionValue: 'all',
        searchPlaceholder: 'Rechercher...',
    },
);

const emit = defineEmits<{
    (e: 'update:modelValue', value: string): void;
}>();

const searchQuery = ref('');

const filteredOptions = computed(() => {
    const query = searchQuery.value.toLowerCase().trim();
    if (!query) return props.options;
    return props.options.filter((o: any) =>
        String(o[props.optionLabel])?.toLowerCase().includes(query),
    );
});

const stopPropagation = (e: Event) => {
    e.stopPropagation();
};
</script>

<template>
    <div class="space-y-3">
        <Label v-if="label" class="text-sm font-semibold text-foreground">{{
            label
        }}</Label>
        <Select
            :model-value="modelValue != null ? String(modelValue) : undefined"
            @update:model-value="emit('update:modelValue', $event as string)"
            :disabled="disabled"
        >
            <SelectTrigger
                :class="[
                    'w-full border-input text-foreground hover:border-primary/30 hover:bg-background hover:shadow-sm disabled:opacity-50',
                    triggerClass,
                ]"
            >
                <SelectValue :placeholder="placeholder" />
            </SelectTrigger>
            <SelectContent
                :class="[
                    'border-border bg-popover text-popover-foreground',
                    contentClass,
                ]"
            >
                <div
                    class="sticky top-0 z-10 border-b border-border bg-popover pb-2"
                >
                    <div class="relative">
                        <Search
                            class="absolute top-1/2 left-2 h-3.5 w-3.5 shrink-0 -translate-y-1/2 text-muted-foreground"
                        />
                        <Input
                            class="h-8 w-full bg-muted/30 pl-8 text-sm placeholder:text-muted-foreground"
                            :placeholder="searchPlaceholder"
                            v-model="searchQuery"
                            @click="stopPropagation"
                            @pointerdown="stopPropagation"
                            @keydown="stopPropagation"
                        />
                    </div>
                </div>
                <SelectItem v-if="showAllOption" :value="allOptionValue">
                    {{ placeholder }}
                </SelectItem>
                <SelectItem
                    v-for="o in filteredOptions"
                    :key="o[optionValue]"
                    :value="String(o[optionValue])"
                >
                    {{ o[optionLabel] }}
                </SelectItem>
            </SelectContent>
        </Select>
    </div>
</template>
