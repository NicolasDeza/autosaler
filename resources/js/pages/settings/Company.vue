<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import axios from 'axios';
import { Crop, MapPin, UploadCloud } from 'lucide-vue-next';
import { ref, watch } from 'vue';
import CompanyController from '@/actions/App/Http/Controllers/Settings/CompanyController';
import AppContent from '@/components/AppContent.vue';
import Heading from '@/components/Heading.vue';
import ImageCropper from '@/components/ImageCropper.vue';
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

// Form logic
const companyForm = useForm({
    _method: 'PATCH',
    name: props.company.name,
    email: props.company.email,
    address: props.company.address,
    city_id: props.company.city_id,
    country_id: props.company.country_id,
    tva_number: props.company.tva_number,
    phone: props.company.phone,
    logo: null as File | null,
    background: null as File | null,
    logo_crop: null as any,
    background_crop: null as any,
});

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
    companyForm.city_id = city.id;
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
        companyForm.city_id = '';
    }
});

const logoPreview = ref(props.company.logo_url);
const backgroundPreview = ref(props.company.background_url);
const originalLogoFile = ref<File | null>(null);
const originalBackgroundFile = ref<File | null>(null);

// Cropper logic
const cropperOpen = ref(false);
const imageToCrop = ref<string | null>(null);
const cropTarget = ref<'logo' | 'background' | null>(null);
const cropAspect = ref(1);

const openCropper = (target: 'logo' | 'background') => {
    cropTarget.value = target;
    cropAspect.value = target === 'logo' ? 1 : 2;

    // Use current freshly uploaded original file if exists, otherwise original model URL
    const originalFile =
        target === 'logo'
            ? originalLogoFile.value
            : originalBackgroundFile.value;

    if (originalFile) {
        imageToCrop.value = URL.createObjectURL(originalFile);
    } else {
        imageToCrop.value =
            target === 'logo'
                ? props.company.original_logo_url
                : props.company.original_background_url;
    }

    if (imageToCrop.value) {
        cropperOpen.value = true;
    }
};

const handleLogoChange = (e: Event) => {
    const file = (e.target as HTMLInputElement).files?.[0];
    if (file) {
        originalLogoFile.value = file;
        imageToCrop.value = URL.createObjectURL(file);
        cropTarget.value = 'logo';
        cropAspect.value = 1;
        cropperOpen.value = true;
    }
};

const handleBackgroundChange = (e: Event) => {
    const file = (e.target as HTMLInputElement).files?.[0];
    if (file) {
        originalBackgroundFile.value = file;
        imageToCrop.value = URL.createObjectURL(file);
        cropTarget.value = 'background';
        cropAspect.value = 2 / 1; // 800/400
        cropperOpen.value = true;
    }
};

const handleCropConfirm = ({
    blob,
    coordinates,
}: {
    blob: Blob;
    coordinates: any;
}) => {
    if (cropTarget.value === 'logo') {
        logoPreview.value = URL.createObjectURL(blob);
        companyForm.logo = originalLogoFile.value;
        companyForm.logo_crop = coordinates;
    } else {
        backgroundPreview.value = URL.createObjectURL(blob);
        companyForm.background = originalBackgroundFile.value;
        companyForm.background_crop = coordinates;
    }

    cropperOpen.value = false;
};

const submit = () => {
    companyForm.post(CompanyController.update.url(), {
        onSuccess: () => {
            // Optional: reset file inputs if needed
            originalLogoFile.value = null;
            originalBackgroundFile.value = null;
        },
    });
};
</script>

