<script setup lang="ts">
import { X } from 'lucide-vue-next';
import { computed } from 'vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';

const props = defineProps<{
    form: any;
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
    currentYear: number;
}>();

const emit = defineEmits([
    'resetAll',
    'updateFilter',
    'updatePrice',
    'updateYear',
]);

const activeFilters = computed(() => {
    const filters: { key: string; label: string; onRemove: () => void }[] = [];
    const v = props.form;

    // Brand
    if (v.brand_id && v.brand_id !== 'all') {
        const brand = props.brands?.find(
            (b: any) => String(b.id) === String(v.brand_id),
        );
        filters.push({
            key: 'brand_id',
            label: `Marque: ${brand?.name || v.brand_id}`,
            onRemove: () => emit('updateFilter', 'brand_id', 'all'),
        });
    }

    // Model
    if (v.model_id && v.model_id !== 'all') {
        const model = props.models?.find(
            (m: any) => String(m.id) === String(v.model_id),
        );
        filters.push({
            key: 'model_id',
            label: `Modèle: ${model?.name || v.model_id}`,
            onRemove: () => emit('updateFilter', 'model_id', 'all'),
        });
    }

    // City
    if (v.city) {
        filters.push({
            key: 'city',
            label: `Ville: ${v.city}`,
            onRemove: () => emit('updateFilter', 'city', ''),
        });
    }

    // Price
    if (Number(v.min_price) > 0 || Number(v.max_price) < 200000) {
        filters.push({
            key: 'price',
            label: `Prix: ${v.min_price}€ - ${v.max_price >= 200000 ? '200k+' : v.max_price + '€'}`,
            onRemove: () => emit('updatePrice', [0, 200000]),
        });
    }

    // Year
    if (Number(v.min_year) > 1980 || Number(v.max_year) < props.currentYear) {
        filters.push({
            key: 'year',
            label: `Année: ${v.min_year} - ${v.max_year}`,
            onRemove: () => emit('updateYear', [1980, props.currentYear]),
        });
    }

    // Mileage
    if (v.max_mileage && v.max_mileage !== 'all') {
        filters.push({
            key: 'max_mileage',
            label: `Kilométrage max: ${v.max_mileage} km`,
            onRemove: () => emit('updateFilter', 'max_mileage', 'all'),
        });
    }

    // Fuel types
    if (Array.isArray(v.fuel_types)) {
        v.fuel_types.forEach((id: string) => {
            const type = props.fuelTypes?.find(
                (t: any) => String(t.id) === String(id),
            );
            filters.push({
                key: `fuel_type_${id}`,
                label: `Carburant: ${type?.code || id}`,
                onRemove: () => {
                    const newValue = v.fuel_types.filter(
                        (t: string) => String(t) !== String(id),
                    );
                    emit('updateFilter', 'fuel_types', newValue);
                },
            });
        });
    }

    // Body types
    if (Array.isArray(v.body_types)) {
        v.body_types.forEach((id: string) => {
            const type = props.bodyTypes?.find(
                (t: any) => String(t.id) === String(id),
            );
            filters.push({
                key: `body_type_${id}`,
                label: `Carrosserie: ${type?.code || id}`,
                onRemove: () => {
                    const newValue = v.body_types.filter(
                        (t: string) => String(t) !== String(id),
                    );
                    emit('updateFilter', 'body_types', newValue);
                },
            });
        });
    }

    // Transmission types
    if (Array.isArray(v.transmission_types)) {
        v.transmission_types.forEach((id: string) => {
            const type = props.transmissionTypes?.find(
                (t: any) => String(t.id) === String(id),
            );
            filters.push({
                key: `transmission_type_${id}`,
                label: `Transmission: ${type?.code || id}`,
                onRemove: () => {
                    const newValue = v.transmission_types.filter(
                        (t: string) => String(t) !== String(id),
                    );
                    emit('updateFilter', 'transmission_types', newValue);
                },
            });
        });
    }

    // Exterior color
    if (v.exterior_color_id && v.exterior_color_id !== 'all') {
        const color = props.exteriorColors?.find(
            (c: any) => String(c.id) === String(v.exterior_color_id),
        );
        filters.push({
            key: 'exterior_color_id',
            label: `Couleur: ${color?.code || v.exterior_color_id}`,
            onRemove: () => emit('updateFilter', 'exterior_color_id', 'all'),
        });
    }

    // Euro norm
    if (v.euro_norm_id && v.euro_norm_id !== 'all') {
        const norm = props.euroNorms?.find(
            (n: any) => String(n.id) === String(v.euro_norm_id),
        );
        filters.push({
            key: 'euro_norm_id',
            label: `Norme Euro: ${norm?.code || v.euro_norm_id}`,
            onRemove: () => emit('updateFilter', 'euro_norm_id', 'all'),
        });
    }

    // Interior color
    if (v.interior_color_id && v.interior_color_id !== 'all') {
        const color = props.interiorColors?.find(
            (c: any) => String(c.id) === String(v.interior_color_id),
        );
        filters.push({
            key: 'interior_color_id',
            label: `Coul. Int.: ${color?.code || v.interior_color_id}`,
            onRemove: () => emit('updateFilter', 'interior_color_id', 'all'),
        });
    }

    // Interior type
    if (v.interior_type_id && v.interior_type_id !== 'all') {
        const type = props.interiorTypes?.find(
            (t: any) => String(t.id) === String(v.interior_type_id),
        );
        filters.push({
            key: 'interior_type_id',
            label: `Mat. Int.: ${type?.code || v.interior_type_id}`,
            onRemove: () => emit('updateFilter', 'interior_type_id', 'all'),
        });
    }

    // Doors
    if (v.doors && v.doors !== 'all') {
        filters.push({
            key: 'doors',
            label: `Portes: ${v.doors}`,
            onRemove: () => emit('updateFilter', 'doors', 'all'),
        });
    }

    // Seats
    if (v.seats && v.seats !== 'all') {
        filters.push({
            key: 'seats',
            label: `Sièges: ${v.seats}`,
            onRemove: () => emit('updateFilter', 'seats', 'all'),
        });
    }

    // Booleans
    if (v.is_damaged === true) {
        filters.push({
            key: 'is_damaged',
            label: 'Véhicule endommagé',
            onRemove: () => emit('updateFilter', 'is_damaged', null),
        });
    }
    if (v.has_accident === true) {
        filters.push({
            key: 'has_accident',
            label: 'Accidenté',
            onRemove: () => emit('updateFilter', 'has_accident', null),
        });
    }
    if (v.complete_maintenance_book === true) {
        filters.push({
            key: 'complete_maintenance_book',
            label: 'Carnet entretien complet',
            onRemove: () =>
                emit('updateFilter', 'complete_maintenance_book', null),
        });
    }
    if (v.non_smoker === true) {
        filters.push({
            key: 'non_smoker',
            label: 'Non fumeur',
            onRemove: () => emit('updateFilter', 'non_smoker', null),
        });
    }

    // Favorites
    if (v.favorites_only === true) {
        filters.push({
            key: 'favorites_only',
            label: 'Favoris uniquement',
            onRemove: () => emit('updateFilter', 'favorites_only', false),
        });
    }

    // Version Name
    if (v.version) {
        filters.push({
            key: 'version',
            label: `Version: ${v.version}`,
            onRemove: () => emit('updateFilter', 'version', ''),
        });
    }

    // Power
    if (v.min_power || v.max_power) {
        const factor = 1.35962;
        let label = 'Puissance: ';
        const isCh = v.power_unit === 'ch';
        const unitLabel = isCh ? 'ch' : 'kW';

        const minVal = isCh
            ? v.min_power
                ? Math.round(Number(v.min_power) * factor)
                : null
            : v.min_power;
        const maxVal = isCh
            ? v.max_power
                ? Math.round(Number(v.max_power) * factor)
                : null
            : v.max_power;

        if (minVal && maxVal) {
            label += `${minVal} - ${maxVal} ${unitLabel}`;
        } else if (minVal) {
            label += `min ${minVal} ${unitLabel}`;
        } else if (maxVal) {
            label += `max ${maxVal} ${unitLabel}`;
        }

        filters.push({
            key: 'power',
            label: label,
            onRemove: () => {
                emit('updateFilter', 'min_power', '');
                emit('updateFilter', 'max_power', '');
            },
        });
    }

    // Features
    if (Array.isArray(v.features) && v.features.length > 0) {
        v.features.forEach((id: string) => {
            const feature = props.features?.find(
                (f: any) => String(f.id) === String(id),
            );
            filters.push({
                key: `feature_${id}`,
                label: `Equip.: ${feature?.code || feature?.key || id}`,
                onRemove: () => {
                    const newValue = v.features.filter(
                        (f: string) => String(f) !== String(id),
                    );
                    emit('updateFilter', 'features', newValue);
                },
            });
        });
    }

    return filters;
});
</script>

