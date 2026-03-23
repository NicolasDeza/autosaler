<script setup lang="ts">
import {
    ChevronLeft,
    ChevronRight,
    ChevronsLeft,
    ChevronsRight,
} from 'lucide-vue-next';
import { computed } from 'vue';
import {
    Pagination,
    PaginationContent,
    PaginationEllipsis,
    PaginationFirst,
    PaginationItem,
    PaginationLast,
    PaginationNext,
    PaginationPrevious,
} from '@/components/ui/pagination';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { useTranslation } from '@/composables/useTranslation';

interface LaravelPagination {
    data: any[];
    total: number;
    current_page: number;
    last_page: number;
    from: number;
    to: number;
    per_page: number;
    [key: string]: any;
}

const props = withDefaults(
    defineProps<{
        pagination: LaravelPagination;
        perPage: string | number;
        resourceLabel?: string;
    }>(),
    {
        resourceLabel: 'annonces',
    },
);

const emit = defineEmits<{
    (e: 'update:perPage', value: string): void;
    (e: 'update:page', value: number): void;
}>();

const { __ } = useTranslation();

const internalPerPage = computed({
    get: () => String(props.perPage),
    set: (val) => emit('update:perPage', val),
});
</script>

<template>
    <div
        v-if="pagination && pagination.data && pagination.data.length"
        class="mt-6 flex flex-col items-center justify-between gap-6 rounded-xl border border-border/40 bg-card/60 p-5 backdrop-blur-sm md:flex-row"
    >
        <!-- Pagination Controls (Top on Mobile, Center on Desktop) -->
        <div class="order-1 md:order-2">
            <Pagination
                :total="pagination.total"
                :sibling-count="1"
                show-edges
                :items-per-page="parseInt(internalPerPage)"
                :page="pagination.current_page"
                @update:page="emit('update:page', $event)"
            >
                <PaginationContent
                    v-slot="{ items }"
                    class="flex items-center gap-1.5"
                >
                    <PaginationFirst
                        class="h-9 w-9 cursor-pointer border-none bg-muted/40 transition-all hover:bg-muted/80 hover:text-primary active:scale-90"
                    >
                        <ChevronsLeft class="h-4 w-4" />
                    </PaginationFirst>

                    <PaginationPrevious
                        class="h-9 cursor-pointer gap-1.5 border-none bg-muted/40 px-3 transition-all hover:bg-muted/80 hover:text-primary active:scale-90"
                    >
                        <ChevronLeft class="h-4 w-4" />
                        <span
                            class="hidden text-xs font-bold tracking-widest uppercase lg:block"
                            >{{ __('pagination.previous') }}</span
                        >
                    </PaginationPrevious>

                    <div
                        class="mx-1 flex flex-wrap items-center justify-center gap-1"
                    >
                        <template v-for="(item, index) in items">
                            <PaginationItem
                                v-if="item.type === 'page'"
                                :key="index"
                                :value="item.value"
                                :is-active="item.value === pagination.current_page"
                                class="h-9 w-9 cursor-pointer font-bold transition-all hover:bg-muted/80 active:scale-90"
                                :class="{
                                    'bg-primary text-primary-foreground shadow-md shadow-primary/20 hover:bg-primary/90':
                                        item.value === pagination.current_page,
                                    'bg-transparent text-muted-foreground':
                                        item.value !== pagination.current_page,
                                }"
                            >
                                {{ item.value }}
                            </PaginationItem>
                            <PaginationEllipsis
                                v-else
                                :key="item.type"
                                :index="index"
                                class="text-muted-foreground/40"
                            />
                        </template>
                    </div>

                    <PaginationNext
                        class="h-9 cursor-pointer gap-1.5 border-none bg-muted/40 px-3 transition-all hover:bg-muted/80 hover:text-primary active:scale-90"
                    >
                        <span
                            class="hidden text-xs font-bold tracking-widest uppercase lg:block"
                            >{{ __('pagination.next') }}</span
                        >
                        <ChevronRight class="h-4 w-4" />
                    </PaginationNext>

                    <PaginationLast
                        class="h-9 w-9 cursor-pointer border-none bg-muted/40 transition-all hover:bg-muted/80 hover:text-primary active:scale-90"
                    >
                        <ChevronsRight class="h-4 w-4" />
                    </PaginationLast>
                </PaginationContent>
            </Pagination>
        </div>

        <!-- Per Page & Counter Wrapper (Bottom on Mobile, Dissolved on Desktop) -->
        <div class="order-2 flex w-full flex-row flex-wrap items-center justify-between gap-4 md:contents">
            <!-- Per Page Selector (Left on Desktop) -->
            <div class="flex items-center gap-2 md:order-1">
                <span
                    class="text-[11px] font-bold tracking-wider text-muted-foreground/80 uppercase"
                >
                    {{ __('pagination.show') }}
                </span>
                <Select v-model="internalPerPage">
                    <SelectTrigger
                        class="h-8 w-[72px] border-border/40 bg-muted/40 text-xs font-semibold transition-all hover:bg-muted/60 focus:bg-background focus:ring-1 focus:ring-primary/20"
                    >
                        <SelectValue />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem value="10">10</SelectItem>
                        <SelectItem value="15">15</SelectItem>
                        <SelectItem value="25">25</SelectItem>
                        <SelectItem value="50">50</SelectItem>
                        <SelectItem value="100">100</SelectItem>
                    </SelectContent>
                </Select>
            </div>

            <!-- Ad Counter Info (Right on Desktop) -->
            <div class="md:order-3">
                <div class="text-right text-[12px] font-medium text-muted-foreground/90 sm:text-[13px]">
                    <span class="font-bold text-foreground">{{
                        pagination.from
                    }}</span>
                    <span class="mx-0.5 text-muted-foreground/60">{{
                        __('pagination.to')
                    }}</span>
                    <span class="font-bold text-foreground">{{
                        pagination.to
                    }}</span>
                    <span class="mx-0.5 text-muted-foreground/60">{{
                        __('pagination.of')
                    }}</span>
                    <span class="font-extrabold text-primary">{{
                        pagination.total
                    }}</span>
                    <span class="ml-0.5 font-medium capitalize">{{
                        resourceLabel
                    }}</span>
                </div>
            </div>
        </div>
    </div>
</template>
