<script setup lang="ts">
import { router, Link } from '@inertiajs/vue3';
import { useDebounceFn } from '@vueuse/core';
import {
    MoreHorizontal,
    Eye,
    Heart,
    MessageCircle,
    Clock,
    Image as ImageIcon,
    Search,
    ArrowUpDown,
    ArrowUp,
    ArrowDown,
    Filter,
    RotateCcw,
} from 'lucide-vue-next';
import { ref, watch } from 'vue';
import AppPagination from '@/components/AppPagination.vue';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { Checkbox } from '@/components/ui/checkbox';
import { Collapsible, CollapsibleContent } from '@/components/ui/collapsible';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Switch } from '@/components/ui/switch';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import dealer_dashboard from '@/routes/dealer_dashboard';
import {
    show as vehicleShow,
    edit as vehicleEdit,
    destroy as vehicleDestroy,
    update_status as vehicleUpdateStatus,
} from '@/routes/vehicles';

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

const searchQuery = ref(props.filters.search || '');
const currentSort = ref(props.filters.sort || 'latest');
const brandFilter = ref(props.filters.brand_id?.toString() || 'all');
const modelFilter = ref(props.filters.model_id?.toString() || 'all');
const statusFilter = ref(props.filters.status || 'all');
const dateFrom = ref(props.filters.date_from || '');
const dateTo = ref(props.filters.date_to || '');

const showFilters = ref(
    brandFilter.value !== 'all' ||
        modelFilter.value !== 'all' ||
        statusFilter.value !== 'all' ||
        dateFrom.value !== '' ||
        dateTo.value !== '',
);

const updateFilters = () => {
    router.get(
        dealer_dashboard.index.url({
            query: {
                page: 1,
                per_page: props.ads.per_page,
                search: searchQuery.value,
                sort: currentSort.value,
                brand_id:
                    brandFilter.value === 'all' ? undefined : brandFilter.value,
                model_id:
                    modelFilter.value === 'all' ? undefined : modelFilter.value,
                status:
                    statusFilter.value === 'all'
                        ? undefined
                        : statusFilter.value,
                date_from: dateFrom.value || undefined,
                date_to: dateTo.value || undefined,
            },
        }),
        {},
        { preserveState: true, preserveScroll: true, replace: true },
    );
};

const resetFilters = () => {
    searchQuery.value = '';
    brandFilter.value = 'all';
    modelFilter.value = 'all';
    statusFilter.value = 'all';
    dateFrom.value = '';
    dateTo.value = '';
    updateFilters();
};

watch(brandFilter, () => {
    modelFilter.value = 'all';
});

const debouncedSearch = useDebounceFn(() => {
    updateFilters();
}, 500);

watch(searchQuery, () => {
    debouncedSearch();
});

const toggleSort = (column: string) => {
    // For stats, we usually only want descending or reset
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
    updateFilters();
};

const getSortIcon = (column: string) => {
    if (currentSort.value === column + '_asc') {
        return ArrowUp;
    }
    if (currentSort.value === column + '_desc') {
        return ArrowDown;
    }
    return ArrowUpDown;
};

const handlePageChange = (p: number) => {
    router.get(
        dealer_dashboard.index.url({
            query: {
                page: p,
                per_page: props.ads.per_page,
                search: searchQuery.value,
            },
        }),
        {},
        { preserveState: true },
    );
};

const handlePerPageChange = (pp: string) => {
    router.get(
        dealer_dashboard.index.url({
            query: {
                page: 1,
                per_page: pp,
                search: searchQuery.value,
            },
        }),
        {},
        { preserveState: true },
    );
};

// Format currency
const formatPrice = (price: number) => {
    return new Intl.NumberFormat('fr-FR', {
        style: 'currency',
        currency: 'EUR',
    }).format(price);
};

