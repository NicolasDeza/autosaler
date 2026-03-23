<script setup lang="ts">
import { X } from 'lucide-vue-next';
import type { Component } from 'vue';
import { Button } from '@/components/ui/button';
import {
    Sheet,
    SheetClose,
    SheetContent,
    SheetHeader,
    SheetTitle,
    SheetDescription,
    SheetFooter,
    SheetTrigger,
} from '@/components/ui/sheet';
import { cn } from '@/lib/utils';

interface Props {
    side?: 'left' | 'right' | 'top' | 'bottom';
    title?: string;
    description?: string;
    icon?: Component;
    class?: string;
    /**
     * If true, shows a floating circular button that slides with the menu.
     * When open, the button icon changes to a 'X' and serves as a close button.
     */
    withFloatingButton?: boolean;
    /**
     * If true, shows the close button even if title/description/icon are missing.
     */
    showClose?: boolean;
    /**
     * Optional count to show as a badge on the floating button.
     */
    badgeCount?: number;
}

const props = withDefaults(defineProps<Props>(), {
    side: 'right',
    withFloatingButton: false,
    showClose: true,
});

const isOpen = defineModel<boolean>('open', { default: false });

const handleOpenChange = (val: boolean) => {
    isOpen.value = val;
};
</script>

<template>
    <div class="contents">
        <!-- Floating Toggle/Close Button -->
        <div
            v-if="withFloatingButton"
            class="pointer-events-auto fixed bottom-6 flex transition-all duration-500 ease-[cubic-bezier(0.32,0.72,0,1)] lg:hidden"
            :class="[
                side === 'left'
                    ? isOpen
                        ? 'left-[85vw] z-100'
                        : 'left-6 z-40'
                    : isOpen
                      ? 'right-[85vw] z-100'
                      : 'right-6 z-40',
            ]"
        >
            <Button
                size="lg"
                type="button"
                class="dark group flex h-14 w-14 items-center justify-center rounded-full p-0 shadow-lg transition-all hover:ring-2 hover:ring-primary active:scale-95"
                :class="
                    isOpen
                        ? 'translate-x-[-50%] bg-primary text-white ring-2 ring-primary hover:bg-primary/90'
                        : 'bg-background hover:scale-110'
                "
                @click="isOpen = !isOpen"
            >
                <component
                    :is="isOpen ? X : icon"
                    class="h-6 w-6 transition-transform group-hover:scale-110"
                    :class="isOpen ? 'rotate-0 text-white' : 'text-primary'"
                />

                <!-- Badge Count Pill -->
                <div
                    v-if="!isOpen && badgeCount && badgeCount > 0"
                    class="absolute -top-1.5 -right-1.5 flex h-5.5 min-w-5.5 items-center justify-center rounded-full border border-background bg-primary px-1 text-[10px] leading-none font-black text-white shadow-sm ring-1 ring-primary/20"
                >
                    {{ badgeCount }}
                </div>
            </Button>
        </div>

        <Sheet :open="isOpen" @update:open="handleOpenChange">
            <SheetTrigger v-if="$slots.trigger" as-child>
                <slot name="trigger" />
            </SheetTrigger>
            <SheetContent
                :side="side"
                :class="
                    cn(
                        'flex w-[85vw] flex-col gap-0 bg-card p-0 sm:max-w-md',
                        side === 'left'
                            ? 'border-r-primary'
                            : side === 'right'
                              ? 'border-l-primary'
                              : '',
                        props.class,
                    )
                "
            >
                <SheetHeader
                    v-if="title || description || icon || $slots.headerBranding"
                    class="dark mb-0 bg-background p-6"
                >
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <!-- Custom Branding Slot (often for Logo) -->
                            <div v-if="$slots.headerBranding" class="flex-1">
                                <slot name="headerBranding" />
                            </div>

                            <template v-else>
                                <div
                                    v-if="icon"
                                    class="flex h-9 w-9 items-center justify-center rounded-xl bg-primary/10 text-primary"
                                >
                                    <component :is="icon" class="h-5 w-5" />
                                </div>
                                <div class="space-y-0.5">
                                    <div class="flex items-center gap-2">
                                        <SheetTitle
                                            v-if="title"
                                            class="text-xl font-bold tracking-tight text-foreground"
                                        >
                                            {{ title }}
                                        </SheetTitle>
                                        <div
                                            v-if="badgeCount && badgeCount > 0"
                                            class="flex h-5 min-w-5 items-center justify-center rounded-full bg-primary px-1.5 text-[10px] leading-none font-black text-white shadow-sm"
                                        >
                                            {{ badgeCount }}
                                        </div>
                                    </div>
                                    <SheetDescription
                                        v-if="description"
                                        class="text-xs font-medium text-muted-foreground/80"
                                    >
                                        {{ description }}
                                    </SheetDescription>
                                </div>
                            </template>
                        </div>

                        <div
                            class="flex items-center gap-2"
                            :class="!withFloatingButton ? 'min-w-9' : ''"
                        >
                            <!-- Slot for extra header actions (buttons, etc.) -->
                            <div
                                v-if="$slots.headerActions"
                                class="flex items-center gap-2"
                            >
                                <slot name="headerActions" />
                            </div>

                            <!-- Integrated Close Button (standard X, hidden if floating button is present) -->
                            <SheetClose
                                v-if="showClose && !withFloatingButton"
                                class="absolute top-5 right-5 z-50 flex h-9 w-9 items-center justify-center rounded-lg text-muted-foreground/60 transition-all hover:bg-muted hover:text-foreground active:scale-95"
                            >
                                <X class="h-5 w-5" />
                                <span class="sr-only">Close</span>
                            </SheetClose>
                        </div>
                    </div>
                </SheetHeader>

                <div
                    class="custom-scrollbar scrollbar-track-transparent flex-1 space-y-8 overflow-y-auto p-6"
                >
                    <slot />
                </div>

                <SheetFooter
                    v-if="$slots.footer"
                    class="mt-auto border-t border-border/10 bg-background/50 p-6"
                >
                    <slot name="footer" />
                </SheetFooter>
            </SheetContent>
        </Sheet>
    </div>
</template>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    width: 5px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: hsl(var(--muted-foreground) / 0.2);
    border-radius: 10px;
    transition: background 0.2s ease;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: hsl(var(--muted-foreground) / 0.4);
}

/* Firefox */
.custom-scrollbar {
    scrollbar-width: thin;
    scrollbar-color: hsl(var(--muted-foreground) / 0.2) transparent;
}
</style>
