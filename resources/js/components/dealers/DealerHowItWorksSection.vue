<script setup lang="ts">
import { BarChart3, Rocket, ShieldCheck } from 'lucide-vue-next';
import type { Component } from 'vue';
import { Card } from '@/components/ui/card';
import { useTranslation } from '@/composables/useTranslation';

type HowItWorksCard = {
    titleKey: string;
    descriptionKey: string;
    icon: Component;
};

const { __ } = useTranslation();

const cards: HowItWorksCard[] = [
    {
        titleKey: 'dealerPage.how_it_works_card_1_title',
        descriptionKey: 'dealerPage.how_it_works_card_1_description',
        icon: ShieldCheck,
    },
    {
        titleKey: 'dealerPage.how_it_works_card_3_title',
        descriptionKey: 'dealerPage.how_it_works_card_3_description',
        icon: Rocket,
    },
    {
        titleKey: 'dealerPage.how_it_works_card_6_title',
        descriptionKey: 'dealerPage.how_it_works_card_6_description',
        icon: BarChart3,
    },
];
</script>

<template>
    <section class="relative overflow-hidden pt-12 pb-12 sm:pt-12 sm:pb-12">
        <div class="relative z-10 mx-auto max-w-7xl px-4 sm:px-6">
            <div class="mx-auto max-w-3xl text-center">
                <h2
                    class="mt-6 text-3xl font-extrabold tracking-tight text-balance text-foreground uppercase"
                >
                    {{ __('dealerPage.how_it_works_title_before') }}
                    <span class="text-primary">{{
                        __('dealerPage.how_it_works_title_highlight')
                    }}</span>
                    {{ __('dealerPage.how_it_works_title_after') }}
                </h2>

                <p
                    class="mx-auto mt-5 max-w-2xl leading-relaxed text-muted-foreground"
                >
                    {{ __('dealerPage.how_it_works_subtitle') }}
                </p>
            </div>

            <div class="mt-14 grid gap-4 md:grid-cols-3">
                <Card
                    v-for="card in cards"
                    :key="card.titleKey"
                    class="group gap-0 border-white/10 bg-card/70 p-5 py-5 shadow-sm backdrop-blur-sm transition-all duration-300 hover:-translate-y-1 hover:border-primary/40 hover:shadow-lg"
                >
                    <div class="flex items-start gap-4">
                        <div
                            class="inline-flex size-10 shrink-0 items-center justify-center rounded-lg border border-primary/20 bg-primary/15 text-primary ring-1 ring-primary/25 transition-all duration-300 group-hover:border-primary/70 group-hover:bg-primary/25 group-hover:ring-primary/50"
                        >
                            <component
                                :is="card.icon"
                                class="size-4.5 transition-transform duration-300 group-hover:-translate-y-0.5 group-hover:scale-110"
                            />
                        </div>

                        <div class="min-w-0">
                            <h3
                                class="text-base font-bold tracking-tight text-card-foreground"
                            >
                                {{ __(card.titleKey) }}
                            </h3>

                            <p
                                class="mt-2 text-sm leading-relaxed text-muted-foreground"
                            >
                                {{ __(card.descriptionKey) }}
                            </p>
                        </div>
                    </div>
                </Card>
            </div>
        </div>
    </section>
</template>
