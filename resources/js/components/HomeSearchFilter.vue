<script setup lang="ts">
import { router, Link } from '@inertiajs/vue3';
import axios from 'axios';
import { Search, Car, MapPin, Settings2, ChevronDown } from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from '@/components/ui/popover';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Slider } from '@/components/ui/slider';
import { useTranslation } from '@/composables/useTranslation';
import vehiclesRoutes from '@/routes/vehicles';

defineProps<{
    brands?: any[];
}>();

const { __ } = useTranslation();

interface FilterState {
    brand: string;
    model: string;
    priceRange: number[];
    year: string;
    city: string;
}

const filters = ref<FilterState>({
    brand: 'all',
    model: 'all',
    priceRange: [0, 100000],
    year: 'all',
    city: '',
});

const models = ref<any[]>([]);
const cities = ref<any[]>([]);
const showCities = ref(false);
const isSearchingCities = ref(false);

let citySearchTimeout: number | undefined;

const searchCities = (query: string) => {
    if (!query || query.length < 2) {
        cities.value = [];
        showCities.value = false;
        return;
    }

    isSearchingCities.value = true;
    showCities.value = true;

    // Annuler la requête précédente si elle existe
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
    filters.value.city = city.zip_code + ' ' + city.code;
    showCities.value = false;
};

const handleCityBlur = () => {
    setTimeout(() => {
        showCities.value = false;
    }, 200);
};

const formatPrice = (val: number) =>
    val >= 100000 ? '100 000€+' : `${val.toLocaleString('fr-FR')}€`;

const hasCustomRange = computed(
    () =>
        filters.value.priceRange[0] !== 0 ||
        filters.value.priceRange[1] !== 100000,
);

const priceLabel = computed(() =>
    hasCustomRange.value
        ? `${formatPrice(filters.value.priceRange[0])} – ${formatPrice(filters.value.priceRange[1])}`
        : __('homeFilter.price_placeholder'),
);

const isModelDisabled = computed(
    () => !filters.value.brand || filters.value.brand === 'all',
);

watch(
    () => filters.value.brand,
    async (val) => {
        filters.value.model = 'all';
        models.value = [];

        if (!val || val === 'all') return;

        try {
            const { data } = await axios.get('/vehicle-models', {
                params: { brand_id: val },
            });
            models.value = data;
        } catch (error) {
            console.error('Error fetching models:', error);
        }
    },
);

const searchQuery = computed(() => {
    const q: Record<string, any> = {};

    if (filters.value.brand && filters.value.brand !== 'all')
        q.brand_id = filters.value.brand;
    if (filters.value.model && filters.value.model !== 'all')
        q.model_id = filters.value.model;
    if (filters.value.priceRange[0] > 0)
        q.min_price = filters.value.priceRange[0];
    if (filters.value.priceRange[1] < 100000)
        q.max_price = filters.value.priceRange[1];
    if (filters.value.year && filters.value.year !== 'all')
        q.min_year = filters.value.year;
    if (filters.value.city) q.city = filters.value.city;

    return q;
});

const submitSearch = () => {
    router.get(vehiclesRoutes.index.url(), searchQuery.value);
};

// Select de l'année 1ère immatriculation
const years = computed(() => {
    const list = [];
    for (let y = 2026; y >= 1980; y--) {
        list.push(String(y));
    }
    return list;
});
</script>

