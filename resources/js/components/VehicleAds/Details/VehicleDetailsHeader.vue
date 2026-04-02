<script setup lang="ts">
import { ChevronLeft, Star } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { useTranslation } from '@/composables/useTranslation';

defineProps<{
    ad: any;
    isSelected: (id: number | string) => boolean;
}>();

const emit = defineEmits(['back', 'toggleFavorite', 'toggleComparison']);

const { __ } = useTranslation();

const back = () => emit('back');
const toggleFavorite = () => emit('toggleFavorite');
const toggleComparison = () => emit('toggleComparison');
</script>

<template>
    <div
        class="flex flex-col gap-6 p-1 sm:flex-row sm:items-start sm:justify-between sm:p-2"
    >
        <!-- Top: Navigation, Title and Mobile Star -->
        <div class="flex w-full items-start justify-between gap-4 sm:w-auto">
            <div class="flex items-start gap-3 sm:gap-4">
                <button
                    class="group flex h-10 w-10 shrink-0 cursor-pointer items-center justify-center rounded-xl border border-border/50 bg-background/50 transition-all hover:border-primary/30 hover:bg-background hover:shadow-md"
                    @click="back"
                    :title="__('vehicleAd.back_to_ads')"
                >
                    <ChevronLeft
                        class="h-5 w-5 text-muted-foreground transition-colors group-hover:text-primary"
                    />
                </button>
                <div class="min-w-0 flex-1">
                    <h1 class="text-2xl font-bold sm:text-3xl">
                        {{ ad.brand?.name }}
                        {{ ad.model?.name }}
                    </h1>
                    <h2
                        class="mt-1 text-sm font-bold tracking-wider text-muted-foreground/80 uppercase"
                    >
                        {{ ad.vehicle_version_name }}
                    </h2>
                </div>
            </div>

            <!-- Mobile Favorite -->
            <Button
                variant="ghost"
                size="icon"
                class="size-10 shrink-0 cursor-pointer rounded-md border border-border bg-background transition-all hover:border-primary hover:bg-primary/10 hover:text-primary sm:hidden"
                :class="{
                    'border-primary/20 fill-primary text-primary':
                        ad.is_favorited,
                }"
                @click="toggleFavorite"
            >
                <Star
                    class="size-5"
                    :class="{
                        'fill-primary': ad.is_favorited,
                    }"
                />
            </Button>
        </div>

        <!-- Right: Actions & Price -->
        <div class="flex flex-col gap-4 sm:items-end">
            <!-- Price Badge -->
            <div
                class="inline-flex self-start bg-primary py-1 pr-6 pl-4 text-white shadow-sm [clip-path:polygon(0_0,90%_0,100%_100%,0_100%)] sm:self-auto sm:py-1.5 sm:pr-5 sm:pl-7 sm:[clip-path:polygon(10%_0,100%_0,100%_100%,0_100%)]"
            >
                <div class="flex items-end gap-2">
                    <span class="text-2xl font-black tracking-tight sm:text-3xl">
                        {{ Number(ad.price).toLocaleString('fr-FR') }}
                        €
                    </span>
                    <span
                        class="pb-0.5 text-[10px] font-semibold tracking-[0.18em] text-white/80 uppercase sm:pb-1 sm:text-[11px]"
                    >
                        {{
                            String(ad.vat_mode ?? '').toLowerCase() ===
                            'htva'
                                ? __('vehicleAd.vat_excluded')
                                : __('vehicleAd.vat_included')
                        }}
                    </span>
                </div>
            </div>

            <!-- Desktop Actions -->
            <div class="hidden items-center gap-3 sm:flex">
                <Button
                    variant="ghost"
                    size="icon"
                    class="size-10 shrink-0 cursor-pointer rounded-md border border-border bg-background transition-all hover:border-primary hover:bg-primary/10 hover:text-primary"
                    :class="{
                        'border-primary/20 fill-primary text-primary':
                            ad.is_favorited,
                    }"
                    @click="toggleFavorite"
                >
                    <Star
                        class="size-5"
                        :class="{
                            'fill-primary': ad.is_favorited,
                        }"
                    />
                </Button>
                <Button
                    variant="ghost"
                    class="hidden h-10 cursor-pointer items-center gap-2 rounded-md border border-border bg-background px-3 transition-all hover:border-primary/30 lg:flex"
                    :class="{
                        'border-primary/20 bg-primary/5 text-primary':
                            isSelected(ad.id),
                    }"
                    @click="toggleComparison"
                >
                    <Checkbox
                        :id="`compare-${ad.id}`"
                        :model-value="isSelected(ad.id)"
                        class="pointer-events-none size-4"
                    />
                    <span class="text-xs font-bold tracking-tight uppercase">
                        {{ __('vehicleAd.compare') }}
                    </span>
                </Button>
            </div>
        </div>
    </div>
</template>
