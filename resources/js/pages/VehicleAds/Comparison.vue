<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { ChevronLeft, X, Car, Info } from 'lucide-vue-next';
import { computed } from 'vue';
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import vehiclesRoutes from '@/routes/vehicles';

const props = defineProps<{
    vehicles: any[];
}>();

const fields = [
    { label: 'Prix', key: 'price', format: (v: any) => `${Number(v).toLocaleString('fr-FR')} €` },
    { label: 'Année', key: 'first_registration_date', format: (v: any) => v?.substring(0, 4) || 'N/A' },
    { label: 'Kilométrage', key: 'mileage', format: (v: any) => `${v?.toLocaleString('fr-FR')} km` },
    { label: 'Carburant', key: 'fuel_type.code' },
    { label: 'Boîte', key: 'transmission_type.code' },
    { label: 'Puissance', key: 'power_kw', format: (v: any) => v ? `${v} kW` : 'N/A' },
    { label: 'Carrosserie', key: 'body_type.code' },
    { label: 'Norme Euro', key: 'euro_norm.code' },
    { label: 'Couleur', key: 'exterior_color.code' },
    { label: 'Portes / Sièges', key: 'doors', format: (_: any, item: any) => `${item.doors} / ${item.seats}` },
];

const getValue = (item: any, path: string) => {
    return path.split('.').reduce((obj, key) => obj?.[key], item) ?? 'N/A';
};

const allFeatureCodes = computed(() => {
    const codes = new Set<string>();
    props.vehicles.forEach(v => {
        v.features?.forEach((f: any) => codes.add(f.code));
    });
    return Array.from(codes).sort();
});

const removeAndReload = (id: number) => {
    const newIds = props.vehicles
        .filter(v => v.id !== id)
        .map(v => v.id)
        .join(',');
    
    if (!newIds) {
        router.visit(vehiclesRoutes.index.url());
        return;
    }
    
    router.visit(`${vehiclesRoutes.index.url()}/compare?ids=${newIds}`, {
        preserveScroll: true,
        replace: true
    });
};
</script>

<template>
    <Head title="Comparaison de véhicules" />

    <AppLayout>
        <div class="mx-auto w-full max-w-7xl space-y-8 p-4 md:p-8">
            <div class="flex items-center justify-between">
                <button
                    class="flex cursor-pointer items-center gap-1.5 text-sm text-muted-foreground transition-colors hover:text-foreground"
                    @click="router.visit(vehiclesRoutes.index.url())"
                >
                    <ChevronLeft class="h-4 w-4" />
                    Retour aux annonces
                </button>
                <h1 class="text-2xl font-bold">Comparaison ({{ vehicles.length }})</h1>
            </div>

            <Card class="overflow-hidden border-border bg-card shadow-xl">
                <div class="overflow-x-auto">
                    <Table>
                        <TableHeader>
                            <TableRow class="hover:bg-transparent">
                                <TableHead class="w-48 bg-muted/30 font-bold uppercase tracking-wider">
                                    Caractéristiques
                                </TableHead>
                                <TableHead
                                    v-for="vehicle in vehicles"
                                    :key="vehicle.id"
                                    class="min-w-64 p-6 text-center"
                                >
                                    <div class="relative group mx-auto mb-4 aspect-video w-full overflow-hidden rounded-lg bg-muted shadow-inner">
                                        <div class="flex h-full w-full items-center justify-center bg-muted">
                                            <Car class="size-12 text-muted-foreground/20" />
                                        </div>
                                        <button
                                            @click="removeAndReload(vehicle.id)"
                                            class="absolute top-2 right-2 flex size-8 items-center justify-center rounded-full bg-destructive text-white opacity-0 transition-all hover:scale-110 group-hover:opacity-100"
                                        >
                                            <X class="size-4" />
                                        </button>
                                    </div>
                                    <h3 class="text-lg font-black tracking-tight text-foreground uppercase">
                                        {{ vehicle.brand?.name }} {{ vehicle.model?.name }}
                                    </h3>
                                    <p class="text-xs font-bold text-muted-foreground uppercase mt-1">
                                        {{ vehicle.vehicle_version?.name || vehicle.vehicle_version_name }}
                                    </p>
                                    <Button
                                        variant="outline"
                                        size="sm"
                                        class="mt-4 border-primary/20 hover:bg-primary/5 hover:text-primary"
                                        @click="router.visit(vehiclesRoutes.show.url(vehicle.id))"
                                    >
                                        Voir l'annonce
                                    </Button>
                                </TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="field in fields" :key="field.key" class="group transition-colors">
                                <TableCell class="bg-muted/30 py-4 font-bold text-muted-foreground uppercase text-[11px] tracking-widest">
                                    {{ field.label }}
                                </TableCell>
                                <TableCell
                                    v-for="vehicle in vehicles"
                                    :key="vehicle.id"
                                    class="text-center font-semibold"
                                    :class="{ 'text-primary text-xl font-black': field.key === 'price' }"
                                >
                                    {{ field.format ? field.format(getValue(vehicle, field.key), vehicle) : getValue(vehicle, field.key) }}
                                </TableCell>
                            </TableRow>

                            <!-- Features Header -->
                            <TableRow class="bg-muted/50">
                                <TableCell colspan="100%" class="py-3 px-6 text-xs font-black uppercase tracking-[0.2em] text-foreground">
                                    Équipements & Options
                                </TableCell>
                            </TableRow>

                            <TableRow v-for="code in allFeatureCodes" :key="code" class="group transition-colors">
                                <TableCell class="bg-muted/30 py-4 font-bold text-muted-foreground uppercase text-[10px] tracking-widest">
                                    {{ code.replace(/[_-]+/g, ' ') }}
                                </TableCell>
                                <TableCell
                                    v-for="vehicle in vehicles"
                                    :key="vehicle.id"
                                    class="text-center"
                                >
                                    <div
                                        class="mx-auto flex size-6 items-center justify-center rounded-full border"
                                        :class="vehicle.features?.some((f: any) => f.code === code) 
                                            ? 'border-green-500 bg-green-50 text-green-600' 
                                            : 'border-border bg-muted/20 text-muted-foreground/30'"
                                    >
                                        <svg
                                            v-if="vehicle.features?.some((f: any) => f.code === code)"
                                            class="size-4"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="3"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                        >
                                            <polyline points="20 6 9 17 4 12" />
                                        </svg>
                                        <X v-else class="size-3" />
                                    </div>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </div>
            </Card>

            <div v-if="vehicles.length < 4" class="flex flex-col items-center justify-center rounded-xl border border-dashed border-border py-12 text-center bg-muted/10">
                <Info class="mb-4 size-8 text-muted-foreground/40" />
                <h3 class="text-sm font-bold text-foreground uppercase tracking-widest">Ajouter d'autres véhicules</h3>
                <p class="mt-2 text-sm text-muted-foreground">Vous pouvez comparer jusqu'à 4 véhicules simultanément.</p>
                <Button
                    variant="link"
                    class="mt-4 text-primary font-bold"
                    @click="router.visit(vehiclesRoutes.index.url())"
                >
                    Retourner à la liste
                </Button>
            </div>
        </div>
    </AppLayout>
</template>
