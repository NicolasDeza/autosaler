<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { Edit, Globe, Mail, MapPin, Phone } from 'lucide-vue-next';
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
    <div
        class="col-span-1 hidden space-y-6 lg:sticky lg:top-24 lg:block lg:self-start"
    >
        <Card
            class="gap-0! overflow-hidden rounded-lg border border-border/50 bg-card/60 pt-0 shadow-2xl shadow-primary/5 backdrop-blur-sm transition-all hover:border-primary/20 hover:shadow-primary/10"
        >
            <CompanyHeader
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
            <div class="p-6">
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

                    <a
                        v-if="ad.user?.company?.phone"
                        :href="`https://wa.me/${ad.user.company.phone.replace(/\\D/g, '')}?text=${encodeURIComponent(__('vehicleAd.whatsapp_message'))}`"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="group flex flex-col items-center gap-2 rounded-xl border border-border bg-[#25D366]/10 py-3.5 transition-all hover:border-[#25D366]/40 hover:bg-[#25D366]/20"
                    >
                        <svg
                            viewBox="0 0 24 24"
                            class="h-4 w-4 fill-current text-[#25D366] transition-transform group-hover:scale-110"
                        >
                            <path
                                d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"
                            />
                        </svg>
                        <span
                            class="text-[10px] font-bold tracking-tight text-[#25D366] uppercase"
                            >WhatsApp</span
                        >
                    </a>

                    <Button
                        type="button"
                        class="group relative col-span-full flex h-16 w-full cursor-pointer items-center justify-center overflow-hidden rounded-xl bg-primary px-6 font-black tracking-tight text-white uppercase shadow-lg shadow-primary/20 transition-all hover:scale-[1.02] hover:shadow-primary/40 active:scale-[0.98]"
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
            class="border-2 border-dashed bg-muted/10 p-4 shadow-none"
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
