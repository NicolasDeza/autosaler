<template>
    <Head :title="seoTitle">
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

    <AppContent>
        <div
            class="mx-auto flex w-full flex-col gap-6 py-4 md:flex-row md:items-start md:py-8"
        >
            <!-- Filters Sidebar -->
            <FilterSidebar
                v-model:open="isFilterSheetOpen"
                v-model:models="models"
                v-model:form="form"
                :brands="brands"
                :fuel-types="fuelTypes"
                :body-types="bodyTypes"
                :transmission-types="transmissionTypes"
                :exterior-colors="exteriorColors"
                :euro-norms="euroNorms"
                :interior-colors="interiorColors"
                :interior-types="interiorTypes"
                :features="features"
                :companies="companies"
                :badge-count="activeFilterCount"
                @reset-filters="resetFilters"
            />

            <!-- Main Content -->
            <main class="flex min-w-0 flex-1 flex-col gap-6">
                <h1 class="sr-only">
                    Annonces de véhicules d'occasion et neufs en Belgique
                </h1>

                <!-- Company Info Card -->
                <Transition
                    enter-from-class="opacity-0 translate-y-2"
                    enter-active-class="transition-all duration-300 ease-out"
                    enter-to-class="opacity-100 translate-y-0"
                    leave-from-class="opacity-100 translate-y-0"
                    leave-active-class="transition-all duration-300 ease-in"
                    leave-to-class="opacity-0 -translate-y-2"
                >
                    <CompanyInfoCard
                        v-if="activeCompany"
                        :company="activeCompany"
                        class="mb-6"
                    />
                </Transition>

                <!-- Results Header & Active Filters (Compact) -->
                <header
                    class="flex flex-col gap-3 rounded-xl border border-border/30 bg-card/40 p-3 shadow-sm backdrop-blur-sm sm:gap-4 sm:px-6 sm:py-4"
                >
                    <div class="flex items-center justify-between gap-3">
                        <div class="flex min-w-0 items-center gap-2 sm:gap-4">
                            <!-- Mobile Icon-based Count -->
                            <div class="flex items-center gap-1.5 sm:hidden">
                                <CarIcon class="size-5 text-primary" />
                                <span
                                    class="text-lg font-black text-foreground"
                                >
                                    {{ ads.total }}
                                </span>
                            </div>

                            <!-- Desktop Text-based Count -->
                            <h2
                                class="hidden truncate text-xl font-black text-foreground sm:inline-block sm:text-2xl"
                            >
                                {{ getResultsFoundLabel(ads.total) }}
                            </h2>

                            <!-- Results Range Badge -->
                            <span
                                v-if="ads.total > 0"
                                class="shrink-0 rounded-full bg-primary/10 px-2 py-0.5 text-[9px] font-black tracking-tighter text-primary ring-1 ring-primary/20 sm:text-[10px]"
                            >
                                {{ ads.from }}-{{ ads.to }} / {{ ads.total }}
                            </span>
                        </div>

                        <div
                            class="flex flex-1 shrink-0 items-center justify-end gap-2 sm:gap-3 lg:flex-none"
                        >
                            <div class="group relative hidden lg:block lg:w-40">
                                <Search
                                    class="absolute top-1/2 left-3 h-4 w-4 -translate-y-1/2 text-muted-foreground/60 transition-colors group-focus-within:text-primary"
                                />
                                <Input
                                    v-model="form.search"
                                    :placeholder="__('vehicleAd.search')"
                                    autocomplete="off"
                                    class="h-9 border-border/20 bg-background/30 pl-10 text-sm transition-all focus-visible:bg-background focus-visible:ring-primary/20"
                                />
                            </div>

                            <span
                                class="hidden text-[10px] font-bold tracking-widest text-muted-foreground uppercase lg:inline-block"
                            >
                                {{ __('ui.sorting.sort_by') }}
                            </span>
                            <SortSelect
                                v-model="form.sort"
                                class="h-9 w-full max-w-40 text-[10px] sm:text-sm"
                            />
                        </div>
                    </div>

                    <div class="group relative block w-full lg:hidden">
                        <Search
                            class="absolute top-1/2 left-3 h-4 w-4 -translate-y-1/2 text-muted-foreground/60 transition-colors group-focus-within:text-primary"
                        />
                        <Input
                            v-model="form.search"
                            :placeholder="__('vehicleAd.search')"
                            autocomplete="off"
                            class="h-9 border-border/20 bg-background/30 pl-10 text-base transition-all focus-visible:bg-background focus-visible:ring-primary/20 md:text-sm"
                        />
                    </div>

                    <ActiveFilters
                        :form="form"
                        :brands="brands"
                        :fuel-types="fuelTypes"
                        :body-types="bodyTypes"
                        :transmission-types="transmissionTypes"
                        :exterior-colors="exteriorColors"
                        :euro-norms="euroNorms"
                        :interior-colors="interiorColors"
                        :interior-types="interiorTypes"
                        :features="features"
                        :models="models"
                        :current-year="currentYear"
                        class="hidden border-t border-border/10 sm:block"
                        @reset-all="resetFilters"
                        @update-filter="updateFilter"
                        @update-price="onPriceChange"
                        @update-year="onYearChange"
                    />
                </header>

                <!-- Vehicles List -->
                <Transition
                    mode="out-in"
                    enter-from-class="opacity-0 translate-y-2"
                    enter-active-class="transition-all duration-300 ease-out"
                    enter-to-class="opacity-100 translate-y-0"
                    leave-from-class="opacity-100 translate-y-0"
                    leave-active-class="transition-all duration-300 ease-in"
                    leave-to-class="opacity-0 -translate-y-2"
                >
                    <!-- Vehicles List -->
                    <div
                        v-if="ads.data.length"
                        key="results-list"
                        class="relative flex w-full min-w-0 flex-col gap-4 transition-opacity duration-300"
                        :class="{
                            'pointer-events-none opacity-50': isProcessing,
                        }"
                    >
                        <TransitionGroup
                            name="list"
                            tag="div"
                            class="flex w-full flex-col gap-4"
                        >
                            <VehicleAdCard
                                v-for="ad in ads.data"
                                :key="ad.id"
                                :ad="ad"
                                variant="list"
                            />
                        </TransitionGroup>
                    </div>

                    <Card
                        v-else
                        key="no-results"
                        class="flex flex-col items-center justify-center space-y-8 rounded-xl border-border/50 bg-card py-24 text-center shadow-lg"
                    >
                        <CardContent
                            class="flex flex-col items-center space-y-6"
                        >
                            <div class="relative">
                                <AppLogoIcon
                                    class="h-24 w-auto drop-shadow-xl"
                                />
                                <div
                                    class="absolute -right-2 -bottom-2 flex h-9 w-9 items-center justify-center rounded-full bg-background shadow-lg ring-2 ring-border"
                                >
                                    <Search class="h-5 w-5 text-primary" />
                                </div>
                            </div>

                            <div class="max-w-sm space-y-3">
                                <CardTitle
                                    class="text-2xl font-black tracking-tight text-foreground"
                                >
                                    {{ __('vehicleAd.no_vehicles_found') }}
                                </CardTitle>
                                <CardDescription
                                    class="text-base leading-relaxed"
                                >
                                    {{ __('vehicleAd.try_modifying_filters') }}
                                </CardDescription>
                            </div>

                            <Button
                                variant="default"
                                class="group h-11 rounded-full px-10 text-base font-bold shadow-xl transition-all hover:shadow-2xl active:scale-95"
                                @click="resetFilters"
                            >
                                <RefreshCw
                                    class="mr-2 h-5 w-5 transition-transform group-hover:rotate-180"
                                />
                                {{ __('vehicleAd.reset_filters') }}
                            </Button>
                        </CardContent>
                    </Card>
                </Transition>

                <!-- Pagination -->
                <AppPagination
                    :pagination="ads"
                    v-model:per-page="form.per_page"
                    @update:page="handlePageChange"
                />
            </main>
        </div>

        <template #sticky-bottom>
            <div class="lg:hidden">
                <div class="flex w-full items-center gap-2">
                    <button
                        class="bottom-bar-tool-btn w-full"
                        type="button"
                        @click="isFilterSheetOpen = !isFilterSheetOpen"
                    >
                        <SlidersHorizontal />
                        <span>{{ __('ui.filters') }}</span>
                        <span
                            v-if="activeFilterCount > 0"
                            class="flex size-5! items-center justify-center rounded-full bg-primary text-[10px]! font-bold text-white! shadow-sm ring-1 ring-primary/20"
                        >
                            {{ activeFilterCount }}
                        </span>
                    </button>
                </div>
            </div>
        </template>
    </AppContent>
