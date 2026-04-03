<template>
    <div class="grid gap-6 md:grid-cols-2">
        <Card
            class="group relative overflow-hidden border-border/40 bg-background/50 shadow-lg backdrop-blur-xl transition-all duration-500 hover:shadow-green-500/10"
        >
            <div
                class="absolute inset-x-0 top-0 h-1 bg-green-500/20 transition-all duration-500 group-hover:h-1.5 group-hover:bg-green-500"
            ></div>
            <CardHeader class="flex flex-row items-center justify-between pb-2">
                <CardTitle
                    class="font-heading text-[10px] font-bold tracking-[0.2em] text-muted-foreground uppercase"
                >
                    {{ __('admin.stats.ads_overview') }}
                </CardTitle>
                <div
                    class="flex h-10 w-10 items-center justify-center rounded-xl bg-green-500/10 text-green-500 transition-all duration-500 group-hover:scale-110 group-hover:bg-green-500 group-hover:text-white group-hover:shadow-lg group-hover:shadow-green-500/20"
                >
                    <CarFront class="h-5 w-5" />
                </div>
            </CardHeader>
            <CardContent>
                <div class="flex items-start justify-between">
                    <div class="flex flex-col">
                        <span
                            class="font-heading text-5xl font-bold tracking-tight"
                            >{{ stats.vehicles.active }}</span
                        >
                        <div
                            class="mt-2 flex items-center gap-2 text-[10px] font-bold tracking-widest text-muted-foreground uppercase"
                        >
                            <CheckCircle2 class="h-3.5 w-3.5 text-green-500" />
                            {{ __('admin.stats.currently_published') }}
                        </div>
                    </div>

                    <div class="flex flex-col items-end text-right">
                        <div
                            class="font-heading flex items-center gap-1 text-2xl font-bold text-green-500"
                        >
                            <TrendingUp class="h-4 w-4" />
                            +{{ totalNewAds7Days }}
                        </div>
                        <span
                            class="mt-1 text-[10px] font-bold tracking-widest text-muted-foreground/70 uppercase"
                        >
                            {{ __('admin.stats.in_last_7_days') }}
                        </span>
                    </div>
                </div>

                <div class="mt-8 h-[80px] w-full">
                    <VisXYContainer
                        :data="stats.evolution?.ads || []"
                        :height="80"
                        :margin="{ top: 5, right: 0, bottom: 5, left: 0 }"
                    >
                        <VisLine
                            :x="xAccessor"
                            :y="yAccessor"
                            color="#22c55e"
                            :lineWidth="3"
                        />
                    </VisXYContainer>
                </div>
            </CardContent>
        </Card>

        <Card
            class="group relative overflow-hidden border-border/40 bg-background/50 shadow-lg backdrop-blur-xl transition-all duration-500 hover:shadow-purple-500/10"
        >
            <div
                class="absolute inset-x-0 top-0 h-1 bg-purple-500/20 transition-all duration-500 group-hover:h-1.5 group-hover:bg-purple-500"
            ></div>
            <CardHeader class="flex flex-row items-center justify-between pb-2">
                <CardTitle
                    class="font-heading text-[10px] font-bold tracking-[0.2em] text-muted-foreground uppercase"
                >
                    {{ __('admin.stats.users_overview') }}
                </CardTitle>
                <div
                    class="flex h-10 w-10 items-center justify-center rounded-xl bg-purple-500/10 text-purple-500 transition-all duration-500 group-hover:scale-110 group-hover:bg-purple-500 group-hover:text-white group-hover:shadow-lg group-hover:shadow-purple-500/20"
                >
                    <Users class="h-5 w-5" />
                </div>
            </CardHeader>
            <CardContent>
                <div class="flex items-start justify-between">
                    <div class="flex flex-col">
                        <span
                            class="font-heading text-5xl font-bold tracking-tight"
                            >{{ stats.users.total_non_admin }}</span
                        >
                        <div
                            class="mt-2 flex items-center gap-2 text-[10px] font-bold tracking-widest text-muted-foreground uppercase"
                        >
                            <Users class="h-3.5 w-3.5 text-purple-500" />
                            {{ __('admin.stats.registered_members') }}
                        </div>
                    </div>

                    <div class="flex flex-col items-end text-right">
                        <div
                            class="font-heading flex items-center gap-1 text-2xl font-bold text-purple-500"
                        >
                            <TrendingUp class="h-4 w-4" />
                            +{{ totalNewUsers7Days }}
                        </div>
                        <span
                            class="mt-1 text-[10px] font-bold tracking-widest text-muted-foreground/70 uppercase"
                        >
                            {{ __('admin.stats.in_last_7_days') }}
                        </span>
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-2 gap-6">
                    <div class="flex flex-col gap-1.5">
                        <span
                            class="flex justify-between text-[10px] font-bold tracking-widest text-muted-foreground uppercase"
                            ><span>{{ __('admin.stats.private_users') }}</span
                            ><span class="text-blue-500">{{
                                stats.users.users
                            }}</span></span
                        >
                        <div
                            class="h-1.5 w-full overflow-hidden rounded-full bg-muted/50"
                        >
                            <div
                                class="h-full rounded-full bg-blue-500"
                                :style="{
                                    width:
                                        (stats.users.users /
                                            (stats.users.total_non_admin ||
                                                1)) *
                                            100 +
                                        '%',
                                }"
                            ></div>
                        </div>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <span
                            class="flex justify-between text-[10px] font-bold tracking-widest text-muted-foreground uppercase"
                            ><span>{{ __('admin.stats.professional_users') }}</span
                            ><span class="text-indigo-500">{{
                                stats.users.dealers
                            }}</span></span
                        >
                        <div
                            class="h-1.5 w-full overflow-hidden rounded-full bg-muted/50"
                        >
                            <div
                                class="h-full rounded-full bg-indigo-500"
                                :style="{
                                    width:
                                        (stats.users.dealers /
                                            (stats.users.total_non_admin ||
                                                1)) *
                                            100 +
                                        '%',
                                }"
                            ></div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 h-[80px] w-full">
                    <VisXYContainer
                        :data="stats.evolution?.users || []"
                        :height="80"
                        :margin="{ top: 5, right: 0, bottom: 5, left: 0 }"
                    >
                        <VisLine
                            :x="xAccessor"
                            :y="yAccessor"
                            color="#a855f7"
                            :lineWidth="3"
                        />
                    </VisXYContainer>
                </div>
            </CardContent>
        </Card>
    </div>
</template>

<script setup lang="ts">
import { VisXYContainer, VisLine } from '@unovis/vue';
import { CarFront, Users, TrendingUp, CheckCircle2 } from 'lucide-vue-next';
import { computed } from 'vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { useTranslation } from '@/composables/useTranslation';

const props = defineProps<{
    stats: any;
}>();

const { __ } = useTranslation();

const totalNewAds7Days = computed(() => {
    return (
        props.stats?.evolution?.ads?.reduce(
            (acc: number, curr: any) => acc + curr.count,
            0,
        ) || 0
    );
});

const totalNewUsers7Days = computed(() => {
    return (
        props.stats?.evolution?.users?.reduce(
            (acc: number, curr: any) => acc + curr.count,
            0,
        ) || 0
    );
});

const xAccessor = (d: any, i: number) => i;
const yAccessor = (d: any) => d.count;
</script>
