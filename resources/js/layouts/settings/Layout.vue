<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import {
    Building2,
    Lock,
    ShieldCheck,
    User,
    Settings as SettingsIcon,
} from 'lucide-vue-next';
import { computed, onMounted, ref } from 'vue';
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

const { __ } = useTranslation();
const { hasRole } = usePermissions();

const isMounted = ref(false);
onMounted(() => {
    isMounted.value = true;
});

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
        <!-- Mobile Menu (Teleported to Bottom Bar) -->
        <Teleport v-if="isMounted" to="#sticky-bottom-mobile-portal">
            <div class="flex h-full w-full items-center justify-around gap-1 px-1 py-1">
                <Link
                    v-for="item in sidebarNavItems"
                    :key="toUrl(item.href)"
                    :href="item.href"
                    class="bottom-bar-tool-btn relative flex-1"
                    :class="isCurrentUrl(item.href) ? 'bg-white/15!' : 'opacity-70'"
                >
                    <component
                        :is="item.icon"
                        :class="isCurrentUrl(item.href) ? 'text-white' : ''"
                    />
                    <span
                        class="hidden"
                        :class="isCurrentUrl(item.href) ? 'text-white' : ''"
                        >{{ item.title }}</span
                    >
                    <!-- Active Indicator -->
                    <div
                        v-if="isCurrentUrl(item.href)"
                        class="absolute -bottom-1 left-1/2 h-0.5 w-6 -translate-x-1/2 rounded-full bg-white shadow-[0_0_8px_rgba(255,255,255,0.8)]"
                    ></div>
                </Link>
            </div>
        </Teleport>

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
