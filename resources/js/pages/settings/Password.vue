<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import PasswordController from '@/actions/App/Http/Controllers/Settings/PasswordController';
import AppContent from '@/components/AppContent.vue';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { useTranslation } from '@/composables/useTranslation';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { edit } from '@/routes/user-password';
import { type BreadcrumbItem } from '@/types';

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Password settings',
        href: edit().url,
    },
];

const { __ } = useTranslation();
</script>

<template>
    <AppContent :breadcrumbs="breadcrumbItems">
        <Head title="Password settings" />

        <h1 class="sr-only">{{ __('settings.password_title') }}</h1>

        <SettingsLayout>
            <div class="space-y-6">
                <Heading
                    variant="small"
                    :title="__('settings.password_title')"
                    :description="__('settings.password_description')"
                />

                <div
                    class="rounded-2xl border border-border bg-card p-6 shadow-xs"
                >
                    <h3
                        class="mb-6 text-[10px] font-black tracking-[0.2em] text-muted-foreground/50 uppercase"
                    >
                        {{ __('settings.section_password') }}
                    </h3>

                    <Form
                        v-bind="PasswordController.update.form()"
                        :options="{
                            preserveScroll: true,
                        }"
                        reset-on-success
                        :reset-on-error="[
                            'password',
                            'password_confirmation',
                            'current_password',
                        ]"
                        class="space-y-6"
                        v-slot="{ errors, processing, recentlySuccessful }"
                    >
                        <div class="grid gap-2">
                            <Label for="current_password" class="text-xs font-bold">{{
                                __('settings.password_current')
                            }}</Label>
                            <Input
                                id="current_password"
                                name="current_password"
                                type="password"
                                class="h-11 rounded-xl bg-muted/30"
                                autocomplete="current-password"
                            />
                            <InputError :message="errors.current_password" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="password" class="text-xs font-bold">{{
                                __('settings.password_new')
                            }}</Label>
                            <Input
                                id="password"
                                name="password"
                                type="password"
                                class="h-11 rounded-xl bg-muted/30"
                                autocomplete="new-password"
                            />
                            <InputError :message="errors.password" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="password_confirmation" class="text-xs font-bold">{{
                                __('settings.password_confirm')
                            }}</Label>
                            <Input
                                id="password_confirmation"
                                name="password_confirmation"
                                type="password"
                                class="h-11 rounded-xl bg-muted/30"
                                autocomplete="new-password"
                            />
                            <InputError :message="errors.password_confirmation" />
                        </div>

                        <div class="flex items-center gap-4">
                            <Button
                                :disabled="processing"
                                data-test="update-password-button"
                                class="hover:cursor-pointer"
                                >{{ __('settings.password_save') }}</Button
                            >

                            <Transition
                                enter-active-class="transition ease-in-out"
                                enter-from-class="opacity-0"
                                leave-active-class="transition ease-in-out"
                                leave-to-class="opacity-0"
                            >
                                <p
                                    v-show="recentlySuccessful"
                                    class="text-sm text-neutral-600"
                                >
                                    {{ __('settings.password_updated') }}
                                </p>
                            </Transition>
                        </div>
                    </Form>
                </div>
            </div>
        </SettingsLayout>
    </AppContent>
</template>
