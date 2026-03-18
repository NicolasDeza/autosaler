<script setup lang="ts">
import { Zap } from 'lucide-vue-next';
import { ref, watch } from 'vue';
import { Input } from '@/components/ui/input';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import FilterGroup from '@/components/VehicleAds/FilterGroup.vue';

const form = defineModel<any>('form', { required: true });

const displayMin = ref('');
const displayMax = ref('');

// Facteur exact : 1 kW = 1.35962 ch
const FACTOR = 1.35962;

const kwToCh = (kw: number | string) => {
    if (!kw) return '';
    return Math.round(Number(kw) * FACTOR).toString();
};

const chToKw = (ch: number | string) => {
    if (!ch) return '';
    // On garde 2 décimales pour éviter les erreurs d'arrondi au retour
    return (Number(ch) / FACTOR).toFixed(2);
};

// Mise à jour des valeurs affichées sans casser la saisie en cours
const updateDisplayValues = () => {
    if (form.value.power_unit === 'kw') {
        const valMin = form.value.min_power || '';
        const valMax = form.value.max_power || '';
        // On n'affiche pas les .00 inutiles si on est en kW
        displayMin.value = valMin ? parseFloat(valMin).toString() : '';
        displayMax.value = valMax ? parseFloat(valMax).toString() : '';
    } else {
        displayMin.value = kwToCh(form.value.min_power);
        displayMax.value = kwToCh(form.value.max_power);
    }
};

// On surveille les changements externes (reset, etc.)
watch(
    () => [form.value.min_power, form.value.max_power],
    (newVals, oldVals) => {
        if (JSON.stringify(newVals) !== JSON.stringify(oldVals)) {
            updateDisplayValues();
        }
    },
    { immediate: true },
);

const onMinInput = (val: string | number) => {
    const stringVal = String(val);
    displayMin.value = stringVal;
    if (!stringVal) {
        form.value.min_power = '';
        return;
    }
    form.value.min_power =
        form.value.power_unit === 'kw' ? stringVal : chToKw(stringVal);
};

const onMaxInput = (val: string | number) => {
    const stringVal = String(val);
    displayMax.value = stringVal;
    if (!stringVal) {
        form.value.max_power = '';
        return;
    }
    form.value.max_power =
        form.value.power_unit === 'kw' ? stringVal : chToKw(stringVal);
};

// Conversion lors du changement d'unité
watch(() => form.value.power_unit, updateDisplayValues);
</script>

<template>
    <FilterGroup
        label="Puissance"
        :icon="Zap"
        :is-active="!!form.min_power || !!form.max_power"
    >
        <div class="space-y-4 pt-2">
            <div class="flex flex-col gap-2">
                <Select v-model="form.power_unit">
                    <SelectTrigger class="h-9 w-full bg-card">
                        <SelectValue />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem value="kw">Kilowatts (kW)</SelectItem>
                        <SelectItem value="ch">Chevaux (ch)</SelectItem>
                    </SelectContent>
                </Select>

                <div class="flex items-center gap-2">
                    <Input
                        :model-value="displayMin"
                        type="number"
                        placeholder="Min"
                        class="h-9 bg-card"
                        @update:model-value="onMinInput"
                    />
                    <span class="text-muted-foreground">-</span>
                    <Input
                        :model-value="displayMax"
                        type="number"
                        placeholder="Max"
                        class="h-9 bg-card"
                        @update:model-value="onMaxInput"
                    />
                </div>
            </div>
        </div>
    </FilterGroup>
</template>