</template>

<script setup lang="ts">
import { router, Head } from '@inertiajs/vue3';
import { CarIcon, RefreshCw, Search, SlidersHorizontal } from 'lucide-vue-next';
import { ref, watch, onUnmounted, computed } from 'vue';

import AppContent from '@/components/AppContent.vue';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import AppPagination from '@/components/AppPagination.vue';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardTitle,
} from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import ActiveFilters from '@/components/VehicleAds/ActiveFilters.vue';
import CompanyInfoCard from '@/components/VehicleAds/CompanyInfoCard.vue';
import FilterSidebar from '@/components/VehicleAds/FilterSidebar.vue';
import SortSelect from '@/components/VehicleAds/SortSelect.vue';
import VehicleAdCard from '@/components/VehicleAds/VehicleAdCard.vue';
import { useTranslation } from '@/composables/useTranslation';
import vehiclesRoutes from '@/routes/vehicles';

// ── Props ───────────────────────────────────────────────────────
interface PaginationData {
    data: any[];
    total: number;
    current_page: number;
    last_page: number;
    from: number;
    to: number;
    per_page: number;
    [key: string]: any;
}

const props = defineProps<{
    ads: PaginationData;
    brands?: any[];
    fuelTypes?: any[];
    bodyTypes?: any[];
    transmissionTypes?: any[];
    exteriorColors?: any[];
    euroNorms?: any[];
    interiorColors?: any[];
    interiorTypes?: any[];
    features?: any[];
    companies?: any[];
    activeCompany?: any;
    filters?: Record<string, any>;
}>();

