<script setup lang="ts">
import { Deferred } from '@inertiajs/vue3';
import axios from 'axios';
import { Search, MapPin } from 'lucide-vue-next';
import { ref, computed, watch, defineComponent, h } from 'vue';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Skeleton } from '@/components/ui/skeleton';
import { Slider } from '@/components/ui/slider';
import FilterCheckboxGroup from '@/components/VehicleAds/FilterCheckboxGroup.vue';
import FilterGroup from '@/components/VehicleAds/FilterGroup.vue';

// ── Reusable filter sub-components ──────────────────────────────
const FilterSelect = defineComponent({
    props: {
        label: String,
        modelValue: String,
        options: { type: Array as () => any[], default: () => [] },
        optionLabel: { type: String, default: 'code' },
        placeholder: { type: String, default: 'Tous' },
        disabled: { type: Boolean, default: false },
    },
    emits: ['update:modelValue'],
    setup(props, { emit }) {
        return () =>
            h('div', { class: 'space-y-3' }, [
                props.label
                    ? h(
                          Label,
                          { class: 'text-sm font-semibold text-slate-200' },
                          () => props.label,
                      )
                    : null,
                h(
                    Select,
                    {
                        modelValue: props.modelValue,
                        'onUpdate:modelValue': (v: any) =>
                            emit('update:modelValue', v),
                        disabled: props.disabled,
                    },
                    () => [
                        h(
                            SelectTrigger,
                            {
                                class: 'w-full border-slate-700 bg-slate-800 text-white disabled:opacity-50',
                            },
                            () =>
                                h(SelectValue, {
                                    placeholder: props.placeholder,
                                }),
                        ),
                        h(
                            SelectContent,
                            {
                                class: 'border-slate-700 bg-slate-800 text-white',
                            },
                            () => [
                                h(
                                    SelectItem,
                                    { value: 'all' },
                                    () => props.placeholder,
                                ),
                                ...props.options.map((o: any) =>
                                    h(
                                        SelectItem,
                                        { key: o.id, value: String(o.id) },
                                        () => o[props.optionLabel],
                                    ),
                                ),
                            ],
                        ),
                    ],
                ),
            ]);
    },
});

const FilterSearchSelect = defineComponent({
    props: {
        label: String,
        modelValue: String,
        options: { type: Array as () => any[], default: () => [] },
        optionLabel: { type: String, default: 'code' },
        placeholder: { type: String, default: 'Tous' },
        disabled: { type: Boolean, default: false },
    },
    emits: ['update:modelValue'],
    setup(props, { emit }) {
        const searchQuery = ref('');

        const filteredOptions = computed(() => {
            const query = searchQuery.value.toLowerCase().trim();
            if (!query) return props.options;
            return props.options.filter((o: any) =>
                o[props.optionLabel]?.toLowerCase().includes(query),
            );
        });

        return () =>
            h('div', { class: 'space-y-3' }, [
                props.label
                    ? h(
                          Label,
                          { class: 'text-sm font-semibold text-slate-200' },
                          () => props.label,
                      )
                    : null,
                h(
                    Select,
                    {
                        modelValue: props.modelValue,
                        'onUpdate:modelValue': (v: any) => {
                            emit('update:modelValue', v);
                        },
                        disabled: props.disabled,
                    },
                    () => [
                        h(
                            SelectTrigger,
                            {
                                class: 'w-full border-slate-700 bg-slate-800 text-white disabled:opacity-50',
                            },
                            () =>
                                h(SelectValue, {
                                    placeholder: props.placeholder,
                                }),
                        ),
                        h(
                            SelectContent,
                            {
                                class: 'border-slate-700 bg-slate-800 text-white',
                            },
                            () => [
                                h(
                                    'div',
                                    {
                                        class: 'sticky top-0 z-10 border-b border-slate-700 bg-slate-800 p-2',
                                    },
                                    [
                                        h(
                                            'div',
                                            {
                                                class: 'flex items-center gap-2 rounded-md border border-slate-600 bg-slate-900 px-2',
                                            },
                                            [
                                                h(Search, {
                                                    class: 'h-3.5 w-3.5 shrink-0 text-slate-400',
                                                }),
                                                h('input', {
                                                    type: 'text',
                                                    class: 'h-8 w-full bg-transparent text-sm text-white placeholder-slate-400 outline-none',
                                                    placeholder:
                                                        'Rechercher...',
                                                    value: searchQuery.value,
                                                    onInput: (e: any) => {
                                                        searchQuery.value =
                                                            e.target.value;
                                                    },
                                                    onClick: (e: Event) => {
                                                        e.stopPropagation();
                                                    },
                                                    onPointerdown: (
                                                        e: Event,
                                                    ) => {
                                                        e.stopPropagation();
                                                    },
                                                    onKeydown: (e: Event) => {
                                                        e.stopPropagation();
                                                    },
                                                }),
                                            ],
                                        ),
                                    ],
                                ),
                                h(
                                    SelectItem,
                                    { value: 'all' },
                                    () => props.placeholder,
                                ),
                                ...filteredOptions.value.map((o: any) =>
                                    h(
                                        SelectItem,
                                        {
                                            key: o.id,
                                            value: String(o.id),
                                        },
                                        () => o[props.optionLabel],
                                    ),
                                ),
                            ],
                        ),
                    ],
                ),
            ]);
    },
});

