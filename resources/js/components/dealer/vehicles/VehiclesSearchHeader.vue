<script setup lang="ts">
import { Search, Filter } from 'lucide-vue-next';
import { ref, watch } from 'vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { useTranslation } from '@/composables/useTranslation';

interface Props {
    search: string;
    showFilters: boolean;
    activeFiltersCount: number;
}

const props = defineProps<Props>();
const emit = defineEmits(['update:search', 'update:showFilters']);

const { __ } = useTranslation();

const internalSearch = ref(props.search);

watch(
    () => props.search,
    (newVal) => {
        internalSearch.value = newVal;
    },
);

const handleSearch = () => {
    emit('update:search', internalSearch.value);
};
</script>

<template>
    <div class="flex items-center gap-3">
        <div class="relative hidden w-full max-w-sm md:flex">
            <Search
                class="absolute top-1/2 left-3 h-4 w-4 -translate-y-1/2 text-muted-foreground transition-colors group-focus-within:text-primary"
            />
            <Input
                id="search"
                type="text"
                :placeholder="__('ui.search')"
                class="h-10 border-none bg-muted/50 pl-10 transition-all focus-visible:bg-background focus-visible:ring-1 focus-visible:ring-primary/20"
                v-model="internalSearch"
                @input="handleSearch"
            />
        </div>
        <Button
            variant="ghost"
            size="default"
            @click="emit('update:showFilters', !showFilters)"
            class="hidden h-10 gap-2 border border-transparent px-3 transition-all hover:cursor-pointer hover:border-border hover:bg-background hover:shadow-sm md:flex md:px-4"
            :class="{ 'border-border bg-background shadow-sm': showFilters }"
        >
            <Filter
                class="h-4 w-4 transition-colors"
                :class="{ 'text-primary': showFilters }"
            />
            <span class="hidden text-sm font-medium md:inline">{{
                __('ui.filters')
            }}</span>
            <template v-if="activeFiltersCount > 0">
                <span
                    class="flex h-5 min-w-5 items-center justify-center rounded-full bg-primary px-1 text-[10px] font-bold text-primary-foreground shadow-sm shadow-primary/20"
                >
                    {{ activeFiltersCount }}
                </span>
            </template>
        </Button>
    </div>
</template>
