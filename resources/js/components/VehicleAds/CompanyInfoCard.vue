<script setup lang="ts">
import { Mail, MapPin, Phone, User, CheckCircle, Globe } from 'lucide-vue-next';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Badge } from '@/components/ui/badge';
import { Card } from '@/components/ui/card';
import { useTranslation } from '@/composables/useTranslation';

defineProps<{
    company: any;
}>();

const { __ } = useTranslation();
</script>

<template>
    <Card
        class="group !mb-none relative overflow-hidden border-none bg-slate-950 p-0 shadow-2xl transition-all duration-500"
    >
        <!-- Background Layer -->
        <div class="absolute inset-0 z-0">
            <div
                v-if="company.background_url"
                class="absolute inset-0 bg-cover bg-center transition-transform duration-1000 group-hover:scale-110"
                :style="{ backgroundImage: `url(${company.background_url})` }"
            >
                <!-- Cinematic gradient overlay -->
                <div
                    class="absolute inset-0 bg-linear-to-b from-black/80 via-black/20 to-black/95"
                ></div>
            </div>
            <div
                v-else
                class="absolute inset-0 bg-linear-to-br from-primary/30 to-slate-950"
            ></div>
        </div>

        <div class="relative z-10 flex min-h-[200px] flex-col">
            <!-- Identity Section -->
            <div
                class="flex flex-1 items-center gap-6 px-6 py-8 lg:px-10 lg:py-8"
            >
                <div class="relative shrink-0">
                    <Avatar
                        class="relative size-20 border-4 border-white/20 bg-white p-0 shadow-2xl lg:size-28"
                    >
                        <AvatarImage
                            v-if="company.logo_url"
                            :src="company.logo_url"
                            :alt="company.name"
                            class="object-cover"
                        />
                        <AvatarFallback
                            class="bg-primary/5 text-4xl font-black text-primary uppercase"
                        >
                            {{ company.name?.charAt(0) || 'D' }}
                        </AvatarFallback>
                    </Avatar>
                </div>

                <div class="min-w-0 space-y-2">
                    <div class="flex flex-wrap items-center gap-2">
                        <Badge
                            class="border-none bg-primary/20 px-3 py-0.5 text-[10px] font-black tracking-widest text-primary uppercase backdrop-blur-md"
                        >
                            {{ __('vehicleAd.seller') }}
                        </Badge>
                        <span
                            class="inline-flex items-center gap-1 rounded-full bg-green-500/20 px-2.5 py-0.5 text-[9px] font-black text-green-400 uppercase ring-1 ring-green-500/30 backdrop-blur-md"
                        >
                            <CheckCircle class="size-2.5" />
                            {{ __('ui.verified') || 'Vérifié' }}
                        </span>
                    </div>

                    <div>
                        <h2
                            class="truncate text-3xl font-black tracking-tighter text-white drop-shadow-2xl lg:text-5xl"
                        >
                            {{ company.name }}
                        </h2>
                    </div>
                </div>
            </div>

            <!-- Panoramic Bottom Bar: Extended Gradient -->
            <div
                class="mt-auto w-full border-t border-white/10 bg-linear-to-t from-foreground/95 to-foreground/80 lg:min-h-0"
            >
                <div
                    class="grid h-full grid-cols-1 divide-y divide-white/5 lg:grid-cols-2 lg:divide-x lg:divide-y-0"
                >
                    <!-- Section 1: Full Address -->
                    <div class="flex items-center gap-4 px-8 py-4 lg:py-3">
                        <div
                            class="flex size-8 shrink-0 items-center justify-center rounded-lg bg-primary/10 text-primary shadow-lg ring-1 ring-primary/20"
                        >
                            <MapPin class="size-4" />
                        </div>
                        <div class="flex min-w-0 flex-col space-y-0.5">
                            <p
                                class="truncate text-sm font-black tracking-tight text-white uppercase transition-colors group-hover:text-primary"
                            >
                                {{ company.address || 'Adresse' }}
                            </p>
                            <p
                                class="truncate text-xs font-bold text-white/50 uppercase"
                            >
                                {{ company.city?.zip_code }}
                                {{ company.city?.code }}
                            </p>
                            <div
                                class="flex items-center gap-1 text-[10px] font-black text-primary uppercase"
                            >
                                <Globe class="size-2.5" />
                                {{
                                    company.country?.label ||
                                    company.country?.code ||
                                    'BE'
                                }}
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Contact -->
                    <div
                        class="flex flex-col justify-center gap-3 px-8 py-4 lg:py-3"
                    >
                        <div class="group/item flex items-center gap-3">
                            <div
                                class="flex size-7 shrink-0 items-center justify-center rounded-lg bg-primary/10 text-primary ring-1 ring-primary/20 transition-all hover:bg-primary hover:text-white"
                            >
                                <Phone class="size-3.5" />
                            </div>
                            <p
                                class="text-sm font-black text-white transition-colors hover:text-primary"
                            >
                                {{ company.phone || 'Non renseigné' }}
                            </p>
                        </div>

                        <div
                            class="group/item flex items-center gap-4 overflow-hidden"
                        >
                            <div
                                class="flex size-7 shrink-0 items-center justify-center rounded-lg bg-primary/10 text-primary ring-1 ring-primary/20 transition-all hover:bg-primary hover:text-white"
                            >
                                <Mail class="size-3.5" />
                            </div>
                            <p
                                class="truncate text-xs font-black text-white transition-colors hover:text-primary"
                            >
                                {{ company.email || 'contact@dealer.com' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Card>
</template>
