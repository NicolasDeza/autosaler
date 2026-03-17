<script setup lang="ts">
import { router, usePage } from '@inertiajs/vue3';
import {
    Star,
    Gauge,
    Fuel,
    Calendar,
    Cog,
    Car as CarIcon,
} from 'lucide-vue-next';
import { ref } from 'vue';
import LoginRequiredModal from '@/components/Auth/LoginRequiredModal.vue';
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import { Checkbox } from '@/components/ui/checkbox';
import { useComparison } from '@/composables/useComparison';
import { useTranslation } from '@/composables/useTranslation';
import vehiclesRoutes from '@/routes/vehicles';

defineProps<{
    ad: any;
}>();

const { __ } = useTranslation();
const { addVehicle, removeVehicle, isSelected } = useComparison();
const page = usePage();
const showLoginModal = ref(false);

const toggleComparison = (ad: any) => {
    if (isSelected(ad.id)) {
        removeVehicle(ad.id);
    } else {
        addVehicle({
            id: ad.id,
            brand: ad.brand?.name,
            model: ad.model?.name,
            vehicle_version_name:
                ad.vehicle_version_name || ad.vehicle_version?.name,
            price: Number(ad.price),
        });
    }
};

const toggleFavorite = (adId: number) => {
    if (!page.props.auth?.user) {
        showLoginModal.value = true;
        return;
    }
    router.post(
        vehiclesRoutes.favorite.url({ vehicleAd: adId }),
        {},
        {
            preserveScroll: true,
        },
    );
};
</script>

<template>
    <Card
        class="group relative cursor-pointer overflow-hidden border border-border bg-card p-0 transition-all duration-300 hover:-translate-y-0.5 hover:border-primary/30 hover:shadow-lg"
        @click="() => router.visit(vehiclesRoutes.show.url(ad.id))"
    >
        <div class="flex flex-col md:flex-row">
            <div
                class="relative h-56 w-full shrink-0 overflow-hidden bg-muted md:h-auto md:w-72"
            >
                <div
                    class="flex h-full w-full items-center justify-center bg-muted transition-transform duration-500 group-hover:scale-105"
                >
                    <CarIcon class="size-16 text-muted-foreground/20" />
                </div>
            </div>

            <div class="flex flex-1 flex-col justify-between p-5 sm:p-6">
                <div
                    class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between sm:gap-4"
                >
                    <div class="min-w-0">
                        <h4
                            class="text-xl font-black tracking-tight text-foreground uppercase sm:truncate"
                        >
                            {{ ad.brand?.name }}
                            {{ ad.model?.name }}
                        </h4>
                        <p
                            v-if="ad.vehicle_version?.name"
                            class="mt-0.5 text-xs font-bold tracking-wider text-muted-foreground/80 uppercase"
                        >
                            {{ ad.vehicle_version.name }}
                        </p>
                    </div>

                    <div class="flex flex-col sm:items-end">
                        <div
                            class="inline-flex self-start bg-primary py-0.5 pr-3.5 pl-2 text-white shadow-sm [clip-path:polygon(0_0,90%_0,100%_100%,0_100%)] sm:self-auto sm:py-1 sm:pr-4 sm:pl-6 sm:[clip-path:polygon(10%_0,100%_0,100%_100%,0_100%)]"
                        >
                            <div class="flex items-end gap-2">
                                <span
                                    class="text-lg font-black tracking-tight whitespace-nowrap sm:text-xl"
                                >
                                    {{
                                        Number(ad.price).toLocaleString('fr-FR')
                                    }}
                                    €
                                </span>
                                <span
                                    class="pb-0.5 text-xs font-semibold tracking-[0.18em] text-white/80 uppercase"
                                >
                                    {{ __('vehicleAd.vat_included') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="mt-6 grid grid-cols-2 gap-4 border-t border-border pt-4 sm:mt-12 lg:-ml-4 lg:flex lg:flex-nowrap lg:gap-0 lg:border-t-0 lg:pt-0"
                >
                    <div
                        class="flex items-center gap-2 text-sm font-medium text-foreground/70 lg:border-r lg:border-border lg:px-4"
                    >
                        <Calendar class="size-4 shrink-0 text-primary/60" />
                        {{
                            ad.first_registration_date?.substring(0, 4) || 'N/A'
                        }}
                    </div>
                    <div
                        class="flex items-center gap-2 text-sm font-medium text-foreground/70 lg:border-r lg:border-border lg:px-4"
                    >
                        <Gauge class="size-4 shrink-0 text-primary/60" />
                        {{ ad.mileage?.toLocaleString('fr-FR') }}
                        km
                    </div>
                    <div
                        v-if="ad.fuel_type"
                        class="flex items-center gap-2 text-sm font-medium text-foreground/70 lg:border-r lg:border-border lg:px-4"
                    >
                        <Fuel class="size-4 shrink-0 text-primary/60" />
                        {{ ad.fuel_type.code }}
                    </div>
                    <div
                        v-if="ad.transmission_type"
                        class="flex items-center gap-2 text-sm font-medium text-foreground/70 lg:px-4"
                    >
                        <Cog class="size-4 shrink-0 text-primary/60" />
                        {{ ad.transmission_type.code }}
                    </div>
                </div>

                <div
                    class="mt-5 flex items-center justify-between border-t border-border pt-4"
                >
                    <div class="text-sm leading-tight">
                        <span class="block font-bold text-foreground">{{
                            ad.user?.company?.name ?? '—'
                        }}</span>
                        <span class="text-muted-foreground"
                            >{{ ad.user?.company?.city?.zip_code }}
                            {{ ad.user?.company?.city?.code }}</span
                        >
                    </div>

                    <div class="flex items-center gap-2">
                        <Button
                            variant="ghost"
                            class="hidden h-9 cursor-pointer items-center gap-2 rounded-md border border-border bg-background px-3 transition-all hover:border-primary/30 lg:flex"
                            :class="{
                                'border-primary/50 bg-primary/5 text-primary':
                                    isSelected(ad.id),
                            }"
                            @click.stop="toggleComparison(ad)"
                        >
                            <Checkbox
                                :id="`compare-${ad.id}`"
                                :model-value="isSelected(ad.id)"
                                class="pointer-events-none size-4"
                            />
                            <span
                                class="text-2xs font-bold tracking-tight uppercase"
                                >{{ __('vehicleAd.compare') }}</span
                            >
                        </Button>

                        <Button
                            variant="ghost"
                            size="icon"
                            class="size-9 cursor-pointer rounded-md border border-border bg-background transition-all hover:border-primary hover:bg-primary/10 hover:text-primary"
                            :class="{
                                'fill-primary text-primary': ad.is_favorited,
                            }"
                            @click.stop="toggleFavorite(ad.id)"
                        >
                            <Star
                                class="size-4"
                                :class="{
                                    'fill-primary': ad.is_favorited,
                                }"
                            />
                        </Button>
                    </div>
                </div>
            </div>
        </div>

        <LoginRequiredModal
            v-model:open="showLoginModal"
            :title="__('vehicleAd.favorite_modal_title')"
            :description="__('vehicleAd.favorite_modal_description')"
        />
    </Card>
</template>
