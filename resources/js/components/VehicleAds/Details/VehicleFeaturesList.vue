<script setup lang="ts">
import { CheckCircle } from 'lucide-vue-next';
import { useTranslation } from '@/composables/useTranslation';

defineProps<{
    groupedFeatures: any[];
    formatOptionLabel: (value?: string) => string;
}>();

const { __ } = useTranslation();
</script>

<template>
    <div v-if="groupedFeatures.length" class="rounded-lg border bg-card p-6 shadow-sm">
        <h3 class="mb-6 text-lg font-bold">
            {{ __('vehicleAd.equipment_options') }}
        </h3>
        <div class="space-y-6">
            <div
                v-for="category in groupedFeatures"
                :key="category.id"
                class="space-y-3"
            >
                <h4
                    class="text-sm font-semibold tracking-wide text-muted-foreground uppercase"
                >
                    {{ formatOptionLabel(category.code) }}
                </h4>
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                    <div
                        v-for="feature in category.features"
                        :key="feature.id"
                        class="flex items-start gap-2.5 text-sm text-foreground"
                    >
                        <CheckCircle
                            class="mt-0.5 h-4 w-4 shrink-0 text-green-600"
                        />
                        <span>{{
                            formatOptionLabel(feature.code ?? feature.key)
                        }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
