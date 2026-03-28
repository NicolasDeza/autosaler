<script setup lang="ts">
import { Calendar, Cog, Fuel, Gauge, Truck, Zap } from 'lucide-vue-next';
import { useTranslation } from '@/composables/useTranslation';
import { kwToHp } from '@/lib/utils';

const props = defineProps<{
    ad: any;
}>();

const { __ } = useTranslation();
</script>

<template>
    <div class="p-2 sm:p-0">
        <div class="grid grid-cols-2 gap-y-6 sm:grid-cols-3">
            <div class="flex flex-col gap-1.5">
                <div class="flex items-center gap-1.5">
                    <Gauge class="h-3.5 w-3.5 text-primary" />
                    <span
                        class="text-xs font-bold text-muted-foreground uppercase"
                        >{{ __('vehicleAd.mileage') }}</span
                    >
                </div>
                <span class="font-semibold"
                    >{{ ad.mileage.toLocaleString() }} km</span
                >
            </div>
            <div class="flex flex-col gap-1.5">
                <div class="flex items-center gap-1.5">
                    <Calendar class="h-3.5 w-3.5 text-primary" />
                    <span
                        class="text-xs font-bold text-muted-foreground uppercase"
                        >{{ __('vehicleAd.first_registration') }}</span
                    >
                </div>
                <span class="font-semibold">{{
                    new Date(ad.first_registration_date).toLocaleDateString()
                }}</span>
            </div>
            <div class="flex flex-col gap-1.5" v-if="ad.power_kw">
                <div class="flex items-center gap-1.5">
                    <Zap class="h-3.5 w-3.5 text-primary" />
                    <span
                        class="text-xs font-bold text-muted-foreground uppercase"
                        >{{ __('vehicleAd.power') }}</span
                    >
                </div>
                <span class="font-semibold">
                    {{ ad.power_kw }} kW ({{ kwToHp(ad.power_kw) }}
                    ch)
                </span>
            </div>
            <div class="flex flex-col gap-1.5" v-if="ad.fuel_type">
                <div class="flex items-center gap-1.5">
                    <Fuel class="h-3.5 w-3.5 text-primary" />
                    <span
                        class="text-xs font-bold text-muted-foreground uppercase"
                        >{{ __('vehicleAd.fuel') }}</span
                    >
                </div>
                <span class="font-semibold">{{ ad.fuel_type?.code }}</span>
            </div>
            <div class="flex flex-col gap-1.5" v-if="ad.transmission_type">
                <div class="flex items-center gap-1.5">
                    <Cog class="h-3.5 w-3.5 text-primary" />
                    <span
                        class="text-xs font-bold text-muted-foreground uppercase"
                        >{{ __('vehicleAd.transmission') }}</span
                    >
                </div>
                <span class="font-semibold">{{
                    ad.transmission_type?.code
                }}</span>
            </div>
            <div class="flex flex-col gap-1.5" v-if="ad.body_type">
                <div class="flex items-center gap-1.5">
                    <Truck class="h-3.5 w-3.5 text-primary" />
                    <span
                        class="text-xs font-bold text-muted-foreground uppercase"
                        >{{ __('vehicleAd.body') }}</span
                    >
                </div>
                <span class="font-semibold">{{ ad.body_type?.code }}</span>
            </div>
        </div>
    </div>
</template>
