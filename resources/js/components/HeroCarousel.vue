<script setup lang="ts">
import hero1Mobile from '@assets/images/hero-1-mobile.webp';
import hero1 from '@assets/images/hero-1.webp';
import hero2 from '@assets/images/hero-4.webp';
import { Link } from '@inertiajs/vue3';
import { computed, onMounted, onUnmounted, ref } from 'vue';
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
        secondaryCta: __('hero.cta_pro_link'),
        secondaryCtaHref: dealers.index().url,
    },
    {
        image: hero2,
        title: __('hero.title2'),
        subtitle: __('hero.subtitle2'),
        cta: __('hero.cta2'),
        ctaHref: vehicles.index().url,
    },
]);

const current = ref(0);
const paused = ref(false);
const isMounted = ref(false);
const isDesktop = ref(false);

const shouldUseCarousel = computed(() => isMounted.value && isDesktop.value);
const activeSlide = computed(
    () => slides.value[shouldUseCarousel.value ? current.value : 0],
);

let interval: ReturnType<typeof setInterval> | undefined;
let desktopMediaQuery: MediaQueryList | undefined;
let onDesktopViewportChange: ((event: MediaQueryListEvent) => void) | undefined;

function goTo(index: number): void {
    if (!shouldUseCarousel.value || index === current.value) {
        return;
    }

    current.value = index;
    resetInterval();
}

function next(): void {
    goTo((current.value + 1) % slides.value.length);
}

function prev(): void {
    goTo((current.value - 1 + slides.value.length) % slides.value.length);
}

function togglePause(): void {
    if (!shouldUseCarousel.value) {
        return;
    }

    paused.value = !paused.value;

    if (paused.value) {
        if (interval) {
            clearInterval(interval);
            interval = undefined;
        }

        return;
    }

    resetInterval();
}

function resetInterval(): void {
    if (interval) {
        clearInterval(interval);
        interval = undefined;
    }

    if (shouldUseCarousel.value && !paused.value) {
        interval = setInterval(() => {
            next();
        }, 5000);
    }
}

function onKeydown(e: KeyboardEvent): void {
    if (!shouldUseCarousel.value) {
        return;
    }

    if (e.key === 'ArrowLeft') {
        prev();
    } else if (e.key === 'ArrowRight') {
        next();
    }
}

const touchStartX = ref(0);

function onTouchStart(e: TouchEvent): void {
    if (!shouldUseCarousel.value) {
        return;
    }

    touchStartX.value = e.touches[0].clientX;
}

function onTouchEnd(e: TouchEvent): void {
    if (!shouldUseCarousel.value) {
        return;
    }

    const delta = touchStartX.value - e.changedTouches[0].clientX;

    if (Math.abs(delta) > 40) {
        if (delta > 0) {
            next();
        } else {
            prev();
        }
    }
}

function syncDesktopViewport(): void {
    if (!desktopMediaQuery) {
        return;
    }

    isDesktop.value = desktopMediaQuery.matches;
}

onMounted(() => {
    isMounted.value = true;

    desktopMediaQuery = window.matchMedia('(min-width: 768px)');
    syncDesktopViewport();

    onDesktopViewportChange = (event: MediaQueryListEvent) => {
        isDesktop.value = event.matches;
        paused.value = false;
        current.value = 0;
        resetInterval();
    };

    desktopMediaQuery.addEventListener('change', onDesktopViewportChange);

    resetInterval();
});

onUnmounted(() => {
    if (interval) {
        clearInterval(interval);
    }

    if (desktopMediaQuery && onDesktopViewportChange) {
        desktopMediaQuery.removeEventListener(
            'change',
            onDesktopViewportChange,
        );
    }
});
</script>

<template>
    <div
        class="absolute inset-0 overflow-hidden bg-background"
        role="region"
        :aria-roledescription="shouldUseCarousel ? 'carousel' : 'region'"
        :aria-label="__('hero.carousel_label')"
        @touchstart.passive="onTouchStart"
        @touchend.passive="onTouchEnd"
        @keydown="onKeydown"
        tabindex="0"
    >
        <img
            v-if="!shouldUseCarousel"
            :src="hero1Mobile"
            :alt="slides[0].title"
            class="absolute inset-0 h-full w-full object-cover"
            loading="eager"
            fetchpriority="high"
            decoding="async"
            width="900"
            height="1000"
            sizes="100vw"
        />

        <div
            v-for="(slide, i) in slides"
            v-else
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
                decoding="async"
                width="2000"
                height="1100"
                sizes="100vw"
            />
        </div>

        <div
            class="pointer-events-none absolute inset-0 z-1 bg-linear-to-b from-black/55 via-black/45 to-black/70"
            aria-hidden="true"
        />

        <div
            class="absolute inset-0 z-10 flex items-center justify-center px-6 pb-32 sm:pb-40"
        >
            <div class="absolute w-full max-w-4xl text-center">
                <h1
                    class="mb-5 text-4xl leading-[1.05] font-black text-white sm:text-5xl md:text-6xl lg:text-6xl"
                >
                    {{ activeSlide.title }}
                </h1>

                <p
                    class="mx-auto mb-8 min-h-13 max-w-xl text-base leading-relaxed font-semibold text-white/80 md:min-h-15 md:text-lg"
                >
                    {{ activeSlide.subtitle }}
                </p>

                <div class="flex justify-center">
                    <Button
                        :as="activeSlide.ctaHref ? Link : 'button'"
                        :href="activeSlide.ctaHref"
                        size="lg"
                        class="group relative inline-flex cursor-pointer items-center overflow-hidden px-8 py-7 text-base font-bold transition-all duration-300 hover:-translate-y-0.5 md:text-lg"
                    >
                        <span
                            class="absolute inset-0 -translate-x-full -skew-x-12 bg-white/15 transition-transform duration-500 group-hover:translate-x-full"
                        />
                        <span
                            class="relative z-10 transition-all duration-300 group-hover:tracking-widest"
                        >
                            {{ activeSlide.cta }}
                        </span>
                    </Button>
                </div>

                <div v-if="activeSlide.secondaryCtaHref" class="mt-4">
                    <Link
                        :href="activeSlide.secondaryCtaHref"
                        class="pointer-events-auto text-sm font-semibold text-white/90 underline underline-offset-4 transition-colors hover:text-primary"
                    >
                        {{ activeSlide.secondaryCta }}
                    </Link>
                </div>
            </div>
        </div>

        <button
            v-if="shouldUseCarousel"
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

        <button
            v-if="shouldUseCarousel"
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

        <button
            v-if="shouldUseCarousel"
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
