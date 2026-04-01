<!-- eslint-disable vue/no-mutating-props -->
<template>
    <!-- Section 1: Informations générales -->
    <section :id="SECTION_IDS.general">
        <Card class="overflow-hidden pt-0">
            <CardHeader
                class="dark rounded-t-xl bg-background py-5 text-card-foreground"
            >
                <div class="flex items-center gap-3">
                    <div
                        class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary/10 text-primary"
                    >
                        <Info class="h-4 w-4" />
                    </div>
                    <div>
                        <CardTitle class="text-base">{{
                            __('vehicleAd.general_info')
                        }}</CardTitle>
                        <CardDescription class="text-xs">{{
                            __('vehicleAd.general_info_desc')
                        }}</CardDescription>
                    </div>
                </div>
            </CardHeader>
            <CardContent class="p-6">
                <div
                    class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3"
                >
                    <div class="space-y-2">
                        <Label for="brand_id"
                            >{{ __('vehicleAd.brand') }} *</Label
                        >
                        <SearchSelect
                            v-model="form.brand_id"
                            :options="brands"
                            :show-all-option="false"
                            :placeholder="__('ui.select')"
                        />
                        <InputError :message="form.errors.brand_id" />
                    </div>
                    <div class="space-y-2">
                        <Label for="model_id"
                            >{{ __('vehicleAd.model') }} *</Label
                        >
                        <SearchSelect
                            v-model="form.model_id"
                            :options="models"
                            :disabled="!form.brand_id || models.length === 0"
                            :show-all-option="false"
                            :placeholder="__('ui.select')"
                        />
                        <InputError :message="form.errors.model_id" />
                    </div>
                    <div class="space-y-2">
                        <Label for="vehicle_version_name"
                            >{{ __('vehicleAd.version') }} *</Label
                        >
                        <Input
                            id="vehicle_version_name"
                            v-model="form.vehicle_version_name"
                            type="text"
                            placeholder="Ex: 1.9 TDI 130ch"
                        />
                        <InputError
                            :message="form.errors.vehicle_version_name"
                        />
                    </div>

                    <div class="space-y-2 md:col-span-2 lg:col-span-1">
                        <Label for="vin">{{ __('vehicleAd.vin') }}</Label>
                        <Input
                            id="vin"
                            v-model="form.vin"
                            type="text"
                            placeholder="Ex: WVWZZZ..."
                        />
                        <InputError :message="form.errors.vin" />
                    </div>

                    <div class="space-y-2">
                        <Label for="first_registration_date"
                            >{{
                                __('vehicleAd.first_registration')
                            }}
                            *</Label
                        >
                        <DatePicker
                            v-model="form.first_registration_date"
                            required
                        />
                        <InputError
                            :message="form.errors.first_registration_date"
                        />
                    </div>
                    <div class="hidden space-y-2 lg:block"></div>
                    <div class="space-y-2">
                        <Label for="purchase_date">{{
                            __('vehicleAd.purchase_date')
                        }}</Label>
                        <DatePicker v-model="form.purchase_date" />
                        <InputError :message="form.errors.purchase_date" />
                    </div>

                    <div class="space-y-2">
                        <Label for="price"
                            >{{ __('vehicleAd.price_eur') }} *</Label
                        >
                        <Input
                            id="price"
                            v-model="form.price"
                            type="number"
                            step="0.01"
                            min="0"
                            required
                        />
                        <InputError :message="form.errors.price" />
                    </div>
                    <div class="space-y-2">
                        <Label for="mileage"
                            >{{ __('vehicleAd.mileage_km') }} *</Label
                        >
                        <Input
                            id="mileage"
                            v-model="form.mileage"
                            type="number"
                            min="0"
                            required
                        />
                        <InputError :message="form.errors.mileage" />
                    </div>
                    <div class="space-y-2">
                        <Label for="previous_owner">{{
                            __('vehicleAd.previous_owners')
                        }}</Label>
                        <Input
                            id="previous_owner"
                            v-model="form.previous_owner"
                            type="number"
                            min="0"
                        />
                        <InputError :message="form.errors.previous_owner" />
                    </div>
                </div>
            </CardContent>
        </Card>
    </section>

    <!-- Section 2: Spécifications techniques -->
    <section :id="SECTION_IDS.technical">
        <Card class="overflow-hidden pt-0">
            <CardHeader
                class="dark rounded-t-xl bg-background py-5 text-card-foreground"
            >
                <div class="flex items-center gap-3">
                    <div
                        class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary/10 text-primary"
                    >
                        <Wrench class="h-4 w-4" />
                    </div>
                    <div>
                        <CardTitle class="text-base">{{
                            __('vehicleAd.technical_specs')
                        }}</CardTitle>
                        <CardDescription class="text-xs">{{
                            __('vehicleAd.technical_specs_desc')
                        }}</CardDescription>
                    </div>
                </div>
            </CardHeader>
            <CardContent class="p-6">
                <div
                    class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4"
                >
                    <div class="space-y-2 md:col-span-2">
                        <Label for="body_type_id"
                            >{{ __('vehicleAd.body') }} *</Label
                        >
                        <Select v-model="form.body_type_id">
                            <SelectTrigger
                                ><SelectValue
                                    :placeholder="__('ui.select')"
                            /></SelectTrigger>
                            <SelectContent>
                                <SelectItem
                                    v-for="body in bodyTypes"
                                    :key="body.id"
                                    :value="String(body.id)"
                                    >{{ body.code }}</SelectItem
                                >
                            </SelectContent>
                        </Select>
                        <InputError :message="form.errors.body_type_id" />
                    </div>
                    <div class="space-y-2">
                        <Label>{{ __('vehicleAd.doors') }}</Label>
                        <Select v-model="form.doors">
                            <SelectTrigger
                                ><SelectValue placeholder="-"
                            /></SelectTrigger>
                            <SelectContent>
                                <SelectItem
                                    v-for="d in [2, 3, 4, 5]"
                                    :key="d"
                                    :value="String(d)"
                                    >{{ d }}</SelectItem
                                >
                            </SelectContent>
                        </Select>
                        <InputError :message="form.errors.doors" />
                    </div>
                    <div class="space-y-2">
                        <Label>{{ __('vehicleAd.seats') }}</Label>
                        <Select v-model="form.seats">
                            <SelectTrigger
                                ><SelectValue placeholder="-"
                            /></SelectTrigger>
                            <SelectContent>
                                <SelectItem
                                    v-for="s in [2, 3, 4, 5, 7, 9]"
                                    :key="s"
                                    :value="String(s)"
                                    >{{ s }}</SelectItem
                                >
                            </SelectContent>
                        </Select>
                        <InputError :message="form.errors.seats" />
                    </div>

                    <div
                        class="col-span-1 my-1 border-t border-border/50 md:col-span-2 lg:col-span-4"
                    ></div>

                    <div class="space-y-2 md:col-span-2 lg:col-span-1">
                        <Label for="fuel_type_id"
                            >{{ __('vehicleAd.fuel') }} *</Label
                        >
                        <Select v-model="form.fuel_type_id">
                            <SelectTrigger
                                ><SelectValue
                                    :placeholder="__('ui.select')"
                            /></SelectTrigger>
                            <SelectContent>
                                <SelectItem
                                    v-for="fuel in fuelTypes"
                                    :key="fuel.id"
                                    :value="String(fuel.id)"
                                    >{{ fuel.code }}</SelectItem
                                >
                            </SelectContent>
                        </Select>
                        <InputError :message="form.errors.fuel_type_id" />
                    </div>
                    <div class="space-y-2">
                        <Label for="power_kw">{{
                            __('vehicleAd.power_kw')
                        }}</Label>
                        <Input
                            id="power_kw"
                            v-model="form.power_kw"
                            type="number"
                            min="0"
                            @input="(e: Event) => updateHpFromKw((e.target as HTMLInputElement).value)"
                        />
                        <InputError :message="form.errors.power_kw" />
                    </div>
                    <div class="space-y-2">
                        <Label for="power_hp">{{
                            __('vehicleAd.power_hp')
                        }}</Label>
                        <Input
                            id="power_hp"
                            v-model="powerHp"
                            type="number"
                            min="0"
                            @input="(e: Event) => updateKwFromHp((e.target as HTMLInputElement).value)"
                        />
                    </div>
                    <div class="space-y-2">
                        <Label for="engine_displacement">{{
                            __('vehicleAd.displacement_cm3')
                        }}</Label>
                        <Input
                            id="engine_displacement"
                            v-model="form.engine_displacement"
                            type="number"
                            min="0"
                        />
                        <InputError
                            :message="form.errors.engine_displacement"
                        />
                    </div>
                    <div class="space-y-2">
                        <Label for="cylinder_count">{{
                            __('vehicleAd.cylinders')
                        }}</Label>
                        <Input
                            id="cylinder_count"
                            v-model="form.cylinder_count"
                            type="number"
                            min="0"
                        />
                        <InputError :message="form.errors.cylinder_count" />
                    </div>

                    <div
                        class="col-span-1 my-1 border-t border-border/50 md:col-span-2 lg:col-span-4"
                    ></div>

                    <div class="space-y-2 md:col-span-2 lg:col-span-1">
                        <Label for="transmission_type_id"
                            >{{ __('vehicleAd.transmission') }} *</Label
                        >
                        <Select v-model="form.transmission_type_id">
                            <SelectTrigger
                                ><SelectValue
                                    :placeholder="__('ui.select')"
                            /></SelectTrigger>
                            <SelectContent>
                                <SelectItem
                                    v-for="trans in transmissionTypes"
                                    :key="trans.id"
                                    :value="String(trans.id)"
                                    >{{ trans.code }}</SelectItem
                                >
                            </SelectContent>
                        </Select>
                        <InputError
                            :message="form.errors.transmission_type_id"
                        />
                    </div>
                    <div class="space-y-2">
                        <Label for="gear_count">{{
                            __('vehicleAd.gear_count')
                        }}</Label>
                        <Input
                            id="gear_count"
                            v-model="form.gear_count"
                            type="number"
                            min="0"
                        />
                        <InputError :message="form.errors.gear_count" />
                    </div>
                    <div class="space-y-2">
                        <Label for="euro_norm_id">{{
                            __('vehicleAd.euro_norm')
                        }}</Label>
                        <Select v-model="form.euro_norm_id">
                            <SelectTrigger
                                ><SelectValue
                                    :placeholder="__('ui.select')"
                            /></SelectTrigger>
                            <SelectContent>
                                <SelectItem
                                    v-for="euro in euroNorms"
                                    :key="euro.id"
                                    :value="String(euro.id)"
                                    >{{ euro.code }}</SelectItem
                                >
                            </SelectContent>
                        </Select>
                        <InputError :message="form.errors.euro_norm_id" />
                    </div>
                    <div class="space-y-2">
                        <Label for="fuel_consumption_avg">{{
                            __('vehicleAd.consumption_avg')
                        }}</Label>
                        <Input
                            id="fuel_consumption_avg"
                            v-model="form.fuel_consumption_avg"
                            type="number"
                            step="0.1"
                            min="0"
                        />
                        <InputError
                            :message="form.errors.fuel_consumption_avg"
                        />
                    </div>
                    <div class="space-y-2">
                        <Label for="co2_emission">{{
                            __('vehicleAd.co2_emissions')
                        }}</Label>
                        <Input
                            id="co2_emission"
                            v-model="form.co2_emission"
                            type="number"
                            min="0"
                        />
                        <InputError :message="form.errors.co2_emission" />
                    </div>
                    <div class="space-y-2">
                        <Label for="weight_kg">{{
                            __('vehicleAd.weight_kg')
                        }}</Label>
                        <Input
                            id="weight_kg"
                            v-model="form.weight_kg"
                            type="number"
                            min="0"
                        />
                        <InputError :message="form.errors.weight_kg" />
                    </div>
                </div>
            </CardContent>
        </Card>
    </section>

    <!-- Section 3: Esthétique & État -->
    <section :id="SECTION_IDS.aesthetics">
        <Card class="overflow-hidden pt-0">
            <CardHeader
                class="dark rounded-t-xl bg-background py-5 text-card-foreground"
            >
                <div class="flex items-center gap-3">
                    <div
                        class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary/10 text-primary"
                    >
                        <Palette class="h-4 w-4" />
                    </div>
                    <div>
                        <CardTitle class="text-base">{{
                            __('vehicleAd.aesthetics_condition')
                        }}</CardTitle>
                        <CardDescription class="text-xs">{{
                            __('vehicleAd.aesthetics_condition_desc')
                        }}</CardDescription>
                    </div>
                </div>
            </CardHeader>
            <CardContent class="p-6">
                <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
                    <!-- Couleurs -->
                    <div class="space-y-5">
                        <h3
                            class="flex items-center gap-2 border-b border-border/50 pb-2.5 text-sm font-bold text-foreground"
                        >
                            <Paintbrush class="h-4 w-4 text-primary" />
                            {{ __('vehicleAd.appearance') }}
                        </h3>
                        <div
                            class="grid grid-cols-1 items-end gap-6 sm:grid-cols-2"
                        >
                            <div class="space-y-2">
                                <Label>{{
                                    __('vehicleAd.exterior_color')
                                }}</Label>
                                <Select v-model="form.exterior_color_id">
                                    <SelectTrigger
                                        ><SelectValue
                                            :placeholder="__('ui.select')"
                                    /></SelectTrigger>
                                    <SelectContent>
                                        <SelectItem
                                            v-for="color in exteriorColors"
                                            :key="color.id"
                                            :value="String(color.id)"
                                            >{{ color.code }}</SelectItem
                                        >
                                    </SelectContent>
                                </Select>
                                <InputError
                                    :message="form.errors.exterior_color_id"
                                />
                            </div>
                            <div class="flex h-10 items-center gap-2.5">
                                <Checkbox
                                    id="exterior_color_metalised"
                                    :checked="form.exterior_color_metalised"
                                    @update:checked="(v: boolean) => (form.exterior_color_metalised = v)"
                                />
                                <Label
                                    for="exterior_color_metalised"
                                    class="cursor-pointer"
                                    >{{
                                        __('vehicleAd.metallic_paint')
                                    }}</Label
                                >
                            </div>
                        </div>
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div class="space-y-2">
                                <Label>{{
                                    __('vehicleAd.interior_color')
                                }}</Label>
                                <Select v-model="form.interior_color_id">
                                    <SelectTrigger
                                        ><SelectValue
                                            :placeholder="__('ui.select')"
                                    /></SelectTrigger>
                                    <SelectContent>
                                        <SelectItem
                                            v-for="color in interiorColors"
                                            :key="color.id"
                                            :value="String(color.id)"
                                            >{{ color.code }}</SelectItem
                                        >
                                    </SelectContent>
                                </Select>
                                <InputError
                                    :message="form.errors.interior_color_id"
                                />
                            </div>
                            <div class="space-y-2">
                                <Label>{{
                                    __('vehicleAd.interior_material')
                                }}</Label>
                                <Select v-model="form.interior_type_id">
                                    <SelectTrigger
                                        ><SelectValue
                                            :placeholder="__('ui.select')"
                                    /></SelectTrigger>
                                    <SelectContent>
                                        <SelectItem
                                            v-for="type in interiorTypes"
                                            :key="type.id"
                                            :value="String(type.id)"
                                            >{{ type.code }}</SelectItem
                                        >
                                    </SelectContent>
                                </Select>
                                <InputError
                                    :message="form.errors.interior_type_id"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- État Général -->
                    <div class="space-y-5">
                        <h3
                            class="flex items-center gap-2 border-b border-border/50 pb-2.5 text-sm font-bold text-foreground"
                        >
                            <ClipboardCheck class="h-4 w-4 text-primary" />
                            {{ __('vehicleAd.state_history') }}
                        </h3>
                        <div
                            class="grid grid-cols-1 gap-x-6 gap-y-3.5 sm:grid-cols-2"
                        >
                            <div
                                v-for="check in stateChecks"
                                :key="check.id"
                                class="flex items-center gap-2.5"
                            >
                                <Checkbox
                                    :id="check.id"
                                    :checked="form[check.field as keyof typeof form]"
                                    @update:checked="(v: boolean) => ((form as any)[check.field] = v)"
                                />
                                <Label
                                    :for="check.id"
                                    class="cursor-pointer leading-tight"
                                    >{{ check.label }}</Label
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </CardContent>
        </Card>
    </section>

    <!-- Section 4: Équipements -->
    <section :id="SECTION_IDS.equipment">
        <Card class="overflow-hidden pt-0">
            <CardHeader
                class="dark rounded-t-xl bg-background py-5 text-card-foreground"
            >
                <div class="flex items-center gap-3">
                    <div
                        class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary/10 text-primary"
                    >
                        <ListChecks class="h-4 w-4" />
                    </div>
                    <div>
                        <CardTitle class="text-base">{{
                            __('vehicleAd.equipment_options')
                        }}</CardTitle>
                        <CardDescription class="text-xs">{{
                            __('vehicleAd.equipment_options_desc')
                        }}</CardDescription>
                    </div>
                </div>
            </CardHeader>
            <CardContent class="p-6">
                <div class="space-y-6">
                    <div
                        v-for="category in featureCategories"
                        :key="category.id"
                        class="space-y-3"
                    >
                        <h3
                            class="text-xs font-semibold tracking-wider text-muted-foreground uppercase"
                        >
                            {{
                                formatOptionLabel(
                                    category.code ?? category.key,
                                )
                            }}
                        </h3>
                        <div
                            class="grid grid-cols-1 gap-x-6 gap-y-3 sm:grid-cols-2 lg:grid-cols-3"
                        >
                            <div
                                v-for="feature in category.features"
                                :key="feature.id"
                                class="flex items-start gap-3"
                            >
                                <Checkbox
                                    :id="`feature-${feature.id}`"
                                    :model-value="
                                        isFeatureSelected(feature.id)
                                    "
                                    @update:model-value="
                                        (
                                            checked:
                                                | boolean
                                                | 'indeterminate',
                                        ) =>
                                            emit(
                                                'toggle-feature',
                                                String(feature.id),
                                                checked,
                                            )
                                    "
                                />
                                <Label
                                    :for="`feature-${feature.id}`"
                                    class="flex-1 cursor-pointer text-sm leading-snug"
                                    >{{
                                        formatOptionLabel(
                                            feature.code ?? feature.key,
                                        )
                                    }}</Label
                                >
                            </div>
                        </div>
                    </div>
                </div>
                <InputError :message="form.errors.features" class="mt-2" />
            </CardContent>
        </Card>
    </section>

    <!-- Section 5: Photos (Gallery) -->
    <section :id="SECTION_IDS.photos">
        <AdProcessingProgress
            v-if="isProcessingImages"
            :vehicle-id="vehicleId ?? 0"
            :mode="mode"
            @close="emit('close-processing')"
        />

        <GalleryManager
            v-model="form.images"
            :existing-media="existingMedia"
            :errors="form.errors"
            @update:media-order="
                (ids: number[]) => emit('update:media-order', ids)
            "
            @update:media-to-delete="
                (ids: number[]) => emit('update:media-to-delete', ids)
            "
        />
    </section>

    <!-- Section 6: Description -->
    <section :id="SECTION_IDS.description">
        <Card class="overflow-hidden pt-0">
            <CardHeader
                class="dark rounded-t-xl bg-background py-5 text-card-foreground"
            >
                <div class="flex items-center gap-3">
                    <div
                        class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary/10 text-primary"
                    >
                        <FileText class="h-4 w-4" />
                    </div>
                    <div>
                        <CardTitle class="text-base">{{
                            __('vehicleAd.detailed_description')
                        }}</CardTitle>
                        <CardDescription class="text-xs">{{
                            __('vehicleAd.detailed_description_desc')
                        }}</CardDescription>
                    </div>
                </div>
            </CardHeader>
            <CardContent class="p-6">
                <Textarea
                    id="description"
                    v-model="form.description"
                    rows="6"
                    :placeholder="
                        __('vehicleAd.detailed_description_placeholder')
                    "
                />
                <InputError :message="form.errors.description" />
            </CardContent>
        </Card>
    </section>
