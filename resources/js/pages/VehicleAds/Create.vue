<template>
    <Head :title="__('vehicleAd.create_ad')" />

    <AppContent>
        <div
            class="mx-auto flex w-full flex-col gap-8 py-4 pb-48 md:py-8 lg:flex-row lg:items-start lg:pb-8"
        >
            <!-- Progress sidebar -->
            <VehicleAdFormProgressNav
                :sections="progressSections"
                :active-section="activeSection"
                :global-completion="globalCompletion"
                desktop-only
            />

            <!-- Main content -->
            <main class="flex min-w-0 flex-1 flex-col gap-8">
                <div class="flex items-center gap-4">
                    <button
                        class="group flex h-10 w-10 shrink-0 cursor-pointer items-center justify-center rounded-xl border border-border/50 bg-background/50 transition-all hover:border-primary/30 hover:bg-background hover:shadow-md"
                        type="button"
                        @click="() => router.visit(vehiclesIndex.url())"
                        :title="__('vehicleAd.back_to_ads')"
                    >
                        <ChevronLeft
                            class="h-5 w-5 text-muted-foreground transition-colors group-hover:text-primary"
                        />
                    </button>
                    <h1 class="text-3xl font-bold tracking-tight">
                        {{ __('vehicleAd.create_new_ad') }}
                    </h1>
                </div>

                <form @submit.prevent="submit('active')" class="space-y-8">
                    <VehicleAdFormSections
                        :form="form"
                        :brands="brands"
                        :models="models"
                        :fuel-types="fuelTypes"
                        :body-types="bodyTypes"
                        :transmission-types="transmissionTypes"
                        :exterior-colors="exteriorColors"
                        :interior-colors="interiorColors"
                        :interior-types="interiorTypes"
                        :euro-norms="euroNorms"
                        :feature-categories="featureCategories"
                        :existing-media="[]"
                        :is-processing-images="isProcessingImages"
                        :vehicle-id="null"
                        :image-limit="imageLimit"
                        mode="create"
                        @toggle-feature="toggleFeature"
                        @close-processing="isProcessingImages = false"
                    />
                </form>
            </main>
        </div>

        <template #sticky-bottom>
            <div class="flex w-full flex-col lg:gap-0">
                <VehicleAdFormProgressNav
                    :sections="progressSections"
                    :active-section="activeSection"
                    :global-completion="globalCompletion"
                    mobile-only
                />
                <!-- Actions Bar (simplified for dock on mobile) -->
                <div
                    class="dark relative z-50 transition-all lg:border-t lg:border-border/40 lg:bg-background lg:shadow-[0_-10px_30px_-15px_rgba(0,0,0,0.3)] lg:backdrop-blur-md"
                >
                    <div class="mx-auto max-w-7xl lg:px-8 lg:py-4">
                        <div
                            class="flex items-center justify-between gap-2 lg:justify-end lg:gap-3"
                        >
                            <div class="flex items-center gap-2">
                                <button
                                    type="button"
                                    class="bottom-bar-tool-btn cursor-pointer lg:hidden!"
                                    :title="__('ui.cancel')"
                                    @click="
                                        () => router.visit(vehiclesIndex.url())
                                    "
                                >
                                    <ChevronLeft />
                                    <span class="hidden sm:inline">{{
                                        __('ui.cancel')
                                    }}</span>
                                </button>
                                <button
                                    type="button"
                                    class="bottom-bar-tool-btn cursor-pointer lg:hidden!"
                                    :disabled="form.processing"
                                    :title="__('vehicleAd.save_draft')"
                                    @click.prevent="submit('draft')"
                                >
                                    <Loader2
                                        v-if="form.processing"
                                        class="animate-spin"
                                    />
                                    <template v-else>
                                        <FileText />
                                        <span class="hidden sm:inline">{{
                                            __('vehicleAd.save_draft')
                                        }}</span>
                                    </template>
                                </button>

                                <!-- Desktop Secondary Buttons -->
                                <div class="hidden items-center gap-3 lg:flex">
                                    <Button
                                        type="button"
                                        variant="outline"
                                        class="cursor-pointer"
                                        @click="
                                            () =>
                                                router.visit(
                                                    vehiclesIndex.url(),
                                                )
                                        "
                                    >
                                        <ChevronLeft class="mr-2 h-4 w-4" />
                                        <span>{{ __('ui.cancel') }}</span>
                                    </Button>
                                    <Button
                                        type="button"
                                        variant="secondary"
                                        class="cursor-pointer"
                                        :disabled="form.processing"
                                        @click.prevent="submit('draft')"
                                    >
                                        <Loader2
                                            v-if="form.processing"
                                            class="mr-2 h-4 w-4 animate-spin"
                                        />
                                        <template v-else>
                                            <FileText class="mr-2 h-4 w-4" />
                                            <span>{{
                                                __('vehicleAd.save_draft')
                                            }}</span>
                                        </template>
                                    </Button>
                                </div>
                            </div>

                            <Button
                                type="submit"
                                class="h-12 flex-1 cursor-pointer font-bold text-foreground shadow-lg active:scale-95 lg:h-11 lg:flex-none lg:px-8"
                                :disabled="form.processing"
                                @click.prevent="submit('active')"
                            >
                                <Loader2
                                    v-if="form.processing"
                                    class="mr-2 h-4 w-4 animate-spin"
                                />
                                <template v-else>
                                    {{ __('vehicleAd.save_publish') }}
                                </template>
                            </Button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </AppContent>
