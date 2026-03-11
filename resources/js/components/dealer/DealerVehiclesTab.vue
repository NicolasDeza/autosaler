<script setup lang="ts">
import { router, Link } from '@inertiajs/vue3';
import {
    MoreHorizontal,
    Eye,
    Heart,
    MessageCircle,
    Clock,
    Image as ImageIcon,
    Search,
} from 'lucide-vue-next';
import { ref } from 'vue';
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
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { Input } from '@/components/ui/input';
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
    ads: any; // Ideally mapped to PaginatedResource<VehicleAd>
}

const props = defineProps<Props>();

const searchQuery = ref('');

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
                    <Button variant="outline" size="sm">Filtres</Button>
                </div>
            </div>
        </CardHeader>
        <CardContent class="p-0">
            <div class="overflow-x-auto">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead class="w-[50px]"><Checkbox /></TableHead>
                            <TableHead>Numéro de référence</TableHead>
                            <TableHead>Véhicule</TableHead>
                            <TableHead>Prix</TableHead>
                            <TableHead class="text-center"
                                ><Eye class="mx-auto h-4 w-4"
                            /></TableHead>
                            <TableHead class="text-center"
                                ><MessageCircle class="mx-auto h-4 w-4"
                            /></TableHead>
                            <TableHead class="text-center"
                                ><Heart class="mx-auto h-4 w-4"
                            /></TableHead>
                            <TableHead class="w-[100px]">Statut</TableHead>
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
                                        {{ ad.vehicle_version_name }}
                                    </div>
                                    <div
                                        class="line-clamp-1 text-xs text-muted-foreground"
                                    >
                                        {{
                                            ad.vehicle_version?.name ||
                                            ad.description
                                        }}
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
