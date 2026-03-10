<template>
    <Head title="Vehicles Listing" />

    <AppLayout>
        <div
            class="mx-auto flex w-full max-w-7xl flex-col gap-6 p-4 md:flex-row md:p-8"
        >
            <!-- Filters Sidebar -->
            <aside
                class="w-full shrink-0 space-y-0 rounded-lg bg-slate-900 p-6 text-white md:w-1/4 lg:w-1/4"
            >
                <div class="mb-6 flex items-center justify-between">
                    <h3 class="text-xl font-bold">Filtres</h3>
                    <Button
                        variant="ghost"
                        size="sm"
                        class="text-xs text-slate-400 hover:text-white"
                        @click="resetFilters"
                        >Réinitialiser</Button
                    >
                </div>

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
                                <Skeleton
                                    class="h-10 w-full rounded-md bg-slate-800"
                                />
                            </div>
                        </div>
                    </template>

                    <div class="space-y-6">
                        <!-- Marque -->
                        <FilterSearchSelect
                            label="Marque"
                            v-model="form.brand_id"
                            :options="brands ?? []"
                            option-label="name"
                            placeholder="Toutes les marques"
                            searchable
                        />

                        <!-- Modèle -->
                        <FilterSelect
                            label="Modèle"
                            v-model="form.model_id"
                            :options="models"
                            option-label="name"
                            placeholder="Tous les modèles"
                            :disabled="
                                !form.brand_id ||
                                form.brand_id === 'all' ||
                                !models.length
                            "
                        />

                        <!-- Ville / CP -->
                        <div class="relative space-y-3">
                            <Label class="text-sm font-semibold text-slate-200"
                                >Emplacement</Label
                            >
                            <div class="relative h-10 w-full">
                                <MapPin
                                    :size="14"
                                    class="absolute top-1/2 left-3 z-10 -translate-y-1/2 text-slate-400"
                                />
                                <Input
                                    v-model="form.city"
                                    placeholder="Localisation"
                                    class="h-full w-full border-slate-700 bg-slate-800 pl-9 text-white placeholder-slate-400"
                                    @input="searchCities(form.city)"
                                    @focus="
                                        form.city.length >= 2
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
                                class="absolute top-full left-0 z-50 mt-1 w-full animate-in overflow-hidden rounded-md border border-slate-700 bg-slate-800 text-white shadow-md fade-in-80"
                            >
                                <div class="max-h-60 overflow-y-auto p-1">
                                    <div
                                        v-for="city in cities"
                                        :key="city.id"
                                        class="flex cursor-pointer items-center rounded-sm px-2 py-1.5 text-sm outline-none select-none hover:bg-slate-700 hover:text-white"
                                        @click="selectCity(city)"
                                    >
                                        <span
                                            class="mr-2 font-medium text-white"
                                            >{{ city.zip_code }}</span
                                        >
                                        <span class="text-slate-300">{{
                                            city.code
                                        }}</span>
                                    </div>
                                    <div
                                        v-if="
                                            cities.length === 0 &&
                                            isSearchingCities
                                        "
                                        class="p-2 text-center text-sm text-slate-400"
                                    >
                                        Recherche...
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Prix -->
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <Label
                                    class="text-sm font-semibold text-slate-200"
                                    >Prix (€)</Label
                                >
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
                                @update:modelValue="onPriceChange"
                            />
                        </div>

                        <!-- Année -->
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <Label
                                    class="text-sm font-semibold text-slate-200"
                                    >Année</Label
                                >
                                <span class="text-xs text-slate-400"
                                    >{{ form.min_year }} -
                                    {{ form.max_year }}</span
                                >
                            </div>
                            <Slider
                                v-model="yearRange"
                                :max="currentYear"
                                :min="2000"
                                :step="1"
                                class="py-4"
                                @update:modelValue="onYearChange"
                            />
                        </div>

                        <!-- Kilométrage -->
                        <div class="space-y-3">
                            <Label class="text-sm font-semibold text-slate-200"
                                >Kilométrage</Label
                            >
                            <Select v-model="form.max_mileage">
                                <SelectTrigger
                                    class="w-full border-slate-700 bg-slate-800 text-white"
                                >
                                    <SelectValue placeholder="Peu importe" />
                                </SelectTrigger>
                                <SelectContent
                                    class="border-slate-700 bg-slate-800 text-white"
                                >
                                    <SelectItem value="all"
                                        >Peu importe</SelectItem
                                    >
                                    <SelectItem value="10000"
                                        >10 000 km</SelectItem
                                    >
                                    <SelectItem value="25000"
                                        >25 000 km</SelectItem
                                    >
                                    <SelectItem value="50000"
                                        >50 000 km</SelectItem
                                    >
                                    <SelectItem value="100000"
                                        >100 000 km</SelectItem
                                    >
                                    <SelectItem value="150000"
                                        >150 000 km</SelectItem
                                    >
                                    <SelectItem value="200000"
                                        >200 000 km</SelectItem
                                    >
                                </SelectContent>
                            </Select>
                        </div>

                        <!-- Carburant -->
                        <FilterCheckboxGroup
                            label="Carburant"
                            :options="fuelTypes ?? []"
                            option-label="code"
                            v-model="form.fuel_types"
                        />

                        <!-- Type de carrosserie -->
                        <FilterCheckboxGroup
                            label="Carrosserie"
                            :options="bodyTypes ?? []"
                            option-label="code"
                            v-model="form.body_types"
                        />

                        <!-- Transmission -->
                        <FilterCheckboxGroup
                            label="Transmission"
                            :options="transmissionTypes ?? []"
                            option-label="code"
                            v-model="form.transmission_types"
                        />

                        <!-- Couleur extérieure -->
                        <FilterSelect
                            label="Couleur extérieure"
                            v-model="form.exterior_color_id"
                            :options="exteriorColors ?? []"
                            option-label="code"
                            placeholder="Toutes"
                        />

                        <!-- Norme Euro -->
                        <FilterSelect
                            label="Norme Euro"
                            v-model="form.euro_norm_id"
                            :options="euroNorms ?? []"
                            option-label="code"
                            placeholder="Toutes"
                        />

                        <!-- Portes -->
                        <FilterSelect
                            label="Portes"
                            v-model="form.doors"
                            :options="doorOptions"
                            option-label="label"
                            placeholder="Peu importe"
                        />

                        <!-- Sièges -->
                        <FilterSelect
                            label="Sièges"
                            v-model="form.seats"
                            :options="seatOptions"
                            option-label="label"
                            placeholder="Peu importe"
                        />

                        <!-- Booleans -->
                        <Collapsible class="space-y-3">
                            <CollapsibleTrigger
                                class="flex w-full items-center justify-between"
                            >
                                <Label
                                    class="cursor-pointer text-sm font-semibold text-slate-200"
                                    >État</Label
                                >
                                <ChevronDown
                                    class="h-4 w-4 text-slate-400 transition-transform duration-200 in-data-[state=open]:rotate-180"
                                />
                            </CollapsibleTrigger>
                            <CollapsibleContent
                                forceMount
                                class="data-[state=closed]:hidden"
                            >
                                <div class="space-y-2 pt-1">
                                    <div class="flex items-center space-x-2">
                                        <Checkbox
                                            id="chk-non-damaged"
                                            :checked="form.is_damaged === false"
                                            @update:checked="
                                                form.is_damaged = $event
                                                    ? false
                                                    : null
                                            "
                                        />
                                        <label
                                            for="chk-non-damaged"
                                            class="cursor-pointer text-sm text-slate-300"
                                            >Non endommagé</label
                                        >
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <Checkbox
                                            id="chk-no-accident"
                                            :checked="
                                                form.has_accident === false
                                            "
                                            @update:checked="
                                                form.has_accident = $event
                                                    ? false
                                                    : null
                                            "
                                        />
                                        <label
                                            for="chk-no-accident"
                                            class="cursor-pointer text-sm text-slate-300"
                                            >Pas d'accident</label
                                        >
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <Checkbox
                                            id="chk-maintenance"
                                            :checked="
                                                form.complete_maintenance_book ===
                                                true
                                            "
                                            @update:checked="
                                                form.complete_maintenance_book =
                                                    $event ? true : null
                                            "
                                        />
                                        <label
                                            for="chk-maintenance"
                                            class="cursor-pointer text-sm text-slate-300"
                                            >Carnet entretien complet</label
                                        >
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <Checkbox
                                            id="chk-non-smoker"
                                            :checked="form.non_smoker === true"
                                            @update:checked="
                                                form.non_smoker = $event
                                                    ? true
                                                    : null
                                            "
                                        />
                                        <label
                                            for="chk-non-smoker"
                                            class="cursor-pointer text-sm text-slate-300"
                                            >Non fumeur</label
                                        >
                                    </div>
                                </div>
                            </CollapsibleContent>
                        </Collapsible>
                    </div>
                </Deferred>
            </aside>

            <!-- Main Content -->
            <main class="flex flex-1 flex-col gap-4">
                <!-- Vehicles List -->
                <div class="flex flex-col gap-4">
                    <Card
                        v-for="ad in ads.data"
                        :key="ad.id"
                        class="relative cursor-pointer border-2 border-transparent bg-slate-300 p-4 transition-colors hover:border-blue-500"
                        @click="
                            () => router.visit(vehiclesRoutes.show.url(ad.id))
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
                                {{ ad.brand?.name }} {{ ad.model?.name }}
                            </h4>
                            <p
                                class="text-sm font-semibold text-gray-700 uppercase"
                            >
                                {{ ad.vehicle_version?.name || '' }}
                            </p>

                            <div class="mt-2 flex flex-col gap-4 md:flex-row">
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

                                    <div class="mt-auto flex flex-wrap gap-2">
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
                                            {{ ad.transmission_type.code }}
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

                    <div
                        v-if="!ads.data.length"
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
                </div>

                <!-- Pagination -->
                <div
                    v-if="ads.data.length"
                    class="mt-4 flex items-center justify-between rounded-lg bg-slate-300 p-2 px-4 text-sm font-semibold"
                >
                    <Button
                        variant="ghost"
                        size="sm"
                        class="gap-1 bg-transparent hover:bg-slate-400"
                    >
                        <ChevronLeft class="h-4 w-4" /> Précédent
                    </Button>
                    <div class="flex gap-2">
                        <span
                            v-for="i in 5"
                            :key="i"
                            class="flex h-8 w-8 cursor-pointer items-center justify-center rounded transition hover:bg-slate-400"
                            :class="{
                                'bg-blue-500 text-white hover:bg-blue-600':
                                    i === 1,
                            }"
                            >{{ i }}</span
                        >
                    </div>
                    <Button
                        variant="ghost"
                        size="sm"
                        class="gap-1 bg-transparent hover:bg-slate-400"
                    >
                        Suivant <ChevronRight class="h-4 w-4" />
                    </Button>
                </div>
            </main>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import { Head, router, Deferred } from '@inertiajs/vue3';
