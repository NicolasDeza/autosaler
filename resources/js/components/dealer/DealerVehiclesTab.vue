<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { useDebounceFn } from '@vueuse/core';
import { ref, computed } from 'vue';
import AppPagination from '@/components/AppPagination.vue';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import dealer from '@/routes/dealer';
import vehicles from '@/routes/vehicles';
import VehiclesFilterPanel from './vehicles/VehiclesFilterPanel.vue';
import VehiclesSearchHeader from './vehicles/VehiclesSearchHeader.vue';
import VehiclesTable from './vehicles/VehiclesTable.vue';

interface Props {
    ads: any;
    filters: {
        search?: string;
        sort?: string;
        per_page?: string | number;
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

const currentSort = ref(props.filters.sort || 'latest');
const showFilters = ref(
    props.filters.brand_id !== undefined ||
        props.filters.model_id !== undefined ||
        props.filters.status !== undefined ||
        props.filters.date_from !== undefined ||
        props.filters.date_to !== undefined,
);

const activeFiltersCount = computed(() => {
    let count = 0;
    if (props.filters.brand_id) count++;
    if (props.filters.model_id) count++;
    if (props.filters.status) count++;
    if (props.filters.date_from) count++;
    if (props.filters.date_to) count++;
    return count;
});

const updateFilters = (newFilters: any) => {
    router.get(
        dealer.dashboard.url({
            query: {
                page: 1,
                per_page: props.ads.per_page,
                sort: currentSort.value,
                ...props.filters,
                ...newFilters,
            },
        }),
        {},
        { preserveState: true, preserveScroll: true, replace: true },
    );
};

const debouncedSearch = useDebounceFn((search: string) => {
    updateFilters({ search });
}, 500);

const resetFilters = () => {
    router.get(
        dealer.dashboard.url({
            query: {
                page: 1,
                per_page: props.ads.per_page,
                sort: 'latest',
            },
        }),
        {},
        { preserveState: true, preserveScroll: true, replace: true },
    );
};

const handleSort = (column: string) => {
    const isStat = ['views', 'contacts', 'favs'].includes(column);

    if (isStat) {
        if (currentSort.value === column + '_desc') {
            currentSort.value = 'latest';
        } else {
            currentSort.value = column + '_desc';
        }
    } else {
        if (currentSort.value === column + '_asc') {
            currentSort.value = column + '_desc';
        } else if (currentSort.value === column + '_desc') {
            currentSort.value = 'latest';
        } else {
            currentSort.value = column + '_asc';
        }
    }

    updateFilters({ sort: currentSort.value });
};

const handleStatusChange = (ad: any, checked: boolean) => {
    const newStatus = checked ? 'active' : 'draft';
    router.patch(
        vehicles.update_status.url(ad.id),
        { status: newStatus },
        {
            preserveScroll: true,
            onError: (err) => console.error('Error updating status:', err),
        },
    );
};

const handlePageChange = (p: number) => {
    router.get(
        dealer.dashboard.url({
            query: {
                ...props.filters,
                page: p,
                per_page: props.ads.per_page,
            },
        }),
        {},
        { preserveState: true },
    );
};

const handlePerPageChange = (pp: string) => {
    router.get(
        dealer.dashboard.url({
            query: {
                ...props.filters,
                page: 1,
                per_page: pp,
            },
        }),
        {},
        { preserveState: true },
    );
};
</script>

<template>
    <Card>
        <CardHeader class="border-b border-border/50 pb-3">
            <div class="flex items-center justify-between">
                <div>
                    <CardTitle>Liste des véhicules</CardTitle>
                    <CardDescription>
                        Gérez vos annonces et surveillez leurs performances
                    </CardDescription>
                </div>
                <VehiclesSearchHeader
                    :search="filters.search || ''"
                    :show-filters="showFilters"
                    :active-filters-count="activeFiltersCount"
                    @update:search="debouncedSearch"
                    @update:show-filters="(val) => (showFilters = val)"
                />
            </div>
        </CardHeader>
        <CardContent class="p-0">
            <VehiclesFilterPanel
                :open="showFilters"
                :filters="filters"
                :brands="brands"
                :models="models"
                @update:filters="updateFilters"
                @reset="resetFilters"
            />

            <VehiclesTable
                :ads="ads"
                :current-sort="currentSort"
                @sort="handleSort"
                @status-change="handleStatusChange"
            />

            <AppPagination
                v-if="ads"
                :pagination="ads"
                :per-page="ads.per_page"
                resource-label="véhicules"
                @update:page="handlePageChange"
                @update:per-page="handlePerPageChange"
                class="border-t border-border/50 bg-transparent shadow-none"
            />
        </CardContent>
    </Card>
</template>
