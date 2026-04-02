<template>
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div class="space-y-1">
                <h2
                    class="font-heading text-2xl font-bold tracking-tight text-foreground"
                >
                    {{ __('admin.tab_catalog') }}
                </h2>
                <p class="font-body text-sm text-muted-foreground">
                    {{ __('admin.catalog_description') }}
                </p>
            </div>
            <Button
                size="sm"
                class="hidden h-9 gap-2 shadow-sm transition-all hover:scale-105 active:scale-95 lg:flex"
                @click="openBrandDialog()"
            >
                <Plus class="h-4 w-4" />
                <span class="hidden sm:inline">{{ __('admin.brand_add') }}</span>
                <span class="sm:hidden">Marque</span>
            </Button>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <!-- Brands Panel -->
            <Card
                class="overflow-hidden border-border/50 bg-background/60 shadow-xl backdrop-blur-md transition-all"
                :class="{ 'hidden lg:block': selectedBrandId }"
            >
                <CardHeader
                    class="flex flex-row items-center justify-between border-b border-border/50 bg-muted/20 pb-4"
                >
                    <CardTitle
                        class="font-heading text-sm font-bold tracking-widest text-muted-foreground uppercase"
                    >
                        {{ __('admin.filter_brand') }}
                    </CardTitle>
                    <Button
                        size="sm"
                        class="h-8 gap-2 border-border/40 text-xs font-bold transition-all hover:border-primary/40 hover:bg-primary/5 lg:hidden"
                        @click="openBrandDialog()"
                    >
                        <Plus class="h-3.5 w-3.5" />
                        {{ __('admin.brand_add') }}
                    </Button>
                </CardHeader>
                <CardContent class="p-0">
                    <div
                        class="max-h-[600px] divide-y divide-border/40 overflow-y-auto"
                    >
                        <div
                            v-for="brand in brands"
                            :key="brand.id"
                            class="group relative flex cursor-pointer items-center justify-between p-4 transition-all hover:bg-muted/50"
                            :class="{
                                'border-l-4 border-l-primary bg-primary/5!':
                                    selectedBrandId === brand.id,
                            }"
                            @click="selectBrand(brand.id)"
                        >
                            <div class="flex items-center gap-3">
                                <span
                                    class="font-heading text-sm font-bold tracking-tight"
                                    :class="{
                                        'text-primary':
                                            selectedBrandId === brand.id,
                                    }"
                                >
                                    {{ brand.name }}
                                </span>
                                <Badge
                                    variant="secondary"
                                    class="bg-primary/10 text-[10px] whitespace-nowrap text-primary hover:bg-primary/20"
                                >
                                    {{ brand.models_count }} modèles
                                </Badge>
                            </div>
                            <div
                                class="flex items-center gap-1 opacity-0 transition-opacity group-hover:opacity-100"
                                @click.stop
                            >
                                <Button
                                    variant="ghost"
                                    size="icon"
                                    class="h-8 w-8 cursor-pointer rounded-full"
                                    @click="openBrandDialog(brand)"
                                >
                                    <Pencil class="h-3.5 w-3.5" />
                                </Button>
                                <Button
                                    variant="ghost"
                                    size="icon"
                                    class="h-8 w-8 cursor-pointer rounded-full text-destructive hover:bg-destructive/10 hover:text-destructive"
                                    @click="confirmDeleteBrand(brand)"
                                >
                                    <Trash2 class="h-3.5 w-3.5" />
                                </Button>
                            </div>
                        </div>
                        <div
                            v-if="brands.length === 0"
                            class="flex flex-col items-center justify-center p-12 text-center text-muted-foreground"
                        >
                            <Car class="mb-4 h-12 w-12 opacity-20" />
                            <p class="text-sm italic">
                                {{ __('admin.no_brands_found') }}
                            </p>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <!-- Models Panel -->
            <Card
                class="overflow-hidden border-border/50 bg-background/60 shadow-xl backdrop-blur-md transition-all"
                :class="{ 'hidden lg:block': !selectedBrandId }"
            >
                <CardHeader
                    class="flex flex-row items-center justify-between border-b border-border/50 bg-muted/20 pb-4"
                >
                    <div class="flex items-center gap-2">
                        <Button
                            variant="ghost"
                            size="icon"
                            class="-ml-2 h-8 w-8 rounded-full lg:hidden"
                            @click="resetSelection"
                        >
                            <ArrowLeft class="h-4 w-4" />
                        </Button>
                        <CardTitle
                            class="font-heading text-sm font-bold tracking-widest text-muted-foreground uppercase"
                        >
                            Modèles
                        </CardTitle>
                    </div>
                    <Button
                        v-if="selectedBrandId"
                        variant="outline"
                        size="sm"
                        class="h-8 gap-2 border-border/40 text-xs font-bold transition-all hover:border-primary/40 hover:bg-primary/5"
                        @click="openModelDialog()"
                    >
                        <Plus class="h-3.5 w-3.5" />
                        {{ __('admin.model_add') }}
                    </Button>
                </CardHeader>
                <CardContent class="p-0">
                    <div
                        v-if="!selectedBrandId"
                        class="flex flex-col items-center justify-center p-12 text-center text-muted-foreground"
                    >
                        <ChevronLeftCircle class="mb-4 h-12 w-12 opacity-20" />
                        <p class="text-sm italic">
                            Sélectionnez une marque pour voir ses modèles.
                        </p>
                    </div>
                    <div
                        v-else
                        class="max-h-[600px] divide-y divide-border/40 overflow-y-auto"
                    >
                        <div
                            v-for="model in models"
                            :key="model.id"
                            class="group relative flex items-center justify-between p-4 transition-all hover:bg-muted/50"
                        >
                            <span
                                class="font-heading text-sm font-bold tracking-tight"
                            >
                                {{ model.name }}
                            </span>
                            <div
                                class="flex items-center gap-1 opacity-0 transition-opacity group-hover:opacity-100"
                            >
                                <Button
                                    variant="ghost"
                                    size="icon"
                                    class="h-8 w-8 cursor-pointer rounded-full"
                                    @click="openModelDialog(model)"
                                >
                                    <Pencil class="h-3.5 w-3.5" />
                                </Button>
                                <Button
                                    variant="ghost"
                                    size="icon"
                                    class="h-8 w-8 cursor-pointer rounded-full text-destructive hover:bg-destructive/10 hover:text-destructive"
                                    @click="confirmDeleteModel(model)"
                                >
                                    <Trash2 class="h-3.5 w-3.5" />
                                </Button>
                            </div>
                        </div>
                        <div
                            v-if="models.length === 0"
                            class="flex flex-col items-center justify-center p-12 text-center text-muted-foreground"
                        >
                            <Layers class="mb-4 h-12 w-12 opacity-20" />
                            <p class="text-sm italic">
                                {{ __('admin.no_models_found') }}
                            </p>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>

        <!-- Brand Dialog -->
        <Dialog v-model:open="brandDialogOpen">
            <DialogContent class="sm:max-w-[425px]">
                <DialogHeader>
                    <DialogTitle class="font-heading text-xl">
                        {{
                            editingBrand
                                ? __('admin.brand_edit')
                                : __('admin.brand_add')
                        }}
                    </DialogTitle>
                    <DialogDescription
                        class="font-body text-sm text-muted-foreground"
                    >
                        {{
                            editingBrand
                                ? __('admin.brand_edit_desc')
                                : __('admin.brand_add_desc')
                        }}
                    </DialogDescription>
                </DialogHeader>
                <form @submit.prevent="saveBrand" class="space-y-4 py-4">
                    <div class="space-y-2">
                        <Label
                            class="font-heading text-xs font-bold tracking-widest uppercase"
                            >{{ __('admin.brand_name') }}</Label
                        >
                        <Input
                            v-model="brandForm.name"
                            placeholder="ex: BMW, Audi, Mercedes..."
                            class="bg-muted/30 focus-visible:ring-primary/20"
                            :class="{
                                'border-destructive': brandForm.errors.name,
                            }"
                        />
                        <p
                            v-if="brandForm.errors.name"
                            class="text-[10px] font-bold text-destructive"
                        >
                            {{ brandForm.errors.name }}
                        </p>
                    </div>
                    <DialogFooter>
                        <Button
                            type="button"
                            variant="ghost"
                            @click="brandDialogOpen = false"
                            >{{ __('admin.cancel') }}</Button
                        >
                        <Button
                            type="submit"
                            :disabled="brandForm.processing"
                            class="shadow-xs"
                        >
                            {{
                                brandForm.processing
                                    ? 'Enregistrement...'
                                    : __('admin.save_changes')
                            }}
                        </Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>

        <!-- Model Dialog -->
        <Dialog v-model:open="modelDialogOpen">
            <DialogContent class="sm:max-w-[425px]">
                <DialogHeader>
                    <DialogTitle class="font-heading text-xl">
                        {{
                            editingModel
                                ? __('admin.model_edit')
                                : __('admin.model_add')
                        }}
                    </DialogTitle>
                    <DialogDescription
                        class="font-body text-sm text-muted-foreground"
                    >
                        {{
                            editingModel
                                ? __('admin.model_edit_desc')
                                : __('admin.model_add_desc')
                        }}
                    </DialogDescription>
                </DialogHeader>
                <form @submit.prevent="saveModel" class="space-y-4 py-4">
                    <div class="space-y-2">
                        <Label
                            class="font-heading text-xs font-bold tracking-widest uppercase"
                            >{{ __('admin.model_name') }}</Label
                        >
                        <Input
                            v-model="modelForm.name"
                            placeholder="ex: X5, A4, Classe C..."
                            class="bg-muted/30 focus-visible:ring-primary/20"
                            :class="{
                                'border-destructive': modelForm.errors.name,
                            }"
                        />
                        <p
                            v-if="modelForm.errors.name"
                            class="text-[10px] font-bold text-destructive"
                        >
                            {{ modelForm.errors.name }}
                        </p>
                    </div>
                    <DialogFooter>
                        <Button
                            type="button"
                            variant="ghost"
                            @click="modelDialogOpen = false"
                            >{{ __('admin.cancel') }}</Button
                        >
                        <Button
                            type="submit"
                            :disabled="modelForm.processing"
                            class="shadow-xs"
                        >
                            {{
                                modelForm.processing
                                    ? 'Enregistrement...'
                                    : __('admin.save_changes')
                            }}
                        </Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>

        <!-- Delete Confirmation Dialog -->
        <AlertDialog v-model:open="isDeleteDialogOpen">
            <AlertDialogContent class="font-body">
                <AlertDialogHeader>
                    <AlertDialogTitle class="font-heading text-xl">
                        {{
                            deleteType === 'brand'
                                ? __('admin.confirm_delete_brand')
                                : __('admin.confirm_delete_model')
                        }}
                    </AlertDialogTitle>
                    <AlertDialogDescription
                        class="font-body text-sm leading-relaxed"
                    >
                        {{
                            deleteType === 'brand'
                                ? __('admin.confirm_delete_brand_desc', {
                                      name: itemToDelete?.name,
                                  })
                                : __('admin.confirm_delete_model_desc', {
                                      name: itemToDelete?.name,
                                  })
                        }}
                    </AlertDialogDescription>
                </AlertDialogHeader>
                <AlertDialogFooter>
                    <AlertDialogCancel
                        @click="isDeleteDialogOpen = false"
                        class="cursor-pointer"
                    >
                        {{ __('ui.cancel') }}
                    </AlertDialogCancel>
                    <AlertDialogAction
                        @click="handleConfirmDelete"
                        class="cursor-pointer bg-destructive text-destructive-foreground transition-all hover:bg-destructive/90 active:scale-95"
                    >
                        {{ __('ui.delete') }}
                    </AlertDialogAction>
                </AlertDialogFooter>
            </AlertDialogContent>
        </AlertDialog>
    </div>
