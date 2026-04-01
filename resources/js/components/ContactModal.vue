<script setup lang="ts">
import { useForm, usePage } from '@inertiajs/vue3';
import { LoaderCircle, Send, X } from 'lucide-vue-next';
import { watch } from 'vue';
import { toast } from 'vue-sonner';
import ContactAdminController from '@/actions/App/Http/Controllers/ContactAdminController';
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
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Textarea } from '@/components/ui/textarea';
import { useTranslation } from '@/composables/useTranslation';
import type { ExtendedPageProps } from '@/types/inertia';

const isOpen = defineModel<boolean>('open', { required: true });
const page = usePage<ExtendedPageProps>();
const { __ } = useTranslation();

const contactForm = useForm({
    topic: 'info',
    message: '',
    first_name: '',
    last_name: '',
    email: '',
    phone: '',
});

const resetForm = (): void => {
    const authUser = page.props.auth?.user;

    contactForm.defaults({
        topic: 'info',
        message: '',
        first_name: typeof authUser?.first_name === 'string' ? authUser.first_name : '',
        last_name: typeof authUser?.last_name === 'string' ? authUser.last_name : '',
        email: typeof authUser?.email === 'string' ? authUser.email : '',
        phone: '',
    });

    contactForm.reset();
    contactForm.clearErrors();
};

watch(isOpen, (open) => {
    if (open) {
        resetForm();
    }
});

const handleOpenChange = (open: boolean): void => {
    isOpen.value = open;
};

const closeModal = (): void => {
    isOpen.value = false;
};

const submitContact = (): void => {
    contactForm.post(ContactAdminController().url, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            resetForm();
            isOpen.value = false;

            window.setTimeout(() => {
                toast.success(__('nav.contact_modal_success_title'), {
                    description: __('nav.contact_modal_success_description'),
                });
            }, 120);
        },
    });
};
</script>

<template>
    <Dialog :open="isOpen" @update:open="handleOpenChange">
        <DialogContent
            :show-close-button="false"
            class="flex max-h-[90svh] flex-col overflow-hidden border-0 p-0 sm:max-w-xl"
        >
            <DialogHeader
                class="z-20 border-b-2 border-primary bg-[#1c2631] px-6 py-5 pr-16"
            >
                <DialogTitle class="text-white">{{ __('nav.contact_modal_title') }}</DialogTitle>
                <DialogDescription class="text-white/80">
                    {{ __('nav.contact_modal_description') }}
                </DialogDescription>
                <DialogClose
                    class="absolute top-4 right-4 inline-flex size-9 cursor-pointer items-center justify-center rounded-md text-white/85 transition-colors hover:bg-white/10 hover:text-white focus-visible:ring-2 focus-visible:ring-white/60 focus-visible:outline-none"
                >
                    <X class="size-5" />
                    <span class="sr-only">{{ __('ui.close') || 'Close' }}</span>
                </DialogClose>
            </DialogHeader>

            <form
                class="min-h-0 space-y-4 overflow-y-auto p-6"
                @submit.prevent="submitContact"
            >
                <div class="space-y-2">
                    <Label for="contact_topic">
                        {{ __('nav.contact_topic_label') }} <span class="text-primary">*</span>
                    </Label>
                    <Select
                        v-model="contactForm.topic"
                        name="topic"
                    >
                        <SelectTrigger id="contact_topic" class="w-full">
                            <SelectValue :placeholder="__('nav.contact_topic_label')" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="info">{{ __('nav.contact_topic_info') }}</SelectItem>
                            <SelectItem value="bug">{{ __('nav.contact_topic_bug') }}</SelectItem>
                            <SelectItem value="other">{{ __('nav.contact_topic_other') }}</SelectItem>
                        </SelectContent>
                    </Select>
                    <input
                        id="contact_topic_value"
                        v-model="contactForm.topic"
                        type="hidden"
                        name="topic"
                    >
                    <InputError :message="contactForm.errors.topic" />
                </div>

                <div class="space-y-2">
                    <Label for="contact_message">
                        {{ __('nav.contact_message_label') }} <span class="text-primary">*</span>
                    </Label>
                    <Textarea
                        id="contact_message"
                        v-model="contactForm.message"
                        name="message"
                        rows="5"
                        required
                        autocomplete="off"
                        :placeholder="__('nav.contact_message_placeholder')"
                    />
                    <InputError :message="contactForm.errors.message" />
                </div>

                <p class="text-xs text-muted-foreground">{{ __('nav.contact_name_hint') }}</p>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div class="space-y-2">
                        <Label for="contact_last_name">{{ __('ui.last_name') }}</Label>
                        <Input
                            id="contact_last_name"
                            v-model="contactForm.last_name"
                            name="last_name"
                            autocomplete="family-name"
                            autocorrect="off"
                            autocapitalize="off"
                            spellcheck="false"
                            :placeholder="__('ui.last_name')"
                        />
                        <InputError :message="contactForm.errors.last_name" />
                    </div>

                    <div class="space-y-2">
                        <Label for="contact_first_name">{{ __('ui.first_name') }}</Label>
                        <Input
                            id="contact_first_name"
                            v-model="contactForm.first_name"
                            name="first_name"
                            autocomplete="given-name"
                            autocorrect="off"
                            autocapitalize="off"
                            spellcheck="false"
                            :placeholder="__('ui.first_name')"
                        />
                        <InputError :message="contactForm.errors.first_name" />
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div class="space-y-2">
                        <Label for="contact_email">
                            {{ __('ui.email') }} <span class="text-primary">*</span>
                        </Label>
                        <Input
                            id="contact_email"
                            v-model="contactForm.email"
                            type="email"
                            name="email"
                            required
                            autocomplete="email"
                            autocorrect="off"
                            autocapitalize="off"
                            spellcheck="false"
                            placeholder="vous@example.com"
                        />
                        <InputError :message="contactForm.errors.email" />
                    </div>

                    <div class="space-y-2">
                        <Label for="contact_phone">{{ __('ui.phone') }} ({{ __('ui.optional') ?? 'optional' }})</Label>
                        <Input
                            id="contact_phone"
                            v-model="contactForm.phone"
                            type="tel"
                            name="phone"
                            autocomplete="tel"
                            placeholder="+32 4XX XX XX XX"
                        />
                        <InputError :message="contactForm.errors.phone" />
                    </div>
                </div>

                <DialogFooter class="gap-2">
                    <Button
                        type="button"
                        variant="outline"
                        class="cursor-pointer"
                        @click="closeModal"
                    >
                        {{ __('ui.cancel') }}
                    </Button>
                    <Button
                        type="submit"
                        class="cursor-pointer"
                        :disabled="contactForm.processing"
                    >
                        <LoaderCircle
                            v-if="contactForm.processing"
                            class="mr-2 h-4 w-4 animate-spin"
                        />
                        <Send v-else class="mr-2 h-4 w-4" />
                        {{
                            contactForm.processing
                                ? __('nav.contact_sending')
                                : __('nav.contact_submit')
                        }}
                    </Button>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>
</template>
