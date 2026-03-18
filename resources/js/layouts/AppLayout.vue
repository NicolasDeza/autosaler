<script setup lang="ts">
import ComparisonBar from '@/components/VehicleAds/ComparisonBar.vue';
import AppLayout from '@/layouts/app/AppHeaderLayout.vue';
import type { BreadcrumbItem } from '@/types';

type Props = {
    breadcrumbs?: BreadcrumbItem[];
};

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <template v-if="$slots['full-width']" #full-width>
            <slot name="full-width" />
        </template>
        <slot />
    </AppLayout>

    <ComparisonBar />
</template>
