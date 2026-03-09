<script setup lang="ts">
import hero1 from '@assets/images/hero.jpg'
import hero2 from '@assets/images/hero2.jpg'
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { useTranslation } from '@/composables/useTranslation'

const { __ } = useTranslation()

const slides = computed(() => [
  {
    image: hero1,
    title: __('hero.title1'),
    subtitle: __('hero.subtitle1'),
    cta: __('hero.cta1')
  },
  {
    image: hero2,
    title: __('hero.title2'),
    subtitle: __('hero.subtitle2'),
    cta: __('hero.cta2')
  },
])

const current = ref(0)
const transitioning = ref(false)
const paused = ref(false)
let interval: ReturnType<typeof setInterval> | undefined

function goTo(index: number) {
  if (index === current.value) return
  transitioning.value = true
  setTimeout(() => {
    current.value = index
    setTimeout(() => { transitioning.value = false }, 50)
  }, 300)
  resetInterval()
}

function next() {
  goTo((current.value + 1) % slides.value.length)
}

function prev() {
  goTo((current.value - 1 + slides.value.length) % slides.value.length)
}

function togglePause() {
  paused.value = !paused.value
  if (paused.value) {
    clearInterval(interval)
  } else {
    resetInterval()
  }
}

function resetInterval() {
  if (interval) {
    clearInterval(interval)
  }
  if (!paused.value) {
    interval = setInterval(() => {
      next()
    }, 5000)
  }
}

function onKeydown(e: KeyboardEvent) {
  if (e.key === 'ArrowLeft') {
    prev()
  } else if (e.key === 'ArrowRight') {
    next()
  }
}

// Swipe en mobile
const touchStartX = ref(0)

function onTouchStart(e: TouchEvent) {
  touchStartX.value = e.touches[0].clientX
}

function onTouchEnd(e: TouchEvent) {
  const delta = touchStartX.value - e.changedTouches[0].clientX
  if (Math.abs(delta) > 40) {
    if (delta > 0) {
      next()
    } else {
      prev()
    }
  }
}

onMounted(() => {
  resetInterval()
})

onUnmounted(() => {
  if (interval) {
    clearInterval(interval)
  }
})
</script>

<template>
<div
  class="absolute inset-0 overflow-hidden bg-neutral-950"
  role="region"
  aria-roledescription="carousel"
  :aria-label="__('hero.carousel_label')"
  @touchstart.passive="onTouchStart"
  @touchend.passive="onTouchEnd"
  @keydown="onKeydown"
  tabindex="0"
>

  <!-- Images de bg -->
  <div
    v-for="(slide, i) in slides"
    :key="'img-'+i"
    class="absolute inset-0"
    role="group"
    aria-roledescription="slide"
    :aria-label="`${i + 1} / ${slides.length}`"
    :aria-hidden="i !== current"
  >
    <img
      :src="slide.image"
      :alt="slide.title"
      class="absolute inset-0 w-full h-full object-cover transition-all duration-1500 ease-in-out"
      :class="i === current ? 'opacity-100 scale-105' : 'opacity-0 scale-110'"
    />
  </div>

  <!-- Contenu centré -->
  <div class="absolute inset-0 z-10 flex items-center justify-center px-6 pb-32 sm:pb-40">
    <div
      class="w-full max-w-3xl text-center transition-all duration-500 ease-out"
      :class="transitioning ? 'opacity-0 translate-y-6 blur-sm' : 'opacity-100 translate-y-0 blur-none'"
      aria-live="polite"
      aria-atomic="true"
    >


      <!-- Titre -->
      <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-extrabold text-white leading-[1.05] mb-5">
        {{ slides[current].title }}
      </h1>

      <!-- Sous-titre -->
      <p class="text-white/80 text-base md:text-lg max-w-xl mx-auto mb-8 font-semibold leading-relaxed">
        {{ slides[current].subtitle }}
      </p>

      <!-- CTA -->
      <div class="flex justify-center">
        <button class="group inline-flex items-center gap-3 bg-red-500 hover:bg-red-600 text-white font-bold px-8 py-3.5 rounded transition-all duration-300 hover:scale-105  cursor-pointer">
          {{ slides[current].cta }}
        </button>
      </div>
    </div>
  </div>

  <!-- Flèche gauche -->
  <button
    @click="prev"
    :aria-label="__('hero.prev_slide')"
    class="absolute left-4 md:left-6 top-1/2 -translate-y-1/2 z-20 w-11 h-11 hidden md:flex items-center justify-center rounded-full border border-white/15 bg-black/25 backdrop-blur-sm text-white/60 hover:text-white hover:bg-red-500 hover:border-red-500 transition-all duration-300 cursor-pointer"
  >
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
    </svg>
  </button>

  <!-- Flèche droite -->
  <button
    @click="next"
    :aria-label="__('hero.next_slide')"
    class="absolute right-4 md:right-6 top-1/2 -translate-y-1/2 z-20 w-11 h-11 hidden md:flex items-center justify-center rounded-full border border-white/15 bg-black/25 backdrop-blur-sm text-white/60 hover:text-white hover:bg-red-500 hover:border-red-500 transition-all duration-300 cursor-pointer"
  >
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
    </svg>
  </button>

  <!-- Bouton pause (WCAG 2.2.2) -->
  <button
    @click="togglePause"
    :aria-label="paused ? __('hero.play') : __('hero.pause')"
    :aria-pressed="paused"
    class="absolute bottom-8 right-4 md:right-6 z-20 w-8 h-8 flex items-center justify-center rounded-full border border-white/15 bg-black/25 backdrop-blur-sm text-white/60 hover:text-white hover:bg-red-500 hover:border-red-500 transition-all duration-300 cursor-pointer"
  >
    <svg v-if="!paused" class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
      <rect x="6" y="4" width="4" height="16" rx="1"/>
      <rect x="14" y="4" width="4" height="16" rx="1"/>
    </svg>
    <svg v-else class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
      <path d="M8 5v14l11-7z"/>
    </svg>
  </button>

</div>
</template>

