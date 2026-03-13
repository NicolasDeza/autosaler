<script setup lang="ts">
import { Search, Filter } from 'lucide-vue-next';
import { ref, watch } from 'vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';

interface Props {
    search: string;
    showFilters: boolean;
    activeFiltersCount: number;
}

const props = defineProps<Props>();
const emit = defineEmits(['update:search', 'update:showFilters']);

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
    <div class="flex items-center gap-2">
        <div class="relative w-full max-w-sm items-center">
            <Input
                id="search"
                type="text"
                placeholder="Rechercher..."
                class="h-9 pl-10"
                v-model="internalSearch"
                @input="handleSearch"
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
            @click="emit('update:showFilters', !showFilters)"
            :class="{ 'bg-muted': showFilters }"
        >
            <Filter class="mr-2 h-4 w-4" />
            Filtres
            <template v-if="activeFiltersCount > 0">
                <span
                    class="ml-2 flex h-4 w-4 items-center justify-center rounded-full bg-primary text-[10px] text-primary-foreground"
                >
                    {{ activeFiltersCount }}
                </span>
            </template>
        </Button>
    </div>
</template>
