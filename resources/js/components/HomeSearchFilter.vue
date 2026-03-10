<script setup lang="ts">
import { Search, Car, MapPin, Settings2, ChevronDown } from 'lucide-vue-next';
import { ref, computed, watch } from 'vue';
import axios from 'axios';
import { router } from '@inertiajs/vue3';
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

const props = defineProps<{
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

const submitSearch = () => {
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

    router.get(vehiclesRoutes.index.url(), q);
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
                            <div class="rounded-lg bg-destructive/10 p-2">
                                <Car :size="20" class="text-destructive" />
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
                                            class="flex h-10 w-full cursor-pointer items-center justify-between rounded-md border border-border bg-card! px-3 text-sm shadow-xs transition-colors hover:bg-muted"
                                            :class="
                                                hasCustomRange
                                                    ? 'text-foreground'
                                                    : 'text-muted-foreground'
                                            "
                                        >
                                            <span
                                                :class="{
                                                    'font-medium':
                                                        hasCustomRange,
                                                }"
                                                >{{ priceLabel }}</span
                                            >
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

                            <div class="w-full lg:flex-1">
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
                                        class="h-full border-border bg-card! pl-9"
                                    />
                                </div>
                            </div>

                            <div class="w-full lg:flex-1">
                                <Button
                                    type="submit"
                                    class="flex h-10 w-full cursor-pointer items-center justify-center gap-2 bg-red-500 px-6 font-bold text-destructive-foreground transition-all hover:scale-105 hover:bg-red-600 active:scale-95"
                                >
                                    <Search :size="18" />
                                    <span class="text-sm uppercase">{{
                                        __('homeFilter.search_button')
                                    }}</span>
                                </Button>
                            </div>
                        </div>
                    </form>

                    <div class="mt-3 flex justify-center">
                        <a
                            href="/vehicles"
                            class="flex items-center gap-1.5 text-xs font-bold text-muted-foreground underline transition-colors hover:text-destructive"
                        >
                            <Settings2 :size="14" />
                            {{ __('homeFilter.more_criteria') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
