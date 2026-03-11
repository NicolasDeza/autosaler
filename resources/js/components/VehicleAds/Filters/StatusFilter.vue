<script setup lang="ts">
import FilterGroup from '@/components/VehicleAds/FilterGroup.vue';
import { Checkbox } from '@/components/ui/checkbox';
import { Label } from '@/components/ui/label';
import { computed } from 'vue';

const form = defineModel<any>('form', { required: true });

const isStatusActive = computed(
    () =>
        form.value.is_damaged !== null ||
        form.value.has_accident !== null ||
        form.value.complete_maintenance_book !== null ||
        form.value.non_smoker !== null,
);
</script>

<template>
    <FilterGroup label="État & Historique" :is-active="isStatusActive">
        <div class="space-y-4 pt-2">
            <div class="flex items-center space-x-2">
                <Checkbox
                    id="chk-damaged"
                    :checked="form.is_damaged === true"
                    @update:checked="
                        (val) => (form.is_damaged = val ? true : null)
                    "
                />
                <Label
                    for="chk-damaged"
                    class="cursor-pointer text-sm text-slate-300"
                    >Véhicule endommagé</Label
                >
            </div>
            <div class="flex items-center space-x-2">
                <Checkbox
                    id="chk-accident"
                    :checked="form.has_accident === true"
                    @update:checked="
                        (val) => (form.has_accident = val ? true : null)
                    "
                />
                <Label
                    for="chk-accident"
                    class="cursor-pointer text-sm text-slate-300"
                    >Accidenté</Label
                >
            </div>
            <div class="flex items-center space-x-2">
                <Checkbox
                    id="chk-maintenance"
                    :checked="form.complete_maintenance_book === true"
                    @update:checked="
                        (val) =>
                            (form.complete_maintenance_book = val ? true : null)
                    "
                />
                <Label
                    for="chk-maintenance"
                    class="cursor-pointer text-sm text-slate-300"
                    >Carnet entretien complet</Label
                >
            </div>
            <div class="flex items-center space-x-2">
                <Checkbox
                    id="chk-non-smoker"
                    :checked="form.non_smoker === true"
                    @update:checked="
                        (val) => (form.non_smoker = val ? true : null)
                    "
                />
                <Label
                    for="chk-non-smoker"
                    class="cursor-pointer text-sm text-slate-300"
                    >Non fumeur</Label
                >
            </div>
        </div>
    </FilterGroup>
</template>
