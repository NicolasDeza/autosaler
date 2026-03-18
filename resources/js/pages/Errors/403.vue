<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ShieldAlert, Home, Lock } from 'lucide-vue-next';
import AppLogoLg from '@/components/AppLogoLg.vue';
import { Button } from '@/components/ui/button';
import { useTranslation } from '@/composables/useTranslation';

const { __ } = useTranslation();
</script>

<template>
    <Head :title="__('errors.403_title')" />

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
            class="bg-[radial-gradient(50rem_50rem_at_top,theme(colors.destructive.100),transparent)] dark:bg-[radial-gradient(50rem_50rem_at_top,theme(colors.destructive.900),transparent)] absolute inset-0 -z-10 opacity-20"
        ></div>

        <div class="z-10 text-center">
            <!-- Central Visual -->
            <div class="relative mb-12 flex items-center justify-center">
                <!-- Large Transparent Background Icon -->
                <div class="absolute inset-0 flex items-center justify-center">
                    <Lock
                        class="size-64 opacity-[0.03] sm:size-80 dark:opacity-[0.05]"
                    />
                </div>

                <!-- Error Number -->
                <h2
                    class="relative mt-4 text-[8rem] leading-none font-black tracking-tighter text-destructive opacity-40 drop-shadow-sm select-none sm:text-[12rem]"
                >
                    403
                </h2>
            </div>

            <p
                class="text-sm font-bold tracking-[0.3em] text-destructive uppercase"
            >
                {{ __('errors.403_forbidden') }}
            </p>
            <h1
                class="mt-6 text-4xl font-extrabold tracking-tight text-foreground sm:text-6xl"
            >
                {{ __('errors.403_header') }}
            </h1>
            <p
                class="mx-auto mt-6 max-w-lg text-lg leading-relaxed text-muted-foreground"
            >
                {{ __('errors.403_description') }}
            </p>

            <div
                class="mt-12 flex flex-col items-center justify-center gap-5 sm:flex-row"
            >
                <Button
                    as-child
                    size="lg"
                    class="h-14 w-full rounded-xl px-10 text-base font-bold shadow-xl shadow-primary/20 transition-all duration-300 hover:scale-105 active:scale-95 sm:w-auto"
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
                    as-child
                >
                    <Link href="/login">
                        <ShieldAlert class="mr-2 size-5" />
                        {{ __('errors.login_as_another') }}
                    </Link>
                </Button>
            </div>
        </div>
    </div>
</template>
