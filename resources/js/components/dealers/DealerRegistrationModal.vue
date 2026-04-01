<script setup lang="ts">
import { router, useForm, usePage } from '@inertiajs/vue3';
import axios from 'axios';
import { LoaderCircle, Send, X } from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';
import { toast } from 'vue-sonner';
import DealerRegistrationController from '@/actions/App/Http/Controllers/DealerRegistrationController';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogClose,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Textarea } from '@/components/ui/textarea';
import { useTranslation } from '@/composables/useTranslation';
import { home } from '@/routes';
import type { DealerSubscriptionPlan } from '@/types/dealers';
import type { ExtendedPageProps } from '@/types/inertia';

type CityResult = {
    id: number;
    code: string;
    zip_code: string;
};

type PlanLabelKey =
    | 'plan_starter'
    | 'plan_pro'
    | 'plan_business'
    | 'badge_premium';

type Props = {
    initialPlan?: string | number | null;
    plans: DealerSubscriptionPlan[];
};

const props = withDefaults(defineProps<Props>(), {
    initialPlan: null,
});
const isOpen = defineModel<boolean>('open', { required: true });
const { __ } = useTranslation();
const page = usePage<ExtendedPageProps>();

const labelKeyByPlanKey: Partial<Record<string, PlanLabelKey>> = {
    starter: 'plan_starter',
    pro: 'plan_pro',
    business: 'plan_business',
    premium: 'badge_premium',
};

const cityQuery = ref('');
const cityResults = ref<CityResult[]>([]);
const showCityResults = ref(false);
const isSearchingCities = ref(false);
const showSuccessModal = ref(false);

let citySearchTimeout: number | undefined;

const availablePlanIds = computed(() => props.plans.map((plan) => String(plan.id)));

const normalizePlan = (value: string | number | null | undefined): string => {
    if (value === null || value === undefined || value === '') {
        return '';
    }

    const normalized = String(value);

    if (availablePlanIds.value.includes(normalized)) {
        return normalized;
    }

    return '';
};

const form = useForm({
    first_name: '',
    last_name: '',
    email: '',
    password: '',
    password_confirmation: '',
    company_name: '',
    company_email: '',
    company_phone: '',
    tva_number: '',
    company_address: '',
    city_id: '',
    subscription_plan_id: '',
    message: '',
});

const selectedPlan = computed(() => {
    return props.plans.find((plan) => String(plan.id) === form.subscription_plan_id) ?? null;
});

const selectedPlanLabel = computed(() => {
    const plan = selectedPlan.value;

    if (!plan) {
        return '';
    }

    const labelKey = labelKeyByPlanKey[plan.key];
    const label = labelKey ? __(`pricing.${labelKey}`) : plan.key;

    return `${label} - ${plan.listing_limit} ${__('pricing.listings')} (${Number(plan.price).toFixed(2)} EUR)`;
});

const resetForm = (planValue: string): void => {
    form.reset();
    form.clearErrors();
    form.subscription_plan_id = planValue;
    cityQuery.value = '';
    cityResults.value = [];
    showCityResults.value = false;
};

watch(isOpen, (open) => {
    if (open) {
        resetForm(normalizePlan(props.initialPlan));
    }
});

watch(
    () => props.initialPlan,
    (newPlan) => {
        if (isOpen.value) {
            form.subscription_plan_id = normalizePlan(newPlan);
        }
    },
);

watch(
    () => page.props.flash?.dealerRegistrationSuccess,
    (dealerRegistrationSuccess) => {
        if (dealerRegistrationSuccess) {
            showSuccessModal.value = true;
        }
    },
    { immediate: true },
);

const handleOpenChange = (open: boolean): void => {
    isOpen.value = open;
};

const searchCities = (query: string): void => {
    if (!query || query.length < 2) {
        cityResults.value = [];
        showCityResults.value = false;
        form.city_id = '';
        return;
    }

    isSearchingCities.value = true;
    showCityResults.value = true;

    if (citySearchTimeout) {
        clearTimeout(citySearchTimeout);
    }

    citySearchTimeout = window.setTimeout(async () => {
        try {
            const { data } = await axios.get<CityResult[]>('/cities/search', {
                params: { query },
            });

            cityResults.value = data;
        } catch (error) {
            cityResults.value = [];
            console.error('Error fetching cities:', error);
        } finally {
            isSearchingCities.value = false;
        }
    }, 250);
};

const selectCity = (city: CityResult): void => {
    form.city_id = String(city.id);
    cityQuery.value = `${city.zip_code} ${city.code}`;
    showCityResults.value = false;
};

const handleCityBlur = (): void => {
    setTimeout(() => {
        showCityResults.value = false;
    }, 150);
};

const submit = (): void => {
    form.post(DealerRegistrationController().url, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            resetForm(normalizePlan(props.initialPlan));
            isOpen.value = false;
        },
    });
};

const planLabel = (plan: DealerSubscriptionPlan): string => {
    const labelKey = labelKeyByPlanKey[plan.key];

    if (!labelKey) {
        return plan.key;
    }

    return __(`pricing.${labelKey}`);
};