<template>
    <AppContent :breadcrumbs="breadcrumbItems">
        <Head :title="__('settings.company_title')" />

        <h1 class="sr-only">{{ __('settings.company_title') }}</h1>

        <SettingsLayout>
            <div class="flex flex-col space-y-6">
                <Heading
                    variant="small"
                    :title="__('settings.menu_company')"
                    :description="__('settings.company_description')"
                />

                <form @submit.prevent="submit" class="space-y-6">
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

                                <!-- Overlay Tools -->
                                <div
                                    class="absolute inset-0 z-10 flex flex-col items-center justify-center space-y-2 bg-black/60 opacity-0 backdrop-blur-sm transition-opacity group-hover:opacity-100"
                                >
                                    <div class="flex gap-2">
                                        <div
                                            class="relative flex h-9 w-9 cursor-pointer items-center justify-center rounded-full bg-white text-black transition-transform hover:scale-110"
                                        >
                                            <UploadCloud :size="16" />
                                            <input
                                                type="file"
                                                accept="image/*"
                                                class="absolute inset-0 cursor-pointer opacity-0"
                                                @change="handleLogoChange"
                                            />
                                        </div>
                                        <button
                                            v-if="logoPreview"
                                            type="button"
                                            class="flex h-9 w-9 cursor-pointer items-center justify-center rounded-full bg-white text-black transition-transform hover:scale-110"
                                            @click="openCropper('logo')"
                                        >
                                            <Crop :size="16" />
                                        </button>
                                    </div>
                                    <p class="text-[10px] font-medium text-white">
                                        {{ logoPreview ? __('ui.edit') : __('ui.add_photos') }}
                                    </p>
                                </div>
                            </div>
                            <p class="text-[10px] text-muted-foreground">
                                {{ __('settings.company_logo_help') }}
                            </p>
                            <InputError :message="companyForm.errors.logo" />
                        </div>

                        <!-- Background Upload -->
                        <div class="space-y-3">
                            <Label>{{
                                __('settings.company_background')
                            }}</Label>
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

                                <!-- Overlay Tools -->
                                <div
                                    class="absolute inset-0 z-10 flex flex-col items-center justify-center space-y-2 bg-black/60 opacity-0 backdrop-blur-sm transition-opacity group-hover:opacity-100"
                                >
                                    <div class="flex gap-2">
                                        <div
                                            class="relative flex h-9 w-9 cursor-pointer items-center justify-center rounded-full bg-white text-black transition-transform hover:scale-110"
                                        >
                                            <UploadCloud :size="16" />
                                            <input
                                                type="file"
                                                accept="image/*"
                                                class="absolute inset-0 cursor-pointer opacity-0"
                                                @change="handleBackgroundChange"
                                            />
                                        </div>
                                        <button
                                            v-if="backgroundPreview"
                                            type="button"
                                            class="flex h-9 w-9 cursor-pointer items-center justify-center rounded-full bg-white text-black transition-transform hover:scale-110"
                                            @click="openCropper('background')"
                                        >
                                            <Crop :size="16" />
                                        </button>
                                    </div>
                                    <p class="text-[10px] font-medium text-white">
                                        {{ backgroundPreview ? __('ui.edit') : __('ui.add_photos') }}
                                    </p>
                                </div>
                            </div>
                            <p class="text-[10px] text-muted-foreground">
                                {{ __('settings.company_background_help') }}
                            </p>
                            <InputError
                                :message="companyForm.errors.background"
                            />
                        </div>
                    </div>

                    <div class="grid gap-2">
                        <Label for="name">{{
                            __('settings.company_name')
                        }}</Label>
                        <Input
                            id="name"
                            v-model="companyForm.name"
                            class="mt-1 block w-full"
                            required
                        />
                        <InputError
                            class="mt-2"
                            :message="companyForm.errors.name"
                        />
                    </div>

                    <div class="grid gap-2">
                        <Label for="email">{{
                            __('settings.company_email')
                        }}</Label>
                        <Input
                            id="email"
                            v-model="companyForm.email"
                            type="email"
                            class="mt-1 block w-full"
                            required
                        />
                        <InputError
                            class="mt-2"
                            :message="companyForm.errors.email"
                        />
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div class="grid gap-2">
                            <Label for="address">{{
                                __('settings.company_address')
                            }}</Label>
                            <Input
                                id="address"
                                v-model="companyForm.address"
                                class="mt-1 block w-full"
                                required
                            />
                            <InputError
                                class="mt-2"
                                :message="companyForm.errors.address"
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
                                :value="companyForm.city_id"
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
                                :message="companyForm.errors.city_id"
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
                                v-model="companyForm.phone"
                                class="mt-1 block w-full"
                                required
                            />
                            <InputError
                                class="mt-2"
                                :message="companyForm.errors.phone"
                            />
                        </div>

                        <div class="grid gap-2">
                            <Label for="tva_number">{{
                                __('settings.company_tva_number')
                            }}</Label>
                            <Input
                                id="tva_number"
                                v-model="companyForm.tva_number"
                                class="mt-1 block w-full"
                            />
                            <InputError
                                class="mt-2"
                                :message="companyForm.errors.tva_number"
                            />
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <Button
                            :disabled="companyForm.processing"
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
                                v-show="companyForm.recentlySuccessful"
                                class="text-sm text-neutral-600"
                            >
                                {{ __('settings.company_updated') }}
                            </p>
                        </Transition>
                    </div>
                </form>
            </div>
        </SettingsLayout>

        <ImageCropper
            :open="cropperOpen"
            :image="imageToCrop"
            :stencil-aspect-ratio="cropAspect"
            :title="
                cropTarget === 'logo'
                    ? __('settings.company_logo')
                    : __('settings.company_background')
            "
            @close="cropperOpen = false"
            @confirm="handleCropConfirm"
        />
    </AppContent>
</template>
