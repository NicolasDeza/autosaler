<script setup lang="ts">
import { usePage, Link } from '@inertiajs/vue3';
import { X, ArrowRight, Trash2, Car } from 'lucide-vue-next';
import { computed } from 'vue';
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
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
            class="fixed bottom-6 left-1/2 z-50 hidden w-max -translate-x-1/2 lg:block"
        >
            <Card
                class="flex min-w-[600px] flex-row items-center justify-between gap-8 border-primary/20 bg-background/95 p-3 py-3 shadow-2xl backdrop-blur-md"
            >
                <div class="flex flex-1 items-center gap-4">
                    <div class="flex gap-3">
                        <div
                            v-for="vehicle in state.vehicles"
                            :key="vehicle.id"
                            class="group relative flex w-60 items-center gap-3 rounded-xl border border-border/50 bg-muted/30 transition-all hover:bg-muted/50"
                        >
                            <!-- Image/Placeholder -->
                            <div
                                class="relative h-20 w-28 shrink-0 overflow-hidden rounded-lg bg-muted shadow-inner"
                            >
                                <div
                                    class="flex h-full w-full items-center justify-center"
                                >
                                    <Car
                                        class="size-10 text-muted-foreground/20"
                                    />
                                </div>
                            </div>

                            <!-- Info -->
                            <div class="flex min-w-0 flex-col">
                                <h4
                                    class="truncate font-black tracking-tight text-foreground uppercase"
                                >
                                    {{ vehicle.brand }} {{ vehicle.model }}
                                </h4>
                                <p
                                    v-if="vehicle.vehicle_version_name"
                                    class="truncate text-sm leading-tight font-bold text-muted-foreground uppercase"
                                >
                                    {{ vehicle.vehicle_version_name }}
                                </p>
                                <span
                                    class="mt-0.5 text-sm font-black text-primary"
                                >
                                    {{
                                        Number(vehicle.price).toLocaleString(
                                            'fr-FR',
                                        )
                                    }}
                                    €
                                </span>
                            </div>

                            <!-- Delete -->
                            <button
                                @click="removeVehicle(vehicle.id)"
                                class="absolute -top-2 -right-2 flex size-6 items-center justify-center rounded-full border border-border bg-background text-muted-foreground opacity-0 shadow-sm transition-all group-hover:opacity-100 hover:bg-destructive hover:text-white"
                            >
                                <X class="size-3" />
                            </button>
                        </div>
                    </div>

                    <div
                        v-if="state.vehicles.length < 4"
                        class="flex h-16 w-32 items-center justify-center rounded-xl border border-dashed border-border/50 bg-muted/10"
                    >
                        <span
                            class="text-sm font-bold tracking-widest text-muted-foreground uppercase"
                            >+ {{ 4 - state.vehicles.length }} places</span
                        >
                    </div>
                </div>

                <div
                    class="flex shrink-0 flex-col items-center justify-end gap-3 border-l border-border/50 pl-8"
                >
                    <Link :href="compareUrl">
                        <Button
                            size="sm"
                            class="h-10 gap-2 px-8 font-black tracking-tighter uppercase"
                        >
                            Comparer
                            <ArrowRight class="size-4" />
                        </Button>
                    </Link>
                    <Button
                        variant="ghost"
                        size="sm"
                        class="h-10 px-4 text-xs font-bold tracking-widest text-muted-foreground uppercase hover:bg-destructive/5 hover:text-destructive"
                        @click="clearSelection"
                    >
                        <Trash2 class="size-4" />
                        Vider
                    </Button>
                </div>
            </Card>
        </div>
    </Transition>
</template>