const confirmSuccessModal = (): void => {
    showSuccessModal.value = false;

    toast.success(__('dealerPage.registration_success_title'), {
        description: __('dealerPage.registration_success_description'),
    });

    router.visit(home().url);
};
</script>

<template>
    <Dialog :open="isOpen" @update:open="handleOpenChange">
        <DialogContent
            :show-close-button="false"
            class="max-h-[90svh] overflow-y-auto border-0 p-0 sm:max-w-2xl"
        >
            <DialogHeader
                class="sticky top-0 z-20 border-b-2 border-primary bg-[#1c2631] px-6 py-5 pr-16"
            >
                <DialogTitle class="text-white">
                    {{ __('dealerPage.registration_modal_title') }}
                </DialogTitle>
                <DialogDescription class="text-white/80">
                    {{ __('dealerPage.registration_modal_description') }}
                </DialogDescription>
                <DialogClose
                    class="absolute top-4 right-4 inline-flex size-9 cursor-pointer items-center justify-center rounded-md text-white/85 transition-colors hover:bg-white/10 hover:text-white focus-visible:ring-2 focus-visible:ring-white/60 focus-visible:outline-none"
                >
                    <X class="size-5" />
                    <span class="sr-only">{{ __('ui.close') || 'Close' }}</span>
                </DialogClose>
            </DialogHeader>

            <form class="flex flex-col gap-5 p-6" @submit.prevent="submit">
                <div class="order-2 space-y-3">
                    <h3 class="text-sm font-bold tracking-wide text-foreground/80 uppercase">
                        {{ __('dealerPage.registration_section_company') }}
                    </h3>
                    <p class="text-xs leading-relaxed text-muted-foreground">
                        {{ __('dealerPage.registration_section_company_description') }}
                    </p>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div class="space-y-2 sm:col-span-2">
                            <Label for="company_name">
                                {{ __('dealerPage.field_company_name') }} <span class="text-primary">*</span>
                            </Label>
                            <Input
                                id="company_name"
                                v-model="form.company_name"
                                name="company_name"
                                autocomplete="organization"
                            />
                            <InputError :message="form.errors.company_name" />
                        </div>

                        <div class="space-y-2">
                            <Label for="company_email">
                                {{ __('dealerPage.field_company_email') }} <span class="text-primary">*</span>
                            </Label>
                            <Input
                                id="company_email"
                                type="email"
                                v-model="form.company_email"
                                name="company_email"
                                autocomplete="email"
                            />
                            <InputError :message="form.errors.company_email" />
                        </div>

                        <div class="space-y-2">
                            <Label for="company_phone">
                                {{ __('dealerPage.field_company_phone') }} <span class="text-primary">*</span>
                            </Label>
                            <Input
                                id="company_phone"
                                type="tel"
                                v-model="form.company_phone"
                                name="company_phone"
                                autocomplete="tel"
                            />
                            <InputError :message="form.errors.company_phone" />
                        </div>

                        <div class="space-y-2">
                            <Label for="tva_number">{{ __('dealerPage.field_tva_number') }}</Label>
                            <Input
                                id="tva_number"
                                v-model="form.tva_number"
                                name="tva_number"
                                autocomplete="off"
                            />
                            <InputError :message="form.errors.tva_number" />
                        </div>

                        <div class="space-y-2">
                            <Label for="subscription_plan_id">
                                {{ __('dealerPage.field_plan') }} <span class="text-primary">*</span>
                            </Label>
                            <Select
                                v-model="form.subscription_plan_id"
                                name="subscription_plan_id"
                            >
                                <SelectTrigger id="subscription_plan_id" class="w-full">
                                    <SelectValue :placeholder="__('dealerPage.placeholder_plan')" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem
                                        v-for="plan in props.plans"
                                        :key="plan.id"
                                        :value="String(plan.id)"
                                    >
                                        {{ planLabel(plan) }} - {{ plan.listing_limit }} {{ __('pricing.listings') }}
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                            <InputError :message="form.errors.subscription_plan_id" />
                        </div>

                        <div class="space-y-2 sm:col-span-2">
                            <Label for="company_address">
                                {{ __('dealerPage.field_company_address') }} <span class="text-primary">*</span>
                            </Label>
                            <Input
                                id="company_address"
                                v-model="form.company_address"
                                name="company_address"
                                autocomplete="street-address"
                            />
                            <InputError :message="form.errors.company_address" />
                        </div>

                        <div class="relative space-y-2 sm:col-span-2">
                            <Label for="city_query">
                                {{ __('dealerPage.field_city') }} <span class="text-primary">*</span>
                            </Label>
                            <Input
                                id="city_query"
                                v-model="cityQuery"
                                name="city_query"
                                :placeholder="__('dealerPage.placeholder_city')"
                                autocomplete="address-level2"
                                @input="searchCities(cityQuery)"
                                @focus="cityQuery.length >= 2 ? (showCityResults = true) : null"
                                @blur="handleCityBlur"
                            />
                            <input
                                id="city_id"
                                v-model="form.city_id"
                                type="hidden"
                                name="city_id"
                            >
                            <input
                                id="subscription_plan_id_value"
                                v-model="form.subscription_plan_id"
                                type="hidden"
                                name="subscription_plan_id"
                            >
                            <InputError :message="form.errors.city_id" />

                            <div
                                v-if="showCityResults && (cityResults.length > 0 || isSearchingCities)"
                                class="absolute top-full z-50 mt-1 w-full rounded-md border border-border bg-popover p-1 shadow-md"
                            >
                                <div
                                    v-for="city in cityResults"
                                    :key="city.id"
                                    class="cursor-pointer rounded-sm px-2 py-1.5 text-sm hover:bg-accent"
                                    @click="selectCity(city)"
                                >
                                    {{ city.zip_code }} {{ city.code }}
                                </div>
                                <div
                                    v-if="cityResults.length === 0 && isSearchingCities"
                                    class="px-2 py-1.5 text-sm text-muted-foreground"
                                >
                                    {{ __('dealerPage.searching_city') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="order-1 space-y-3">
                    <h3 class="text-sm font-bold tracking-wide text-foreground/80 uppercase">
                        {{ __('dealerPage.registration_section_user') }}
                    </h3>
                    <p class="text-xs leading-relaxed text-muted-foreground">
                        {{ __('dealerPage.registration_section_user_description') }}
                    </p>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div class="space-y-2">
                            <Label for="first_name">
                                {{ __('dealerPage.field_first_name') }} <span class="text-primary">*</span>
                            </Label>
                            <Input
                                id="first_name"
                                v-model="form.first_name"
                                name="first_name"
                                autocomplete="given-name"
                            />
                            <InputError :message="form.errors.first_name" />
                        </div>

                        <div class="space-y-2">
                            <Label for="last_name">
                                {{ __('dealerPage.field_last_name') }} <span class="text-primary">*</span>
                            </Label>
                            <Input
                                id="last_name"
                                v-model="form.last_name"
                                name="last_name"
                                autocomplete="family-name"
                            />
                            <InputError :message="form.errors.last_name" />
                        </div>

                        <div class="space-y-2 sm:col-span-2">
                            <Label for="email">
                                {{ __('dealerPage.field_user_email') }} <span class="text-primary">*</span>
                            </Label>
                            <Input
                                id="email"
                                type="email"
                                v-model="form.email"
                                name="email"
                                autocomplete="email"
                            />
                            <InputError :message="form.errors.email" />
                        </div>

                        <div class="space-y-2">
                            <Label for="password">
                                {{ __('dealerPage.field_password') }} <span class="text-primary">*</span>
                            </Label>
                            <Input
                                id="password"
                                type="password"
                                v-model="form.password"
                                name="password"
                                autocomplete="new-password"
                            />
                            <InputError :message="form.errors.password" />
                        </div>

                        <div class="space-y-2">
                            <Label for="password_confirmation">
                                {{ __('dealerPage.field_password_confirmation') }} <span class="text-primary">*</span>
                            </Label>
                            <Input
                                id="password_confirmation"
                                type="password"
                                v-model="form.password_confirmation"
                                name="password_confirmation"
                                autocomplete="new-password"
                            />
                        </div>

                        <div class="space-y-2 sm:col-span-2">
                            <Label for="message">{{ __('dealerPage.field_message') }}</Label>
                            <Textarea
                                id="message"
                                v-model="form.message"
                                name="message"
                                autocomplete="off"
                                rows="3"
                            />
                            <InputError :message="form.errors.message" />
                        </div>
                    </div>
                </div>

                <div
                    class="order-3 rounded-md border border-border bg-muted/40 px-3 py-2 text-xs text-muted-foreground"
                >
                    {{ selectedPlanLabel || __('dealerPage.placeholder_plan') }}
                </div>

                <DialogFooter class="order-4">
                    <Button
                        type="button"
                        variant="outline"
                        class="cursor-pointer"
                        @click="isOpen = false"
                    >
                        {{ __('dealerPage.modal_cancel') }}
                    </Button>
                    <Button
                        type="submit"
                        :disabled="form.processing"
                        class="cursor-pointer disabled:cursor-not-allowed"
                    >
                        <LoaderCircle
                            v-if="form.processing"
                            class="mr-2 h-4 w-4 animate-spin"
                        />
                        <Send v-else class="mr-2 h-4 w-4" />
                        {{ form.processing ? __('dealerPage.modal_sending') : __('dealerPage.modal_submit') }}
                    </Button>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>

    <Dialog :open="showSuccessModal" @update:open="showSuccessModal = $event">
        <DialogContent class="sm:max-w-md">
            <DialogHeader>
                <DialogTitle>{{ __('dealerPage.registration_success_title') }}</DialogTitle>
                <DialogDescription>
                    {{ __('dealerPage.registration_success_description') }}
                </DialogDescription>
            </DialogHeader>

            <DialogFooter>
                <Button
                    type="button"
                    class="cursor-pointer"
                    @click="confirmSuccessModal"
                >
                    {{ __('dealerPage.modal_acknowledge') }}
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
