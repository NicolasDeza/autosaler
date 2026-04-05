<template>
    <footer
        class="dark relative overflow-hidden border-t border-white/5 bg-background text-foreground print:hidden"
    >
        <div class="relative z-10 mx-auto px-6 md:max-w-7xl">
            <div
                class="grid grid-cols-1 gap-y-12 py-12 sm:py-24 lg:grid-cols-12 lg:gap-x-12"
            >
                <div class="flex flex-col items-center gap-10 text-center lg:col-span-4 lg:items-start lg:text-left">
                    <div class="flex flex-col items-center gap-6 lg:items-start">
                        <Link
                            :href="home().url"
                            class="mx-auto flex w-fit items-center transition-transform hover:scale-[1.02] lg:mx-0"
                        >
                            <AppLogoLg />
                        </Link>
                        <p
                            class="mx-auto max-w-sm text-[14px] leading-relaxed font-light text-muted-foreground/70 lg:mx-0"
                        >
                            {{ __('nav.footer_description') }}
                        </p>
                    </div>

                    <div class="flex justify-center lg:justify-start">
                        <a
                            href="mailto:contact@autosaler.be"
                            class="text-[13px] text-muted-foreground underline decoration-primary/50 underline-offset-4 transition-all hover:text-primary hover:decoration-primary"
                        >
                            contact@autosaler.be
                        </a>
                    </div>
                </div>

                <div
                    class="grid grid-cols-1 gap-10 sm:grid-cols-3 lg:col-span-8"
                >
                    <div
                        class="relative flex flex-col items-center gap-8 pl-0 text-center sm:items-start sm:pl-8 sm:text-left lg:border-l lg:border-white/5"
                    >
                        <div class="flex flex-col items-center gap-2 sm:items-start">
                            <h3
                                class="text-[11px] font-bold tracking-[0.25em] text-white uppercase"
                            >
                                {{ __('nav.footer_platform') }}
                            </h3>
                            <div class="h-0.5 w-8 bg-primary"></div>
                        </div>
                        <nav class="flex flex-col items-center gap-3.5 sm:items-start">
                            <Link
                                v-for="item in menuItems"
                                :key="item.title"
                                :href="item.href"
                                class="group flex items-center justify-center text-[14px] text-muted-foreground transition-all hover:translate-x-1 hover:text-white sm:justify-start"
                            >
                                <span
                                    class="mr-0 h-px w-0 bg-primary transition-all group-hover:mr-2 group-hover:w-3"
                                ></span>
                                {{ item.title }}
                            </Link>
                        </nav>
                    </div>

                    <div
                        class="relative flex flex-col items-center gap-8 pl-0 text-center sm:items-start sm:pl-8 sm:text-left lg:border-l lg:border-white/5"
                    >
                        <div class="flex flex-col items-center gap-2 sm:items-start">
                            <h3
                                class="text-[11px] font-bold tracking-[0.25em] text-white uppercase"
                            >
                                {{ __('nav.footer_legal') }}
                            </h3>
                            <div class="h-0.5 w-8 bg-primary"></div>
                        </div>
                        <nav class="flex flex-col items-center gap-3.5 sm:items-start">
                            <Link
                                v-for="item in legalItems"
                                :key="item.title"
                                :href="item.href"
                                class="group flex items-center justify-center text-[14px] text-muted-foreground transition-all hover:translate-x-1 hover:text-white sm:justify-start"
                            >
                                <span
                                    class="mr-0 h-px w-0 bg-primary transition-all group-hover:mr-2 group-hover:w-3"
                                ></span>
                                {{ item.title }}
                            </Link>
                        </nav>
                    </div>

                    <div
                        class="relative flex flex-col items-center gap-8 pl-0 text-center sm:items-start sm:pl-8 sm:text-left lg:border-l lg:border-white/5"
                    >
                        <div class="flex flex-col items-center gap-2 sm:items-start">
                            <h3
                                class="text-[11px] font-bold tracking-[0.25em] text-white uppercase"
                            >
                                {{ __('nav.footer_contact') }}
                            </h3>
                            <div class="h-0.5 w-8 bg-primary"></div>
                        </div>
                        <div class="flex flex-col items-center gap-5 sm:items-start">
                            <p
                                class="text-[13px] leading-relaxed text-muted-foreground/80"
                            >
                                {{ __('nav.footer_contact_description') }}
                            </p>
                            <Button
                                type="button"
                                class="group relative inline-flex h-9 w-fit cursor-pointer items-center justify-center overflow-hidden rounded-lg border border-primary/30 px-5 text-[10px] font-bold tracking-widest text-white uppercase transition-all hover:border-primary hover:bg-primary active:scale-95 sm:self-start"
                                @click="openContactModal"
                            >
                                {{ __('nav.footer_contact_button') }}
                            </Button>
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="flex flex-col items-center justify-between gap-8 border-t border-white/5 py-10 md:flex-row"
            >
                <p
                    class="text-center text-[11px] font-medium tracking-wide text-muted-foreground/50 md:text-left"
                >
                    © {{ new Date().getFullYear() }} —
                    <span class="text-white/80 uppercase">Autosaler</span>.
                    {{ __('nav.all_rights_reserved') }}
                </p>

                <div
                    class="flex flex-col items-center gap-3 text-[11px] font-medium tracking-widest uppercase sm:flex-row sm:gap-6"
                >
                    <span class="text-muted-foreground/40">{{
                        __('nav.made_by')
                    }}</span>
                    <div class="flex gap-4 text-[10px]">
                        <a
                            href="https://www.nicolasdeza.com/"
                            target="_blank"
                            class="text-muted-foreground underline decoration-primary/50 underline-offset-4 transition-all hover:text-primary hover:decoration-primary"
                            >NICOLAS DEZA</a
                        >
                        <span class="text-white/10">/</span>
                        <a
                            href="https://techybear.eu/"
                            target="_blank"
                            class="text-muted-foreground underline decoration-primary/50 underline-offset-4 transition-all hover:text-primary hover:decoration-primary"
                            >SEBASTIEN ECTOR</a
                        >
                    </div>
                </div>
            </div>
        </div>

        <ContactModal v-model:open="isContactModalOpen" />
    </footer>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import ContactModal from '@/components/ContactModal.vue';
