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
    open?: boolean;
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
}

const props = withDefaults(defineProps<Props>(), {
    side: 'right',
    withFloatingButton: false,
});

const emit = defineEmits(['update:open']);

const handleOpenChange = (val: boolean) => {
    emit('update:open', val);
};
</script>

<template>
    <div class="contents">
        <!-- Floating Toggle/Close Button -->
        <div
            v-if="withFloatingButton"
            class="pointer-events-auto fixed bottom-6 z-100 flex transition-all duration-500 ease-[cubic-bezier(0.32,0.72,0,1)] md:hidden"
            :class="[
                side === 'left'
                    ? open
                        ? 'left-[85vw]'
                        : 'left-6'
                    : open
                      ? 'right-[85vw]'
                      : 'right-6',
            ]"
        >
            <Button
                size="lg"
                type="button"
                class="dark group flex h-14 w-14 items-center justify-center rounded-full p-0 shadow-lg transition-all hover:ring-2 hover:ring-primary active:scale-95"
                :class="
                    open
                        ? 'translate-x-[-50%] bg-primary text-white ring-2 ring-primary hover:bg-primary/90'
                        : 'bg-background hover:scale-110'
                "
                @click="emit('update:open', !open)"
            >
                <component
                    :is="open ? X : icon"
                    class="h-6 w-6 transition-transform group-hover:scale-110"
                    :class="open ? 'rotate-0 text-white' : 'text-primary'"
                />
            </Button>
        </div>

        <Sheet :open="open" @update:open="handleOpenChange">
            <SheetTrigger v-if="$slots.trigger" as-child>
                <slot name="trigger" />
            </SheetTrigger>
            <SheetContent
                :side="side"
                :class="
                    cn(
                        'flex w-[85vw] flex-col bg-card p-0 sm:max-w-md',
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
                    v-if="title || description || icon"
                    class="dark mb-0 bg-background p-6"
                >
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div
                                v-if="icon"
                                class="flex h-9 w-9 items-center justify-center rounded-xl bg-primary/10 text-primary"
                            >
                                <component :is="icon" class="h-5 w-5" />
                            </div>
                            <div class="space-y-0.5">
                                <SheetTitle
                                    v-if="title"
                                    class="text-xl font-bold tracking-tight text-foreground"
                                >
                                    {{ title }}
                                </SheetTitle>
                                <SheetDescription
                                    v-if="description"
                                    class="text-xs font-medium text-muted-foreground/80"
                                >
                                    {{ description }}
                                </SheetDescription>
                            </div>
                        </div>

                        <div
                            class="flex items-center gap-2"
                            :class="!withFloatingButton ? 'pr-10' : ''"
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
                                v-if="!withFloatingButton"
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
