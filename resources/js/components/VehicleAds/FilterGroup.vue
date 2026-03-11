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
    overflowVisible?: boolean;
}

withDefaults(defineProps<Props>(), {
    isActive: false,
    defaultOpen: false,
    disabled: false,
    overflowVisible: false,
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
                    class="h-2 w-2 rounded-full bg-red-500 shadow-[0_0_8px_rgba(59,130,246,0.5)]"
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
            class="collapsible-content rounded-md"
            :class="{ 'overflow-visible!': overflowVisible }"
        >
            <div class="pt-1">
                <slot />
            </div>
        </CollapsibleContent>
    </Collapsible>
</template>

<style scoped>
.collapsible-content {
    overflow: hidden;
}

.collapsible-content[data-state='open'] {
    animation: slideDown 300ms cubic-bezier(0.4, 0, 0.2, 1);
}

.collapsible-content[data-state='closed'] {
    animation: slideUp 300ms cubic-bezier(0.4, 0, 0.2, 1);
}

@keyframes slideDown {
    from {
        height: 0;
    }
    to {
        height: var(--reka-collapsible-content-height);
    }
}

@keyframes slideUp {
    from {
        height: var(--reka-collapsible-content-height);
    }
    to {
        height: 0;
    }
}
</style>
