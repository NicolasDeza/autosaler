<template>
    <div class="dark text-foreground">
        <div class="border-b border-sidebar-border/80 bg-background">
            <div class="mx-auto grid grid-cols-4 px-4 py-10 md:max-w-7xl">
                <div class="col-span-2 flex flex-col gap-4">
                    <div class="flex">
                        <Link :href="dashboard()" class="flex gap-x-2">
                            <AppLogoLg />
                        </Link>
                    </div>
                    <div class="max-w-96">
                        {{ __('nav.footer_description') }}
                    </div>
                    <div>socials</div>
                </div>
                <!-- <div class="flex grow justify-between"> -->
                <div class="flex flex-col gap-4">
                    <div class="font-bold">Menu</div>
                    <Link
                        v-for="item in menuItems"
                        :key="item.title"
                        :href="item.href"
                        class="flex items-center rounded-lg text-sm font-medium hover:underline"
                    >
                        {{ item.title }}
                    </Link>
                </div>
                <div class="flex flex-col gap-4">
                    <div class="font-bold">Legal</div>
                    <Link
                        v-for="item in legalItems"
                        :key="item.title"
                        :href="item.href"
                        class="flex items-center rounded-lg text-sm font-medium hover:underline"
                    >
                        {{ item.title }}
                    </Link>
                </div>
                <!-- </div> -->
            </div>
            <div
                class="mx-auto flex justify-center border-t border-red-500 px-4 py-10 md:max-w-7xl"
            >
                Copyright ©{{ new Date().getFullYear() }} AutoSaler
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { usePermissions } from '@/composables/usePermissions';
import { useTranslation } from '@/composables/useTranslation';
import { admin_dashboard, dashboard } from '@/routes';
import type { NavItem } from '@/types';
import AppLogo from './AppLogo.vue';
import AppLogoLg from './AppLogoLg.vue';

const { __ } = useTranslation();
const { can } = usePermissions();

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