<template>
    <section class="relative w-full pb-8 sm:pb-12">
        <div class="mx-auto max-w-6xl px-6">
            <div class="relative z-30 -mt-24 sm:-mt-40">
                <div
                    class="rounded-lg border border-border bg-card/95 p-3 shadow-[0_20px_50px_rgba(0,0,0,0.1)] backdrop-blur-md sm:p-4"
                >
                    <div class="mb-2 flex items-center px-2">
                        <div class="flex items-center gap-2">
                            <div class="rounded-lg bg-primary/10 p-2">
                                <Car :size="20" class="text-primary" />
                            </div>
                            <h2
                                class="text-sm font-bold tracking-wider text-foreground uppercase"
                            >
                                {{ __('homeFilter.title') }}
                            </h2>
                        </div>
                    </div>

                    <form
                        class="flex flex-col gap-3 px-2"
                        @submit.prevent="submitSearch"
                    >
                        <!-- Ligne 1 : Marque / Modèle / Prix range -->
                        <div class="flex flex-col gap-3 lg:flex-row">
                            <div class="w-full lg:flex-1">
                                <Select v-model="filters.brand">
                                    <SelectTrigger
                                        class="h-10! w-full cursor-pointer border-border bg-card! text-foreground"
                                    >
                                        <SelectValue
                                            :placeholder="
                                                __(
                                                    'homeFilter.brand_placeholder',
                                                )
                                            "
                                        />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="all">{{
                                            __('homeFilter.brand_placeholder')
                                        }}</SelectItem>
                                        <SelectItem
                                            v-for="brand in brands"
                                            :key="brand.id"
                                            :value="String(brand.id)"
                                        >
                                            {{ brand.name }}
                                        </SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>

                            <div class="w-full lg:flex-1">
                                <Select
                                    v-model="filters.model"
                                    :disabled="isModelDisabled"
                                >
                                    <SelectTrigger
                                        class="h-10! w-full cursor-pointer border-border bg-card! text-foreground"
                                        :class="{
                                            'cursor-not-allowed! opacity-50':
                                                isModelDisabled,
                                        }"
                                    >
                                        <SelectValue
                                            :placeholder="
                                                __(
                                                    'homeFilter.model_placeholder',
                                                )
                                            "
                                        />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="all">{{
                                            __('homeFilter.model_placeholder')
                                        }}</SelectItem>
                                        <SelectItem
                                            v-for="model in models"
                                            :key="model.id"
                                            :value="String(model.id)"
                                        >
                                            {{ model.name }}
                                        </SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>

                            <div class="w-full lg:flex-1">
                                <Popover>
                                    <PopoverTrigger as-child>
                                        <button
                                            type="button"
                                            class="flex h-10 w-full cursor-pointer items-center justify-between rounded-md border border-border bg-card! px-3 text-sm text-foreground shadow-xs transition-colors hover:bg-muted"
                                        >
                                            <span>{{ priceLabel }}</span>
                                            <ChevronDown
                                                class="size-4 shrink-0 opacity-50"
                                            />
                                        </button>
                                    </PopoverTrigger>
                                    <PopoverContent
                                        class="w-80 p-4"
                                        align="start"
                                    >
                                        <div class="flex flex-col gap-3">
                                            <div
                                                class="flex items-center justify-between"
                                            >
                                                <span
                                                    class="text-sm font-semibold text-foreground"
                                                    >Budget</span
                                                >
                                                <span
                                                    class="text-xs text-muted-foreground"
                                                    >{{
                                                        formatPrice(
                                                            filters
                                                                .priceRange[0],
                                                        )
                                                    }}
                                                    –
                                                    {{
                                                        formatPrice(
                                                            filters
                                                                .priceRange[1],
                                                        )
                                                    }}</span
                                                >
                                            </div>
                                            <Slider
                                                v-model="filters.priceRange"
                                                :min="0"
                                                :max="100000"
                                                :step="500"
                                                class="w-full"
                                            />
                                            <div
                                                class="flex justify-between text-[11px] text-muted-foreground"
                                            >
                                                <span>0€</span>
                                                <span>100 000€+</span>
                                            </div>
                                        </div>
                                    </PopoverContent>
                                </Popover>
                            </div>
                        </div>

                        <!-- Ligne 2 : 1ère immatriculation / Ville / Bouton -->
                        <div class="flex flex-col gap-3 lg:flex-row">
                            <div class="w-full lg:flex-1">
                                <Select v-model="filters.year">
                                    <SelectTrigger
                                        class="h-10! w-full cursor-pointer border-border bg-card! text-foreground"
                                    >
                                        <SelectValue
                                            :placeholder="
                                                __(
                                                    'homeFilter.year_placeholder',
                                                )
                                            "
                                        />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="all">{{
                                            __('homeFilter.year_placeholder')
                                        }}</SelectItem>
                                        <SelectItem
                                            v-for="year in years"
                                            :key="year"
                                            :value="year"
                                        >
                                            {{ year }}
                                        </SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>

                            <div class="relative w-full lg:flex-1">
                                <div class="relative h-10">
                                    <MapPin
                                        :size="14"
                                        class="absolute top-1/2 left-3 z-10 -translate-y-1/2 text-muted-foreground"
                                    />
                                    <Input
                                        v-model="filters.city"
                                        :placeholder="
                                            __(
                                                'homeFilter.location_placeholder',
                                            )
                                        "
                                        class="h-full border-border bg-card! pl-9 text-foreground placeholder:text-foreground"
                                        @input="searchCities(filters.city)"
                                        @focus="
                                            filters.city.length >= 2
                                                ? (showCities = true)
                                                : null
                                        "
                                        @blur="handleCityBlur"
                                    />
                                </div>
                                <div
                                    v-if="
                                        showCities &&
                                        (cities.length > 0 || isSearchingCities)
                                    "
                                    class="absolute top-full left-0 z-50 mt-1 w-full animate-in overflow-hidden rounded-md border border-border bg-popover text-popover-foreground shadow-md fade-in-80"
                                >
                                    <div class="max-h-60 overflow-y-auto p-1">
                                        <div
                                            v-for="city in cities"
                                            :key="city.id"
                                            class="flex cursor-pointer items-center rounded-sm px-2 py-1.5 text-sm outline-none select-none hover:bg-accent hover:text-accent-foreground"
                                            @click="selectCity(city)"
                                        >
                                            <span
                                                class="mr-2 font-medium text-foreground"
                                                >{{ city.zip_code }}</span
                                            >
                                            <span
                                                class="text-muted-foreground"
                                                >{{ city.code }}</span
                                            >
                                        </div>
                                        <div
                                            v-if="
                                                cities.length === 0 &&
                                                isSearchingCities
                                            "
                                            class="p-2 text-center text-sm text-muted-foreground"
                                        >
                                            Recherche...
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="w-full lg:flex-1">
                                <Button
                                    type="submit"
                                    size="lg"
                                    class="flex w-full cursor-pointer items-center justify-center gap-2 px-10 py-4 text-base font-bold uppercase"
                                >
                                    <Search :size="18" />
                                    <span>{{
                                        __('homeFilter.search_button')
                                    }}</span>
                                </Button>
                            </div>
                        </div>
                    </form>

                    <div class="mt-3 flex justify-center px-2">
                        <Link
                            :href="vehiclesRoutes.index.url()"
                            :data="searchQuery"
                            class="flex items-center justify-center gap-1.5 text-xs font-bold text-muted-foreground underline transition-colors hover:text-primary"
                        >
                            <Settings2 :size="14" />
                            {{ __('homeFilter.more_criteria') }}
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