</template>

<script setup lang="ts">
import { Head, useForm, router, usePage } from '@inertiajs/vue3';
import axios from 'axios';
import {
    ChevronLeft,
    Info,
    Wrench,
    Palette,
    ListChecks,
    Image,
    FileText,
    Loader2,
} from 'lucide-vue-next';
import { computed, onMounted, onUnmounted, ref, watch } from 'vue';
import AppContent from '@/components/AppContent.vue';
import { Button } from '@/components/ui/button';
import VehicleAdFormProgressNav, {
    type FormSection,
} from '@/components/VehicleAds/VehicleAdFormProgressNav.vue';
import { SECTION_IDS } from '@/components/VehicleAds/VehicleAdFormSectionIds';
import VehicleAdFormSections from '@/components/VehicleAds/VehicleAdFormSections.vue';
import { useTranslation } from '@/composables/useTranslation';
import {
    index as vehiclesIndex,
    store as vehicleStore,
} from '@/routes/vehicles';
import type { ExtendedPageProps } from '@/types/inertia';

const { __ } = useTranslation();

type FeatureOption = {
    id: number | string;
    code?: string;
    key?: string;
};

type FeatureCategoryOption = {
    id: number | string;
    code?: string;
    key?: string;
    features: FeatureOption[];
};

defineProps<{
    brands: any[];
    fuelTypes: any[];
    bodyTypes: any[];
    transmissionTypes: any[];
    exteriorColors: any[];
    interiorColors: any[];
    interiorTypes: any[];
    euroNorms: any[];
    featureCategories: FeatureCategoryOption[];
    imageLimit: number;
}>();

const form = useForm({
    status: 'draft',
    brand_id: '',
    model_id: '',
    vehicle_version_id: '',
    vehicle_version_name: '',
    price: '',
    vat_mode: 'tvac',
    mileage: '',
    first_registration_date: '',
    purchase_date: '',
    vin: '',
    registration_number: '',
    fuel_type_id: '',
    body_type_id: '',
    transmission_type_id: '',
    euro_norm_id: '',
    power_kw: '',
    engine_displacement: '',
    cylinder_count: '',
    fuel_consumption_avg: '',
    weight_kg: '',
    co2_emission: '',
    doors: '',
    seats: '',
    previous_owner: '',
    gear_count: '',
    exterior_color_id: '',
    exterior_color_metalised: false,
    interior_color_id: '',
    interior_type_id: '',
    is_damaged: false,
    has_accident: false,
    complete_maintenance_book: false,
    non_smoker: false,
    technical_inspection_status: false,
    description: '',
    features: [] as string[],
    images: [] as File[],
});

const models = ref<any[]>([]);
const versions = ref<any[]>([]);

const toggleFeature = (
    id: string,
    checked: boolean | 'indeterminate',
): void => {
    if (checked === true) {
        if (!form.features.includes(id)) form.features.push(id);
    } else {
        form.features = form.features.filter((fId) => fId !== id);
    }
};

