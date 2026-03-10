<script setup lang="ts">
import { computed } from 'vue';
import { SidebarInset } from '@/components/ui/sidebar';

type Props = {
    variant?: 'header' | 'sidebar';
    class?: string;
};

const props = defineProps<Props>();
const className = computed(() => props.class);
</script>

<template>
    <SidebarInset v-if="props.variant === 'sidebar'" :class="className">
        <slot />
    </SidebarInset>
    <main v-else class="flex-1 bg-slate-50" :class="className">
        <div
            class="mx-auto flex h-full w-full max-w-7xl flex-col gap-4 rounded-xl pb-16 sm:pb-24"
        >
            <slot />
        </div>
    </main>
</template>
