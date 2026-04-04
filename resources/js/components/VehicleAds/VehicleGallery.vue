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
    <div class="relative flex w-full flex-col gap-4 overflow-hidden">
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
                        v-if="images && images.length > 0"
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
            class="scrollbar-thin flex w-full items-center gap-3 overflow-x-auto p-1 scrollbar-thumb-primary/40 scrollbar-track-transparent hover:scrollbar-thumb-primary"
        >
            <button
                v-for="(image, index) in images"
                :key="image.id"
                @click="scrollTo(index)"
                class="relative h-16 w-24 shrink-0 cursor-pointer overflow-hidden rounded-xl border-2 transition-all duration-300 lg:h-20 lg:w-32"
                :class="
                    selectedIndex === index
                        ? 'scale-105 border-primary ring-2 ring-primary/20'
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

        <Dialog :open="showLightbox" @update:open="showLightbox = $event">
            <DialogContent
                :show-close-button="false"
                class="fixed! inset-0! z-50! flex h-screen! w-screen! max-w-none! translate-x-0! translate-y-0! flex-col overflow-hidden border-none bg-zinc-950 p-0 shadow-none ring-0 sm:max-w-none!"
            >
                <DialogTitle class="sr-only">Galerie Fullscreen</DialogTitle>
                <DialogDescription class="sr-only">
                    Parcourez les photos en haute qualité
                </DialogDescription>

                <!-- Fullscreen Layout -->
                <div class="flex h-full w-full flex-col overflow-hidden">
                    <!-- 1. Elegant Header -->
                    <div
                        class="flex h-20 w-full shrink-0 items-center justify-between px-6 lg:px-12"
                    >
                        <div class="flex flex-col gap-0.5">
                            <span
                                class="text-[10px] font-black tracking-[0.3em] text-zinc-500 uppercase lg:text-xs"
                            >
                                {{ brandName }}
                            </span>
                            <span
                                class="text-sm font-bold text-zinc-100 lg:text-base"
                            >
                                {{ modelName }}
                            </span>
                        </div>

                        <div class="flex items-center gap-4">
                            <div
                                class="hidden items-center rounded-full bg-zinc-900/50 px-4 py-1.5 text-[10px] font-black tracking-widest text-zinc-400 uppercase backdrop-blur-md lg:flex"
                            >
                                {{ selectedIndex + 1 }} / {{ images.length }}
                            </div>
                            <Button
                                variant="ghost"
                                size="icon"
                                @click="showLightbox = false"
                                class="h-10 w-10 cursor-pointer rounded-full bg-zinc-900 text-zinc-100 hover:bg-zinc-800 lg:h-12 lg:w-12"
                            >
                                <XIcon class="size-5 lg:size-6" />
                            </Button>
                        </div>
                    </div>

                    <!-- 2. Main Stage (Flex-1 for responsiveness) -->
                    <div
                        class="group/lightbox relative min-h-0 w-full flex-1 overflow-hidden"
                    >
                        <!-- Floating Navigation Arrows -->
                        <div
                            class="pointer-events-none absolute inset-y-0 left-0 z-50 flex items-center px-4"
                        >
                            <Button
                                v-if="images && images.length > 1"
                                variant="secondary"
                                size="icon"
                                @click="prev"
                                class="pointer-events-auto h-12 w-12 cursor-pointer rounded-full border-none bg-zinc-900/80 text-zinc-100 shadow-2xl backdrop-blur-md transition-all hover:scale-110 hover:bg-zinc-800 lg:h-20 lg:w-20"
                            >
                                <ChevronLeftIcon class="size-6 lg:size-10" />
                            </Button>
                        </div>

                        <div
                            class="pointer-events-none absolute inset-y-0 right-0 z-50 flex items-center px-4"
                        >
                            <Button
                                v-if="images && images.length > 1"
                                variant="secondary"
                                size="icon"
                                @click="next"
                                class="pointer-events-auto h-12 w-12 cursor-pointer rounded-full border-none bg-zinc-900/80 text-zinc-100 shadow-2xl backdrop-blur-md transition-all hover:scale-110 hover:bg-zinc-800 lg:h-20 lg:w-20"
                            >
                                <ChevronRightIcon class="size-6 lg:size-10" />
                            </Button>
                        </div>

                        <Carousel
                            class="h-full w-full"
                            @init-api="(val) => (lightboxApi = val)"
                            :opts="{ loop: true, duration: 45 }"
                        >
                            <CarouselContent class="ml-0 h-full w-full">
                                <CarouselItem
                                    v-for="(image, index) in images"
                                    :key="image.id"
                                    class="flex h-full w-full items-center justify-center pl-0"
                                >
                                    <div
                                        class="flex h-full w-full items-center justify-center p-4 lg:p-8"
                                    >
                                        <img
                                            :src="image.large"
                                            class="h-auto max-h-full w-auto max-w-full object-contain shadow-2xl transition-all duration-500 select-none"
                                            :alt="`Photo ${index + 1}`"
                                            draggable="false"
                                        />
                                    </div>
                                </CarouselItem>
                            </CarouselContent>
                        </Carousel>
                    </div>

                    <!-- 3. Refined Bottom Thumbnails Row -->
                    <div
                        v-if="images && images.length > 1"
                        class="flex w-full shrink-0 flex-col items-center border-t border-zinc-800/50 bg-zinc-950/80 px-6 py-6 backdrop-blur-xl lg:px-12"
                    >
                        <div
                            ref="lightboxThumbScrollRef"
                            class="scrollbar-none flex w-full max-w-5xl items-center gap-3 overflow-x-auto p-1 lg:px-6 lg:py-4"
                        >
                            <button
                                v-for="(image, index) in images"
                                :key="image.id"
                                @click="scrollTo(index)"
                                class="group/thumb relative h-14 w-20 shrink-0 cursor-pointer overflow-hidden rounded-lg border-2 transition-all duration-300 lg:h-20 lg:w-32"
                                :class="
                                    selectedIndex === index
                                        ? 'scale-105 border-primary ring-2 ring-primary/20'
                                        : 'border-transparent opacity-40 grayscale-[0.8] hover:opacity-100 hover:grayscale-0'
                                "
                            >
                                <img
                                    :src="image.thumb"
                                    class="h-full w-full object-cover transition-transform duration-500 group-hover/thumb:scale-110"
                                    :alt="`Vignette ${index + 1}`"
                                />
                            </button>
                        </div>

                        <!-- Progress indicator for mobile -->
                        <div class="mt-4 flex gap-1 lg:hidden">
                            <div
                                v-for="(_, i) in images"
                                :key="i"
                                class="h-1 rounded-full transition-all duration-300"
                                :class="
                                    i === selectedIndex
                                        ? 'w-4 bg-primary'
                                        : 'w-1 bg-zinc-800'
                                "
                            />
                        </div>
                    </div>
                </div>
            </DialogContent>
        </Dialog>
    </div>
</template>
