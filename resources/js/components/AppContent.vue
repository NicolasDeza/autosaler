<script setup lang="ts">
import { computed } from 'vue';
import { SidebarInset } from '@/components/ui/sidebar';

type Props = {
    variant?: 'header' | 'sidebar';
    class?: string;
    isOffline?: boolean;
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
            v-if="!isOffline"
            class="mx-auto flex h-full w-full max-w-7xl flex-col gap-4 px-4 pb-16 sm:px-6 sm:pb-24 lg:px-8"
        >
            <slot />
        </div>
        <div v-else class="flex h-full w-full items-center justify-center">
            <div class="text-center">
                <h1 class="text-2xl font-bold">Offline</h1>
                <p class="text-gray-500">You are offline</p>
            </div>
        </div>

        <Teleport
            v-if="$slots['sticky-bottom']"
            to="#sticky-bottom-mobile-portal"
        >
            <slot name="sticky-bottom" />
        </Teleport>

        <Teleport
            v-if="$slots['sticky-bottom']"
            to="#sticky-bottom-desktop-portal"
        >
            <slot name="sticky-bottom" />
        </Teleport>
    </main>
</template>
