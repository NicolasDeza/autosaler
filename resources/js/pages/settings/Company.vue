<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import axios from 'axios';
import { Crop, MapPin, UploadCloud, X } from 'lucide-vue-next';
import { ref, watch } from 'vue';
import CompanyController from '@/actions/App/Http/Controllers/Settings/CompanyController';
import AppContent from '@/components/AppContent.vue';
import CompanyHeader from '@/components/Company/CompanyHeader.vue';
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
    remove_logo: false,
    remove_background: false,
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
const cropCoords = ref<any>(null);

const openCropper = (target: 'logo' | 'background') => {
    cropCoords.value = null;
    cropTarget.value = target;
    cropAspect.value = target === 'logo' ? 1 : 3;

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
        // Find existing crop coordinates if available
        cropCoords.value =
            target === 'logo'
                ? props.company.logo_crop_coords
                : props.company.background_crop_coords;

        cropperOpen.value = true;
    }
};

const handleLogoChange = (e: Event) => {
    const file = (e.target as HTMLInputElement).files?.[0];
    if (file) {
        cropCoords.value = null;
        originalLogoFile.value = file;
        imageToCrop.value = URL.createObjectURL(file);
        cropTarget.value = 'logo';
        cropAspect.value = 1;
        cropperOpen.value = true;
        companyForm.remove_logo = false; // Reset removal if new file selected
    }
};

const removeLogo = () => {
    companyForm.logo = null;
    companyForm.logo_crop = null;
    companyForm.remove_logo = true;
    logoPreview.value = null;
    originalLogoFile.value = null;
};

const handleBackgroundChange = (e: Event) => {
    const file = (e.target as HTMLInputElement).files?.[0];
    if (file) {
        cropCoords.value = null;
        originalBackgroundFile.value = file;
        imageToCrop.value = URL.createObjectURL(file);
        cropTarget.value = 'background';
        cropAspect.value = 3 / 1; // 1200/400 or any 3:1 ratio
        cropperOpen.value = true;
        companyForm.remove_background = false; // Reset removal if new file selected
    }
};