</template>

<script setup lang="ts">
import {
    Info,
    Wrench,
    Palette,
    Paintbrush,
    ClipboardCheck,
    ListChecks,
    FileText,
} from 'lucide-vue-next';
import { computed, onMounted, ref, watch } from 'vue';
import DatePicker from '@/components/DatePicker.vue';
import InputError from '@/components/InputError.vue';
import SearchSelect from '@/components/SearchSelect.vue';
import {
    Card,
    CardHeader,
    CardTitle,
    CardDescription,
    CardContent,
} from '@/components/ui/card';
import { Checkbox } from '@/components/ui/checkbox';
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
import AdProcessingProgress from '@/components/VehicleAds/AdProcessingProgress.vue';
import GalleryManager from '@/components/VehicleAds/GalleryManager.vue';
import { SECTION_IDS } from '@/components/VehicleAds/VehicleAdFormSectionIds';
import { useTranslation } from '@/composables/useTranslation';
import { kwToHp, hpToKw } from '@/lib/utils';

const { __ } = useTranslation();

type FeatureOption = {
    id: number | string;
    code?: string;
    key?: string;
};

type FeatureCategoryOption = {
    id: number | string;
    code?: string;
    key?: string;
    features: FeatureOption[];
};

const props = defineProps<{
     
    form: any;
    brands: any[];
    models: any[];
    fuelTypes: any[];
    bodyTypes: any[];
    transmissionTypes: any[];
    exteriorColors: any[];
    interiorColors: any[];
    interiorTypes: any[];
    euroNorms: any[];
    featureCategories: FeatureCategoryOption[];
    existingMedia?: any[];
    isProcessingImages?: boolean;
    vehicleId?: number | null;
    mode?: 'create' | 'edit';
}>();

