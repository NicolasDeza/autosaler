<script setup lang="ts">
import hero2 from '@assets/images/hero3.jpg';
import { Head } from '@inertiajs/vue3';
import DealerHowItWorksSection from '@/components/dealers/DealerHowItWorksSection.vue';
import PriceCard from '@/components/PriceCard.vue';
import { Button } from '@/components/ui/button';
import { useTranslation } from '@/composables/useTranslation';
import AppLayout from '@/layouts/AppLayout.vue';

const { __ } = useTranslation();
</script>

<template>
    <Head :title="__('dealerPage.page_title')" />

    <AppLayout>
        <template #full-width>
            <section class="dark relative overflow-hidden bg-[#0a0a0a] py-20 md:py-24 lg:py-40">

                <div class="absolute inset-0 z-0">
                    <img
                        :src="hero2"
                        :alt="__('dealerPage.heading')"
                        class="h-full w-full object-cover opacity-40"
                    />


                </div>

                <div class="relative z-20 mx-auto max-w-5xl px-4 sm:px-6">
                    <div class="flex flex-col items-center text-center">

                        <div class="inline-flex items-center rounded-full border border-white/10 bg-white/5 px-3 py-1 mb-8 backdrop-blur-sm">
                            <span class="text-[10px] font-bold uppercase tracking-[0.2em] text-primary">
                                {{ __('dealerPage.hero_badge') }}
                            </span>
                        </div>

                        <h1 class="text-balance text-4xl font-extrabold tracking-tight text-white md:text-6xl lg:text-7xl">
                            {{ __('dealerPage.heading') }}
                        </h1>

                        <p class="mt-6 max-w-2xl text-lg leading-relaxed text-white/80 md:text-lg">
                            {{ __('dealerPage.hero_subtitle') }}
                        </p>

                        <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
                            <Button
                                as="a"
                                href="#dealer-signup"
                                size="lg"
                                class="group relative h-12 w-full overflow-hidden px-10 text-base font-bold text-white! transition-all duration-300 hover:-translate-y-0.5 hover:text-white! active:scale-95 sm:w-auto"
                            >
                                <span class="absolute inset-0 -translate-x-full -skew-x-12 bg-white/15 transition-transform duration-500 group-hover:translate-x-full" />
                                <span class="relative z-10 transition-all duration-300 group-hover:tracking-widest">
                                    {{ __('dealerPage.cta_signup') }}
                                </span>
                            </Button>


                        </div>

                        <div class="mt-16 flex flex-wrap justify-center gap-8 opacity-40 grayscale hover:grayscale-0 transition-all duration-500">
                           <div class="text-xs text-white font-semibold tracking-widest uppercase">{{ __('dealerPage.hero_stat_performance') }}</div>
                           <div class="text-xs text-white font-semibold tracking-widest uppercase">{{ __('dealerPage.hero_stat_visibility') }}</div>
                           <div class="text-xs text-white font-semibold tracking-widest uppercase">{{ __('dealerPage.hero_stat_simplicity') }}</div>
                        </div>

                    </div>
                </div>

                <div class="absolute bottom-0 left-0 h-px w-full bg-linear-to-r from-transparent via-primary/30 to-transparent"></div>
            </section>
            <DealerHowItWorksSection />
        </template>


        <PriceCard/>
    </AppLayout>
</template>
