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
                    class="group flex w-full cursor-pointer items-center gap-3 rounded-md px-3 py-2.5 text-left transition-all"
                    :class="
                        activeSection === section.id
                            ? 'bg-secondary/6 shadow-sm ring-1 ring-border'
                            : 'hover:bg-secondary/5'
                    "
                    @click="scrollTo(section.id)"
                >
                    <!-- Icon circle -->
                    <div
                        class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full border-2 transition-all"
                        :class="[
                            section.completion === 100
                                ? 'border-green-500 bg-green-500/10 text-green-600 dark:border-green-400 dark:text-green-400'
                                : section.completion > 0
                                  ? 'border-primary/60 bg-primary/20 text-primary shadow-[0_0_10px_rgba(239,68,68,0.1)]'
                                  : activeSection === section.id
                                    ? 'border-foreground/5 bg-foreground/10 text-foreground'
                                    : 'border-muted bg-muted/30 text-muted-foreground/30',
                            activeSection === section.id
                                ? 'scale-110 shadow-md ring-2 ring-white/10'
                                : '',
                        ]"
                    >
                        <component :is="section.icon" class="h-3.5 w-3.5" />
                    </div>

                    <!-- Label + bar -->
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center justify-between">
                            <p
                                class="truncate text-xs leading-tight transition-all duration-300"
                                :class="
                                    activeSection === section.id
                                        ? 'font-black text-foreground'
                                        : 'font-semibold text-muted-foreground/80'
                                "
                            >
                                {{ section.label }}
                            </p>
                            <span
                                class="ml-1 shrink-0 text-[10px] tabular-nums transition-all duration-300"
                                :class="[
                                    section.completion === 100
                                        ? 'font-bold text-green-600 dark:text-green-400'
                                        : section.completion > 0
                                          ? 'font-bold text-primary'
                                          : 'font-medium text-muted-foreground/40',
                                    activeSection === section.id
                                        ? 'scale-110'
                                        : '',
                                ]"
                            >
                                {{ section.completion }}%
                            </span>
                        </div>
                        <div
                            class="mt-1.5 h-1 overflow-hidden rounded-full bg-muted/40"
                        >
                            <div
                                class="h-full rounded-full transition-all duration-500"
                                :class="
                                    section.completion === 100
                                        ? 'bg-green-500 shadow-[0_0_8px_rgba(34,197,94,0.4)]'
                                        : section.completion > 0
                                          ? 'bg-primary'
                                          : activeSection === section.id
                                            ? 'bg-foreground'
                                            : ''
                                "
                                :style="{ width: `${section.completion}%` }"
                            />
                        </div>
                    </div>
                </button>
            </div>
        </div>

        <!-- Global completion footer -->
        <div class="mt-2 border-t border-border/50 bg-muted/5 pt-3">
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
                            : 'text-foreground'
                    "
                >
                    {{ globalCompletion }}%
                </span>
            </div>
            <div class="px-3 pb-3">
                <div class="h-2 overflow-hidden rounded-full bg-muted/30">
                    <div
                        class="h-full rounded-full transition-all duration-700"
                        :class="
                            globalCompletion === 100
                                ? 'bg-green-500 shadow-[0_0_10px_rgba(34,197,94,0.3)]'
                                : 'bg-primary/80 shadow-[0_0_10px_rgba(239,68,68,0.2)]'
                        "
                        :style="{ width: `${globalCompletion}%` }"
                    />
                </div>
            </div>
        </div>
    </aside>

    <!-- Mobile: sleek segmented progress navigation -->
    <div v-if="!desktopOnly" class="dark relative z-40 px-2 py-1.5 lg:hidden">
        <div class="flex flex-col">
            <!-- Header: Current Step & Global % -->
            <div class="flex items-end justify-between px-0.5 pb-2">
                <div class="flex flex-col">
                    <span
                        class="text-[8px] font-bold tracking-widest text-white/40 uppercase"
                    >
                        {{ __('vehicleAd.form_progress') }}
                    </span>
                    <span
                        class="text-[11px] font-black tabular-nums transition-colors"
                        :class="
                            globalCompletion === 100 ||
                            (activeSectionData?.completion ?? 0) === 100
                                ? 'text-green-400'
                                : (activeSectionData?.completion ?? 0) > 0
                                  ? 'text-primary'
                                  : 'text-white'
                        "
                    >
                        {{ activeSectionData?.label ?? '' }}
                    </span>
                </div>
                <div class="flex items-baseline gap-1">
                    <span
                        class="text-sm font-black tabular-nums"
                        :class="
                            globalCompletion === 100
                                ? 'text-green-400'
                                : 'text-white'
                        "
                    >
                        {{ globalCompletion }}%
                    </span>
                </div>
            </div>

            <!-- Segmented Progress Bar & Navigation -->
            <div class="flex w-full items-center gap-1.5">
                <button
                    v-for="section in sections"
                    :key="section.id"
                    type="button"
                    class="group relative flex flex-1 flex-col gap-1.5"
                    :class="
                        activeSection === section.id
                            ? 'bg-linear-to-t from-foreground/10 to-transparent'
                            : ''
                    "
                    @click="scrollTo(section.id)"
                >
                    <!-- Small Icon Indicator -->
                    <div
                        class="mx-auto flex h-6 w-6 items-center justify-center rounded-full border transition-all"
                        :class="[
                            section.completion === 100
                                ? 'border-green-500/50 bg-green-500/10 text-green-400'
                                : section.completion > 0
                                  ? 'border-primary/50 bg-primary/20 text-primary shadow-[0_0_8px_rgba(239,68,68,0.2)]'
                                  : activeSection === section.id
                                    ? 'border-white bg-white/20 text-white'
                                    : 'border-white/10 bg-white/5 text-white/30',
                            activeSection === section.id
                                ? 'scale-110 shadow-md ring-1 ring-white/10'
                                : '',
                        ]"
                    >
                        <component :is="section.icon" class="h-3 w-3" />
                    </div>

                    <!-- Segment Bar -->
                    <div
                        class="h-1.5 w-full overflow-hidden rounded-full transition-all duration-300"
                        :class="[
                            section.completion === 100
                                ? 'bg-green-500 shadow-[0_0_8px_rgba(34,197,94,0.3)]'
                                : section.completion > 0
                                  ? 'bg-primary shadow-[0_0_8px_rgba(239,68,68,0.2)]'
                                  : activeSection === section.id
                                    ? 'bg-white shadow-[0_0_8px_rgba(255,255,255,0.4)]'
                                    : 'bg-white/10',
                        ]"
                    />
                </button>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { BarChart2 } from 'lucide-vue-next';
import { type Component, computed } from 'vue';
import { useTranslation } from '@/composables/useTranslation';

const { __ } = useTranslation();

export type FormSection = {
    id: string;
    label: string;
    icon: Component;
    completion: number;
};

const { sections, activeSection, globalCompletion, desktopOnly, mobileOnly } =
    defineProps<{
        sections: FormSection[];
        activeSection?: string;
        globalCompletion: number;
        desktopOnly?: boolean;
        mobileOnly?: boolean;
    }>();

const activeSectionData = computed(() =>
    sections.find((s) => s.id === activeSection),
);

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
