<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ChevronDown, ChevronUp, Cookie, ShieldCheck } from 'lucide-vue-next';
import { ref } from 'vue';
import { useCookieConsent } from '@/composables/useCookieConsent';
import { useTranslation } from '@/composables/useTranslation';
import { cookies } from '@/routes/legal';

const { hasDecided, acceptAll, rejectAll, saveCustom } = useCookieConsent();
const { __ } = useTranslation();

const showDetails = ref(false);
const analyticsChoice = ref(false);
const marketingChoice = ref(false);

function handleAcceptAll() {
    acceptAll();
}

function handleRejectAll() {
    rejectAll();
}

function handleSaveCustom() {
    saveCustom(analyticsChoice.value, marketingChoice.value);
}
</script>

<template>
    <Transition
        enter-active-class="transition duration-300 ease-out"
        enter-from-class="translate-y-full opacity-0"
        enter-to-class="translate-y-0 opacity-100"
        leave-active-class="transition duration-200 ease-in"
        leave-from-class="translate-y-0 opacity-100"
        leave-to-class="translate-y-full opacity-0"
    >
        <div
            v-if="!hasDecided"
            class="fixed inset-x-0 bottom-0 z-50 border-t border-border bg-background/95 shadow-[0_-8px_30px_rgba(0,0,0,0.12)] backdrop-blur-md print:hidden"
            role="dialog"
            aria-labelledby="cookie-banner-title"
            aria-modal="false"
        >
            <!-- Accent line -->
            <div class="h-px bg-linear-to-r from-transparent via-red-500 to-transparent" />

            <div class="mx-auto max-w-7xl px-4 py-4 sm:px-6">

                <!-- Main row -->
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-start gap-3">
                        <div class="mt-0.5 hidden shrink-0 rounded-full bg-red-500/10 p-2 sm:flex">
                            <Cookie class="size-4 text-red-500" />
                        </div>
                        <div>
                            <p id="cookie-banner-title" class="text-sm font-semibold text-foreground">
                                {{ __('cookieBanner.banner_title') }}
                            </p>
                            <p class="mt-0.5 text-xs leading-relaxed text-muted-foreground">
                                {{ __('cookieBanner.banner_desc') }}
                                <Link :href="cookies().url" class="font-medium text-red-500 underline underline-offset-2 hover:text-red-400">
                                    {{ __('cookieBanner.learn_more') }}
                                </Link>
                            </p>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex shrink-0 flex-wrap items-center gap-2">
                        <button
                            type="button"
                            class="flex items-center gap-1.5 rounded-md border border-border px-3 py-1.5 text-xs font-medium text-muted-foreground transition-colors hover:border-foreground/30 hover:text-foreground"
                            @click="showDetails = !showDetails"
                        >
                            {{ __('cookieBanner.customize') }}
                            <component :is="showDetails ? ChevronUp : ChevronDown" class="size-3.5" />
                        </button>
                        <button
                            type="button"
                            class="rounded-md border border-border px-3 py-1.5 text-xs font-medium text-muted-foreground transition-colors hover:border-foreground/30 hover:text-foreground"
                            @click="handleRejectAll"
                        >
                            {{ __('cookieBanner.reject') }}
                        </button>
                        <button
                            type="button"
                            class="flex items-center gap-1.5 rounded-md bg-red-500 px-4 py-1.5 text-xs font-semibold text-white transition-colors hover:bg-red-600 active:scale-95"
                            @click="handleAcceptAll"
                        >
                            <ShieldCheck class="size-3.5" />
                            {{ __('cookieBanner.accept_all') }}
                        </button>
                    </div>
                </div>

                <!-- Detailed preferences panel -->
                <Transition
                    enter-active-class="transition duration-200 ease-out overflow-hidden"
                    enter-from-class="max-h-0 opacity-0"
                    enter-to-class="max-h-96 opacity-100"
                    leave-active-class="transition duration-150 ease-in overflow-hidden"
                    leave-from-class="max-h-96 opacity-100"
                    leave-to-class="max-h-0 opacity-0"
                >
                    <div v-if="showDetails" class="mt-4 border-t border-border pt-4">
                        <div class="grid gap-3 sm:grid-cols-3">

                            <!-- Essential -->
                            <div class="flex items-start justify-between gap-3 rounded-lg border border-border bg-muted/30 p-3">
                                <div>
                                    <p class="text-xs font-semibold text-foreground">{{ __('cookieBanner.essential_title') }}</p>
                                    <p class="mt-0.5 text-xs text-muted-foreground">
                                        {{ __('cookieBanner.essential_desc') }}
                                    </p>
                                </div>
                                <button type="button" class="mt-0.5 shrink-0 h-5 w-9 rounded-full bg-red-500 opacity-50 cursor-not-allowed" disabled aria-checked="true" role="switch" />
                            </div>

                            <!-- Analytics -->
                            <div class="flex items-start justify-between gap-3 rounded-lg border border-border bg-muted/30 p-3">
                                <div>
                                    <p class="text-xs font-semibold text-foreground">{{ __('cookieBanner.analytics_title') }}</p>
                                    <p class="mt-0.5 text-xs text-muted-foreground">
                                        {{ __('cookieBanner.analytics_desc') }}
                                    </p>
                                </div>
                                <button
                                    type="button"
                                    role="switch"
                                    :aria-checked="analyticsChoice"
                                    class="mt-0.5 inline-flex h-5 w-9 shrink-0 cursor-pointer items-center rounded-full border-2 border-transparent transition-colors focus-visible:outline-none"
                                    :class="analyticsChoice ? 'bg-red-500' : 'bg-muted-foreground/30'"
                                    @click="analyticsChoice = !analyticsChoice"
                                >
                                    <span class="pointer-events-none block h-4 w-4 rounded-full bg-white shadow-sm transition-transform" :class="analyticsChoice ? 'translate-x-4' : 'translate-x-0'" />
                                </button>
                            </div>

                            <!-- Marketing -->
                            <div class="flex items-start justify-between gap-3 rounded-lg border border-border bg-muted/30 p-3">
                                <div>
                                    <p class="text-xs font-semibold text-foreground">{{ __('cookieBanner.marketing_title') }}</p>
                                    <p class="mt-0.5 text-xs text-muted-foreground">
                                        {{ __('cookieBanner.marketing_desc') }}
                                    </p>
                                </div>
                                <button
                                    type="button"
                                    role="switch"
                                    :aria-checked="marketingChoice"
                                    class="mt-0.5 inline-flex h-5 w-9 shrink-0 cursor-pointer items-center rounded-full border-2 border-transparent transition-colors focus-visible:outline-none"
                                    :class="marketingChoice ? 'bg-red-500' : 'bg-muted-foreground/30'"
                                    @click="marketingChoice = !marketingChoice"
                                >
                                    <span class="pointer-events-none block h-4 w-4 rounded-full bg-white shadow-sm transition-transform" :class="marketingChoice ? 'translate-x-4' : 'translate-x-0'" />
                                </button>
                            </div>

                        </div>

                        <div class="mt-3 flex justify-end">
                            <button
                                type="button"
                                class="rounded-md bg-foreground px-4 py-1.5 text-xs font-semibold text-background transition-colors hover:opacity-90"
                                @click="handleSaveCustom"
                            >
                                {{ __('cookieBanner.save') }}
                            </button>
                        </div>
                    </div>
                </Transition>

            </div>
        </div>
    </Transition>
</template>
