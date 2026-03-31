<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import VehicleAdCard from '@/components/VehicleAds/VehicleAdCard.vue';
import { useTranslation } from '@/composables/useTranslation';
import vehiclesRoutes from '@/routes/vehicles';

const { __ } = useTranslation();

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
    is_favorited?: boolean;
}

defineProps<{
    vehicles: Vehicle[];
}>();
</script>

<template>
    <section class="w-full py-8 sm:py-12">
        <div class="mx-auto max-w-360 px-6">
            <div class="mb-8 text-center">
                <h2
                    class="text-3xl font-extrabold tracking-wide text-foreground uppercase"
                >
                    {{ __('recentVehicles.title_prefix') }}
                    <span class="text-primary">{{
                        __('recentVehicles.title_highlight')
                    }}</span>
                </h2>
            </div>

            <div
                v-if="vehicles.length"
                class="grid grid-cols-1 items-stretch gap-4 md:grid-cols-2 md:gap-5 lg:grid-cols-4"
            >
                <VehicleAdCard
                    v-for="vehicle in vehicles"
                    :key="vehicle.id"
                    :ad="vehicle"
                    variant="grid"
                />
            </div>

            <div v-else class="py-12 text-center text-sm text-muted-foreground">
                {{ __('recentVehicles.empty') }}
            </div>

            <div class="mt-8 flex justify-center">
                <Button
                    as-child
                    size="lg"
                    class="cursor-pointer px-4 py-4 text-base font-semibold"
                >
                    <Link :href="vehiclesRoutes.index().url">
                        {{ __('recentVehicles.search_button') }}
                    </Link>
                </Button>
            </div>
        </div>
    </section>
</template>