const removeBackground = () => {
    companyForm.background = null;
    companyForm.background_crop = null;
    companyForm.remove_background = true;
    backgroundPreview.value = null;
    originalBackgroundFile.value = null;
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

                <form @submit.prevent="submit" class="space-y-8">
                    <!-- Live Preview with Integrated Controls -->
                    <div class="space-y-4">
                        <Label
                            class="text-xs font-bold tracking-widest text-muted-foreground uppercase"
                        >
                            {{ __('settings.company_preview') }}
                        </Label>
                        <div
                            class="group/header relative overflow-hidden rounded-2xl border border-border bg-card shadow-2xl shadow-primary/5 transition-all hover:shadow-primary/10"
                        >
                            <CompanyHeader
                                :name="companyForm.name"
                                :logo-url="logoPreview"
                                :background-url="backgroundPreview"
                                :phone="companyForm.phone"
                                :member-since="
                                    new Date(company.created_at).getFullYear()
                                "
                                editable
                            >
                                <template #backgroundActions>
                                    <div
                                        class="group/bg absolute inset-0 z-10 flex cursor-pointer items-center justify-center bg-black/40 opacity-0 backdrop-blur-[2px] transition-all duration-300 hover:opacity-100"
                                    >
                                        <div
                                            class="flex flex-col items-center gap-3"
                                        >
                                            <div class="flex gap-4">
                                                <div
                                                    class="relative flex h-14 w-14 items-center justify-center rounded-2xl bg-white text-black shadow-xl transition-all hover:scale-110 active:scale-95"
                                                >
                                                    <UploadCloud :size="24" />
                                                    <input
                                                        type="file"
                                                        accept="image/*"
                                                        class="absolute inset-0 cursor-pointer opacity-0"
                                                        @change="
                                                            handleBackgroundChange
                                                        "
                                                    />
                                                </div>
                                                <button
                                                    v-if="backgroundPreview"
                                                    type="button"
                                                    class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white text-black shadow-xl transition-all hover:scale-110 active:scale-95"
                                                    @click.stop="
                                                        openCropper(
                                                            'background',
                                                        )
                                                    "
                                                >
                                                    <Crop :size="24" />
                                                </button>
                                                <button
                                                    v-if="backgroundPreview"
                                                    type="button"
                                                    class="flex h-14 w-14 items-center justify-center rounded-2xl bg-red-600 text-white shadow-xl transition-all hover:scale-110 active:scale-95"
                                                    @click.stop="
                                                        removeBackground
                                                    "
                                                >
                                                    <X :size="24" />
                                                </button>
                                            </div>
                                            <span
                                                class="text-[10px] font-black tracking-widest text-white uppercase"
                                                >{{
                                                    backgroundPreview
                                                        ? __(
                                                              'settings.company_background',
                                                          )
                                                        : __('ui.add_photos')
                                                }}</span
                                            >
                                        </div>
                                    </div>
                                </template>

                                <template #logoActions>
                                    <div
                                        class="absolute inset-0 -top-20 z-40 flex flex-col items-center justify-center rounded-full opacity-0 transition-opacity duration-300 group-hover/logo:opacity-100"
                                    >
                                        <div class="flex gap-1.5">
                                            <div
                                                class="relative flex h-8 w-8 cursor-pointer items-center justify-center rounded-lg bg-white text-black shadow-xl ring-1 ring-border transition-all hover:scale-110 active:scale-95"
                                            >
                                                <UploadCloud :size="14" />
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
                                                class="flex h-8 w-8 items-center justify-center rounded-lg bg-white text-black shadow-xl ring-1 ring-border transition-all hover:scale-110 active:scale-95"
                                                @click.stop="
                                                    openCropper('logo')
                                                "
                                            >
                                                <Crop :size="14" />
                                            </button>
                                            <button
                                                v-if="logoPreview"
                                                type="button"
                                                class="flex h-8 w-8 items-center justify-center rounded-lg bg-red-600 text-white shadow-xl ring-1 ring-red-400/50 transition-all hover:scale-110 active:scale-95"
                                                @click.stop="removeLogo"
                                            >
                                                <X :size="14" />
                                            </button>
                                        </div>
                                        <span
                                            class="mt-1 text-[8px] font-black tracking-widest text-white uppercase sm:text-[9px]"
                                            >{{
                                                logoPreview
                                                    ? __(
                                                          'settings.company_logo',
                                                      )
                                                    : __('ui.add_photos')
                                            }}</span
                                        >
                                    </div>
                                </template>
                            </CompanyHeader>
                        </div>
                        <div class="flex items-center gap-4 px-1">
                            <p
                                class="flex h-4 items-center gap-1.5 text-[10px] font-bold text-muted-foreground/60"
                            >
                                <UploadCloud class="size-3" />
                                {{ __('settings.company_logo_help') }}
                            </p>
                            <div class="h-1 w-1 rounded-full bg-border" />
                            <p
                                class="flex h-4 items-center gap-1.5 text-[10px] font-bold text-muted-foreground/60"
                            >
                                <MapPin class="size-3" />
                                {{ __('settings.company_background_help') }}
                            </p>
                        </div>
                        <InputError :message="companyForm.errors.logo" />
                        <InputError :message="companyForm.errors.background" />
                    </div>

                    <!-- Form Sections -->
                    <div class="space-y-8 pt-4">
                        <!-- Section Identity -->
                        <div
                            class="rounded-2xl border border-border bg-card p-6 shadow-xs"
                        >
                            <h3
                                class="mb-6 text-[10px] font-black tracking-[0.2em] text-muted-foreground/50 uppercase"
                            >
                                {{
                                    __('settings.section_identity') ||
                                    'Identité'
                                }}
                            </h3>
                            <div class="grid gap-6 md:grid-cols-2">
                                <div class="grid gap-2">
                                    <Label
                                        for="name"
                                        class="text-xs font-bold"
                                        >{{
                                            __('settings.company_name')
                                        }}</Label
                                    >
                                    <Input
                                        id="name"
                                        v-model="companyForm.name"
                                        class="h-11 rounded-xl bg-muted/30"
                                        required
                                    />
                                    <InputError
                                        :message="companyForm.errors.name"
                                    />
                                </div>
                                <div class="grid gap-2">
                                    <Label
                                        for="email"
                                        class="text-xs font-bold"
                                        >{{
                                            __('settings.company_email')
                                        }}</Label
                                    >
                                    <Input
                                        id="email"
                                        v-model="companyForm.email"
                                        type="email"
                                        class="h-11 rounded-xl bg-muted/30"
                                        required
                                    />
                                    <InputError
                                        :message="companyForm.errors.email"
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Section Location & Contact -->
                        <div
                            class="rounded-2xl border border-border bg-card p-6 shadow-xs"
                        >
                            <h3
                                class="mb-6 text-[10px] font-black tracking-[0.2em] text-muted-foreground/50 uppercase"
                            >
                                {{
                                    __('settings.section_contact') ||
                                    'Localisation & Contact'
                                }}
                            </h3>
                            <div class="grid gap-6">
                                <div class="grid gap-2">
                                    <Label
                                        for="phone"
                                        class="text-xs font-bold"
                                        >{{
                                            __('settings.company_phone')
                                        }}</Label
                                    >
                                    <Input
                                        id="phone"
                                        v-model="companyForm.phone"
                                        class="h-11 rounded-xl bg-muted/30"
                                        required
                                    />
                                    <InputError
                                        :message="companyForm.errors.phone"
                                    />
                                </div>

                                <div class="grid gap-6 md:grid-cols-2">
                                    <div class="grid gap-2">
                                        <Label
                                            for="address"
                                            class="text-xs font-bold"
                                            >{{
                                                __('settings.company_address')
                                            }}</Label
                                        >
                                        <Input
                                            id="address"
                                            v-model="companyForm.address"
                                            class="h-11 rounded-xl bg-muted/30"
                                            required
                                        />
                                        <InputError
                                            :message="
                                                companyForm.errors.address
                                            "
                                        />
                                    </div>

                                    <div class="relative grid gap-2">
                                        <Label
                                            for="city_search"
                                            class="text-xs font-bold"
                                            >{{
                                                __('settings.company_city')
                                            }}</Label
                                        >
                                        <div class="relative">
                                            <MapPin
                                                :size="14"
                                                class="absolute top-1/2 left-3 z-10 -translate-y-1/2 text-muted-foreground"
                                            />
                                            <Input
                                                id="city_search"
                                                v-model="searchTerm"
                                                :placeholder="
                                                    __(
                                                        'settings.company_city_search',
                                                    )
                                                "
                                                class="h-11 rounded-xl bg-muted/30 pl-9"
                                                @input="
                                                    searchCities(searchTerm)
                                                "
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
                                                (cities.length > 0 ||
                                                    isSearchingCities)
                                            "
                                            class="absolute top-full left-0 z-100 mt-1 w-full animate-in overflow-hidden rounded-xl border border-border bg-popover text-popover-foreground shadow-2xl fade-in-80"
                                        >
                                            <div
                                                class="max-h-60 overflow-y-auto p-2"
                                            >
                                                <div
                                                    v-for="city in cities"
                                                    :key="city.id"
                                                    class="flex cursor-pointer items-center rounded-lg px-3 py-2 text-sm transition-colors hover:bg-accent hover:text-accent-foreground"
                                                    @click="selectCity(city)"
                                                >
                                                    <span
                                                        class="mr-2 font-black text-primary"
                                                        >{{
                                                            city.zip_code
                                                        }}</span
                                                    >
                                                    <span
                                                        class="font-bold text-muted-foreground"
                                                        >{{ city.code }}</span
                                                    >
                                                </div>
                                                <div
                                                    v-if="
                                                        cities.length === 0 &&
                                                        isSearchingCities
                                                    "
                                                    class="p-4 text-center text-xs font-bold text-muted-foreground"
                                                >
                                                    {{ __('ui.loading') }}
                                                </div>
                                            </div>
                                        </div>
                                        <InputError
                                            :message="
                                                companyForm.errors.city_id
                                            "
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section Legal -->
                        <div
                            class="rounded-2xl border border-border bg-card p-6 shadow-xs"
                        >
                            <h3
                                class="mb-6 text-[10px] font-black tracking-[0.2em] text-muted-foreground/50 uppercase"
                            >
                                {{
                                    __('settings.section_legal') ||
                                    'Informations Légales'
                                }}
                            </h3>
                            <div class="grid gap-2">
                                <Label
                                    for="tva_number"
                                    class="text-xs font-bold"
                                    >{{
                                        __('settings.company_tva_number')
                                    }}</Label
                                >
                                <Input
                                    id="tva_number"
                                    v-model="companyForm.tva_number"
                                    class="h-11 rounded-xl bg-muted/30"
                                />
                                <InputError
                                    :message="companyForm.errors.tva_number"
                                />
                            </div>
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
            :default-coordinates="cropCoords"
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