const emit = defineEmits<{
    'toggle-feature': [id: string, checked: boolean | 'indeterminate'];
    'close-processing': [];
    'update:media-order': [ids: number[]];
    'update:media-to-delete': [ids: number[]];
}>();

const normalizedFeatureIds = computed<string[]>(() =>
    (props.form.features ?? []).map((id: string) => String(id)),
);

const isFeatureSelected = (featureId: number | string): boolean =>
    normalizedFeatureIds.value.includes(String(featureId));

const formatOptionLabel = (value?: string): string => {
    if (!value) return '';
    return value.replace(/[_-]+/g, ' ').trim();
};

const stateChecks = computed(() => [
    { id: 'is_damaged', field: 'is_damaged', label: __('vehicleAd.damaged') },
    {
        id: 'has_accident',
        field: 'has_accident',
        label: __('vehicleAd.accident'),
    },
    {
        id: 'complete_maintenance_book',
        field: 'complete_maintenance_book',
        label: __('vehicleAd.full_service_history'),
    },
    {
        id: 'non_smoker',
        field: 'non_smoker',
        label: __('vehicleAd.non_smoker'),
    },
    {
        id: 'technical_inspection_status',
        field: 'technical_inspection_status',
        label: __('vehicleAd.technical_inspection'),
    },
]);

