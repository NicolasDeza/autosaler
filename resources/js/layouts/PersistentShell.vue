<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { computed, onMounted, ref } from 'vue';
import AppFooter from '@/components/AppFooter.vue';
import AppHeader from '@/components/AppHeader.vue';
import AppMobileBottomBar from '@/components/AppMobileBottomBar.vue';
import AppShell from '@/components/AppShell.vue';
import CookieBanner from '@/components/CookieBanner.vue';
import InstallBanner from '@/components/PWA/InstallBanner.vue';
import ComparisonBar from '@/components/VehicleAds/ComparisonBar.vue';

const page = usePage();

// Breadcrumbs can be passed as props from any page
const breadcrumbs = computed(() => (page.props as any).breadcrumbs || []);

const isOffline = ref(false);

onMounted(() => {
    isOffline.value = !navigator.onLine;
    window.addEventListener('offline', () => (isOffline.value = true));
    window.addEventListener('online', () => (isOffline.value = false));
});
</script>

<template>
    <AppShell variant="header" class="flex-col">
        <AppHeader :breadcrumbs="breadcrumbs" />
        
        <div
            v-if="isOffline"
            class="animate-pulse bg-red-600 px-4 py-2 text-center text-sm font-medium text-white shadow-inner"
        >
            You are currently offline. Some features may be unavailable.
        </div>

        <InstallBanner />

        <!-- Full-Width Content (Hero, etc.) -->
        <slot name="full-width" />

        <div class="flex-1">
            <slot />
        </div>

        <!-- Desktop Persistent Bottom Slot -->
        <div
            id="sticky-bottom-desktop-portal"
            class="sticky bottom-0 z-50 hidden w-full lg:block"
        >
            <slot name="sticky-bottom" />
        </div>

        <!-- Mobile Dynamic Dock Bar / Bottom Bar -->
        <AppMobileBottomBar>
            <template #sticky-bottom-mobile>
                <div id="sticky-bottom-mobile-portal" class="flex-1">
                    <slot v-if="$slots['sticky-bottom']" name="sticky-bottom" />
                </div>
            </template>
        </AppMobileBottomBar>

        <AppFooter />
        <CookieBanner />
        <ComparisonBar />
    </AppShell>
</template>
