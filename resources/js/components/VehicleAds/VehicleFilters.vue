<script setup lang="ts">
import { Deferred } from '@inertiajs/vue3';
import axios from 'axios';
import { ref, watch } from 'vue';
import { Skeleton } from '@/components/ui/skeleton';

// Import individual filter components
import BodyFilter from './Filters/BodyFilter.vue';
import BrandFilter from './Filters/BrandFilter.vue';
import CompanyFilter from './Filters/CompanyFilter.vue';
import DoorsFilter from './Filters/DoorsFilter.vue';
import EuroNormFilter from './Filters/EuroNormFilter.vue';
import ExteriorColorFilter from './Filters/ExteriorColorFilter.vue';
import FeaturesFilter from './Filters/FeaturesFilter.vue';
import FuelFilter from './Filters/FuelFilter.vue';
import InteriorColorFilter from './Filters/InteriorColorFilter.vue';
import InteriorTypeFilter from './Filters/InteriorTypeFilter.vue';
import LocationFilter from './Filters/LocationFilter.vue';
import MileageFilter from './Filters/MileageFilter.vue';
import ModelFilter from './Filters/ModelFilter.vue';
import PowerFilter from './Filters/PowerFilter.vue';
import PriceFilter from './Filters/PriceFilter.vue';
import SeatsFilter from './Filters/SeatsFilter.vue';
import StatusFilter from './Filters/StatusFilter.vue';
import TransmissionFilter from './Filters/TransmissionFilter.vue';
import VersionFilter from './Filters/VersionFilter.vue';
import YearFilter from './Filters/YearFilter.vue';

defineProps<{
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
    models?: any[];
}>();

const form = defineModel<any>('form', { required: true });

const emit = defineEmits(['update:models']);

// ── Fetching Logic (Coordinates between Brand, Model and Version) ──
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
                'features',
                'companies',
            ]"
        >
            <template #fallback>
                <div class="space-y-6">
                    <div v-for="i in 6" :key="i" class="space-y-3">
                        <Skeleton class="h-4 w-24 bg-muted" />
                        <Skeleton class="h-10 w-full rounded-md bg-muted/50" />
                    </div>
                </div>
            </template>

            <div class="space-y-6">
                <!-- Marque -->
                <BrandFilter v-model:form="form" :brands="brands ?? []" />

                <!-- Société -->
                <CompanyFilter
                    v-model:form="form"
                    :companies="companies ?? []"
                />

                <!-- Modèle -->
                <ModelFilter v-model:form="form" :models="models ?? []" />

                <!-- Version -->
                <VersionFilter v-model:form="form" :versions="versions" />

                <!-- Emplacement -->
                <LocationFilter
                    v-model:city="form.city"
                    v-model:cityId="form.city_id"
                />

                <!-- Prix -->
                <PriceFilter v-model:form="form" />

                <!-- Année -->
                <YearFilter v-model:form="form" />

                <!-- Kilométrage -->
                <MileageFilter v-model:form="form" />

                <!-- Carburant -->
                <FuelFilter v-model:form="form" :fuel-types="fuelTypes ?? []" />

                <!-- Carrosserie -->
                <BodyFilter v-model:form="form" :body-types="bodyTypes ?? []" />

                <!-- Transmission -->
                <TransmissionFilter
                    v-model:form="form"
                    :transmission-types="transmissionTypes ?? []"
                />

                <!-- Couleur extérieur -->
                <ExteriorColorFilter
                    v-model:form="form"
                    :exterior-colors="exteriorColors ?? []"
                />

                <!-- Couleur intérieur -->
                <InteriorColorFilter
                    v-model:form="form"
                    :interior-colors="interiorColors ?? []"
                />

                <!-- Matériaux intérieur -->
                <InteriorTypeFilter
                    v-model:form="form"
                    :interior-types="interiorTypes ?? []"
                />

                <!-- Euro Norm -->
                <EuroNormFilter
                    v-model:form="form"
                    :euro-norms="euroNorms ?? []"
                />

                <!-- Puissance -->
                <PowerFilter v-model:form="form" />

                <!-- Doors & Seats -->
                <div class="grid grid-cols-2 gap-4">
                    <DoorsFilter v-model:form="form" />
                    <SeatsFilter v-model:form="form" />
                </div>

                <!-- Status Checkboxes -->
                <StatusFilter v-model:form="form" />

                <!-- Equipements -->
                <FeaturesFilter
                    v-model:form="form"
                    :features="features ?? []"
                />
            </div>
        </Deferred>
    </div>
</template>
