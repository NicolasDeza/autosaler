<script setup lang="ts">
import { Filter } from 'lucide-vue-next';
import { ref } from 'vue';
import { Button } from '@/components/ui/button';
import {
    Sheet,
    SheetContent,
    SheetHeader,
    SheetTitle,
    SheetTrigger,
} from '@/components/ui/sheet';
import VehicleFilters from './VehicleFilters.vue';

defineProps<{
    brands?: any[];
    fuelTypes?: any[];
    bodyTypes?: any[];
    transmissionTypes?: any[];
    exteriorColors?: any[];
    euroNorms?: any[];
    interiorColors?: any[];
    interiorTypes?: any[];
    features?: any[];
    models: any[];
}>();

const form = defineModel<any>('form', { required: true });

const emit = defineEmits(['resetFilters', 'update:models']);
const isOpen = ref(false);

const handleUpdateModels = (models: any[]) => {
    emit('update:models', models);
};
</script>

<template>
    <!-- Mobile Floating Button -->
    <div class="fixed bottom-6 left-6 z-50 md:hidden">
        <Sheet v-model:open="isOpen">
            <SheetTrigger as-child>
                <Button
                    size="lg"
                    class="dark group h-14 w-14 rounded-full bg-background shadow-lg transition-transform hover:scale-110 hover:ring-2 hover:ring-red-500 active:scale-95"
                >
                    <Filter
                        class="h-6 w-6 text-red-500 transition-transform group-hover:scale-110"
                    />
                </Button>
            </SheetTrigger>
            <SheetContent side="left" class="w-[85vw] bg-card p-0 sm:max-w-md">
                <SheetHeader
                    class="dark border-b border-border bg-background p-6 pb-4 text-left"
                >
                    <div class="flex items-center justify-between">
                        <SheetTitle class="text-xl font-bold text-foreground"
                            >Filtres</SheetTitle
                        >
                        <Button
                            variant="ghost"
                            size="sm"
                            class="text-xs text-foreground/60 hover:text-foreground"
                            @click="emit('resetFilters')"
                            >Réinitialiser</Button
                        >
                    </div>
                </SheetHeader>
                <div
                    class="h-[calc(100vh-80px)] overflow-y-auto bg-card p-6 pt-2 text-card-foreground"
                >
                    <VehicleFilters
                        v-model:form="form"
                        :brands="brands"
                        :fuel-types="fuelTypes"
                        :body-types="bodyTypes"
                        :transmission-types="transmissionTypes"
                        :exterior-colors="exteriorColors"
                        :euro-norms="euroNorms"
                        :interior-colors="interiorColors"
                        :interior-types="interiorTypes"
                        :features="features"
                        :models="models"
                        @update:models="handleUpdateModels"
                    />
                </div>
            </SheetContent>
        </Sheet>
    </div>

    <!-- Desktop Sidebar -->
    <aside
        class="sticky top-24 hidden h-fit w-full shrink-0 overflow-hidden rounded-lg bg-card shadow-lg md:block md:w-1/4 lg:w-1/4"
    >
        <div
            class="dark flex items-center justify-between border-b border-border bg-background p-6 pb-4"
        >
            <h3 class="text-xl font-bold text-foreground">Filtres</h3>
            <Button
                variant="ghost"
                size="sm"
                class="cursor-pointer text-xs text-foreground/60 hover:text-foreground"
                @click="emit('resetFilters')"
                >Réinitialiser</Button
            >
        </div>

        <div
            class="custom-scrollbar max-h-[calc(100vh-14rem)] overflow-y-auto bg-card p-6 pr-2 text-card-foreground"
        >
            <VehicleFilters
                v-model:form="form"
                :brands="brands"
                :fuel-types="fuelTypes"
                :body-types="bodyTypes"
                :transmission-types="transmissionTypes"
                :exterior-colors="exteriorColors"
                :euro-norms="euroNorms"
                :interior-colors="interiorColors"
                :interior-types="interiorTypes"
                :features="features"
                :models="models"
                @update:models="handleUpdateModels"
            />
        </div>
    </aside>
</template>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    width: 5px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: hsl(var(--muted-foreground) / 0.3);
    border-radius: 10px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: hsl(var(--muted-foreground) / 0.5);
}

/* Firefox */
.custom-scrollbar {
    scrollbar-width: thin;
    scrollbar-color: hsl(var(--muted-foreground) / 0.3) transparent;
}
</style>
