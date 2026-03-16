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
        if (!state.vehicles.find((v) => v.id === vehicle.id)) {
            state.vehicles.push(vehicle);
        }
        return true;
    };

    const removeVehicle = (id: number) => {
        const index = state.vehicles.findIndex((v) => v.id === id);
        if (index !== -1) {
            state.vehicles.splice(index, 1);
        }
    };

    const clearSelection = () => {
        state.vehicles.splice(0, state.vehicles.length);
    };

    const isSelected = (id: number) => {
        return state.vehicles.some((v) => v.id === id);
    };

    return {
        state,
        addVehicle,
        removeVehicle,
        clearSelection,
        isSelected,
    };
}
