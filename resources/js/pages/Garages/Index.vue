<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { useDebounceFn } from '@vueuse/core';
import axios from 'axios';
import { MapPin, RotateCcw, Search, Warehouse } from 'lucide-vue-next';
import { ref, watch } from 'vue';
import AppContent from '@/components/AppContent.vue';
import AppPagination from '@/components/AppPagination.vue';
import { Avatar, AvatarImage } from '@/components/ui/avatar';
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { useTranslation } from '@/composables/useTranslation';
import garageRoutes from '@/routes/garages';
import vehiclesRoutes from '@/routes/vehicles';

type Garage = {
    id: number;
    name: string;
    address: string;
    city: {
        code: string;
        zip_code: string;
    } | null;
    country: {
        code: string;
    } | null;
    logo_url: string | null;
    background_url: string | null;
    active_vehicle_ads_count: number;
};

type CityResult = {
    id: number;
    code: string;
    zip_code: string;
};

const props = defineProps<{
    garages: {
        data: Garage[];
        total: number;
        current_page: number;
        last_page: number;
        from: number;
        to: number;
        per_page: number;
        [key: string]: unknown;
    };
    filters: {
        search?: string | null;
        city?: string | null;
        city_id?: number | string | null;
        sort?: string | null;
    };
}>();

const { __ } = useTranslation();
const seoTitle = 'Garages et concessionnaires en Belgique';
const seoDescription =
    'Trouvez un garage ou un concessionnaire en Belgique sur AutoSaler. Comparez les profils, consultez leur stock de véhicules et accédez à leurs annonces.';
const siteUrl = (
    import.meta.env.VITE_SITE_URL ||
    (typeof window !== 'undefined'
        ? window.location.origin
        : 'https://dev.autosaler.be')
).replace(/\/$/, '');
const canonicalUrl = `${siteUrl}/garages`;

const perPage = ref<string>(String(props.garages.per_page ?? 15));
const search = ref<string>(String(props.filters.search ?? ''));
const city = ref<string>(String(props.filters.city ?? ''));
const cityId = ref<string>(
    props.filters.city_id != null ? String(props.filters.city_id) : '',
);
const sort = ref<string>(String(props.filters.sort ?? 'name_asc'));
const cities = ref<CityResult[]>([]);
const showCities = ref(false);
const isSearchingCities = ref(false);

let citySearchTimeout: number | undefined;

const scrollToTop = (): void => {
    window.scrollTo({
        top: 0,
        behavior: 'smooth',
    });
};

const visitGaragesPage = (page: number): void => {
    scrollToTop();

    router.get(
        garageRoutes.index({
            query: {
                page,
                per_page: perPage.value,
                search: search.value.trim() === '' ? undefined : search.value,
                city: city.value.trim() === '' ? undefined : city.value,
                city_id: cityId.value === '' ? undefined : cityId.value,
                sort: sort.value === 'name_asc' ? undefined : sort.value,
            },
        }).url,
        {},
        {
            preserveScroll: true,
            preserveState: true,
            replace: true,
        },
    );
};

const handlePageChange = (page: number): void => {
    visitGaragesPage(page);
};

const handlePerPageChange = (value: string): void => {
    perPage.value = value;
    visitGaragesPage(1);
};

const handleSearch = useDebounceFn((): void => {
    visitGaragesPage(1);
}, 350);

