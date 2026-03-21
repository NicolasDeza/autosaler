<template>
    <!-- Desktop: sticky sidebar inspired by FilterSidebar -->
    <aside
        v-if="!mobileOnly"
        class="sticky top-24 hidden h-fit w-56 shrink-0 overflow-hidden rounded-lg bg-card shadow-lg lg:block lg:w-1/4"
    >
        <!-- Header (dark like FilterSidebar) -->
        <div
            class="dark relative flex items-center gap-2.5 bg-background px-5 py-4"
        >
            <BarChart2 class="h-4 w-4 text-primary" />
            <h3 class="text-base font-bold tracking-wide text-white">
                {{ __('vehicleAd.form_progress') }}
            </h3>
        </div>
        <div class="h-px bg-border" />

        <!-- Section list -->
        <div class="bg-card p-3">
            <div class="space-y-0.5">
                <button
                    v-for="section in sections"
                    :key="section.id"
                    type="button"
                    class="group flex w-full cursor-pointer items-center gap-3 rounded-md px-3 py-2.5 text-left transition-all hover:bg-muted/60"
                    :class="{
                        'bg-primary/8': activeSection === section.id,
                    }"
                    @click="scrollTo(section.id)"
                >
                    <!-- Icon circle -->
                    <div
                        class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full border-2 transition-all"
                        :class="
                            section.completion === 100
                                ? 'border-green-500 bg-green-500/10 text-green-600 dark:border-green-400 dark:text-green-400'
                                : section.completion > 0
                                  ? 'border-primary bg-primary/10 text-primary'
                                  : 'border-border bg-muted/50 text-muted-foreground/30'
                        "
                    >
                        <component
                            :is="section.icon"
                            class="h-3.5 w-3.5"
                        />
                    </div>

                    <!-- Label + bar -->
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center justify-between">
                            <p
                                class="truncate text-xs leading-tight font-semibold"
                                :class="
                                    activeSection === section.id
                                        ? 'text-primary'
                                        : 'text-foreground/80'
                                "
                            >
                                {{ section.label }}
                            </p>
                            <span
                                class="ml-1 shrink-0 text-[10px] font-bold tabular-nums"
                                :class="
                                    section.completion === 100
                                        ? 'text-green-600 dark:text-green-400'
                                        : section.completion > 0
                                          ? 'text-primary'
                                          : 'text-muted-foreground/40'
                                "
                            >
                                {{ section.completion }}%
                            </span>
                        </div>
                        <div
                            class="mt-1.5 h-1 overflow-hidden rounded-full bg-muted"
                        >
                            <div
                                class="h-full rounded-full transition-all duration-500"
                                :class="
                                    section.completion === 100
                                        ? 'bg-green-500'
                                        : 'bg-primary'
                                "
                                :style="{ width: `${section.completion}%` }"
                            />
                        </div>
                    </div>
                </button>
            </div>

            <!-- Global completion footer -->
            <div class="mt-2 border-t border-border/50 pt-3">
                <div class="flex items-center justify-between px-3 pb-1">
                    <span
                        class="text-[10px] font-bold tracking-widest text-muted-foreground uppercase"
                    >
                        {{ __('vehicleAd.global_completion') }}
                    </span>
                    <span
                        class="text-sm font-black tabular-nums"
                        :class="
                            globalCompletion === 100
                                ? 'text-green-600 dark:text-green-400'
                                : 'text-primary'
                        "
                    >
                        {{ globalCompletion }}%
                    </span>
                </div>
                <div class="px-3">
                    <div class="h-2 overflow-hidden rounded-full bg-muted">
                        <div
                            class="h-full rounded-full transition-all duration-700"
                            :class="
                                globalCompletion === 100
                                    ? 'bg-green-500'
                                    : 'bg-primary'
                            "
                            :style="{ width: `${globalCompletion}%` }"
                        />
                    </div>
                </div>
            </div>
        </div>
    </aside>

    <!-- Mobile: compact strip (only if not desktopOnly) -->
    <div
        v-if="!desktopOnly"
        class="dark relative z-40 border-t border-border/40 bg-background px-4 py-3 shadow-2xl backdrop-blur-md lg:hidden"
    >
        <div class="flex items-center gap-3">
            <!-- Section icon dots -->
            <div
                class="scrollbar-none flex flex-1 items-center gap-2 overflow-x-auto"
            >
                <button
                    v-for="section in sections"
                    :key="section.id"
                    type="button"
                    class="group flex shrink-0 cursor-pointer flex-col items-center gap-1.5 rounded-md px-1 py-0.5 transition-all"
                    :class="{ 'bg-primary/10': activeSection === section.id }"
                    :title="section.label"
                    @click="scrollTo(section.id)"
                >
                    <div
                        class="flex h-7 w-7 items-center justify-center rounded-full border-2 transition-all"
                        :class="
                            section.completion === 100
                                ? 'border-green-500 bg-green-500/10 text-green-400'
                                : section.completion > 0
                                  ? 'border-primary bg-primary/20 text-primary'
                                  : 'border-border/60 bg-white/5 text-white/30'
                        "
                    >
                        <component
                            :is="section.icon"
                            class="h-3.5 w-3.5"
                        />
                    </div>
                    <div
                        class="h-1 w-8 overflow-hidden rounded-full bg-white/10"
                    >
                        <div
                            class="h-full rounded-full transition-all duration-500"
                            :class="
                                section.completion === 100
                                    ? 'bg-green-500'
                                    : 'bg-primary'
                            "
                            :style="{ width: `${section.completion}%` }"
                        />
                    </div>
                </button>
            </div>

            <!-- Global % -->
            <div class="shrink-0 border-l border-border/50 pl-4 text-right">
                <span
                    class="text-base font-black tabular-nums leading-none text-white"
                    :class="{
                        'text-green-400': globalCompletion === 100,
                        'text-primary': globalCompletion < 100,
                    }"
                >
                    {{ globalCompletion }}%
                </span>
                <p
                    class="mt-1 text-[8px] font-bold tracking-widest text-white/50 uppercase"
                >
                    {{ __('vehicleAd.form_progress') }}
                </p>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { BarChart2 } from 'lucide-vue-next';
import { type Component } from 'vue';
import { useTranslation } from '@/composables/useTranslation';

const { __ } = useTranslation();

export type FormSection = {
    id: string;
    label: string;
    icon: Component;
    completion: number;
};

defineProps<{
    sections: FormSection[];
    activeSection?: string;
    globalCompletion: number;
    desktopOnly?: boolean;
    mobileOnly?: boolean;
}>();

const scrollTo = (sectionId: string): void => {
    const el = document.getElementById(sectionId);
    if (el) {
        const headerOffset = 100;
        const elementPosition = el.getBoundingClientRect().top;
        const offsetPosition =
            elementPosition + window.pageYOffset - headerOffset;

        window.scrollTo({
            top: offsetPosition,
            behavior: 'smooth',
        });
    }
};
</script>
