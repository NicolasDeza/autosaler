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
            class="pointer-events-none relative z-20 flex h-full flex-col justify-end p-4 text-white sm:p-5"
        >
            <!-- WhatsApp - Floating Top Right -->
            <div class="absolute top-3 right-3 items-start">
                <a
                    v-if="phone"
                    :href="`https://wa.me/${phone.replace(/\D/g, '')}?text=${encodeURIComponent(__('vehicleAd.whatsapp_message'))}`"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="group/wa pointer-events-auto relative flex h-10 w-10 items-center justify-center rounded-2xl bg-[#25D366] text-white shadow-lg transition-all hover:scale-110 active:scale-95 sm:h-11 sm:w-11"
                    title="WhatsApp"
                >
                    <svg viewBox="0 0 24 24" class="h-5 w-5 fill-current">
                        <path
                            d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"
                        />
                    </svg>
                    <div
                        class="absolute -top-0.5 -right-0.5 block h-2.5 w-2.5 animate-pulse rounded-full border-2 border-white bg-green-500"
                    ></div>
                </a>
            </div>

            <!-- Bottom Data Row -->
            <div class="flex items-end gap-3 sm:gap-5">
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

                <div class="pointer-events-auto min-w-0 space-y-1.5 pb-1">
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
