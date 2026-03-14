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
import { useTranslation } from '@/composables/useTranslation';

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

const { __ } = useTranslation();

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
                class="grid grid-cols-1 gap-6 border-b border-border/40 bg-muted/20 p-6 md:grid-cols-2 lg:grid-cols-6"
            >
                <div class="space-y-2">
                    <Label class="text-[11px] font-bold uppercase tracking-wider text-muted-foreground/80">{{ __('dealer.brand') }}</Label>
                    <Select v-model="brandFilter" :disabled="!brands">
                        <SelectTrigger class="h-10 text-sm ring-offset-background transition-all focus:ring-1 focus:ring-primary/20">
                            <SelectValue
                                :placeholder="
                                    !brands
                                        ? __('ui.loading')
                                        : __('dealer.all_brands')
                                "
                            />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="all"
                                >{{ __('dealer.all_brands') }}</SelectItem
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
                <div class="space-y-2">
                    <Label class="text-[11px] font-bold uppercase tracking-wider text-muted-foreground/80">{{ __('dealer.model') }}</Label>
                    <Select v-model="modelFilter" :disabled="!models">
                        <SelectTrigger class="h-10 text-sm ring-offset-background transition-all focus:ring-1 focus:ring-primary/20">
                            <SelectValue
                                :placeholder="
                                    !models
                                        ? __('ui.loading')
                                        : __('dealer.all_models')
                                "
                            />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="all"
                                >{{ __('dealer.all_models') }}</SelectItem
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
                <div class="space-y-2">
                    <Label class="text-[11px] font-bold uppercase tracking-wider text-muted-foreground/80">{{ __('dealer.status') }}</Label>
                    <Select v-model="statusFilter">
                        <SelectTrigger class="h-10 text-sm ring-offset-background transition-all focus:ring-1 focus:ring-primary/20">
                            <SelectValue :placeholder="__('dealer.all_statuses')" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="all"
                                >{{ __('dealer.all_statuses') }}</SelectItem
                            >
                            <SelectItem value="active">{{ __('dealer.active_status') }}</SelectItem>
                            <SelectItem value="draft">{{ __('dealer.draft_status') }}</SelectItem>
                            <SelectItem value="sold">{{ __('dealer.sold_status') }}</SelectItem>
                        </SelectContent>
                    </Select>
                </div>
                <div class="space-y-2">
                    <Label class="text-[11px] font-bold uppercase tracking-wider text-muted-foreground/80">{{ __('dealer.from_date') }}</Label>
                    <Input
                        type="date"
                        v-model="dateFrom"
                        class="h-10 text-sm transition-all focus-visible:ring-1 focus-visible:ring-primary/20"
                        @change="emitFilters"
                    />
                </div>
                <div class="space-y-2">
                    <Label class="text-[11px] font-bold uppercase tracking-wider text-muted-foreground/80">{{ __('dealer.to_date') }}</Label>
                    <Input
                        type="date"
                        v-model="dateTo"
                        class="h-10 text-sm transition-all focus-visible:ring-1 focus-visible:ring-primary/20"
                        @change="emitFilters"
                    />
                </div>
                <div class="flex items-end">
                    <Button
                        variant="ghost"
                        size="default"
                        class="group h-10 w-full justify-center px-4 font-medium text-muted-foreground transition-all hover:bg-background hover:text-foreground"
                        @click="handleReset"
                    >
                        <RotateCcw class="mr-2 h-4 w-4 transition-transform group-hover:rotate-[-45deg]" />
                        {{ __('ui.reset') }}
                    </Button>
                </div>
            </div>
        </CollapsibleContent>
    </Collapsible>
</template>
