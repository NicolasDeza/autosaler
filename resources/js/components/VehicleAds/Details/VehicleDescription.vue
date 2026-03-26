<script setup lang="ts">
import { useTranslation } from '@/composables/useTranslation';

defineProps<{
    description: string;
}>();

const { __ } = useTranslation();
</script>

<template>
    <div v-if="description" class="rounded-lg border bg-card p-6 shadow-sm">
        <h3 class="mb-4 text-lg font-bold">
            {{ __('vehicleAd.description') }}
        </h3>
        <p class="whitespace-pre-wrap text-foreground">
            {{ description }}
        </p>
    </div>
</template>
