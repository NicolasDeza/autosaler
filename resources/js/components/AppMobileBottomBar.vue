<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { Home, PlusCircle, User, Car } from 'lucide-vue-next';
import { computed } from 'vue';
import { useCurrentUrl } from '@/composables/useCurrentUrl';
import { usePermissions } from '@/composables/usePermissions';
import { useTranslation } from '@/composables/useTranslation';
import { login } from '@/routes';
import dealer from '@/routes/dealer';
import vehicles from '@/routes/vehicles';
import type { NavItem } from '@/types';
import type { ExtendedPageProps } from '@/types/inertia';

const { __ } = useTranslation();
const page = usePage<ExtendedPageProps>();
const { isCurrentUrl } = useCurrentUrl();
const { hasRole } = usePermissions();

const auth = computed(() => page.props.auth);

// Check if user is a dealer and hide if on creation page
const canCreateAd = computed(() => {
    if (!auth.value?.user) return false;
    if (!hasRole('dealer')) return false;
    // Don't show create button if we are already on the create page
    if (page.url.startsWith('/vehicles/create')) return false;
    return true;
});

/**
 * Intelligent helper to determine if a nav item should be marked as active.
 * Handles grouped routes and query parameters.
 */
const isActiveItem = (item: NavItem) => {
    const url = page.url;

    // Vehicles: check for any /vehicles path, but exclude if it's favorites
    if (item.title === (__('ui.vehicles') || 'Véhicules')) {
        const isVehiclePath = url.startsWith('/vehicles');
        const isFavorites = url.includes('favorites_only=1');
        return isVehiclePath && !isFavorites;
    }

    // Dashboard: check for nested dashboard routes
    if (item.title === __('nav.nav_dashboard')) {
        return url.startsWith('/dealer/dashboard');
    }

    // Default to the standard isCurrentUrl for Home and Login
    return isCurrentUrl(item.href);
};

const navItems = computed<NavItem[]>(() => {
    const items: NavItem[] = [
        {
            title: __('nav.nav_home'),
            href: '/',
            icon: Home,
        },
        {
            title: __('ui.vehicles') || 'Véhicules',
            href: vehicles.index().url,
            icon: Car,
        },
    ];

    // Profile item (login or dashboard/account)
    if (auth.value?.user) {
        items.push({
            title: __('nav.nav_dashboard'),
            href: dealer.dashboard().url,
            icon: User,
        });
    } else {
        items.push({
            title: __('nav.login'),
            href: login(),
            icon: User,
        });
    }

    return items;
});

const activeIndex = computed(() => {
    return navItems.value.findIndex((item) => isActiveItem(item));
});
</script>


<template>
    <div
        class="dark pointer-events-none fixed inset-x-0 bottom-0 z-50 flex flex-col items-center justify-end px-2 pb-4 md:hidden lg:hidden"
    >
        <!-- The Multi-Tier Dock Console -->
        <div
            class="pointer-events-auto relative mx-4 flex w-full max-w-lg flex-col overflow-hidden rounded-3xl border border-sidebar-border/80 bg-background shadow-[0_25px_50px_-12px_rgba(0,0,0,0.6)] backdrop-blur-xl transition-all duration-700 ease-[cubic-bezier(0.32,0.72,0,1)]"
        >
            <!-- TIER 1: TOOLS & ACTIONS (ALWAYS TARGET FOR TELEPORTS) -->
            <div
                class="relative z-10 w-full overflow-hidden border-b border-white/5 empty:hidden"
            >
                <div
                    class="mb-1 flex w-full flex-row items-center gap-2 px-3 pt-2 pb-1"
                >
                    <!-- Left: Global Quick Actions (Create Ad) -->
                    <div
                        v-if="canCreateAd"
                        class="shrink-0 border-r border-white/10 pr-2 pl-3"
                    >
                        <Link
                            :href="vehicles.create().url"
                            class="flex h-10 items-center gap-2 rounded-xl bg-white/5 px-3 transition-all hover:bg-white/10 active:scale-95"
                        >
                            <PlusCircle class="size-4 text-primary" />
                            <span
                                class="text-[10px] font-black tracking-widest text-white uppercase"
                                >{{ __('dealer.create_ad') }}</span
                            >
                        </Link>
                    </div>


                    <!-- Right/Next: Contextual Tools from Slot -->
                    <div
                        class="flex flex-1 items-center justify-center empty:hidden"
                    >
                        <slot name="sticky-bottom-mobile" />
                    </div>
                </div>
            </div>

            <!-- TIER 2: MAIN NAVIGATION FLOW -->
            <nav
                class="relative z-20 flex h-16 w-full items-center justify-around px-2"
            >
                <!-- Sliding Background Capsule -->
                <div
                    class="pointer-events-none absolute inset-y-2 z-0 rounded-2xl bg-white shadow-lg transition-all duration-500 ease-[cubic-bezier(0.32,0.72,0,1)]"
                    :style="{
                        width: `${100 / navItems.length - 2}%`,
                        left: `${(activeIndex * 100) / navItems.length + 1}%`,
                    }"
                ></div>

                <Link
                    v-for="item in navItems"
                    :key="item.title"
                    :href="item.href"
                    class="relative z-10 flex flex-1 flex-col items-center justify-center gap-1.5 transition-all duration-300"
                    :class="[
                        isActiveItem(item)
                            ? 'scale-105 opacity-100'
                            : 'text-neutral-400 opacity-60 hover:opacity-100',
                    ]"
                >
                    <!-- Nav Item Content -->
                    <div
                        class="relative flex flex-col items-center px-4 py-1.5 transition-all duration-500"
                    >
                        <component
                            :is="item.icon"
                            class="size-5 shrink-0 transition-colors"
                            :class="isActiveItem(item) ? 'text-red-500' : ''"
                        />
                        <span
                            class="font-heading text-[9px] font-black tracking-tight whitespace-nowrap uppercase transition-colors"
                            :class="isActiveItem(item) ? 'text-neutral-900' : ''"
                        >
                            {{ item.title }}
                        </span>
                    </div>

                    <!-- Active Indicator Line -->
                    <div
                        v-if="isActiveItem(item)"
                        class="absolute -bottom-1 h-0.5 w-3 animate-pulse rounded-full bg-primary"
                    ></div>
                </Link>
            </nav>

        </div>
    </div>
</template>

<style scoped>
/* Smooth transition for the height of the dock itself */
.rounded-3xl {
    transition:
        height 0.7s cubic-bezier(0.32, 0.72, 0, 1),
        width 0.7s cubic-bezier(0.32, 0.72, 0, 1);
}
</style>
