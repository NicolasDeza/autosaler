<script setup lang="ts">
import { Fuel, Gauge, Calendar, ImageOff, Star } from 'lucide-vue-next'
import { Badge } from '@/components/ui/badge'
import { Card, CardContent } from '@/components/ui/card'
import { useTranslation } from '@/composables/useTranslation'

const { __ } = useTranslation()

interface Vehicle {
  id: number
  price: string
  mileage: number
  first_registration_date: string | null
  brand: { id: number; name: string } | null
  model: { id: number; name: string } | null
  fuel_type: { id: number; code: string } | null
  transmission_type: { id: number; code: string } | null
  user: { company: { name: string } | null } | null
}

defineProps<{
  vehicles: Vehicle[]
}>()

const formatPrice = (price: string | number) => {
  return Number(price).toLocaleString('fr-FR') + ' €'
}

const formatMileage = (km: number) => {
  return km.toLocaleString('fr-FR') + ' km'
}

const formatDate = (date: string | null) => {
  if (!date) return '—'
  return new Date(date).toLocaleDateString('fr-FR', { month: '2-digit', year: 'numeric' })
}
</script>

<template>
  <section class="w-full py-8 sm:py-12">
    <div class="max-w-360 mx-auto px-6">
      <div class="text-center mb-8">
        <h2 class="text-3xl font-extrabold text-gray-800 uppercase tracking-wide">
          {{ __('recentVehicles.title_prefix') }} <span class="text-red-600">{{ __('recentVehicles.title_highlight') }}</span>
        </h2>
      </div>

      <div v-if="vehicles.length" class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <Card
          v-for="vehicle in vehicles"
          :key="vehicle.id"
          class="overflow-hidden p-0 gap-0 hover:shadow-md transition-shadow cursor-pointer"
        >
          <!-- Image placeholder -->
          <div class="aspect-4/3 bg-gray-100 flex items-center justify-center">
            <ImageOff :size="40" class="text-gray-300" />
          </div>

          <CardContent class="p-3 flex flex-col gap-2">
            <!-- Titre : Marque + Modèle -->
            <h3 class="text-base font-bold text-gray-900 truncate">
              {{ vehicle.brand?.name ?? '' }} {{ vehicle.model?.name ?? '' }}
            </h3>

            <!-- Prix -->
            <span class="text-lg font-extrabold text-gray-900">
              {{ formatPrice(vehicle.price) }}
            </span>

            <!-- Infos : date, km, carburant -->
            <div class="flex items-center gap-3 text-xs text-gray-500">
              <span class="flex items-center gap-1">
                <Calendar :size="12" />
                {{ formatDate(vehicle.first_registration_date) }}
              </span>
              <span class="flex items-center gap-1">
                <Gauge :size="12" />
                {{ formatMileage(vehicle.mileage) }}
              </span>
              <span v-if="vehicle.fuel_type" class="flex items-center gap-1">
                <Fuel :size="12" />
                {{ vehicle.fuel_type.code }}
              </span>
            </div>

            <!-- Badges -->
            <div class="flex gap-1.5 mt-1">
              <Badge v-if="vehicle.transmission_type" variant="secondary" class="text-[10px] px-1.5 py-0">
                {{ vehicle.transmission_type.code }}
              </Badge>
            </div>

            <!-- Favoris -->
            <div class="flex items-center justify-between mt-auto pt-2">
              <span class="text-xs text-gray-400 truncate">
                {{ vehicle.user?.company?.name ?? __('recentVehicles.private_seller') }}
              </span>
              <button class="border border-gray-200 rounded-md p-1.5 text-gray-300 hover:text-red-500 hover:border-red-500 transition-colors duration-200 cursor-pointer">
                <Star :size="16" />
              </button>
            </div>
          </CardContent>
        </Card>
      </div>

      <div v-else class="text-center py-12 text-gray-400 text-sm">
        {{ __('recentVehicles.empty') }}
      </div>

      <div class="flex justify-center mt-8">
        <button class="bg-white border-2 border-red-500 text-red-500 px-6 py-2.5 rounded-md text-base font-semibold transition-all duration-200 cursor-pointer hover:scale-105">
          {{ __('recentVehicles.search_button') }}
        </button>
      </div>
    </div>
  </section>
</template>
