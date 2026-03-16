<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import {
    ChevronLeft,
    X,
    Car,
    Check,
    Zap,
    Calendar,
    Gauge,
    Fuel,
    Cog,
    Palette,
    DoorOpen,
    Plus,
    ArrowRight,
    Leaf,
    MapPin,
    PaintBucket,
    Sofa,
    Users,
} from 'lucide-vue-next';
import { computed } from 'vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { kwToHp } from '@/lib/utils';
import vehiclesRoutes from '@/routes/vehicles';

const props = defineProps<{
    vehicles: any[];
}>();

const fields = [
    {
        label: 'Prix',
        key: 'price',
        icon: Zap,
        format: (v: any) => `${Number(v).toLocaleString('fr-FR')} €`,
        highlight: true,
    },
    {
        label: '1ère immatriculation',
        key: 'first_registration_date',
        icon: Calendar,
        format: (v: any) => v?.substring(0, 4) || 'N/A',
    },
    {
        label: 'Kilométrage',
        key: 'mileage',
        icon: Gauge,
        format: (v: any) => `${v?.toLocaleString('fr-FR')} km`,
    },
    { label: 'Emplacement', key: 'city.code', icon: MapPin },
    { label: 'Carburant', key: 'fuel_type.code', icon: Fuel },
    { label: 'Carrosserie', key: 'body_type.code', icon: Car },
    { label: 'Boîte de vitesse', key: 'transmission_type.code', icon: Cog },
    { label: 'Couleur extérieur', key: 'exterior_color.code', icon: Palette },
    {
        label: 'Couleur intérieur',
        key: 'interior_color.code',
        icon: PaintBucket,
    },
    { label: 'Matériaux intérieur', key: 'interior_type.code', icon: Sofa },
    { label: 'Norme Euro', key: 'euro_norm.code', icon: Leaf },
    {
        label: 'Puissance',
        key: 'power_kw',
        icon: Zap,
        format: (v: any) => {
            if (!v) return 'N/A';
            const kw = Math.round(Number(v));
            const hp = kwToHp(kw);
            return `${kw} kW (${hp} ch)`;
        },
    },
    {
        label: 'Portes',
        key: 'doors',
        icon: DoorOpen,
        format: (v: any) => `${v}P`,
    },
    {
        label: 'Sièges',
        key: 'seats',
        icon: Users,
        format: (v: any) => `${v}S`,
    },
];

const getValue = (item: any, path: string) => {
    return path.split('.').reduce((obj, key) => obj?.[key], item) ?? 'N/A';
};

const formatOptionLabel = (value?: string): string => {
    if (!value) {
        return '';
    }

    return value.replace(/[_-]+/g, ' ').trim();
};

const groupedFeatures = computed(() => {
    const categories = new Map<
        string,
        {
            id: string;
            code: string;
            sortOrder: number;
            featureCodes: Set<string>;
        }
    >();

    props.vehicles.forEach((vehicle) => {
        vehicle.features?.forEach((f: any) => {
            const categoryCode =
                f.category?.code ?? f.category?.key ?? 'autres';
            const categoryId = String(f.category?.id ?? categoryCode);

            if (!categories.has(categoryId)) {
                categories.set(categoryId, {
                    id: categoryId,
                    code: categoryCode,
                    sortOrder:
                        f.category?.sort_order ?? Number.MAX_SAFE_INTEGER,
                    featureCodes: new Set(),
                });
            }
            categories.get(categoryId)!.featureCodes.add(f.code);
        });
    });

    return Array.from(categories.values())
        .sort((a, b) => {
            if (a.sortOrder !== b.sortOrder) {
                return a.sortOrder - b.sortOrder;
            }
            return a.code.localeCompare(b.code, 'fr');
        })
        .map((cat) => ({
            ...cat,
            codes: Array.from(cat.featureCodes).sort((a, b) =>
                a.localeCompare(b, 'fr'),
            ),
        }));
});

const removeAndReload = (id: number) => {
    const newIds = props.vehicles
        .filter((v) => v.id !== id)
        .map((v) => v.id)
        .join(',');

    if (!newIds) {
        router.visit(vehiclesRoutes.index.url());
        return;
    }

    router.visit(`${vehiclesRoutes.index.url()}/compare?ids=${newIds}`, {
        preserveScroll: true,
        replace: true,
    });
};
</script>

