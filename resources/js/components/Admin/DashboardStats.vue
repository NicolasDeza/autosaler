<template>
    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
        <!-- Section Véhicules -->
        <Card class="group relative overflow-hidden border-border/40 bg-background/50 shadow-lg backdrop-blur-xl transition-all duration-500 hover:shadow-primary/5">
            <div class="absolute inset-x-0 top-0 h-1 bg-primary/20 transition-all duration-500 group-hover:h-1.5 group-hover:bg-primary"></div>
            <CardHeader class="flex flex-row items-center justify-between pb-3">
                <CardTitle class="font-heading text-[10px] font-bold tracking-[0.2em] text-muted-foreground uppercase">
                    {{ __('admin.stats.total_vehicles') }}
                </CardTitle>
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-primary/10 text-primary transition-all duration-500 group-hover:scale-110 group-hover:bg-primary group-hover:text-white group-hover:shadow-lg group-hover:shadow-primary/20">
                    <CarFront class="h-5 w-5" />
                </div>
            </CardHeader>
            <CardContent>
                <div class="font-heading flex items-baseline gap-2">
                    <span class="text-4xl font-bold tracking-tight">{{ stats.vehicles.total }}</span>
                </div>
                <div class="mt-4 flex flex-col gap-2">
                    <div class="flex items-center justify-between text-[10px] font-bold tracking-widest text-green-500 uppercase">
                        <span>{{ stats.vehicles.active }} {{ __('admin.stats.active') }}</span>
                        <span>{{ Math.round((stats.vehicles.active / stats.vehicles.total) * 100) }}%</span>
                    </div>
                    <div class="h-1.5 w-full overflow-hidden rounded-full bg-muted/50 p-[1px]">
                        <div
                            class="h-full rounded-full bg-linear-to-r from-green-500/80 to-green-500 transition-all duration-1000 ease-out"
                            :style="{ width: (stats.vehicles.active / stats.vehicles.total) * 100 + '%' }"
                        ></div>
                    </div>
                </div>
            </CardContent>
        </Card>

        <!-- Section Utilisateurs -->
        <Card class="group relative overflow-hidden border-border/40 bg-background/50 shadow-lg backdrop-blur-xl transition-all duration-500 hover:shadow-blue-500/5">
            <div class="absolute inset-x-0 top-0 h-1 bg-blue-500/20 transition-all duration-500 group-hover:h-1.5 group-hover:bg-blue-500"></div>
            <CardHeader class="flex flex-row items-center justify-between pb-3">
                <CardTitle class="font-heading text-[10px] font-bold tracking-[0.2em] text-muted-foreground uppercase">
                    {{ __('admin.stats.total_users') }}
                </CardTitle>
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-500/10 text-blue-500 transition-all duration-500 group-hover:scale-110 group-hover:bg-blue-500 group-hover:text-white group-hover:shadow-lg group-hover:shadow-blue-500/20">
                    <Users class="h-5 w-5" />
                </div>
            </CardHeader>
            <CardContent>
                <div class="font-heading flex items-baseline gap-2">
                    <span class="text-4xl font-bold tracking-tight">{{ stats.users.total }}</span>
                </div>
                <div class="mt-4 flex items-center gap-2 text-[10px] font-bold tracking-widest text-muted-foreground/60 uppercase">
                    <div class="h-1.5 w-1.5 rounded-full bg-blue-500"></div>
                    {{ stats.users.users }} {{ __('admin.roles.user') }}s
                </div>
            </CardContent>
        </Card>

        <!-- Section Entreprises -->
        <Card class="group relative overflow-hidden border-border/40 bg-background/50 shadow-lg backdrop-blur-xl transition-all duration-500 hover:shadow-amber-500/5">
            <div class="absolute inset-x-0 top-0 h-1 bg-amber-500/20 transition-all duration-500 group-hover:h-1.5 group-hover:bg-amber-500"></div>
            <CardHeader class="flex flex-row items-center justify-between pb-3">
                <CardTitle class="font-heading text-[10px] font-bold tracking-[0.2em] text-muted-foreground uppercase">
                    {{ __('admin.stats.total_companies') }}
                </CardTitle>
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-500/10 text-amber-500 transition-all duration-500 group-hover:scale-110 group-hover:bg-amber-500 group-hover:text-white group-hover:shadow-lg group-hover:shadow-amber-500/20">
                    <Building2 class="h-5 w-5" />
                </div>
            </CardHeader>
            <CardContent>
                <div class="font-heading flex items-baseline gap-2">
                    <span class="text-4xl font-bold tracking-tight">{{ stats.companies.total }}</span>
                </div>
                <div class="mt-4 flex items-center gap-2 text-[10px] font-bold tracking-widest text-muted-foreground/60 uppercase">
                    <TrendingUp class="h-3.5 w-3.5 text-green-500" />
                    {{ stats.companies.active }} {{ __('admin.stats.active_accounts') }}
                </div>
            </CardContent>
        </Card>

        <!-- Activité Récente -->
        <Card class="group relative overflow-hidden border-border/40 bg-background/50 shadow-lg backdrop-blur-xl transition-all duration-500 hover:shadow-primary/10">
            <div class="absolute inset-x-0 top-0 h-1 bg-primary/30 transition-all duration-500 group-hover:h-1.5 group-hover:bg-primary"></div>
            <CardHeader class="flex flex-row items-center justify-between pb-3">
                <CardTitle class="font-heading text-[10px] font-bold tracking-[0.2em] text-primary uppercase">
                    {{ __('admin.stats.new_ads_week') }}
                </CardTitle>
                <div class="flex h-10 w-10 animate-pulse items-center justify-center rounded-xl bg-primary/20 text-primary group-hover:animate-none group-hover:bg-primary group-hover:text-white">
                    <Zap class="h-5 w-5" />
                </div>
            </CardHeader>
            <CardContent>
                <div class="font-heading flex items-baseline gap-2 text-primary">
                    <span class="text-4xl font-bold tracking-tight">{{ stats.recent_activity.new_ads_week }}</span>
                </div>
                <div class="mt-4 flex items-center gap-2 text-[10px] font-bold tracking-widest text-primary/60 uppercase">
                    <Plus class="h-3.5 w-3.5" />
                    {{ stats.recent_activity.new_users_week }} {{ __('admin.stats.new_members') }}
                </div>
            </CardContent>
        </Card>
    </div>

    <!-- Charts Section -->
    <div class="grid gap-6 md:grid-cols-2">
        <Card class="border-border/40 bg-background/50 shadow-xl backdrop-blur-xl">
            <CardHeader class="pb-8">
                <CardTitle class="font-heading text-xl font-bold tracking-tight">
                    {{ __('admin.stats.user_distribution') }}
                </CardTitle>
                <CardDescription class="font-body text-sm text-muted-foreground/70">
                    {{ __('admin.stats.user_distribution_desc') }}
                </CardDescription>
            </CardHeader>
            <CardContent>
                <div class="space-y-8">
                    <div class="group/bar space-y-3">
                        <div class="flex items-end justify-between">
                            <div class="flex flex-col gap-0.5">
                                <span class="font-heading text-[10px] font-bold tracking-widest text-muted-foreground uppercase">{{ __('admin.roles.user') }}</span>
                                <span class="font-heading text-xl font-bold">{{ stats.users.users }}</span>
                            </div>
                            <span class="font-heading text-xs font-bold text-primary/60">{{ Math.round((stats.users.users / stats.users.total) * 100) }}%</span>
                        </div>
                        <div class="h-2 w-full overflow-hidden rounded-full bg-muted/40 p-[1px]">
                            <div
                                class="h-full rounded-full bg-linear-to-r from-primary/60 to-primary transition-all duration-1000 group-hover/bar:from-primary group-hover/bar:to-primary group-hover/bar:shadow-[0_0_12px_rgba(220,38,38,0.2)]"
                                :style="{ width: (stats.users.users / stats.users.total) * 100 + '%' }"
                            ></div>
                        </div>
                    </div>

                    <div class="group/bar space-y-3">
                        <div class="flex items-end justify-between">
                            <div class="flex flex-col gap-0.5">
                                <span class="font-heading text-[10px] font-bold tracking-widest text-muted-foreground uppercase">{{ __('admin.roles.dealer') }}</span>
                                <span class="font-heading text-xl font-bold">{{ stats.users.dealers }}</span>
                            </div>
                            <span class="font-heading text-xs font-bold text-blue-500/60">{{ Math.round((stats.users.dealers / stats.users.total) * 100) }}%</span>
                        </div>
                        <div class="h-2 w-full overflow-hidden rounded-full bg-muted/40 p-[1px]">
                            <div
                                class="h-full rounded-full bg-linear-to-r from-blue-500/60 to-blue-500 transition-all duration-1000 group-hover/bar:from-blue-500 group-hover/bar:to-blue-500 group-hover/bar:shadow-[0_0_12px_rgba(59,130,246,0.2)]"
                                :style="{ width: (stats.users.dealers / stats.users.total) * 100 + '%' }"
                            ></div>
                        </div>
                    </div>

                    <div class="group/bar space-y-3">
                        <div class="flex items-end justify-between">
                            <div class="flex flex-col gap-0.5">
                                <span class="font-heading text-[10px] font-bold tracking-widest text-muted-foreground uppercase">{{ __('admin.roles.admin') }}</span>
                                <span class="font-heading text-xl font-bold">{{ stats.users.admins }}</span>
                            </div>
                            <span class="font-heading text-xs font-bold text-indigo-500/60">{{ Math.round((stats.users.admins / stats.users.total) * 100) }}%</span>
                        </div>
                        <div class="h-2 w-full overflow-hidden rounded-full bg-muted/40 p-[1px]">
                            <div
                                class="h-full rounded-full bg-linear-to-r from-indigo-500/60 to-indigo-500 transition-all duration-1000 group-hover/bar:from-indigo-500 group-hover/bar:to-indigo-500 group-hover/bar:shadow-[0_0_12px_rgba(99,102,241,0.2)]"
                                :style="{ width: (stats.users.admins / stats.users.total) * 100 + '%' }"
                            ></div>
                        </div>
                    </div>
                </div>
            </CardContent>
        </Card>

        <Card class="border-border/40 bg-background/50 shadow-xl backdrop-blur-xl">
            <CardHeader class="pb-8">
                <CardTitle class="font-heading text-xl font-bold tracking-tight">
                    {{ __('admin.stats.ad_status') }}
                </CardTitle>
                <CardDescription class="font-body text-sm text-muted-foreground/70">
                    {{ __('admin.stats.ad_status_desc') }}
                </CardDescription>
            </CardHeader>
            <CardContent>
                <div class="grid h-full auto-rows-fr grid-cols-1 gap-4 sm:grid-cols-3">
                    <div class="flex flex-col items-center justify-center rounded-2xl border border-border/40 bg-muted/20 p-6 transition-all duration-300 hover:border-green-500/30 hover:bg-green-500/[0.02]">
                        <span class="font-heading text-4xl font-bold text-green-500">{{ stats.vehicles.active }}</span>
                        <span class="font-heading mt-2 text-[10px] font-bold tracking-widest text-muted-foreground uppercase">{{ __('admin.stats.active') }}</span>
                    </div>
                    <div class="flex flex-col items-center justify-center rounded-2xl border border-border/40 bg-muted/20 p-6 transition-all duration-300 hover:border-amber-500/30 hover:bg-amber-500/[0.02]">
                        <span class="font-heading text-4xl font-bold text-amber-500">{{ stats.vehicles.draft }}</span>
                        <span class="font-heading mt-2 text-[10px] font-bold tracking-widest text-muted-foreground uppercase">{{ __('admin.stats.draft') }}</span>
                    </div>
                    <div class="flex flex-col items-center justify-center rounded-2xl border border-border/40 bg-muted/20 p-6 transition-all duration-300 hover:border-blue-500/30 hover:bg-blue-500/[0.02]">
                        <span class="font-heading text-4xl font-bold text-blue-500">{{ stats.vehicles.pending }}</span>
                        <span class="font-heading mt-2 text-[10px] font-bold tracking-widest text-muted-foreground uppercase">{{ __('admin.stats.pending') }}</span>
                    </div>
                </div>
            </CardContent>
        </Card>
    </div>
</template>

<script setup lang="ts">
import {
    Building2,
    CarFront,
    Users,
    TrendingUp,
    Zap,
    Plus,
} from 'lucide-vue-next';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { useTranslation } from '@/composables/useTranslation';

defineProps<{
    stats: any;
}>();

const { __ } = useTranslation();
</script>
