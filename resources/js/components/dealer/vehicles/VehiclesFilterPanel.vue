<script setup lang="ts">
import {
    useBreakpoints,
    breakpointsTailwind,
    useDebounceFn,
} from '@vueuse/core';
import {
    RotateCcw,
    SlidersHorizontal,
    Calendar,
    Tag,
    Activity,
    Search,
} from 'lucide-vue-next';
import { ref, watch, computed } from 'vue';
import SheetMenu from '@/components/SheetMenu.vue';
import { Button } from '@/components/ui/button';
import { Collapsible, CollapsibleContent } from '@/components/ui/collapsible';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { useTranslation } from '@/composables/useTranslation';

interface Props {
    open: boolean;
    filters: {
        search?: string;
        brand_id?: string | number;
        model_id?: string | number;
        status?: string;
        date_from?: string;
        date_to?: string;
    };
    brands?: any[];
    models?: any[];
}

const props = defineProps<Props>();
const emit = defineEmits(['update:filters', 'reset', 'update:open']);

const { __ } = useTranslation();
const breakpoints = useBreakpoints(breakpointsTailwind);
const isDesktop = breakpoints.greaterOrEqual('lg');

const internalSearch = ref(props.filters.search || '');
const brandFilter = ref(props.filters.brand_id?.toString() || 'all');
const modelFilter = ref(props.filters.model_id?.toString() || 'all');
const statusFilter = ref(props.filters.status || 'all');
const dateFrom = ref(props.filters.date_from || '');
const dateTo = ref(props.filters.date_to || '');

// Automatic Application Logic
const emitFilters = useDebounceFn(() => {
    emit('update:filters', {
        search: internalSearch.value || undefined,
        brand_id: brandFilter.value === 'all' ? undefined : brandFilter.value,
        model_id: modelFilter.value === 'all' ? undefined : modelFilter.value,
        status: statusFilter.value === 'all' ? undefined : statusFilter.value,
        date_from: dateFrom.value || undefined,
        date_to: dateTo.value || undefined,
    });
}, 300);

watch(
    () => props.filters,
    (newFilters) => {
        // Only update if values are actually different to avoid loops
        if (newFilters.search !== internalSearch.value)
            internalSearch.value = newFilters.search || '';
        if (newFilters.brand_id?.toString() !== brandFilter.value)
            brandFilter.value = newFilters.brand_id?.toString() || 'all';
        if (newFilters.model_id?.toString() !== modelFilter.value)
            modelFilter.value = newFilters.model_id?.toString() || 'all';
        if (newFilters.status !== statusFilter.value)
            statusFilter.value = newFilters.status || 'all';
        if (newFilters.date_from !== dateFrom.value)
            dateFrom.value = newFilters.date_from || '';
        if (newFilters.date_to !== dateTo.value)
            dateTo.value = newFilters.date_to || '';
    },
    { deep: true },
);

// Trigger automatic apply on change
watch(
    [internalSearch, brandFilter, modelFilter, statusFilter, dateFrom, dateTo],
    () => {
        emitFilters();
    },
);

watch(brandFilter, (newBrand) => {
    if (newBrand !== (props.filters.brand_id?.toString() || 'all')) {
        modelFilter.value = 'all';
    }
});

const handleReset = () => {
    internalSearch.value = '';
    brandFilter.value = 'all';
    modelFilter.value = 'all';
    statusFilter.value = 'all';
    dateFrom.value = '';
    dateTo.value = '';
    emit('reset');
};

const handleOpenChange = (val: boolean) => {
    emit('update:open', val);
};

const isSearchActive = computed(() => internalSearch.value.trim() !== '');
const isBrandActive = computed(() => brandFilter.value !== 'all');
const isModelActive = computed(() => modelFilter.value !== 'all');
const isStatusActive = computed(() => statusFilter.value !== 'all');
const isDateActive = computed(
    () => dateFrom.value !== '' || dateTo.value !== '',
);
</script>

