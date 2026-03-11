<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { Check, Send } from 'lucide-vue-next';
import { ref, computed } from 'vue';
import { toast } from 'vue-sonner';
import SubscriptionInquiryController from '@/actions/App/Http/Controllers/SubscriptionInquiryController';
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { useTranslation } from '@/composables/useTranslation';

const { __ } = useTranslation();

const plans = [10, 25, 50, 100];

const selected = ref(25);
const showModal = ref(false);

const price = computed(() => {
    switch (selected.value) {
        case 10:
            return 9.99;
        case 25:
            return 19.99;
        case 50:
            return 39.99;
        case 100:
            return 79.99;
        default:
            return 19.99;
    }
});

const form = useForm({
    company_name: '',
    contact_name: '',
    email: '',
    phone: '',
    plan_listings: 25,
    message: '',
});

function openModal() {
    form.plan_listings = selected.value;
    form.clearErrors();
    showModal.value = true;
}

function submit() {
    form.post(SubscriptionInquiryController().url, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            form.reset();
            showModal.value = false;
            toast.success(__('pricing.modal_success_title'), {
                description: __('pricing.modal_success_desc'),
            });
        },
    });
}

function closeModal() {
    showModal.value = false;
}
</script>

<template>
    <section class="w-full pt-8 sm:pt-12">
        <div class="mx-auto max-w-5xl px-6">
            <div class="mb-16 text-center">
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

            <div class="mb-10 flex justify-center">
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

            <div class="relative mx-auto max-w-2xl">
                <div
                    class="mb-8 grid grid-cols-2 gap-1 rounded-lg border border-border/50 bg-muted/80 p-1.5 sm:grid-cols-4"
                >
                    <button
                        v-for="plan in plans"
                        :key="plan"
                        @click="selected = plan"
                        class="relative flex-1 cursor-pointer rounded-lg px-2 py-3 text-sm font-bold transition-all duration-300"
                        :class="
                            selected === plan
                                ? 'scale-[1.02] bg-card text-foreground shadow-md'
                                : 'text-muted-foreground hover:bg-card/40 hover:text-foreground'
                        "
                    >
                        {{ plan }}
                        <span
                            class="ml-1 hidden font-medium opacity-60 sm:inline-block"
                            >{{ __('pricing.listings') }}</span
                        >
                    </button>
                </div>

                <Card
                    class="relative z-10 overflow-hidden rounded-lg border-border bg-card p-8 transition-all duration-500 md:p-12"
                >
                    <div class="mb-8 flex justify-center">
                        <div class="flex h-6 items-center">
                            <span
                                v-if="selected >= 50"
                                class="animate-pulse rounded-full border border-red-100 bg-red-50 px-4 py-1.5 text-[10px] font-black text-primary uppercase"
                            >
                                {{ __('pricing.badge_premium') }}
                            </span>
                            <span
                                v-else
                                class="text-[10px] font-black tracking-[0.2em] text-muted-foreground uppercase"
                            >
                                {{ __('pricing.badge_flexible') }}
                            </span>
                        </div>
                    </div>

                    <div class="flex flex-col items-center">
                        <div
                            class="mb-10 flex w-full flex-col items-center gap-10 sm:flex-row"
                        >
                            <div
                                class="flex flex-1 flex-col items-center sm:items-start"
                            >
                                <div class="flex items-start gap-1">
                                    <transition name="price-pop" mode="out-in">
                                        <span
                                            :key="price"
                                            class="text-6xl font-[1000] tracking-tighter text-foreground tabular-nums"
                                        >
                                            {{ price }}
                                        </span>
                                    </transition>
                                    <span
                                        class="mt-3 text-2xl font-bold text-primary"
                                        >€</span
                                    >
                                </div>
                                <p
                                    class="mt-1 text-xs font-bold tracking-tight text-muted-foreground uppercase"
                                >
                                    par {{ __('pricing.month') }} —
                                    {{ __('pricing.no_commitment') }}
                                </p>
                            </div>

                            <div
                                class="hidden h-20 w-px bg-linear-to-b from-transparent via-border to-transparent sm:block"
                            ></div>

                            <div class="flex-1 space-y-4">
                                <div
                                    v-for="feature in ['photos', 'stats']"
                                    :key="feature"
                                    class="group flex items-center gap-3"
                                >
                                    <div
                                        class="shrink-0 rounded-full bg-emerald-100 p-1 text-emerald-600 transition-colors group-hover:bg-emerald-500 group-hover:text-white"
                                    >
                                        <Check :size="14" stroke-width="4" />
                                    </div>
                                    <span
                                        class="text-sm font-bold text-card-foreground transition-colors group-hover:text-foreground"
                                    >
                                        {{ __(`pricing.feature_${feature}`) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <Button
                            @click="openModal"
                            class="h-12 w-full cursor-pointer rounded-lg bg-primary text-base font-black text-white "
                        >
                            {{ __('pricing.cta_paid') }}
                        </Button>
                    </div>
                </Card>
            </div>
        </div>

        <!-- Modal de demande d'abonnement -->
        <Dialog :open="showModal" @update:open="closeModal">
            <DialogContent class="sm:max-w-lg">
                <DialogHeader>
                    <DialogTitle>{{ __('pricing.modal_title') }}</DialogTitle>
                    <DialogDescription>
                        {{
                            __('pricing.modal_description', {
                                count: form.plan_listings,
                                price: price,
                            })
                        }}
                    </DialogDescription>
                </DialogHeader>

                <form @submit.prevent="submit" class="space-y-4">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div class="space-y-2">
                            <Label for="company_name">{{
                                __('pricing.field_company')
                            }}</Label>
                            <Input
                                id="company_name"
                                v-model="form.company_name"
                                :placeholder="__('pricing.placeholder_company')"
                            />
                            <p
                                v-if="form.errors.company_name"
                                class="text-sm text-primary"
                            >
                                {{ form.errors.company_name }}
                            </p>
                        </div>
                        <div class="space-y-2">
                            <Label for="contact_name">{{
                                __('pricing.field_contact')
                            }}</Label>
                            <Input
                                id="contact_name"
                                v-model="form.contact_name"
                                :placeholder="__('pricing.placeholder_contact')"
                            />
                            <p
                                v-if="form.errors.contact_name"
                                class="text-sm text-primary"
                            >
                                {{ form.errors.contact_name }}
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div class="space-y-2">
                            <Label for="email">{{
                                __('pricing.field_email')
                            }}</Label>
                            <Input
                                id="email"
                                type="email"
                                v-model="form.email"
                                placeholder="pro@garage.be"
                            />
                            <p
                                v-if="form.errors.email"
                                class="text-sm text-primary"
                            >
                                {{ form.errors.email }}
                            </p>
                        </div>
                        <div class="space-y-2">
                            <Label for="phone">{{
                                __('pricing.field_phone')
                            }}</Label>
                            <Input
                                id="phone"
                                type="tel"
                                v-model="form.phone"
                                placeholder="+32 4XX XXX XXX"
                            />
                            <p
                                v-if="form.errors.phone"
                                class="text-sm text-primary"
                            >
                                {{ form.errors.phone }}
                            </p>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <Label for="message">{{
                            __('pricing.field_message')
                        }}</Label>
                        <textarea
                            id="message"
                            v-model="form.message"
                            :placeholder="__('pricing.placeholder_message')"
                            rows="3"
                            class="flex w-full rounded-lg border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none"
                        />
                        <p
                            v-if="form.errors.message"
                            class="text-sm text-primary"
                        >
                            {{ form.errors.message }}
                        </p>
                    </div>

                    <DialogFooter>
                        <Button
                            type="button"
                            variant="outline"
                            @click="closeModal"
                            class="cursor-pointer"
                        >
                            {{ __('pricing.modal_cancel') }}
                        </Button>
                        <Button
                            type="submit"
                            :disabled="form.processing"
                            class="cursor-pointer bg-primary text-white"
                        >
                            <Send :size="16" class="mr-2" />
                            {{
                                form.processing
                                    ? __('pricing.modal_sending')
                                    : __('pricing.modal_submit')
                            }}
                        </Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>
    </section>
</template>

<style scoped>
/* Animation du changement de prix */
.price-pop-enter-active,
.price-pop-leave-active {
    transition: all 0.2s ease-out;
}
.price-pop-enter-from {
    opacity: 0;
    transform: translateY(10px);
}
.price-pop-leave-to {
    opacity: 0;
    transform: translateY(-10px);
}

/* Animation du reflet brillant sur le bouton */
@keyframes shimmer {
    100% {
        transform: translateX(300%);
    }
}
.animate-shimmer {
    animation: shimmer 0.8s ease-out;
}
</style>
