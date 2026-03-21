<script setup lang="ts">
import { X, GripVertical, Plus, UploadCloud, Image as ImageIcon } from 'lucide-vue-next';
import { ref, watch, onMounted } from 'vue';
import { toast } from 'vue-sonner';
import draggable from 'vuedraggable';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardHeader, CardTitle, CardDescription, CardContent } from '@/components/ui/card';
import { useTranslation } from '@/composables/useTranslation';

const { __ } = useTranslation();

interface MediaItem {
    id?: number;
    url?: string;
    thumb?: string;
    card?: string;
    large?: string;
    file?: File;
    name?: string;
    size?: number;
    isNew: boolean;
    previewUrl?: string;
}

const props = defineProps<{
    existingMedia?: any[];
    modelValue: File[];
    errors?: Record<string, string>;
}>();

const emit = defineEmits<{
    (e: 'update:modelValue', files: File[]): void;
    (e: 'update:mediaOrder', ids: number[]): void;
    (e: 'update:mediaToDelete', ids: number[]): void;
}>();

// Unified list for the UI
const items = ref<MediaItem[]>([]);
const mediaToDelete = ref<number[]>([]);

// Initialize with existing media
onMounted(() => {
    if (props.existingMedia) {
        items.value = props.existingMedia.map((m) => ({
            id: m.id,
            url: m.large || m.url,
            thumb: m.thumb,
            card: m.card,
            large: m.large,
            name: m.name,
            size: m.size,
            isNew: false,
            previewUrl: m.card || m.thumb || m.url,
        }));
    }
});

// Handle file selection
const onFileSelected = (event: Event) => {
    const target = event.target as HTMLInputElement;
    if (!target.files) return;

    const files = Array.from(target.files);
    handleFiles(files);

    // Reset input
    target.value = '';
};

// Common file handler
const handleFiles = (files: File[]) => {
    files.forEach((file) => {
        if (!file.type.startsWith('image/')) {
            toast.error(`${file.name} is not an image`);
            return;
        }

        const reader = new FileReader();
        reader.onload = (e) => {
            const newItem: MediaItem = {
                file: file,
                name: file.name,
                size: file.size,
                isNew: true,
                previewUrl: e.target?.result as string,
            };
            items.value.push(newItem);
            updateModel();
        };
        reader.readAsDataURL(file);
    });
};

const isDragging = ref(false);
const handleDragOver = (e: DragEvent) => {
    e.preventDefault();
    isDragging.value = true;
};
const handleDragLeave = () => {
    isDragging.value = false;
};
const handleDrop = (e: DragEvent) => {
    e.preventDefault();
    isDragging.value = false;

    if (e.dataTransfer?.files) {
        handleFiles(Array.from(e.dataTransfer.files));
    }
};

// Remove item
const removeItem = (index: number) => {
    const item = items.value[index];

    if (!item.isNew && item.id) {
        mediaToDelete.value.push(item.id);
        emit('update:mediaToDelete', mediaToDelete.value);
    }

    items.value.splice(index, 1);
    updateModel();
};

// Update parents
const updateModel = () => {
    // New files only for form.images
    const newFiles = items.value
        .filter((i) => i.isNew && i.file)
        .map((i) => i.file as File);

    emit('update:modelValue', newFiles);

    // Order of existing IDs
    const existingIds = items.value
        .filter((i) => !i.isNew && i.id)
        .map((i) => i.id as number);

    emit('update:mediaOrder', existingIds);
};

// Watch for reordering
watch(
    items,
    () => {
        updateModel();
    },
    { deep: true },
);
</script>