</template>

<script setup lang="ts">
import { router, useForm } from '@inertiajs/vue3';
import {
    Plus,
    Pencil,
    Trash2,
    Car,
    ChevronLeftCircle,
    Layers,
    ArrowLeft,
} from 'lucide-vue-next';
import { ref } from 'vue';
import { index as adminDashboardIndex } from '@/actions/App/Http/Controllers/Admin/AdminDashboardController';
import {
    store as storeBrand,
    update as updateBrand,
    destroy as destroyBrand,
} from '@/actions/App/Http/Controllers/Admin/VehicleBrandController';
import {
    store as storeModel,
    update as updateModel,
    destroy as destroyModel,
} from '@/actions/App/Http/Controllers/Admin/VehicleModelController';
import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
} from '@/components/ui/alert-dialog';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { useTranslation } from '@/composables/useTranslation';

const props = defineProps<{
    brands: any[];
    models: any[];
    filters: any;
}>();

const { __ } = useTranslation();

const selectedBrandId = ref(
    props.filters.brand_id ? parseInt(props.filters.brand_id) : null,
);

const selectBrand = (id: number) => {
    selectedBrandId.value = id;
    router.get(
        adminDashboardIndex.url({
            query: {
                tab: 'catalog',
                brand_id: id,
            },
        }),
        {},
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        },
    );
};

