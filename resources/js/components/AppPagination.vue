<script setup lang="ts">
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

const internalPerPage = computed({
    get: () => String(props.perPage),
    set: (val) => emit('update:perPage', val),
});
</script>

<template>
    <div
        v-if="pagination.data.length"
        class="mt-4 flex flex-col items-center justify-between gap-4 rounded-lg bg-slate-100 p-4 shadow-sm md:flex-row"
    >
        <!-- Per Page Selector & Info -->
        <div class="flex items-center gap-4">
            <span class="text-sm font-medium text-slate-600"> Afficher </span>
            <Select v-model="internalPerPage">
                <SelectTrigger class="w-20 bg-white">
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
            <span class="text-xs text-slate-500">
                Affichage de {{ pagination.from }} à {{ pagination.to }} sur
                {{ pagination.total }} {{ resourceLabel }}
            </span>
        </div>

        <!-- Pagination Controls -->
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
                class="flex items-center gap-1"
            >
                <PaginationFirst />
                <PaginationPrevious />

                <template v-for="(item, index) in items">
                    <PaginationItem
                        v-if="item.type === 'page'"
                        :key="index"
                        :value="item.value"
                        :is-active="item.value === pagination.current_page"
                        class="h-9 w-9 p-0"
                    >
                        {{ item.value }}
                    </PaginationItem>
                    <PaginationEllipsis
                        v-else
                        :key="item.type"
                        :index="index"
                    />
                </template>

                <PaginationNext />
                <PaginationLast />
            </PaginationContent>
        </Pagination>

        <!-- Page Indicator -->
        <div class="text-sm font-medium text-slate-600">
            Page {{ pagination.current_page }} sur {{ pagination.last_page }}
        </div>
    </div>
</template>
