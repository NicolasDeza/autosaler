<script setup lang="ts">
import { Fuel, Gauge, Calendar, ImageOff, Star } from 'lucide-vue-next';
import { Badge } from '@/components/ui/badge';
import { Card, CardContent } from '@/components/ui/card';
import { useTranslation } from '@/composables/useTranslation';
import { Link } from '@inertiajs/vue3';
import { show } from '@/actions/App/Http/Controllers/VehicleAdController';

const { __ } = useTranslation();

interface Vehicle {
    id: number;
    price: string;
    mileage: number;
    first_registration_date: string | null;
    brand: { id: number; name: string } | null;
    model: { id: number; name: string } | null;
    fuel_type: { id: number; code: string } | null;
    transmission_type: { id: number; code: string } | null;
    user: { company: { name: string } | null } | null;
}

defineProps<{
    vehicles: Vehicle[];
}>();

const formatPrice = (price: string | number) => {
    return Number(price).toLocaleString('fr-FR') + ' €';
};

const formatMileage = (km: number) => {
    return km.toLocaleString('fr-FR') + ' km';
};

const formatDate = (date: string | null) => {
    if (!date) return '—';
    return new Date(date).toLocaleDateString('fr-FR', {
        month: '2-digit',
        year: 'numeric',
    });
};
</script>

<template>
    <section class="w-full py-8 sm:py-12">
        <div class="mx-auto max-w-360 px-6">
            <div class="mb-8 text-center">
                <h2
                    class="text-3xl font-extrabold tracking-wide text-gray-800 uppercase"
                >
                    {{ __('recentVehicles.title_prefix') }}
                    <span class="text-red-600">{{
                        __('recentVehicles.title_highlight')
                    }}</span>
                </h2>
            </div>

            <div
                v-if="vehicles.length"
                class="grid grid-cols-2 gap-4 lg:grid-cols-4"
            >
                <Link
                    v-for="vehicle in vehicles"
                    :key="vehicle.id"
                    :href="show(vehicle.id).url"
                    class="group block"
                >
                    <Card
                        class="h-full cursor-pointer gap-0 overflow-hidden p-0 transition-shadow group-hover:shadow-md"
                    >
                        <!-- Image placeholder -->
                        <div
                            class="flex aspect-4/3 items-center justify-center bg-gray-100"
                        >
                            <ImageOff :size="40" class="text-gray-300" />
                        </div>

                        <CardContent class="flex flex-col gap-2 p-3">
                            <!-- Titre : Marque + Modèle -->
                            <h3
                                class="truncate text-base font-bold text-gray-900"
                            >
                                {{ vehicle.brand?.name ?? '' }}
                                {{ vehicle.model?.name ?? '' }}
                            </h3>

                            <!-- Prix -->
                            <span class="text-lg font-extrabold text-gray-900">
                                {{ formatPrice(vehicle.price) }}
                            </span>

                            <!-- Infos : date, km, carburant -->
                            <div
                                class="flex items-center gap-3 text-xs text-gray-500"
                            >
                                <span class="flex items-center gap-1">
                                    <Calendar :size="12" />
                                    {{
                                        formatDate(
                                            vehicle.first_registration_date,
                                        )
                                    }}
                                </span>
                                <span class="flex items-center gap-1">
                                    <Gauge :size="12" />
                                    {{ formatMileage(vehicle.mileage) }}
                                </span>
                                <span
                                    v-if="vehicle.fuel_type"
                                    class="flex items-center gap-1"
                                >
                                    <Fuel :size="12" />
                                    {{ vehicle.fuel_type.code }}
                                </span>
                            </div>

                            <!-- Badges -->
                            <div class="mt-1 flex gap-1.5">
                                <Badge
                                    v-if="vehicle.transmission_type"
                                    variant="secondary"
                                    class="px-1.5 py-0 text-[10px]"
                                >
                                    {{ vehicle.transmission_type.code }}
                                </Badge>
                            </div>

                            <!-- Favoris -->
                            <div
                                class="mt-auto flex items-center justify-between pt-2"
                            >
                                <span class="truncate text-xs text-gray-400">
                                    {{
                                        vehicle.user?.company?.name ??
                                        __('recentVehicles.private_seller')
                                    }}
                                </span>
                                <button
                                    @click.prevent
                                    class="cursor-pointer rounded-md border border-gray-200 p-1.5 text-gray-300 transition-colors duration-200 hover:border-red-500 hover:text-red-500"
                                >
                                    <Star :size="16" />
                                </button>
                            </div>
                        </CardContent>
                    </Card>
                </Link>
            </div>

            <div v-else class="py-12 text-center text-sm text-gray-400">
                {{ __('recentVehicles.empty') }}
            </div>

            <div class="mt-8 flex justify-center">
                <button
                    class="cursor-pointer rounded-md border-2 border-red-500 bg-white px-6 py-2.5 text-base font-semibold text-red-500 transition-all duration-200 hover:scale-105"
                >
                    {{ __('recentVehicles.search_button') }}
                </button>
            </div>
        </div>
    </section>
</template>
