<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { RotateCcw, SlidersHorizontal, Star } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import LoginRequiredModal from '@/components/Auth/LoginRequiredModal.vue';
import SheetMenu from '@/components/SheetMenu.vue';
import { Button } from '@/components/ui/button';
import { useTranslation } from '@/composables/useTranslation';
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
    models?: any[];
    badgeCount?: number;
}>();

const form = defineModel<any>('form', { required: true });
const isOpen = defineModel<boolean>('open', { default: false });

const emit = defineEmits(['resetFilters', 'update:models']);
const showLoginModal = ref(false);
const page = usePage();
const auth = computed(() => page.props.auth);
const { __ } = useTranslation();

const handleUpdateModels = (models: any[]) => {
    emit('update:models', models);
};

const toggleFavoritesFilter = () => {
    if (!auth.value?.user) {
        showLoginModal.value = true;
        return;
    }
    form.value.favorites_only = !form.value.favorites_only;
};
</script>

<template>
    <!-- Mobile Sheet (Triggered from outside via v-model:open or by default floating button) -->
    <div class="lg:hidden">
        <SheetMenu
            v-model:open="isOpen"
            side="left"
            :title="__('ui.filters')"
            :icon="SlidersHorizontal"
            with-floating-button
            hide-floating-button-when-closed
            :badge-count="badgeCount"
        >
            <template #headerActions>
                <Button
                    size="icon"
                    class="group h-7 w-7 cursor-pointer rounded-md bg-transparent text-primary transition-colors duration-200 hover:bg-primary/80 hover:text-white"
                    :class="{
                        'bg-primary/20': form.favorites_only,
                    }"
                    @click="toggleFavoritesFilter"
                >
                    <Star
                        class="h-4 w-4 group-hover:fill-white group-hover:text-white"
                        :class="{
                            'fill-primary': form.favorites_only,
                        }"
                    />
                </Button>
                <Button
                    size="icon"
                    class="group h-7 w-7 cursor-pointer rounded-md bg-transparent text-primary transition-colors duration-200 hover:bg-primary/80 hover:text-white"
                    @click="emit('resetFilters')"
                >
                    <RotateCcw class="h-4 w-4 group-hover:text-white" />
                </Button>
            </template>

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
        </SheetMenu>
    </div>

    <!-- Desktop Sidebar -->
    <aside
        class="sticky top-24 hidden h-fit w-full shrink-0 overflow-hidden rounded-lg bg-card shadow-lg lg:block lg:w-1/4"
    >
        <div
            class="dark relative flex items-center justify-between bg-background px-5 py-4"
        >
            <div class="flex items-center gap-2.5">
                <SlidersHorizontal class="h-4 w-4 text-primary" />
                <div class="flex items-center gap-2">
                    <h3 class="text-base font-bold tracking-wide text-white">
                        {{ __('ui.filters') }}
                    </h3>
                    <div
                        v-if="badgeCount && badgeCount > 0"
                        class="flex h-5 min-w-5 items-center justify-center rounded-full bg-primary px-1.5 text-[10px] font-black leading-none text-white shadow-sm"
                    >
                        {{ badgeCount }}
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <Button
                    size="icon"
                    class="group h-7 w-7 cursor-pointer rounded-md bg-transparent text-primary transition-colors duration-200 hover:bg-primary/80 hover:text-white"
                    :class="{
                        'bg-primary/20': form.favorites_only,
                    }"
                    @click="toggleFavoritesFilter"
                >
                    <Star
                        class="h-4 w-4 group-hover:fill-white group-hover:text-white"
                        :class="{
                            'fill-primary': form.favorites_only,
                        }"
                    />
                </Button>
                <Button
                    size="icon"
                    class="group h-7 w-7 cursor-pointer rounded-md bg-transparent text-primary transition-colors duration-200 hover:bg-primary/80 hover:text-white"
                    @click="emit('resetFilters')"
                >
                    <RotateCcw class="h-4 w-4 group-hover:text-white" />
                </Button>
            </div>
        </div>
        <div class="h-px bg-border" />

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

    <LoginRequiredModal
        v-model:open="showLoginModal"
        :title="__('vehicleAd.favorites_filter_title')"
        :description="__('vehicleAd.favorites_filter_desc')"
    />
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
    border-radius: 10px;
}

/* Firefox */
.custom-scrollbar {
    scrollbar-width: thin;
    scrollbar-color: hsl(var(--muted-foreground) / 0.3) transparent;
}
</style>