<template>
    <Transition
        enter-from-class="opacity-0 max-h-0 -translate-y-2"
        enter-active-class="transition-all duration-400 ease-in-out"
        enter-to-class="opacity-100 max-h-[500px] translate-y-0"
        leave-from-class="opacity-100 max-h-[500px] translate-y-0"
        leave-active-class="transition-all duration-400 ease-in-out"
        leave-to-class="opacity-0 max-h-0 -translate-y-2"
    >
        <div v-if="activeFilters.length > 0" class="overflow-hidden">
            <TransitionGroup
                tag="div"
                class="flex flex-wrap gap-2 py-1"
                enter-from-class="opacity-0 scale-90"
                enter-active-class="transition-all duration-300 ease-out"
                enter-to-class="opacity-100 scale-100"
                leave-from-class="opacity-100 scale-100"
                leave-active-class="transition-all duration-300 ease-in absolute"
                leave-to-class="opacity-0 scale-90"
                move-class="transition-transform duration-300"
            >
                <Badge
                    v-for="filter in activeFilters"
                    :key="filter.key"
                    variant="secondary"
                    class="flex items-center gap-1 rounded-full px-3 py-1 text-xs transition-colors"
                >
                    {{ filter.label }}
                    <button
                        @click="filter.onRemove"
                        class="ml-1 cursor-pointer rounded-full p-0.5 transition-colors hover:bg-muted"
                    >
                        <X class="h-3 w-3" />
                    </button>
                </Badge>
                <Button
                    key="reset-button"
                    variant="ghost"
                    size="sm"
                    class="h-7 px-2 text-xs text-muted-foreground hover:text-foreground"
                    @click="emit('resetAll')"
                >
                    Tout effacer
                </Button>
            </TransitionGroup>
        </div>
    </Transition>
</template>
