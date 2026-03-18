<template>
    <Head :title="__('admin.dashboard_title')" />

    <AppLayout>
        <div class="flex min-h-[calc(100vh-64px)] flex-col bg-linear-to-b from-background to-muted/20 lg:flex-row">
            <!-- Navigation Sidebar -->
            <aside class="w-full shrink-0 border-r border-border/40 bg-background/50 backdrop-blur-xl lg:w-72">
                <div class="sticky top-16 flex flex-col p-6 h-full">
                    <div class="mb-8 px-2">
                        <h2 class="font-heading text-[10px] font-bold tracking-[0.2em] text-muted-foreground uppercase">
                            {{ __('admin.menu_title') }}
                        </h2>
                    </div>

                    <nav class="space-y-1.5">
                        <Button
                            variant="ghost"
                            class="group relative w-full justify-start gap-3 rounded-xl px-4 py-6 transition-all duration-300"
                            :class="{
                                'bg-primary/10 text-primary hover:bg-primary/15': tab === 'stats',
                                'text-muted-foreground hover:bg-muted/50 hover:text-foreground': tab !== 'stats'
                            }"
                            @click="switchTab('stats')"
                        >
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-background shadow-xs transition-colors group-hover:bg-muted/10">
                                <LayoutDashboard class="h-4 w-4" />
                            </div>
                            <span class="font-heading text-xs font-bold tracking-widest uppercase">
                                {{ __('admin.tab_stats') }}
                            </span>
                            <div v-if="tab === 'stats'" class="absolute right-3 h-1.5 w-1.5 rounded-full bg-primary shadow-[0_0_8px_rgba(220,38,38,0.5)]"></div>
                        </Button>

                        <Button
                            variant="ghost"
                            class="group relative w-full justify-start gap-3 rounded-xl px-4 py-6 transition-all duration-300"
                            :class="{
                                'bg-primary/10 text-primary hover:bg-primary/15': tab === 'vehicles',
                                'text-muted-foreground hover:bg-muted/50 hover:text-foreground': tab !== 'vehicles'
                            }"
                            @click="switchTab('vehicles')"
                        >
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-background shadow-xs transition-colors group-hover:bg-muted/10">
                                <CarFront class="h-4 w-4" />
                            </div>
                            <span class="font-heading text-xs font-bold tracking-widest uppercase">
                                {{ __('admin.tab_vehicles') }}
                            </span>
                            <div v-if="tab === 'vehicles'" class="absolute right-3 h-1.5 w-1.5 rounded-full bg-primary shadow-[0_0_8px_rgba(220,38,38,0.5)]"></div>
                        </Button>

                        <Button
                            variant="ghost"
                            class="group relative w-full justify-start gap-3 rounded-xl px-4 py-6 transition-all duration-300"
                            :class="{
                                'bg-primary/10 text-primary hover:bg-primary/15': tab === 'users',
                                'text-muted-foreground hover:bg-muted/50 hover:text-foreground': tab !== 'users'
                            }"
                            @click="switchTab('users')"
                        >
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-background shadow-xs transition-colors group-hover:bg-muted/10">
                                <Users class="h-4 w-4" />
                            </div>
                            <span class="font-heading text-xs font-bold tracking-widest uppercase">
                                {{ __('admin.tab_users') }}
                            </span>
                            <div v-if="tab === 'users'" class="absolute right-3 h-1.5 w-1.5 rounded-full bg-primary shadow-[0_0_8px_rgba(220,38,38,0.5)]"></div>
                        </Button>
                    </nav>

                    <div class="mt-auto hidden lg:block">
                        <Card class="border-border/40 bg-muted/30 backdrop-blur-sm">
                            <CardContent class="p-4">
                                <p class="text-[10px] font-medium text-muted-foreground">Admin Access</p>
                                <p class="mt-1 text-xs font-bold">{{ $page.props.auth.user.first_name }} {{ $page.props.auth.user.last_name }}</p>
                            </CardContent>
                        </Card>
                    </div>
                </div>
            </aside>

            <!-- Main Workspace -->
            <main class="min-w-0 flex-1 px-4 py-8 lg:px-10">
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
                                    :subscription-plans="subscription_plans || []"
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
import { Head, router } from '@inertiajs/vue3';
import { LayoutDashboard, CarFront, Users } from 'lucide-vue-next';
import { index as adminDashboardIndex } from '@/actions/App/Http/Controllers/Admin/AdminDashboardController';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { useTranslation } from '@/composables/useTranslation';
import AppLayout from '@/layouts/AppLayout.vue';

// Components
import DashboardStats from '@/components/Admin/DashboardStats.vue';
import UsersList from '@/components/Admin/UsersList.vue';
import VehiclesList from '@/components/Admin/VehiclesList.vue';

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