import { Button } from '@/components/ui/button';
import { usePermissions } from '@/composables/usePermissions';
import { useTranslation } from '@/composables/useTranslation';
import { home } from '@/routes';
import admin from '@/routes/admin';
import dealer from '@/routes/dealer';
import dealers from '@/routes/dealers';
import garages from '@/routes/garages';
import { notices, privacy, cookies } from '@/routes/legal';
import vehicles from '@/routes/vehicles';
import type { NavItem } from '@/types';
import AppLogoLg from './AppLogoLg.vue';

const { __ } = useTranslation();
const { can } = usePermissions();
const isContactModalOpen = ref(false);

const openContactModal = (): void => {
    isContactModalOpen.value = true;
};

const menuItems = computed<NavItem[]>(() => {
    const items: NavItem[] = [
        {
            title: __('nav.home'),
            href: home().url,
        },
        {
            title: __('nav.footer_vehicles'),
            href: vehicles.index().url,
        },
        {
            title: __('nav.footer_garages'),
            href: garages.index().url,
        },
        {
            title: __('dealerPage.heading'),
            href: dealers.index().url,
        },
    ];

    if (can('view_dealer_dashboard')) {
        items.push({
            title: __('nav.dealer_dashboard'),
            href: dealer.dashboard().url,
        });
    }

    if (can('view_admin_dashboard')) {
        items.push({
            title: __('nav.admin_dashboard'),
            href: admin.dashboard().url,
        });
    }

    return items;
});

const legalItems = computed<NavItem[]>(() => {
    const items: NavItem[] = [
        {
            title: __('nav.legal_notices'),
            href: notices().url,
        },
        {
            title: __('nav.privacy_policy'),
            href: privacy().url,
        },
        {
            title: __('nav.cookie_policy'),
            href: cookies().url,
        },
    ];

    return items;
});
</script>
