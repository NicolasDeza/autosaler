<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import axios from 'axios';
import { MapPin, UploadCloud } from 'lucide-vue-next';
import { ref, watch } from 'vue';
import CompanyController from '@/actions/App/Http/Controllers/Settings/CompanyController';
import AppContent from '@/components/AppContent.vue';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { useTranslation } from '@/composables/useTranslation';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { edit } from '@/routes/company';
import { type BreadcrumbItem } from '@/types';

type Props = {
    company: any;
    countries: any[];
};

const props = defineProps<Props>();

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: props.company.name,
        href: edit().url,
    },
];

const { __ } = useTranslation();

// City search logic
const showCities = ref(false);
const isSearchingCities = ref(false);
const cities = ref<any[]>([]);
const searchTerm = ref(
    props.company.city
        ? props.company.city.zip_code + ' ' + props.company.city.code
        : '',
);
const selectedCityId = ref(props.company.city_id);

let citySearchTimeout: number | undefined;

const searchCities = (query: string) => {
    if (!query || query.length < 2) {
        cities.value = [];
        showCities.value = false;
        return;
    }

    isSearchingCities.value = true;
    showCities.value = true;

    if (citySearchTimeout) clearTimeout(citySearchTimeout);

    citySearchTimeout = window.setTimeout(async () => {
        try {
            const { data } = await axios.get('/cities/search', {
                params: { query },
            });
            cities.value = data;
        } catch (error) {
            console.error('Error fetching cities:', error);
        } finally {
            isSearchingCities.value = false;
        }
    }, 300);
};

const selectCity = (city: any) => {
    searchTerm.value = city.zip_code + ' ' + city.code;
    selectedCityId.value = city.id;
    showCities.value = false;
};

const handleCityBlur = () => {
    setTimeout(() => {
        showCities.value = false;
    }, 200);
};

watch(searchTerm, (newVal) => {
    if (!newVal) {
        selectedCityId.value = '';
    }
});

const logoPreview = ref(props.company.logo_url);
const backgroundPreview = ref(props.company.background_url);

const handleLogoChange = (e: Event) => {
    const file = (e.target as HTMLInputElement).files?.[0];
    if (file) {
        logoPreview.value = URL.createObjectURL(file);
    }
};

const handleBackgroundChange = (e: Event) => {
    const file = (e.target as HTMLInputElement).files?.[0];
    if (file) {
        backgroundPreview.value = URL.createObjectURL(file);
    }
};
</script>

