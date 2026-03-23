<script setup lang="ts">
import { Link, usePage, router } from '@inertiajs/vue3';
import {
    // BookOpen,
    // Folder,
    Home,
    Menu,
    Shield,
    Car,
    Star,
    Warehouse,
    User,
} from 'lucide-vue-next';
import { computed, ref } from 'vue';
import AppLogo from '@/components/AppLogo.vue';
import LoginRequiredModal from '@/components/Auth/LoginRequiredModal.vue';
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import InstallButton from '@/components/PWA/InstallButton.vue';
import SheetMenu from '@/components/SheetMenu.vue';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import {
    NavigationMenu,
    NavigationMenuItem,
    NavigationMenuList,
} from '@/components/ui/navigation-menu';
import {
    Tooltip,
    TooltipContent,
    TooltipProvider,
    TooltipTrigger,
} from '@/components/ui/tooltip';
import UserMenuContent from '@/components/UserMenuContent.vue';
import { useCurrentUrl } from '@/composables/useCurrentUrl';
import { getInitials } from '@/composables/useInitials';
import { usePermissions } from '@/composables/usePermissions';
import { useTranslation } from '@/composables/useTranslation';
import { toUrl } from '@/lib/utils';
import { login } from '@/routes';
import admin from '@/routes/admin';
import dealer from '@/routes/dealer';
import dealers from '@/routes/dealers';
import vehicles from '@/routes/vehicles';

import type { BreadcrumbItem, NavItem } from '@/types';
import type { ExtendedPageProps } from '@/types/inertia';
import LanguageSelector from './LanguageSelector.vue';

const { __ } = useTranslation();
const page = usePage<ExtendedPageProps>();

type Props = {
    breadcrumbs?: BreadcrumbItem[];
};

const props = withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});

const auth = computed(() => page.props.auth);
const { isCurrentUrl } = useCurrentUrl();

const { can } = usePermissions();

const mainNavItems = computed<NavItem[]>(() => {
    const items: NavItem[] = [
        {
            title: __('nav.nav_home'),
            href: '/',
            icon: Home,
        },
        {
            title: 'Véhicules',
            href: vehicles.index().url,
            icon: Car,
        },
        {
            title: __('nav.professionals'),
            href: dealers.index().url,
            icon: Warehouse,
        },
        // {
        //     title: __('nav.nav_dashboard'),
        //     href: dashboard(),
        //     icon: LayoutGrid,
        // },
    ];

    if (can('view_dealer_dashboard')) {
        items.push({
            title: __('nav.dealer_dashboard'),
            href: dealer.dashboard().url,
            icon: Shield,
        });
    }

    if (can('view_admin_dashboard')) {
        items.push({
            title: __('nav.admin_dashboard'),
            href: admin.dashboard().url,
            icon: Shield,
        });
    }

    return items;
});

const rightNavItems: NavItem[] = [];

const showLoginModal = ref(false);

const handleFavoritesClick = () => {
    if (!auth.value?.user) {
        showLoginModal.value = true;
        return;
    }
    router.get(vehicles.index({ query: { favorites_only: '1' } }).url);
};
// {
//     title: 'Repository',
//     href: 'https://github.com/laravel/vue-starter-kit',
//     icon: Folder,
// },
// {
//     title: 'Documentation',
//     href: 'https://laravel.com/docs/starter-kits#vue',
//     icon: BookOpen,
// },

const isNavItemActive = (item: NavItem) => {
    const url = page.url;

    // Vehicles: check for any /vehicles path, but exclude if it's favorites
    if (item.title === 'Véhicules') {
        const isVehiclePath = url.startsWith('/vehicles');
        const isFavorites = url.includes('favorites_only=1');
        return isVehiclePath && !isFavorites;
    }

    // Dashboard: check for nested dashboard routes
    if (
        item.title === __('nav.dealer_dashboard') ||
        item.title === __('nav.admin_dashboard')
    ) {
        return (
            url.startsWith('/dealer/dashboard') ||
            url.startsWith('/admin/dashboard')
        );
    }

    // Default to the standard isCurrentUrl
    return isCurrentUrl(item.href);
};

const activeNavItemRefs = ref<Record<number, HTMLElement>>({});
const indicatorStyle = computed(() => {
    const activeIndex = mainNavItems.value.findIndex((item) =>
        isNavItemActive(item),
    );
    const el = activeNavItemRefs.value[activeIndex];

    if (!el) return { opacity: 0, width: '0px', left: '0px' };

    return {
        left: `${el.offsetLeft}px`,
        width: `${el.offsetWidth}px`,
        opacity: 1,
    };
});
</script>