const searchCities = (query: string): void => {
    if (!query || query.length < 2) {
        cities.value = [];
        showCities.value = false;

        return;
    }

    isSearchingCities.value = true;
    showCities.value = true;

    if (citySearchTimeout) {
        clearTimeout(citySearchTimeout);
    }

    citySearchTimeout = window.setTimeout(async () => {
        try {
            const { data } = await axios.get<CityResult[]>('/cities/search', {
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

const handleCityInput = useDebounceFn((): void => {
    visitGaragesPage(1);
}, 350);

const onCityInput = (): void => {
    cityId.value = '';
    searchCities(city.value);
    handleCityInput();
};

const selectCity = (selectedCity: CityResult): void => {
    city.value = `${selectedCity.zip_code} ${selectedCity.code}`;
    cityId.value = String(selectedCity.id);
    showCities.value = false;
    visitGaragesPage(1);
};

const handleCityBlur = (): void => {
    setTimeout(() => {
        showCities.value = false;
    }, 200);
};

const handleSortChange = (value: string): void => {
    sort.value = value;
    visitGaragesPage(1);
};

const resetFilters = (): void => {
    search.value = '';
    city.value = '';
    cityId.value = '';
    sort.value = 'name_asc';
    showCities.value = false;
    cities.value = [];

    scrollToTop();

    router.get(
        garageRoutes.index({
            query: {
                per_page: perPage.value === '15' ? undefined : perPage.value,
            },
        }).url,
        {},
        {
            preserveScroll: true,
            preserveState: true,
            replace: true,
        },
    );
};

const getStockLabel = (count: number): string => {
    return count === 1
        ? __('garage.stock_label_singular')
        : __('garage.stock_label_plural');
};

watch(
    () => props.garages.per_page,
    (value) => {
        perPage.value = String(value ?? 15);
    },
);

watch(
    () => props.filters.search,
    (value) => {
        search.value = String(value ?? '');
    },
);

watch(
    () => props.filters.city,
    (value) => {
        city.value = String(value ?? '');
    },
);

watch(
    () => props.filters.city_id,
    (value) => {
        cityId.value = value != null ? String(value) : '';
    },
);

watch(
    () => props.filters.sort,
    (value) => {
        sort.value = String(value ?? 'name_asc');
    },
);
</script>

<template>
    <Head :title="seoTitle">
        <link head-key="canonical" rel="canonical" :href="canonicalUrl" />
        <meta head-key="og:url" property="og:url" :content="canonicalUrl" />
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
        <section class="space-y-7 py-10 md:py-12">
            <header
                class="relative overflow-visible rounded-2xl bg-card p-5 shadow-sm sm:p-7"
            >
                <div class="relative z-10 space-y-3">
                    <div class="space-y-2">
                        <h1
                            class="text-3xl font-black tracking-tight text-balance text-foreground md:text-4xl"
                        >
                            {{ __('garage.hero_title_prefix') }}
                            <span class="text-primary">
                                {{ __('garage.hero_title_highlight') }}
                            </span>
                        </h1>
                        <p
                            class="max-w-2xl text-sm leading-relaxed text-muted-foreground md:text-base"
                        >
                            {{ __('garage.hero_description') }}
                        </p>
                    </div>
                    <div class="flex flex-col gap-3 md:flex-row md:flex-wrap md:items-end">
                        <div class="group relative w-full md:w-[24rem] lg:w-md xl:w-120">
                            <Search
                                class="absolute top-1/2 left-3 size-4 -translate-y-1/2 text-muted-foreground transition-colors group-focus-within:text-primary"
                            />
                            <Input
                                v-model="search"
                                :placeholder="__('garage.search_placeholder')"
                                class="h-11 border-border/20 bg-background/30 pl-10 focus-visible:bg-background focus-visible:ring-primary/20"
                                @input="handleSearch"
                            />
                        </div>
                        <div class="relative w-full md:w-[16rem] lg:w-68">
                            <MapPin
                                class="pointer-events-none absolute top-1/2 left-3 z-10 size-4 -translate-y-1/2 text-muted-foreground"
                            />
                            <Input
                                v-model="city"
                                :placeholder="__('homeFilter.location_placeholder')"
                                class="h-11 border-border/20 bg-background/30 pl-10 focus-visible:bg-background focus-visible:ring-primary/20"
                                @input="onCityInput"
                                @focus="city.length >= 2 ? (showCities = true) : null"
                                @blur="handleCityBlur"
                            />
                            <div
                                v-if="showCities && (cities.length > 0 || isSearchingCities)"
                                class="absolute top-full left-0 z-90 mt-1 w-full overflow-hidden rounded-md border border-border bg-popover text-popover-foreground shadow-md"
                            >
                                <div class="max-h-60 overflow-y-auto p-1">
                                    <button
                                        v-for="cityOption in cities"
                                        :key="cityOption.id"
                                        type="button"
                                        class="flex w-full cursor-pointer items-center rounded-sm px-2 py-1.5 text-left text-sm outline-none select-none hover:bg-accent hover:text-accent-foreground"
                                        @click="selectCity(cityOption)"
                                    >
                                        <span class="mr-2 font-medium text-foreground">
                                            {{ cityOption.zip_code }}
                                        </span>
                                        <span class="text-muted-foreground">
                                            {{ cityOption.code }}
                                        </span>
                                    </button>
                                    <div
                                        v-if="cities.length === 0 && isSearchingCities"
                                        class="p-2 text-center text-sm text-muted-foreground"
                                    >
                                        {{ __('ui.loading') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex w-full items-center gap-2 md:w-auto">
                            <span
                                class="hidden text-[10px] font-bold tracking-widest text-muted-foreground uppercase md:block"
                            >
                                {{ __('ui.sorting.sort_by') }}
                            </span>
                            <div class="w-full md:w-auto">
                                    <Select
                                        :model-value="sort"
                                        @update:model-value="(value) => handleSortChange(String(value))"
                                    >
                                        <SelectTrigger class="h-11! w-full cursor-pointer border-border/60 bg-card focus-visible:bg-card md:min-w-52 md:w-">
                                            <SelectValue />
                                        </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="name_asc">
                                            {{ __('garage.sort_name_asc') }}
                                        </SelectItem>
                                        <SelectItem value="name_desc">
                                            {{ __('garage.sort_name_desc') }}
                                        </SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>
                            <Button
                                variant="outline"
                                size="icon"
                                class="inline-flex h-11 w-11 shrink-0 cursor-pointer items-center justify-center border-border/20 bg-background/30 hover:bg-background/60"
                                :title="__('garage.reset_filters')"
                                @click="resetFilters"
                            >
                                <RotateCcw class="size-4" />
                            </Button>
                        </div>
                    </div>
                </div>
            </header>

            <div
                v-if="props.garages.data.length === 0"
                class="rounded-xl border border-dashed border-border bg-card/50 px-6 py-14 text-center text-sm text-muted-foreground"
            >
                {{ __('garage.empty_state') }}
            </div>

            <div v-else class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <Link
                    v-for="garage in props.garages.data"
                    :key="garage.id"
                    :href="vehiclesRoutes.index({ query: { company_id: garage.id } }).url"
                    class="group block"
                >
                    <Card
                        class="gap-0 overflow-hidden border border-border bg-card p-0 transition-all duration-300 hover:-translate-y-0.5 hover:border-primary/30 hover:shadow-lg"
                    >
                        <div class="relative aspect-16/8.5 overflow-hidden">
                            <img
                                v-if="garage.background_url"
                                :src="garage.background_url"
                                :alt="garage.name"
                                class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105"
                                loading="lazy"
                            />
                            <div
                                v-else
                                class="flex h-full w-full items-center justify-center bg-linear-to-br from-muted/95 via-muted/80 to-muted/60"
                            >
                                <Warehouse class="size-11 text-muted-foreground/55" />
                            </div>

                            <div
                                v-if="garage.background_url"
                                class="absolute inset-0 bg-linear-to-t from-black/80 via-black/35 to-transparent"
                            />

                            <div
                                v-if="garage.logo_url"
                                class="absolute bottom-2.5 left-2.5"
                            >
                                <Avatar
                                    :class="
                                        garage.background_url
                                            ? 'size-9 border border-white/40 shadow-md'
                                            : 'size-9 border border-border bg-background/90 shadow-md'
                                    "
                                >
                                    <AvatarImage
                                        :src="garage.logo_url"
                                        :alt="garage.name"
                                        class="object-cover"
                                    />
                                </Avatar>
                            </div>

                        </div>

                        <div class="space-y-2.5 p-3.5">
                            <h2
                                class="line-clamp-1 text-lg leading-tight font-black text-foreground"
                            >
                                {{ garage.name }}
                            </h2>
                            <p class="text-[15px] font-semibold">
                                <span class="font-black text-primary">
                                    {{ garage.active_vehicle_ads_count }}
                                </span>
                                <span class="text-foreground">
                                    {{ ` ${getStockLabel(garage.active_vehicle_ads_count)}` }}
                                </span>
                            </p>
                            <p
                                class="flex items-start gap-2 text-[15px] leading-snug text-muted-foreground"
                            >
                                <MapPin
                                    class="mt-0.5 size-3.5 shrink-0 text-primary/70"
                                />
                                <span>
                                    {{ garage.address }}<br />
                                    {{ garage.city?.zip_code }}
                                    {{ garage.city?.code }}
                                </span>
                            </p>

                        </div>
                    </Card>
                </Link>
            </div>

            <AppPagination
                :pagination="props.garages"
                :per-page="perPage"
                resource-label="garages"
                @update:per-page="handlePerPageChange"
                @update:page="handlePageChange"
            />
        </section>
    </AppContent>
</template>