// ── Static option arrays ────────────────────────────────────────
const doorOptions = [
    { id: '2', label: '2' },
    { id: '3', label: '3' },
    { id: '4', label: '4' },
    { id: '5', label: '5' },
];
const seatOptions = [
    { id: '2', label: '2' },
    { id: '4', label: '4' },
    { id: '5', label: '5' },
    { id: '7', label: '7' },
    { id: '9', label: '9' },
];

const props = defineProps<{
    brands?: any[];
    fuelTypes?: any[];
    bodyTypes?: any[];
    transmissionTypes?: any[];
    exteriorColors?: any[];
    euroNorms?: any[];
    models: any[];
}>();

const form = defineModel<any>('form', { required: true });

const emit = defineEmits(['update:models']);

const currentYear = new Date().getFullYear();
const yearRange = ref([form.value.min_year, form.value.max_year]);
const priceRange = ref([form.value.min_price, form.value.max_price]);

// ── Visibility Computeds ────────────────────────────────────────
const isBrandActive = computed(() => form.value.brand_id !== 'all');
const isModelActive = computed(() => form.value.model_id !== 'all');
const isLocationActive = computed(() => !!form.value.city);
const isPriceActive = computed(
    () => form.value.min_price > 0 || form.value.max_price < 200000,
);
const isYearActive = computed(
    () => form.value.min_year > 1980 || form.value.max_year < currentYear,
);
const isMileageActive = computed(() => form.value.max_mileage !== 'all');
const isFuelActive = computed(() => form.value.fuel_types.length > 0);
const isBodyActive = computed(() => form.value.body_types.length > 0);
const isTransmissionActive = computed(
    () => form.value.transmission_types.length > 0,
);
const isColorActive = computed(() => form.value.exterior_color_id !== 'all');
const isEuroActive = computed(() => form.value.euro_norm_id !== 'all');
const isDoorsActive = computed(() => form.value.doors !== 'all');
const isSeatsActive = computed(() => form.value.seats !== 'all');
const isStatusActive = computed(
    () =>
        form.value.is_damaged !== null ||
        form.value.has_accident !== null ||
        form.value.complete_maintenance_book !== null ||
        form.value.non_smoker !== null,
);

// ── Fetching Logic ─────────────────────────────────────────────
const versions = ref<any[]>([]);