const resetSelection = () => {
    selectedBrandId.value = null;
    router.get(
        adminDashboardIndex.url({
            query: {
                tab: 'catalog',
            },
        }),
        {},
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        },
    );
};

// Brand Management
const brandDialogOpen = ref(false);
const editingBrand = ref<any>(null);
const brandForm = useForm({
    name: '',
});

const openBrandDialog = (brand?: any) => {
    editingBrand.value = brand || null;
    brandForm.name = brand ? brand.name : '';
    brandForm.clearErrors();
    brandDialogOpen.value = true;
};

const saveBrand = () => {
    if (editingBrand.value) {
        brandForm.put(updateBrand.url(editingBrand.value.id), {
            onSuccess: () => (brandDialogOpen.value = false),
        });
    } else {
        brandForm.post(storeBrand.url(), {
            onSuccess: () => {
                brandDialogOpen.value = false;
                brandForm.reset();
            },
        });
    }
};

const isDeleteDialogOpen = ref(false);
const itemToDelete = ref<any>(null);
const deleteType = ref<'brand' | 'model'>('brand');

const confirmDeleteBrand = (brand: any) => {
    itemToDelete.value = brand;
    deleteType.value = 'brand';
    isDeleteDialogOpen.value = true;
};

const deleteBrand = () => {
    if (!itemToDelete.value) return;
    router.delete(destroyBrand.url(itemToDelete.value.id), {
        onSuccess: () => {
            isDeleteDialogOpen.value = false;
            itemToDelete.value = null;
        },
    });
};

