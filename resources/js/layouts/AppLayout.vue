<script setup lang="ts">
import { onMounted, ref } from 'vue';
import InstallBanner from '@/components/PWA/InstallBanner.vue';
import ComparisonBar from '@/components/VehicleAds/ComparisonBar.vue';
import AppHeaderLayout from '@/layouts/app/AppHeaderLayout.vue';
import type { BreadcrumbItem } from '@/types';

type Props = {
    breadcrumbs?: BreadcrumbItem[];
};


withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});

const isOffline = ref(false);


onMounted(() => {
    isOffline.value = !navigator.onLine;
    window.addEventListener('offline', () => (isOffline.value = true));
    window.addEventListener('online', () => (isOffline.value = false));
});
</script>

<template>
    <AppHeaderLayout :breadcrumbs="breadcrumbs">
        <template #full-width>
            <div
                v-if="isOffline"
                class="animate-pulse bg-red-600 px-4 py-2 text-center text-sm font-medium text-white shadow-inner"
            >
                You are currently offline. Some features may be unavailable.
            </div>

            <InstallBanner />

            <slot name="full-width" />
        </template>

        <template #sticky-bottom>
            <slot name="sticky-bottom" />
        </template>

        <slot />
    </AppHeaderLayout>

    <ComparisonBar />
</template>
