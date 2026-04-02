<template>
    <Head :title="__('vehicleAd.edit_ad')" />

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
                        @click="() => router.visit(vehicleShow.url(ad.id))"
                        :title="__('vehicleAd.back_to_ad')"
                    >
                        <ChevronLeft
                            class="h-5 w-5 text-muted-foreground transition-colors group-hover:text-primary"
                        />
                    </button>
                    <h1 class="text-3xl font-bold tracking-tight">
                        {{ __('vehicleAd.edit_ad') }}
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
                        :existing-media="images"
                        :is-processing-images="isProcessingImages"
                        :vehicle-id="ad.id"
                        :image-limit="imageLimit"
                        :mode="processingMode"
                        @toggle-feature="toggleFeature"
                        @close-processing="isProcessingImages = false"
                        @update:media-order="
                            (ids: number[]) => (form.media_order = ids)
                        "
                        @update:media-to-delete="
                            (ids: number[]) => (form.media_to_delete = ids)
                        "
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
                    <div class="mx-auto max-w-7xl px-2 py-2 lg:px-8 lg:py-4">
                        <div
                            class="flex items-center justify-between gap-2 lg:justify-between lg:gap-3"
                        >
                            <!-- Delete button (Desktop: Left, Mobile: Tool style) -->
                            <button
                                type="button"
                                class="bottom-bar-tool-btn bg-red-500/10! hover:bg-red-500/20! lg:hidden!"
                                @click="destroyAd"
                            >
                                <Trash2
                                    class="text-red-500!"
                                    :title="__('vehicleAd.delete_ad')"
                                />
                                <span class="hidden sm:inline">{{
                                    __('vehicleAd.delete_ad')
                                }}</span>
                            </button>

                            <Button
                                type="button"
                                variant="destructive"
                                class="hidden lg:flex"
                                @click="destroyAd"
                            >
                                <Trash2 class="mr-2 h-4 w-4" />
                                <span>{{ __('vehicleAd.delete_ad') }}</span>
                            </Button>

                            <!-- Actions Group (Desktop: Right, Mobile: Tools) -->
                            <div
                                class="flex flex-1 items-center justify-end gap-2 lg:flex-none lg:gap-3"
                            >
                                <button
                                    type="button"
                                    class="bottom-bar-tool-btn lg:hidden!"
                                    @click="
                                        () =>
                                            router.visit(vehicleShow.url(ad.id))
                                    "
                                >
                                    <ChevronLeft />
                                    <span class="hidden sm:inline">{{
                                        __('ui.cancel')
                                    }}</span>
                                </button>

                                <button
                                    type="button"
                                    class="bottom-bar-tool-btn lg:hidden!"
                                    :disabled="form.processing"
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
                                        @click="
                                            () =>
                                                router.visit(
                                                    vehicleShow.url(ad.id),
                                                )
                                        "
                                    >
                                        <ChevronLeft class="mr-2 h-4 w-4" />
                                        <span>{{ __('ui.cancel') }}</span>
                                    </Button>
                                    <Button
                                        type="button"
                                        variant="secondary"
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

                                <Button
                                    type="button"
                                    class="h-12 flex-1 cursor-pointer bg-primary/90 font-bold text-foreground shadow-lg active:scale-95 lg:h-10 lg:w-auto lg:flex-none"
                                    :disabled="form.processing"
                                    @click="submit('active')"
                                >
                                    <Loader2
                                        v-if="form.processing"
                                        class="mr-2 h-4 w-4 animate-spin"
                                    />
                                    {{
                                        form.processing
                                            ? __('vehicleAd.processing')
                                            : __('vehicleAd.save_publish')
                                    }}
                                </Button>
                            </div>
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
    Trash2,
} from 'lucide-vue-next';
import { computed, nextTick, onMounted, onUnmounted, ref, watch } from 'vue';
import AppContent from '@/components/AppContent.vue';
import { Button } from '@/components/ui/button';
import VehicleAdFormProgressNav, {
    type FormSection,
} from '@/components/VehicleAds/VehicleAdFormProgressNav.vue';
import { SECTION_IDS } from '@/components/VehicleAds/VehicleAdFormSectionIds';
import VehicleAdFormSections from '@/components/VehicleAds/VehicleAdFormSections.vue';
import { useTranslation } from '@/composables/useTranslation';
import {
    show as vehicleShow,
    update as vehicleUpdate,
    destroy as vehicleDestroy,
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

const props = defineProps<{
    ad: any;
    brands: any[];
    fuelTypes: any[];
    bodyTypes: any[];
    transmissionTypes: any[];
    exteriorColors: any[];
    interiorColors: any[];
    interiorTypes: any[];
    euroNorms: any[];
    featureCategories: FeatureCategoryOption[];
    selectedFeatureIds: string[];
    images: any[];
    imageLimit: number;
}>();

const formatDate = (date: any): string => {
    return date ? new Date(date).toISOString().split('T')[0] : '';
};

const extractFeatureIds = (value: unknown): string[] => {
    const source = Array.isArray(value)
        ? value
        : typeof value === 'object' &&
            value !== null &&
            Array.isArray((value as { data?: unknown[] }).data)
          ? ((value as { data: unknown[] }).data ?? [])
          : [];

    return source
        .map((item) => {
            if (typeof item === 'string' || typeof item === 'number') {
                return String(item);
            }
            if (item && typeof item === 'object') {
                const feature = item as {
                    id?: number | string;
                    feature_id?: number | string;
                    pivot?: { feature_id?: number | string };
                };
                const featureId =
                    feature.id ??
                    feature.feature_id ??
                    feature.pivot?.feature_id;
                if (featureId !== undefined && featureId !== null) {
                    return String(featureId);
                }
            }
            return null;
        })
        .filter((featureId): featureId is string => featureId !== null);
};

const getInitialFeatureIds = (): string[] => {
    const source =
        props.selectedFeatureIds?.length > 0
            ? props.selectedFeatureIds
            : extractFeatureIds(props.ad.features);
    return Array.from(new Set(source.map((featureId) => String(featureId))));
};

const form = useForm({
    status: props.ad.status ?? 'draft',
    brand_id: props.ad.brand_id ? String(props.ad.brand_id) : '',
    model_id: props.ad.model_id ? String(props.ad.model_id) : '',
    vehicle_version_id: props.ad.vehicle_version_id
        ? String(props.ad.vehicle_version_id)
        : '',
    vehicle_version_name: props.ad.vehicle_version_name ?? '',
    price: props.ad.price ?? '',
    vat_mode: props.ad.vat_mode ?? 'tvac',
    mileage: props.ad.mileage ?? '',
    first_registration_date: formatDate(props.ad.first_registration_date),
    purchase_date: formatDate(props.ad.purchase_date),
    vin: props.ad.vin ?? '',
    registration_number: props.ad.registration_number ?? '',
    fuel_type_id: props.ad.fuel_type_id ? String(props.ad.fuel_type_id) : '',
    body_type_id: props.ad.body_type_id ? String(props.ad.body_type_id) : '',
    transmission_type_id: props.ad.transmission_type_id
        ? String(props.ad.transmission_type_id)
        : '',
    euro_norm_id: props.ad.euro_norm_id ? String(props.ad.euro_norm_id) : '',
    power_kw: props.ad.power_kw ?? '',
    engine_displacement: props.ad.engine_displacement ?? '',
    cylinder_count: props.ad.cylinder_count ?? '',
    fuel_consumption_avg: props.ad.fuel_consumption_avg ?? '',
    weight_kg: props.ad.weight_kg ?? '',
    co2_emission: props.ad.co2_emission ?? '',
    doors: props.ad.doors ? String(props.ad.doors) : '',
    seats: props.ad.seats ? String(props.ad.seats) : '',
    previous_owner: props.ad.previous_owner ?? '',
    gear_count: props.ad.gear_count ?? '',
    exterior_color_id: props.ad.exterior_color_id
        ? String(props.ad.exterior_color_id)
        : '',
    exterior_color_metalised: props.ad.exterior_color_metalised ?? false,
    interior_color_id: props.ad.interior_color_id
        ? String(props.ad.interior_color_id)
        : '',
    interior_type_id: props.ad.interior_type_id
        ? String(props.ad.interior_type_id)
        : '',
    is_damaged: props.ad.is_damaged ?? false,
    has_accident: props.ad.has_accident ?? false,
    complete_maintenance_book: props.ad.complete_maintenance_book ?? false,
    non_smoker: props.ad.non_smoker ?? false,
    technical_inspection_status: props.ad.technical_inspection_status ?? false,
    description: props.ad.description ?? '',
    features: getInitialFeatureIds(),
    images: [] as File[],
    media_order: [] as number[],
    media_to_delete: [] as number[],
});

const models = ref<any[]>([]);
const versions = ref<any[]>([]);
const featureSelectionReady = ref(false);

const toggleFeature = (
    id: string,
    checked: boolean | 'indeterminate',
): void => {
    if (!featureSelectionReady.value) return;
    if (checked === true) {
        if (!form.features.includes(id)) form.features.push(id);
    } else {
        form.features = form.features.filter((fId: string) => fId !== id);
    }
};

const loadModels = async (brandId: string): Promise<void> => {
    if (!brandId) return;
    const { data } = await axios.get('/vehicle-models', {
        params: { brand_id: brandId },
    });
    models.value = data;
};

const loadVersions = async (modelId: string): Promise<void> => {
    if (!modelId) return;
    const { data } = await axios.get('/vehicle-versions', {
        params: { model_id: modelId },
    });
    versions.value = data;
};

onMounted(async () => {
    form.features = getInitialFeatureIds();
    await nextTick();
    featureSelectionReady.value = true;

    if (form.brand_id) loadModels(form.brand_id);
    if (form.model_id) loadVersions(form.model_id);
});

watch(
    () => form.brand_id,
    async (brandId, oldBrandId) => {
        if (oldBrandId && brandId !== oldBrandId) {
            form.model_id = '';
            form.vehicle_version_id = '';
            models.value = [];
            versions.value = [];
        }
        if (brandId) await loadModels(brandId);
    },
);

watch(
    () => form.model_id,
    async (modelId, oldModelId) => {
        if (oldModelId && modelId !== oldModelId) {
            form.vehicle_version_id = '';
            versions.value = [];
        }
        if (modelId) await loadVersions(modelId);
    },
);

const submit = (status: 'active' | 'draft'): void => {
    form.status = status;
    form.transform((data) => ({
        ...data,
        _method: 'PUT',
    })).post(vehicleUpdate.url(props.ad.id));
};

const destroyAd = (): void => {
    if (confirm(__('ui.confirm_delete'))) {
        router.delete(vehicleDestroy.url(props.ad.id));
    }
};

const page = usePage<ExtendedPageProps>();
const isProcessingImages = ref(Boolean(page.props.flash?.processing_images));
const processingMode = computed(() =>
    page.props.flash?.created ? 'create' : 'edit',
);

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
    const photos = [...form.images, ...(props.images ?? [])];
    const description = [form.description];

    const pct = (filled: any[], total: number): number =>
        total === 0
            ? 0
            : Math.round((filled.filter(Boolean).length / total) * 100);

    return {
        general: pct(general, general.length),
        technical: pct(technical, technical.length),
        aesthetics: pct(aesthetics, aesthetics.length),
        equipment: Math.min(100, ((equipment.length / 3) * 100) | 0),
        photos: Math.min(100, ((photos.length / 3) * 100) | 0),
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