<template>
    <Card class="overflow-hidden pt-0">
        <CardHeader class="dark rounded-t-xl bg-background py-5 text-card-foreground">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div
                        class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary/10 text-primary"
                    >
                        <ImageIcon class="h-4 w-4" />
                    </div>
                    <div>
                        <CardTitle class="text-base">{{
                            __('vehicleAd.photos')
                        }}</CardTitle>
                        <CardDescription class="text-xs">{{
                            __('vehicleAd.photos_desc')
                        }}</CardDescription>
                    </div>
                </div>

                <Button
                    type="button"
                    variant="outline"
                    size="sm"
                    class="relative h-9 cursor-pointer"
                >
                    <Plus class="mr-2 h-4 w-4" />
                    {{ __('ui.add_photos') || 'Ajouter des photos' }}
                    <input
                        type="file"
                        multiple
                        accept="image/*"
                        class="absolute inset-0 cursor-pointer opacity-0"
                        @change="onFileSelected"
                    />
                </Button>
            </div>
        </CardHeader>

        <CardContent class="p-6 space-y-4">
            <!-- Dropzone area when empty -->
            <div
                v-if="items.length === 0"
                class="relative flex flex-col items-center justify-center rounded-xl border-2 border-dashed border-muted-foreground/25 bg-muted/30 py-12 transition-colors hover:bg-muted/50"
            >
                <div class="flex flex-col items-center space-y-2 text-center">
                    <div class="rounded-full bg-background p-3 shadow-sm">
                        <UploadCloud class="h-6 w-6 text-muted-foreground" />
                    </div>
                    <div class="space-y-1">
                        <p class="text-sm font-medium">Aucune photo sélectionnée</p>
                        <p class="text-xs text-muted-foreground">
                            Cliquez sur le bouton pour en ajouter
                        </p>
                    </div>
                </div>
                <input
                    type="file"
                    multiple
                    accept="image/*"
                    class="absolute inset-0 cursor-pointer opacity-0"
                    @change="onFileSelected"
                />
            </div>

        <!-- Draggable Grid -->
        <div
            v-else
            class="relative rounded-xl border-2 border-transparent transition-all"
            :class="{ 'border-dashed border-primary bg-primary/5 ring-4 ring-primary/10': isDragging }"
            @dragover="handleDragOver"
            @dragleave="handleDragLeave"
            @drop="handleDrop"
        >
            <draggable
                v-model="items"
                item-key="previewUrl"
                class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5"
                handle=".drag-handle"
                ghost-class="opacity-50"
            >
                <template #item="{ element, index }">
                    <div
                        class="group relative aspect-4/3 overflow-hidden rounded-xl border bg-muted transition-all hover:ring-2 hover:ring-primary/20"
                    >
                        <!-- Preview Image -->
                        <img
                            :src="element.previewUrl"
                            class="h-full w-full object-cover transition-transform group-hover:scale-105"
                            alt="Preview"
                            loading="lazy"
                        />

                        <!-- Overlay Layer (Visible on touch, hover otherwise) -->
                        <div
                            class="absolute inset-0 bg-black/30 transition-opacity lg:opacity-0 lg:group-hover:opacity-100"
                        >
                            <div class="absolute top-2 right-2">
                                <Button
                                    type="button"
                                    variant="destructive"
                                    size="icon"
                                    class="h-8 w-8 rounded-full shadow-lg"
                                    @click="removeItem(index)"
                                >
                                    <X class="h-4 w-4" />
                                </Button>
                            </div>

                            <div
                                class="drag-handle absolute top-2 left-2 flex h-8 w-8 cursor-move items-center justify-center rounded-full bg-white/20 text-white backdrop-blur-md transition-colors hover:bg-white/40"
                            >
                                <GripVertical class="h-4 w-4" />
                            </div>
                        </div>

                        <!-- Main Badge -->
                        <div v-if="index === 0" class="absolute bottom-2 left-2">
                            <Badge
                                variant="secondary"
                                class="bg-primary text-primary-foreground shadow-sm"
                            >
                                Principale
                            </Badge>
                        </div>

                        <!-- New Indicator -->
                        <div
                            v-if="element.isNew"
                            class="absolute top-2 left-1/2 -translate-x-1/2"
                        >
                            <Badge
                                variant="outline"
                                class="bg-background/80 text-[10px] backdrop-blur-sm"
                            >
                                Nouveau
                            </Badge>
                        </div>
                    </div>
                </template>

                <template #footer>
                    <div
                        class="relative flex aspect-4/3 flex-col items-center justify-center rounded-xl border-2 border-dashed border-muted-foreground/20 bg-muted/10 transition-colors hover:border-primary/50 hover:bg-muted/20"
                    >
                        <Plus class="h-8 w-8 text-muted-foreground/50" />
                        <input
                            type="file"
                            multiple
                            accept="image/*"
                            class="absolute inset-0 cursor-pointer opacity-0"
                            @change="onFileSelected"
                        />
                    </div>
                </template>
            </draggable>
        </div>

        </CardContent>

        <!-- Errors -->
        <div
            v-if="
                errors &&
                Object.keys(errors).some((k) => k.startsWith('images'))
            "
            class="px-6 pb-6 text-sm font-medium text-destructive"
        >
            <p v-for="(error, key) in errors" :key="key">
                <span v-if="key.startsWith('images')">{{ error }}</span>
            </p>
        </div>
    </Card>
</template>

<style scoped>
.drag-handle {
    touch-action: none;
}
</style>
