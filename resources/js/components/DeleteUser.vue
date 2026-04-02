<script setup lang="ts">
import { Form } from '@inertiajs/vue3';
import { useTemplateRef } from 'vue';
import ProfileController from '@/actions/App/Http/Controllers/Settings/ProfileController';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { useTranslation } from '@/composables/useTranslation';

const passwordInput = useTemplateRef('passwordInput');

const { __ } = useTranslation();
</script>

<template>
    <div
        class="rounded-2xl border border-red-200/50 bg-red-50/50 p-6 shadow-xs dark:border-red-500/20 dark:bg-red-500/5"
    >
        <h3
            class="mb-6 text-[10px] font-black tracking-[0.2em] text-red-600/70 uppercase dark:text-red-400/70"
        >
            {{ __('settings.section_danger_zone') }}
        </h3>

        <div class="space-y-4">
            <div class="relative space-y-0.5 text-red-600 dark:text-red-100">
                <p class="text-sm font-bold">
                    {{ __('settings.profile_delete_title') }}
                </p>
                <p class="text-xs text-red-600/70 dark:text-red-100/60">
                    {{ __('settings.profile_delete_description') }}
                </p>
            </div>
            <Dialog>
                <DialogTrigger as-child>
                    <Button
                        variant="destructive"
                        data-test="delete-user-button"
                        class="hover:cursor-pointer"
                        >{{ __('settings.profile_delete') }}</Button
                    >
                </DialogTrigger>
                <DialogContent>
                    <Form
                        v-bind="ProfileController.destroy.form()"
                        reset-on-success
                        @error="() => passwordInput?.$el?.focus()"
                        :options="{
                            preserveScroll: true,
                        }"
                        class="space-y-6"
                        v-slot="{ errors, processing, reset, clearErrors }"
                    >
                        <DialogHeader class="space-y-3">
                            <DialogTitle>{{
                                __('settings.profile_delete_modal_title')
                            }}</DialogTitle>
                            <DialogDescription>
                                {{
                                    __(
                                        'settings.profile_delete_modal_description',
                                    )
                                }}
                            </DialogDescription>
                        </DialogHeader>

                        <div class="grid gap-2">
                            <Label for="password" class="sr-only">{{
                                __('settings.profile_delete_modal_password')
                            }}</Label>
                            <Input
                                id="password"
                                type="password"
                                name="password"
                                ref="passwordInput"
                                :placeholder="
                                    __('settings.profile_delete_modal_password')
                                "
                            />
                            <InputError :message="errors.password" />
                        </div>

                        <DialogFooter class="gap-2">
                            <DialogClose as-child>
                                <Button
                                    variant="secondary"
                                    class="hover:cursor-pointer"
                                    @click="
                                        () => {
                                            clearErrors();
                                            reset();
                                        }
                                    "
                                >
                                    {{
                                        __(
                                            'settings.profile_delete_modal_cancel',
                                        )
                                    }}
                                </Button>
                            </DialogClose>

                            <Button
                                type="submit"
                                variant="destructive"
                                :disabled="processing"
                                data-test="confirm-delete-user-button"
                                class="hover:cursor-pointer"
                            >
                                {{
                                    __('settings.profile_delete_modal_confirm')
                                }}
                            </Button>
                        </DialogFooter>
                    </Form>
                </DialogContent>
            </Dialog>
        </div>
    </div>
</template>
