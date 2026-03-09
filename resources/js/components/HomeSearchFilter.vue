<script setup lang="ts">
import { Search, Car, MapPin, Settings2, ChevronDown } from "lucide-vue-next"
import { ref, computed, watch } from "vue"
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover'
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select'
import { Slider } from '@/components/ui/slider'
import { useTranslation } from '@/composables/useTranslation'

const { __ } = useTranslation()

interface FilterState {
  brand: string
  model: string
  priceRange: number[]
  year: string
  city: string
}

const filters = ref<FilterState>({
  brand: "",
  model: "",
  priceRange: [0, 100000],
  year: "",
  city: "",
})

const formatPrice = (val: number) =>
  val >= 100000 ? '100 000€+' : `${val.toLocaleString('fr-FR')}€`

const hasCustomRange = computed(() =>
  filters.value.priceRange[0] !== 0 || filters.value.priceRange[1] !== 100000
)

const priceLabel = computed(() =>
  hasCustomRange.value
    ? `${formatPrice(filters.value.priceRange[0])} – ${formatPrice(filters.value.priceRange[1])}`
    : __('homeFilter.price_placeholder')
)

const isModelDisabled = computed(() => !filters.value.brand)

watch(() => filters.value.brand, (val) => {
  if (val === 'all') filters.value.brand = ''
  filters.value.model = ''
})

watch(() => filters.value.model, (val) => {
  if (val === 'all') filters.value.model = ''
})

watch(() => filters.value.year, (val) => {
  if (val === 'all') filters.value.year = ''
})

const submitSearch = () => {
  console.log(filters.value)
}

// Select de l'année 1ère immatriculation
const years = computed(() => {
  const list = []
  for (let y = 2026; y >= 1980; y--) {
    list.push(String(y))
  }
  return list
})
</script>

<template>
  <section class="relative w-full pb-8 sm:pb-12">
    <div class="max-w-6xl mx-auto px-6">

      <div class="relative -mt-24 sm:-mt-40 z-30">
        <div class="bg-white/95 backdrop-blur-md rounded-lg shadow-[0_20px_50px_rgba(0,0,0,0.1)] border border-gray-100 p-3 sm:p-4">

          <div class="flex items-center px-2 mb-2">
            <div class="flex items-center gap-2">
              <div class="p-2 bg-red-50 rounded-lg">
                <Car :size="20" class="text-red-500" />
              </div>
              <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wider">
                {{ __("homeFilter.title") }}
              </h2>
            </div>
          </div>

          <form class="flex flex-col gap-3 px-2" @submit.prevent="submitSearch">

            <!-- Ligne 1 : Marque / Modèle / Prix range -->
            <div class="flex flex-col lg:flex-row gap-3">
              <div class="w-full lg:flex-1">
                <Select v-model="filters.brand">
                  <SelectTrigger class="bg-white! border-gray-200 w-full h-10! text-gray-900 cursor-pointer">
                    <SelectValue :placeholder="__('homeFilter.brand_placeholder')" />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem value="all">{{ __('homeFilter.brand_placeholder') }}</SelectItem>
                    <SelectItem value="toyota">Toyota</SelectItem>
                    <SelectItem value="bmw">BMW</SelectItem>
                    <SelectItem value="mercedes">Mercedes</SelectItem>
                    <SelectItem value="audi">Audi</SelectItem>
                  </SelectContent>
                </Select>
              </div>

              <div class="w-full lg:flex-1">
                <Select v-model="filters.model" :disabled="isModelDisabled">
                  <SelectTrigger class="bg-white! border-gray-200 w-full h-10! text-gray-900 cursor-pointer" :class="{ 'opacity-50 cursor-not-allowed!': isModelDisabled }">
                    <SelectValue :placeholder="__('homeFilter.model_placeholder')" />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem value="all">{{ __('homeFilter.model_placeholder') }}</SelectItem>
                    <SelectItem value="model1">Modèle 1</SelectItem>
                    <SelectItem value="model2">Modèle 2</SelectItem>
                  </SelectContent>
                </Select>
              </div>

              <div class="w-full lg:flex-1">
                <Popover>
                  <PopoverTrigger as-child>
                    <button
                      type="button"
                      class="bg-white! border-gray-200 w-full h-10 rounded-md border shadow-xs px-3 flex items-center justify-between text-sm transition-colors hover:bg-gray-50 cursor-pointer"
                      :class="hasCustomRange ? 'text-foreground' : 'text-muted-foreground'"
                    >
                      <span :class="{ 'font-medium': hasCustomRange }">{{ priceLabel }}</span>
                      <ChevronDown class="size-4 shrink-0 opacity-50" />
                    </button>
                  </PopoverTrigger>
                  <PopoverContent class="w-80 p-4" align="start">
                    <div class="flex flex-col gap-3">
                      <div class="flex items-center justify-between">
                        <span class="text-sm font-semibold text-gray-800">Budget</span>
                        <span class="text-xs text-gray-500">{{ formatPrice(filters.priceRange[0]) }} – {{ formatPrice(filters.priceRange[1]) }}</span>
                      </div>
                      <Slider
                        v-model="filters.priceRange"
                        :min="0"
                        :max="100000"
                        :step="500"
                        class="w-full"
                      />
                      <div class="flex justify-between text-[11px] text-gray-400">
                        <span>0€</span>
                        <span>100 000€+</span>
                      </div>
                    </div>
                  </PopoverContent>
                </Popover>
              </div>
            </div>

            <!-- Ligne 2 : 1ère immatriculation / Ville / Bouton -->
            <div class="flex flex-col lg:flex-row gap-3">
              <div class="w-full lg:flex-1">
                <Select v-model="filters.year">
                  <SelectTrigger class="bg-white! border-gray-200 w-full h-10! text-gray-900 cursor-pointer">
                    <SelectValue :placeholder="__('homeFilter.year_placeholder')" />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem value="all">{{ __('homeFilter.year_placeholder') }}</SelectItem>
                    <SelectItem v-for="year in years" :key="year" :value="year">
                      {{ year }}
                    </SelectItem>
                  </SelectContent>
                </Select>
              </div>

              <div class="w-full lg:flex-1">
                <div class="relative h-10">
                  <MapPin :size="14" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 z-10" />
                  <Input
                    v-model="filters.city"
                    :placeholder="__('homeFilter.location_placeholder')"
                    class="pl-9 bg-white! border-gray-200 h-full"
                  />
                </div>
              </div>

              <div class="w-full lg:flex-1">
                <Button
                  type="submit"
                  class="w-full h-10 px-6 bg-red-500 hover:bg-red-600 text-white font-bold transition-all active:scale-95 flex items-center justify-center gap-2 cursor-pointer"
                >
                  <Search :size="18" />
                  <span class="text-sm uppercase">{{ __("homeFilter.search_button") }}</span>
                </Button>
              </div>
            </div>

          </form>

          <div class="flex justify-center mt-3">
            <a href="/vehicles" class="flex items-center gap-1.5 text-xs font-bold text-gray-400 underline hover:text-red-500 transition-colors">
              <Settings2 :size="14" />
              {{ __("homeFilter.more_criteria") }}
            </a>
          </div>

        </div>
      </div>
    </div>
  </section>
</template>
