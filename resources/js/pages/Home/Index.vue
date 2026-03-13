<script setup lang="ts">
import { Head, Deferred } from '@inertiajs/vue3';
import BrandsSection from '@/components/BrandsSection.vue';
import HeroSection from '@/components/HeroSection.vue';
import HomeSearchFilter from '@/components/HomeSearchFilter.vue';
import PriceCard from '@/components/PriceCard.vue';
import RecentVehiclesSection from '@/components/RecentVehiclesSection.vue';
import AppLayout from '@/layouts/AppLayout.vue';

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

defineProps<{
    canRegister: boolean;
    recentVehicles: Vehicle[];
    brands?: any[];
}>();
</script>

<template>
    <Head title="Véhicules d'occasion et neufs" />

    <AppLayout>
        <template #full-width>
            <HeroSection />
        </template>
        <Deferred data="brands">
            <template #fallback>
                <HomeSearchFilter :brands="[]" />
            </template>
            <HomeSearchFilter :brands="brands" />
        </Deferred>
        <RecentVehiclesSection :vehicles="recentVehicles" />
        <BrandsSection />
        <PriceCard />
    </AppLayout>
</template>
