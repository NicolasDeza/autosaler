<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { MapPin, Warehouse } from 'lucide-vue-next';
import { ref, watch } from 'vue';
import AppContent from '@/components/AppContent.vue';
import AppPagination from '@/components/AppPagination.vue';
import { Avatar, AvatarImage } from '@/components/ui/avatar';
import { Card } from '@/components/ui/card';
import { useTranslation } from '@/composables/useTranslation';
import garageRoutes from '@/routes/garages';

type Garage = {
    id: number;
    name: string;
    address: string;
    city: {
        code: string;
        zip_code: string;
    } | null;
    country: {
        code: string;
    } | null;
    logo_url: string | null;
    background_url: string | null;
    active_vehicle_ads_count: number;
};

const props = defineProps<{
    garages: {
        data: Garage[];
        total: number;
        current_page: number;
        last_page: number;
        from: number;
        to: number;
        per_page: number;
        [key: string]: unknown;
    };
}>();

const { __ } = useTranslation();

const perPage = ref<string>(String(props.garages.per_page ?? 15));

const scrollToTop = (): void => {
    window.scrollTo({
        top: 0,
        behavior: 'smooth',
    });
};

const visitGaragesPage = (page: number): void => {
    scrollToTop();

    router.get(
        garageRoutes.index({
            query: {
                page,
                per_page: perPage.value,
            },
        }).url,
        {},
        {
            preserveScroll: true,
            preserveState: true,
            replace: true,
        },
    );
};

const handlePageChange = (page: number): void => {
    visitGaragesPage(page);
};

const handlePerPageChange = (value: string): void => {
    perPage.value = value;
    visitGaragesPage(1);
};

watch(
    () => props.garages.per_page,
    (value) => {
        perPage.value = String(value ?? 15);
    },
);
</script>

<template>
    <Head :title="__('garage.page_title')" />

    <AppContent>
        <section class="space-y-7 py-10 md:py-12">
            <header
                class="relative overflow-hidden rounded-2xl bg-card p-5 shadow-sm sm:p-7"
            >
                <div class="relative z-10 space-y-3">
                    <div class="space-y-2">
                        <h1
                            class="text-3xl font-black tracking-tight text-balance text-foreground md:text-4xl"
                        >
                            {{ __('garage.hero_title_prefix') }}
                            <span class="text-primary">
                                {{ __('garage.hero_title_highlight') }}
                            </span>
                        </h1>
                        <p
                            class="max-w-2xl text-sm leading-relaxed text-muted-foreground md:text-base"
                        >
                            {{ __('garage.hero_description') }}
                        </p>
                    </div>
                    <div class="h-1 w-20 rounded-full bg-primary" />
                </div>
            </header>

            <div
                v-if="props.garages.data.length === 0"
                class="rounded-xl border border-dashed border-border bg-card/50 px-6 py-14 text-center text-sm text-muted-foreground"
            >
                {{ __('garage.empty_state') }}
            </div>

            <div v-else class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <Link
                    v-for="garage in props.garages.data"
                    :key="garage.id"
                    :href="garageRoutes.show(garage.id).url"
                    class="group block"
                >
                    <Card
                        class="gap-0 overflow-hidden border border-border bg-card p-0 transition-all duration-300 hover:-translate-y-0.5 hover:border-primary/30 hover:shadow-lg"
                    >
                        <div class="relative aspect-16/8.5 overflow-hidden">
                            <img
                                v-if="garage.background_url"
                                :src="garage.background_url"
                                :alt="garage.name"
                                class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105"
                                loading="lazy"
                            />
                            <div
                                v-else
                                class="flex h-full w-full items-center justify-center bg-linear-to-br from-muted/95 via-muted/80 to-muted/60"
                            >
                                <Warehouse class="size-11 text-muted-foreground/55" />
                            </div>

                            <div
                                v-if="garage.background_url"
                                class="absolute inset-0 bg-linear-to-t from-black/80 via-black/35 to-transparent"
                            />

                            <div
                                v-if="garage.logo_url"
                                class="absolute bottom-2.5 left-2.5"
                            >
                                <Avatar
                                    :class="
                                        garage.background_url
                                            ? 'size-9 border border-white/40 shadow-md'
                                            : 'size-9 border border-border bg-background/90 shadow-md'
                                    "
                                >
                                    <AvatarImage
                                        :src="garage.logo_url"
                                        :alt="garage.name"
                                        class="object-cover"
                                    />
                                </Avatar>
                            </div>

                        </div>

                        <div class="space-y-2.5 p-3.5">
                            <h2
                                class="line-clamp-1 text-lg leading-tight font-black text-foreground"
                            >
                                {{ garage.name }}
                            </h2>
                            <p class="text-[15px] font-semibold">
                                <span class="font-black text-primary">
                                    {{ garage.active_vehicle_ads_count }}
                                </span>
                            </p>
                            <p
                                class="flex items-start gap-2 text-[15px] leading-snug text-muted-foreground"
                            >
                                <MapPin
                                    class="mt-0.5 size-3.5 shrink-0 text-primary/70"
                                />
                                <span>
                                    {{ garage.address }}<br />
                                    {{ garage.city?.zip_code }}
                                    {{ garage.city?.code }}
                                </span>
                            </p>

                        </div>
                    </Card>
                </Link>
            </div>

            <AppPagination
                :pagination="props.garages"
                :per-page="perPage"
                resource-label="garages"
                @update:per-page="handlePerPageChange"
                @update:page="handlePageChange"
            />
        </section>
    </AppContent>
</template>
