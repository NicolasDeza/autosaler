<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import HeroSection from '@/components/HeroSection.vue'
import AppLayout from '@/layouts/AppLayout.vue';
</script>

<template>
  <Head title="Autosaler" />

  <AppLayout>
    <template #full-width>
      <HeroSection />
    </template>
  </AppLayout>
</template>
