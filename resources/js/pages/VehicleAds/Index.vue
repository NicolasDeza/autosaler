<template>
    <Head title="Vehicles Listing" />

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
                @reset-filters="resetFilters"
            />

            <!-- Main Content -->
            <main class="flex flex-1 flex-col gap-6">
                <!-- Filters Summary and Result Count -->
                <div class="flex flex-col gap-4">
                    <div class="flex items-end justify-between">
                        <h2 class="text-2xl font-bold text-slate-900">
                            {{ ads.total }} véhicules trouvés
                        </h2>
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
                        class="relative flex flex-col gap-4 transition-opacity duration-300"
                        :class="{
                            'pointer-events-none opacity-50': isProcessing,
                        }"
                    >
                        <TransitionGroup
                            name="list"
                            tag="div"
                            class="flex flex-col gap-4"
                        >
                            <Card
                                v-for="ad in ads.data"
                                :key="ad.id"
                                class="relative cursor-pointer border-2 border-transparent bg-slate-300 p-4 transition-all duration-300 hover:border-blue-500 hover:shadow-lg"
                                @click="
                                    () =>
                                        router.visit(
                                            vehiclesRoutes.show.url(ad.id),
                                        )
                                "
                            >
                                <div class="absolute top-4 right-4">
                                    <Button
                                        variant="ghost"
                                        size="icon"
                                        class="h-8 w-8 rounded bg-white"
                                        ><Star class="h-4 w-4"
                                    /></Button>
                                </div>

                                <div class="flex flex-col gap-2">
                                    <h4 class="text-lg font-bold">
                                        {{ ad.brand?.name }}
                                        {{ ad.model?.name }}
                                    </h4>
                                    <p
                                        class="text-sm font-semibold text-gray-700 uppercase"
                                    >
                                        {{ ad.vehicle_version?.name || '' }}
                                    </p>

                                    <div
                                        class="mt-2 flex flex-col gap-4 md:flex-row"
                                    >
                                        <div
                                            class="h-48 w-full shrink-0 rounded bg-white md:h-32 md:w-48"
                                        ></div>

                                        <div
                                            class="flex w-full flex-col justify-between gap-4 py-1 md:gap-0"
                                        >
                                            <div
                                                class="self-start rounded bg-white px-4 py-1 text-lg font-bold shadow-sm"
                                            >
                                                € {{ ad.price }}
                                            </div>

                                            <div
                                                class="mt-auto flex flex-wrap gap-2"
                                            >
                                                <div
                                                    class="flex h-7 items-center rounded bg-white px-3 text-xs font-semibold text-gray-700 shadow-sm"
                                                >
                                                    {{
                                                        ad.first_registration_date?.substring(
                                                            0,
                                                            4,
                                                        ) || 'N/A'
                                                    }}
                                                </div>
                                                <div
                                                    class="flex h-7 items-center rounded bg-white px-3 text-xs font-semibold text-gray-700 shadow-sm"
                                                >
                                                    {{
                                                        ad.mileage
                                                            ? ad.mileage.toLocaleString()
                                                            : '0'
                                                    }}
                                                    km
                                                </div>
                                                <div
                                                    v-if="ad.fuel_type"
                                                    class="flex h-7 items-center rounded bg-white px-3 text-xs font-semibold text-gray-700 shadow-sm"
                                                >
                                                    {{ ad.fuel_type.code }}
                                                </div>
                                                <div
                                                    v-if="ad.transmission_type"
                                                    class="flex h-7 items-center rounded bg-white px-3 text-xs font-semibold text-gray-700 shadow-sm"
                                                >
                                                    {{
                                                        ad.transmission_type
                                                            .code
                                                    }}
                                                </div>
                                                <div
                                                    v-if="ad.body_type"
                                                    class="flex h-7 items-center rounded bg-white px-3 text-xs font-semibold text-gray-700 shadow-sm"
                                                >
                                                    {{ ad.body_type.code }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div
                                        class="mt-4 text-xs font-semibold text-gray-600"
                                    >
                                        Premium auto SRL<br />
                                        1330 Rixensart
                                    </div>
                                </div>
                            </Card>
                        </TransitionGroup>
                    </div>

                    <div
                        v-else
                        key="no-results"
                        class="rounded-lg bg-slate-200 py-12 text-center"
                    >
                        <CarIcon
                            class="mx-auto mb-4 h-12 w-12 text-slate-400"
                        />
                        <h3 class="text-lg font-bold text-slate-700">
                            Aucun véhicule trouvé
                        </h3>
                        <p class="text-slate-500">
                            Essayez de modifier vos critères de recherche.
                        </p>
                        <Button
                            variant="outline"
                            class="mt-4"
                            @click="resetFilters"
                            >Réinitialiser les filtres</Button
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
import { Star, Car as CarIcon } from 'lucide-vue-next';
import { ref, watch, reactive, onUnmounted } from 'vue';
import AppPagination from '@/components/AppPagination.vue';
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import ActiveFilters from '@/components/VehicleAds/ActiveFilters.vue';
import FilterSidebar from '@/components/VehicleAds/FilterSidebar.vue';
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
    filters?: Record<string, any>;
}>();

// ── Form state ──────────────────────────────────────────────────
const currentYear = new Date().getFullYear();
const f = props.filters || {};
const toArr = (v: any): string[] =>
    v ? (Array.isArray(v) ? v.map(String) : [String(v)]) : [];

const form = reactive({
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
    per_page: f.per_page ? String(f.per_page) : '15',
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
    form.min_year = values[0];
    form.max_year = values[1];
};

const onPriceChange = (values: number[] | undefined) => {
    if (!values) return;
    form.min_price = values[0];
    form.max_price = values[1];
};

const updateFilter = (key: keyof typeof form, value: any) => {
    (form[key] as any) = value;
};

// ── Apply filters (debounced) ───────────────────────────────────
let timeoutId: ReturnType<typeof setTimeout>;

const getFilterParams = () => {
    const q: Record<string, any> = {};
    const v = form;

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
    if (v.per_page && v.per_page !== '15') q.per_page = v.per_page;

    return q;
};

const applyFilters = (resetPage = true) => {
    clearTimeout(timeoutId);
    timeoutId = setTimeout(() => {
        const q = getFilterParams();
        if (resetPage) {
            delete q.page;
        }
        router.get(vehiclesRoutes.index.url(), q, {
            preserveState: true,
            replace: true,
            preserveScroll: false,
        });
    }, 400);
};

const handlePageChange = (p: number) => {
    const q = { ...getFilterParams(), page: p };
    router.get(vehiclesRoutes.index.url(), q, {
        preserveState: true,
        replace: true,
        preserveScroll: false,
    });
};

// Reactive: auto-apply when form changes
watch(form, () => applyFilters(true), { deep: true });

const resetFilters = () => {
    clearTimeout(timeoutId);
    router.get(
        vehiclesRoutes.index.url(),
        {},
        {
            preserveState: false,
            preserveScroll: false,
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
