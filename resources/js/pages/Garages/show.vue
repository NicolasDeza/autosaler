<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { ArrowLeft, CheckCircle, MapPin, Phone } from 'lucide-vue-next';
import { ref, watch } from 'vue';
import AppContent from '@/components/AppContent.vue';
import AppPagination from '@/components/AppPagination.vue';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import VehicleAdCard from '@/components/VehicleAds/VehicleAdCard.vue';
import { useTranslation } from '@/composables/useTranslation';
import garageRoutes from '@/routes/garages';

type Garage = {
    id: number;
    name: string;
    address: string;
    city: {
        code: string;
        zip_code: string;
    } | null;
    country: {
        code: string;
    } | null;
    logo_url: string | null;
    background_url: string | null;
    phone: string | null;
};

type GarageVehicle = {
    id: number;
    brand: {
        id: number;
        name: string;
    } | null;
    model: {
        id: number;
        name: string;
    } | null;
    fuel_type: {
        id: number;
        code: string;
    } | null;
    transmission_type: {
        id: number;
        code: string;
    } | null;
    vehicle_version_name: string;
    price: number;
    mileage: number;
    first_registration_date: string | null;
    user: {
        company: {
            name: string;
        } | null;
    } | null;
    primary_image: {
        id: number;
        thumb: string;
        card: string;
        large: string;
    } | null;
};

const props = defineProps<{
    garage: Garage;
    vehicles: {
        data: GarageVehicle[];
        total: number;
        current_page: number;
        last_page: number;
        from: number;
        to: number;
        per_page: number;
        [key: string]: unknown;
    };
}>();

const { __ } = useTranslation();

const perPage = ref<string>(String(props.vehicles.per_page ?? 10));

const scrollToTop = (): void => {
    window.scrollTo({
        top: 0,
        behavior: 'smooth',
    });
};

const visitVehiclesPage = (page: number): void => {
    scrollToTop();

    router.get(
        garageRoutes.show(props.garage.id, {
            query: {
                page,
                per_page: perPage.value,
            },
        }).url,
        {},
        {
            preserveScroll: true,
            preserveState: true,
            replace: true,
        },
    );
};

const handlePageChange = (page: number): void => {
    visitVehiclesPage(page);
};

const handlePerPageChange = (value: string): void => {
    perPage.value = value;
    visitVehiclesPage(1);
};

watch(
    () => props.vehicles.per_page,
    (value) => {
        perPage.value = String(value ?? 10);
    },
);
</script>

