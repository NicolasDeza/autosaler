<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { useTranslation } from '@/composables/useTranslation';
import { cookies } from '@/routes/legal';
import AppLayout from '@/layouts/AppLayout.vue';

const { __ } = useTranslation();
</script>

<template>
    <Head :title="__('privacyPolicy.page_title')" />

    <AppLayout>
        <!-- Page Header -->
        <div class="relative overflow-hidden border-b border-border/50 30 px-6 py-16 text-center">
            <div class="absolute inset-x-0 top-0 h-px bg-linear-to-r from-transparent via-red-500 to-transparent" />
            <p class="mb-3 text-xs font-semibold uppercase tracking-widest text-red-500">
                {{ __('privacyPolicy.legal_info') }}
            </p>
            <h1 class="text-4xl font-bold tracking-tight text-foreground">
                {{ __('privacyPolicy.page_title') }}
            </h1>
            <p class="mt-3 text-sm text-muted-foreground">
                {{ __('privacyPolicy.last_updated') }}
            </p>
        </div>

        <!-- Content -->
        <div class="mx-auto max-w-3xl space-y-6 px-6 py-12">

            <!-- Section 1 -->
            <section class="rounded-xl border border-border bg-card p-6 shadow-sm">
                <div class="mb-4 flex items-center gap-3">
                    <span class="flex size-8 shrink-0 items-center justify-center rounded-full bg-red-500/10 text-sm font-bold text-red-500">1</span>
                    <h2 class="text-lg font-semibold text-foreground">{{ __('privacyPolicy.s1_title') }}</h2>
                </div>
                <div class="space-y-3 text-sm leading-relaxed text-muted-foreground">
                    <p>
                        {{ __('privacyPolicy.s1_p1_pre') }} <span class="font-medium text-foreground">Autosaler</span>
                        {{ __('privacyPolicy.s1_p1_post') }}
                    </p>
                    <p>
                        {{ __('privacyPolicy.s1_p2_pre') }} <span class="font-medium text-foreground">{{ __('privacyPolicy.s1_gdpr') }}</span>
                        {{ __('privacyPolicy.s1_p2_post') }}
                    </p>
                </div>
            </section>

            <!-- Section 2 -->
            <section class="rounded-xl border border-border bg-card p-6 shadow-sm">
                <div class="mb-4 flex items-center gap-3">
                    <span class="flex size-8 shrink-0 items-center justify-center rounded-full bg-red-500/10 text-sm font-bold text-red-500">2</span>
                    <h2 class="text-lg font-semibold text-foreground">{{ __('privacyPolicy.s2_title') }}</h2>
                </div>
                <p class="mb-3 text-sm text-muted-foreground">{{ __('privacyPolicy.s2_intro') }}</p>
                <ul class="space-y-2 text-sm text-muted-foreground">
                    <li class="flex items-start gap-2">
                        <span class="mt-1.5 size-1.5 shrink-0 rounded-full bg-red-500" />
                        <span><span class="font-medium text-foreground">{{ __('privacyPolicy.s2_li1_label') }}</span> {{ __('privacyPolicy.s2_li1_desc') }}</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="mt-1.5 size-1.5 shrink-0 rounded-full bg-red-500" />
                        <span><span class="font-medium text-foreground">{{ __('privacyPolicy.s2_li2_label') }}</span> {{ __('privacyPolicy.s2_li2_desc') }}</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="mt-1.5 size-1.5 shrink-0 rounded-full bg-red-500" />
                        <span><span class="font-medium text-foreground">{{ __('privacyPolicy.s2_li3_label') }}</span> {{ __('privacyPolicy.s2_li3_desc') }}</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="mt-1.5 size-1.5 shrink-0 rounded-full bg-red-500" />
                        <span>{{ __('privacyPolicy.s2_li4_pre') }} <span class="font-medium text-foreground">{{ __('privacyPolicy.s2_li4_label') }}</span></span>
                    </li>
                </ul>
            </section>

            <!-- Section 3 -->
            <section class="rounded-xl border border-border bg-card p-6 shadow-sm">
                <div class="mb-4 flex items-center gap-3">
                    <span class="flex size-8 shrink-0 items-center justify-center rounded-full bg-red-500/10 text-sm font-bold text-red-500">3</span>
                    <h2 class="text-lg font-semibold text-foreground">{{ __('privacyPolicy.s3_title') }}</h2>
                </div>
                <p class="mb-3 text-sm text-muted-foreground">{{ __('privacyPolicy.s3_intro') }}</p>
                <ul class="space-y-2 text-sm text-muted-foreground">
                    <li class="flex items-start gap-2">
                        <span class="mt-1.5 size-1.5 shrink-0 rounded-full bg-red-500" />
                        <span>{{ __('privacyPolicy.s3_li1_pre') }} <span class="font-medium text-foreground">{{ __('privacyPolicy.s3_li1_label') }}</span></span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="mt-1.5 size-1.5 shrink-0 rounded-full bg-red-500" />
                        <span>{{ __('privacyPolicy.s3_li2_pre') }} <span class="font-medium text-foreground">{{ __('privacyPolicy.s3_li2_label') }}</span></span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="mt-1.5 size-1.5 shrink-0 rounded-full bg-red-500" />
                        <span>{{ __('privacyPolicy.s3_li3_pre') }} <span class="font-medium text-foreground">{{ __('privacyPolicy.s3_li3_label') }}</span></span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="mt-1.5 size-1.5 shrink-0 rounded-full bg-red-500" />
                        <span>{{ __('privacyPolicy.s3_li4_pre') }}<span class="font-medium text-foreground">{{ __('privacyPolicy.s3_li4_label') }}</span></span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="mt-1.5 size-1.5 shrink-0 rounded-full bg-red-500" />
                        <span>{{ __('privacyPolicy.s3_li5_pre') }} <span class="font-medium text-foreground">{{ __('privacyPolicy.s3_li5_label') }}</span></span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="mt-1.5 size-1.5 shrink-0 rounded-full bg-red-500" />
                        <span>{{ __('privacyPolicy.s3_li6_pre') }} <span class="font-medium text-foreground">{{ __('privacyPolicy.s3_li6_label') }}</span></span>
                    </li>
                </ul>
            </section>

            <!-- Section 4 -->
            <section class="rounded-xl border border-border bg-card p-6 shadow-sm">
                <div class="mb-4 flex items-center gap-3">
                    <span class="flex size-8 shrink-0 items-center justify-center rounded-full bg-red-500/10 text-sm font-bold text-red-500">4</span>
                    <h2 class="text-lg font-semibold text-foreground">{{ __('privacyPolicy.s4_title') }}</h2>
                </div>
                <ul class="space-y-2 text-sm text-muted-foreground">
                    <li class="flex items-start gap-2">
                        <span class="mt-1.5 size-1.5 shrink-0 rounded-full bg-red-500" />
                        <span><span class="font-medium text-foreground">{{ __('privacyPolicy.s4_li1_label') }}</span>{{ __('privacyPolicy.s4_li1_desc') }}</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="mt-1.5 size-1.5 shrink-0 rounded-full bg-red-500" />
                        <span><span class="font-medium text-foreground">{{ __('privacyPolicy.s4_li2_label') }}</span>{{ __('privacyPolicy.s4_li2_desc') }}</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="mt-1.5 size-1.5 shrink-0 rounded-full bg-red-500" />
                        <span><span class="font-medium text-foreground">{{ __('privacyPolicy.s4_li3_label') }}</span>{{ __('privacyPolicy.s4_li3_desc') }}</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="mt-1.5 size-1.5 shrink-0 rounded-full bg-red-500" />
                        <span><span class="font-medium text-foreground">{{ __('privacyPolicy.s4_li4_label') }}</span>{{ __('privacyPolicy.s4_li4_desc') }}</span>
                    </li>
                </ul>
            </section>

            <!-- Section 5 -->
            <section class="rounded-xl border border-border bg-card p-6 shadow-sm">
                <div class="mb-4 flex items-center gap-3">
                    <span class="flex size-8 shrink-0 items-center justify-center rounded-full bg-red-500/10 text-sm font-bold text-red-500">5</span>
                    <h2 class="text-lg font-semibold text-foreground">{{ __('privacyPolicy.s5_title') }}</h2>
                </div>
                <p class="mb-3 text-sm text-muted-foreground">
                    {{ __('privacyPolicy.s5_intro') }}
                </p>
                <ul class="mb-3 space-y-2 text-sm text-muted-foreground">
                    <li class="flex items-start gap-2">
                        <span class="mt-1.5 size-1.5 shrink-0 rounded-full bg-red-500" />
                        <span><span class="font-medium text-foreground">{{ __('privacyPolicy.s5_li1_label') }}</span>{{ __('privacyPolicy.s5_li1_desc') }}</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="mt-1.5 size-1.5 shrink-0 rounded-full bg-red-500" />
                        <span><span class="font-medium text-foreground">{{ __('privacyPolicy.s5_li2_label') }}</span>{{ __('privacyPolicy.s5_li2_desc') }}</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="mt-1.5 size-1.5 shrink-0 rounded-full bg-red-500" />
                        <span><span class="font-medium text-foreground">{{ __('privacyPolicy.s5_li3_label') }}</span>{{ __('privacyPolicy.s5_li3_desc') }}</span>
                    </li>
                </ul>
                <p class="text-sm text-muted-foreground">
                    {{ __('privacyPolicy.s5_footer') }}
                </p>
            </section>

            <!-- Section 6 -->
            <section class="rounded-xl border border-border bg-card p-6 shadow-sm">
                <div class="mb-4 flex items-center gap-3">
                    <span class="flex size-8 shrink-0 items-center justify-center rounded-full bg-red-500/10 text-sm font-bold text-red-500">6</span>
                    <h2 class="text-lg font-semibold text-foreground">{{ __('privacyPolicy.s6_title') }}</h2>
                </div>
                <p class="mb-3 text-sm text-muted-foreground">{{ __('privacyPolicy.s6_intro') }}</p>
                <ul class="mb-3 space-y-2 text-sm text-muted-foreground">
                    <li class="flex items-start gap-2">
                        <span class="mt-1.5 size-1.5 shrink-0 rounded-full bg-red-500" />
                        <span>{{ __('privacyPolicy.s6_li1_pre') }} <span class="font-medium text-foreground">{{ __('privacyPolicy.s6_li1_label') }}</span> {{ __('privacyPolicy.s6_li1_desc') }}</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="mt-1.5 size-1.5 shrink-0 rounded-full bg-red-500" />
                        <span>{{ __('privacyPolicy.s6_li2_pre') }} <span class="font-medium text-foreground">{{ __('privacyPolicy.s6_li2_label') }}</span> {{ __('privacyPolicy.s6_li2_desc') }}</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="mt-1.5 size-1.5 shrink-0 rounded-full bg-red-500" />
                        <span>{{ __('privacyPolicy.s6_li3_pre') }} <span class="font-medium text-foreground">{{ __('privacyPolicy.s6_li3_label') }}</span> {{ __('privacyPolicy.s6_li3_desc') }}</span>
                    </li>
                </ul>
                <p class="rounded-lg bg-red-500/5 px-4 py-3 text-sm font-medium text-foreground">
                    {{ __('privacyPolicy.s6_note') }}
                </p>
            </section>

            <!-- Section 7 -->
            <section class="rounded-xl border border-border bg-card p-6 shadow-sm">
                <div class="mb-4 flex items-center gap-3">
                    <span class="flex size-8 shrink-0 items-center justify-center rounded-full bg-red-500/10 text-sm font-bold text-red-500">7</span>
                    <h2 class="text-lg font-semibold text-foreground">{{ __('privacyPolicy.s7_title') }}</h2>
                </div>
                <div class="space-y-3 text-sm leading-relaxed text-muted-foreground">
                    <p>{{ __('privacyPolicy.s7_p1') }}</p>
                    <p>{{ __('privacyPolicy.s7_p2') }}</p>
                </div>
            </section>

            <!-- Section 8 -->
            <section class="rounded-xl border border-border bg-card p-6 shadow-sm">
                <div class="mb-4 flex items-center gap-3">
                    <span class="flex size-8 shrink-0 items-center justify-center rounded-full bg-red-500/10 text-sm font-bold text-red-500">8</span>
                    <h2 class="text-lg font-semibold text-foreground">{{ __('privacyPolicy.s8_title') }}</h2>
                </div>
                <p class="mb-3 text-sm text-muted-foreground">{{ __('privacyPolicy.s8_intro') }}</p>
                <ul class="mb-4 space-y-2 text-sm text-muted-foreground">
                    <li class="flex items-start gap-2">
                        <span class="mt-1.5 size-1.5 shrink-0 rounded-full bg-red-500" />
                        <span>{{ __('privacyPolicy.s8_li1') }}</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="mt-1.5 size-1.5 shrink-0 rounded-full bg-red-500" />
                        <span>{{ __('privacyPolicy.s8_li2') }}</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="mt-1.5 size-1.5 shrink-0 rounded-full bg-red-500" />
                        <span>{{ __('privacyPolicy.s8_li3') }}</span>
                    </li>
                </ul>
                <p class="text-sm text-muted-foreground">
                    {{ __('privacyPolicy.s8_link_intro') }}
                    <Link
                        :href="cookies().url"
                        class="font-medium text-red-500 underline underline-offset-4 hover:text-red-400"
                    >
                        {{ __('privacyPolicy.s8_link_text') }}
                    </Link>.
                </p>
            </section>

            <!-- Section 9 -->
            <section class="rounded-xl border border-border bg-card p-6 shadow-sm">
                <div class="mb-4 flex items-center gap-3">
                    <span class="flex size-8 shrink-0 items-center justify-center rounded-full bg-red-500/10 text-sm font-bold text-red-500">9</span>
                    <h2 class="text-lg font-semibold text-foreground">{{ __('privacyPolicy.s9_title') }}</h2>
                </div>
                <p class="mb-3 text-sm text-muted-foreground">{{ __('privacyPolicy.s9_intro') }}</p>
                <ul class="mb-4 space-y-2 text-sm text-muted-foreground">
                    <li class="flex items-start gap-2">
                        <span class="mt-1.5 size-1.5 shrink-0 rounded-full bg-red-500" />
                        <span>{{ __('privacyPolicy.s9_li1_pre') }} <span class="font-medium text-foreground">{{ __('privacyPolicy.s9_li1_label') }}</span> {{ __('privacyPolicy.s9_li1_post') }}</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="mt-1.5 size-1.5 shrink-0 rounded-full bg-red-500" />
                        <span>{{ __('privacyPolicy.s9_li2_pre') }} <span class="font-medium text-foreground">{{ __('privacyPolicy.s9_li2_label') }}</span></span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="mt-1.5 size-1.5 shrink-0 rounded-full bg-red-500" />
                        <span>{{ __('privacyPolicy.s9_li3_pre') }} <span class="font-medium text-foreground">{{ __('privacyPolicy.s9_li3_label') }}</span> {{ __('privacyPolicy.s9_li3_post') }}</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="mt-1.5 size-1.5 shrink-0 rounded-full bg-red-500" />
                        <span>{{ __('privacyPolicy.s9_li4_pre') }} <span class="font-medium text-foreground">{{ __('privacyPolicy.s9_li4_label') }}</span> {{ __('privacyPolicy.s9_li4_post') }}</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="mt-1.5 size-1.5 shrink-0 rounded-full bg-red-500" />
                        <span>{{ __('privacyPolicy.s9_li5_pre') }} <span class="font-medium text-foreground">{{ __('privacyPolicy.s9_li5_label') }}</span> {{ __('privacyPolicy.s9_li5_post') }}</span>
                    </li>
                </ul>
                <div class="rounded-lg bg-muted/50 px-4 py-3 text-sm">
                    <p class="mb-1 font-medium text-foreground">{{ __('privacyPolicy.s9_contact_title') }}</p>
                    <p class="italic text-muted-foreground"> {{ __('privacyPolicy.s9_contact_email') }}</p>
                    <p class="italic text-muted-foreground"> {{ __('privacyPolicy.s9_contact_address') }}</p>
                </div>
            </section>

            <!-- Section 10 -->
            <section class="rounded-xl border border-border bg-card p-6 shadow-sm">
                <div class="mb-4 flex items-center gap-3">
                    <span class="flex size-8 shrink-0 items-center justify-center rounded-full bg-red-500/10 text-sm font-bold text-red-500">10</span>
                    <h2 class="text-lg font-semibold text-foreground">{{ __('privacyPolicy.s10_title') }}</h2>
                </div>
                <div class="space-y-3 text-sm leading-relaxed text-muted-foreground">
                    <p>{{ __('privacyPolicy.s10_p1') }}</p>
                    <p>
                        {{ __('privacyPolicy.s10_p2_pre') }} <span class="font-medium text-foreground">Autosaler</span>
                        {{ __('privacyPolicy.s10_p2_post') }}
                    </p>
                </div>
            </section>

            <!-- Section 11 -->
            <section class="rounded-xl border border-border bg-card p-6 shadow-sm">
                <div class="mb-4 flex items-center gap-3">
                    <span class="flex size-8 shrink-0 items-center justify-center rounded-full bg-red-500/10 text-sm font-bold text-red-500">11</span>
                    <h2 class="text-lg font-semibold text-foreground">{{ __('privacyPolicy.s11_title') }}</h2>
                </div>
                <div class="space-y-3 text-sm leading-relaxed text-muted-foreground">
                    <p>{{ __('privacyPolicy.s11_p1') }}</p>
                    <p>{{ __('privacyPolicy.s11_p2') }}</p>
                </div>
            </section>

            <!-- Section 12 -->
            <section class="rounded-xl border border-border bg-card p-6 shadow-sm">
                <div class="mb-4 flex items-center gap-3">
                    <span class="flex size-8 shrink-0 items-center justify-center rounded-full bg-red-500/10 text-sm font-bold text-red-500">12</span>
                    <h2 class="text-lg font-semibold text-foreground">{{ __('privacyPolicy.s12_title') }}</h2>
                </div>
                <p class="mb-3 text-sm text-muted-foreground">{{ __('privacyPolicy.s12_intro') }}</p>
                <div class="rounded-lg bg-muted/50 px-4 py-3 text-sm">
                    <p class="italic text-muted-foreground">{{ __('privacyPolicy.s12_email') }}</p>
                    <p class="italic text-muted-foreground"> {{ __('privacyPolicy.s12_address') }}</p>
                </div>
            </section>

        </div>
    </AppLayout>
</template>
