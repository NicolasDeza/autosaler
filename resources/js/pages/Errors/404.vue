<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Home, MoveLeft, Search } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { useTranslation } from '@/composables/useTranslation';

const { __ } = useTranslation();

const goBack = () => {
    window.history.back();
};
</script>

<template>
    <Head :title="__('errors.404_title')" />

    <div
        class="relative flex min-h-screen flex-col items-center justify-center overflow-hidden bg-background px-6 py-24 sm:py-32 lg:px-8"
    >
        <!-- Background decorative elements -->
        <div
            class="bg-[radial-gradient(45rem_50rem_at_top,theme(colors.primary.100),transparent)] dark:bg-[radial-gradient(45rem_50rem_at_top,theme(colors.primary.900),transparent)] absolute inset-0 -z-10 opacity-20"
        ></div>

        <div class="text-center">
            <div class="mb-8 flex justify-center">
                <div class="relative">
                    <div
                        class="absolute -inset-1 rounded-full bg-linear-to-r from-primary to-orange-500 opacity-20 blur-lg"
                    ></div>
                    <div
                        class="relative flex size-20 items-center justify-center rounded-full border border-border bg-background shadow-xl"
                    >
                        <Search class="size-10 animate-pulse text-primary" />
                    </div>
                </div>
            </div>

            <p class="text-base font-semibold text-primary">404</p>
            <h1
                class="mt-4 text-3xl font-bold tracking-tight text-foreground sm:text-5xl"
            >
                {{ __('errors.404_header') }}
            </h1>
            <p
                class="mx-auto mt-6 max-w-md text-base leading-7 text-muted-foreground"
            >
                {{ __('errors.404_description') }}
            </p>

            <div
                class="mt-10 flex flex-col items-center justify-center gap-4 sm:flex-row"
            >
                <Button
                    as-child
                    size="lg"
                    class="w-full px-8 shadow-lg shadow-primary/20 transition-all duration-300 hover:scale-105 active:scale-95 sm:w-auto"
                >
                    <Link href="/">
                        <Home class="mr-2 size-5" />
                        {{ __('errors.back_to_home') }}
                    </Link>
                </Button>

                <Button
                    variant="outline"
                    size="lg"
                    class="w-full px-8 transition-all duration-300 hover:bg-muted sm:w-auto"
                    @click="goBack"
                >
                    <MoveLeft class="mr-2 size-5" />
                    {{ __('errors.go_back') }}
                </Button>
            </div>
        </div>

        <!-- Illustration element -->
        <div
            class="relative mx-auto mt-16 w-full max-w-2xl opacity-10 dark:opacity-20"
        >
            <div
                class="absolute top-0 left-1/2 h-px w-full -translate-x-1/2 bg-linear-to-r from-transparent via-border to-transparent"
            ></div>
            <div
                class="pt-8 text-center text-[12rem] font-black tracking-tighter text-muted-foreground/30 select-none"
            >
                404
            </div>
        </div>
    </div>
</template>
