import { reactive, watch } from 'vue';

interface VehicleSummary {
    id: number;
    brand: string;
    model: string;
    vehicle_version_name?: string;
    price: number;
    image?: string;
}

interface ComparisonState {
    vehicles: VehicleSummary[];
}

const STORAGE_KEY = 'autosaler_comparison_selection';

const state = reactive<ComparisonState>({
    vehicles:
        typeof window !== 'undefined'
            ? JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]')
            : [],
});

watch(
    () => state.vehicles,
    (newVehicles) => {
        if (typeof window !== 'undefined') {
            localStorage.setItem(STORAGE_KEY, JSON.stringify(newVehicles));
        }
    },
    { deep: true },
);

export function useComparison() {
    const addVehicle = (vehicle: VehicleSummary) => {
        if (state.vehicles.length >= 4) {
            return false;
        }
        const numericId = Number(vehicle.id);
        if (!state.vehicles.find((v) => Number(v.id) === numericId)) {
            state.vehicles.push({ ...vehicle, id: numericId });
        }
        return true;
    };

    const removeVehicle = (id: number | string) => {
        const numericId = Number(id);
        const index = state.vehicles.findIndex(
            (v) => Number(v.id) === numericId,
        );
        if (index !== -1) {
            state.vehicles.splice(index, 1);
        }
    };

    const clearSelection = () => {
        state.vehicles.splice(0, state.vehicles.length);
    };

    const isSelected = (id: number | string) => {
        return state.vehicles.some((v) => Number(v.id) === Number(id));
    };

    return {
        state,
        addVehicle,
        removeVehicle,
        clearSelection,
        isSelected,
    };
}
