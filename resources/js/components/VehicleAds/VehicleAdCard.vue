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
import { useVehicleAdFieldTranslation } from '@/composables/useVehicleAdFieldTranslation';
import vehiclesRoutes from '@/routes/vehicles';

defineProps<{
    ad: any;
    variant?: 'list' | 'grid';
}>();

const { __ } = useTranslation();
const { translateVehicleAdField } = useVehicleAdFieldTranslation();
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
            image: ad.primary_image?.thumb,
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
        :class="[
            'group relative cursor-pointer overflow-hidden border border-border bg-card p-0 transition-all duration-300 hover:-translate-y-0.5 hover:border-primary/30 hover:shadow-lg',
            variant === 'grid' ? 'flex h-full flex-col' : '',
        ]"
        @click="() => router.visit(vehiclesRoutes.show.url(ad.id))"
    >
        <div
            :class="[
                'flex flex-col',
                variant === 'list' ? 'md:flex-row' : 'h-full',
            ]"
        >
            <div
                :class="[
                    'relative shrink-0 overflow-hidden bg-muted',
                    variant === 'list'
                        ? 'h-56 w-full md:h-auto md:w-72'
                        : 'aspect-video w-full',
                ]"
            >
                <img
                    v-if="ad.primary_image"
                    :src="ad.primary_image.card"
                    class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                    :alt="`${ad.brand?.name} ${ad.model?.name}`"
                    loading="lazy"
                />
                <div
                    v-else
                    class="flex h-full w-full items-center justify-center bg-muted transition-transform duration-500 group-hover:scale-105"
                >
                    <CarIcon class="size-16 text-muted-foreground/20" />
                </div>
            </div>

            <div
                :class="[
                    'flex flex-1 flex-col justify-between',
                    variant === 'list' ? 'p-5 sm:p-6' : 'p-3 md:p-4',
                ]"
            >
                <div
                    :class="[
                        'flex flex-col gap-3',
                        variant === 'list'
                            ? 'sm:flex-row sm:items-start sm:justify-between sm:gap-4'
                            : '',
                    ]"
                >
                    <div class="min-w-0">
                        <h4
                            :class="[
                                'font-black tracking-tight text-foreground uppercase',
                                variant === 'list'
                                    ? 'text-xl sm:truncate'
                                    : 'line-clamp-2 text-base leading-tight md:text-lg',
                            ]"
                        >
                            {{ ad.brand?.name }}
                            {{ ad.model?.name }}
                        </h4>
                        <p
                            v-if="
                                ad.vehicle_version?.name ||
                                ad.vehicle_version_name
                            "
                            :class="[
                                'mt-0.5 font-bold tracking-wider text-muted-foreground/80 uppercase',
                                variant === 'list'
                                    ? 'text-xs'
                                    : 'line-clamp-1 text-[11px]',
                            ]"
                        >
                            {{
                                ad.vehicle_version?.name ||
                                ad.vehicle_version_name
                            }}
                        </p>
                    </div>

                    <div
                        :class="[
                            'flex flex-col',
                            variant === 'list' ? 'sm:items-end' : 'mt-2',
                        ]"
                    >
                        <div
                            :class="[
                                'inline-flex self-start bg-primary py-0.5 pr-3.5 text-white shadow-sm',
                                variant === 'list'
                                    ? 'pl-2 [clip-path:polygon(0_0,90%_0,100%_100%,0_100%)] sm:self-auto sm:py-1 sm:pr-4 sm:pl-6 sm:[clip-path:polygon(10%_0,100%_0,100%_100%,0_100%)]'
                                    : 'px-2 [clip-path:polygon(0_0,90%_0,100%_100%,0_100%)]',
                            ]"
                        >
                            <div class="flex items-end gap-2">
                                <span
                                    :class="[
                                        'font-black tracking-tight whitespace-nowrap',
                                        variant === 'list'
                                            ? 'text-lg sm:text-xl'
                                            : 'text-base md:text-lg',
                                    ]"
                                >
                                    {{
                                        Number(ad.price).toLocaleString('fr-FR')
                                    }}
                                    €
                                </span>
                                <span
                                    :class="[
                                        'pb-0.5 font-semibold tracking-[0.18em] text-white/80 uppercase',
                                        variant === 'list'
                                            ? 'text-xs'
                                            : 'text-[10px]',
                                    ]"
                                >
                                    {{
                                        String(ad.vat_mode ?? '')
                                            .toLowerCase() === 'htva'
                                            ? __('vehicleAd.vat_excluded')
                                            : __('vehicleAd.vat_included')
                                    }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    :class="[
                        'mt-6',
                        variant === 'list'
                            ? 'grid grid-cols-2 gap-4 border-t border-border pt-4 sm:mt-12 lg:-ml-4 lg:flex lg:flex-nowrap lg:gap-0 lg:border-t-0 lg:pt-0'
                            : 'mt-4 grid grid-cols-2 gap-4 text-xs text-muted-foreground md:h-auto md:flex-row md:flex-wrap md:gap-2',
                    ]"
                >
                    <div
                        :class="[
                            'flex items-center gap-2 font-medium text-foreground/70',
                            variant === 'list'
                                ? 'text-sm lg:border-r lg:border-border lg:px-4'
                                : 'text-xs',
                        ]"
                    >
                        <Calendar class="size-4 shrink-0 text-primary/60" />
                        {{
                            ad.first_registration_date?.substring(0, 4) || 'N/A'
                        }}
                    </div>
                    <div
                        :class="[
                            'flex items-center gap-2 font-medium text-foreground/70',
                            variant === 'list'
                                ? 'text-sm lg:border-r lg:border-border lg:px-4'
                                : 'text-xs',
                        ]"
                    >
                        <Gauge class="size-4 shrink-0 text-primary/60" />
                        {{ ad.mileage?.toLocaleString('fr-FR') }}
                        km
                    </div>
                    <div
                        v-if="ad.fuel_type"
                        :class="[
                            'flex items-center gap-2 font-medium text-foreground/70',
                            variant === 'list'
                                ? 'text-sm lg:border-r lg:border-border lg:px-4'
                                : 'text-xs',
                        ]"
                    >
                        <Fuel class="size-4 shrink-0 text-primary/60" />
                        {{
                            translateVehicleAdField(
                                'fuel_types',
                                ad.fuel_type.code,
                            )
                        }}
                    </div>
                    <div
                        v-if="ad.transmission_type"
                        :class="[
                            'flex items-center gap-2 font-medium text-foreground/70',
                            variant === 'list' ? 'text-sm lg:px-4' : 'text-xs',
                        ]"
                    >
                        <Cog class="size-4 shrink-0 text-primary/60" />
                        {{
                            translateVehicleAdField(
                                'transmission_types',
                                ad.transmission_type.code,
                            )
                        }}
                    </div>
                </div>

                <div
                    class="mt-5 flex items-center justify-between border-t border-border pt-4"
                >
                    <div
                        :class="[
                            'leading-tight',
                            variant === 'list'
                                ? 'text-sm'
                                : 'max-w-[60%] truncate text-xs',
                        ]"
                    >
                        <span class="block font-bold text-foreground">
                            {{
                                ad.user?.company?.name ??
                                __('recentVehicles.private_seller')
                            }}
                        </span>
                        <span
                            v-if="variant === 'list'"
                            class="text-muted-foreground"
                        >
                            {{ ad.user?.company?.city?.zip_code }}
                            {{ ad.user?.company?.city?.code }}
                        </span>
                    </div>

                    <div class="flex items-center gap-2">
                        <Button
                            v-if="variant === 'list'"
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
                            :class="[
                                'size-9 cursor-pointer rounded-md border border-border bg-background transition-all hover:border-primary hover:bg-primary/10 hover:text-primary',
                                variant === 'grid' ? 'size-8' : '',
                                ad.is_favorited
                                    ? 'fill-primary text-primary'
                                    : 'text-muted-foreground/70',
                            ]"
                            @click.stop="toggleFavorite(ad.id)"
                        >
                            <Star
                                :class="[
                                    'size-4',
                                    ad.is_favorited ? 'fill-primary' : '',
                                ]"
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