const isFilterSheetOpen = ref(false);

// ── Form state ──────────────────────────────────────────────────

const currentYear = new Date().getFullYear();
const f = props.filters || {};
const toArr = (v: any): string[] =>
    v ? (Array.isArray(v) ? v.map(String) : [String(v)]) : [];

const { __ } = useTranslation();
const seoTitle = "Annonces de véhicules d'occasion et neufs";
const seoDescription =
    "Parcourez les annonces auto en Belgique sur AutoSaler. Filtrez par marque, prix, kilométrage ou carburant, puis contactez rapidement un concessionnaire.";

const getResultsFoundLabel = (count: number): string => {
    return count <= 1
        ? __('vehicleAd.results_found_singular', { count })
        : __('vehicleAd.results_found_plural', { count });
};

interface FilterForm {
    brand_id: string;
    model_id: string;
    min_price: number;
    max_price: number;
    min_year: number;
    max_year: number;
    max_mileage: string;
    fuel_types: string[];
    body_types: string[];
    transmission_types: string[];
    exterior_color_id: string;
    euro_norm_id: string;
    interior_color_id: string;
    interior_type_id: string;
    doors: string;
    seats: string;
    is_damaged: boolean | null;
    has_accident: boolean | null;
    complete_maintenance_book: boolean | null;
    non_smoker: boolean | null;
    city: string;
    city_id: string;
    company_id: string;
    per_page: string;
    sort: string;
    version: string;
    min_power: string;
    max_power: string;
    power_unit: 'kw' | 'ch';
    features: string[];
    favorites_only: boolean;
    search: string;
}

