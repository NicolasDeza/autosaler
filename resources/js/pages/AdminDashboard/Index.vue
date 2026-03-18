<template>
    <Head :title="__('admin.dashboard_title')" />

    <AppLayout>
        <div
            class="flex min-h-[calc(100vh-64px)] flex-col bg-linear-to-b from-background to-muted/20 lg:flex-row lg:gap-8 lg:p-8"
        >
            <!-- Mobile Menu -->
            <div class="lg:hidden">
                <SheetMenu
                    v-model:open="isOpen"
                    side="left"
                    :title="__('admin.menu_title')"
                    :icon="ShieldCheck"
                    with-floating-button
                >
                    <nav class="space-y-1.5">
                        <Button
                            v-for="item in navItems"
                            :key="item.id"
                            variant="ghost"
                            class="group relative w-full justify-start gap-4 rounded-xl px-4 py-8 transition-all duration-300"
                            :class="{
                                'bg-primary/10 text-primary hover:bg-primary/15':
                                    tab === item.id,
                                'text-muted-foreground hover:bg-muted/50 hover:text-foreground':
                                    tab !== item.id,
                            }"
                            @click="switchTab(item.id)"
                        >
                            <div
                                class="flex h-10 w-10 items-center justify-center rounded-lg bg-background shadow-xs transition-colors group-hover:bg-muted/10"
                            >
                                <component :is="item.icon" class="h-5 w-5" />
                            </div>
                            <span
                                class="font-heading text-sm font-bold tracking-widest uppercase"
                            >
                                {{ item.label }}
                            </span>
                        </Button>
                    </nav>

                    <template #footer>
                        <div
                            class="flex w-full items-center gap-4 rounded-xl border border-border/40 bg-muted/30 p-4 backdrop-blur-sm"
                        >
                            <div
                                class="flex h-10 w-10 items-center justify-center rounded-full bg-primary/10 font-bold text-primary"
                            >
                                {{ page.props.auth.user.first_name?.[0]
                                }}{{ page.props.auth.user.last_name?.[0] }}
                            </div>
                            <div class="flex-1 overflow-hidden text-left">
                                <p class="truncate text-xs font-bold">
                                    {{ page.props.auth.user.first_name }}
                                    {{ page.props.auth.user.last_name }}
                                </p>
                                <p
                                    class="text-[10px] font-medium text-muted-foreground"
                                >
                                    Admin Access
                                </p>
                            </div>
                        </div>
                    </template>
                </SheetMenu>
            </div>

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
            <main class="min-w-0 flex-1 p-4 lg:p-0">
                <div class="mx-auto max-w-7xl">
                    <Transition name="content-fade" mode="out-in">
                        <div :key="tab" class="space-y-8">
                            <template v-if="tab === 'stats'">
                                <DashboardStats :stats="stats || {}" />
                            </template>

                            <template v-else-if="tab === 'vehicles'">
                                <VehiclesList
                                    :ads="ads || {}"
                                    :filters="filters || {}"
                                    :companies="companies || []"
                                    :brands="brands || []"
                                    :models="models || []"
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
    </AppLayout>
</template>

<script setup lang="ts">
import { Head, router, usePage } from '@inertiajs/vue3';
import { LayoutDashboard, CarFront, Users, ShieldCheck } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import { index as adminDashboardIndex } from '@/actions/App/Http/Controllers/Admin/AdminDashboardController';
import DashboardStats from '@/components/Admin/DashboardStats.vue';
import UsersList from '@/components/Admin/UsersList.vue';
import VehiclesList from '@/components/Admin/VehiclesList.vue';
import SheetMenu from '@/components/SheetMenu.vue';
import { Button } from '@/components/ui/button';
import { useTranslation } from '@/composables/useTranslation';
import AppLayout from '@/layouts/AppLayout.vue';

interface Props {
    tab: 'stats' | 'vehicles' | 'users';
    stats?: any;
    ads?: any;
    users?: any;
    filters?: any;
    companies?: any[];
    brands?: any[];
    models?: any[];
    roles?: any[];
    subscription_plans?: any[];
}

defineProps<Props>();
const { __ } = useTranslation();
const page = usePage();

const isOpen = ref(false);

const navItems = computed(() => [
    { id: 'stats', label: __('admin.tab_stats'), icon: LayoutDashboard },
    { id: 'vehicles', label: __('admin.tab_vehicles'), icon: CarFront },
    { id: 'users', label: __('admin.tab_users'), icon: Users },
]);

const switchTab = (newTab: string) => {
    isOpen.value = false;
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