const powerHp = ref<number | undefined>(
    props.form.power_kw ? kwToHp(Number(props.form.power_kw)) : undefined,
);

// Sync powerHp when form.power_kw changes from outside (e.g. data load)
watch(
    () => props.form.power_kw,
    (newVal) => {
        if (newVal !== null && newVal !== undefined && newVal !== '') {
            const calculatedHp = kwToHp(Number(newVal));
            if (powerHp.value !== calculatedHp) {
                powerHp.value = calculatedHp;
            }
        } else {
            powerHp.value = undefined;
        }
    },
);

const updateHpFromKw = (val: string | number | null) => {
    if (val === null || val === '') {
        // eslint-disable-next-line vue/no-mutating-props
        props.form.power_kw = null;
        powerHp.value = undefined;
        return;
    }
    const kw = Number(val);
    const hp = kwToHp(kw);
    powerHp.value = hp;
    // eslint-disable-next-line vue/no-mutating-props
    props.form.power_kw = kw;
};

const updateKwFromHp = (val: string | number | null) => {
    if (val === null || val === '') {
        // eslint-disable-next-line vue/no-mutating-props
        props.form.power_kw = null;
        powerHp.value = undefined;
        return;
    }
    const hp = Number(val);
    const kw = hpToKw(hp);
    // eslint-disable-next-line vue/no-mutating-props
    props.form.power_kw = kw;
    powerHp.value = hp;
};

onMounted(() => {
    if (props.form.power_kw) {
        powerHp.value = kwToHp(Number(props.form.power_kw));
    }
});
</script>