// Fetch models when brand changes
let isInitialBrandLoad = true;
watch(
    () => form.value.brand_id,
    async (brandId: string | number | null) => {
        if (!isInitialBrandLoad) {
            form.value.model_id = 'all';
            versions.value = [];
        }
        isInitialBrandLoad = false;
        emit('update:models', []);

        if (!brandId || brandId === 'all') return;

        const { data } = await axios.get('/vehicle-models', {
            params: { brand_id: brandId },
        });
        emit('update:models', data);
    },
    { immediate: true },
);

// Fetch versions when model changes
watch(
    () => form.value.model_id,
    async (modelId: string | number | null) => {
        versions.value = [];

        if (!modelId || modelId === 'all') return;

        const { data } = await axios.get('/vehicle-versions', {
            params: { model_id: modelId },
        });
        versions.value = data;
    },
    { immediate: true },
);

// City Search
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
    form.value.city = city.zip_code + ' ' + city.code;
    showCities.value = false;
};

const handleCityBlur = () => {
    setTimeout(() => {
        showCities.value = false;
    }, 200);
};

const onYearChange = (values: number[] | undefined) => {
    if (!values) return;
    form.value.min_year = values[0];
    form.value.max_year = values[1];
    yearRange.value = [...values];
};

const onPriceChange = (values: number[] | undefined) => {
    if (!values) return;
    form.value.min_price = values[0];
    form.value.max_price = values[1];
    priceRange.value = [...values];
};

// Sync internal ranges when form changes externally (e.g. reset)
watch(
    () => [form.value.min_year, form.value.max_year],
    (newVal: any[]) => {
        yearRange.value = [newVal[0] as number, newVal[1] as number];
    },
    { deep: true },
);

watch(
    () => [form.value.min_price, form.value.max_price],
    (newVal: any[]) => {
        priceRange.value = [newVal[0] as number, newVal[1] as number];
    },
    { deep: true },
);
</script>

