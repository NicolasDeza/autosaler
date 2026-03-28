<script setup lang="ts">
import { AlertTriangle, CheckCircle } from 'lucide-vue-next';
import { useTranslation } from '@/composables/useTranslation';

defineProps<{
    ad: any;
}>();

const { __ } = useTranslation();
</script>

<template>
    <div class="rounded-lg border bg-card p-6 shadow-sm">
        <h3 class="mb-6 text-lg font-bold">
            {{ __('vehicleAd.state_history') }}
        </h3>
        <div class="flex flex-wrap gap-2">
            <span
                class="inline-flex items-center gap-1.5 rounded-full border px-3 py-1 text-xs font-semibold"
                :class="
                    ad.is_damaged
                        ? 'border-red-200 bg-red-50 text-red-600 dark:border-red-800 dark:bg-red-950/40 dark:text-red-400'
                        : 'border-green-200 bg-green-50 text-green-700 dark:border-green-800 dark:bg-green-950/40 dark:text-green-400'
                "
            >
                <component
                    :is="ad.is_damaged ? AlertTriangle : CheckCircle"
                    class="h-3.5 w-3.5"
                />
                {{ __('vehicleAd.damaged') }}:
                {{ ad.is_damaged ? __('ui.yes') : __('ui.no') }}
            </span>
            <span
                class="inline-flex items-center gap-1.5 rounded-full border px-3 py-1 text-xs font-semibold"
                :class="
                    ad.has_accident
                        ? 'border-red-200 bg-red-50 text-red-600 dark:border-red-800 dark:bg-red-950/40 dark:text-red-400'
                        : 'border-green-200 bg-green-50 text-green-700 dark:border-green-800 dark:bg-green-950/40 dark:text-green-400'
                "
            >
                <component
                    :is="ad.has_accident ? AlertTriangle : CheckCircle"
                    class="h-3.5 w-3.5"
                />
                {{ __('vehicleAd.accident') }}:
                {{ ad.has_accident ? __('ui.yes') : __('ui.no') }}
            </span>
            <span
                class="inline-flex items-center gap-1.5 rounded-full border px-3 py-1 text-xs font-semibold"
                :class="
                    ad.complete_maintenance_book
                        ? 'border-green-200 bg-green-50 text-green-700 dark:border-green-800 dark:bg-green-950/40 dark:text-green-400'
                        : 'border-border bg-muted/50 text-muted-foreground/50'
                "
            >
                <CheckCircle class="h-3.5 w-3.5" />
                {{ __('vehicleAd.full_service_history') }}
            </span>
            <span
                class="inline-flex items-center gap-1.5 rounded-full border px-3 py-1 text-xs font-semibold"
                :class="
                    ad.non_smoker
                        ? 'border-green-200 bg-green-50 text-green-700 dark:border-green-800 dark:bg-green-950/40 dark:text-green-400'
                        : 'border-border bg-muted/50 text-muted-foreground/50'
                "
            >
                <CheckCircle class="h-3.5 w-3.5" />
                {{ __('vehicleAd.non_smoker') }}
            </span>
            <span
                class="inline-flex items-center gap-1.5 rounded-full border px-3 py-1 text-xs font-semibold"
                :class="
                    ad.technical_inspection_status
                        ? 'border-green-200 bg-green-50 text-green-700 dark:border-green-800 dark:bg-green-950/40 dark:text-green-400'
                        : 'border-border bg-muted/50 text-muted-foreground/50'
                "
            >
                <CheckCircle class="h-3.5 w-3.5" />
                {{ __('vehicleAd.technical_inspection') }}
            </span>
        </div>
    </div>
</template>
