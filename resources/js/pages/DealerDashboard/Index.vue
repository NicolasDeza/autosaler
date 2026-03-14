<template>
    <Head :title="__('dealer.dashboard_title')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-6"
        >
            <div class="mb-6 flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
                <div>
                    <h1 class="text-4xl font-extrabold tracking-tight lg:text-5xl">
                        {{ __('dealer.my_stock') }}
                    </h1>
                    <p class="mt-2 text-muted-foreground">
                        {{ __('dealer.manage_ads_description') }}
                    </p>
                </div>
                <div class="flex flex-wrap items-center gap-3 print:hidden">
                    <Button
                        variant="outline"
                        size="default"
                        class="h-10 border-border/60 bg-background/50 backdrop-blur-sm transition-all hover:bg-background hover:shadow-sm"
                        @click="exportToCSV"
                    >
                        <Download class="mr-2 h-4 w-4 text-muted-foreground" />
                        {{ __('dealer.save_csv') }}
                    </Button>
                    <Button
                        variant="outline"
                        size="default"
                        class="h-10 border-border/60 bg-background/50 backdrop-blur-sm transition-all hover:bg-background hover:shadow-sm"
                        @click="printList"
                    >
                        <Printer class="mr-2 h-4 w-4 text-muted-foreground" />
                        {{ __('dealer.print_list') }}
                    </Button>
                    <Button
                        as-child
                        size="default"
                        class="h-10 bg-primary px-6 font-semibold shadow-md transition-all hover:shadow-lg hover:shadow-primary/20 active:scale-95"
                    >
                        <Link :href="vehicles.create().url">
                            <Plus class="mr-2 h-5 w-5" />
                            {{ __('dealer.create_ad') }}
                        </Link>
                    </Button>
                </div>
            </div>

            <div class="mb-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <Card
                    class="relative overflow-hidden border-none bg-gradient-to-br from-background to-muted/30 shadow-sm"
                >
                    <CardHeader class="flex flex-row items-center justify-between pb-2">
                        <CardTitle class="text-sm font-medium">
                            {{ __('dealer.total') }}
                        </CardTitle>
                        <div
                            class="flex h-8 w-8 items-center justify-center rounded-full bg-primary/10 text-primary"
                        >
                            <span class="text-xs font-bold">#</span>
                        </div>
                    </CardHeader>
                    <CardContent>
                        <div class="text-3xl font-bold">{{ stats.total }}</div>
                        <p class="mt-1 text-xs text-muted-foreground">
                            {{ __('dealer.vehicles_label') }}
                        </p>
                    </CardContent>
                </Card>

                <Card
                    class="relative overflow-hidden border-none bg-gradient-to-br from-background to-green-500/5 shadow-sm"
                >
                    <CardHeader class="flex flex-row items-center justify-between pb-2">
                        <CardTitle class="text-sm font-medium">
                            {{ __('dealer.active') }}
                        </CardTitle>
                        <div
                            class="flex h-8 w-8 items-center justify-center rounded-full bg-green-500/10 text-green-600 dark:text-green-400"
                        >
                            <div class="h-2 w-2 rounded-full bg-current"></div>
                        </div>
                    </CardHeader>
                    <CardContent>
                        <div
                            class="text-3xl font-bold text-green-600 dark:text-green-400"
                        >
                            {{ stats.active }}
                        </div>
                        <p class="mt-1 text-xs text-muted-foreground">
                            {{ __('dealer.active_status_description') || __('dealer.active') }}
                        </p>
                    </CardContent>
                </Card>

                <Card
                    class="relative overflow-hidden border-none bg-gradient-to-br from-background to-amber-500/5 shadow-sm sm:col-span-2 lg:col-span-1"
                >
                    <CardHeader class="flex flex-row items-center justify-between pb-2">
                        <CardTitle class="text-sm font-medium">
                            {{ __('dealer.draft') }}
                        </CardTitle>
                        <div
                            class="flex h-8 w-8 items-center justify-center rounded-full bg-amber-500/10 text-amber-600 dark:text-amber-400"
                        >
                            <Clock class="h-4 w-4" />
                        </div>
                    </CardHeader>
                    <CardContent>
                        <div
                            class="text-3xl font-bold text-amber-600 dark:text-amber-400"
                        >
                            {{ stats.draft }}
                        </div>
                        <p class="mt-1 text-xs text-muted-foreground">
                            {{ __('dealer.draft_status_description') || __('dealer.draft') }}
                        </p>
                    </CardContent>
                </Card>
            </div>

            <Card class="gap-0 pb-0 print:border-none print:shadow-none">
                <CardHeader class="border-b border-border/50 pb-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <CardTitle>{{
                                __('dealer.vehicle_list')
                            }}</CardTitle>
                            <CardDescription class="print:hidden">
                                {{ __('dealer.manage_ads_description') }}
                            </CardDescription>
                        </div>
                        <VehiclesSearchHeader
                            class="print:hidden"
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
                        class="print:hidden"
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
                        :resource-label="__('dealer.vehicles_label')"
                        @update:page="handlePageChange"
                        @update:per-page="handlePerPageChange"
                        class="rounded-t-none border-t border-border/50 bg-transparent shadow-none print:hidden"
                    />
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { useDebounceFn } from '@vueuse/core';
import { Download, Plus, Printer, Clock } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import AppPagination from '@/components/AppPagination.vue';
import VehiclesFilterPanel from '@/components/dealer/vehicles/VehiclesFilterPanel.vue';
import VehiclesSearchHeader from '@/components/dealer/vehicles/VehiclesSearchHeader.vue';
import VehiclesTable from '@/components/dealer/vehicles/VehiclesTable.vue';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { useTranslation } from '@/composables/useTranslation';
import AppLayout from '@/layouts/AppLayout.vue';
import dealer from '@/routes/dealer';
import vehicles from '@/routes/vehicles';
import type { BreadcrumbItem } from '@/types';

