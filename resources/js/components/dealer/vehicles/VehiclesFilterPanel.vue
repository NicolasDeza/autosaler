<script setup lang="ts">
import { RotateCcw } from 'lucide-vue-next';
import { ref, watch } from 'vue';
import { Button } from '@/components/ui/button';
import { Collapsible, CollapsibleContent } from '@/components/ui/collapsible';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';

interface Props {
    open: boolean;
    filters: {
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
const emit = defineEmits(['update:filters', 'reset']);

const brandFilter = ref(props.filters.brand_id?.toString() || 'all');
const modelFilter = ref(props.filters.model_id?.toString() || 'all');
const statusFilter = ref(props.filters.status || 'all');
const dateFrom = ref(props.filters.date_from || '');
const dateTo = ref(props.filters.date_to || '');

watch(
    () => props.filters,
    (newFilters) => {
        brandFilter.value = newFilters.brand_id?.toString() || 'all';
        modelFilter.value = newFilters.model_id?.toString() || 'all';
        statusFilter.value = newFilters.status || 'all';
        dateFrom.value = newFilters.date_from || '';
        dateTo.value = newFilters.date_to || '';
    },
    { deep: true },
);

const emitFilters = () => {
    emit('update:filters', {
        brand_id: brandFilter.value === 'all' ? undefined : brandFilter.value,
        model_id: modelFilter.value === 'all' ? undefined : modelFilter.value,
        status: statusFilter.value === 'all' ? undefined : statusFilter.value,
        date_from: dateFrom.value || undefined,
        date_to: dateTo.value || undefined,
    });
};

watch(brandFilter, () => {
    modelFilter.value = 'all';
    emitFilters();
});

watch([modelFilter, statusFilter], () => {
    emitFilters();
});

const handleReset = () => {
    emit('reset');
};
</script>

<template>
    <Collapsible :open="open" class="w-full">
        <CollapsibleContent>
            <div
                class="grid grid-cols-1 gap-4 border-b border-border/50 bg-muted/30 p-4 md:grid-cols-3 lg:grid-cols-6"
            >
                <div class="space-y-1.5">
                    <Label class="text-xs">Marque</Label>
                    <Select v-model="brandFilter" :disabled="!brands">
                        <SelectTrigger class="h-8 text-xs">
                            <SelectValue
                                :placeholder="
                                    !brands
                                        ? 'Chargement...'
                                        : 'Toutes les marques'
                                "
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
                    <Select v-model="modelFilter" :disabled="!models">
                        <SelectTrigger class="h-8 text-xs">
                            <SelectValue
                                :placeholder="
                                    !models
                                        ? 'Chargement...'
                                        : 'Tous les modèles'
                                "
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
                    <Select v-model="statusFilter">
                        <SelectTrigger class="h-8 text-xs">
                            <SelectValue placeholder="Tous les statuts" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="all"
                                >Tous les statuts</SelectItem
                            >
                            <SelectItem value="active">Actif</SelectItem>
                            <SelectItem value="draft">Brouillon</SelectItem>
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
                        @change="emitFilters"
                    />
                </div>
                <div class="space-y-1.5">
                    <Label class="text-xs">Au</Label>
                    <Input
                        type="date"
                        v-model="dateTo"
                        class="h-8 text-xs"
                        @change="emitFilters"
                    />
                </div>
                <div class="flex items-end gap-2">
                    <Button
                        variant="ghost"
                        size="sm"
                        class="h-8 w-full justify-start px-2 font-normal text-muted-foreground hover:text-foreground"
                        @click="handleReset"
                    >
                        <RotateCcw class="mr-2 h-3.5 w-3.5" />
                        Réinitialiser
                    </Button>
                </div>
            </div>
        </CollapsibleContent>
    </Collapsible>
</template>
