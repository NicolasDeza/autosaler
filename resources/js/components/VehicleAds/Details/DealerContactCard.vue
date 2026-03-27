<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import {
    Edit,
    Globe,
    Mail,
    MapPin,
    Phone,
} from 'lucide-vue-next';
import CompanyHeader from '@/components/Company/CompanyHeader.vue';
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import { useTranslation } from '@/composables/useTranslation';

defineProps<{
    ad: any;
    canEdit: boolean;
    vehicleEdit: any;
}>();

const emit = defineEmits(['contact']);

const { __ } = useTranslation();

const openContactModal = () => {
    emit('contact');
};
</script>

<template>
    <div class="col-span-1 space-y-6 lg:sticky lg:top-24 lg:self-start">
        <Card
            class="gap-0! overflow-hidden rounded-lg border border-border/50 bg-card/60 pt-0 shadow-2xl shadow-primary/5 backdrop-blur-sm transition-all hover:border-primary/20 hover:shadow-primary/10"
        >
            <CompanyHeader
                class="hidden lg:block"
                :name="
                    ad.user?.company?.name ??
                    ad.user?.first_name + ' ' + ad.user?.last_name
                "
                :logo-url="ad.user?.company?.logo_url"
                :background-url="ad.user?.company?.background_url"
                :phone="ad.user?.company?.phone"
                :member-since="
                    ad.user?.created_at
                        ? new Date(ad.user.created_at).getFullYear()
                        : null
                "
            />

            <!-- Card Body (Desktop Location / Sidebar Actions) -->
            <div class="hidden p-6 lg:block">
                <div v-if="ad.user?.company" class="mb-8 space-y-4">
                    <div class="flex items-center gap-2">
                        <div
                            class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary/10 text-primary"
                        >
                            <MapPin class="h-4 w-4" />
                        </div>
                        <p
                            class="text-[11px] font-black tracking-widest text-muted-foreground/60 uppercase"
                        >
                            {{ __('ui.location') }}
                        </p>
                    </div>
                    <div
                        class="rounded-xl border border-border/50 bg-muted/30 p-4 text-sm leading-relaxed text-foreground/80"
                    >
                        <p class="font-bold text-foreground">
                            {{ ad.user.company.address }}
                        </p>
                        <p>
                            {{ ad.user.company.city?.zip_code }}
                            {{ ad.user.company.city?.code }}
                        </p>
                        <p
                            class="mt-2 flex items-center gap-1.5 text-[11px] font-bold text-primary uppercase"
                        >
                            <Globe class="size-3" />
                            {{ ad.user.company.country?.code }}
                        </p>
                    </div>
                </div>

                <div class="mb-6 grid grid-cols-2 gap-3">
                    <a
                        v-if="ad.user?.company?.phone"
                        :href="`tel:${ad.user.company.phone}`"
                        class="group flex flex-col items-center gap-2 rounded-xl border border-border bg-muted/50 py-3.5 transition-all hover:border-primary/30 hover:bg-primary/5"
                    >
                        <Phone
                            class="h-4 w-4 text-muted-foreground transition-colors group-hover:text-primary"
                        />
                        <span
                            class="text-[10px] font-bold tracking-tight text-muted-foreground uppercase"
                            >{{ __('ui.call') }}</span
                        >
                    </a>

                    <Button
                        type="button"
                        class="group relative flex h-16 flex-1 items-center justify-center overflow-hidden rounded-xl bg-primary px-6 font-black tracking-tight text-white uppercase shadow-lg shadow-primary/20 transition-all hover:scale-[1.02] hover:shadow-primary/40 active:scale-[0.98]"
                        @click="openContactModal"
                    >
                        <div
                            class="absolute inset-0 bg-linear-to-r from-transparent via-white/10 to-transparent transition-transform duration-1000 group-hover:translate-x-full"
                        ></div>
                        <div class="flex flex-col items-center gap-2">
                            <Mail
                                class="h-4 w-4 transition-transform group-hover:-rotate-12"
                            />
                            <span
                                class="text-xs font-bold whitespace-break-spaces"
                                >{{ __('vehicleAd.contact_seller') }}</span
                            >
                        </div>
                    </Button>
                </div>
            </div>
        </Card>

        <Card
            v-if="canEdit"
            class="hidden border-2 border-dashed bg-muted/10 p-4 shadow-none lg:block"
        >
            <h3
                class="mb-3 text-[10px] font-black tracking-[0.2em] text-muted-foreground/70 uppercase"
            >
                {{ __('vehicleAd.ad_management') }}
            </h3>
            <Button
                variant="outline"
                class="w-full cursor-pointer justify-start gap-2 rounded border-border/50 bg-background"
                @click="router.visit(vehicleEdit.url(ad.id))"
            >
                <Edit class="h-3.5 w-3.5 text-primary" />
                <span class="text-xs font-bold">{{
                    __('vehicleAd.edit_ad')
                }}</span>
            </Button>
        </Card>
    </div>
</template>
