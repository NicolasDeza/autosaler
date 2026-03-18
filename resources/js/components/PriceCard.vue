<script setup lang="ts">
import { Check } from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import { useTranslation } from '@/composables/useTranslation';
import type { DealerSubscriptionPlan } from '@/types/dealers';

const props = defineProps<{
    plans: DealerSubscriptionPlan[];
}>();

const { __ } = useTranslation();
const emit = defineEmits<{
    (event: 'select-plan', subscriptionPlanId: number): void;
}>();

type PlanFeature = 'photos' | 'stats' | 'unlimited';
type PlanLabelKey =
    | 'plan_starter'
    | 'plan_pro'
    | 'plan_business'
    | 'badge_premium';

type PricingPlan = DealerSubscriptionPlan & {
    labelKey?: PlanLabelKey;
    featured: boolean;
    features: PlanFeature[];
};

const labelKeyByPlanKey: Partial<Record<string, PlanLabelKey>> = {
    starter: 'plan_starter',
    pro: 'plan_pro',
    business: 'plan_business',
    premium: 'badge_premium',
};

const featuresByPlanKey: Partial<Record<string, PlanFeature[]>> = {
    starter: ['photos'],
    pro: ['photos', 'stats'],
    business: ['photos', 'stats'],
    premium: ['unlimited', 'photos', 'stats'],
};

const pricingPlans = computed<PricingPlan[]>(() => {
    return props.plans.map((plan) => ({
        ...plan,
        labelKey: labelKeyByPlanKey[plan.key],
        featured: plan.key === 'pro',
        features: featuresByPlanKey[plan.key] ?? ['photos'],
    }));
});

const selected = ref<number | null>(null);

watch(
    pricingPlans,
    (plans) => {
        if (plans.length === 0) {
            selected.value = null;
            return;
        }

        if (selected.value !== null && plans.some((plan) => plan.id === selected.value)) {
            return;
        }

        selected.value = plans.find((plan) => plan.featured)?.id ?? plans[0].id;
    },
    { immediate: true },
);

function featureLabel(feature: PlanFeature): string {
    if (feature === 'unlimited') {
        return __('pricing.unlimited');
    }

    return __(`pricing.feature_${feature}`);
}

function listingsLabel(count: number): string {
    if (count === 1) {
        return __('pricing.listing_single');
    }

    return __('pricing.listing_plural', { count });
}

function planLabel(plan: PricingPlan): string {
    if (!plan.labelKey) {
        return plan.key;
    }

    return __(`pricing.${plan.labelKey}`);
}

function selectPlan(subscriptionPlanId: number): void {
    selected.value = subscriptionPlanId;
    emit('select-plan', subscriptionPlanId);
}
</script>

<template>
    <section class="relative w-full">
        <div class="mx-auto max-w-7xl px-6">
            <div class="mb-12 text-center sm:mb-14">
                <h2
                    class="text-3xl font-[1000] tracking-tight text-foreground uppercase"
                >
                    {{ __('pricing.title_prefix') }}
                    <span class="text-primary">{{
                        __('pricing.title_highlight')
                    }}</span>
                </h2>
                <p
                    class="mx-auto mt-4 max-w-lg font-medium text-muted-foreground"
                >
                    {{ __('pricing.subtitle') }}
                </p>
            </div>

            <div class="mb-10 flex justify-center sm:mb-12">
                <div
                    class="flex items-center gap-3 rounded-lg border border-border bg-muted px-5 py-2.5 text-sm text-muted-foreground shadow-sm"
                >
                    <span
                        class="rounded-md bg-primary px-2 py-0.5 text-[9px] font-black tracking-widest text-white uppercase"
                    >
                        Pro
                    </span>
                    <span class="font-semibold">{{
                        __('pricing.pro_only_description')
                    }}</span>
                </div>
            </div>

            <div v-if="pricingPlans.length > 0" class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
                <Card
                    v-for="plan in pricingPlans"
                    :key="plan.id"
                    class="relative flex h-full flex-col overflow-hidden rounded-2xl border p-6 transition-all duration-300"
                    :class="[
                        plan.featured
                            ? 'border-primary bg-card lg:-translate-y-2'
                            : 'border-border/70 bg-card/90 hover:-translate-y-1 hover:border-primary/40',
                        selected === plan.id ? 'ring-2 ring-primary/35' : '',
                    ]"
                >
                    <div class="relative z-10 flex h-full flex-col">
                        <div class="mb-6 flex items-start justify-between gap-3">
                            <div>
                                <p
                                    class="text-[10px] font-black tracking-[0.2em] text-muted-foreground uppercase"
                                >
                                    {{ __('pricing.title_highlight') }}
                                </p>
                                <h3
                                    class="mt-2 text-2xl font-[1000] tracking-tight text-foreground"
                                >
                                    {{ planLabel(plan) }}
                                </h3>
                            </div>

                            <span
                                v-if="plan.featured"
                                class="rounded-full border border-primary/20 bg-primary/10 px-3 py-1 text-[10px] font-black tracking-wider text-primary uppercase"
                            >
                                {{ __('pricing.popular') }}
                            </span>
                        </div>

                        <div class="mb-5 flex items-start gap-1">
                            <span
                                class="text-4xl font-[1000] tracking-tight text-foreground tabular-nums"
                            >
                                {{ Number(plan.price).toFixed(2) }}
                            </span>
                            <span class="mt-1 text-lg font-bold text-primary">
                                &euro;
                            </span>
                        </div>

                        <p
                            class="text-xs font-semibold tracking-tight text-muted-foreground uppercase"
                        >
                            par {{ __('pricing.month') }} -
                            {{ __('pricing.no_commitment') }}
                        </p>

                        <div class="mt-6 flex-1 space-y-3">
                            <div class="flex items-center gap-3">
                                <div
                                    class="shrink-0 rounded-full bg-emerald-100 p-1 text-emerald-600"
                                >
                                    <Check :size="14" stroke-width="4" />
                                </div>
                                <span class="text-sm font-semibold text-card-foreground">
                                    {{ listingsLabel(plan.listing_limit) }}
                                </span>
                            </div>

                            <div
                                v-for="feature in plan.features"
                                :key="`${plan.id}-${feature}`"
                                class="flex items-center gap-3"
                            >
                                <div
                                    class="shrink-0 rounded-full bg-emerald-100 p-1 text-emerald-600"
                                >
                                    <Check :size="14" stroke-width="4" />
                                </div>
                                <span class="text-sm font-semibold text-card-foreground">
                                    {{ featureLabel(feature) }}
                                </span>
                            </div>
                        </div>

                        <Button
                            :variant="plan.featured ? 'default' : 'outline'"
                            @click="selectPlan(plan.id)"
                            class="mt-8 h-11 w-full cursor-pointer rounded-lg text-sm font-black uppercase transition-all duration-300"
                            :class="
                                plan.featured
                                    ? 'shadow-lg shadow-primary/30'
                                    : 'border-primary/30 text-foreground hover:border-primary hover:bg-primary hover:text-white'
                            "
                        >
                            {{ __('pricing.cta_paid') }}
                        </Button>
                    </div>
                </Card>
            </div>
        </div>
    </section>
</template>
