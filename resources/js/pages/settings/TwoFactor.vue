<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import { ShieldBan, ShieldCheck } from 'lucide-vue-next';
import { onUnmounted, ref } from 'vue';
import AppContent from '@/components/AppContent.vue';
import Heading from '@/components/Heading.vue';
import TwoFactorRecoveryCodes from '@/components/TwoFactorRecoveryCodes.vue';
import TwoFactorSetupModal from '@/components/TwoFactorSetupModal.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { useTranslation } from '@/composables/useTranslation';
import { useTwoFactorAuth } from '@/composables/useTwoFactorAuth';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { disable, enable, show } from '@/routes/two-factor';
import type { BreadcrumbItem } from '@/types';

type Props = {
    requiresConfirmation?: boolean;
    twoFactorEnabled?: boolean;
};

withDefaults(defineProps<Props>(), {
    requiresConfirmation: false,
    twoFactorEnabled: false,
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Two-Factor Authentication',
        href: show.url(),
    },
];

const { __ } = useTranslation();

const { hasSetupData, clearTwoFactorAuthData } = useTwoFactorAuth();
const showSetupModal = ref<boolean>(false);

onUnmounted(() => {
    clearTwoFactorAuthData();
});
</script>

<template>
    <AppContent :breadcrumbs="breadcrumbs">
        <Head title="Two-Factor Authentication" />

        <h1 class="sr-only">{{ __('settings.two_factor_title') }}</h1>

        <SettingsLayout>
            <div class="space-y-6">
                <Heading
                    variant="small"
                    :title="__('settings.two_factor_title')"
                    :description="__('settings.two_factor_description')"
                />

                <div
                    class="rounded-2xl border border-border bg-card p-6 shadow-xs"
                >
                    <h3
                        class="mb-6 text-[10px] font-black tracking-[0.2em] text-muted-foreground/50 uppercase"
                    >
                        {{ __('settings.section_security') }}
                    </h3>

                    <div
                        v-if="!twoFactorEnabled"
                        class="flex flex-col items-start justify-start space-y-4"
                    >
                        <Badge variant="destructive">{{
                            __('settings.two_factor_disabled')
                        }}</Badge>

                        <p class="text-sm text-muted-foreground">
                            {{ __('settings.two_factor_disable_description') }}
                        </p>

                        <div>
                            <Button
                                v-if="hasSetupData"
                                @click="showSetupModal = true"
                                class="hover:cursor-pointer"
                            >
                                <ShieldCheck />{{
                                    __('settings.two_factor_continue_setup')
                                }}
                            </Button>
                            <Form
                                v-else
                                v-bind="enable.form()"
                                @success="showSetupModal = true"
                                #default="{ processing }"
                            >
                                <Button type="submit" :disabled="processing">
                                    <ShieldCheck />{{
                                        __('settings.two_factor_enable_2fa')
                                    }}</Button
                                ></Form
                            >
                        </div>
                    </div>

                    <div
                        v-else
                        class="flex flex-col items-start justify-start space-y-4"
                    >
                        <Badge variant="default">{{
                            __('settings.two_factor_enabled')
                        }}</Badge>

                        <p class="text-sm text-muted-foreground">
                            {{ __('settings.two_factor_enable_description') }}
                        </p>

                        <TwoFactorRecoveryCodes />

                        <div class="relative inline">
                            <Form
                                v-bind="disable.form()"
                                #default="{ processing }"
                            >
                                <Button
                                    variant="destructive"
                                    type="submit"
                                    :disabled="processing"
                                >
                                    <ShieldBan />
                                    {{ __('settings.two_factor_disable_2fa') }}
                                </Button>
                            </Form>
                        </div>
                    </div>
                </div>

                <TwoFactorSetupModal
                    v-model:isOpen="showSetupModal"
                    :requiresConfirmation="requiresConfirmation"
                    :twoFactorEnabled="twoFactorEnabled"
                />
            </div>
        </SettingsLayout>
    </AppContent>
</template>
