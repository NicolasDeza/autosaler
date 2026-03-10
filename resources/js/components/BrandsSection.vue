<script setup lang="ts">

import alfaRomeoLogo from '@assets/images/brands/alfa-romeo.svg'
import audiLogo from '@assets/images/brands/audi.svg'
import bmwLogo from '@assets/images/brands/bmw.svg'
import bydLogo from '@assets/images/brands/byd.svg'
import chevroletLogo from '@assets/images/brands/chevrolet.svg'
import citroenLogo from '@assets/images/brands/citroen.svg'
import cupraLogo from '@assets/images/brands/cupra.svg'
import daciaLogo from '@assets/images/brands/dacia.svg'
import fordLogo from '@assets/images/brands/ford.svg'
import hyundaiLogo from '@assets/images/brands/hyundai.svg'
import mercedesLogo from '@assets/images/brands/mercedes-benz.svg'
import opelLogo from '@assets/images/brands/opel.svg'
import peugeotLogo from '@assets/images/brands/peugeot.svg'
import renaultLogo from '@assets/images/brands/renault.svg'
import seatLogo from '@assets/images/brands/seat.svg'
import skodaLogo from '@assets/images/brands/skoda.svg'
import teslaLogo from '@assets/images/brands/tesla.svg'
import toyotaLogo from '@assets/images/brands/toyota.svg'
import volkswagenLogo from '@assets/images/brands/volkswagen.svg'
import volvoLogo from '@assets/images/brands/volvo.svg'
import {
  Carousel,
  CarouselContent,
  CarouselItem,
  CarouselNext,
  CarouselPrevious,
} from '@/components/ui/carousel'
import { useTranslation } from '@/composables/useTranslation'

const { __ } = useTranslation()

const brands = [
  { name: 'Alfa Romeo', logo: alfaRomeoLogo },
  { name: 'Audi', logo: audiLogo },
  { name: 'BMW', logo: bmwLogo },
  { name: 'BYD', logo: bydLogo },
  { name: 'Chevrolet', logo: chevroletLogo },
  { name: 'Citroën', logo: citroenLogo },
  { name: 'Cupra', logo: cupraLogo },
  { name: 'Dacia', logo: daciaLogo },
  { name: 'Ford', logo: fordLogo },
  { name: 'Hyundai', logo: hyundaiLogo },
  { name: 'Mercedes-Benz', logo: mercedesLogo },
  { name: 'Opel', logo: opelLogo },
  { name: 'Peugeot', logo: peugeotLogo },
  { name: 'Renault', logo: renaultLogo },
  { name: 'Seat', logo: seatLogo },
  { name: 'Škoda', logo: skodaLogo },
  { name: 'Tesla', logo: teslaLogo },
  { name: 'Toyota', logo: toyotaLogo },
  { name: 'Volkswagen', logo: volkswagenLogo },
  { name: 'Volvo', logo: volvoLogo },
]
</script>

<template>
  <section class="w-full py-8 sm:py-12">
    <div class="max-w-360 mx-auto px-6">
      <div class="text-center mb-8">
        <h2 class="text-3xl font-extrabold text-foreground uppercase tracking-wide">
          {{ __('brands.title_prefix') }} <span class="text-red-500">{{ __('brands.title_highlight') }}</span>
        </h2>
        <p class="text-base text-muted-foreground mt-4">{{ __('brands.subtitle') }}</p>
      </div>

      <Carousel
        class="w-full px-10"
        :opts="{ align: 'start', loop: true }"
      >
        <CarouselContent class="-ml-4 sm:-ml-2">
          <CarouselItem
            v-for="brand in brands"
            :key="brand.name"
            class="basis-1/3 sm:basis-1/5 md:basis-1/6 lg:basis-[14.28%] pl-4 sm:pl-2"
          >
            <div class="flex flex-col items-center gap-2 py-4 cursor-pointer group">
              <div class="w-16 h-16 flex items-center justify-center">
                <img
                  :src="brand.logo"
                  :alt="brand.name"
                  class="max-w-full max-h-full object-contain"
                />
              </div>
              <span class="text-base font-medium text-muted-foreground group-hover:text-foreground transition-colors group-hover:underline">
                {{ brand.name }}
              </span>
            </div>
          </CarouselItem>
        </CarouselContent>
        <CarouselPrevious class="left-0 hidden sm:flex" />
        <CarouselNext class="right-0 hidden sm:flex" />
      </Carousel>
    </div>
  </section>
</template>
