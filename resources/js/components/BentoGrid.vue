<script setup lang="ts">
import { CurveType } from '@unovis/ts'
import { VisXYContainer, VisArea, VisLine } from '@unovis/vue'
import { ShieldCheck, Search, TrendingUp, Users, Zap, HeartHandshake } from 'lucide-vue-next'
import { Card } from '@/components/ui/card'
import { ChartContainer } from '@/components/ui/chart'
import { useTranslation } from '@/composables/useTranslation'

const { __ } = useTranslation()

const chartData = [
  { month: 'Jan', price: 18500 },
  { month: 'Fév', price: 17800 },
  { month: 'Mar', price: 19200 },
  { month: 'Avr', price: 18100 },
  { month: 'Mai', price: 20500 },
  { month: 'Juin', price: 21200 },
  { month: 'Juil', price: 20800 },
  { month: 'Août', price: 22400 },
]

const x = (_: (typeof chartData)[number], i: number) => i
const y = (d: (typeof chartData)[number]) => d.price

const chartConfig = {
  price: { label: 'Prix moyen', color: 'var(--color-blue-500)' },
}
</script>

<template>
  <section class="w-full py-16 bg-white">
    <div class="max-w-360 mx-auto px-6">

      <div class="text-center mb-16">
        <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight uppercase">
          {{ __('bento.title_prefix') }}
          <span class="text-red-600 ">{{ __('bento.title_highlight') }}</span>
        </h2>
        <p class="text-slate-500 mt-4 max-w-xl mx-auto font-medium">
          {{ __('bento.subtitle') }}
        </p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <Card class="md:col-span-2 group relative overflow-hidden border-none bg-slate-50/50 p-8 transition-all hover:bg-slate-50">
          <div class="relative z-10 flex flex-col h-full justify-between">
            <div>
              <div class="inline-flex p-3 bg-white rounded-2xl shadow-sm border border-slate-100 mb-6 group-hover:scale-110 transition-transform duration-300">
                <Search :size="24" class="text-red-600" />
              </div>
              <h3 class="text-2xl font-bold text-slate-900 mb-3">{{ __('bento.search_title') }}</h3>
              <p class="text-slate-600 max-w-md leading-relaxed">
                {{ __('bento.search_desc') }}
              </p>
            </div>

            <div class="flex flex-wrap gap-2 mt-8">
              <span v-for="tag in ['brand', 'price', 'fuel', 'year']" :key="tag"
                class="text-xs font-semibold bg-white border border-slate-200 text-slate-700 px-4 py-1.5 rounded-full shadow-sm hover:border-red-200 transition-colors">
                {{ __(`bento.tag_${tag}`) }}
              </span>
            </div>
          </div>
          <div class="absolute -right-10 -bottom-10 opacity-5 group-hover:opacity-10 transition-opacity">
            <Search :size="200" />
          </div>
        </Card>

        <Card class="group border-none bg-slate-900 p-8 transition-all hover:bg-slate-800">
          <div class="inline-flex p-3 bg-emerald-500/20 rounded-2xl mb-6">
            <ShieldCheck :size="24" class="text-emerald-400" />
          </div>
          <h3 class="text-xl font-bold text-white mb-3">{{ __('bento.trust_title') }}</h3>
          <p class="text-slate-400 text-sm leading-relaxed">
            {{ __('bento.trust_desc') }}
          </p>
        </Card>

        <Card class="group border-none bg-blue-50/50 p-6 transition-all hover:bg-blue-50 shadow-[inset_0_0_0_1px_rgba(59,130,246,0.1)]">
          <div class="flex items-center justify-between mb-1">
            <div>
              <div class="inline-flex p-3 bg-blue-600 rounded-2xl shadow-lg shadow-blue-200 mb-4 group-hover:-translate-y-1 transition-transform">
                <TrendingUp :size="24" class="text-white" />
              </div>
              <h3 class="text-xl font-bold text-slate-900">{{ __('bento.market_title') }}</h3>
            </div>
            <div class="text-right">
              <span class="text-2xl font-bold text-slate-900">22 400 €</span>
              <div class="flex items-center gap-1 justify-end">
                <TrendingUp :size="14" class="text-emerald-500" />
                <span class="text-xs font-semibold text-emerald-600">+5.8%</span>
              </div>
            </div>
          </div>
          <p class="text-xs text-slate-500 mb-3">{{ __('bento.market_subtitle') }}</p>
          <ChartContainer :config="chartConfig" class="h-28 w-full">
            <VisXYContainer :data="chartData" :padding="{ top: 5, bottom: 0, left: 0, right: 0 }">
              <VisArea :x="x" :y="y" color="var(--color-blue-200)" :opacity="0.3" :curve-type="CurveType.MonotoneX" />
              <VisLine :x="x" :y="y" color="var(--color-blue-500)" :line-width="2.5" :curve-type="CurveType.MonotoneX" />
            </VisXYContainer>
          </ChartContainer>
        </Card>

        <Card class="md:col-span-2 group border-none bg-slate-50/50 p-8 flex flex-col md:flex-row gap-8 items-center transition-all">
          <div class="flex-1">
            <div class="inline-flex p-3 bg-amber-100 rounded-2xl mb-6">
              <Users :size="24" class="text-amber-600" />
            </div>
            <h3 class="text-2xl font-bold text-slate-900 mb-3">{{ __('bento.community_title') }}</h3>
            <p class="text-slate-600 text-sm leading-relaxed">
              {{ __('bento.community_desc') }}
            </p>
          </div>

          <div class="w-full md:w-64 bg-white p-6 rounded-2xl shadow-sm border border-slate-100 space-y-4">
            <div v-for="(item, idx) in [
              {icon: Zap, text: 'community_fast', color: 'text-amber-500'},
              {icon: ShieldCheck, text: 'community_verified', color: 'text-blue-500'},
              {icon: HeartHandshake, text: 'community_support', color: 'text-red-500'}
            ]" :key="idx" class="flex items-center gap-3">
              <component :is="item.icon" :size="18" :class="item.color" />
              <span class="text-sm font-semibold text-slate-700">{{ __(`bento.${item.text}`) }}</span>
            </div>
          </div>
        </Card>

      </div>
    </div>
  </section>
</template>
