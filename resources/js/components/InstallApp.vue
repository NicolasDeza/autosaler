<script setup lang="ts">
import { usePwaInstall } from '@/composables/usePwaInstall';

const { canInstall, install } = usePwaInstall();
</script>

<template>
    <!-- Default slot provides the install function to the parent component -->
    <slot v-if="canInstall" :install="install" :can-install="canInstall" />
</template>