<template>
    <Head title="Comparaison de véhicules" />

    <AppLayout>
        <div class="min-h-screen bg-background pb-20">
            <div class="px-4 pt-8 md:px-8">
                <button
                    class="group flex cursor-pointer items-center gap-2 text-[10px] font-black tracking-[0.3em] text-muted-foreground uppercase transition-colors hover:text-primary"
                    @click="router.visit(vehiclesRoutes.index.url())"
                >
                    <ChevronLeft
                        class="size-4 transition-transform group-hover:-translate-x-1"
                    />
                    Retour au catalogue
                </button>
            </div>

            <div class="px-4 py-4 md:px-8">
                <!-- Comparison Table -->
                <div
                    class="relative overflow-hidden rounded-4xl border border-border bg-card shadow-[0_32px_64px_-12px_rgba(0,0,0,0.1)]"
                >
                    <div class="ring-1 ring-border">
                        <!-- Grid Header -->
                        <div
                            class="grid border-collapse divide-border"
                            :style="{
                                gridTemplateColumns: `minmax(200px, 15%) repeat(${props.vehicles.length}, 1fr)`,
                            }"
                        >
                            <!-- Sticky Corner -->
                            <div
                                class="sticky left-0 z-30 flex flex-col gap-1.5 border-b border-border bg-muted/95 p-8 backdrop-blur-xl"
                            >
                                <span
                                    class="text-[9px] font-black tracking-[0.4em] text-primary uppercase"
                                    >Benchmark</span
                                >
                                <span
                                    class="text-2xl font-black tracking-tighter text-foreground uppercase"
                                    >Specs</span
                                >
                            </div>

                            <!-- Vehicle Headers -->
                            <div
                                v-for="vehicle in props.vehicles"
                                :key="vehicle.id"
                                class="flex flex-col border-b border-border bg-card/80 p-6 text-center align-top md:p-4"
                            >
                                <div class="group relative mb-8">
                                    <div
                                        class="relative aspect-16/10 overflow-hidden rounded-3xl bg-muted shadow-lg ring-1 ring-border"
                                    >
                                        <div
                                            class="flex h-full w-full items-center justify-center bg-muted/50 transition-transform duration-1000 group-hover:scale-110"
                                        >
                                            <Car
                                                class="size-20 text-muted-foreground/5"
                                            />
                                        </div>
                                        <div
                                            class="absolute inset-0 bg-linear-to-t from-black/60 via-transparent to-transparent opacity-0 transition-opacity duration-500 group-hover:opacity-100"
                                        />
                                        <div
                                            class="absolute inset-x-0 bottom-4 flex translate-y-4 items-center justify-center gap-2 opacity-0 transition-all duration-500 group-hover:translate-y-0 group-hover:opacity-100"
                                        >
                                            <Button
                                                size="sm"
                                                class="h-10 rounded-full bg-white px-6 text-[10px] font-black tracking-widest text-black uppercase hover:bg-white/90"
                                                @click="
                                                    router.visit(
                                                        vehiclesRoutes.show.url(
                                                            vehicle.id,
                                                        ),
                                                    )
                                                "
                                            >
                                                Voir l'offre
                                            </Button>
                                        </div>
                                    </div>
                                    <button
                                        @click="removeAndReload(vehicle.id)"
                                        class="absolute -top-3 -right-3 z-10 flex size-10 items-center justify-center rounded-full border border-border bg-background text-muted-foreground shadow-2xl transition-all duration-300 hover:scale-110 hover:bg-destructive hover:text-white"
                                    >
                                        <X class="size-4" />
                                    </button>
                                </div>

                                <div class="flex flex-col gap-1">
                                    <span
                                        class="text-xl leading-none font-black tracking-tighter uppercase md:text-2xl"
                                    >
                                        {{ vehicle.brand?.name }}
                                    </span>
                                    <span
                                        class="line-clamp-1 text-sm font-bold tracking-widest text-foreground uppercase md:text-base"
                                    >
                                        {{ vehicle.model?.name }}
                                    </span>
                                </div>
                                <div class="flex justify-center">
                                    <Badge
                                        variant="secondary"
                                        class="rounded-sm bg-muted/50 px-2 py-0 text-[9px] font-black tracking-widest text-muted-foreground uppercase ring-1 ring-border"
                                    >
                                        {{
                                            vehicle.vehicle_version?.name ||
                                            vehicle.vehicle_version_name
                                        }}
                                    </Badge>
                                </div>
                            </div>

                            <!-- Specs Rows -->
                            <template v-for="field in fields" :key="field.key">
                                <div class="group contents">
                                    <div
                                        class="sticky left-0 z-20 flex items-center gap-4 border-b border-border bg-muted/90 p-4 backdrop-blur-md transition-colors group-hover:bg-muted"
                                    >
                                        <div
                                            class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-background shadow-2xl ring-1 ring-border"
                                        >
                                            <component
                                                :is="field.icon"
                                                class="size-5 text-primary"
                                            />
                                        </div>
                                        <span
                                            class="text-[10px] font-black tracking-[0.2em] text-muted-foreground uppercase"
                                        >
                                            {{ field.label }}
                                        </span>
                                    </div>
                                    <div
                                        v-for="vehicle in props.vehicles"
                                        :key="`val-${vehicle.id}-${field.key}`"
                                        class="flex items-center justify-center border-b border-border p-4 text-center group-hover:bg-muted/5"
                                    >
                                        <div
                                            class="font-black tracking-tight"
                                            :class="
                                                field.highlight
                                                    ? 'text-xl text-primary'
                                                    : 'text-base text-foreground/80 group-hover:text-foreground'
                                            "
                                        >
                                            {{
                                                field.format
                                                    ? field.format(
                                                          getValue(
                                                              vehicle,
                                                              field.key,
                                                          ),
                                                      )
                                                    : getValue(
                                                          vehicle,
                                                          field.key,
                                                      )
                                            }}
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <!-- Features Categories -->
                            <template
                                v-for="category in groupedFeatures"
                                :key="category.id"
                            >
                                <!-- Category Header -->
                                <div
                                    class="col-span-full bg-muted/40 px-8 py-3"
                                >
                                    <div class="flex items-center gap-4">
                                        <div class="h-px flex-1 bg-border/50" />
                                        <span
                                            class="text-[10px] font-black tracking-[0.4em] text-muted-foreground uppercase"
                                        >
                                            {{
                                                formatOptionLabel(category.code)
                                            }}
                                        </span>
                                        <div class="h-px flex-1 bg-border/50" />
                                    </div>
                                </div>

                                <!-- Each Feature in Category -->
                                <template
                                    v-for="code in category.codes"
                                    :key="code"
                                >
                                    <div class="group contents">
                                        <div
                                            class="sticky left-0 z-20 border-b border-border bg-muted/80 p-4 backdrop-blur-md transition-colors group-hover:bg-muted"
                                        >
                                            <span
                                                class="block pl-11 text-[10px] leading-relaxed font-bold tracking-[0.2em] text-muted-foreground uppercase"
                                            >
                                                {{ formatOptionLabel(code) }}
                                            </span>
                                        </div>
                                        <div
                                            v-for="vehicle in props.vehicles"
                                            :key="`feat-${vehicle.id}-${code}`"
                                            class="flex items-center justify-center border-b border-border p-4 group-hover:bg-muted/5"
                                        >
                                            <div
                                                v-if="
                                                    vehicle.features?.some(
                                                        (f: any) =>
                                                            f.code === code,
                                                    )
                                                "
                                                class="flex size-6 items-center justify-center rounded-full bg-green-500/10 text-green-500 ring-1 ring-green-500/20"
                                            >
                                                <Check
                                                    class="size-3.5"
                                                    stroke-width="4"
                                                />
                                            </div>
                                            <div
                                                v-else
                                                class="flex size-6 items-center justify-center rounded-full bg-red-500/10 text-red-500 ring-1 ring-red-500/20"
                                            >
                                                <X
                                                    class="size-3.5"
                                                    stroke-width="4"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Footer Call to Action -->
                <div
                    v-if="props.vehicles.length < 4"
                    class="group relative mt-16 overflow-hidden rounded-[3rem] border border-white/5 bg-black p-16 transition-all duration-500 hover:border-primary/20"
                >
                    <!-- Background Decorative Pattern -->
                    <div class="absolute inset-0 opacity-5">
                        <div
                            class="absolute h-full w-full bg-[radial-gradient(#e11d48_1px,transparent_1px)] bg-size-[24px_24px]"
                        ></div>
                    </div>

                    <div
                        class="absolute inset-0 bg-linear-to-br from-primary/10 via-transparent to-transparent opacity-0 transition-opacity duration-700 group-hover:opacity-100"
                    />

                    <div
                        class="relative flex flex-col items-center justify-center text-center"
                    >
                        <div
                            class="mb-8 flex size-20 items-center justify-center rounded-3xl bg-white/5 ring-1 ring-white/10 transition-transform duration-500 group-hover:scale-110 group-hover:bg-white/10"
                        >
                            <Plus class="size-10 text-primary" />
                        </div>
                        <h3
                            class="text-4xl font-black tracking-tighter text-white uppercase italic md:text-5xl"
                        >
                            Complétez votre
                            <span class="text-primary italic">duel</span>
                        </h3>
                        <p
                            class="mt-4 max-w-lg text-lg font-medium text-white/40"
                        >
                            Il reste des places disponibles. Ajoutez d'autres
                            véhicules pour affiner votre choix et trouver la
                            perle rare.
                        </p>

                        <Button
                            size="lg"
                            class="mt-10 h-14 gap-3 rounded-full bg-primary px-10 text-base font-black tracking-widest text-white uppercase shadow-2xl transition-all duration-300 hover:scale-105 hover:bg-primary/90"
                            @click="router.visit(vehiclesRoutes.index.url())"
                        >
                            RETOUR AU CATALOGUE
                            <ArrowRight class="size-5" />
                        </Button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
@reference "../../../css/app.css";

/* Custom scrollbar for desktop comparison table */
::-webkit-scrollbar {
    height: 8px;
}
::-webkit-scrollbar-track {
    background: transparent;
}
::-webkit-scrollbar-thumb {
    @apply rounded-full bg-muted transition-colors hover:bg-muted-foreground/30;
}

/* Glass effect for sticky headers */
.sticky {
    box-shadow: 1px 0 0 0 hsl(var(--border));
}
</style>