const form = ref<FilterForm>({
    brand_id: f.brand_id ? String(f.brand_id) : 'all',
    company_id: f.company_id ? String(f.company_id) : 'all',
    model_id: f.model_id ? String(f.model_id) : 'all',
    min_price: f.min_price ? Number(f.min_price) : 0,
    max_price: f.max_price ? Number(f.max_price) : 200000,
    min_year: f.min_year ? Number(f.min_year) : 1980,
    max_year: f.max_year ? Number(f.max_year) : currentYear,
    max_mileage: f.max_mileage ? String(f.max_mileage) : 'all',
    fuel_types: toArr(f.fuel_types),
    body_types: toArr(f.body_types),
    transmission_types: toArr(f.transmission_types),
    exterior_color_id: f.exterior_color_id
        ? String(f.exterior_color_id)
        : 'all',
    euro_norm_id: f.euro_norm_id ? String(f.euro_norm_id) : 'all',
    interior_color_id: f.interior_color_id
        ? String(f.interior_color_id)
        : 'all',
    interior_type_id: f.interior_type_id ? String(f.interior_type_id) : 'all',
    doors: f.doors ? String(f.doors) : 'all',
    seats: f.seats ? String(f.seats) : 'all',
    is_damaged:
        f.is_damaged !== undefined
            ? f.is_damaged === '1' || f.is_damaged === true
                ? true
                : null
            : null,
    has_accident:
        f.has_accident !== undefined
            ? f.has_accident === '1' || f.has_accident === true
                ? true
                : null
            : null,
    complete_maintenance_book:
        f.complete_maintenance_book !== undefined
            ? f.complete_maintenance_book === '1' ||
              f.complete_maintenance_book === true
                ? true
                : null
            : null,
    non_smoker:
        f.non_smoker !== undefined
            ? f.non_smoker === '1' || f.non_smoker === true
                ? true
                : null
            : null,
    city: f.city ? String(f.city) : '',
    city_id: f.city_id ? String(f.city_id) : '',
    per_page: f.per_page ? String(f.per_page) : '15',
    sort: typeof f.sort === 'string' ? f.sort : 'latest',
    version: f.version ? String(f.version) : '',
    min_power: f.min_power ? String(f.min_power) : '',
    max_power: f.max_power ? String(f.max_power) : '',
    power_unit: f.power_unit || 'ch',
    features: toArr(f.features),
    favorites_only: f.favorites_only === '1' || f.favorites_only === true,
    search: f.search ? String(f.search) : '',
});

const activeFilterCount = computed(() => {
    const v = form.value;
    const simpleFilters = [
        v.brand_id !== 'all',
        v.company_id !== 'all' && !!v.company_id,
        v.model_id !== 'all',
        !!v.city,
        v.max_mileage !== 'all',
        v.exterior_color_id !== 'all',
        v.euro_norm_id !== 'all',
        v.interior_color_id !== 'all',
        v.interior_type_id !== 'all',
        v.doors !== 'all',
        v.seats !== 'all',
        !!v.version,
        v.is_damaged === true,
        v.has_accident === true,
        v.complete_maintenance_book === true,
        v.non_smoker === true,
        v.favorites_only === true,
        Number(v.min_price) > 0 || Number(v.max_price) < 200000,
        Number(v.min_year) > 1980 || Number(v.max_year) < currentYear,
        !!(v.min_power || v.max_power),
        !!v.search,
    ].filter(Boolean).length;

    const multiFilters = [
        v.fuel_types,
        v.body_types,
        v.transmission_types,
        v.features,
    ].reduce((acc, curr) => acc + (curr?.length || 0), 0);

    return simpleFilters + multiFilters;
});

const models = ref<any[]>([]);
const isProcessing = ref(false);

const startFinishListeners = [
    router.on('start', () => (isProcessing.value = true)),
    router.on('finish', () => (isProcessing.value = false)),
];

onUnmounted(() => {
    startFinishListeners.forEach((stop) => stop());
});

const onYearChange = (values: number[] | undefined) => {
    if (!values) return;
    form.value.min_year = values[0];
    form.value.max_year = values[1];
};

const onPriceChange = (values: number[] | undefined) => {
    if (!values) return;
    form.value.min_price = values[0];
    form.value.max_price = values[1];
};

const updateFilter = (key: string, value: any) => {
    (form.value as any)[key] = value;
};

