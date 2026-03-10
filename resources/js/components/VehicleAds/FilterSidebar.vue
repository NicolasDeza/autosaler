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
                    class="h-14 w-14 rounded-full bg-blue-600 shadow-lg transition-transform hover:scale-110 hover:bg-blue-700 active:scale-95"
                >
                    <Filter class="h-6 w-6 text-white" />
                </Button>
            </SheetTrigger>
            <SheetContent
                side="left"
                class="w-[85vw] border-slate-800 bg-slate-900 p-0 text-white sm:max-w-md"
            >
                <SheetHeader
                    class="border-b border-slate-800 p-6 pb-4 text-left"
                >
                    <div class="flex items-center justify-between">
                        <SheetTitle class="text-xl font-bold text-white"
                            >Filtres</SheetTitle
                        >
                        <Button
                            variant="ghost"
                            size="sm"
                            class="text-xs text-slate-400 hover:text-white"
                            @click="emit('resetFilters')"
                            >Réinitialiser</Button
                        >
                    </div>
                </SheetHeader>
                <div class="h-[calc(100vh-80px)] overflow-y-auto p-6 pt-2">
                    <VehicleFilters
                        v-model:form="form"
                        :brands="brands"
                        :fuel-types="fuelTypes"
                        :body-types="bodyTypes"
                        :transmission-types="transmissionTypes"
                        :exterior-colors="exteriorColors"
                        :euro-norms="euroNorms"
                        :models="models"
                        @update:models="handleUpdateModels"
                    />
                </div>
            </SheetContent>
        </Sheet>
    </div>

    <!-- Desktop Sidebar -->
    <aside
        class="sticky top-20 hidden h-fit w-full shrink-0 space-y-0 rounded-lg bg-slate-900 p-6 text-white md:block md:w-1/4 lg:w-1/4"
    >
        <div class="mb-6 flex items-center justify-between">
            <h3 class="text-xl font-bold">Filtres</h3>
            <Button
                variant="ghost"
                size="sm"
                class="text-xs text-slate-400 hover:text-white"
                @click="emit('resetFilters')"
                >Réinitialiser</Button
            >
        </div>

        <VehicleFilters
            v-model:form="form"
            :brands="brands"
            :fuel-types="fuelTypes"
            :body-types="bodyTypes"
            :transmission-types="transmissionTypes"
            :exterior-colors="exteriorColors"
            :euro-norms="euroNorms"
            :models="models"
            @update:models="handleUpdateModels"
        />
    </aside>
</template>