<template>
    <Head :title="props.garage.name" />

    <AppContent>
        <section class="space-y-8 py-10 md:py-14">
            <Card class="gap-0 overflow-hidden border-border/70 p-0">
                <div class="relative aspect-[16/5.8] overflow-hidden">
                    <img
                        v-if="props.garage.background_url"
                        :src="props.garage.background_url"
                        :alt="props.garage.name"
                        class="h-full w-full object-cover"
                    />
                    <div
                        v-else
                        class="flex h-full w-full items-center justify-center bg-linear-to-br from-muted to-muted/60"
                    />

                    <div
                        class="absolute inset-0 bg-linear-to-t from-black/80 via-black/35 to-transparent"
                    />

                    <Button
                        as-child
                        variant="secondary"
                        class="absolute top-4 left-4 z-20 h-9 border border-white/30 bg-black/45 px-3 text-white backdrop-blur-md transition-all hover:border-white/60 hover:bg-black/55 hover:text-white"
                    >
                        <Link :href="garageRoutes.index().url">
                            <ArrowLeft class="mr-1.5 size-4" />
                            {{ __('garage.show_back_to_garages') }}
                        </Link>
                    </Button>

                    <div
                        class="absolute bottom-4 left-4 flex items-end gap-3 md:bottom-5 md:left-6"
                    >
                        <Avatar class="size-14 border-2 border-white/50 shadow-lg">
                            <AvatarImage
                                v-if="props.garage.logo_url"
                                :src="props.garage.logo_url"
                                :alt="props.garage.name"
                                class="object-cover"
                            />
                            <AvatarFallback class="bg-white/10 font-bold text-white">
                                {{ props.garage.name.charAt(0).toUpperCase() }}
                            </AvatarFallback>
                        </Avatar>

                        <div class="space-y-1">
                            <div class="flex items-center gap-2">
                                <span
                                    class="rounded-full bg-white/15 px-2 py-0.5 text-[10px] font-black tracking-wider text-white uppercase backdrop-blur-sm"
                                >
                                    {{ __('garage.show_seller') }}
                                </span>
                                <span
                                    class="inline-flex items-center gap-1 rounded-full bg-green-500/25 px-2 py-0.5 text-[10px] font-bold text-green-300"
                                >
                                    <CheckCircle class="size-3" />
                                    {{ __('garage.show_verified') }}
                                </span>
                            </div>
                            <h1
                                class="text-2xl leading-tight font-black tracking-tight text-white md:text-3xl"
                            >
                                {{ props.garage.name }}
                            </h1>
                        </div>
                    </div>
                </div>

                <div class="grid gap-4 p-5 md:grid-cols-3">
                    <div
                        class="rounded-lg border border-border bg-muted/30 p-4 text-sm text-muted-foreground"
                    >
                        <p class="mb-2 text-xs font-bold tracking-widest uppercase">
                            {{ __('garage.show_address') }}
                        </p>
                        <p class="flex items-start gap-2 leading-relaxed text-foreground">
                            <MapPin class="mt-0.5 size-4 shrink-0 text-primary/80" />
                            <span>
                                {{ props.garage.address }}<br />
                                {{ props.garage.city?.zip_code }}
                                {{ props.garage.city?.code }}<br />
                                {{ props.garage.country?.code }}
                            </span>
                        </p>
                    </div>

                    <div
                        class="rounded-lg border border-border bg-muted/30 p-4 text-sm text-muted-foreground"
                    >
                        <p class="mb-2 text-xs font-bold tracking-widest uppercase">
                            {{ __('garage.show_contact') }}
                        </p>
                        <p
                            v-if="props.garage.phone"
                            class="flex items-center gap-2 text-foreground"
                        >
                            <Phone class="size-4 text-primary/80" />
                            <a
                                :href="`tel:${props.garage.phone}`"
                                class="hover:text-primary hover:underline"
                            >
                                {{ props.garage.phone }}
                            </a>
                        </p>
                        <p v-else class="text-foreground/70">
                            {{ __('garage.show_phone_not_provided') }}
                        </p>
                    </div>

                    <div
                        class="rounded-lg border border-border bg-muted/30 p-4 text-sm text-muted-foreground"
                    >
                        <p class="mb-2 text-xs font-bold tracking-widest uppercase">
                            {{ __('garage.show_active_stock') }}
                        </p>
                        <p class="text-2xl font-black tracking-tight text-primary">
                            {{ props.vehicles.total }}
                        </p>
                        <p class="text-xs uppercase">{{ __('garage.show_visible_vehicles') }}</p>
                    </div>
                </div>
            </Card>

            <div class="space-y-1">
                <h2 class="text-xl font-black tracking-tight text-foreground">
                    {{ __('garage.show_vehicles_in_stock') }}
                </h2>
                <p class="text-sm text-muted-foreground">
                    {{ __('garage.show_active_ads_preview') }}
                </p>
            </div>

            <div
                v-if="props.vehicles.data.length === 0"
                class="rounded-xl border border-dashed border-border bg-card/50 px-6 py-12 text-center text-sm text-muted-foreground"
            >
                {{ __('garage.show_no_active_vehicles') }}
            </div>

            <div
                v-else
                class="grid grid-cols-1 items-stretch gap-4 md:grid-cols-2 md:gap-5 lg:grid-cols-4"
            >
                <VehicleAdCard
                    v-for="vehicle in props.vehicles.data"
                    :key="vehicle.id"
                    :ad="vehicle"
                    variant="grid"
                />
            </div>

            <AppPagination
                :pagination="props.vehicles"
                :per-page="perPage"
                resource-label="vehicules"
                @update:per-page="handlePerPageChange"
                @update:page="handlePageChange"
            />
        </section>
    </AppContent>
</template>
