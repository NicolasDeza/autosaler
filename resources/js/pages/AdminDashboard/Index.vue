<template>
    <Head :title="__('admin.dashboard_title')" />

    <AppContent>
        <div
            class="flex min-h-[calc(100vh-64px)] flex-col bg-linear-to-b from-background to-muted/20 lg:flex-row lg:gap-8 lg:py-8"
        >
            <!-- Mobile Menu (Teleported to Bottom Bar) -->
            <Teleport to="#sticky-bottom-mobile-portal" defer>
                <div class="flex h-full w-full items-center justify-around gap-1 px-1 py-1">
                    <button
                        v-for="item in navItems"
                        :key="item.id"
                        class="bottom-bar-tool-btn relative flex-1"
                        :class="tab === item.id ? 'bg-white/15!' : 'opacity-70'"
                        @click="switchTab(item.id)"
                    >
                        <component 
                            :is="item.icon" 
                            :class="tab === item.id ? 'text-white' : ''" 
                        />
                        <span class="hidden" :class="tab === item.id ? 'text-white' : ''">{{ item.label }}</span>
                        <!-- Active Indicator -->
                        <div
                            v-if="tab === item.id"
                            class="absolute -bottom-1 left-1/2 h-0.5 w-6 -translate-x-1/2 rounded-full bg-white shadow-[0_0_8px_rgba(255,255,255,0.8)]"
                        ></div>
                    </button>
                </div>
            </Teleport>

            <!-- Navigation Sidebar (Desktop) -->
            <aside
                class="sticky top-24 hidden h-fit w-full shrink-0 overflow-hidden rounded-2xl bg-card shadow-xl ring-1 ring-border/50 backdrop-blur-xl lg:block lg:w-80"
            >
                <div
                    class="dark relative flex items-center gap-3 bg-background px-6 py-5"
                >
                    <div
                        class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary/10 text-primary"
                    >
                        <ShieldCheck class="h-4 w-4" />
                    </div>
                    <h2
                        class="font-heading text-sm font-bold tracking-widest text-white uppercase"
                    >
                        {{ __('admin.menu_title') }}
                    </h2>
                </div>

                <div class="h-px bg-border/40" />

                <div class="p-4">
                    <nav class="space-y-2">
                        <Button
                            v-for="item in navItems"
                            :key="item.id"
                            variant="ghost"
                            class="group relative w-full justify-start gap-3 rounded-xl px-3 py-6 transition-all duration-300 hover:cursor-pointer"
                            :class="{
                                'bg-primary/10 text-primary hover:bg-primary/15':
                                    tab === item.id,
                                'text-muted-foreground hover:bg-muted/50 hover:text-foreground':
                                    tab !== item.id,
                            }"
                            @click="switchTab(item.id)"
                        >
                            <div
                                class="flex h-8 w-8 items-center justify-center rounded-lg bg-background shadow-xs transition-colors group-hover:bg-muted/10"
                            >
                                <component :is="item.icon" class="h-4 w-4" />
                            </div>
                            <span
                                class="font-heading text-xs font-bold tracking-widest uppercase"
                            >
                                {{ item.label }}
                            </span>
                        </Button>
                    </nav>

                    <div class="mt-6 border-t border-border/40 pt-6">
                        <div
                            class="group relative overflow-hidden rounded-xl border border-border/40 bg-muted/30 p-4 transition-all hover:bg-muted/50"
                        >
                            <div class="relative z-10 flex items-center gap-3">
                                <div
                                    class="flex h-8 w-8 items-center justify-center rounded-full bg-primary/10 text-[10px] font-bold text-primary"
                                >
                                    {{ page.props.auth.user.first_name?.[0]
                                    }}{{ page.props.auth.user.last_name?.[0] }}
                                </div>
                                <div>
                                    <p
                                        class="text-[10px] font-medium text-muted-foreground"
                                    >
                                        Admin Access
                                    </p>
                                    <p class="text-xs font-bold">
                                        {{ page.props.auth.user.first_name }}
                                        {{ page.props.auth.user.last_name }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Main Workspace -->
            <main class="min-w-0 flex-1 lg:mt-0">
                <div class="mx-auto max-w-7xl">
                    <Transition name="content-fade" mode="out-in">
                        <div :key="tab" class="space-y-8">
                            <template v-if="tab === 'stats'">
                                <DashboardStats :stats="stats || {}" />
                            </template>

                            <template v-else-if="tab === 'catalog'">
                                <VehicleCatalog
                                    :brands="brands || []"
                                    :models="models || []"
                                    :filters="filters || {}"
                                />
                            </template>

                            <template v-else-if="tab === 'users'">
                                <UsersList
                                    :users="users || {}"
                                    :filters="filters || {}"
                                    :roles="roles || []"
                                    :subscription-plans="
                                        subscription_plans || []
                                    "
                                />
                            </template>
                        </div>
                    </Transition>
                </div>
            </main>
        </div>
    </AppContent>
</template>

<script setup lang="ts">
import { Head, router, usePage } from '@inertiajs/vue3';
import { LayoutDashboard, CarFront, Users, ShieldCheck } from 'lucide-vue-next';
import { computed } from 'vue';
import { index as adminDashboardIndex } from '@/actions/App/Http/Controllers/Admin/AdminDashboardController';
import DashboardStats from '@/components/Admin/DashboardStats.vue';
import UsersList from '@/components/Admin/UsersList.vue';
import VehicleCatalog from '@/components/Admin/VehicleCatalog.vue';
import AppContent from '@/components/AppContent.vue';
import { Button } from '@/components/ui/button';
import { useTranslation } from '@/composables/useTranslation';

interface Props {
    tab: 'stats' | 'catalog' | 'users';
    stats?: any;
    users?: any;
    filters?: any;
    brands?: any[];
    models?: any[];
    roles?: any[];
    subscription_plans?: any[];
}

defineProps<Props>();
const { __ } = useTranslation();
const page = usePage();

const navItems = computed(() => [
    { id: 'stats', label: __('admin.tab_stats'), icon: LayoutDashboard },
    { id: 'catalog', label: __('admin.tab_catalog'), icon: CarFront },
    { id: 'users', label: __('admin.tab_users'), icon: Users },
]);

const switchTab = (newTab: string) => {
    router.get(
        adminDashboardIndex.url({ query: { tab: newTab } }),
        {},
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        },
    );
};
</script>

<style scoped>
.page-fade-enter-active,
.page-fade-leave-active {
    transition:
        opacity 0.25s ease,
        transform 0.25s ease;
}

.page-fade-enter-from {
    opacity: 0;
    transform: translateY(8px);
}

.page-fade-leave-to {
    opacity: 0;
    transform: translateY(-8px);
}
</style>