// ── Apply filters (debounced) ───────────────────────────────────
// ── Helper: Smooth Scroll to Top ─────────────────────────────
const scrollToTop = () => {
    window.scrollTo({
        top: 0,
        behavior: 'smooth',
    });
};

// ── Apply filters (debounced) ───────────────────────────────────
let timeoutId: ReturnType<typeof setTimeout>;

const getFilterParams = () => {
    const q: Record<string, any> = {};
    const v = form.value;

    if (v.brand_id && v.brand_id !== 'all') q.brand_id = v.brand_id;
    if (v.company_id && v.company_id !== 'all') q.company_id = v.company_id;
    if (v.model_id && v.model_id !== 'all') q.model_id = v.model_id;
    if (v.min_price > 0) q.min_price = v.min_price;
    if (v.max_price < 200000) q.max_price = v.max_price;
    if (v.min_year > 1980) q.min_year = v.min_year;
    if (v.max_year < currentYear) q.max_year = v.max_year;
    if (v.max_mileage && v.max_mileage !== 'all') q.max_mileage = v.max_mileage;
    if (v.fuel_types.length) q.fuel_types = [...v.fuel_types];
    if (v.body_types.length) q.body_types = [...v.body_types];
    if (v.transmission_types.length)
        q.transmission_types = [...v.transmission_types];
    if (v.exterior_color_id && v.exterior_color_id !== 'all')
        q.exterior_color_id = v.exterior_color_id;
    if (v.euro_norm_id && v.euro_norm_id !== 'all')
        q.euro_norm_id = v.euro_norm_id;
    if (v.interior_color_id && v.interior_color_id !== 'all')
        q.interior_color_id = v.interior_color_id;
    if (v.interior_type_id && v.interior_type_id !== 'all')
        q.interior_type_id = v.interior_type_id;
    if (v.doors && v.doors !== 'all') q.doors = v.doors;
    if (v.seats && v.seats !== 'all') q.seats = v.seats;
    if (v.is_damaged === true) q.is_damaged = '1';
    if (v.has_accident === true) q.has_accident = '1';
    if (v.complete_maintenance_book === true) q.complete_maintenance_book = '1';
    if (v.non_smoker === true) q.non_smoker = '1';
    if (v.city) q.city = v.city;
    if (v.city_id) q.city_id = v.city_id;
    if (v.per_page && v.per_page !== '15') q.per_page = v.per_page;
    if (v.sort && v.sort !== 'latest') q.sort = v.sort;
    if (v.version) q.version = v.version;
    if (v.min_power) q.min_power = v.min_power;
    if (v.max_power) q.max_power = v.max_power;
    if (v.power_unit && v.power_unit !== 'ch') q.power_unit = v.power_unit;
    if (v.features.length) q.features = [...v.features];
    if (v.favorites_only) q.favorites_only = '1';
    if (v.search) q.search = v.search;

    return q;
};

const applyFilters = (scroll = false) => {
    clearTimeout(timeoutId);
    timeoutId = setTimeout(() => {
        const q = getFilterParams();
        if (scroll) {
            scrollToTop();
        }
        router.get(vehiclesRoutes.index.url(), q, {
            preserveState: true,
            replace: true,
            preserveScroll: true,
        });
    }, 400);
};

const handlePageChange = (p: number) => {
    const q = { ...getFilterParams(), page: p };
    scrollToTop();
    router.get(vehiclesRoutes.index.url(), q, {
        preserveState: true,
        replace: true,
        preserveScroll: true,
    });
};

// Reactive: auto-apply when form changes
watch(form, () => applyFilters(true), { deep: true });

const resetFilters = () => {
    clearTimeout(timeoutId);
    scrollToTop();
    router.get(
        vehiclesRoutes.index.url(),
        {},
        {
            preserveState: false,
            preserveScroll: true,
        },
    );
};
</script>

<style scoped>
.list-enter-active,
.list-leave-active {
    transition: all 0.4s ease;
}
.list-enter-from,
.list-leave-to {
    opacity: 0;
    transform: translateY(10px);
}
.list-move {
    transition: transform 0.4s ease;
}
</style>
