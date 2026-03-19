<script setup lang="ts">
import { Smartphone } from 'lucide-vue-next';
import { onMounted, ref } from 'vue';
import InstallApp from '@/components/InstallApp.vue';
import { Button } from '@/components/ui/button';
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
            <div
                v-if="isOffline"
                class="animate-pulse bg-red-600 px-4 py-2 text-center text-sm font-medium text-white shadow-inner"
            >
                You are currently offline. Some features may be unavailable.
            </div>

            <InstallApp v-slot="{ install }">
                <div
                    class="flex animate-in items-center gap-x-3 border-b border-border/50 bg-primary px-4 py-1.5 backdrop-blur-md transition-all duration-300 fade-in slide-in-from-top-4"
                >
                    <Smartphone
                        class="h-4 w-4 shrink-0 text-primary-foreground/60"
                    />
                    <p
                        class="flex-1 truncate text-xs leading-none font-medium text-primary-foreground sm:whitespace-normal"
                    >
                        Installez l'application Autosaler sur votre appareil.
                    </p>
                    <Button
                        variant="outline"
                        size="sm"
                        class="h-7 cursor-pointer rounded-md bg-primary-foreground px-3 text-[10px] font-bold tracking-widest text-foreground uppercase transition-all hover:bg-muted-foreground/10 active:scale-95"
                        @click="install"
                    >
                        Installer
                    </Button>
                </div>
            </InstallApp>

            <slot name="full-width" />
        </template>

        <slot />
    </AppLayout>

    <ComparisonBar />
</template>