<template>
    <header class="dark sticky top-0 z-50 text-foreground print:hidden">
        <div class="border-b border-sidebar-border/80 bg-background">
            <div class="mx-auto flex h-16 items-center px-4 md:max-w-7xl">
                <!-- Mobile Menu (Now Hidden as we use Bottom Dock) -->
                <div class="hidden">
                    <SheetMenu side="left">
                        <template #trigger>
                            <Button
                                variant="ghost"
                                size="icon"
                                class="mr-2 h-9 w-9"
                            >
                                <Menu class="h-5 w-5" />
                            </Button>
                        </template>

                        <template #headerBranding>
                            <div class="flex items-center gap-x-2">
                                <AppLogo />
                            </div>
                        </template>

                        <div
                            class="flex h-full flex-1 flex-col justify-between space-y-4"
                        >
                            <nav class="space-y-1.5">
                                <template v-if="!auth || !auth.user">
                                    <Button
                                        :as="Link"
                                        :href="login()"
                                        variant="ghost"
                                        class="group relative w-full justify-start gap-4 rounded-xl px-4 py-8 text-muted-foreground transition-all duration-300 hover:bg-muted/50 hover:text-foreground"
                                    >
                                        <div
                                            class="flex h-10 w-10 items-center justify-center rounded-lg bg-background shadow-xs transition-colors group-hover:bg-muted/10"
                                        >
                                            <User class="h-5 w-5" />
                                        </div>
                                        <span
                                            class="font-heading text-sm font-bold tracking-widest uppercase"
                                        >
                                            Log in
                                        </span>
                                    </Button>
                                    <div class="my-4 h-px bg-border/40" />
                                </template>

                                <Button
                                    v-for="item in mainNavItems"
                                    :key="item.title"
                                    :as="Link"
                                    :href="item.href"
                                    variant="ghost"
                                    class="group relative w-full justify-start gap-4 rounded-xl px-4 py-8 transition-all duration-300"
                                    :class="{
                                        'bg-primary/10 text-primary hover:bg-primary/15':
                                            isNavItemActive(item),
                                        'text-muted-foreground hover:bg-muted/50 hover:text-foreground':
                                            !isNavItemActive(item),
                                    }"
                                >
                                    <div
                                        class="flex h-10 w-10 items-center justify-center rounded-lg bg-background shadow-xs transition-colors group-hover:bg-muted/10"
                                    >
                                        <component
                                            v-if="item.icon"
                                            :is="item.icon"
                                            class="h-5 w-5"
                                        />
                                    </div>
                                    <span
                                        class="font-heading text-sm font-bold tracking-widest uppercase"
                                    >
                                        {{ item.title }}
                                    </span>
                                </Button>
                            </nav>
                            <div class="flex flex-col space-y-4">
                                <a
                                    v-for="item in rightNavItems"
                                    :key="item.title"
                                    :href="toUrl(item.href)"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="flex items-center space-x-2 text-sm font-medium"
                                >
                                    <component
                                        v-if="item.icon"
                                        :is="item.icon"
                                        class="h-5 w-5"
                                    />
                                    <span>{{ item.title }}</span>
                                </a>
                            </div>
                            <InstallButton variant="outline" />
                        </div>
                    </SheetMenu>
                </div>

                <Link href="/" class="flex items-center gap-x-2">
                    <AppLogo />
                </Link>
                <!-- Desktop Menu -->
                <div class="hidden h-full lg:flex lg:flex-1">
                    <NavigationMenu class="ml-10 flex h-full items-stretch">
                        <NavigationMenuList
                            class="relative flex h-full items-stretch space-x-2"
                        >
                            <!-- Sliding Background Capsule -->
                            <div
                                class="pointer-events-none absolute inset-y-3 z-0 rounded bg-white shadow-lg transition-all duration-500 ease-[cubic-bezier(0.32,0.72,0,1)]"
                                :style="{
                                    left: indicatorStyle.left,
                                    width: indicatorStyle.width,
                                    opacity: indicatorStyle.opacity,
                                }"
                            ></div>

                            <!-- Sliding Indicator Underline (Primary Color) -->
                            <div
                                class="absolute bottom-0 h-0.5 bg-primary transition-all duration-500 ease-[cubic-bezier(0.32,0.72,0,1)]"
                                :style="indicatorStyle"
                            ></div>

                            <NavigationMenuItem
                                v-for="(item, index) in mainNavItems"
                                :key="index"
                                class="relative z-10 flex h-full items-center"
                                :ref="
                                    (el: any) => {
                                        activeNavItemRefs[index] = el?.$el;
                                    }
                                "
                            >
                                <Link
                                    :class="[
                                        isNavItemActive(item)
                                            ? 'font-bold text-neutral-900'
                                            : 'text-neutral-400',
                                        'group relative flex h-9 cursor-pointer items-center justify-center rounded-xl px-4 text-sm transition-all duration-300 hover:text-white',
                                    ]"
                                    :href="item.href"
                                >
                                    <component
                                        v-if="item.icon"
                                        :is="item.icon"
                                        class="mr-2 h-4 w-4 transition-colors duration-300"
                                        :class="
                                            isNavItemActive(item)
                                                ? 'text-primary'
                                                : 'group-hover:text-primary/70'
                                        "
                                    />
                                    {{ item.title }}
                                </Link>
                            </NavigationMenuItem>
                        </NavigationMenuList>
                    </NavigationMenu>
                </div>

                <div class="ml-auto flex items-center space-x-2">
                    <div class="relative flex items-center space-x-1">
                        <div class="hidden space-x-1 lg:flex">
                            <template
                                v-for="item in rightNavItems"
                                :key="item.title"
                            >
                                <TooltipProvider :delay-duration="0">
                                    <Tooltip>
                                        <TooltipTrigger>
                                            <Button
                                                variant="ghost"
                                                size="icon"
                                                as-child
                                                class="group h-9 w-9 cursor-pointer"
                                            >
                                                <a
                                                    :href="toUrl(item.href)"
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                >
                                                    <span class="sr-only">{{
                                                        item.title
                                                    }}</span>
                                                    <component
                                                        :is="item.icon"
                                                        class="size-5 opacity-80 group-hover:opacity-100"
                                                    />
                                                </a>
                                            </Button>
                                        </TooltipTrigger>
                                        <TooltipContent
                                            class="dark border border-background/50"
                                        >
                                            <p>{{ item.title }}</p>
                                        </TooltipContent>
                                    </Tooltip>
                                </TooltipProvider>
                            </template>
                        </div>
                    </div>

                    <TooltipProvider :delay-duration="0">
                        <Tooltip>
                            <TooltipTrigger as-child>
                                <Button
                                    variant="ghost"
                                    size="icon"
                                    class="group h-9 w-9 cursor-pointer"
                                    :class="{
                                        'bg-foreground/10 text-red-500':
                                            page.url.includes(
                                                'favorites_only=1',
                                            ),
                                    }"
                                    @click="handleFavoritesClick"
                                >
                                    <Star
                                        class="size-5 opacity-80 group-hover:opacity-100"
                                        :class="{
                                            'fill-red-500 text-red-500':
                                                page.url.includes(
                                                    'favorites_only=1',
                                                ),
                                        }"
                                    />
                                    <span class="sr-only">{{
                                        __('nav.favorites')
                                    }}</span>
                                </Button>
                            </TooltipTrigger>
                            <TooltipContent
                                class="dark border border-background/50"
                            >
                                <p>{{ __('nav.favorites') }}</p>
                            </TooltipContent>
                        </Tooltip>
                    </TooltipProvider>

                    <InstallButton show-tooltip class="hidden lg:flex" />

                    <LanguageSelector />

                    <DropdownMenu v-if="auth && auth.user">
                        <DropdownMenuTrigger :as-child="true">
                            <Button
                                variant="ghost"
                                size="icon"
                                class="relative size-10 w-auto cursor-pointer rounded-full p-1 transition-opacity focus-within:ring-2 focus-within:ring-primary hover:opacity-80"
                            >
                                <Avatar
                                    class="size-8 overflow-hidden rounded-full"
                                >
                                    <AvatarImage
                                        v-if="auth.user?.avatar"
                                        :src="auth.user.avatar"
                                        :alt="auth.user.first_name"
                                    />
                                    <AvatarFallback
                                        class="rounded-lg bg-neutral-200 font-semibold text-black dark:bg-foreground/15 dark:text-white"
                                    >
                                        {{
                                            getInitials(
                                                auth.user?.first_name,
                                                auth.user?.last_name,
                                            )
                                        }}
                                    </AvatarFallback>
                                </Avatar>
                            </Button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent align="end" class="w-56">
                            <UserMenuContent :user="auth.user" />
                        </DropdownMenuContent>
                    </DropdownMenu>
                    <template v-else>
                        <div class="hidden lg:block">
                            <Button
                                :as="Link"
                                :href="login()"
                                variant="outline"
                                class="h-9 px-4"
                            >
                                {{ __('nav.login') }}
                            </Button>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <LoginRequiredModal
            v-model:open="showLoginModal"
            title="Coup de cœur ?"
            description="Connectez-vous pour accéder à vos favoris et ne plus jamais perdre une annonce."
        />

        <div
            v-if="props.breadcrumbs.length > 1"
            class="flex w-full border-b border-sidebar-border/70"
        >
            <div
                class="mx-auto flex h-12 w-full items-center justify-start px-4 text-neutral-500 md:max-w-7xl"
            >
                <Breadcrumbs :breadcrumbs="breadcrumbs" />
            </div>
        </div>
    </header>
</template>
