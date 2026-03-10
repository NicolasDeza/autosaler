<script setup lang="ts">
import { ChevronDown } from 'lucide-vue-next';
import {
    Collapsible,
    CollapsibleContent,
    CollapsibleTrigger,
} from '@/components/ui/collapsible';
import { Label } from '@/components/ui/label';

interface Props {
    label: string;
    isActive?: boolean;
    defaultOpen?: boolean;
    disabled?: boolean;
}

withDefaults(defineProps<Props>(), {
    isActive: false,
    defaultOpen: false,
    disabled: false,
});
</script>

<template>
    <Collapsible
        :default-open="defaultOpen"
        :disabled="disabled"
        class="space-y-3"
    >
        <CollapsibleTrigger
            class="group flex w-full items-center justify-between disabled:cursor-not-allowed disabled:opacity-50"
        >
            <div class="flex items-center gap-2">
                <div
                    v-if="isActive"
                    class="h-2 w-2 rounded-full bg-blue-500 shadow-[0_0_8px_rgba(59,130,246,0.5)]"
                />
                <Label
                    class="cursor-pointer text-sm font-semibold text-slate-200 transition-colors group-hover:text-white"
                >
                    {{ label }}
                </Label>
            </div>
            <ChevronDown
                class="h-4 w-4 text-slate-400 transition-transform duration-200 group-data-[state=open]:rotate-180"
            />
        </CollapsibleTrigger>
        <CollapsibleContent
            force-mount
            class="transition-all data-[state=closed]:hidden"
        >
            <div class="pt-1">
                <slot />
            </div>
        </CollapsibleContent>
    </Collapsible>
</template>
