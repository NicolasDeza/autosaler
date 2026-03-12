<script setup lang="ts">
import axios from 'axios';
import { MapPin } from 'lucide-vue-next';
import { ref, watch } from 'vue';
import { Input } from '@/components/ui/input';
import FilterGroup from '@/components/VehicleAds/FilterGroup.vue';

const city = defineModel<string>('city');
const cityId = defineModel<string | number>('cityId');

const cities = ref<any[]>([]);
const showCities = ref(false);
const isSearchingCities = ref(false);
const searchTerm = ref(city.value || '');

watch(
    () => city.value,
    (newVal) => {
        searchTerm.value = newVal || '';
    },
);

// If the user clears the search term manually, we clear the filter too
watch(searchTerm, (newVal) => {
    if (!newVal && (city.value || cityId.value)) {
        city.value = '';
        cityId.value = '';
    }
});

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

const selectCity = (selectedCity: any) => {
    const value = selectedCity.zip_code + ' ' + selectedCity.code;
    searchTerm.value = value;
    city.value = value;
    cityId.value = selectedCity.id;
    showCities.value = false;
};

const handleCityBlur = () => {
    setTimeout(() => {
        showCities.value = false;
    }, 200);
};
</script>

<template>
    <FilterGroup
        label="Emplacement"
        :icon="MapPin"
        :is-active="!!city"
        class="relative z-50"
        overflow-visible
    >
        <div class="relative space-y-3">
            <div class="relative h-10 w-full">
                <MapPin
                    :size="14"
                    class="absolute top-1/2 left-3 z-10 -translate-y-1/2 text-muted-foreground"
                />
                <Input
                    v-model="searchTerm"
                    placeholder="Localisation"
                    class="h-full w-full border-input bg-background pl-9 text-foreground placeholder-muted-foreground"
                    @input="searchCities(searchTerm)"
                    @focus="searchTerm.length >= 2 ? (showCities = true) : null"
                    @blur="handleCityBlur"
                />
            </div>

            <!-- City Suggestions -->
            <div
                v-if="showCities && (cities.length > 0 || isSearchingCities)"
                class="absolute top-full left-0 z-100 mt-1 w-full animate-in overflow-hidden rounded-md border border-border bg-popover text-popover-foreground shadow-xl fade-in-80"
            >
                <div class="max-h-60 overflow-y-auto p-1">
                    <div
                        v-for="city in cities"
                        :key="city.id"
                        class="flex cursor-pointer items-center rounded-sm px-2 py-1.5 text-sm outline-none select-none hover:bg-accent hover:text-accent-foreground"
                        @click="selectCity(city)"
                    >
                        <span class="mr-2 font-medium text-popover-foreground">{{
                            city.zip_code
                        }}</span>
                        <span class="text-muted-foreground">{{ city.code }}</span>
                    </div>
                    <div
                        v-if="cities.length === 0 && isSearchingCities"
                        class="p-2 text-center text-sm text-muted-foreground"
                    >
                        Recherche...
                    </div>
                </div>
            </div>
        </div>
    </FilterGroup>
</template>
