<script setup lang="ts">
import { usePage, Link } from '@inertiajs/vue3';
import { X, ArrowRight, Trash2, Car } from 'lucide-vue-next';
import { computed } from 'vue';
import { Button } from '@/components/ui/button';
import { useComparison } from '@/composables/useComparison';
import vehiclesRoutes from '@/routes/vehicles';

const page = usePage();
const { state, removeVehicle, clearSelection } = useComparison();

const isVisible = computed(() => {
    const rawComponent = page.component;
    const component = rawComponent?.toLowerCase() || '';
    const hasVehicles = state.vehicles.length > 0;

    const isTargetPage =
        component.includes('vehicleads/index') ||
        component.includes('vehicleads/show');

    return isTargetPage && hasVehicles;
});

const compareUrl = computed(() => {
    const ids = state.vehicles.map((v) => v.id).join(',');
    return `${vehiclesRoutes.index.url()}/compare?ids=${ids}`;
});
</script>

<template>
    <Transition
        enter-from-class="translate-y-full opacity-0"
        enter-active-class="transition-all duration-500 ease-out"
        enter-to-class="translate-y-0 opacity-100"
        leave-from-class="translate-y-0 opacity-100"
        leave-active-class="transition-all duration-300 ease-in"
        leave-to-class="translate-y-full opacity-0"
    >
        <div
            v-if="isVisible"
            class="fixed bottom-8 left-1/2 z-50 hidden w-max -translate-x-1/2 lg:block"
        >
            <div
                class="flex min-w-[640px] flex-row items-center justify-between gap-10 rounded-2xl border border-white/10 bg-foreground p-2 shadow-[0_20px_50px_rgba(0,0,0,0.5)] backdrop-blur-2xl lg:flex"
            >
                <!-- Selection Area -->
                <div class="flex flex-1 items-center gap-3 px-2">
                    <div class="flex gap-2.5">
                        <div
                            v-for="vehicle in state.vehicles"
                            :key="vehicle.id"
                            class="group relative flex w-64 items-center gap-4 overflow-hidden rounded-xl border border-white/5 bg-white/5 p-2 transition-all duration-300 hover:scale-[1.02] hover:bg-white/10 active:scale-[0.98]"
                        >
                            <!-- Image/Placeholder Container -->
                            <div
                                class="relative h-16 w-24 shrink-0 overflow-hidden rounded-lg bg-muted shadow-inner"
                            >
                                <div
                                    class="flex h-full w-full items-center justify-center transition-transform duration-500 group-hover:scale-110"
                                >
                                    <Car
                                        class="size-8 text-muted-foreground/30"
                                    />
                                </div>
                            </div>

                            <!-- Vehicle Details -->
                            <div class="flex min-w-0 flex-col py-1">
                                <h4
                                    class="truncate text-xs font-black tracking-wide text-white uppercase"
                                >
                                    {{ vehicle.brand }} {{ vehicle.model }}
                                </h4>
                                <p
                                    v-if="vehicle.vehicle_version_name"
                                    class="truncate text-[10px] leading-tight font-bold tracking-wider text-white/40 uppercase"
                                >
                                    {{ vehicle.vehicle_version_name }}
                                </p>
                                <span
                                    class="mt-1 text-xs font-black text-primary"
                                >
                                    {{
                                        Number(vehicle.price).toLocaleString(
                                            'fr-FR',
                                        )
                                    }}
                                    €
                                </span>
                            </div>

                            <!-- Remove Button Overlay -->
                            <button
                                @click="removeVehicle(vehicle.id)"
                                class="absolute top-1 right-1 flex size-5 items-center justify-center rounded-full bg-background/80 text-muted-foreground opacity-0 backdrop-blur-md transition-all duration-300 group-hover:opacity-100 hover:bg-destructive hover:text-destructive-foreground"
                            >
                                <X class="size-3" />
                            </button>
                        </div>
                    </div>

                    <!-- Placeholder Slots -->
                    <div v-if="state.vehicles.length < 4" class="flex gap-2.5">
                        <div
                            v-for="i in 4 - state.vehicles.length"
                            :key="`empty-${i}`"
                            class="flex h-20 w-32 items-center justify-center rounded-xl border border-dashed border-white/10 bg-white/5 transition-colors hover:bg-white/10"
                        >
                            <span
                                class="text-[9px] font-black tracking-[0.2em] text-white/20 uppercase"
                                >Slot {{ state.vehicles.length + i }}</span
                            >
                        </div>
                    </div>
                </div>

                <!-- Action Side -->
                <div
                    class="flex shrink-0 flex-col items-center justify-center gap-1.5 border-l border-white/10 pr-4 pl-6"
                >
                    <Link :href="compareUrl">
                        <Button
                            size="sm"
                            class="h-11 gap-2 rounded-xl bg-primary px-8 font-black tracking-tighter text-white uppercase shadow-lg shadow-primary/20 transition-all hover:scale-105 hover:bg-primary/90 hover:shadow-primary/40 active:scale-95"
                        >
                            Comparer
                            <ArrowRight class="size-4" />
                        </Button>
                    </Link>
                    <Button
                        variant="ghost"
                        size="sm"
                        class="h-8 rounded-lg px-4 text-[10px] font-black tracking-[0.2em] text-white/40 uppercase transition-colors hover:bg-white/10 hover:text-destructive"
                        @click="clearSelection"
                    >
                        <Trash2 class="mr-1.5 size-3" />
                        Tout vider
                    </Button>
                </div>
            </div>
        </div>
    </Transition>
</template>
