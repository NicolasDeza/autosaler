<script setup lang="ts">
import {
    CarIcon,
    ChevronLeftIcon,
    ChevronRightIcon,
    Maximize2Icon,
    XIcon,
} from 'lucide-vue-next';
import { onMounted, onUnmounted, ref, watch, nextTick } from 'vue';
import { Button } from '@/components/ui/button';
import {
    Carousel,
    CarouselContent,
    CarouselItem,
    type CarouselApi,
} from '@/components/ui/carousel';
import {
    Dialog,
    DialogContent,
    DialogTitle,
    DialogDescription,
} from '@/components/ui/dialog';

interface Image {
    id: number;
    large: string;
    thumb: string;
}

defineProps<{
    images: Image[];
    brandName?: string;
    modelName?: string;
}>();

const api = ref<CarouselApi>();
const lightboxApi = ref<CarouselApi>();
const selectedIndex = ref(0);
const showLightbox = ref(false);
const thumbScrollRef = ref<HTMLElement | null>(null);
const lightboxThumbScrollRef = ref<HTMLElement | null>(null);

function onSelect() {
    if (!api.value) return;
    selectedIndex.value = api.value.selectedScrollSnap();
}

function scrollTo(index: number) {
    api.value?.scrollTo(index);
    lightboxApi.value?.scrollTo(index);
}

function next() {
    api.value?.scrollNext();
    lightboxApi.value?.scrollNext();
}

function prev() {
    api.value?.scrollPrev();
    lightboxApi.value?.scrollPrev();
}

function toggleLightbox() {
    showLightbox.value = !showLightbox.value;
}

// Auto-centering for thumbnails
function centerThumbnail(container: HTMLElement | null, index: number) {
    if (!container) return;
    const activeThumb = container.children[index] as HTMLElement;
    if (activeThumb) {
        const scrollLeft =
            activeThumb.offsetLeft -
            container.offsetWidth / 2 +
            activeThumb.offsetWidth / 2;
        container.scrollTo({ left: scrollLeft, behavior: 'smooth' });
    }
}

watch(selectedIndex, (val) => {
    nextTick(() => {
        centerThumbnail(thumbScrollRef.value, val);
        centerThumbnail(lightboxThumbScrollRef.value, val);
    });
});

// Keyboard navigation
function handleKeydown(e: KeyboardEvent) {
    if (e.key === 'ArrowLeft') prev();
    else if (e.key === 'ArrowRight') next();
    else if (e.key === 'Escape' && showLightbox.value)
        showLightbox.value = false;
}

watch(api, (newApi) => {
    if (!newApi) return;
    newApi.on('select', onSelect);
    newApi.on('reInit', onSelect);
    onSelect();
});

watch(lightboxApi, (newApi) => {
    if (!newApi) return;
    newApi.on('select', onSelect);
    newApi.on('reInit', onSelect);
    if (showLightbox.value) {
        newApi.scrollTo(selectedIndex.value, true);
    }
});

watch(showLightbox, (val) => {
    if (val) {
        nextTick(() => {
            lightboxApi.value?.scrollTo(selectedIndex.value, true);
        });
    }
});

onMounted(() => window.addEventListener('keydown', handleKeydown));
onUnmounted(() => window.removeEventListener('keydown', handleKeydown));
</script>

