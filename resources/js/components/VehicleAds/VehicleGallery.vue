<script setup lang="ts">
import { CarIcon } from 'lucide-vue-next';
import { ref, watch } from 'vue';
import { Card, CardContent } from '@/components/ui/card';
import {
    Carousel,
    CarouselContent,
    CarouselItem,
    CarouselNext,
    CarouselPrevious,
    type CarouselApi,
} from '@/components/ui/carousel';

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
const selectedIndex = ref(0);

function onSelect() {
    if (!api.value) return;
    selectedIndex.value = api.value.selectedScrollSnap();
}

function scrollTo(index: number) {
    api.value?.scrollTo(index);
}

watch(api, (newApi) => {
    if (!newApi) return;

    newApi.on('select', onSelect);
    newApi.on('reInit', onSelect);

    // Initial state
    onSelect();
});
</script>

<template>
    <div
        class="relative space-y-4 rounded-2xl bg-muted/40 p-2 shadow-inner lg:p-3"
    >
        <!-- Main Carousel -->
        <div
            class="relative overflow-hidden rounded-xl bg-background shadow-xl ring-1 ring-border/50"
        >
            <Carousel
                class="group relative w-full"
                @init-api="(val) => (api = val)"
                :opts="{
                    loop: true,
                    duration: 30,
                }"
            >
                <CarouselContent class="ml-0">
                    <template v-if="images && images.length > 0">
                        <CarouselItem
                            v-for="(image, index) in images"
                            :key="image.id"
                            class="pl-0"
                        >
                            <div class="p-0">
                                <Card
                                    class="overflow-hidden border-none bg-transparent shadow-none"
                                >
                                    <CardContent
                                        class="flex aspect-video items-center justify-center p-0"
                                    >
                                        <img
                                            :src="image.large"
                                            class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105"
                                            :alt="`${brandName} ${modelName} - photo ${index + 1}`"
                                            loading="lazy"
                                        />
                                    </CardContent>
                                </Card>
                            </div>
                        </CarouselItem>
                    </template>
                    <CarouselItem v-else class="pl-0">
                        <div class="p-0">
                            <Card
                                class="overflow-hidden border-none bg-muted/30 shadow-none"
                            >
                                <CardContent
                                    class="flex aspect-video items-center justify-center p-0"
                                >
                                    <div
                                        class="flex flex-col items-center gap-4 text-muted-foreground/30"
                                    >
                                        <CarIcon class="size-20" />
                                        <span
                                            class="text-sm font-medium tracking-widest uppercase"
                                            >Aucune image</span
                                        >
                                    </div>
                                </CardContent>
                            </Card>
                        </div>
                    </CarouselItem>
                </CarouselContent>

                <template v-if="images && images.length > 1">
                    <CarouselPrevious
                        class="left-4 h-11 w-11 border-none bg-background/80 text-foreground opacity-0 shadow-lg backdrop-blur-md transition-all group-hover:opacity-100 hover:scale-110 hover:bg-primary hover:text-white disabled:opacity-0"
                    />
                    <CarouselNext
                        class="right-4 h-11 w-11 border-none bg-background/80 text-foreground opacity-0 shadow-lg backdrop-blur-md transition-all group-hover:opacity-100 hover:scale-110 hover:bg-primary hover:text-white disabled:opacity-0"
                    />
                </template>

                <!-- Counter overlay -->
                <div
                    v-if="images && images.length > 1"
                    class="absolute right-4 bottom-4 z-10 rounded-full border border-white/20 bg-black/60 px-4 py-1 text-[10px] font-black tracking-[0.2em] text-white uppercase backdrop-blur-md"
                >
                    {{ selectedIndex + 1 }}
                    <span class="mx-1 opacity-50">/</span> {{ images.length }}
                </div>
            </Carousel>
        </div>

        <!-- Thumbnails scroll container -->
        <div
            v-if="images && images.length > 1"
            class="scrollbar-hide flex items-center gap-3 overflow-x-auto rounded-xl bg-background/30 p-2 ring-1 ring-border/30"
        >
            <button
                v-for="(image, index) in images"
                :key="image.id"
                class="relative h-18 w-28 shrink-0 cursor-pointer overflow-hidden rounded-lg border-2 p-0.5 transition-all duration-300 outline-none"
                :class="
                    selectedIndex === index
                        ? 'z-10 scale-105 border-primary shadow-lg ring-4 ring-primary/10'
                        : 'border-transparent opacity-40 grayscale-[0.5] hover:border-primary/30 hover:opacity-100 hover:grayscale-0'
                "
                @click="scrollTo(index)"
            >
                <img
                    :src="image.thumb"
                    class="h-full w-full rounded-md object-cover"
                    :alt="`${brandName} ${modelName} - vignette ${index + 1}`"
                />
            </button>
        </div>
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
