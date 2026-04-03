<script setup lang="ts">
import { useTranslation } from '@/composables/useTranslation';
import { useVehicleAdFieldTranslation } from '@/composables/useVehicleAdFieldTranslation';

const props = defineProps<{
    ad: any;
}>();

const { __ } = useTranslation();
const { translateVehicleAdField } = useVehicleAdFieldTranslation();
</script>

<template>
    <div class="rounded-lg border bg-card p-6 shadow-sm">
        <h3 class="mb-6 text-lg font-bold">
            {{ __('vehicleAd.technical_info') }}
        </h3>
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
            <div class="text-sm">
                <div class="flex items-center justify-between border-b py-2.5">
                    <span class="text-muted-foreground">{{
                        __('vehicleAd.displacement')
                    }}</span>
                    <span class="font-medium"
                        >{{ ad.engine_displacement ?? '-' }} cm³</span
                    >
                </div>
                <div class="flex items-center justify-between border-b py-2.5">
                    <span class="text-muted-foreground">{{
                        __('vehicleAd.cylinders')
                    }}</span>
                    <span class="font-medium">{{
                        ad.cylinder_count ?? '-'
                    }}</span>
                </div>
                <div class="flex items-center justify-between border-b py-2.5">
                    <span class="text-muted-foreground">{{
                        __('vehicleAd.gear_count')
                    }}</span>
                    <span class="font-medium">{{ ad.gear_count ?? '-' }}</span>
                </div>
                <div class="flex items-center justify-between border-b py-2.5">
                    <span class="text-muted-foreground">{{
                        __('vehicleAd.weight')
                    }}</span>
                    <span class="font-medium">{{ ad.weight_kg ?? '-' }} kg</span>
                </div>
                <div class="flex items-center justify-between py-2.5">
                    <span class="text-muted-foreground">{{
                        __('vehicleAd.doors_seats')
                    }}</span>
                    <span class="font-medium"
                        >{{ ad.doors ?? '-' }} / {{ ad.seats ?? '-' }}</span
                    >
                </div>
            </div>
            <div class="text-sm">
                <div class="flex items-center justify-between border-b py-2.5">
                    <span class="text-muted-foreground">{{
                        __('vehicleAd.euro_norm')
                    }}</span>
                    <span class="font-medium">{{
                        ad.euro_norm?.code
                            ? translateVehicleAdField(
                                  'euro_norms',
                                  ad.euro_norm.code,
                              )
                            : '-'
                    }}</span>
                </div>
                <div class="flex items-center justify-between border-b py-2.5">
                    <span class="text-muted-foreground">{{
                        __('vehicleAd.co2_consumption')
                    }}</span>
                    <span class="font-medium"
                        >{{ ad.co2_emission ?? '-' }} g/km /
                        {{ ad.fuel_consumption_avg ?? '-' }} L</span
                    >
                </div>
                <div class="flex items-center justify-between border-b py-2.5">
                    <span class="text-muted-foreground">{{
                        __('vehicleAd.exterior_color')
                    }}</span>
                    <span class="font-medium"
                        >{{
                            ad.exterior_color?.code
                                ? translateVehicleAdField(
                                      'exterior_colors',
                                      ad.exterior_color.code,
                                  )
                                : '-'
                        }}<span v-if="ad.exterior_color_metalised">
                            ({{ __('vehicleAd.metallic') }})</span
                        ></span
                    >
                </div>
                <div class="flex items-center justify-between border-b py-2.5">
                    <span class="text-muted-foreground">{{
                        __('vehicleAd.interior')
                    }}</span>
                    <span class="font-medium"
                        >{{
                            ad.interior_color?.code
                                ? translateVehicleAdField(
                                      'interior_colors',
                                      ad.interior_color.code,
                                  )
                                : '-'
                        }},
                        {{
                            ad.interior_type?.code
                                ? translateVehicleAdField(
                                      'interior_types',
                                      ad.interior_type.code,
                                  )
                                : '-'
                        }}</span
                    >
                </div>
                <div class="flex items-center justify-between py-2.5">
                    <span class="text-muted-foreground">{{
                        __('vehicleAd.previous_owners')
                    }}</span>
                    <span class="font-medium">{{
                        ad.previous_owner ?? '-'
                    }}</span>
                </div>
            </div>
        </div>
    </div>
</template>
