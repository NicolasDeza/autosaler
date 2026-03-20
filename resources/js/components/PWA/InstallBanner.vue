<script setup lang="ts">
import { Smartphone, X as CloseIcon } from 'lucide-vue-next';
import { onMounted, ref } from 'vue';
import InstallApp from '@/components/InstallApp.vue';
import { Button } from '@/components/ui/button';
import { useTranslation } from '@/composables/useTranslation';

const { __ } = useTranslation();

const isDismissed = ref(false);
const showBanner = ref(false);
const showInstructions = ref(false);

const dismiss = () => {
    isDismissed.value = true;
    localStorage.setItem('pwa_install_dismissed', 'true');
};

onMounted(() => {
    if (localStorage.getItem('pwa_install_dismissed') === 'true') {
        isDismissed.value = true;
    }

    setTimeout(() => {
        showBanner.value = true;
    }, 5000);
});
</script>

<template>
    <InstallApp v-slot="{ install, canInstall, showIosInstallMessage }">
        <div
            v-if="!isDismissed && showBanner && (canInstall || showIosInstallMessage)"
            class="dark fixed top-20 left-1/2 z-60 flex w-[calc(100%-2rem)] max-w-lg -translate-x-1/2 animate-in flex-col gap-y-3 rounded-2xl border border-white/10 bg-background p-4 shadow-2xl backdrop-blur-xl transition-all duration-500 fade-in slide-in-from-top-8"
        >
            <div v-if="showInstructions" class="space-y-3">
                <div class="flex items-center gap-x-3">
                    <div
                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white/10 shadow-inner"
                    >
                        <Smartphone class="h-5 w-5 text-white" />
                    </div>
                    <div class="flex-1">
                        <p
                            class="text-[10px] font-black tracking-widest text-white/40 uppercase"
                        >
                            {{ __('pwa.ios_guide_title') }}
                        </p>
                    </div>
                    <button
                        @click="dismiss"
                        class="flex h-9 w-9 items-center justify-center rounded-xl bg-white/5 text-white/40 transition-colors hover:bg-white/10 hover:text-white"
                    >
                        <CloseIcon class="h-4 w-4" />
                    </button>
                </div>

                <div class="space-y-2.5 px-1 py-1">
                    <div class="flex items-start gap-x-3 text-white">
                        <span
                            class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-white/10 text-[10px] font-black"
                            >1</span
                        >
                        <p class="text-xs font-medium">
                            {{ __('pwa.ios_guide_step_1') }}
                        </p>
                    </div>
                    <div class="flex items-start gap-x-3 text-white">
                        <span
                            class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-white/10 text-[10px] font-black"
                            >2</span
                        >
                        <p class="text-xs font-medium">
                            {{ __('pwa.ios_guide_step_2') }}
                        </p>
                    </div>
                </div>
            </div>

            <div v-else class="flex items-center gap-x-3">
                <div
                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white/10 shadow-inner"
                >
                    <Smartphone class="h-5 w-5 text-white" />
                </div>

                <div class="flex flex-1 flex-col overflow-hidden">
                    <p
                        class="truncate text-[10px] font-black tracking-widest text-white/40 uppercase"
                    >
                        {{ __('pwa.title') }}
                    </p>
                    <p
                        class="truncate text-xs leading-tight font-bold text-white sm:whitespace-normal"
                    >
                        {{ __('pwa.message') }}
                    </p>
                </div>

                <div class="flex items-center gap-2">
                    <Button
                        variant="secondary"
                        size="sm"
                        class="h-9 cursor-pointer rounded-xl bg-primary px-4 text-[10px] font-black tracking-widest text-white uppercase shadow-lg shadow-primary/20 transition-all hover:scale-105 hover:bg-primary/90 active:scale-95"
                        @click="
                            showIosInstallMessage
                                ? (showInstructions = true)
                                : install()
                        "
                    >
                        {{ __('pwa.button') }}
                    </Button>

                    <button
                        @click="dismiss"
                        class="flex h-9 w-9 items-center justify-center rounded-xl bg-white/5 text-white/40 transition-colors hover:bg-white/10 hover:text-white"
                    >
                        <CloseIcon class="h-4 w-4" />
                    </button>
                </div>
            </div>
        </div>
    </InstallApp>
</template>
