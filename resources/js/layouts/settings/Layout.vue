<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import {
    Building2,
    Lock,
    ShieldCheck,
    User,
    Settings as SettingsIcon,
} from 'lucide-vue-next';
import { computed, ref } from 'vue';
import SheetMenu from '@/components/SheetMenu.vue';
import { Button } from '@/components/ui/button';
import { useCurrentUrl } from '@/composables/useCurrentUrl';
import { usePermissions } from '@/composables/usePermissions';
import { useTranslation } from '@/composables/useTranslation';
import { toUrl } from '@/lib/utils';
// import { edit as editAppearance } from '@/routes/appearance';
import { edit as editCompany } from '@/routes/company';
import { edit as editProfile } from '@/routes/profile';
import { show } from '@/routes/two-factor';
import { edit as editPassword } from '@/routes/user-password';
import { type NavItem } from '@/types';
import { type ExtendedPageProps } from '@/types/inertia';

const { __ } = useTranslation();
const { hasRole } = usePermissions();
const page = usePage<ExtendedPageProps>();

const isOpen = ref(false);

const sidebarNavItems = computed<NavItem[]>(() => [
    {
        title: __('settings.menu_profile'),
        href: editProfile(),
        icon: User,
    },
    ...(hasRole('dealer')
        ? [
              {
                  title: __('settings.menu_company'),
                  href: editCompany(),
                  icon: Building2,
              },
          ]
        : []),
    {
        title: __('settings.menu_password'),
        href: editPassword(),
        icon: Lock,
    },
    {
        title: __('settings.menu_2fa'),
        href: show(),
        icon: ShieldCheck,
    },
]);

const { isCurrentUrl } = useCurrentUrl();
</script>

<template>
    <div
        class="flex min-h-[calc(100vh-64px)] flex-col bg-linear-to-b from-background to-muted/20 lg:flex-row lg:gap-8 lg:p-8"
    >
        <!-- Mobile/Tablet Sheet Menu -->
        <div class="lg:hidden">
            <SheetMenu
                v-model:open="isOpen"
                side="left"
                :title="__('settings.page_title')"
                :icon="SettingsIcon"
                with-floating-button
            >
                <nav class="space-y-1.5">
                    <Button
                        v-for="item in sidebarNavItems"
                        :key="toUrl(item.href)"
                        variant="ghost"
                        class="group relative w-full justify-start gap-4 rounded-xl px-4 py-8 transition-all duration-300"
                        :class="{
                            'bg-primary/10 text-primary hover:bg-primary/15':
                                isCurrentUrl(item.href),
                            'text-muted-foreground hover:bg-muted/50 hover:text-foreground':
                                !isCurrentUrl(item.href),
                        }"
                        as-child
                    >
                        <Link :href="item.href" @click="isOpen = false">
                            <div
                                class="flex h-10 w-10 items-center justify-center rounded-lg bg-background shadow-xs transition-colors group-hover:bg-muted/10"
                            >
                                <component :is="item.icon" class="h-5 w-5" />
                            </div>
                            <span
                                class="font-heading text-sm font-bold tracking-widest uppercase"
                            >
                                {{ item.title }}
                            </span>
                        </Link>
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
                                {{ page.props.auth.user.email }}
                            </p>
                        </div>
                    </div>
                </template>
            </SheetMenu>
        </div>

        <aside
            class="sticky top-24 hidden h-fit w-full shrink-0 overflow-hidden rounded-2xl bg-card shadow-xl ring-1 ring-border/50 backdrop-blur-xl lg:block lg:w-80"
        >
            <div
                class="dark relative flex items-center gap-3 bg-background px-6 py-5"
            >
                <div
                    class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary/10 text-primary"
                >
                    <SettingsIcon class="h-4 w-4" />
                </div>
                <h2
                    class="font-heading text-sm font-bold tracking-widest text-white uppercase"
                >
                    {{ __('settings.page_title') }}
                </h2>
            </div>

            <div class="h-px bg-border/40" />

            <div class="p-4">
                <nav class="space-y-2">
                    <Button
                        v-for="item in sidebarNavItems"
                        :key="toUrl(item.href)"
                        variant="ghost"
                        class="group relative w-full justify-start gap-3 rounded-xl px-3 py-6 transition-all duration-300"
                        :class="{
                            'bg-primary/10 text-primary hover:bg-primary/15':
                                isCurrentUrl(item.href),
                            'text-muted-foreground hover:bg-muted/50 hover:text-foreground':
                                !isCurrentUrl(item.href),
                        }"
                        as-child
                    >
                        <Link :href="item.href">
                            <div
                                class="flex h-8 w-8 items-center justify-center rounded-lg bg-background shadow-xs transition-colors group-hover:bg-muted/10"
                            >
                                <component :is="item.icon" class="h-4 w-4" />
                            </div>
                            <span
                                class="font-heading text-xs font-bold tracking-widest uppercase"
                            >
                                {{ item.title }}
                            </span>
                        </Link>
                    </Button>
                </nav>
            </div>
        </aside>

        <!-- Main Workspace -->
        <main class="min-w-0 flex-1 p-4 lg:p-0">
            <div class="mx-auto max-w-3xl">
                <section class="space-y-12">
                    <slot />
                </section>
            </div>
        </main>
    </div>
</template>
