<template>
    <div class="dark text-foreground">
        <!-- Accent top bar -->
        <div class="h-px bg-linear-to-r from-transparent via-red-500 to-transparent"></div>

        <div class="bg-background">
            <div class="mx-auto md:max-w-7xl px-4">
                <!-- Main footer content -->
                <div class="grid grid-cols-1 gap-12 py-16 sm:grid-cols-2 lg:grid-cols-4">
                    <!-- Brand column -->
                    <div class="flex flex-col items-center gap-6 sm:col-span-2 sm:items-start">
                        <Link :href="dashboard()" class="flex items-center gap-x-2 w-fit">
                            <AppLogoLg />
                        </Link>
                        <p class="max-w-sm text-center text-sm leading-relaxed text-muted-foreground sm:text-left">
                            {{ __('nav.footer_description') }}
                        </p>
                        <!-- Socials -->
                        <div class="flex gap-3">
                            <a
                                v-for="social in socialLinks"
                                :key="social.label"
                                :href="social.href"
                                :aria-label="social.label"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="flex size-9 items-center justify-center rounded border border-[#3E3E3A] bg-transparent text-muted-foreground transition-colors hover:border-[#62605b] hover:text-red-400"
                            >
                                <span class="sr-only">{{ social.label }}</span>
                                <Facebook class="size-4" />
                            </a>
                        </div>
                    </div>

                    <!-- Menu column -->
                    <div class="flex flex-col items-center gap-4 sm:items-start">
                        <p class="text-xs font-semibold uppercase tracking-widest text-white border-b-2 border-red-500 pb-2 w-fit">Menu</p>
                        <nav class="flex flex-col items-center gap-2 sm:items-start">
                            <Link
                                v-for="item in menuItems"
                                :key="item.title"
                                :href="item.href"
                                class="text-sm text-foreground/70 transition-colors hover:text-red-400 hover:underline decoration-red-400 underline-offset-4"
                            >
                                {{ item.title }}
                            </Link>
                        </nav>
                    </div>

                    <!-- Legal column -->
                    <div class="flex flex-col items-center gap-4 sm:items-start">
                        <p class="text-xs font-semibold uppercase tracking-widest text-white border-b-2 border-red-500 pb-2 w-fit">Légal</p>
                        <nav class="flex flex-col items-center gap-2 sm:items-start">
                            <Link
                                v-for="item in legalItems"
                                :key="item.title"
                                :href="item.href"
                                class="text-sm text-foreground/70 transition-colors hover:text-red-400 hover:underline decoration-red-400 underline-offset-4"
                            >
                                {{ item.title }}
                            </Link>
                        </nav>
                    </div>
                </div>

                <!-- Bottom bar -->
                <div class="flex flex-col items-center justify-between gap-4 border-t border-white/20 py-6 sm:flex-row">
                    <p class="text-xs text-muted-foreground">
                        © {{ new Date().getFullYear() }}
                        <span class="font-semibold text-foreground">AutoSaler</span>
                        — {{ __('nav.all_rights_reserved') }}
                    </p>
                    <p class="text-xs text-muted-foreground">
                        {{ __('nav.made_by') }}
                        <a href="/" target="_blank" rel="noopener noreferrer" class="font-semibold text-red-500 underline decoration-red-500 underline-offset-4 hover:text-red-400">
                            La passion
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Facebook } from 'lucide-vue-next';
import { computed } from 'vue';
import { usePermissions } from '@/composables/usePermissions';
import { useTranslation } from '@/composables/useTranslation';
import { admin_dashboard, dashboard } from '@/routes';
import type { NavItem } from '@/types';
import AppLogoLg from './AppLogoLg.vue';

const { __ } = useTranslation();
const { can } = usePermissions();

const socialLinks = [
    { label: 'Facebook', href: '#', icon: 'facebook' },
];

const menuItems = computed<NavItem[]>(() => {
    const items: NavItem[] = [
        {
            title: __('nav.home'),
            href: dashboard(),
        },
    ];

    if (can('view_admin_dashboard')) {
        items.push({
            title: __('nav.admin_panel'),
            href: admin_dashboard(),
        });
    }

    return items;
});

const legalItems = computed<NavItem[]>(() => {
    const items: NavItem[] = [
        {
            title: __('nav.legal_notices'),
            href: 'legal/notices',
        },
        {
            title: __('nav.privacy_policy'),
            href: 'legal/privacy',
        },
        {
            title: __('nav.cookie_policy'),
            href: 'legal/cookies',
        },
    ];

    return items;
});
</script>
