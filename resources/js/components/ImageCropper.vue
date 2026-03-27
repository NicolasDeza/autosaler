<script setup lang="ts">
import { ref } from 'vue';
import { Cropper } from 'vue-advanced-cropper';
import 'vue-advanced-cropper/dist/style.css';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
    DialogFooter,
    DialogDescription,
} from '@/components/ui/dialog';
import { useTranslation } from '@/composables/useTranslation';

defineProps<{
    image: string | null;
    stencilAspectRatio: number;
    open: boolean;
    title: string;
    // New prop to restore previous crop
    defaultCoordinates?: {
        width: number;
        height: number;
        left: number;
        top: number;
    } | null;
}>();

const emit = defineEmits(['close', 'confirm']);

const { __ } = useTranslation();

const cropperRef = ref<any>(null);

const handleConfirm = () => {
    if (cropperRef.value) {
        const result = cropperRef.value.getResult();
        const { canvas, coordinates } = result;
        if (canvas) {
            canvas.toBlob((blob: Blob) => {
                emit('confirm', {
                    blob,
                    coordinates: {
                        width: Math.round(coordinates.width),
                        height: Math.round(coordinates.height),
                        left: Math.round(coordinates.left),
                        top: Math.round(coordinates.top),
                    },
                });
            }, 'image/jpeg');
        }
    }
};
</script>

<template>
    <Dialog :open="open" @update:open="emit('close')">
        <DialogContent class="max-w-xl">
            <DialogHeader>
                <DialogTitle>{{ title }}</DialogTitle>
                <DialogDescription class="sr-only">
                    {{ __('ui.crop_image_instruction', { default: 'Ajustez votre image' }) }}
                </DialogDescription>
            </DialogHeader>

            <div class="overflow-hidden rounded-lg bg-black/5 flex items-center justify-center min-h-[300px]">
                <Cropper
                    v-if="image"
                    ref="cropperRef"
                    class="h-[400px] w-full"
                    :src="image"
                    :stencil-props="{
                        aspectRatio: stencilAspectRatio,
                    }"
                    :default-size="defaultCoordinates || undefined"
                    :default-position="defaultCoordinates || undefined"
                />
                <div v-else class="text-muted-foreground p-8">
                    {{ __('ui.loading') }}
                </div>
            </div>

            <DialogFooter>
                <Button variant="outline" @click="emit('close')">
                    {{ __('ui.cancel') }}
                </Button>
                <Button @click="handleConfirm">
                    {{ __('ui.confirm_crop') }}
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