// Model Management
const modelDialogOpen = ref(false);
const editingModel = ref<any>(null);
const modelForm = useForm({
    brand_id: 0,
    name: '',
});

const openModelDialog = (model?: any) => {
    editingModel.value = model || null;
    modelForm.name = model ? model.name : '';
    modelForm.brand_id = selectedBrandId.value || 0;
    modelForm.clearErrors();
    modelDialogOpen.value = true;
};

const saveModel = () => {
    if (editingModel.value) {
        modelForm.put(updateModel.url(editingModel.value.id), {
            onSuccess: () => (modelDialogOpen.value = false),
        });
    } else {
        modelForm.post(storeModel.url(), {
            onSuccess: () => {
                modelDialogOpen.value = false;
                modelForm.reset();
            },
        });
    }
};

const confirmDeleteModel = (model: any) => {
    itemToDelete.value = model;
    deleteType.value = 'model';
    isDeleteDialogOpen.value = true;
};

const deleteModel = () => {
    if (!itemToDelete.value) return;
    router.delete(destroyModel.url(itemToDelete.value.id), {
        onSuccess: () => {
            isDeleteDialogOpen.value = false;
            itemToDelete.value = null;
        },
    });
};

const handleConfirmDelete = () => {
    if (deleteType.value === 'brand') {
        deleteBrand();
    } else {
        deleteModel();
    }
};
</script>
