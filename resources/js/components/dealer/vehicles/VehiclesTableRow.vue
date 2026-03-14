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
    <TableRow class="group transition-colors hover:bg-muted/30">
        <TableCell class="print:hidden"
            ><Checkbox class="transition-all active:scale-90"
        /></TableCell>
        <TableCell>
            <div class="flex items-center gap-4 py-1">
                <div
                    class="relative flex h-12 w-16 shrink-0 items-center justify-center overflow-hidden rounded-lg bg-muted shadow-inner ring-1 ring-border/50 transition-all group-hover:shadow-md print:hidden"
                >
                    <ImageIcon
                        class="h-6 w-6 text-muted-foreground/50 transition-transform group-hover:scale-110"
                    />
                </div>
                <div class="min-w-0 flex-1">
                    <div
                        class="font-bold text-foreground transition-colors group-hover:text-primary"
                    >
                        {{ ad.brand?.name }}
                        {{ ad.model?.name }}
                    </div>
                    <div
                        class="line-clamp-1 text-xs font-medium text-muted-foreground/80"
                    >
                        {{ ad.vehicle_version_name }}
                    </div>
                </div>
            </div>
        </TableCell>
        <TableCell>
            <div class="flex flex-col gap-0.5">
                <span class="text-xs font-bold text-foreground">
                    {{
                        ad.created_at
                            ? new Date(ad.created_at).toLocaleDateString()
                            : 'N/A'
                    }}
                </span>
            </div>
        </TableCell>
        <TableCell>
            <div class="text-lg font-black tracking-tight text-primary">
                {{ formatPrice(ad.price) }}
            </div>
        </TableCell>
        <TableCell class="text-center">
            <div class="inline-flex flex-col items-center">
                <span class="text-sm font-bold text-foreground">{{
                    ad.stat?.views_count || '0'
                }}</span>
                <span
                    class="text-[10px] font-bold text-muted-foreground/60 uppercase"
                    >{{ __('dealer.views') }}</span
                >
            </div>
        </TableCell>
        <TableCell class="text-center">
            <div class="inline-flex flex-col items-center">
                <span class="text-sm font-bold text-foreground">{{
                    ad.stat?.contact_count || '0'
                }}</span>
                <span
                    class="text-[10px] font-bold text-muted-foreground/60 uppercase"
                    >{{ __('dealer.contacts') }}</span
                >
            </div>
        </TableCell>
        <TableCell class="text-center">
            <div class="inline-flex flex-col items-center">
                <span class="text-sm font-bold text-foreground">{{
                    ad.stat?.fav_count || '0'
                }}</span>
                <span
                    class="text-[10px] font-bold text-muted-foreground/60 uppercase"
                    >{{ __('dealer.favorites') }}</span
                >
            </div>
        </TableCell>
        <TableCell>
            <div class="flex items-center gap-3">
                <Switch
                    :model-value="ad.status === 'active'"
                    @update:model-value="handleStatusChange"
                    class="data-[state=checked]:bg-green-500 print:hidden"
                />
                <span
                    class="hidden text-xs font-bold tracking-widest uppercase print:inline"
                    >{{ ad.status }}</span
                >
            </div>
        </TableCell>
        <TableCell class="print:hidden">
            <DropdownMenu>
                <DropdownMenuTrigger asChild>
                    <Button
                        variant="ghost"
                        size="icon"
                        class="h-9 w-9 p-0 transition-all hover:bg-background hover:shadow-sm"
                    >
                        <span class="sr-only">{{ __('ui.open_menu') }}</span>
                        <MoreHorizontal class="h-5 w-5" />
                    </Button>
                </DropdownMenuTrigger>
                <DropdownMenuContent
                    align="end"
                    class="w-48 shadow-lg ring-1 ring-black/5"
                >
                    <DropdownMenuLabel
                        class="px-3 py-2 text-xs font-bold tracking-widest text-muted-foreground/60 uppercase"
                        >{{ __('ui.actions') }}</DropdownMenuLabel
                    >
                    <DropdownMenuItem
                        asChild
                        class="cursor-pointer px-3 py-2 font-medium focus:bg-primary/5 focus:text-primary"
                    >
                        <Link :href="vehicleShow.url(ad.id)" class="w-full">
                            {{ __('ui.view_ad') }}
                        </Link>
                    </DropdownMenuItem>
                    <DropdownMenuItem
                        asChild
                        class="cursor-pointer px-3 py-2 font-medium focus:bg-primary/5 focus:text-primary"
                    >
                        <Link :href="vehicleEdit.url(ad.id)" class="w-full">
                            {{ __('ui.edit') }}
                        </Link>
                    </DropdownMenuItem>
                    <div class="my-1 h-px bg-muted"></div>
                    <DropdownMenuItem
                        asChild
                        class="cursor-pointer px-3 py-2 font-bold text-destructive focus:bg-destructive/5 focus:text-destructive"
                    >
                        <Link
                            :href="vehicleDestroy.url(ad.id)"
                            method="delete"
                            as="button"
                            preserve-scroll
                            class="w-full text-left"
                        >
                            {{ __('ui.delete') }}
                        </Link>
                    </DropdownMenuItem>
                </DropdownMenuContent>
            </DropdownMenu>
        </TableCell>
    </TableRow>
</template>
