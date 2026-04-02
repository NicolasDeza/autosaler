<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { MapPin, Warehouse } from 'lucide-vue-next';
import { Avatar, AvatarImage } from '@/components/ui/avatar';
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import { useTranslation } from '@/composables/useTranslation';
import garageRoutes from '@/routes/garages';
import vehiclesRoutes from '@/routes/vehicles';

type Garage = {
    id: number;
    name: string;
    address: string;
    city: {
        code: string;
        zip_code: string;
    } | null;
    logo_url: string | null;
    background_url: string | null;
    active_vehicle_ads_count: number;
};

defineProps<{
    garages: Garage[];
}>();

const { __ } = useTranslation();

const getStockLabel = (count: number): string => {
    return count === 1
        ? __('garage.stock_label_singular')
        : __('garage.stock_label_plural');
};
</script>

<template>
    <section class="w-full py-8 sm:py-12">
        <div class="mx-auto max-w-360 px-6">
            <div class="mb-7 text-center">
                <h2 class="text-3xl font-extrabold tracking-wide text-foreground uppercase">
                    {{ __('garage.home_title_prefix') }}
                    <span class="text-primary">{{ __('garage.home_title_highlight') }}</span>
                </h2>
            </div>

            <div
                v-if="garages.length === 0"
                class="rounded-xl border border-dashed border-border bg-card/50 px-6 py-12 text-center text-sm text-muted-foreground"
            >
                {{ __('garage.empty_state') }}
            </div>

            <div v-else class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <Link
                    v-for="garage in garages"
                    :key="garage.id"
                    :href="vehiclesRoutes.index({ query: { company_id: garage.id } }).url"
                    class="group block"
                >
                    <Card
                        class="gap-0 overflow-hidden border border-border bg-card p-0 transition-all duration-300 hover:-translate-y-0.5 hover:border-primary/30 hover:shadow-lg"
                    >
                        <div class="relative aspect-16/8.5 overflow-hidden">
                            <img
                                v-if="garage.background_url"
                                :src="garage.background_url"
                                :alt="garage.name"
                                class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105"
                                loading="lazy"
                            />
                            <div
                                v-else
                                class="flex h-full w-full items-center justify-center bg-linear-to-br from-muted/95 via-muted/80 to-muted/60"
                            >
                                <Warehouse class="size-11 text-muted-foreground/55" />
                            </div>

                            <div
                                v-if="garage.background_url"
                                class="absolute inset-0 bg-linear-to-t from-black/80 via-black/35 to-transparent"
                            />

                            <div
                                v-if="garage.logo_url"
                                class="absolute bottom-2.5 left-2.5"
                            >
                                <Avatar
                                    :class="
                                        garage.background_url
                                            ? 'size-9 border border-white/40 shadow-md'
                                            : 'size-9 border border-border bg-background/90 shadow-md'
                                    "
                                >
                                    <AvatarImage
                                        :src="garage.logo_url"
                                        :alt="garage.name"
                                        class="object-cover"
                                    />
                                </Avatar>
                            </div>

                        </div>

                        <div class="space-y-2.5 p-3.5">
                            <h3 class="line-clamp-1 text-lg leading-tight font-black text-foreground">
                                {{ garage.name }}
                            </h3>
                            <p class="text-[15px] font-semibold">
                                <span class="font-black text-primary">
                                    {{ garage.active_vehicle_ads_count }}
                                </span>
                                <span class="text-foreground">
                                    {{ ` ${getStockLabel(garage.active_vehicle_ads_count)}` }}
                                </span>
                            </p>
                            <p
                                class="flex items-start gap-2 text-[15px] leading-snug text-muted-foreground"
                            >
                                <MapPin class="mt-0.5 size-3.5 shrink-0 text-primary/70" />
                                <span>
                                    {{ garage.address }}<br />
                                    {{ garage.city?.zip_code }}
                                    {{ garage.city?.code }}
                                </span>
                            </p>

                        </div>
                    </Card>
                </Link>
            </div>

            <div class="mt-8 flex justify-center">
                <Button as-child class="shrink-0 font-bold">
                    <Link :href="garageRoutes.index().url">
                        {{ __('garage.home_cta') }}
                    </Link>
                </Button>
            </div>
        </div>
    </section>
</template>
