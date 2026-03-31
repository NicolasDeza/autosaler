<script setup lang="ts">
import { CheckCircle, User } from 'lucide-vue-next';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Badge } from '@/components/ui/badge';
import { useTranslation } from '@/composables/useTranslation';

interface Props {
    name?: string;
    logoUrl?: string | null;
    backgroundUrl?: string | null;
    phone?: string | null;
    verified?: boolean;
    memberSince?: string | number | null;
    editable?: boolean;
}

withDefaults(defineProps<Props>(), {
    verified: true,
    editable: false,
});

const { __ } = useTranslation();
</script>

<template>
    <div
        class="dark group/contact relative aspect-3/1 overflow-hidden rounded-t-lg bg-card"
    >
        <!-- Background Image Layer -->
        <div
            v-if="backgroundUrl"
            class="absolute inset-0 bg-cover bg-center transition-transform duration-1000 group-hover/contact:scale-105"
            :style="{
                backgroundImage: `url(${backgroundUrl})`,
            }"
        >
            <!-- Heavy Gradient for Contrast -->
            <div
                class="absolute inset-0 bg-linear-to-t from-black/90 via-black/40 to-transparent"
            ></div>
        </div>
        <div v-else class="absolute inset-0 bg-background"></div>

        <!-- Background Actions Slot -->
        <div class="absolute inset-0 z-10">
            <slot name="backgroundActions" />
        </div>

        <!-- Compact Content Overlay -->
        <div
            class="pointer-events-none relative z-20 flex h-full flex-col justify-center p-4 text-white sm:p-5"
        >
            <!-- Bottom Data Row -->
            <div class="flex items-center gap-3 sm:gap-5">
                <!-- Company Logo Area -->
                <div
                    v-if="logoUrl || editable"
                    class="group/logo pointer-events-auto relative shrink-0"
                >
                    <Avatar
                        :class="[
                            'size-12 border-2 border-white/20 bg-white shadow-lg transition-all duration-300 group-hover/contact:scale-105 sm:size-16',
                            editable ? 'group-hover/logo:blur-[2px]' : '',
                        ]"
                    >
                        <AvatarImage
                            v-if="logoUrl"
                            :src="logoUrl"
                            :alt="name"
                            class="object-cover"
                        />
                        <AvatarFallback
                            class="bg-white/10 text-xl font-bold text-white"
                        >
                            <User v-if="!name" class="size-5 sm:size-7" />
                            <template v-else>
                                {{ name.charAt(0) }}
                            </template>
                        </AvatarFallback>
                    </Avatar>
                    <slot name="logoActions" />
                </div>

                <div class="pointer-events-auto min-w-0 space-y-1.5">
                    <div class="flex flex-wrap items-center gap-2">
                        <Badge
                            variant="secondary"
                            class="border-white/10 bg-white/10 text-[9px] font-black tracking-widest text-white uppercase backdrop-blur-md"
                        >
                            {{ __('vehicleAd.seller') }}
                        </Badge>
                        <span
                            v-if="verified"
                            class="inline-flex items-center gap-1 rounded-full bg-green-500/20 px-2 py-0.5 text-[8px] font-bold text-green-400 backdrop-blur-md"
                        >
                            <CheckCircle class="size-2" />
                            {{ __('ui.verified') || 'Vérifié' }}
                        </span>
                    </div>

                    <div class="space-y-0.5">
                        <h2
                            class="truncate text-xl leading-tight font-black tracking-tight text-white drop-shadow-sm sm:text-2xl"
                        >
                            {{ name || __('ui.unknown_user') }}
                        </h2>
                        <div
                            class="flex items-center gap-1.5 text-[10px] font-bold tracking-widest text-white/60 uppercase"
                        >
                            <span v-if="memberSince">
                                {{ memberSince }}
                            </span>
                            <span
                                v-if="memberSince && $slots.default"
                                class="opacity-30"
                                >•</span
                            >
                            <slot />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <slot />
    </div>
</template>
