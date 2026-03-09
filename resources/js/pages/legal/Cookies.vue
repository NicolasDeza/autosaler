<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { useTranslation } from '@/composables/useTranslation';
import AppLayout from '@/layouts/AppLayout.vue';

const { __ } = useTranslation();
</script>

<template>
    <Head :title="__('cookies.page_title')" />

    <AppLayout>
        <!-- Page Header -->
        <div class="relative overflow-hidden border-b border-border/50 30 px-6 py-16 text-center">
            <div class="absolute inset-x-0 top-0 h-px bg-linear-to-r from-transparent via-red-500 to-transparent" />
            <p class="mb-3 text-xs font-semibold uppercase tracking-widest text-red-500">
                {{ __('cookies.legal_info') }}
            </p>
            <h1 class="text-4xl font-bold tracking-tight text-foreground">
                {{ __('cookies.page_title') }}
            </h1>
            <p class="mt-3 text-sm text-muted-foreground">
                {{ __('cookies.last_updated') }}
            </p>
        </div>

        <!-- Content -->
        <div class="mx-auto max-w-3xl space-y-6 px-6 py-12">

            <!-- Section 1 -->
            <section class="rounded-xl border border-border bg-card p-6 shadow-sm">
                <div class="mb-4 flex items-center gap-3">
                    <span class="flex size-8 shrink-0 items-center justify-center rounded-full bg-red-500/10 text-sm font-bold text-red-500">1</span>
                    <h2 class="text-lg font-semibold text-foreground">{{ __('cookies.s1_title') }}</h2>
                </div>
                <div class="space-y-3 text-sm leading-relaxed text-muted-foreground">
                    <p>{{ __('cookies.s1_p1') }}</p>
                    <p>
                        {{ __('cookies.s1_p2_pre') }} <span class="font-medium text-foreground">{{ __('cookies.s1_gdpr') }}</span>
                        {{ __('cookies.s1_p2_mid') }}
                        <span class="font-medium text-foreground">{{ __('cookies.s1_law') }}</span>{{ __('cookies.s1_p2_post') }}
                    </p>
                </div>
            </section>

            <!-- Section 2 -->
            <section class="rounded-xl border border-border bg-card p-6 shadow-sm">
                <div class="mb-4 flex items-center gap-3">
                    <span class="flex size-8 shrink-0 items-center justify-center rounded-full bg-red-500/10 text-sm font-bold text-red-500">2</span>
                    <h2 class="text-lg font-semibold text-foreground">{{ __('cookies.s2_title') }}</h2>
                </div>
                <div class="space-y-5 text-sm">

                    <!-- Cookies essentiels -->
                    <div>
                        <div class="mb-2 flex items-center gap-2">
                            <span class="rounded-full bg-green-500/10 px-3 py-0.5 text-xs font-semibold text-green-600 dark:text-green-400">
                                {{ __('cookies.s2_essential_badge') }}
                            </span>
                            <h3 class="font-semibold text-foreground">{{ __('cookies.s2_essential_title') }}</h3>
                        </div>
                        <p class="mb-2 text-muted-foreground">{{ __('cookies.s2_essential_desc') }}</p>
                        <ul class="space-y-1.5 text-muted-foreground">
                            <li class="flex items-start gap-2">
                                <span class="mt-1.5 size-1.5 shrink-0 rounded-full bg-green-500" />
                                <span>{{ __('cookies.s2_essential_li1') }}</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="mt-1.5 size-1.5 shrink-0 rounded-full bg-green-500" />
                                <span>{{ __('cookies.s2_essential_li2') }}</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="mt-1.5 size-1.5 shrink-0 rounded-full bg-green-500" />
                                <span>{{ __('cookies.s2_essential_li3') }}</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="mt-1.5 size-1.5 shrink-0 rounded-full bg-green-500" />
                                <span>{{ __('cookies.s2_essential_li4') }}</span>
                            </li>
                        </ul>
                    </div>

                    <div class="border-t border-border" />

                    <!-- Cookies analytiques -->
                    <div>
                        <div class="mb-2 flex items-center gap-2">
                            <span class="rounded-full bg-blue-500/10 px-3 py-0.5 text-xs font-semibold text-blue-600 dark:text-blue-400">
                                {{ __('cookies.s2_analytics_badge') }}
                            </span>
                            <h3 class="font-semibold text-foreground">{{ __('cookies.s2_analytics_title') }}</h3>
                        </div>
                        <p class="mb-2 text-muted-foreground">{{ __('cookies.s2_analytics_desc') }}</p>
                        <ul class="space-y-1.5 text-muted-foreground">
                            <li class="flex items-start gap-2">
                                <span class="mt-1.5 size-1.5 shrink-0 rounded-full bg-blue-500" />
                                <span>{{ __('cookies.s2_analytics_li1') }}</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="mt-1.5 size-1.5 shrink-0 rounded-full bg-blue-500" />
                                <span>{{ __('cookies.s2_analytics_li2') }}</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="mt-1.5 size-1.5 shrink-0 rounded-full bg-blue-500" />
                                <span>{{ __('cookies.s2_analytics_li3') }}</span>
                            </li>
                        </ul>
                    </div>

                    <div class="border-t border-border" />

                    <!-- Cookies marketing -->
                    <div>
                        <div class="mb-2 flex items-center gap-2">
                            <span class="rounded-full bg-orange-500/10 px-3 py-0.5 text-xs font-semibold text-orange-600 dark:text-orange-400">
                                {{ __('cookies.s2_marketing_badge') }}
                            </span>
                            <h3 class="font-semibold text-foreground">{{ __('cookies.s2_marketing_title') }}</h3>
                        </div>
                        <p class="mb-2 text-muted-foreground">{{ __('cookies.s2_marketing_desc') }}</p>
                        <ul class="space-y-1.5 text-muted-foreground">
                            <li class="flex items-start gap-2">
                                <span class="mt-1.5 size-1.5 shrink-0 rounded-full bg-orange-500" />
                                <span>{{ __('cookies.s2_marketing_li1') }}</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="mt-1.5 size-1.5 shrink-0 rounded-full bg-orange-500" />
                                <span>{{ __('cookies.s2_marketing_li2') }}</span>
                            </li>
                        </ul>
                    </div>

                </div>
            </section>

            <!-- Section 3 -->
            <section class="rounded-xl border border-border bg-card p-6 shadow-sm">
                <div class="mb-4 flex items-center gap-3">
                    <span class="flex size-8 shrink-0 items-center justify-center rounded-full bg-red-500/10 text-sm font-bold text-red-500">3</span>
                    <h2 class="text-lg font-semibold text-foreground">{{ __('cookies.s3_title') }}</h2>
                </div>
                <div class="overflow-hidden rounded-lg border border-border text-sm">
                    <table class="w-full">
                        <thead class="bg-muted/50">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold text-foreground">{{ __('cookies.s3_th_type') }}</th>
                                <th class="px-4 py-3 text-left font-semibold text-foreground">{{ __('cookies.s3_th_duration') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-t border-border">
                                <td class="px-4 py-3 text-foreground/80">{{ __('cookies.s3_row1_type') }}</td>
                                <td class="px-4 py-3 text-muted-foreground">{{ __('cookies.s3_row1_duration') }}</td>
                            </tr>
                            <tr class="border-t border-border bg-muted/20">
                                <td class="px-4 py-3 text-foreground/80">{{ __('cookies.s3_row2_type') }}</td>
                                <td class="px-4 py-3 text-muted-foreground">{{ __('cookies.s3_row2_duration') }}</td>
                            </tr>
                            <tr class="border-t border-border">
                                <td class="px-4 py-3 text-foreground/80">{{ __('cookies.s3_row3_type') }}</td>
                                <td class="px-4 py-3 text-muted-foreground">{{ __('cookies.s3_row3_duration') }}</td>
                            </tr>
                            <tr class="border-t border-border bg-muted/20">
                                <td class="px-4 py-3 text-foreground/80">{{ __('cookies.s3_row4_type') }}</td>
                                <td class="px-4 py-3 text-muted-foreground">{{ __('cookies.s3_row4_duration') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Section 4 -->
            <section class="rounded-xl border border-border bg-card p-6 shadow-sm">
                <div class="mb-4 flex items-center gap-3">
                    <span class="flex size-8 shrink-0 items-center justify-center rounded-full bg-red-500/10 text-sm font-bold text-red-500">4</span>
                    <h2 class="text-lg font-semibold text-foreground">{{ __('cookies.s4_title') }}</h2>
                </div>
                <div class="space-y-3 text-sm leading-relaxed text-muted-foreground">
                    <p>{{ __('cookies.s4_p1') }}</p>
                    <p>{{ __('cookies.s4_p2') }}</p>
                </div>
            </section>

            <!-- Section 5 -->
            <section class="rounded-xl border border-border bg-card p-6 shadow-sm">
                <div class="mb-4 flex items-center gap-3">
                    <span class="flex size-8 shrink-0 items-center justify-center rounded-full bg-red-500/10 text-sm font-bold text-red-500">5</span>
                    <h2 class="text-lg font-semibold text-foreground">{{ __('cookies.s5_title') }}</h2>
                </div>
                <div class="space-y-4 text-sm">
                    <p class="leading-relaxed text-muted-foreground">{{ __('cookies.s5_intro') }}</p>
                    <div class="rounded-lg bg-muted/50 px-4 py-3">
                        <p class="mb-2 font-medium text-foreground">{{ __('cookies.s5_browser_title') }}</p>
                        <ul class="space-y-1.5 text-muted-foreground">
                            <li class="flex items-start gap-2">
                                <span class="mt-1.5 size-1.5 shrink-0 rounded-full bg-red-500" />
                                <span><span class="font-medium text-foreground">Chrome</span> : {{ __('cookies.s5_chrome') }}</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="mt-1.5 size-1.5 shrink-0 rounded-full bg-red-500" />
                                <span><span class="font-medium text-foreground">Firefox</span> : {{ __('cookies.s5_firefox') }}</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="mt-1.5 size-1.5 shrink-0 rounded-full bg-red-500" />
                                <span><span class="font-medium text-foreground">Safari</span> : {{ __('cookies.s5_safari') }}</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="mt-1.5 size-1.5 shrink-0 rounded-full bg-red-500" />
                                <span><span class="font-medium text-foreground">Edge</span> : {{ __('cookies.s5_edge') }}</span>
                            </li>
                        </ul>
                    </div>
                    <p class="leading-relaxed text-muted-foreground">{{ __('cookies.s5_warning') }}</p>
                </div>
            </section>

            <!-- Section 6 -->
            <section class="rounded-xl border border-border bg-card p-6 shadow-sm">
                <div class="mb-4 flex items-center gap-3">
                    <span class="flex size-8 shrink-0 items-center justify-center rounded-full bg-red-500/10 text-sm font-bold text-red-500">6</span>
                    <h2 class="text-lg font-semibold text-foreground">{{ __('cookies.s6_title') }}</h2>
                </div>
                <div class="space-y-3 text-sm leading-relaxed text-muted-foreground">
                    <p>
                        {{ __('cookies.s6_p1_pre') }}
                        <Link
                            href="/legal/privacy"
                            class="font-medium text-red-500 underline underline-offset-4 hover:text-red-400"
                        >
                            {{ __('cookies.s6_p1_link') }}
                        </Link>.
                    </p>
                    <p>
                        {{ __('cookies.s6_p2_pre') }}
                        <a
                            href="https://www.autoriteprotectiondonnees.be"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="font-medium text-red-500 underline underline-offset-4 hover:text-red-400"
                        >
                            {{ __('cookies.s6_p2_apd') }}
                        </a>
                        {{ __('cookies.s6_p2_post') }}
                    </p>
                </div>
            </section>

            <!-- Section 7 -->
            <section class="rounded-xl border border-border bg-card p-6 shadow-sm">
                <div class="mb-4 flex items-center gap-3">
                    <span class="flex size-8 shrink-0 items-center justify-center rounded-full bg-red-500/10 text-sm font-bold text-red-500">7</span>
                    <h2 class="text-lg font-semibold text-foreground">{{ __('cookies.s7_title') }}</h2>
                </div>
                <p class="text-sm leading-relaxed text-muted-foreground">
                    {{ __('cookies.s7_text') }}
                </p>
            </section>

        </div>
    </AppLayout>
</template>
