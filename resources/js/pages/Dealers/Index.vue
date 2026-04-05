<script setup lang="ts">
import hero2 from '@assets/images/hero3.jpg';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import AppContent from '@/components/AppContent.vue';
import DealerHowItWorksSection from '@/components/dealers/DealerHowItWorksSection.vue';
import DealerRegistrationModal from '@/components/dealers/DealerRegistrationModal.vue';
import PriceCard from '@/components/PriceCard.vue';
import { Button } from '@/components/ui/button';
import { useTranslation } from '@/composables/useTranslation';
import type { DealerSubscriptionPlan } from '@/types/dealers';

const { __ } = useTranslation();
const props = defineProps<{
    plans: DealerSubscriptionPlan[];
}>();
const showDealerRegistrationModal = ref(false);
const preselectedPlan = ref<string | number | null>(null);
const seoTitle = "Professionnels de l'automobile et abonnements";
const seoDescription =
    "Rejoignez AutoSaler en tant que professionnel de l'automobile. Découvrez les abonnements, publiez vos annonces et gagnez en visibilité en Belgique.";
const siteUrl = (
    import.meta.env.VITE_SITE_URL ||
    (typeof window !== 'undefined'
        ? window.location.origin
        : 'https://dev.autosaler.be')
).replace(/\/$/, '');
const canonicalUrl = `${siteUrl}/dealers`;

const openDealerRegistrationModal = (
    plan: number | string | null = null,
): void => {
    preselectedPlan.value = plan;
    showDealerRegistrationModal.value = true;
};
</script>

<template>
    <Head :title="seoTitle">
        <link head-key="canonical" rel="canonical" :href="canonicalUrl" />
        <meta head-key="og:url" property="og:url" :content="canonicalUrl" />
        <meta
            head-key="description"
            name="description"
            :content="seoDescription"
        />
        <meta head-key="og:title" property="og:title" :content="seoTitle" />
        <meta
            head-key="og:description"
            property="og:description"
            :content="seoDescription"
        />
        <meta
            head-key="twitter:title"
            name="twitter:title"
            :content="seoTitle"
        />
        <meta
            head-key="twitter:description"
            name="twitter:description"
            :content="seoDescription"
        />
    </Head>

    <section
        class="dark relative overflow-hidden bg-[#0a0a0a] py-20 md:py-24 lg:py-48"
    >
        <div class="absolute inset-0 z-0">
            <img
                :src="hero2"
                :alt="__('dealerPage.heading')"
                class="h-full w-full object-cover opacity-40"
            />
        </div>

        <div class="relative z-20 mx-auto max-w-5xl px-4 sm:px-6">
            <div class="flex flex-col items-center text-center">
                <h1
                    class="text-4xl font-extrabold tracking-tight text-balance text-white md:text-6xl lg:text-7xl"
                >
                    {{ __('dealerPage.heading') }}
                </h1>

                <p
                    class="mt-6 max-w-2xl text-lg leading-relaxed text-white/80 md:text-lg"
                >
                    {{ __('dealerPage.hero_subtitle') }}
                </p>

                <div
                    class="mt-10 flex flex-col items-center justify-center gap-4 sm:flex-row"
                >
                    <Button
                        @click="openDealerRegistrationModal(null)"
                        size="lg"
                        class="group relative h-12 w-full cursor-pointer overflow-hidden px-10 text-base font-bold text-white! transition-all duration-300 hover:-translate-y-0.5 hover:text-white! active:scale-95 sm:w-auto"
                    >
                        <span
                            class="absolute inset-0 -translate-x-full -skew-x-12 bg-white/15 transition-transform duration-500 group-hover:translate-x-full"
                        />
                        <span
                            class="relative z-10 transition-all duration-300 group-hover:tracking-widest"
                        >
                            {{ __('dealerPage.cta_signup') }}
                        </span>
                    </Button>
                </div>

                <div
                    class="mt-16 flex flex-wrap justify-center gap-8 opacity-40 grayscale transition-all duration-500 hover:grayscale-0"
                >
                    <div
                        class="text-xs font-semibold tracking-widest text-white uppercase"
                    >
                        {{ __('dealerPage.hero_stat_performance') }}
                    </div>
                    <div
                        class="text-xs font-semibold tracking-widest text-white uppercase"
                    >
                        {{ __('dealerPage.hero_stat_visibility') }}
                    </div>
                    <div
                        class="text-xs font-semibold tracking-widest text-white uppercase"
                    >
                        {{ __('dealerPage.hero_stat_simplicity') }}
                    </div>
                </div>
            </div>
        </div>

        <div
            class="absolute bottom-0 left-0 h-px w-full bg-linear-to-r from-transparent via-primary/30 to-transparent"
        ></div>
    </section>
    <DealerHowItWorksSection />

    <AppContent>
        <PriceCard
            :plans="props.plans"
            :pro-only-selection="true"
            @select-plan="openDealerRegistrationModal"
        />
    </AppContent>

    <DealerRegistrationModal
        v-model:open="showDealerRegistrationModal"
        :initial-plan="preselectedPlan"
        :plans="props.plans"
    />
</template>