const toggleStatus = (ad: any, checked: boolean) => {
    const newStatus = checked ? 'active' : 'draft';
    router.patch(
        vehicleUpdateStatus.url(ad.id),
        { status: newStatus },
        {
            preserveScroll: true,
            onError: (err) => console.error('Error updating status:', err),
        },
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
                <div class="flex items-center gap-2">
                    <div class="relative w-full max-w-sm items-center">
                        <Input
                            id="search"
                            type="text"
                            placeholder="Rechercher..."
                            class="h-9 pl-10"
                            v-model="searchQuery"
                        />
                        <span
                            class="absolute inset-y-0 inset-s-0 flex items-center justify-center px-3"
                        >
                            <Search class="h-4 w-4 text-muted-foreground" />
                        </span>
                    </div>
                    <Button
                        variant="outline"
                        size="sm"
                        @click="showFilters = !showFilters"
                        :class="{ 'bg-muted': showFilters }"
                    >
                        <Filter class="mr-2 h-4 w-4" />
                        Filtres
                        <template
                            v-if="
                                brandFilter !== 'all' ||
                                modelFilter !== 'all' ||
                                statusFilter !== 'all' ||
                                dateFrom !== '' ||
                                dateTo !== ''
                            "
                        >
                            <span
                                class="ml-2 flex h-4 w-4 items-center justify-center rounded-full bg-primary text-[10px] text-primary-foreground"
                            >
                                {{
                                    (brandFilter !== 'all' ? 1 : 0) +
                                    (modelFilter !== 'all' ? 1 : 0) +
                                    (statusFilter !== 'all' ? 1 : 0) +
                                    (dateFrom !== '' ? 1 : 0) +
                                    (dateTo !== '' ? 1 : 0)
                                }}
                            </span>
                        </template>
                    </Button>
                </div>
            </div>
        </CardHeader>
        <CardContent class="p-0">
            <Collapsible v-model:open="showFilters" class="w-full">
                <CollapsibleContent>
                    <div
                        class="grid grid-cols-1 gap-4 border-b border-border/50 bg-muted/30 p-4 md:grid-cols-3 lg:grid-cols-6"
                    >
                        <div class="space-y-1.5">
                            <Label class="text-xs">Marque</Label>
                            <Select
                                v-model="brandFilter"
                                @update:model-value="updateFilters"
                            >
                                <SelectTrigger class="h-8 text-xs">
                                    <SelectValue
                                        placeholder="Toutes les marques"
                                    />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="all"
                                        >Toutes les marques</SelectItem
                                    >
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
                        <div class="space-y-1.5">
                            <Label class="text-xs">Modèle</Label>
                            <Select
                                v-model="modelFilter"
                                @update:model-value="updateFilters"
                            >
                                <SelectTrigger class="h-8 text-xs">
                                    <SelectValue
                                        placeholder="Tous les modèles"
                                    />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="all"
                                        >Tous les modèles</SelectItem
                                    >
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
                        <div class="space-y-1.5">
                            <Label class="text-xs">Statut</Label>
                            <Select
                                v-model="statusFilter"
                                @update:model-value="updateFilters"
                            >
                                <SelectTrigger class="h-8 text-xs">
                                    <SelectValue
                                        placeholder="Tous les statuts"
                                    />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="all"
                                        >Tous les statuts</SelectItem
                                    >
                                    <SelectItem value="active"
                                        >Actif</SelectItem
                                    >
                                    <SelectItem value="draft"
                                        >Brouillon</SelectItem
                                    >
                                    <SelectItem value="sold">Vendu</SelectItem>
                                </SelectContent>
                            </Select>
                        </div>
                        <div class="space-y-1.5">
                            <Label class="text-xs">Date du</Label>
                            <Input
                                type="date"
                                v-model="dateFrom"
                                class="h-8 text-xs"
                                @change="updateFilters"
                            />
                        </div>
                        <div class="space-y-1.5">
                            <Label class="text-xs">Au</Label>
                            <Input
                                type="date"
                                v-model="dateTo"
                                class="h-8 text-xs"
                                @change="updateFilters"
                            />
                        </div>
                        <div class="flex items-end gap-2">
                            <Button
                                variant="ghost"
                                size="sm"
                                class="h-8 w-full justify-start px-2 font-normal text-muted-foreground hover:text-foreground"
                                @click="resetFilters"
                            >
                                <RotateCcw class="mr-2 h-3.5 w-3.5" />
                                Réinitialiser
                            </Button>
                        </div>
                    </div>
                </CollapsibleContent>
            </Collapsible>
            <div class="overflow-x-auto">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead class="w-[50px]"><Checkbox /></TableHead>
                            <TableHead
                                class="w-[150px] cursor-pointer hover:bg-muted/50"
                                @click="toggleSort('id')"
                            >
                                <div class="flex items-center gap-2">
                                    Numéro de référence
                                    <component
                                        :is="getSortIcon('id')"
                                        class="h-4 w-4"
                                    />
                                </div>
                            </TableHead>
                            <TableHead>Véhicule</TableHead>
                            <TableHead
                                class="cursor-pointer hover:bg-muted/50"
                                @click="toggleSort('price')"
                            >
                                <div class="flex items-center gap-2">
                                    Prix
                                    <component
                                        :is="getSortIcon('price')"
                                        class="h-4 w-4"
                                    />
                                </div>
                            </TableHead>
                            <TableHead
                                class="cursor-pointer text-center hover:bg-muted/50"
                                @click="toggleSort('views')"
                            >
                                <div
                                    class="flex items-center justify-center gap-2"
                                >
                                    <Eye class="h-4 w-4" />
                                    <component
                                        :is="getSortIcon('views', true)"
                                        class="h-4 w-4"
                                    />
                                </div>
                            </TableHead>
                            <TableHead
                                class="cursor-pointer text-center hover:bg-muted/50"
                                @click="toggleSort('contacts')"
                            >
                                <div
                                    class="flex items-center justify-center gap-2"
                                >
                                    <MessageCircle class="h-4 w-4" />
                                    <component
                                        :is="getSortIcon('contacts', true)"
                                        class="h-4 w-4"
                                    />
                                </div>
                            </TableHead>
                            <TableHead
                                class="cursor-pointer text-center hover:bg-muted/50"
                                @click="toggleSort('favs')"
                            >
                                <div
                                    class="flex items-center justify-center gap-2"
                                >
                                    <Heart class="h-4 w-4" />
                                    <component
                                        :is="getSortIcon('favs', true)"
                                        class="h-4 w-4"
                                    />
                                </div>
                            </TableHead>
                            <TableHead
                                class="w-[100px] cursor-pointer hover:bg-muted/50"
                                @click="toggleSort('latest')"
                            >
                                <div class="flex items-center gap-2">
                                    Statut
                                    <component
                                        :is="
                                            currentSort === 'latest' ||
                                            currentSort === 'oldest'
                                                ? getSortIcon(
                                                      currentSort === 'latest'
                                                          ? 'latest'
                                                          : 'oldest',
                                                  )
                                                : ArrowUpDown
                                        "
                                        class="h-4 w-4"
                                    />
                                </div>
                            </TableHead>
                            <TableHead class="w-[50px]"></TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <template v-if="ads && ads.data && ads.data.length > 0">
                            <TableRow v-for="ad in ads.data" :key="ad.id">
                                <TableCell><Checkbox /></TableCell>
                                <TableCell>
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="flex h-10 w-12 items-center justify-center rounded bg-muted"
                                        >
                                            <ImageIcon
                                                class="h-5 w-5 text-muted-foreground"
                                            />
                                        </div>
                                        <div>
                                            <div class="font-medium">
                                                #{{ ad.id }}
                                            </div>
                                            <div
                                                class="flex items-center gap-1 text-xs text-muted-foreground"
                                            >
                                                <Clock class="h-3 w-3" />
                                                {{
                                                    ad.created_at
                                                        ? new Date(
                                                              ad.created_at,
                                                          ).toLocaleDateString()
                                                        : 'N/A'
                                                }}
                                            </div>
                                        </div>
                                    </div>
                                </TableCell>
                                <TableCell>
                                    <div class="font-medium">
                                        {{ ad.brand?.name }}
                                        {{ ad.model?.name }}
                                    </div>
                                    <div
                                        class="line-clamp-1 text-xs text-muted-foreground"
                                    >
                                        {{ ad.vehicle_version_name }}
                                    </div>
                                </TableCell>
                                <TableCell>
                                    <div
                                        class="font-medium text-green-600 dark:text-green-500"
                                    >
                                        {{ formatPrice(ad.price) }}
                                    </div>
                                </TableCell>
                                <TableCell
                                    class="text-center text-muted-foreground"
                                >
                                    {{ ad.stat?.views_count || '-' }}
                                </TableCell>
                                <TableCell
                                    class="text-center text-muted-foreground"
                                >
                                    {{ ad.stat?.contact_count || '-' }}
                                </TableCell>
                                <TableCell
                                    class="text-center text-muted-foreground"
                                >
                                    {{ ad.stat?.fav_count || '-' }}
                                </TableCell>
                                <TableCell>
                                    <div class="flex items-center gap-2">
                                        <Switch
                                            :model-value="
                                                ad.status === 'active'
                                            "
                                            @update:model-value="
                                                (checked: boolean) =>
                                                    toggleStatus(ad, checked)
                                            "
                                        />
                                    </div>
                                </TableCell>
                                <TableCell>
                                    <DropdownMenu>
                                        <DropdownMenuTrigger asChild>
                                            <Button
                                                variant="ghost"
                                                size="icon"
                                                class="h-8 w-8 p-0"
                                            >
                                                <span class="sr-only"
                                                    >Ouvrir le menu</span
                                                >
                                                <MoreHorizontal
                                                    class="h-4 w-4"
                                                />
                                            </Button>
                                        </DropdownMenuTrigger>
                                        <DropdownMenuContent align="end">
                                            <DropdownMenuLabel
                                                >Actions</DropdownMenuLabel
                                            >
                                            <DropdownMenuItem asChild>
                                                <Link
                                                    :href="
                                                        vehicleShow.url(ad.id)
                                                    "
                                                    class="w-full cursor-pointer"
                                                >
                                                    Voir l'annonce
                                                </Link>
                                            </DropdownMenuItem>
                                            <DropdownMenuItem asChild>
                                                <Link
                                                    :href="
                                                        vehicleEdit.url(ad.id)
                                                    "
                                                    class="w-full cursor-pointer"
                                                >
                                                    Modifier
                                                </Link>
                                            </DropdownMenuItem>
                                            <DropdownMenuItem
                                                asChild
                                                class="text-destructive"
                                            >
                                                <Link
                                                    :href="
                                                        vehicleDestroy.url(
                                                            ad.id,
                                                        )
                                                    "
                                                    method="delete"
                                                    as="button"
                                                    preserve-scroll
                                                    class="w-full cursor-pointer"
                                                >
                                                    Supprimer
                                                </Link>
                                            </DropdownMenuItem>
                                        </DropdownMenuContent>
                                    </DropdownMenu>
                                </TableCell>
                            </TableRow>
                        </template>
                        <template v-else>
                            <TableRow>
                                <TableCell colspan="9" class="h-24 text-center">
                                    Aucun véhicule trouvé.
                                </TableCell>
                            </TableRow>
                        </template>
                    </TableBody>
                </Table>
            </div>

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
