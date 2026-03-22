<template>
    <Head :title="__('dealer.dashboard_title')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4 md:p-6"
        >
            <div
                class="mb-6 flex flex-col gap-6 md:flex-row md:items-end md:justify-between"
            >
                <div>
                    <h1
                        class="text-2xl font-extrabold tracking-tight md:text-4xl lg:text-5xl"
                    >
                        {{ __('dealer.my_stock') }}
                    </h1>
                    <p class="mt-2 text-muted-foreground">
                        {{ __('dealer.manage_ads_description') }}
                    </p>
                </div>
                <div class="flex flex-wrap items-center gap-2 print:hidden">
                    <Button
                        variant="outline"
                        size="icon"
                        class="group h-10 w-10 border-border/60 bg-background/50 backdrop-blur-sm transition-all hover:cursor-pointer hover:bg-background hover:shadow-sm md:w-auto md:px-4"
                        @click="exportToCSV"
                        :title="__('dealer.save_csv')"
                    >
                        <Download
                            class="h-4 w-4 text-muted-foreground group-hover:text-primary md:mr-2"
                        />
                        <span class="hidden md:inline">{{
                            __('dealer.save_csv')
                        }}</span>
                    </Button>
                    <Button
                        variant="outline"
                        size="icon"
                        class="group h-10 w-10 border-border/60 bg-background/50 backdrop-blur-sm transition-all hover:cursor-pointer hover:bg-background hover:shadow-sm md:w-auto md:px-4"
                        @click="printList"
                        :title="__('dealer.print_list')"
                    >
                        <Printer
                            class="h-4 w-4 text-muted-foreground group-hover:text-primary md:mr-2"
                        />
                        <span class="hidden md:inline">{{
                            __('dealer.print_list')
                        }}</span>
                    </Button>
                    <Button
                        as-child
                        size="default"
                        class="h-10 flex-1 bg-primary px-6 font-semibold shadow-md transition-all active:scale-95 md:flex-none"
                    >
                        <Link :href="vehicles.create().url">
                            <Plus class="mr-2 h-5 w-5" />
                            {{ __('dealer.create_ad') }}
                        </Link>
                    </Button>
                </div>
            </div>

            <div class="mb-8 grid grid-cols-2 gap-4 lg:grid-cols-3">
                <!-- Total Ads -->
                <Card
                    class="group relative overflow-hidden rounded-2xl border border-border/50 bg-card shadow-xl ring-1 ring-border/50 transition-all duration-500"
                >
                    <div
                        class="absolute inset-x-0 top-0 h-1 bg-primary/20 transition-all duration-500 group-hover:h-1.5 group-hover:bg-primary"
                    ></div>
                    <CardHeader
                        class="flex flex-row items-center justify-between p-4 pb-2 md:p-6 md:pb-2"
                    >
                        <CardTitle
                            class="font-heading text-[10px] font-bold tracking-widest text-muted-foreground uppercase"
                        >
                            {{ __('dealer.total') }}
                        </CardTitle>
                        <div
                            class="flex h-8 w-8 items-center justify-center rounded-lg bg-background shadow-xs transition-all duration-500 group-hover:scale-110 group-hover:bg-primary group-hover:text-white"
                        >
                            <CarFront class="h-4 w-4" />
                        </div>
                    </CardHeader>
                    <CardContent class="p-4 pt-0 md:p-6 md:pt-0">
                        <div
                            class="text-3xl font-bold tracking-tight md:text-4xl"
                        >
                            {{ stats.total }}
                        </div>
                        <p
                            class="mt-1 text-[10px] font-bold tracking-widest text-muted-foreground/60 uppercase"
                        >
                            {{ __('dealer.vehicles_label') }}
                        </p>
                    </CardContent>
                </Card>

                <!-- Active Ads -->
                <Card
                    class="group relative overflow-hidden rounded-2xl border border-border/50 bg-card shadow-xl ring-1 ring-border/50 transition-all duration-500"
                >
                    <div
                        class="absolute inset-x-0 top-0 h-1 bg-green-500/20 transition-all duration-500 group-hover:h-1.5 group-hover:bg-green-500"
                    ></div>
                    <CardHeader
                        class="flex flex-row items-center justify-between p-4 pb-2 md:p-6 md:pb-2"
                    >
                        <CardTitle
                            class="font-heading text-[10px] font-bold tracking-widest text-muted-foreground uppercase"
                        >
                            {{ __('dealer.active') }}
                        </CardTitle>
                        <div
                            class="flex h-8 w-8 items-center justify-center rounded-lg bg-background shadow-xs transition-all duration-500 group-hover:scale-110 group-hover:bg-green-500 group-hover:text-white"
                        >
                            <CheckCircle class="h-4 w-4" />
                        </div>
                    </CardHeader>
                    <CardContent class="p-4 pt-0 md:p-6 md:pt-0">
                        <div
                            class="text-3xl font-bold tracking-tight text-green-600 md:text-4xl dark:text-green-400"
                        >
                            {{ stats.active }}
                        </div>
                        <p
                            class="mt-1 text-[10px] font-bold tracking-widest text-muted-foreground/60 uppercase"
                        >
                            {{
                                __('dealer.active_status_description') ||
                                __('dealer.active')
                            }}
                        </p>
                    </CardContent>
                </Card>

                <!-- Draft Ads -->
                <Card
                    class="group relative col-span-2 overflow-hidden rounded-2xl border border-border/50 bg-card shadow-xl ring-1 ring-border/50 transition-all duration-500 lg:col-span-1"
                >
                    <div
                        class="absolute inset-x-0 top-0 h-1 bg-amber-500/20 transition-all duration-500 group-hover:h-1.5 group-hover:bg-amber-500"
                    ></div>
                    <CardHeader
                        class="flex flex-row items-center justify-between p-4 pb-2 md:p-6 md:pb-2"
                    >
                        <CardTitle
                            class="font-heading text-[10px] font-bold tracking-widest text-muted-foreground uppercase"
                        >
                            {{ __('dealer.draft') }}
                        </CardTitle>
                        <div
                            class="flex h-8 w-8 items-center justify-center rounded-lg bg-background shadow-xs transition-all duration-500 group-hover:scale-110 group-hover:bg-amber-500 group-hover:text-white"
                        >
                            <Clock class="h-4 w-4" />
                        </div>
                    </CardHeader>
                    <CardContent class="p-4 pt-0 md:p-6 md:pt-0">
                        <div
                            class="text-3xl font-bold tracking-tight text-amber-600 md:text-4xl dark:text-amber-400"
                        >
                            {{ stats.draft }}
                        </div>
                        <p
                            class="mt-1 text-[10px] font-bold tracking-widest text-muted-foreground/60 uppercase"
                        >
                            {{
                                __('dealer.draft_status_description') ||
                                __('dealer.draft')
                            }}
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
                        v-model:open="showFilters"
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
import {
    Download,
    Plus,
    Printer,
    Clock,
    CarFront,
    CheckCircle,
} from 'lucide-vue-next';
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
    if (props.filters.search) count++;
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
    router.post(
        vehicles.update_status.url(ad.id),
        { _method: 'patch', status: newStatus },
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
