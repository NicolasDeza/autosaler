<script setup lang="ts">
import alfaRomeoLogo from '@assets/images/brands/alfa-romeo.svg';
import audiLogo from '@assets/images/brands/audi.svg';
import bmwLogo from '@assets/images/brands/bmw.svg';
import bydLogo from '@assets/images/brands/byd.svg';
import citroenLogo from '@assets/images/brands/citroen.svg';
import cupraLogo from '@assets/images/brands/cupra.svg';
import daciaLogo from '@assets/images/brands/dacia.svg';
import fordLogo from '@assets/images/brands/ford.svg';
import hyundaiLogo from '@assets/images/brands/hyundai.svg';
import mercedesLogo from '@assets/images/brands/mercedes-benz.svg';
import opelLogo from '@assets/images/brands/opel.svg';
import peugeotLogo from '@assets/images/brands/peugeot.svg';
import renaultLogo from '@assets/images/brands/renault.svg';
import seatLogo from '@assets/images/brands/seat.svg';
import skodaLogo from '@assets/images/brands/skoda.svg';
import teslaLogo from '@assets/images/brands/tesla.svg';
import toyotaLogo from '@assets/images/brands/toyota.svg';
import volkswagenLogo from '@assets/images/brands/volkswagen.svg';
import volvoLogo from '@assets/images/brands/volvo.svg';
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import {
    Carousel,
    CarouselContent,
    CarouselItem,
    CarouselNext,
    CarouselPrevious,
} from '@/components/ui/carousel';
import { useTranslation } from '@/composables/useTranslation';
import vehiclesRoutes from '@/routes/vehicles';

interface BrandOption {
    id: number | string;
    name: string;
}

const props = defineProps<{
    brands?: BrandOption[];
}>();

const { __ } = useTranslation();

const normalizeBrandName = (name: string): string =>
    name
        .toLocaleLowerCase()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .replace(/[^a-z0-9]/g, '');

const brandsWithLogos = [
    { name: 'Alfa Romeo', logo: alfaRomeoLogo },
    { name: 'Audi', logo: audiLogo },
    { name: 'BMW', logo: bmwLogo },
    { name: 'BYD', logo: bydLogo },
    { name: 'Citroen', logo: citroenLogo },
    { name: 'Cupra', logo: cupraLogo },
    { name: 'Dacia', logo: daciaLogo },
    { name: 'Ford', logo: fordLogo },
    { name: 'Hyundai', logo: hyundaiLogo },
    { name: 'Mercedes-Benz', logo: mercedesLogo },
    { name: 'Opel', logo: opelLogo },
    { name: 'Peugeot', logo: peugeotLogo },
    { name: 'Renault', logo: renaultLogo },
    { name: 'Seat', logo: seatLogo },
    { name: 'Skoda', logo: skodaLogo },
    { name: 'Tesla', logo: teslaLogo },
    { name: 'Toyota', logo: toyotaLogo },
    { name: 'Volkswagen', logo: volkswagenLogo },
    { name: 'Volvo', logo: volvoLogo },
];

const brandIdByName = computed(() => {
    return new Map(
        (props.brands ?? []).map((brand) => [
            normalizeBrandName(brand.name),
            String(brand.id),
        ]),
    );
});

const carouselBrands = computed(() =>
    brandsWithLogos.map((brand) => ({
        ...brand,
        id: brandIdByName.value.get(normalizeBrandName(brand.name)) ?? null,
    })),
);
</script>

<template>
    <section class="w-full py-8 sm:py-12">
        <div class="mx-auto max-w-360 px-1 sm:px-6">
            <div class="mb-8 text-center">
                <h2
                    class="text-3xl font-extrabold tracking-wide text-foreground uppercase"
                >
                    {{ __('brands.title_prefix') }}
                    <span class="text-primary">{{
                        __('brands.title_highlight')
                    }}</span>
                </h2>
                <p class="mt-4 text-base font-medium text-muted-foreground">
                    {{ __('brands.subtitle') }}
                </p>
            </div>

            <Carousel
                class="w-full px-2 sm:px-10"
                :opts="{ align: 'start', loop: true }"
            >
                <CarouselContent class="-ml-1 sm:-ml-2">
                    <CarouselItem
                        v-for="brand in carouselBrands"
                        :key="brand.name"
                        class="basis-1/3 pl-3 sm:basis-1/5 sm:pl-2 md:basis-1/6 lg:basis-[14.28%]"
                    >
                        <component
                            :is="brand.id ? Link : 'div'"
                            :href="
                                brand.id
                                    ? vehiclesRoutes.index.url({
                                          query: { brand_id: brand.id },
                                      })
                                    : undefined
                            "
                            class="group flex flex-col items-center gap-2 py-4 transition-opacity"
                            :class="
                                brand.id
                                    ? 'cursor-pointer hover:opacity-85'
                                    : 'cursor-default opacity-60'
                            "
                        >
                            <div
                                class="flex h-16 w-16 items-center justify-center"
                            >
                                <img
                                    :src="brand.logo"
                                    :alt="brand.name"
                                    class="max-h-full max-w-full object-contain"
                                    loading="lazy"
                                />
                            </div>
                            <span
                                class="text-base font-medium text-muted-foreground transition-colors group-hover:text-foreground group-hover:underline"
                            >
                                {{ brand.name }}
                            </span>
                        </component>
                    </CarouselItem>
                </CarouselContent>
                <CarouselPrevious class="left-0 hidden sm:flex" />
                <CarouselNext class="right-0 hidden sm:flex" />
            </Carousel>
        </div>
    </section>
</template>