import axios from 'axios';
import {
    ChevronRight,
    ChevronLeft,
    Star,
    Car as CarIcon,
    Search,
    MapPin,
} from 'lucide-vue-next';
import { ChevronDown } from 'lucide-vue-next';
import { ref, computed, watch } from 'vue';
import { defineComponent, h } from 'vue';
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import { Checkbox } from '@/components/ui/checkbox';
import {
    Collapsible,
    CollapsibleContent,
    CollapsibleTrigger,
} from '@/components/ui/collapsible';
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
import AppLayout from '@/layouts/AppLayout.vue';
import vehiclesRoutes from '@/routes/vehicles';

// ── Reusable filter sub-components ──────────────────────────────
// FilterSelect – a labelled Select with id/code or id/name options
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
                h(
                    Label,
                    { class: 'text-sm font-semibold text-slate-200' },
                    () => props.label,
                ),
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

// FilterSearchSelect – a labelled Select with search input to handle large lists
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
                h(
                    Label,
                    { class: 'text-sm font-semibold text-slate-200' },
                    () => props.label,
                ),
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
                                // Search input (non-selectable)
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

// FilterCheckboxGroup – a labelled collapsible list of checkboxes
const FilterCheckboxGroup = defineComponent({
    props: {
        label: String,
        modelValue: { type: Array as () => string[], default: () => [] },
        options: { type: Array as () => any[], default: () => [] },
        optionLabel: { type: String, default: 'code' },
    },
    emits: ['update:modelValue'],
    setup(props, { emit }) {
        const toggle = (id: string, checked: boolean) => {
            const current = [...props.modelValue];
            if (checked && !current.includes(id)) {
                current.push(id);
            } else if (!checked) {
                const idx = current.indexOf(id);
                if (idx > -1) current.splice(idx, 1);
            }
            emit('update:modelValue', current);
        };

        const activeCount = computed(() => props.modelValue.length);

        return () =>
            h(Collapsible, { class: 'space-y-3' }, () => [
                h(
                    CollapsibleTrigger,
                    { class: 'flex w-full items-center justify-between' },
                    () => [
                        h('span', { class: 'flex items-center gap-2' }, [
                            h(
                                Label,
                                {
                                    class: 'text-sm font-semibold text-slate-200 cursor-pointer',
                                },
                                () => props.label,
                            ),
                            activeCount.value > 0
                                ? h(
                                      'span',
                                      {
                                          class: 'rounded-full bg-blue-500 px-1.5 py-0.5 text-[10px] font-bold leading-none text-white',
                                      },
                                      String(activeCount.value),
                                  )
                                : null,
                        ]),
                        h(ChevronDown, {
                            class: 'h-4 w-4 text-slate-400 transition-transform duration-200 [[data-state=open]_&]:rotate-180',
                        }),
                    ],
                ),
                h(
                    CollapsibleContent,
                    { forceMount: true, class: 'data-[state=closed]:hidden' },
                    () =>
                        h(
                            'div',
                            { class: 'space-y-2 pt-2' },
                            props.options.map((o: any) =>
                                h(
                                    'div',
                                    {
                                        key: o.id,
                                        class: 'flex items-center space-x-2',
                                    },
                                    [
                                        h(Checkbox, {
                                            id: `${props.label}-${o.id}`,
                                            checked: props.modelValue.includes(
                                                String(o.id),
                                            ),
                                            'onUpdate:checked': (v: boolean) =>
                                                toggle(String(o.id), v),
                                        }),
                                        h(
                                            'label',
                                            {
                                                for: `${props.label}-${o.id}`,
                                                class: 'cursor-pointer text-sm text-slate-300',
                                            },
                                            o[props.optionLabel],
                                        ),
                                    ],
                                ),
                            ),
                        ),
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

// ── Props ───────────────────────────────────────────────────────
interface PaginationData {
    data: any[];
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
    filters?: Record<string, any>;
}>();

// ── Form state ──────────────────────────────────────────────────
const currentYear = new Date().getFullYear();
const f = props.filters || {};
const toArr = (v: any): string[] =>
    v ? (Array.isArray(v) ? v.map(String) : [String(v)]) : [];

const form = ref({
    brand_id: f.brand_id ? String(f.brand_id) : 'all',
    model_id: f.model_id ? String(f.model_id) : 'all',
    min_price: f.min_price ? Number(f.min_price) : 0,
    max_price: f.max_price ? Number(f.max_price) : 200000,
    min_year: f.min_year ? Number(f.min_year) : 2000,
    max_year: f.max_year ? Number(f.max_year) : currentYear,
    max_mileage: f.max_mileage ? String(f.max_mileage) : 'all',
    fuel_types: toArr(f.fuel_types),
    body_types: toArr(f.body_types),
    transmission_types: toArr(f.transmission_types),
    exterior_color_id: f.exterior_color_id
        ? String(f.exterior_color_id)
        : 'all',
    euro_norm_id: f.euro_norm_id ? String(f.euro_norm_id) : 'all',
    doors: f.doors ? String(f.doors) : 'all',
    seats: f.seats ? String(f.seats) : 'all',
    is_damaged:
        f.is_damaged !== undefined
            ? f.is_damaged === '0' || f.is_damaged === false
                ? false
                : null
            : null,
    has_accident:
        f.has_accident !== undefined
            ? f.has_accident === '0' || f.has_accident === false
                ? false
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
});

const yearRange = ref([form.value.min_year, form.value.max_year]);
const priceRange = ref([form.value.min_price, form.value.max_price]);

// ── On-demand data fetching ─────────────────────────────────────
const models = ref<any[]>([]);
const versions = ref<any[]>([]);

// Fetch models when brand changes
let isInitialBrandLoad = true;
watch(
    () => form.value.brand_id,
    async (brandId) => {
        if (!isInitialBrandLoad) {
            form.value.model_id = 'all';
            versions.value = [];
        }
        isInitialBrandLoad = false;
        models.value = [];

        if (!brandId || brandId === 'all') return;

        const { data } = await axios.get('/vehicle-models', {
            params: { brand_id: brandId },
        });
        models.value = data;
    },
    { immediate: true },
);

// Fetch versions when model changes
watch(
    () => form.value.model_id,
    async (modelId) => {
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
};

const onPriceChange = (values: number[] | undefined) => {
    if (!values) return;
    form.value.min_price = values[0];
    form.value.max_price = values[1];
};

// ── Apply filters (debounced) ───────────────────────────────────
let timeoutId: ReturnType<typeof setTimeout>;

const applyFilters = () => {
    clearTimeout(timeoutId);
    timeoutId = setTimeout(() => {
        const q: Record<string, any> = {};
        const v = form.value;

        if (v.brand_id && v.brand_id !== 'all') q.brand_id = v.brand_id;
        if (v.model_id && v.model_id !== 'all') q.model_id = v.model_id;
        if (v.min_price > 0) q.min_price = v.min_price;
        if (v.max_price < 200000) q.max_price = v.max_price;
        if (v.min_year > 2000) q.min_year = v.min_year;
        if (v.max_year < currentYear) q.max_year = v.max_year;
        if (v.max_mileage && v.max_mileage !== 'all')
            q.max_mileage = v.max_mileage;
        if (v.fuel_types.length) q.fuel_types = v.fuel_types;
        if (v.body_types.length) q.body_types = v.body_types;
        if (v.transmission_types.length)
            q.transmission_types = v.transmission_types;
        if (v.exterior_color_id && v.exterior_color_id !== 'all')
            q.exterior_color_id = v.exterior_color_id;
        if (v.euro_norm_id && v.euro_norm_id !== 'all')
            q.euro_norm_id = v.euro_norm_id;
        if (v.doors && v.doors !== 'all') q.doors = v.doors;
        if (v.seats && v.seats !== 'all') q.seats = v.seats;
        if (v.is_damaged === false) q.is_damaged = '0';
        if (v.has_accident === false) q.has_accident = '0';
        if (v.complete_maintenance_book === true)
            q.complete_maintenance_book = '1';
        if (v.non_smoker === true) q.non_smoker = '1';
        if (v.city) q.city = v.city;

        router.get(vehiclesRoutes.index.url(), q, {
            preserveState: true,
            replace: true,
            preserveScroll: true,
        });
    }, 400);
};

// Reactive: auto-apply when form changes
watch(
    () => form.value,
    () => applyFilters(),
    { deep: true },
);

const resetFilters = () => {
    clearTimeout(timeoutId);
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
