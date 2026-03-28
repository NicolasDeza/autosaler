<script setup lang="ts">
import hero1 from '@assets/images/hero-1.jpg';
import hero2 from '@assets/images/hero-4.jpg';
import { Link } from '@inertiajs/vue3';
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { Button } from '@/components/ui/button';
import { useTranslation } from '@/composables/useTranslation';
import dealers from '@/routes/dealers';
import vehicles from '@/routes/vehicles';

const { __ } = useTranslation();

const slides = computed(() => [
    {
        image: hero1,
        title: __('hero.title1'),
        subtitle: __('hero.subtitle1'),
        cta: __('hero.cta1'),
        ctaHref: vehicles.index().url,
    },
    {
        image: hero2,
        title: __('hero.title2'),
        subtitle: __('hero.subtitle2'),
        cta: __('hero.cta2'),
        ctaHref: dealers.index().url,
    },
]);

const current = ref(0);
const paused = ref(false);
let interval: ReturnType<typeof setInterval> | undefined;

function goTo(index: number) {
    if (index === current.value) return;
    current.value = index;
    resetInterval();
}

function next() {
    goTo((current.value + 1) % slides.value.length);
}

function prev() {
    goTo((current.value - 1 + slides.value.length) % slides.value.length);
}

function togglePause() {
    paused.value = !paused.value;
    if (paused.value) {
        clearInterval(interval);
    } else {
        resetInterval();
    }
}

function resetInterval() {
    if (interval) {
        clearInterval(interval);
    }
    if (!paused.value) {
        interval = setInterval(() => {
            next();
        }, 5000);
    }
}

function onKeydown(e: KeyboardEvent) {
    if (e.key === 'ArrowLeft') {
        prev();
    } else if (e.key === 'ArrowRight') {
        next();
    }
}

// Swipe en mobile
const touchStartX = ref(0);

function onTouchStart(e: TouchEvent) {
    touchStartX.value = e.touches[0].clientX;
}

function onTouchEnd(e: TouchEvent) {
    const delta = touchStartX.value - e.changedTouches[0].clientX;
    if (Math.abs(delta) > 40) {
        if (delta > 0) {
            next();
        } else {
            prev();
        }
    }
}

onMounted(() => {
    resetInterval();
});

onUnmounted(() => {
    if (interval) {
        clearInterval(interval);
    }
});
</script>

<template>
    <div
        class="absolute inset-0 overflow-hidden bg-background"
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
            :key="'img-' + i"
            class="absolute inset-0"
            role="group"
            aria-roledescription="slide"
            :aria-label="`${i + 1} / ${slides.length}`"
            :aria-hidden="i !== current"
        >
            <img
                :src="slide.image"
                :alt="slide.title"
                class="absolute inset-0 h-full w-full object-cover transition-all duration-1500 ease-in-out"
                :class="
                    i === current
                        ? 'scale-105 opacity-100'
                        : 'scale-110 opacity-0'
                "
                :loading="i === current ? 'eager' : 'lazy'"
                :fetchpriority="i === current ? 'high' : 'auto'"
                width="2000"
                height="1100"
            />
        </div>

        <!-- Contenu centré -->
        <div
            class="pointer-events-none absolute inset-0 z-1 bg-linear-to-b from-black/55 via-black/45 to-black/70"
            aria-hidden="true"
        />

        <div
            class="absolute inset-0 z-10 flex items-center justify-center px-6 pb-32 sm:pb-40"
        >
            <div
                v-for="(slide, i) in slides"
                :key="'content-' + i"
                class="absolute w-full max-w-4xl text-center transition-all duration-1500 ease-in-out"
                :class="
                    i === current
                        ? 'translate-y-0 opacity-100'
                        : 'pointer-events-none translate-y-8 opacity-0'
                "
            >
                <!-- Titre -->
                <h1
                    class="mb-5 text-4xl leading-[1.05] font-black text-white sm:text-5xl md:text-6xl lg:text-6xl"
                >
                    {{ slide.title }}
                </h1>

                <!-- Sous-titre -->
                <p
                    class="mx-auto mb-8 min-h-13 max-w-xl text-base leading-relaxed font-semibold text-white/80 md:min-h-15 md:text-lg"
                >
                    {{ slide.subtitle }}
                </p>

                <!-- CTA -->
                <div class="flex justify-center">
                    <Button
                        :as="slide.ctaHref ? Link : 'button'"
                        :href="slide.ctaHref"
                        size="lg"
                        class="group relative inline-flex cursor-pointer items-center overflow-hidden px-8 py-7 text-base font-bold transition-all duration-300 hover:-translate-y-0.5 md:text-lg"
                    >
                        <!-- Diagonal sweep -->
                        <span
                            class="absolute inset-0 -translate-x-full -skew-x-12 bg-white/15 transition-transform duration-500 group-hover:translate-x-full"
                        />
                        <!-- Text avec tracking expansion -->
                        <span
                            class="relative z-10 transition-all duration-300 group-hover:tracking-widest"
                            >{{ slide.cta }}</span
                        >
                    </Button>
                </div>
            </div>
        </div>

        <!-- Flèche gauche -->
        <button
            @click="prev"
            :aria-label="__('hero.prev_slide')"
            class="absolute top-1/2 left-4 z-20 hidden h-11 w-11 -translate-y-1/2 cursor-pointer items-center justify-center rounded-full border border-white/15 bg-black/25 text-white/60 backdrop-blur-sm transition-all duration-300 hover:border-primary hover:bg-primary hover:text-white md:left-6 md:flex"
        >
            <svg
                class="h-5 w-5"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
                aria-hidden="true"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M15 19l-7-7 7-7"
                />
            </svg>
        </button>

        <!-- Flèche droite -->
        <button
            @click="next"
            :aria-label="__('hero.next_slide')"
            class="absolute top-1/2 right-4 z-20 hidden h-11 w-11 -translate-y-1/2 cursor-pointer items-center justify-center rounded-full border border-white/15 bg-black/25 text-white/60 backdrop-blur-sm transition-all duration-300 hover:border-primary hover:bg-primary hover:text-white md:right-6 md:flex"
        >
            <svg
                class="h-5 w-5"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
                aria-hidden="true"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9 5l7 7-7 7"
                />
            </svg>
        </button>

        <!-- Bouton pause (WCAG 2.2.2) -->
        <button
            @click="togglePause"
            :aria-label="paused ? __('hero.play') : __('hero.pause')"
            :aria-pressed="paused"
            class="absolute right-4 bottom-8 z-20 flex h-8 w-8 cursor-pointer items-center justify-center rounded-full border border-white/15 bg-black/25 text-white/60 backdrop-blur-sm transition-all duration-300 hover:border-primary hover:bg-primary hover:text-white md:right-6"
        >
            <svg
                v-if="!paused"
                class="h-3.5 w-3.5"
                fill="currentColor"
                viewBox="0 0 24 24"
                aria-hidden="true"
            >
                <rect x="6" y="4" width="4" height="16" rx="1" />
                <rect x="14" y="4" width="4" height="16" rx="1" />
            </svg>
            <svg
                v-else
                class="h-3.5 w-3.5"
                fill="currentColor"
                viewBox="0 0 24 24"
                aria-hidden="true"
            >
                <path d="M8 5v14l11-7z" />
            </svg>
        </button>
    </div>
</template>