<template>
    <AppContent :breadcrumbs="breadcrumbItems">
        <Head :title="__('settings.company_title')" />
        <Head :title="__('settings.menu_company')" />

        <h1 class="sr-only">{{ __('settings.company_title') }}</h1>

        <SettingsLayout>
            <div class="flex flex-col space-y-6">
                <Heading
                    variant="small"
                    :title="__('settings.menu_company')"
                    :description="__('settings.company_description')"
                />

                <Form
                    v-bind="CompanyController.update.form()"
                    class="space-y-6"
                    v-slot="{ errors, processing, recentlySuccessful }"
                >
                    <input type="hidden" name="country_id" value="1" />

                    <!-- Company Profile Images -->
                    <div class="grid gap-6 sm:grid-cols-2">
                        <!-- Logo Upload -->
                        <div class="space-y-3">
                            <Label>{{ __('settings.company_logo') }}</Label>
                            <div
                                class="group relative flex h-40 w-40 items-center justify-center overflow-hidden rounded-xl border-2 border-dashed border-muted-foreground/25 bg-muted/30 transition-all hover:bg-muted/50"
                            >
                                <img
                                    v-if="logoPreview"
                                    :src="logoPreview"
                                    class="h-full w-full object-contain p-4"
                                    alt="Logo preview"
                                />
                                <div
                                    v-else
                                    class="flex flex-col items-center space-y-2 text-center text-muted-foreground"
                                >
                                    <UploadCloud class="h-8 w-8" />
                                    <span class="px-2 text-xs font-medium">{{
                                        __('settings.company_logo_placeholder')
                                    }}</span>
                                </div>
                                <input
                                    type="file"
                                    name="logo"
                                    accept="image/*"
                                    class="absolute inset-0 cursor-pointer opacity-0"
                                    @change="handleLogoChange"
                                />
                                <div
                                    v-if="logoPreview"
                                    class="absolute inset-x-0 bottom-0 bg-black/50 p-1 text-center text-[10px] text-white opacity-0 transition-opacity group-hover:opacity-100"
                                >
                                    {{ __('ui.click_to_change') }}
                                </div>
                            </div>
                            <p class="text-[10px] text-muted-foreground">
                                {{ __('settings.company_logo_help') }}
                            </p>
                            <InputError :message="errors.logo" />
                        </div>

                        <!-- Background Upload -->
                        <div class="space-y-3">
                            <Label>{{ __('settings.company_background') }}</Label>
                            <div
                                class="group relative flex aspect-2/1 w-full items-center justify-center overflow-hidden rounded-xl border-2 border-dashed border-muted-foreground/25 bg-muted/30 transition-all hover:bg-muted/50"
                            >
                                <img
                                    v-if="backgroundPreview"
                                    :src="backgroundPreview"
                                    class="h-full w-full object-cover"
                                    alt="Background preview"
                                />
                                <div
                                    v-else
                                    class="flex flex-col items-center space-y-2 text-center text-muted-foreground"
                                >
                                    <UploadCloud class="h-8 w-8" />
                                    <span class="px-2 text-xs font-medium">{{
                                        __('settings.company_background_placeholder')
                                    }}</span>
                                </div>
                                <input
                                    type="file"
                                    name="background"
                                    accept="image/*"
                                    class="absolute inset-0 cursor-pointer opacity-0"
                                    @change="handleBackgroundChange"
                                />
                                <div
                                    v-if="backgroundPreview"
                                    class="absolute inset-x-0 bottom-0 bg-black/50 p-1 text-center text-[10px] text-white opacity-0 transition-opacity group-hover:opacity-100"
                                >
                                    {{ __('ui.click_to_change') }}
                                </div>
                            </div>
                            <p class="text-[10px] text-muted-foreground">
                                {{ __('settings.company_background_help') }}
                            </p>
                            <InputError :message="errors.background" />
                        </div>
                    </div>

                    <div class="grid gap-2">
                        <Label for="name">{{
                            __('settings.company_name')
                        }}</Label>
                        <Input
                            id="name"
                            class="mt-1 block w-full"
                            name="name"
                            :default-value="company.name"
                            required
                        />
                        <InputError class="mt-2" :message="errors.name" />
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div class="grid gap-2">
                            <Label for="address">{{
                                __('settings.company_address')
                            }}</Label>
                            <Input
                                id="address"
                                class="mt-1 block w-full"
                                name="address"
                                :default-value="company.address"
                                required
                            />
                            <InputError
                                class="mt-2"
                                :message="errors.address"
                            />
                        </div>

                        <div class="relative grid gap-2">
                            <Label for="city_search">{{
                                __('settings.company_city')
                            }}</Label>
                            <div class="relative">
                                <MapPin
                                    :size="14"
                                    class="absolute top-1/2 left-3 z-10 -translate-y-1/2 text-muted-foreground"
                                />
                                <Input
                                    id="city_search"
                                    v-model="searchTerm"
                                    :placeholder="
                                        __('settings.company_city_search')
                                    "
                                    class="block w-full pl-9"
                                    @input="searchCities(searchTerm)"
                                    @focus="
                                        searchTerm.length >= 2
                                            ? (showCities = true)
                                            : null
                                    "
                                    @blur="handleCityBlur"
                                />
                            </div>
                            <input
                                type="hidden"
                                name="city_id"
                                :value="selectedCityId"
                            />

                            <!-- Suggestions List -->
                            <div
                                v-if="
                                    showCities &&
                                    (cities.length > 0 || isSearchingCities)
                                "
                                class="absolute top-full left-0 z-100 mt-1 w-full animate-in overflow-hidden rounded-md border border-border bg-popover text-popover-foreground shadow-xl fade-in-80"
                            >
                                <div class="max-h-60 overflow-y-auto p-1">
                                    <div
                                        v-for="city in cities"
                                        :key="city.id"
                                        class="flex cursor-pointer items-center rounded-sm px-2 py-1.5 text-sm outline-none select-none hover:bg-accent hover:text-accent-foreground"
                                        @click="selectCity(city)"
                                    >
                                        <span
                                            class="mr-2 font-medium text-popover-foreground"
                                            >{{ city.zip_code }}</span
                                        >
                                        <span class="text-muted-foreground">{{
                                            city.code
                                        }}</span>
                                    </div>
                                    <div
                                        v-if="
                                            cities.length === 0 &&
                                            isSearchingCities
                                        "
                                        class="p-2 text-center text-sm text-muted-foreground"
                                    >
                                        {{ __('ui.loading') }}
                                    </div>
                                </div>
                            </div>
                            <InputError
                                class="mt-2"
                                :message="errors.city_id"
                            />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div class="grid gap-2">
                            <Label for="phone">{{
                                __('settings.company_phone')
                            }}</Label>
                            <Input
                                id="phone"
                                class="mt-1 block w-full"
                                name="phone"
                                :default-value="company.phone"
                                required
                            />
                            <InputError class="mt-2" :message="errors.phone" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="tva_number">{{
                                __('settings.company_tva_number')
                            }}</Label>
                            <Input
                                id="tva_number"
                                class="mt-1 block w-full"
                                name="tva_number"
                                :default-value="company.tva_number"
                            />
                            <InputError
                                class="mt-2"
                                :message="errors.tva_number"
                            />
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <Button
                            :disabled="processing"
                            class="hover:cursor-pointer"
                            >{{ __('settings.company_save') }}</Button
                        >

                        <Transition
                            enter-active-class="transition ease-in-out"
                            enter-from-class="opacity-0"
                            leave-active-class="transition ease-in-out"
                            leave-to-class="opacity-0"
                        >
                            <p
                                v-show="recentlySuccessful"
                                class="text-sm text-neutral-600"
                            >
                                {{ __('settings.company_updated') }}
                            </p>
                        </Transition>
                    </div>
                </Form>
            </div>
        </SettingsLayout>
    </AppContent>
</template>
