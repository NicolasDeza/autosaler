<script setup lang="ts">
import {
    ArrowUpDown,
    ArrowUp,
    ArrowDown,
    Eye,
    MessageCircle,
    Star,
} from 'lucide-vue-next';
import { Checkbox } from '@/components/ui/checkbox';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import { useTranslation } from '@/composables/useTranslation';
import VehiclesTableRow from './VehiclesTableRow.vue';

interface Props {
    ads: any;
    currentSort: string;
}

const props = defineProps<Props>();
const emit = defineEmits(['sort', 'status-change']);

const { __ } = useTranslation();

const toggleSort = (column: string) => {
    emit('sort', column);
};

const getSortIcon = (column: string) => {
    if (
        props.currentSort === column + '_asc' ||
        (column === 'created_at' && props.currentSort === 'oldest')
    ) {
        return ArrowUp;
    }
    if (
        props.currentSort === column + '_desc' ||
        (column === 'created_at' && props.currentSort === 'latest')
    ) {
        return ArrowDown;
    }
    return ArrowUpDown;
};
</script>

<template>
    <div class="overflow-x-auto md:overflow-visible">
        <Table>
            <TableHeader class="hidden bg-secondary/5 md:table-header-group">
                <TableRow class="border-b hover:bg-transparent">
                    <TableHead class="w-[50px] py-4 print:hidden"
                        ><Checkbox
                    /></TableHead>
                    <TableHead
                        class="py-4 text-[11px] font-bold tracking-wider text-muted-foreground/80 uppercase"
                        >{{ __('dealer.vehicle') }}</TableHead
                    >
                    <TableHead
                        class="w-[140px] cursor-pointer py-4 text-[11px] font-bold tracking-wider text-muted-foreground/80 uppercase transition-colors hover:text-primary"
                        @click="toggleSort('created_at')"
                    >
                        <div class="flex items-center gap-2">
                            {{ __('ui.date') }}
                            <component
                                :is="getSortIcon('created_at')"
                                class="h-3.5 w-3.5"
                            />
                        </div>
                    </TableHead>
                    <TableHead
                        class="cursor-pointer py-4 text-[11px] font-bold tracking-wider text-muted-foreground/80 uppercase transition-colors hover:text-primary"
                        @click="toggleSort('price')"
                    >
                        <div class="flex items-center gap-2">
                            {{ __('dealer.price') }}
                            <component
                                :is="getSortIcon('price')"
                                class="h-3.5 w-3.5"
                            />
                        </div>
                    </TableHead>
                    <TableHead
                        class="cursor-pointer py-4 text-center text-[11px] font-bold tracking-wider text-muted-foreground/80 uppercase transition-colors hover:text-primary"
                        @click="toggleSort('views')"
                    >
                        <div class="flex items-center justify-center gap-2">
                            <Eye class="h-3.5 w-3.5" />
                            <component
                                :is="getSortIcon('views')"
                                class="h-3.5 w-3.5"
                            />
                        </div>
                    </TableHead>
                    <TableHead
                        class="cursor-pointer py-4 text-center text-[11px] font-bold tracking-wider text-muted-foreground/80 uppercase transition-colors hover:text-primary"
                        @click="toggleSort('contacts')"
                    >
                        <div class="flex items-center justify-center gap-2">
                            <MessageCircle class="h-3.5 w-3.5" />
                            <component
                                :is="getSortIcon('contacts')"
                                class="h-3.5 w-3.5"
                            />
                        </div>
                    </TableHead>
                    <TableHead
                        class="cursor-pointer py-4 text-center text-[11px] font-bold tracking-wider text-muted-foreground/80 uppercase transition-colors hover:text-primary"
                        @click="toggleSort('favs')"
                    >
                        <div class="flex items-center justify-center gap-2">
                            <Star class="h-3.5 w-3.5" />
                            <component
                                :is="getSortIcon('favs')"
                                class="h-3.5 w-3.5"
                            />
                        </div>
                    </TableHead>
                    <TableHead
                        class="w-[120px] py-4 text-[11px] font-bold tracking-wider text-muted-foreground/80 uppercase"
                    >
                        {{ __('dealer.status') }}
                    </TableHead>
                    <TableHead class="w-[60px] py-4 print:hidden"></TableHead>
                </TableRow>
            </TableHeader>

            <TableBody>
                <template v-if="ads && ads.data && ads.data.length > 0">
                    <VehiclesTableRow
                        v-for="ad in ads.data"
                        :key="ad.id"
                        :ad="ad"
                        @status-change="
                            (checked) => emit('status-change', ad, checked)
                        "
                    />
                </template>
                <template v-else>
                    <TableRow>
                        <TableCell colspan="9" class="h-24 text-center">
                            {{ __('dealer.no_vehicles_found') }}
                        </TableCell>
                    </TableRow>
                </template>
            </TableBody>
        </Table>
    </div>
</template>
