<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Home, Wrench, RefreshCcw } from 'lucide-vue-next';
import AppLogoLg from '@/components/AppLogoLg.vue';
import { Button } from '@/components/ui/button';
import { useTranslation } from '@/composables/useTranslation';

const { __ } = useTranslation();

const reload = () => {
    window.location.reload();
};
</script>

<template>
    <Head :title="__('errors.503_title')" />

    <div
        class="relative flex min-h-screen flex-col items-center justify-center overflow-hidden bg-background px-6 py-20 lg:px-8"
    >
        <!-- Top Logo -->
        <div class="absolute top-8 left-0 flex w-full justify-center">
            <Link href="/" class="transition-opacity hover:opacity-80">
                <div class="flex scale-75 items-center gap-1">
                    <AppLogoLg />
                </div>
            </Link>
        </div>

        <!-- Background decorative elements -->
        <div
            class="bg-[radial-gradient(50rem_50rem_at_top,theme(colors.primary.100),transparent)] dark:bg-[radial-gradient(50rem_50rem_at_top,theme(colors.primary.900),transparent)] absolute inset-0 -z-10 opacity-20"
        ></div>

        <div class="z-10 text-center">
            <!-- Central Visual (Icon behind text) -->
            <div class="relative mb-8 flex items-center justify-center">
                <!-- Large Transparent Background Icon -->
                <div
                    class="absolute inset-0 -z-10 flex items-center justify-center opacity-[0.03] dark:opacity-[0.05]"
                >
                    <Wrench class="size-80 sm:size-128" />
                </div>

                <div class="relative py-12">
                    <p
                        class="text-sm font-bold tracking-[0.3em] text-primary uppercase"
                    >
                        {{ __('errors.503_maintenance') }}
                    </p>
                    <h1
                        class="mt-6 text-4xl font-extrabold tracking-tight text-foreground sm:text-6xl"
                    >
                        {{ __('errors.503_header') }}
                    </h1>
                    <p
                        class="mx-auto mt-6 max-w-lg text-lg leading-relaxed text-muted-foreground"
                    >
                        {{ __('errors.503_description') }}
                    </p>
                </div>
            </div>

            <div
                class="mt-12 flex flex-col items-center justify-center gap-5 sm:flex-row"
            >
                <Button
                    as-child
                    size="lg"
                    class="h-14 w-full rounded-xl bg-primary px-10 text-base font-bold text-white shadow-xl shadow-primary/20 transition-all duration-300 hover:scale-105 hover:bg-primary/80 active:scale-95 sm:w-auto"
                >
                    <Link href="/">
                        <Home class="mr-2 size-5" />
                        {{ __('errors.back_to_home') }}
                    </Link>
                </Button>

                <Button
                    variant="outline"
                    size="lg"
                    class="h-14 w-full rounded-xl border-white/10 bg-white/5 px-10 text-base font-bold backdrop-blur-sm transition-all duration-300 hover:bg-white/10 sm:w-auto"
                    @click="reload"
                >
                    <RefreshCcw class="mr-2 size-5" />
                    {{ __('errors.refresh_page') || 'Refresh' }}
                </Button>
            </div>
        </div>
    </div>
</template>
