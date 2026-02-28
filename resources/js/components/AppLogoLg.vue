<script setup lang="ts">
import AppLogoIcon from '@/components/AppLogoIcon.vue';
</script>

<template>
    <div
        class="flex aspect-square size-12 items-center justify-center rounded-md bg-transparent text-sidebar-primary-foreground"
    >
        <AppLogoIcon class="size-6 fill-current text-white dark:text-black" />
    </div>
    <div class="ml-1 grid flex-1 text-left text-3xl">
        <div
            class="flex items-center justify-center gap-0.5 truncate leading-tight font-semibold uppercase"
        >
            <span class="text-red-500"> Auto </span>
            <span>Saler</span>
        </div>
    </div>
</template>
