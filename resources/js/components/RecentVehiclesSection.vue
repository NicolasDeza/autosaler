<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Fuel, Gauge, Calendar, ImageOff, Star, Cog } from 'lucide-vue-next';
import { show } from '@/actions/App/Http/Controllers/VehicleAdController';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { useTranslation } from '@/composables/useTranslation';

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
                class="grid grid-cols-2 items-stretch gap-4 md:gap-5 lg:grid-cols-4"
            >
                <Link
                    v-for="vehicle in vehicles"
                    :key="vehicle.id"
                    :href="show(vehicle.id).url"
                    class="group block h-full"
                >
                    <Card
                        class="h-full cursor-pointer gap-0 overflow-hidden rounded-lg border border-border bg-card p-0 transition-all duration-200 group-hover:border-primary/30 group-hover:shadow-xl md:rounded-xl"
                    >
                        <!-- Image placeholder -->
                        <div
                            class="flex aspect-4/3 items-center justify-center bg-muted md:aspect-video"
                        >
                            <ImageOff
                                class="size-10 text-muted-foreground/30 md:size-12"
                            />
                        </div>

                        <CardContent
                            class="flex min-h-57.5 flex-col gap-1.5 p-3 md:min-h-45 md:gap-2 md:p-3"
                        >
                            <!-- Titre : Marque + Modèle -->
                            <div
                                class="mb-2.5 flex h-[3.8rem] flex-col gap-0.5 md:mb-3 md:h-[4.2rem]"
                            >
                                <h3
                                    class="line-clamp-2 h-10 text-base leading-tight font-bold text-card-foreground md:h-[2.6rem] md:text-lg"
                                >
                                    {{ vehicle.brand?.name ?? '' }}
                                    {{ vehicle.model?.name ?? '' }}
                                </h3>
                                <p
                                    class="line-clamp-1 h-4 text-[11px] font-bold tracking-wider text-muted-foreground/80 uppercase"
                                >
                                    {{
                                        vehicle.vehicle_version?.name ??
                                        vehicle.vehicle_version_name ??
                                        ''
                                    }}
                                </p>
                            </div>

                            <!-- Prix -->
                            <div class="flex flex-col">
                                <span
                                    class="block h-7 text-lg leading-tight font-extrabold text-primary md:h-8 md:text-xl"
                                >
                                    {{ formatPrice(vehicle.price) }}
                                </span>
                                <span
                                    class="text-[10px] font-medium tracking-wider text-muted-foreground/60 uppercase"
                                    >TVAC</span
                                >
                            </div>

                            <!-- Infos : date, km, carburant, boîte -->
                            <div
                                class="flex h-18 flex-col gap-1 text-xs text-muted-foreground md:h-auto md:flex-row md:flex-wrap md:gap-2"
                            >
                                <span
                                    class="flex items-center gap-1 md:gap-1.5"
                                >
                                    <Calendar
                                        class="size-3 shrink-0 text-primary md:size-3.5"
                                    />
                                    <span>{{
                                        formatDate(
                                            vehicle.first_registration_date,
                                        )
                                    }}</span>
                                </span>
                                <span
                                    class="flex items-center gap-1 md:gap-1.5"
                                >
                                    <Gauge
                                        class="size-3 shrink-0 text-primary md:size-3.5"
                                    />
                                    <span>{{
                                        formatMileage(vehicle.mileage)
                                    }}</span>
                                </span>
                                <span
                                    v-if="vehicle.fuel_type"
                                    class="flex items-center gap-1 md:gap-1.5"
                                >
                                    <Fuel
                                        class="size-3 shrink-0 text-primary md:size-3.5"
                                    />
                                    <span>{{ vehicle.fuel_type.code }}</span>
                                </span>
                                <span
                                    v-if="vehicle.transmission_type"
                                    class="flex items-center gap-1 md:gap-1.5"
                                >
                                    <Cog
                                        class="size-3 shrink-0 text-primary md:size-3.5"
                                    />
                                    <span>{{
                                        vehicle.transmission_type.code
                                    }}</span>
                                </span>
                            </div>

                            <!-- Favoris -->
                            <div
                                class="mt-auto flex items-center justify-between border-t border-border pt-2 md:pt-2"
                            >
                                <span
                                    class="truncate text-xs font-medium text-muted-foreground"
                                >
                                    {{
                                        vehicle.user?.company?.name ??
                                        __('recentVehicles.private_seller')
                                    }}
                                </span>
                                <button
                                    @click.prevent
                                    class="cursor-pointer rounded-md border border-border p-1.5 text-muted-foreground/70 transition-all duration-200 hover:border-primary hover:bg-primary/10 hover:text-primary md:p-2"
                                    :aria-label="
                                        __('recentVehicles.add_to_favorites')
                                    "
                                >
                                    <Star class="size-4 md:size-4.5" />
                                </button>
                            </div>
                        </CardContent>
                    </Card>
                </Link>
            </div>

            <div v-else class="py-12 text-center text-sm text-muted-foreground">
                {{ __('recentVehicles.empty') }}
            </div>

            <div class="mt-8 flex justify-center">
                <Button
                    size="lg"
                    class="cursor-pointer px-4 py-4 text-base font-semibold"
                >
                    {{ __('recentVehicles.search_button') }}
                </Button>
            </div>
        </div>
    </section>
</template>
