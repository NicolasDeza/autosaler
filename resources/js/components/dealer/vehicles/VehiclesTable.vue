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
    if (props.currentSort === column + '_asc') {
        return ArrowUp;
    }
    if (props.currentSort === column + '_desc') {
        return ArrowDown;
    }
    return ArrowUpDown;
};
</script>

<template>
    <div class="overflow-x-auto">
        <Table>
            <TableHeader>
                <TableRow>
                    <TableHead class="w-[50px] print:hidden"
                        ><Checkbox
                    /></TableHead>
                    <TableHead
                        class="w-[150px] cursor-pointer hover:bg-muted/50"
                        @click="toggleSort('id')"
                    >
                        <div class="flex items-center gap-2">
                            {{ __('dealer.reference_number') }}
                            <component
                                :is="getSortIcon('id')"
                                class="h-4 w-4"
                            />
                        </div>
                    </TableHead>
                    <TableHead>{{ __('dealer.vehicle') }}</TableHead>
                    <TableHead
                        class="cursor-pointer hover:bg-muted/50"
                        @click="toggleSort('price')"
                    >
                        <div class="flex items-center gap-2">
                            {{ __('dealer.price') }}
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
                        <div class="flex items-center justify-center gap-2">
                            <Eye class="h-4 w-4" />
                            <component
                                :is="getSortIcon('views')"
                                class="h-4 w-4"
                            />
                        </div>
                    </TableHead>
                    <TableHead
                        class="cursor-pointer text-center hover:bg-muted/50"
                        @click="toggleSort('contacts')"
                    >
                        <div class="flex items-center justify-center gap-2">
                            <MessageCircle class="h-4 w-4" />
                            <component
                                :is="getSortIcon('contacts')"
                                class="h-4 w-4"
                            />
                        </div>
                    </TableHead>
                    <TableHead
                        class="cursor-pointer text-center hover:bg-muted/50"
                        @click="toggleSort('favs')"
                    >
                        <div class="flex items-center justify-center gap-2">
                            <Star class="h-4 w-4" />
                            <component
                                :is="getSortIcon('favs')"
                                class="h-4 w-4"
                            />
                        </div>
                    </TableHead>
                    <TableHead
                        class="w-[100px] cursor-pointer hover:bg-muted/50"
                        @click="toggleSort('latest')"
                    >
                        <div class="flex items-center gap-2">
                            {{ __('dealer.status') }}
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
                    <TableHead class="w-[50px] print:hidden"></TableHead>
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
