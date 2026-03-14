<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { MoreHorizontal, Clock, Image as ImageIcon } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { Switch } from '@/components/ui/switch';
import { TableCell, TableRow } from '@/components/ui/table';
import { useTranslation } from '@/composables/useTranslation';
import {
    show as vehicleShow,
    edit as vehicleEdit,
    destroy as vehicleDestroy,
} from '@/routes/vehicles';

interface Props {
    ad: any;
}

defineProps<Props>();
const emit = defineEmits(['status-change']);

const { __ } = useTranslation();

const formatPrice = (price: number) => {
    return new Intl.NumberFormat('fr-FR', {
        style: 'currency',
        currency: 'EUR',
    }).format(price);
};

const handleStatusChange = (checked: boolean) => {
    emit('status-change', checked);
};
</script>

<template>
    <TableRow>
        <TableCell class="print:hidden"><Checkbox /></TableCell>
        <TableCell>
            <div class="flex items-center gap-3">
                <div
                    class="flex h-10 w-12 shrink-0 items-center justify-center rounded bg-muted print:hidden"
                >
                    <ImageIcon class="h-5 w-5 text-muted-foreground" />
                </div>
                <div class="min-w-0">
                    <div class="font-medium truncate">
                        {{ ad.brand?.name }}
                        {{ ad.model?.name }}
                    </div>
                    <div class="line-clamp-1 text-xs text-muted-foreground">
                        {{ ad.vehicle_version_name }}
                    </div>
                </div>
            </div>
        </TableCell>
        <TableCell>
            <div
                class="flex items-center gap-1 text-xs text-muted-foreground"
            >
                <Clock class="h-3 w-3" />
                {{
                    ad.created_at
                        ? new Date(ad.created_at).toLocaleDateString()
                        : 'N/A'
                }}
            </div>
        </TableCell>
        <TableCell>
            <div class="font-medium text-green-600 dark:text-green-500">
                {{ formatPrice(ad.price) }}
            </div>
        </TableCell>
        <TableCell class="text-center text-muted-foreground">
            {{ ad.stat?.views_count || '-' }}
        </TableCell>
        <TableCell class="text-center text-muted-foreground">
            {{ ad.stat?.contact_count || '-' }}
        </TableCell>
        <TableCell class="text-center text-muted-foreground">
            {{ ad.stat?.fav_count || '-' }}
        </TableCell>
        <TableCell>
            <div class="flex items-center gap-2">
                <Switch
                    :model-value="ad.status === 'active'"
                    @update:model-value="handleStatusChange"
                    class="print:hidden"
                />
                <span class="hidden print:inline text-xs capitalize">{{
                    ad.status
                }}</span>
            </div>
        </TableCell>
        <TableCell class="print:hidden">
            <DropdownMenu>
                <DropdownMenuTrigger asChild>
                    <Button variant="ghost" size="icon" class="h-8 w-8 p-0">
                        <span class="sr-only">{{ __('ui.open_menu') }}</span>
                        <MoreHorizontal class="h-4 w-4" />
                    </Button>
                </DropdownMenuTrigger>
                <DropdownMenuContent align="end">
                    <DropdownMenuLabel>{{ __('ui.actions') }}</DropdownMenuLabel>
                    <DropdownMenuItem asChild>
                        <Link
                            :href="vehicleShow.url(ad.id)"
                            class="w-full cursor-pointer"
                        >
                            {{ __('ui.view_ad') }}
                        </Link>
                    </DropdownMenuItem>
                    <DropdownMenuItem asChild>
                        <Link
                            :href="vehicleEdit.url(ad.id)"
                            class="w-full cursor-pointer"
                        >
                            {{ __('ui.edit') }}
                        </Link>
                    </DropdownMenuItem>
                    <DropdownMenuItem asChild class="text-destructive">
                        <Link
                            :href="vehicleDestroy.url(ad.id)"
                            method="delete"
                            as="button"
                            preserve-scroll
                            class="w-full cursor-pointer"
                        >
                            {{ __('ui.delete') }}
                        </Link>
                    </DropdownMenuItem>
                </DropdownMenuContent>
            </DropdownMenu>
        </TableCell>
    </TableRow>

</template>
