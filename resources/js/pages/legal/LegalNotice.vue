<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppContent from '@/components/AppContent.vue';
import { useTranslation } from '@/composables/useTranslation';
import { privacy } from '@/routes/legal';

const { __ } = useTranslation();
</script>

<template>
    <Head :title="__('legalNotice.page_title')" />

    <AppContent>
        <!-- Page Header -->
        <div class="relative overflow-hidden border-b border-border/50 30 px-6 py-16 text-center">
            <div class="absolute inset-x-0 top-0 h-px bg-linear-to-r from-transparent via-red-500 to-transparent" />
            <p class="mb-3 text-xs font-semibold uppercase tracking-widest text-red-500">
                {{ __('legalNotice.legal_info') }}
            </p>
            <h1 class="text-4xl font-bold tracking-tight text-foreground">
                {{ __('legalNotice.page_title') }}
            </h1>
            <p class="mt-3 text-sm text-muted-foreground">
                {{ __('legalNotice.last_updated') }}
            </p>
        </div>

        <!-- Content -->
        <div class="mx-auto max-w-3xl space-y-6 px-6 py-12">

            <!-- Section 1 -->
            <section class="rounded-xl border border-border bg-card p-6 shadow-sm">
                <div class="mb-4 flex items-center gap-3">
                    <span class="flex size-8 shrink-0 items-center justify-center rounded-full bg-red-500/10 text-sm font-bold text-red-500">1</span>
                    <h2 class="text-lg font-semibold text-foreground">{{ __('legalNotice.s1_title') }}</h2>
                </div>
                <dl class="space-y-3 text-sm">
                    <div class="flex flex-col gap-0.5 sm:flex-row sm:gap-2">
                        <dt class="shrink-0 font-medium text-foreground/80">{{ __('legalNotice.s1_name_label') }}</dt>
                        <dd class="text-muted-foreground">{{ __('legalNotice.s1_name_value') }}</dd>
                    </div>
                    <div class="flex flex-col gap-0.5 sm:flex-row sm:gap-2">
                        <dt class="shrink-0 font-medium text-foreground/80">{{ __('legalNotice.s1_legal_form_label') }}</dt>
                        <dd class="italic text-muted-foreground">{{ __('legalNotice.s1_legal_form_value') }}</dd>
                    </div>
                    <div class="flex flex-col gap-0.5 sm:flex-row sm:gap-2">
                        <dt class="shrink-0 font-medium text-foreground/80">{{ __('legalNotice.s1_address_label') }}</dt>
                        <dd class="italic text-muted-foreground">{{ __('legalNotice.s1_address_value') }}</dd>
                    </div>
                    <div class="flex flex-col gap-0.5 sm:flex-row sm:gap-2">
                        <dt class="shrink-0 font-medium text-foreground/80">{{ __('legalNotice.s1_email_label') }}</dt>
                        <dd class="italic text-muted-foreground">{{ __('legalNotice.s1_email_value') }}</dd>
                    </div>
                    <div class="flex flex-col gap-0.5 sm:flex-row sm:gap-2">
                        <dt class="shrink-0 font-medium text-foreground/80">{{ __('legalNotice.s1_bce_label') }}</dt>
                        <dd class="italic text-muted-foreground">{{ __('legalNotice.s1_bce_value') }}</dd>
                    </div>
                    <div class="flex flex-col gap-0.5 sm:flex-row sm:gap-2">
                        <dt class="shrink-0 font-medium text-foreground/80">{{ __('legalNotice.s1_vat_label') }}</dt>
                        <dd class="italic text-muted-foreground">{{ __('legalNotice.s1_vat_value') }}</dd>
                    </div>
                </dl>
            </section>

            <!-- Section 2 -->
            <section class="rounded-xl border border-border bg-card p-6 shadow-sm">
                <div class="mb-4 flex items-center gap-3">
                    <span class="flex size-8 shrink-0 items-center justify-center rounded-full bg-red-500/10 text-sm font-bold text-red-500">2</span>
                    <h2 class="text-lg font-semibold text-foreground">{{ __('legalNotice.s2_title') }}</h2>
                </div>
                <dl class="space-y-3 text-sm">
                    <div class="flex flex-col gap-0.5 sm:flex-row sm:gap-2">
                        <dt class="shrink-0 font-medium text-foreground/80">{{ __('legalNotice.s2_host_label') }}</dt>
                        <dd class="italic text-muted-foreground">{{ __('legalNotice.s2_host_value') }}</dd>
                    </div>
                    <div class="flex flex-col gap-0.5 sm:flex-row sm:gap-2">
                        <dt class="shrink-0 font-medium text-foreground/80">{{ __('legalNotice.s2_address_label') }}</dt>
                        <dd class="italic text-muted-foreground">{{ __('legalNotice.s2_address_value') }}</dd>
                    </div>
                    <div class="flex flex-col gap-0.5 sm:flex-row sm:gap-2">
                        <dt class="shrink-0 font-medium text-foreground/80">{{ __('legalNotice.s2_website_label') }}</dt>
                        <dd class="italic text-muted-foreground">{{ __('legalNotice.s2_website_value') }}</dd>
                    </div>
                </dl>
            </section>

            <!-- Section 3 -->
            <section class="rounded-xl border border-border bg-card p-6 shadow-sm">
                <div class="mb-4 flex items-center gap-3">
                    <span class="flex size-8 shrink-0 items-center justify-center rounded-full bg-red-500/10 text-sm font-bold text-red-500">3</span>
                    <h2 class="text-lg font-semibold text-foreground">{{ __('legalNotice.s3_title') }}</h2>
                </div>
                <dl class="space-y-3 text-sm">
                    <div class="flex flex-col gap-0.5 sm:flex-row sm:gap-2">
                        <dt class="shrink-0 font-medium text-foreground/80">{{ __('legalNotice.s3_name_label') }}</dt>
                        <dd class="italic text-muted-foreground">{{ __('legalNotice.s3_name_value') }}</dd>
                    </div>
                    <div class="flex flex-col gap-0.5 sm:flex-row sm:gap-2">
                        <dt class="shrink-0 font-medium text-foreground/80">{{ __('legalNotice.s3_contact_label') }}</dt>
                        <dd class="italic text-muted-foreground">{{ __('legalNotice.s3_contact_value') }}</dd>
                    </div>
                </dl>
            </section>

            <!-- Section 4 -->
            <section class="rounded-xl border border-border bg-card p-6 shadow-sm">
                <div class="mb-4 flex items-center gap-3">
                    <span class="flex size-8 shrink-0 items-center justify-center rounded-full bg-red-500/10 text-sm font-bold text-red-500">4</span>
                    <h2 class="text-lg font-semibold text-foreground">{{ __('legalNotice.s4_title') }}</h2>
                </div>
                <p class="text-sm leading-relaxed text-muted-foreground">
                    {{ __('legalNotice.s4_text', { brand: 'Autosaler' }) }}
                </p>
            </section>

            <!-- Section 5 -->
            <section class="rounded-xl border border-border bg-card p-6 shadow-sm">
                <div class="mb-4 flex items-center gap-3">
                    <span class="flex size-8 shrink-0 items-center justify-center rounded-full bg-red-500/10 text-sm font-bold text-red-500">5</span>
                    <h2 class="text-lg font-semibold text-foreground">{{ __('legalNotice.s5_title') }}</h2>
                </div>
                <div class="space-y-3 text-sm leading-relaxed text-muted-foreground">
                    <p>{{ __('legalNotice.s5_p1') }}</p>
                    <p>{{ __('legalNotice.s5_p2') }}</p>
                </div>
            </section>

            <!-- Section 6 -->
            <section class="rounded-xl border border-border bg-card p-6 shadow-sm">
                <div class="mb-4 flex items-center gap-3">
                    <span class="flex size-8 shrink-0 items-center justify-center rounded-full bg-red-500/10 text-sm font-bold text-red-500">6</span>
                    <h2 class="text-lg font-semibold text-foreground">{{ __('legalNotice.s6_title') }}</h2>
                </div>
                <p class="text-sm leading-relaxed text-muted-foreground">
                    {{ __('legalNotice.s6_text') }}
                </p>
            </section>

            <!-- Section 7 -->
            <section class="rounded-xl border border-border bg-card p-6 shadow-sm">
                <div class="mb-4 flex items-center gap-3">
                    <span class="flex size-8 shrink-0 items-center justify-center rounded-full bg-red-500/10 text-sm font-bold text-red-500">7</span>
                    <h2 class="text-lg font-semibold text-foreground">{{ __('legalNotice.s7_title') }}</h2>
                </div>
                <p class="text-sm leading-relaxed text-muted-foreground">
                    {{ __('legalNotice.s7_text_pre') }}
                    <Link
                        :href="privacy().url"
                        class="font-medium text-red-500 underline underline-offset-4 hover:text-red-400"
                    >
                        {{ __('legalNotice.s7_link_text') }}
                    </Link>.
                </p>
            </section>

            <!-- Section 8 -->
            <section class="rounded-xl border border-border bg-card p-6 shadow-sm">
                <div class="mb-4 flex items-center gap-3">
                    <span class="flex size-8 shrink-0 items-center justify-center rounded-full bg-red-500/10 text-sm font-bold text-red-500">8</span>
                    <h2 class="text-lg font-semibold text-foreground">{{ __('legalNotice.s8_title') }}</h2>
                </div>
                <div class="space-y-3 text-sm leading-relaxed text-muted-foreground">
                    <p>
                        {{ __('legalNotice.s8_p1_pre') }} <span class="font-medium text-foreground">{{ __('legalNotice.s8_p1_label') }}</span>.
                    </p>
                    <p>
                        {{ __('legalNotice.s8_p2_pre') }}
                        <span class="italic">{{ __('legalNotice.s8_p2_city') }}</span>
                        {{ __('legalNotice.s8_p2_post') }}
                    </p>
                </div>
            </section>

        </div>
    </AppContent>
</template>
