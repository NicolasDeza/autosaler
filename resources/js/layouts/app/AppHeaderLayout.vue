<script setup lang="ts">
import AppContent from '@/components/AppContent.vue';
import AppFooter from '@/components/AppFooter.vue';
import AppHeader from '@/components/AppHeader.vue';
import AppShell from '@/components/AppShell.vue';
import CookieBanner from '@/components/CookieBanner.vue';
import type { BreadcrumbItem } from '@/types';

type Props = {
    breadcrumbs?: BreadcrumbItem[];
};

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>

<template>
    <AppShell class="flex-col">
        <AppHeader :breadcrumbs="breadcrumbs" />
        <slot name="full-width" />
        <AppContent>
            <slot />
        </AppContent>
        <div id="sticky-bottom-container" class="sticky bottom-0 z-50 w-full">
            <slot name="sticky-bottom" />
        </div>
        <AppFooter />
        <CookieBanner />
    </AppShell>
</template>