watch(
    () => form.brand_id,
    async (brandId) => {
        form.model_id = '';
        form.vehicle_version_id = '';
        models.value = [];
        versions.value = [];
        if (!brandId) return;
        const { data } = await axios.get('/vehicle-models', {
            params: { brand_id: brandId },
        });
        models.value = data;
    },
);

watch(
    () => form.model_id,
    async (modelId) => {
        form.vehicle_version_id = '';
        versions.value = [];
        if (!modelId) return;
        const { data } = await axios.get('/vehicle-versions', {
            params: { model_id: modelId },
        });
        versions.value = data;
    },
);

const submit = (status: 'active' | 'draft'): void => {
    form.status = status;
    form.transform((data: Record<string, any>) => {
        const transformed: Record<string, any> = {};
        for (const [key, value] of Object.entries(data)) {
            transformed[key] = value === '' ? null : value;
        }
        return transformed;
    }).post(vehicleStore.url());
};

const page = usePage<ExtendedPageProps>();
const isProcessingImages = ref(Boolean(page.props.flash?.processing_images));

watch(
    () => page.props.flash?.processing_images,
    (val) => {
        if (val) {
            isProcessingImages.value = true;
        }
    },
);

// ---- Progress tracking ----
const activeSection = ref<string>(SECTION_IDS.general);

const sectionCompletion = computed(() => {
    const general = [
        form.brand_id,
        form.model_id,
        form.vehicle_version_name,
        form.first_registration_date,
        form.price,
        form.mileage,
    ];
    const technical = [
        form.body_type_id,
        form.fuel_type_id,
        form.transmission_type_id,
    ];
    const aesthetics = [form.exterior_color_id];
    const equipment = form.features;
    const photos = form.images;
    const description = [form.description];

    const pct = (filled: any[], total: number): number =>
        total === 0
            ? 0
            : Math.round((filled.filter(Boolean).length / total) * 100);

    return {
        general: pct(general, general.length),
        technical: pct(technical, technical.length),
        aesthetics: pct(aesthetics, aesthetics.length),
        equipment: Math.min(100, (equipment.length / 3) * 100) | 0,
        photos: Math.min(100, (photos.length / 3) * 100) | 0,
        description: pct(description, description.length),
    };
});

const progressSections = computed<FormSection[]>(() => [
    {
        id: SECTION_IDS.general,
        label: __('vehicleAd.general_info'),
        icon: Info,
        completion: sectionCompletion.value.general,
    },
    {
        id: SECTION_IDS.technical,
        label: __('vehicleAd.technical_specs'),
        icon: Wrench,
        completion: sectionCompletion.value.technical,
    },
    {
        id: SECTION_IDS.aesthetics,
        label: __('vehicleAd.aesthetics_condition'),
        icon: Palette,
        completion: sectionCompletion.value.aesthetics,
    },
    {
        id: SECTION_IDS.equipment,
        label: __('vehicleAd.equipment_options'),
        icon: ListChecks,
        completion: sectionCompletion.value.equipment,
    },
    {
        id: SECTION_IDS.photos,
        label: __('vehicleAd.photos'),
        icon: Image,
        completion: sectionCompletion.value.photos,
    },
    {
        id: SECTION_IDS.description,
        label: __('vehicleAd.detailed_description'),
        icon: FileText,
        completion: sectionCompletion.value.description,
    },
]);

const globalCompletion = computed(() => {
    const vals = Object.values(sectionCompletion.value);
    return Math.round(vals.reduce((a, b) => a + b, 0) / vals.length);
});

// Intersection observer for active section
let observer: IntersectionObserver | null = null;

onMounted(() => {
    const sectionIds = Object.values(SECTION_IDS);
    const options: IntersectionObserverInit = {
        root: null,
        rootMargin: '-40% 0px -50% 0px',
        threshold: 0,
    };
    observer = new IntersectionObserver((entries) => {
        for (const entry of entries) {
            if (entry.isIntersecting) {
                activeSection.value = entry.target.id;
            }
        }
    }, options);

    sectionIds.forEach((id) => {
        const el = document.getElementById(id);
        if (el) observer?.observe(el);
    });
});

onUnmounted(() => {
    observer?.disconnect();
});
</script>
