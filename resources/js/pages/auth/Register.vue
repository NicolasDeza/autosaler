<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { useTranslation } from '@/composables/useTranslation';
import AuthBase from '@/layouts/auth/AuthModalLayout.vue';
import { login } from '@/routes';
import { store } from '@/routes/register';

defineProps<{
    intendedUrl?: string;
}>();

const { __ } = useTranslation();
</script>

<template>
    <AuthBase
        :title="__('auth.register_title')"
        :description="__('auth.register_description')"
        :close-href="intendedUrl"
    >
        <Head :title="__('auth.register_head')" />

        <Form
            v-bind="store.form()"
            :reset-on-success="['password', 'password_confirmation']"
            v-slot="{ errors, processing }"
            class="flex flex-col gap-6"
        >
            <div class="grid gap-6">
                <div class="flex gap-2">
                    <div class="grid gap-2">
                        <Label for="first_name">{{
                            __('auth.register_first_name_label')
                        }}</Label>
                        <Input
                            id="first_name"
                            type="text"
                            required
                            autofocus
                            :tabindex="1"
                            autocomplete="given-name"
                            name="first_name"
                            :placeholder="__('auth.register_first_name_placeholder')"
                        />
                        <InputError :message="errors.first_name" />
                    </div>
                    <div class="grid gap-2">
                        <Label for="last_name">{{
                            __('auth.register_last_name_label')
                        }}</Label>
                        <Input
                            id="last_name"
                            type="text"
                            required
                            autofocus
                            :tabindex="1"
                            autocomplete="family-name"
                            name="last_name"
                            :placeholder="__('auth.register_last_name_placeholder')"
                        />
                        <InputError :message="errors.last_name" />
                    </div>
                </div>

                <div class="grid gap-2">
                    <Label for="email">{{
                        __('auth.register_email_label')
                    }}</Label>
                    <Input
                        id="email"
                        type="email"
                        required
                        :tabindex="2"
                        autocomplete="email"
                        name="email"
                        :placeholder="__('auth.register_email_placeholder')"
                    />
                    <InputError :message="errors.email" />
                </div>

                <div class="grid gap-2">
                    <Label for="password">{{
                        __('auth.register_password_label')
                    }}</Label>
                    <Input
                        id="password"
                        type="password"
                        required
                        :tabindex="3"
                        autocomplete="new-password"
                        name="password"
                        :placeholder="__('auth.register_password_placeholder')"
                    />
                    <InputError :message="errors.password" />
                </div>

                <div class="grid gap-2">
                    <Label for="password_confirmation">{{
                        __('auth.register_confirm_password_label')
                    }}</Label>
                    <Input
                        id="password_confirmation"
                        type="password"
                        required
                        :tabindex="4"
                        autocomplete="new-password"
                        name="password_confirmation"
                        :placeholder="
                            __('auth.register_confirm_password_placeholder')
                        "
                    />
                    <InputError :message="errors.password_confirmation" />
                </div>

                <Button
                    type="submit"
                    class="mt-2 w-full"
                    tabindex="5"
                    :disabled="processing"
                    data-test="register-user-button"
                >
                    <Spinner v-if="processing" />
                    {{ __('auth.register_button') }}
                </Button>
            </div>

            <div class="text-center text-sm text-muted-foreground">
                {{ __('auth.register_already_have_account') }}
                <TextLink
                    :href="login()"
                    class="underline underline-offset-4"
                    :tabindex="6"
                    >{{ __('auth.register_login_link') }}</TextLink
                >
            </div>
        </Form>
    </AuthBase>
</template>