<template>
    <div class="space-y-6">
        <Deferred
            :data="[
                'brands',
                'fuelTypes',
                'bodyTypes',
                'transmissionTypes',
                'exteriorColors',
                'euroNorms',
            ]"
        >
            <template #fallback>
                <div class="space-y-6">
                    <div v-for="i in 6" :key="i" class="space-y-3">
                        <Skeleton class="h-4 w-24 bg-slate-700" />
                        <Skeleton class="h-10 w-full rounded-md bg-slate-800" />
                    </div>
                </div>
            </template>

            <div class="space-y-6">
                <!-- Marque -->
                <FilterGroup label="Marque" :is-active="isBrandActive">
                    <FilterSearchSelect
                        v-model="form.brand_id"
                        :options="props.brands ?? []"
                        option-label="name"
                        placeholder="Toutes les marques"
                    />
                </FilterGroup>

                <!-- Modèle -->
                <FilterGroup
                    label="Modèle"
                    :is-active="isModelActive"
                    :disabled="
                        !form.brand_id ||
                        form.brand_id === 'all' ||
                        !props.models.length
                    "
                >
                    <FilterSelect
                        v-model="form.model_id"
                        :options="props.models"
                        option-label="name"
                        placeholder="Tous les modèles"
                        :disabled="
                            !form.brand_id ||
                            form.brand_id === 'all' ||
                            !props.models.length
                        "
                    />
                </FilterGroup>

                <!-- Version -->
                <FilterGroup
                    v-if="form.model_id && form.model_id !== 'all'"
                    label="Version"
                    :is-active="form.version_id !== 'all'"
                >
                    <FilterSelect
                        v-model="form.version_id"
                        :options="versions"
                        option-label="name"
                        placeholder="Toutes les versions"
                    />
                </FilterGroup>

                <!-- Emplacement -->
                <FilterGroup label="Emplacement" :is-active="isLocationActive">
                    <div class="relative z-50 space-y-3">
                        <div class="relative h-10 w-full">
                            <MapPin
                                :size="14"
                                class="absolute top-1/2 left-3 z-10 -translate-y-1/2 text-slate-400"
                            />
                            <Input
                                v-model="form.city"
                                placeholder="Localisation"
                                class="h-full w-full border-slate-700 bg-slate-800 pl-9 text-white placeholder-slate-400"
                                @input="searchCities($event.target.value)"
                                @focus="
                                    form.city.length >= 2
                                        ? (showCities = true)
                                        : null
                                "
                                @blur="handleCityBlur"
                            />
                        </div>

                        <!-- City Suggestions -->
                        <div
                            v-if="
                                showCities &&
                                (cities.length > 0 || isSearchingCities)
                            "
                            class="absolute top-full left-0 z-50 mt-1 w-full animate-in overflow-hidden rounded-md border border-slate-700 bg-slate-800 text-white shadow-md fade-in-80"
                        >
                            <div class="max-h-60 overflow-y-auto p-1">
                                <div
                                    v-for="city in cities"
                                    :key="city.id"
                                    class="flex cursor-pointer items-center rounded-sm px-2 py-1.5 text-sm outline-none select-none hover:bg-slate-700 hover:text-white"
                                    @click="selectCity(city)"
                                >
                                    <span class="mr-2 font-medium text-white">{{
                                        city.zip_code
                                    }}</span>
                                    <span class="text-slate-300">{{
                                        city.code
                                    }}</span>
                                </div>
                                <div
                                    v-if="
                                        cities.length === 0 && isSearchingCities
                                    "
                                    class="p-2 text-center text-sm text-slate-400"
                                >
                                    Recherche...
                                </div>
                            </div>
                        </div>
                    </div>
                </FilterGroup>

                <!-- Prix -->
                <FilterGroup label="Prix" :is-active="isPriceActive">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-slate-400">
                                {{ form.min_price?.toLocaleString() }} -
                                {{
                                    form.max_price >= 200000
                                        ? '200k+'
                                        : form.max_price?.toLocaleString()
                                }}
                            </span>
                        </div>
                        <Slider
                            v-model="priceRange"
                            :max="200000"
                            :min="0"
                            :step="1000"
                            class="py-4"
                            @update:model-value="onPriceChange"
                        />
                    </div>
                </FilterGroup>

                <!-- Année -->
                <FilterGroup
                    label="1ère immatriculation"
                    :is-active="isYearActive"
                >
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-slate-400">
                                {{ form.min_year }} - {{ form.max_year }}
                            </span>
                        </div>
                        <Slider
                            v-model="yearRange"
                            :max="currentYear"
                            :min="1980"
                            :step="1"
                            class="py-4"
                            @update:model-value="onYearChange"
                        />
                    </div>
                </FilterGroup>

                <!-- Kilométrage -->
                <FilterGroup label="Kilométrage" :is-active="isMileageActive">
                    <Select v-model="form.max_mileage">
                        <SelectTrigger
                            class="w-full border-slate-700 bg-slate-800 text-white"
                        >
                            <SelectValue placeholder="Peu importe" />
                        </SelectTrigger>
                        <SelectContent
                            class="border-slate-700 bg-slate-800 text-white"
                        >
                            <SelectItem value="all">Peu importe</SelectItem>
                            <SelectItem value="10000">10 000 km</SelectItem>
                            <SelectItem value="25000">25 000 km</SelectItem>
                            <SelectItem value="50000">50 000 km</SelectItem>
                            <SelectItem value="100000">100 000 km</SelectItem>
                            <SelectItem value="150000">150 000 km</SelectItem>
                            <SelectItem value="200000">200 000 km</SelectItem>
                        </SelectContent>
                    </Select>
                </FilterGroup>

                <!-- Carburant -->
                <FilterGroup label="Carburant" :is-active="isFuelActive">
                    <FilterCheckboxGroup
                        v-model="form.fuel_types"
                        :options="props.fuelTypes ?? []"
                    />
                </FilterGroup>

                <!-- Carrosserie -->
                <FilterGroup label="Carrosserie" :is-active="isBodyActive">
                    <FilterCheckboxGroup
                        v-model="form.body_types"
                        :options="props.bodyTypes ?? []"
                    />
                </FilterGroup>

                <!-- Transmission -->
                <FilterGroup
                    label="Boîte de vitesse"
                    :is-active="isTransmissionActive"
                >
                    <FilterCheckboxGroup
                        v-model="form.transmission_types"
                        :options="props.transmissionTypes ?? []"
                    />
                </FilterGroup>

                <!-- Couleur extérieur -->
                <FilterGroup label="Couleur" :is-active="isColorActive">
                    <FilterSelect
                        v-model="form.exterior_color_id"
                        :options="props.exteriorColors ?? []"
                        option-label="name"
                        placeholder="Toutes les couleurs"
                    />
                </FilterGroup>

                <!-- Euro Norm -->
                <FilterGroup label="Norme Euro" :is-active="isEuroActive">
                    <FilterSelect
                        v-model="form.euro_norm_id"
                        :options="props.euroNorms ?? []"
                        option-label="name"
                        placeholder="Peu importe"
                    />
                </FilterGroup>

                <!-- Doors & Seats -->
                <div class="grid grid-cols-2 gap-4">
                    <FilterGroup label="Portes" :is-active="isDoorsActive">
                        <FilterSelect
                            v-model="form.doors"
                            :options="doorOptions"
                            option-label="label"
                            placeholder="Peu importe"
                        />
                    </FilterGroup>
                    <FilterGroup label="Sièges" :is-active="isSeatsActive">
                        <FilterSelect
                            v-model="form.seats"
                            :options="seatOptions"
                            option-label="label"
                            placeholder="Peu importe"
                        />
                    </FilterGroup>
                </div>

                <!-- Status Checkboxes -->
                <FilterGroup
                    label="État & Historique"
                    :is-active="isStatusActive"
                >
                    <div class="space-y-4 pt-2">
                        <div class="flex items-center space-x-2">
                            <Checkbox
                                id="chk-damaged"
                                :checked="form.is_damaged === true"
                                @update:modelValue="
                                    (val: boolean | 'indeterminate') =>
                                        (form.is_damaged =
                                            val === true ? true : null)
                                "
                            />
                            <Label
                                for="chk-damaged"
                                class="cursor-pointer text-sm text-slate-300"
                                >Véhicule endommagé</Label
                            >
                        </div>
                        <div class="flex items-center space-x-2">
                            <Checkbox
                                id="chk-accident"
                                :checked="form.has_accident === true"
                                @update:modelValue="
                                    (val: boolean | 'indeterminate') =>
                                        (form.has_accident =
                                            val === true ? true : null)
                                "
                            />
                            <Label
                                for="chk-accident"
                                class="cursor-pointer text-sm text-slate-300"
                                >Accidenté</Label
                            >
                        </div>
                        <div class="flex items-center space-x-2">
                            <Checkbox
                                id="chk-maintenance"
                                :checked="
                                    form.complete_maintenance_book === true
                                "
                                @update:modelValue="
                                    (val: boolean | 'indeterminate') =>
                                        (form.complete_maintenance_book =
                                            val === true ? true : null)
                                "
                            />
                            <Label
                                for="chk-maintenance"
                                class="cursor-pointer text-sm text-slate-300"
                                >Carnet entretien complet</Label
                            >
                        </div>
                        <div class="flex items-center space-x-2">
                            <Checkbox
                                id="chk-non-smoker"
                                :checked="form.non_smoker === true"
                                @update:modelValue="
                                    (val: boolean | 'indeterminate') =>
                                        (form.non_smoker =
                                            val === true ? true : null)
                                "
                            />
                            <Label
                                for="chk-non-smoker"
                                class="cursor-pointer text-sm text-slate-300"
                                >Non fumeur</Label
                            >
                        </div>
                    </div>
                </FilterGroup>
            </div>
        </Deferred>
    </div>
</template>
