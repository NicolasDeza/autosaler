<script setup lang="ts">
import { Head, Deferred } from '@inertiajs/vue3';
import AppContent from '@/components/AppContent.vue';
import BrandsSection from '@/components/BrandsSection.vue';
import GarageHome from '@/components/GarageHome.vue';
import HeroSection from '@/components/HeroSection.vue';
import HomeSearchFilter from '@/components/HomeSearchFilter.vue';
import RecentVehiclesSection from '@/components/RecentVehiclesSection.vue';

interface Vehicle {
    id: number;
    price: string;
    mileage: number;
    first_registration_date: string | null;
    vehicle_version_name?: string | null;
    vehicle_version?: { name: string } | null;
    brand: { id: number; name: string } | null;
    model: { id: number; name: string } | null;
    fuel_type: { id: number; code: string } | null;
    transmission_type: { id: number; code: string } | null;
    user: { company: { name: string } | null } | null;
}

interface FeaturedGarage {
    id: number;
    name: string;
    address: string;
    city: { code: string; zip_code: string } | null;
    logo_url: string | null;
    background_url: string | null;
    active_vehicle_ads_count: number;
}

defineProps<{
    recentVehicles: Vehicle[];
    featuredGarages: FeaturedGarage[];
    brands?: any[];
}>();
</script>

<template>
    <Head title="Véhicules d'occasion et neufs" />

    <HeroSection />

    <AppContent>
        <Deferred data="brands">
            <template #fallback>
                <HomeSearchFilter :brands="[]" />
            </template>
            <HomeSearchFilter :brands="brands" />
        </Deferred>

        <RecentVehiclesSection :vehicles="recentVehicles" />
        <GarageHome :garages="featuredGarages" />
        <BrandsSection :brands="brands" />
    </AppContent>
</template>
