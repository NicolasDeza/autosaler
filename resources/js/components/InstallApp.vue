<script setup lang="ts">
import { usePwaInstall } from '@/composables/usePwaInstall';

const { canInstall, install, showIOSInstallMessage } = usePwaInstall();
</script>

<template>
    <!-- Default slot provides the install function to the parent component -->
    <slot
        :install="install"
        :can-install="canInstall"
        :show-ios-install-message="showIOSInstallMessage"
    />
</template>
