<script setup lang="ts">
import { onMounted, ref } from 'vue';
import ComparisonBar from '@/components/VehicleAds/ComparisonBar.vue';
import AppLayout from '@/layouts/app/AppHeaderLayout.vue';
import type { BreadcrumbItem } from '@/types';

type Props = {
    breadcrumbs?: BreadcrumbItem[];
};

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});

const isOffline = ref(!navigator.onLine);

onMounted(() => {
    window.addEventListener('offline', () => (isOffline.value = true));
    window.addEventListener('online', () => (isOffline.value = false));
});
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <template #full-width>
            <div v-if="isOffline" class="bg-red-600 px-4 py-2 text-center text-sm font-medium text-white shadow-inner animate-pulse">
                You are currently offline. Some features may be unavailable.
            </div>
            <slot name="full-width" />
        </template>

        <slot />
    </AppLayout>

    <ComparisonBar />
</template>