interface Props {
    ads: any;
    stats: {
        total: number;
        active: number;
        draft: number;
    };
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

const { __ } = useTranslation();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: __('dealer.dashboard_title'),
        href: dealer.dashboard().url,
    },
];

const currentSort = ref(
    typeof props.filters.sort === 'string' ? props.filters.sort : 'latest',
);
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

const exportToCSV = () => {
    if (!props.ads?.data?.length) return;

    const escapeCSV = (val: any) => {
        if (val === null || val === undefined) return '';
        const str = String(val);
        if (str.includes(',') || str.includes('"') || str.includes('\n')) {
            return `"${str.replace(/"/g, '""')}"`;
        }
        return str;
    };

    const headers = [
        'ID',
        __('dealer.from_date'),
        __('dealer.brand'),
        __('dealer.model'),
        'Version',
        __('dealer.price'),
        __('dealer.views'),
        __('dealer.contacts'),
        __('dealer.favorites'),
        __('dealer.status'),
    ];

    const rows = props.ads.data.map((ad: any) => [
        ad.id,
        ad.created_at ? new Date(ad.created_at).toLocaleDateString() : 'N/A',
        escapeCSV(ad.brand?.name),
        escapeCSV(ad.model?.name),
        escapeCSV(ad.vehicle_version_name),
        ad.price,
        ad.stat?.views_count || 0,
        ad.stat?.contact_count || 0,
        ad.stat?.fav_count || 0,
        ad.status,
    ]);

    const csvContent = [
        '\uFEFF' + headers.join(','),
        ...rows.map((row: any[]) => row.join(',')),
    ].join('\n');

    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const url = URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.setAttribute('href', url);
    link.setAttribute(
        'download',
        `${__('dealer.csv_filename')}_${new Date().toISOString().split('T')[0]}.csv`,
    );
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
};

const printList = () => {
    window.print();
};
</script>

<style>
@media print {
    /* Indispensable pour régler les marges du papier */
    @page {
        margin: 1cm;
        size: auto;
    }
    /* Force le noir sur blanc pour économiser l'encre et garantir la lisibilité */
    body {
        background: white !important;
        color: black !important;
    }
    /* Sécurité pour tous les textes importants */
    h1,
    h2,
    h3,
    p,
    span,
    td,
    th {
        color: black !important;
    }
}
</style>
