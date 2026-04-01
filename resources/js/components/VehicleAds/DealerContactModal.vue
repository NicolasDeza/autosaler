<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { Send, X } from 'lucide-vue-next';
import { watch } from 'vue';
import { toast } from 'vue-sonner';
import VehicleAdContactController from '@/actions/App/Http/Controllers/VehicleAdContactController';
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
import { Textarea } from '@/components/ui/textarea';
import { useTranslation } from '@/composables/useTranslation';

const props = defineProps<{
    vehicleAdId: number;
}>();

const isOpen = defineModel<boolean>('open', { required: true });

const contactForm = useForm({
    message: '',
    first_name: '',
    last_name: '',
    email: '',
    phone: '',
});

const { __ } = useTranslation();

const resetForm = (): void => {
    contactForm.reset();
    contactForm.clearErrors();
};

watch(isOpen, (open) => {
    if (open) {
        resetForm();
    }
});

const closeModal = (): void => {
    isOpen.value = false;
};

const handleOpenChange = (open: boolean): void => {
    isOpen.value = open;
};

const submitSellerContact = (): void => {
    contactForm.post(
        VehicleAdContactController.url({ vehicleAd: props.vehicleAdId }),
        {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                resetForm();
                isOpen.value = false;

                window.setTimeout(() => {
                    toast.success(__('vehicleAd.contact_seller_success_title'), {
                        description: __('vehicleAd.contact_seller_success_description'),
                    });
                }, 120);
            },
        },
    );
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
                <DialogTitle class="text-white">{{ __('vehicleAd.contact_seller') }}</DialogTitle>
                <DialogDescription class="text-white/80">
                    {{ __('vehicleAd.contact_seller_desc') }}
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
                autocomplete="off"
                @submit.prevent="submitSellerContact"
            >
                <div class="space-y-2">
                    <Label for="seller_message">{{ __('vehicleAd.message') }} *</Label>
                    <Textarea
                        id="seller_message"
                        v-model="contactForm.message"
                        rows="5"
                        required
                        :placeholder="__('vehicleAd.message_placeholder')"
                    />
                    <InputError :message="contactForm.errors.message" />
                </div>

                <p class="text-xs text-muted-foreground">{{ __('vehicleAd.last_name_or_first_name') }} *</p>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div class="space-y-2">
                        <Label for="seller_last_name">{{ __('ui.last_name') }}</Label>
                        <Input
                            id="seller_last_name"
                            v-model="contactForm.last_name"
                            name="dealer_contact_last_name"
                            autocomplete="off"
                            autocorrect="off"
                            autocapitalize="off"
                            spellcheck="false"
                            :placeholder="__('ui.last_name')"
                        />
                        <InputError :message="contactForm.errors.last_name" />
                    </div>

                    <div class="space-y-2">
                        <Label for="seller_first_name">{{ __('ui.first_name') }}</Label>
                        <Input
                            id="seller_first_name"
                            v-model="contactForm.first_name"
                            name="dealer_contact_first_name"
                            autocomplete="off"
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
                        <Label for="seller_email">{{ __('ui.email') }} *</Label>
                        <Input
                            id="seller_email"
                            v-model="contactForm.email"
                            type="email"
                            name="dealer_contact_email"
                            autocomplete="off"
                            autocorrect="off"
                            autocapitalize="off"
                            spellcheck="false"
                            required
                            placeholder="vous@example.com"
                        />
                        <InputError :message="contactForm.errors.email" />
                    </div>

                    <div class="space-y-2">
                        <Label for="seller_phone">{{ __('ui.phone') }} ({{ __('ui.optional') ?? 'facultatif' }})</Label>
                        <Input
                            id="seller_phone"
                            v-model="contactForm.phone"
                            type="tel"
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
                        <Send class="mr-2 h-4 w-4" />
                        {{
                            contactForm.processing
                                ? __('vehicleAd.sending')
                                : __('vehicleAd.send_message')
                        }}
                    </Button>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>
</template>
