<template>
    <Head :title="__('vehicleAd.listing_title')" />

    <AppLayout>
        <div
            class="mx-auto flex w-full max-w-7xl flex-col gap-6 p-4 md:flex-row md:items-start md:p-8"
        >
            <!-- Filters Sidebar -->
            <FilterSidebar
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
                @reset-filters="resetFilters"
            />

            <!-- Main Content -->
            <main class="flex min-w-0 flex-1 flex-col gap-6">
                <!-- Filters Summary and Result Count -->
                <div class="flex flex-col gap-4">
                    <div
                        class="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-end"
                    >
                        <h2 class="text-2xl font-bold text-foreground">
                            {{
                                __('vehicleAd.results_found', {
                                    count: ads.total,
                                })
                            }}
                        </h2>

                        <SortSelect v-model="form.sort" />
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
                        @reset-all="resetFilters"
                        @update-filter="updateFilter"
                        @update-price="onPriceChange"
                        @update-year="onYearChange"
                    />
                </div>
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

                    <div
                        v-else
                        key="no-results"
                        class="rounded-lg bg-muted py-12 text-center"
                    >
                        <CarIcon
                            class="mx-auto mb-4 h-12 w-12 text-muted-foreground"
                        />
                        <h3 class="text-lg font-bold text-foreground">
                            {{ __('vehicleAd.no_vehicles_found') }}
                        </h3>
                        <p class="text-muted-foreground">
                            {{ __('vehicleAd.try_modifying_filters') }}
                        </p>
                        <Button
                            variant="outline"
                            class="mt-4"
                            @click="resetFilters"
                            >{{ __('vehicleAd.reset_filters') }}</Button
                        >
                    </div>
                </Transition>

                <!-- Pagination -->
                <AppPagination
                    :pagination="ads"
                    v-model:per-page="form.per_page"
                    @update:page="handlePageChange"
                />
            </main>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import { router, Head } from '@inertiajs/vue3';
import { Car as CarIcon } from 'lucide-vue-next';
import { ref, watch, onUnmounted } from 'vue';
import AppPagination from '@/components/AppPagination.vue';
import { Button } from '@/components/ui/button';
import ActiveFilters from '@/components/VehicleAds/ActiveFilters.vue';
import FilterSidebar from '@/components/VehicleAds/FilterSidebar.vue';
import SortSelect from '@/components/VehicleAds/SortSelect.vue';
import VehicleAdCard from '@/components/VehicleAds/VehicleAdCard.vue';
import { useTranslation } from '@/composables/useTranslation';
import AppLayout from '@/layouts/AppLayout.vue';
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
    filters?: Record<string, any>;
}>();

// ── Form state ──────────────────────────────────────────────────
const currentYear = new Date().getFullYear();
const f = props.filters || {};
const toArr = (v: any): string[] =>
    v ? (Array.isArray(v) ? v.map(String) : [String(v)]) : [];

const { __ } = useTranslation();

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
    per_page: string;
    sort: string;
    version: string;
    min_power: string;
    max_power: string;
    power_unit: 'kw' | 'ch';
    features: string[];
    favorites_only: boolean;
}

const form = ref<FilterForm>({
    brand_id: f.brand_id ? String(f.brand_id) : 'all',
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