<template>
    <div class="relative flex flex-col gap-4">
        <!-- Main Stage -->
        <div
            class="group/gallery relative aspect-video min-h-0 w-full overflow-hidden rounded-3xl border border-border bg-muted/30 shadow-xl"
        >
            <!-- Navigation Overlay -->
            <div
                class="pointer-events-none absolute inset-0 z-10 p-4 opacity-100 transition-opacity duration-300 lg:opacity-0 lg:group-hover/gallery:opacity-100"
            >
                <!-- Top Right: Info & Expand -->
                <div
                    class="pointer-events-auto absolute top-4 right-4 flex gap-2 lg:top-6 lg:right-6"
                >
                    <div
                        v-if="images && images.length > 0"
                        class="hidden h-10 items-center rounded-xl bg-background/80 px-4 text-xs font-bold tracking-widest text-foreground uppercase shadow-sm backdrop-blur-sm lg:flex"
                    >
                        {{ selectedIndex + 1 }} / {{ images.length }}
                    </div>
                    <Button
                        variant="secondary"
                        size="icon"
                        @click="toggleLightbox"
                        class="h-8 w-8 cursor-pointer rounded-lg shadow-sm backdrop-blur-sm transition-colors active:scale-95 lg:h-10 lg:w-10 lg:rounded-xl"
                    >
                        <Maximize2Icon class="size-3 lg:size-4" />
                    </Button>
                </div>

                <!-- Center Left: Prev -->
                <Button
                    v-if="images && images.length > 1"
                    variant="secondary"
                    size="icon"
                    @click="prev"
                    class="pointer-events-auto absolute top-1/2 left-6 hidden h-14 w-14 -translate-y-1/2 cursor-pointer rounded-2xl shadow-sm backdrop-blur-sm transition-all hover:scale-105 active:scale-90 lg:flex"
                >
                    <ChevronLeftIcon class="size-8" />
                </Button>

                <!-- Center Right: Next -->
                <Button
                    v-if="images && images.length > 1"
                    variant="secondary"
                    size="icon"
                    @click="next"
                    class="pointer-events-auto absolute top-1/2 right-6 hidden h-14 w-14 -translate-y-1/2 cursor-pointer rounded-2xl shadow-sm backdrop-blur-sm transition-all hover:scale-105 active:scale-90 lg:flex"
                >
                    <ChevronRightIcon class="size-8" />
                </Button>
            </div>

            <Carousel
                class="h-full w-full"
                @init-api="(val) => (api = val)"
                :opts="{ loop: true, duration: 35 }"
            >
                <CarouselContent class="ml-0 h-full w-full">
                    <template v-if="images && images.length > 0">
                        <CarouselItem
                            v-for="(image, index) in images"
                            :key="image.id"
                            class="h-full w-full cursor-zoom-in pl-0"
                            @click="toggleLightbox"
                        >
                            <img
                                :src="image.large"
                                class="h-full w-full object-cover"
                                :alt="`${brandName} ${modelName} - photo ${index + 1}`"
                                loading="lazy"
                            />
                        </CarouselItem>
                    </template>
                    <CarouselItem v-else class="pl-0">
                        <div
                            class="flex h-full w-full flex-col items-center justify-center bg-muted/20 text-muted-foreground/30"
                        >
                            <CarIcon class="size-24 stroke-1" />
                            <span
                                class="mt-4 text-xs font-medium tracking-[0.2em] uppercase"
                                >Aucune image</span
                            >
                        </div>
                    </CarouselItem>
                </CarouselContent>
            </Carousel>
        </div>

        <!-- External Thumbnails Ribbon -->
        <div
            v-if="images && images.length > 1"
            ref="thumbScrollRef"
            class="scrollbar-hide flex items-center gap-3 overflow-x-auto"
        >
            <button
                v-for="(image, index) in images"
                :key="image.id"
                @click="scrollTo(index)"
                class="relative h-20 w-32 shrink-0 cursor-pointer overflow-hidden rounded-xl border-2 transition-all duration-300"
                :class="
                    selectedIndex === index
                        ? 'border-primary'
                        : 'border-transparent opacity-60 grayscale-[0.5] hover:opacity-100 hover:grayscale-0'
                "
            >
                <img
                    :src="image.thumb"
                    class="h-full w-full object-cover"
                    :alt="`Vignette ${index + 1}`"
                />
            </button>
        </div>

        <!-- Fullscreen Dialog / Lightbox -->
        <Dialog :open="showLightbox" @update:open="showLightbox = $event">
            <DialogContent
                :show-close-button="false"
                class="fixed! inset-0! flex h-screen! w-screen! max-w-none! translate-x-0! translate-y-0! flex-col overflow-hidden border-none bg-background p-0 shadow-none ring-0 sm:max-w-none!"
            >
                <DialogTitle class="sr-only">Galerie Fullscreen</DialogTitle>
                <DialogDescription class="sr-only"
                    >Parcourez les photos en haute qualité</DialogDescription
                >

                <!-- Grid Partitioning -->
                <div class="grid h-full w-full grid-rows-[5rem_1fr_10rem]">
                    <!-- 1. Header -->
                    <div
                        class="flex w-full items-center justify-between px-8 text-foreground"
                    >
                        <span
                            class="text-xs font-bold tracking-widest text-muted-foreground uppercase"
                        >
                            {{ brandName }} {{ modelName }}
                        </span>
                        <Button
                            variant="secondary"
                            size="icon"
                            @click="showLightbox = false"
                            class="h-12 w-12 cursor-pointer rounded-xl transition-colors active:scale-95"
                        >
                            <XIcon class="size-6" />
                        </Button>
                    </div>

                    <!-- 2. Main Carousel Stage -->
                    <div class="relative min-h-0 w-full overflow-hidden">
                        <!-- Navigation Arrows -->
                        <Button
                            v-if="images && images.length > 1"
                            variant="secondary"
                            size="icon"
                            @click="prev"
                            class="absolute top-1/2 left-4 z-50 flex h-12 w-12 -translate-y-1/2 cursor-pointer rounded-2xl shadow-lg transition-all lg:left-8 lg:h-20 lg:w-20 lg:rounded-3xl"
                        >
                            <ChevronLeftIcon class="size-6 lg:size-10" />
                        </Button>
                        <Button
                            v-if="images && images.length > 1"
                            variant="secondary"
                            size="icon"
                            @click="next"
                            class="absolute top-1/2 right-4 z-50 flex h-12 w-12 -translate-y-1/2 cursor-pointer rounded-2xl shadow-lg transition-all lg:right-8 lg:h-20 lg:w-20 lg:rounded-3xl"
                        >
                            <ChevronRightIcon class="size-6 lg:size-10" />
                        </Button>

                        <Carousel
                            class="h-full w-full"
                            @init-api="(val) => (lightboxApi = val)"
                            :opts="{ loop: true, duration: 40 }"
                        >
                            <CarouselContent class="ml-0 h-full w-full">
                                <CarouselItem
                                    v-for="(image, index) in images"
                                    :key="image.id"
                                    class="flex h-full w-full items-center justify-center pl-0"
                                >
                                    <img
                                        :src="image.large"
                                        class="h-full max-h-full w-full max-w-full object-contain"
                                        :alt="`Photo ${index + 1}`"
                                    />
                                </CarouselItem>
                            </CarouselContent>
                        </Carousel>
                    </div>

                    <!-- 3. Bottom Thumbnails -->
                    <div
                        v-if="images && images.length > 1"
                        class="flex w-full flex-col items-center justify-center border-t border-border bg-background/95 px-8 backdrop-blur"
                    >
                        <div
                            ref="lightboxThumbScrollRef"
                            class="scrollbar-hide flex max-w-4xl items-center gap-4 overflow-x-auto"
                        >
                            <button
                                v-for="(image, index) in images"
                                :key="image.id"
                                @click="scrollTo(index)"
                                class="group/thumb relative h-16 w-24 shrink-0 cursor-pointer overflow-hidden rounded-xl border-2 transition-all duration-300"
                                :class="
                                    selectedIndex === index
                                        ? 'border-primary ring-2 ring-primary ring-offset-2 ring-offset-background'
                                        : 'border-border/50 opacity-50 hover:border-border hover:opacity-100'
                                "
                            >
                                <img
                                    :src="image.thumb"
                                    class="h-full w-full object-cover transition-transform duration-500 group-hover/thumb:scale-110"
                                    :alt="`Vignette ${index + 1}`"
                                />
                            </button>
                        </div>
                        <div
                            class="mt-4 text-[10px] font-black tracking-[0.4em] text-muted-foreground uppercase"
                        >
                            {{ selectedIndex + 1 }} / {{ images.length }}
                        </div>
                    </div>
                </div>
            </DialogContent>
        </Dialog>
    </div>
</template>

<style scoped>
.scrollbar-hide::-webkit-scrollbar {
    display: none;
}
.scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>
