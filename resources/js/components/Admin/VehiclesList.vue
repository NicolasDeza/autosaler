<template>
    <Card
        class="overflow-hidden border-border/50 bg-background/60 shadow-xl backdrop-blur-md"
    >
        <CardHeader class="border-b border-border/50 bg-muted/20 pb-6">
            <div class="flex flex-col gap-6">
                <div class="space-y-1">
                    <CardTitle class="font-heading text-2xl tracking-tight">{{
                        __('admin.tab_vehicles')
                    }}</CardTitle>
                    <CardDescription class="font-body text-sm">{{
                        __('admin.vehicles_description')
                    }}</CardDescription>
                </div>
                <div class="group relative w-full sm:w-80">
                    <Search
                        class="absolute top-1/2 left-3 h-4 w-4 -translate-y-1/2 text-muted-foreground transition-colors group-focus-within:text-primary"
                    />
                    <Input
                        v-model="search"
                        :placeholder="__('admin.search_placeholder')"
                        class="h-11 border-border/40 bg-background/50 pl-10 focus-visible:ring-primary/20"
                        @input="debouncedSearch"
                    />
                </div>
            </div>
        </CardHeader>

        <CardContent class="p-0">
            <!-- Admin Filter Panel -->
            <div class="border-b border-border/50 bg-muted/10 p-6">
                <div class="grid grid-cols-1 items-end gap-6 md:grid-cols-4">
                    <div class="space-y-2.5">
                        <Label
                            class="font-heading ml-1 text-[10px] leading-none font-bold tracking-widest text-muted-foreground/80 uppercase"
                            >{{ __('admin.filter_company') }}</Label
                        >
                        <Select
                            v-model="companyId"
                            @update:model-value="updateFilters"
                        >
                            <SelectTrigger
                                class="font-heading h-11 border-border/40 bg-background/50 text-xs font-bold transition-all hover:border-primary/30"
                            >
                                <SelectValue
                                    :placeholder="__('admin.all_companies')"
                                />
                            </SelectTrigger>
                            <SelectContent class="font-body">
                                <SelectItem value="all">{{
                                    __('admin.all_companies')
                                }}</SelectItem>
                                <SelectItem
                                    v-for="company in companies"
                                    :key="company.id"
                                    :value="company.id.toString()"
                                >
                                    {{ company.name }}
                                </SelectItem>
                            </SelectContent>
                        </Select>
                    </div>

                    <div class="space-y-2.5">
                        <Label
                            class="font-heading ml-1 text-[10px] leading-none font-bold tracking-widest text-muted-foreground/80 uppercase"
                            >{{ __('admin.filter_brand') }}</Label
                        >
                        <Select
                            v-model="brandId"
                            @update:model-value="updateFilters"
                        >
                            <SelectTrigger
                                class="font-heading h-11 border-border/40 bg-background/50 text-xs font-bold transition-all hover:border-primary/30"
                            >
                                <SelectValue
                                    :placeholder="__('admin.all_brands')"
                                />
                            </SelectTrigger>
                            <SelectContent class="font-body">
                                <SelectItem value="all">{{
                                    __('admin.all_brands')
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

                    <div class="space-y-2.5">
                        <Label
                            class="font-heading ml-1 text-[10px] leading-none font-bold tracking-widest text-muted-foreground/80 uppercase"
                            >{{ __('admin.filter_status') }}</Label
                        >
                        <Select
                            v-model="status"
                            @update:model-value="updateFilters"
                        >
                            <SelectTrigger
                                class="font-heading h-11 border-border/40 bg-background/50 text-xs font-bold transition-all hover:border-primary/30"
                            >
                                <SelectValue
                                    :placeholder="__('admin.all_statuses')"
                                />
                            </SelectTrigger>
                            <SelectContent class="font-body">
                                <SelectItem value="all">{{
                                    __('admin.all_statuses')
                                }}</SelectItem>
                                <SelectItem value="active">{{
                                    __('admin.status_active')
                                }}</SelectItem>
                                <SelectItem value="draft">{{
                                    __('admin.status_draft')
                                }}</SelectItem>
                                <SelectItem value="pending">{{
                                    __('admin.status_pending')
                                }}</SelectItem>
                            </SelectContent>
                        </Select>
                    </div>

                    <div class="flex">
                        <Button
                            variant="outline"
                            class="font-heading h-11 w-full gap-2 border-border/40 text-[10px] font-bold tracking-widest uppercase shadow-sm transition-all hover:bg-muted"
                            @click="resetFilters"
                        >
                            <RotateCcw class="h-4 w-4" />
                            {{ __('admin.reset_filters') }}
                        </Button>
                    </div>
                </div>
            </div>

            <!-- Vehicles Table (Shared component - cautious styling) -->
            <div class="overflow-x-auto bg-background/30">
                <VehiclesTable
                    :ads="ads"
                    :current-sort="currentSort"
                    @sort="handleSort"
                />
            </div>

            <AppPagination
                v-if="ads"
                :pagination="ads"
                :per-page="ads.per_page"
                :resource-label="__('admin.vehicles_label')"
                @update:page="handlePageChange"
                @update:per-page="handlePerPageChange"
                class="border-t border-border/50 bg-muted/10 px-4 py-3"
            />
        </CardContent>
    </Card>
</template>

<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { useDebounceFn } from '@vueuse/core';
import { Search, RotateCcw } from 'lucide-vue-next';
import { ref } from 'vue';
import { index as adminDashboardIndex } from '@/actions/App/Http/Controllers/Admin/AdminDashboardController';
import AppPagination from '@/components/AppPagination.vue';
import VehiclesTable from '@/components/dealer/vehicles/VehiclesTable.vue';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
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

const props = defineProps<{
    ads: any;
    filters: any;
    companies: any[];
    brands: any[];
    models: any[];
}>();

const { __ } = useTranslation();

const search = ref(props.filters.search || '');
const companyId = ref(props.filters.company_id?.toString() || 'all');
const brandId = ref(props.filters.brand_id?.toString() || 'all');
const status = ref(props.filters.status || 'all');
const currentSort = ref(props.filters.sort || 'latest');

const updateFilters = () => {
    router.get(
        adminDashboardIndex.url({
            query: {
                tab: 'vehicles',
                search: search.value,
                company_id:
                    companyId.value === 'all' ? undefined : companyId.value,
                brand_id: brandId.value === 'all' ? undefined : brandId.value,
                status: status.value === 'all' ? undefined : status.value,
                sort: currentSort.value,
                per_page: props.ads?.per_page,
            },
        }),
        {},
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        },
    );
};

const debouncedSearch = useDebounceFn(updateFilters, 500);

const handleSort = (column: string) => {
    if (currentSort.value === column + '_asc') {
        currentSort.value = column + '_desc';
    } else if (currentSort.value === column + '_desc') {
        currentSort.value = 'latest';
    } else {
        currentSort.value = column + '_asc';
    }
    updateFilters();
};

const resetFilters = () => {
    search.value = '';
    companyId.value = 'all';
    brandId.value = 'all';
    status.value = 'all';
    currentSort.value = 'latest';
    updateFilters();
};

const handlePageChange = (page: number) => {
    router.get(
        adminDashboardIndex.url({
            query: {
                ...props.filters,
                tab: 'vehicles',
                page: page,
            },
        }),
        {},
        { preserveState: true },
    );
};

const handlePerPageChange = (pp: string) => {
    router.get(
        adminDashboardIndex.url({
            query: {
                ...props.filters,
                tab: 'vehicles',
                per_page: pp,
                page: 1,
            },
        }),
        {},
        { preserveState: true },
    );
};
</script>
