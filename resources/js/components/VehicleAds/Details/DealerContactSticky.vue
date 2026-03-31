<script setup lang="ts">
import { CheckCircle, MapPin, Mail, Phone } from 'lucide-vue-next';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Button } from '@/components/ui/button';
import { useTranslation } from '@/composables/useTranslation';

defineProps<{
    ad: any;
}>();

const emit = defineEmits(['contact']);

const { __ } = useTranslation();

const openContactModal = () => {
    emit('contact');
};
</script>

<template>
    <div class="px-2 py-2 lg:hidden">
        <div class="flex items-center justify-between gap-3">
            <div class="flex min-w-0 items-center gap-3">
                <!-- Enlarged Logo -->
                <Avatar class="size-11 shrink-0 border border-border/50 bg-white shadow-sm">
                    <AvatarImage
                        v-if="ad.user?.company?.logo_url"
                        :src="ad.user.company.logo_url"
                        :alt="ad.user?.company?.name"
                        class="object-cover"
                    />
                    <AvatarFallback
                        class="bg-primary/10 text-xs font-black text-primary"
                    >
                        {{
                            (
                                ad.user?.company?.name ||
                                ad.user?.first_name ||
                                'U'
                            ).charAt(0)
                        }}
                    </AvatarFallback>
                </Avatar>

                <!-- Dealer Info Column -->
                <div class="flex min-w-0 flex-col justify-center">
                    <h2
                        class="flex items-center gap-1.5 text-sm font-black tracking-tight text-foreground"
                    >
                        <span class="truncate">
                            {{
                                ad.user?.company?.name ??
                                ad.user?.first_name + ' ' + ad.user?.last_name
                            }}
                        </span>
                        <CheckCircle class="size-3 shrink-0 text-green-500" />
                    </h2>
                    <div
                        v-if="ad.user?.company?.city"
                        class="flex items-center gap-1 text-[10px] font-bold text-muted-foreground uppercase transition-colors"
                    >
                        <MapPin class="size-3 shrink-0" />
                        <span class="truncate">{{
                            ad.user.company.city.code
                        }}</span>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a
                    v-if="ad.user?.company?.phone"
                    :href="`tel:${ad.user.company.phone}`"
                    class="flex h-12 items-center justify-center rounded-xl border border-border bg-primary/10 px-2 transition-all hover:bg-primary/25 active:scale-95"
                    :title="__('ui.call')"
                >
                    <Phone class="size-5 text-primary" />
                </a>

                <a
                    v-if="ad.user?.company?.phone"
                    :href="`https://wa.me/${ad.user.company.phone?.replace(/\D/g, '')}?text=${encodeURIComponent(__('vehicleAd.whatsapp_message'))}`"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="flex h-12 items-center justify-center rounded-xl bg-[#25D366]/10 px-2 text-[#25D366] ring-1 ring-[#25D366]/20 transition-all hover:bg-[#25D366]/20 active:scale-95"
                    title="WhatsApp"
                >
                    <svg viewBox="0 0 24 24" class="size-5 fill-current">
                        <path
                            d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"
                        />
                    </svg>
                </a>

                <Button
                    type="button"
                    size="sm"
                    class="h-12 cursor-pointer rounded-xl bg-primary px-4 font-black tracking-tight text-white uppercase shadow-lg shadow-primary/20 active:scale-95"
                    @click="openContactModal"
                >
                    <Mail class="size-4" />
                    <span class="max-sm:hidden">{{
                        __('vehicleAd.contact') || 'Contact'
                    }}</span>
                </Button>
            </div>
        </div>
    </div>
</template>