<template>
    <!-- Desktop: Single-line Inline Grid -->
    <Collapsible v-if="isDesktop" :open="open">
        <CollapsibleContent>
            <div
                class="flex items-end gap-4 border-b border-border/40 bg-muted/20 px-6 py-4"
            >
                <div class="flex-1 space-y-1.5">
                    <Label
                        class="text-[10px] font-bold tracking-wider text-muted-foreground/60 uppercase"
                        >{{ __('dealer.brand') }}</Label
                    >
                    <Select v-model="brandFilter" :disabled="!brands">
                        <SelectTrigger
                            class="h-9 border-border/40 bg-background/50 text-xs transition-all focus:ring-1 focus:ring-primary/20"
                        >
                            <SelectValue
                                :placeholder="
                                    !brands
                                        ? __('ui.loading')
                                        : __('dealer.all_brands')
                                "
                            />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="all">{{
                                __('dealer.all_brands')
                            }}</SelectItem>
                            <SelectItem
                                v-for="brand in brands"
                                :key="brand.id"
                                :value="brand.id.toString()"
                            >
                                {{ brand.name }}
                            </SelectItem>
                        </SelectContent>
                    </Select>
                </div>

                <div class="flex-1 space-y-1.5">
                    <Label
                        class="text-[10px] font-bold tracking-wider text-muted-foreground/60 uppercase"
                        >{{ __('dealer.model') }}</Label
                    >
                    <Select v-model="modelFilter" :disabled="!models">
                        <SelectTrigger
                            class="h-9 border-border/40 bg-background/50 text-xs transition-all focus:ring-1 focus:ring-primary/20"
                        >
                            <SelectValue
                                :placeholder="
                                    !models
                                        ? __('ui.loading')
                                        : __('dealer.all_models')
                                "
                            />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="all">{{
                                __('dealer.all_models')
                            }}</SelectItem>
                            <SelectItem
                                v-for="model in models"
                                :key="model.id"
                                :value="model.id.toString()"
                            >
                                {{ model.name }}
                            </SelectItem>
                        </SelectContent>
                    </Select>
                </div>

                <div class="flex-1 space-y-1.5">
                    <Label
                        class="text-[10px] font-bold tracking-wider text-muted-foreground/60 uppercase"
                        >{{ __('dealer.status') }}</Label
                    >
                    <Select v-model="statusFilter">
                        <SelectTrigger
                            class="h-9 border-border/40 bg-background/50 text-xs transition-all focus:ring-1 focus:ring-primary/20"
                        >
                            <SelectValue
                                :placeholder="__('dealer.all_statuses')"
                            />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="all">{{
                                __('dealer.all_statuses')
                            }}</SelectItem>
                            <SelectItem value="active">{{
                                __('dealer.active_status')
                            }}</SelectItem>
                            <SelectItem value="draft">{{
                                __('dealer.draft_status')
                            }}</SelectItem>
                            <SelectItem value="sold">{{
                                __('dealer.sold_status')
                            }}</SelectItem>
                        </SelectContent>
                    </Select>
                </div>

                <div class="flex-1 space-y-1.5">
                    <Label
                        class="text-[10px] font-bold tracking-wider text-muted-foreground/60 uppercase"
                        >{{ __('dealer.from_date') }}</Label
                    >
                    <Input
                        type="date"
                        v-model="dateFrom"
                        class="h-9 border-border/40 bg-background/50 text-xs transition-all focus-visible:ring-1 focus-visible:ring-primary/20"
                    />
                </div>

                <div class="flex-1 space-y-1.5">
                    <Label
                        class="text-[10px] font-bold tracking-wider text-muted-foreground/60 uppercase"
                        >{{ __('dealer.to_date') }}</Label
                    >
                    <Input
                        type="date"
                        v-model="dateTo"
                        class="h-9 border-border/40 bg-background/50 text-xs transition-all focus-visible:ring-1 focus-visible:ring-primary/20"
                    />
                </div>

                <div class="pb-0.5">
                    <Button
                        variant="ghost"
                        size="sm"
                        class="group h-9 px-3 text-xs font-bold transition-all hover:bg-muted"
                        @click="handleReset"
                    >
                        <RotateCcw
                            class="mr-1.5 h-3.5 w-3.5 transition-transform group-hover:-rotate-45"
                        />
                        {{ __('ui.reset') }}
                    </Button>
                </div>
            </div>
        </CollapsibleContent>
    </Collapsible>

    <!-- Mobile Layout: Left-side Dark Sheet with "Attached" Floating Button -->
    <template v-else>
        <SheetMenu
            :open="open"
            @update:open="handleOpenChange"
            side="left"
            :title="__('ui.filters')"
            :description="__('dealer.manage_ads_description')"
            :icon="SlidersHorizontal"
            with-floating-button
        >
            <div class="space-y-8">
                <!-- Mobile Search Section -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <Search
                                class="h-3.5 w-3.5 text-muted-foreground/60"
                            />
                            <Label
                                class="text-[11px] font-bold tracking-wider text-muted-foreground/80 uppercase"
                                >{{ __('ui.search') }}</Label
                            >
                        </div>
                        <div
                            v-if="isSearchActive"
                            class="h-1.5 w-1.5 rounded-full bg-primary"
                        ></div>
                    </div>
                    <div class="relative">
                        <Search
                            class="absolute top-1/2 left-3 h-3.5 w-3.5 -translate-y-1/2 text-muted-foreground/50"
                        />
                        <Input
                            v-model="internalSearch"
                            :placeholder="__('ui.search')"
                            class="h-11 border-border/20 bg-muted/10 pl-9 text-sm text-foreground transition-all focus-visible:bg-muted/20 focus-visible:ring-1 focus-visible:ring-primary/20"
                        />
                    </div>
                </div>

                <!-- Brand & Model Section -->
                <div class="space-y-4 border-t border-border/10 pt-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <Tag class="h-3.5 w-3.5 text-muted-foreground/60" />
                            <Label
                                class="text-[11px] font-bold tracking-wider text-muted-foreground/80 uppercase"
                                >{{ __('dealer.vehicle') }}</Label
                            >
                        </div>
                        <div
                            v-if="isBrandActive || isModelActive"
                            class="h-1.5 w-1.5 animate-pulse rounded-full bg-primary"
                        ></div>
                    </div>

                    <div class="grid gap-4">
                        <div class="space-y-2">
                            <Select v-model="brandFilter" :disabled="!brands">
                                <SelectTrigger
                                    class="h-11 border-border/20 bg-muted/10 text-sm text-foreground transition-all focus:ring-1 focus:ring-primary/20"
                                >
                                    <SelectValue
                                        :placeholder="
                                            !brands
                                                ? __('ui.loading')
                                                : __('dealer.all_brands')
                                        "
                                    />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="all">{{
                                        __('dealer.all_brands')
                                    }}</SelectItem>
                                    <SelectItem
                                        v-for="brand in brands"
                                        :key="brand.id"
                                        :value="brand.id.toString()"
                                    >
                                        {{ brand.name }}
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                        </div>

                        <div class="space-y-2">
                            <Select v-model="modelFilter" :disabled="!models">
                                <SelectTrigger
                                    class="h-11 border-border/20 bg-muted/10 text-sm text-foreground transition-all focus:ring-1 focus:ring-primary/20"
                                >
                                    <SelectValue
                                        :placeholder="
                                            !models
                                                ? __('ui.loading')
                                                : __('dealer.all_models')
                                        "
                                    />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="all">{{
                                        __('dealer.all_models')
                                    }}</SelectItem>
                                    <SelectItem
                                        v-for="model in models"
                                        :key="model.id"
                                        :value="model.id.toString()"
                                    >
                                        {{ model.name }}
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                        </div>
                    </div>
                </div>

                <!-- Status Section -->
                <div class="space-y-4 border-t border-border/10 pt-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <Activity
                                class="h-3.5 w-3.5 text-muted-foreground/60"
                            />
                            <Label
                                class="text-[11px] font-bold tracking-wider text-muted-foreground/80 uppercase"
                                >{{ __('dealer.status') }}</Label
                            >
                        </div>
                        <div
                            v-if="isStatusActive"
                            class="h-1.5 w-1.5 rounded-full bg-primary"
                        ></div>
                    </div>

                    <Select v-model="statusFilter">
                        <SelectTrigger
                            class="h-11 border-border/20 bg-muted/10 text-sm text-foreground transition-all focus:ring-1 focus:ring-primary/20"
                        >
                            <SelectValue
                                :placeholder="__('dealer.all_statuses')"
                            />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="all">{{
                                __('dealer.all_statuses')
                            }}</SelectItem>
                            <SelectItem value="active">{{
                                __('dealer.active_status')
                            }}</SelectItem>
                            <SelectItem value="draft">{{
                                __('dealer.draft_status')
                            }}</SelectItem>
                            <SelectItem value="sold">{{
                                __('dealer.sold_status')
                            }}</SelectItem>
                        </SelectContent>
                    </Select>
                </div>

                <!-- Date Range Section -->
                <div class="space-y-4 border-t border-border/10 pt-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <Calendar
                                class="h-3.5 w-3.5 text-muted-foreground/60"
                            />
                            <Label
                                class="text-[11px] font-bold tracking-wider text-muted-foreground/80 uppercase"
                                >{{ __('ui.date') }}</Label
                            >
                        </div>
                        <div
                            v-if="isDateActive"
                            class="h-1.5 w-1.5 rounded-full bg-primary"
                        ></div>
                    </div>

                    <div class="grid gap-4">
                        <div class="space-y-2">
                            <span
                                class="text-[10px] font-bold text-muted-foreground/50 uppercase"
                                >{{ __('dealer.from_date') }}</span
                            >
                            <Input
                                type="date"
                                v-model="dateFrom"
                                class="scheme:dark h-11 border-border/20 bg-muted/10 text-sm text-foreground transition-all focus-visible:ring-1 focus-visible:ring-primary/20"
                            />
                        </div>
                        <div class="space-y-2">
                            <span
                                class="text-[10px] font-bold text-muted-foreground/50 uppercase"
                                >{{ __('dealer.to_date') }}</span
                            >
                            <Input
                                type="date"
                                v-model="dateTo"
                                class="h-11 border-border/20 bg-muted/10 text-sm text-foreground scheme-dark transition-all focus-visible:ring-1 focus-visible:ring-primary/20"
                            />
                        </div>
                    </div>
                </div>
            </div>

            <template #footer>
                <div class="flex w-full flex-col gap-3">
                    <Button
                        variant="ghost"
                        class="group h-11 w-full font-bold text-foreground transition-all hover:bg-muted"
                        @click="handleReset"
                    >
                        <RotateCcw
                            class="mr-2 h-4 w-4 transition-transform group-hover:-rotate-45"
                        />
                        {{ __('ui.reset') }}
                    </Button>
                </div>
            </template>
        </SheetMenu>
    </template>
</template>
