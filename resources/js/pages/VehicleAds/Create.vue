<template>
    <Head title="Créer une annonce" />

    <AppLayout>
        <div class="mx-auto max-w-5xl p-4 md:p-8">
            <h1 class="mb-8 text-3xl font-bold tracking-tight">
                Créer une nouvelle annonce
            </h1>

            <form @submit.prevent="submit('active')" class="space-y-8">
                <!-- Section 1 : Informations générales -->
                <Card class="pt-0">
                    <CardHeader
                        class="dark rounded-t-xl bg-background py-6 text-card-foreground"
                    >
                        <CardTitle>Informations générales</CardTitle>
                        <CardDescription>
                            Les informations principales d'identification et de
                            vente du véhicule.
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div
                            class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3"
                        >
                            <!-- Véhicule Identification -->
                            <div class="space-y-2">
                                <Label for="brand_id">Marque *</Label>
                                <Select v-model="form.brand_id" required>
                                    <SelectTrigger
                                        ><SelectValue
                                            placeholder="Sélectionnez"
                                    /></SelectTrigger>
                                    <SelectContent>
                                        <SelectItem
                                            v-for="brand in brands"
                                            :key="brand.id"
                                            :value="String(brand.id)"
                                            >{{ brand.name }}</SelectItem
                                        >
                                    </SelectContent>
                                </Select>
                                <InputError :message="form.errors.brand_id" />
                            </div>
                            <div class="space-y-2">
                                <Label for="model_id">Modèle *</Label>
                                <Select
                                    v-model="form.model_id"
                                    :disabled="
                                        !form.brand_id || models.length === 0
                                    "
                                    required
                                >
                                    <SelectTrigger
                                        ><SelectValue
                                            placeholder="Sélectionnez"
                                    /></SelectTrigger>
                                    <SelectContent>
                                        <SelectItem
                                            v-for="model in models"
                                            :key="model.id"
                                            :value="String(model.id)"
                                            >{{ model.name }}</SelectItem
                                        >
                                    </SelectContent>
                                </Select>
                                <InputError :message="form.errors.model_id" />
                            </div>
                            <!-- <div class="space-y-2">
                                <Label for="vehicle_version_id">Version</Label>
                                <Select
                                    v-model="form.vehicle_version_id"
                                    :disabled="
                                        !form.model_id || versions.length === 0
                                    "
                                >
                                    <SelectTrigger
                                        ><SelectValue
                                            placeholder="Sélectionnez"
                                    /></SelectTrigger>
                                    <SelectContent>
                                        <SelectItem
                                            v-for="version in versions"
                                            :key="version.id"
                                            :value="String(version.id)"
                                            >{{ version.name }}</SelectItem
                                        >
                                    </SelectContent>
                                </Select>
                                <InputError
                                    :message="form.errors.vehicle_version_id"
                                />
                            </div> -->
                            <div class="space-y-2">
                                <Label for="vehicle_version_name"
                                    >Version *</Label
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

                            <!-- Numéros d'identification -->
                            <div class="space-y-2 md:col-span-2 lg:col-span-1">
                                <Label for="vin">Numéro de châssis (VIN)</Label>
                                <Input
                                    id="vin"
                                    v-model="form.vin"
                                    type="text"
                                    placeholder="Ex: WVWZZZ..."
                                />
                                <InputError :message="form.errors.vin" />
                            </div>

                            <!-- Dates -->
                            <div class="space-y-2">
                                <Label for="first_registration_date"
                                    >1ère immatriculation *</Label
                                >
                                <DatePicker
                                    v-model="form.first_registration_date"
                                    required
                                />
                                <InputError
                                    :message="
                                        form.errors.first_registration_date
                                    "
                                />
                            </div>
                            <div class="hidden space-y-2 lg:block"></div>
                            <!-- Spacer for grid alignment -->
                            <div class="space-y-2">
                                <Label for="purchase_date">Date d'achat</Label>
                                <DatePicker v-model="form.purchase_date" />
                                <InputError
                                    :message="form.errors.purchase_date"
                                />
                            </div>

                            <!-- Données de base Vente -->
                            <div class="space-y-2">
                                <Label for="price">Prix (€) *</Label>
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
                                <Label for="mileage">Kilométrage (km) *</Label>
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
                                <Label for="previous_owner"
                                    >Précédents proprios</Label
                                >
                                <Input
                                    id="previous_owner"
                                    v-model="form.previous_owner"
                                    type="number"
                                    min="0"
                                />
                                <InputError
                                    :message="form.errors.previous_owner"
                                />
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Section 2 : Spécifications techniques -->
                <Card class="pt-0">
                    <CardHeader
                        class="dark rounded-t-xl bg-card py-6 text-card-foreground"
                    >
                        <CardTitle>Spécifications techniques</CardTitle>
                        <CardDescription>
                            Détails sur le moteur, la transmission et le type
                            carrosserie.
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div
                            class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4"
                        >
                            <!-- Groupe Carrosserie -->
                            <div class="space-y-2 md:col-span-2">
                                <Label for="body_type_id"
                                    >Type de carrosserie *</Label
                                >
                                <Select v-model="form.body_type_id">
                                    <SelectTrigger
                                        ><SelectValue
                                            placeholder="Sélectionnez"
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
                                <InputError
                                    :message="form.errors.body_type_id"
                                />
                            </div>
                            <div class="space-y-2">
                                <Label>Portes</Label>
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
                                <Label>Sièges</Label>
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
                                class="col-span-1 my-2 border-t md:col-span-2 lg:col-span-4"
                            ></div>

                            <!-- Groupe Moteur / Énergie -->
                            <div class="space-y-2 md:col-span-2 lg:col-span-1">
                                <Label for="fuel_type_id">Carburant *</Label>
                                <Select v-model="form.fuel_type_id">
                                    <SelectTrigger
                                        ><SelectValue
                                            placeholder="Sélectionnez"
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
                                <InputError
                                    :message="form.errors.fuel_type_id"
                                />
                            </div>
                            <div class="space-y-2">
                                <Label for="power_kw">Puissance (kW)</Label>
                                <Input
                                    id="power_kw"
                                    v-model="form.power_kw"
                                    type="number"
                                    min="0"
                                />
                                <InputError :message="form.errors.power_kw" />
                            </div>
                            <div class="space-y-2">
                                <Label for="engine_displacement"
                                    >Cylindrée (cm³)</Label
                                >
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
                                <Label for="cylinder_count">Cylindres</Label>
                                <Input
                                    id="cylinder_count"
                                    v-model="form.cylinder_count"
                                    type="number"
                                    min="0"
                                />
                                <InputError
                                    :message="form.errors.cylinder_count"
                                />
                            </div>

                            <div
                                class="col-span-1 my-2 border-t md:col-span-2 lg:col-span-4"
                            ></div>

                            <!-- Groupe Transmission & Performance environnementales -->
                            <div class="space-y-2 md:col-span-2 lg:col-span-1">
                                <Label for="transmission_type_id"
                                    >Boîte de vitesse *</Label
                                >
                                <Select v-model="form.transmission_type_id">
                                    <SelectTrigger
                                        ><SelectValue
                                            placeholder="Sélectionnez"
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
                                <Label for="gear_count">Nb de vitesses</Label>
                                <Input
                                    id="gear_count"
                                    v-model="form.gear_count"
                                    type="number"
                                    min="0"
                                />
                                <InputError :message="form.errors.gear_count" />
                            </div>
                            <div class="space-y-2">
                                <Label for="euro_norm_id">Norme Euro</Label>
                                <Select v-model="form.euro_norm_id">
                                    <SelectTrigger
                                        ><SelectValue
                                            placeholder="Sélectionnez"
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
                                <InputError
                                    :message="form.errors.euro_norm_id"
                                />
                            </div>
                            <div class="space-y-2">
                                <Label for="fuel_consumption_avg"
                                    >Conso moy (L/100)</Label
                                >
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

                            <!-- Dernier groupe -->
                            <div class="space-y-2">
                                <Label for="co2_emission"
                                    >Émission CO2 (g/km)</Label
                                >
                                <Input
                                    id="co2_emission"
                                    v-model="form.co2_emission"
                                    type="number"
                                    min="0"
                                />
                                <InputError
                                    :message="form.errors.co2_emission"
                                />
                            </div>
                            <div class="space-y-2">
                                <Label for="weight_kg">Poids (kg)</Label>
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

                <!-- Section 3 : Esthétique & État -->
                <Card class="pt-0">
                    <CardHeader
                        class="dark rounded-t-xl bg-card py-6 text-card-foreground"
                    >
                        <CardTitle>Esthétique & État</CardTitle>
                        <CardDescription>
                            Design intérieur/extérieur et l'état de l'historique
                            du véhicule.
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
                            <!-- Couleurs & Habitacle -->
                            <div class="space-y-6">
                                <h3 class="border-b pb-2 font-medium">
                                    Apparence
                                </h3>
                                <div
                                    class="grid grid-cols-1 items-end gap-6 sm:grid-cols-2"
                                >
                                    <div class="space-y-2">
                                        <Label>Couleur extérieure</Label>
                                        <Select
                                            v-model="form.exterior_color_id"
                                        >
                                            <SelectTrigger
                                                ><SelectValue
                                                    placeholder="Sélectionnez"
                                            /></SelectTrigger>
                                            <SelectContent>
                                                <SelectItem
                                                    v-for="color in exteriorColors"
                                                    :key="color.id"
                                                    :value="String(color.id)"
                                                    >{{
                                                        color.code
                                                    }}</SelectItem
                                                >
                                            </SelectContent>
                                        </Select>
                                        <InputError
                                            :message="
                                                form.errors.exterior_color_id
                                            "
                                        />
                                    </div>
                                    <div
                                        class="flex h-10 items-center space-x-2"
                                    >
                                        <Checkbox
                                            id="exterior_color_metalised"
                                            :checked="
                                                form.exterior_color_metalised
                                            "
                                            @update:checked="
                                                (v: boolean) =>
                                                    (form.exterior_color_metalised =
                                                        v)
                                            "
                                        />
                                        <Label for="exterior_color_metalised"
                                            >Peinture métallisée</Label
                                        >
                                    </div>
                                </div>
                                <div
                                    class="grid grid-cols-1 gap-6 sm:grid-cols-2"
                                >
                                    <div class="space-y-2">
                                        <Label>Couleur intérieure</Label>
                                        <Select
                                            v-model="form.interior_color_id"
                                        >
                                            <SelectTrigger
                                                ><SelectValue
                                                    placeholder="Sélectionnez"
                                            /></SelectTrigger>
                                            <SelectContent>
                                                <SelectItem
                                                    v-for="color in interiorColors"
                                                    :key="color.id"
                                                    :value="String(color.id)"
                                                    >{{
                                                        color.code
                                                    }}</SelectItem
                                                >
                                            </SelectContent>
                                        </Select>
                                        <InputError
                                            :message="
                                                form.errors.interior_color_id
                                            "
                                        />
                                    </div>
                                    <div class="space-y-2">
                                        <Label>Matière intérieure</Label>
                                        <Select v-model="form.interior_type_id">
                                            <SelectTrigger
                                                ><SelectValue
                                                    placeholder="Sélectionnez"
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
                                            :message="
                                                form.errors.interior_type_id
                                            "
                                        />
                                    </div>
                                </div>
                            </div>

                            <!-- État Général -->
                            <div class="space-y-6">
                                <h3 class="border-b pb-2 font-medium">
                                    État et Historique
                                </h3>
                                <div
                                    class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-2"
                                >
                                    <div class="flex items-center space-x-3">
                                        <Checkbox
                                            id="is_damaged"
                                            :checked="form.is_damaged"
                                            @update:checked="
                                                (v: boolean) =>
                                                    (form.is_damaged = v)
                                            "
                                        />
                                        <Label
                                            for="is_damaged"
                                            class="cursor-pointer leading-tight"
                                            >Véhicule endommagé</Label
                                        >
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <Checkbox
                                            id="has_accident"
                                            :checked="form.has_accident"
                                            @update:checked="
                                                (v: boolean) =>
                                                    (form.has_accident = v)
                                            "
                                        />
                                        <Label
                                            for="has_accident"
                                            class="cursor-pointer leading-tight"
                                            >A déjà eu un accident</Label
                                        >
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <Checkbox
                                            id="complete_maintenance_book"
                                            :checked="
                                                form.complete_maintenance_book
                                            "
                                            @update:checked="
                                                (v: boolean) =>
                                                    (form.complete_maintenance_book =
                                                        v)
                                            "
                                        />
                                        <Label
                                            for="complete_maintenance_book"
                                            class="cursor-pointer leading-tight"
                                            >Carnet d'entretien complet</Label
                                        >
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <Checkbox
                                            id="non_smoker"
                                            :checked="form.non_smoker"
                                            @update:checked="
                                                (v: boolean) =>
                                                    (form.non_smoker = v)
                                            "
                                        />
                                        <Label
                                            for="non_smoker"
                                            class="cursor-pointer leading-tight"
                                            >Véhicule non-fumeur</Label
                                        >
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <Checkbox
                                            id="technical_inspection_status"
                                            :checked="
                                                form.technical_inspection_status
                                            "
                                            @update:checked="
                                                (v: boolean) =>
                                                    (form.technical_inspection_status =
                                                        v)
                                            "
                                        />
                                        <Label
                                            for="technical_inspection_status"
                                            class="cursor-pointer leading-tight"
                                            >Contrôle technique valide</Label
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Section 4 : Équipements -->
                <Card class="pt-0">
                    <CardHeader
                        class="dark rounded-t-xl bg-card py-6 text-card-foreground"
                    >
                        <CardTitle>Équipements & Options</CardTitle>
                        <CardDescription>
                            Sélectionnez les options disponibles sur ce
                            véhicule.
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div
                            class="grid grid-cols-1 gap-x-6 gap-y-3 sm:grid-cols-2 lg:grid-cols-3"
                        >
                            <div
                                v-for="feature in features"
                                :key="feature.id"
                                class="flex items-start space-x-3 rounded-md border p-3 shadow-sm transition-colors hover:bg-muted"
                            >
                                <Checkbox
                                    :id="`feature-${feature.id}`"
                                    :checked="
                                        form.features.includes(
                                            String(feature.id),
                                        )
                                    "
                                    @update:checked="
                                        (checked: boolean) =>
                                            toggleFeature(
                                                String(feature.id),
                                                checked,
                                            )
                                    "
                                />
                                <Label
                                    :for="`feature-${feature.id}`"
                                    class="flex-1 cursor-pointer text-sm leading-snug"
                                    >{{ feature.key }}</Label
                                >
                            </div>
                        </div>
                        <InputError
                            :message="form.errors.features"
                            class="mt-2"
                        />
                    </CardContent>
                </Card>

                <!-- Section 5 : Description -->
                <Card class="pt-0">
                    <CardHeader
                        class="dark rounded-t-xl bg-card py-6 text-card-foreground"
                    >
                        <CardTitle>Description détaillée</CardTitle>
                        <CardDescription>
                            Le texte de votre annonce.
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <Textarea
                            id="description"
                            v-model="form.description"
                            rows="6"
                            placeholder="Décrivez votre véhicule de la manière la plus complète possible..."
                        />
                        <InputError :message="form.errors.description" />
                    </CardContent>
                </Card>

                <!-- Actions Menu Sticky -->
                <div
                    class="sticky bottom-0 z-10 -mx-4 mt-8 -mb-4 flex items-center justify-end gap-3 border-t bg-background/95 px-4 py-4 shadow-sm backdrop-blur-sm md:-mx-8 md:-mb-8 md:px-8"
                >
                    <div class="flex w-full justify-end gap-3">
                        <Button
                            type="button"
                            variant="outline"
                            @click="() => router.visit(vehiclesIndex.url())"
                        >
                            Annuler
                        </Button>
                        <Button
                            type="button"
                            variant="secondary"
                            :disabled="form.processing"
                            @click.prevent="submit('draft')"
                        >
                            Sauvegarder en brouillon
                        </Button>
                        <Button type="submit" :disabled="form.processing">
                            Publier l'annonce
                        </Button>
                    </div>
                </div>
            </form>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import { Head, useForm, router } from '@inertiajs/vue3';
import axios from 'axios';
import { ref, watch } from 'vue';
import DatePicker from '@/components/DatePicker.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
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
import AppLayout from '@/layouts/AppLayout.vue';
import {
    index as vehiclesIndex,
    store as vehicleStore,
} from '@/routes/vehicles';

const props = defineProps<{
    brands: any[];
    fuelTypes: any[];
    bodyTypes: any[];
    transmissionTypes: any[];
    exteriorColors: any[];
    interiorColors: any[];
    interiorTypes: any[];
    euroNorms: any[];
    features: any[];
}>();

const form = useForm({
    status: 'draft',
    brand_id: '',
    model_id: '',
    vehicle_version_id: '',
    vehicle_version_name: '',
    price: '',
    mileage: '',
    first_registration_date: '',
    purchase_date: '',
    vin: '',
    registration_number: '',
    fuel_type_id: '',
    body_type_id: '',
    transmission_type_id: '',
    euro_norm_id: '',
    power_kw: '',
    engine_displacement: '',
    cylinder_count: '',
    fuel_consumption_avg: '',
    weight_kg: '',
    co2_emission: '',
    doors: '',
    seats: '',
    previous_owner: '',
    gear_count: '',
    exterior_color_id: '',
    exterior_color_metalised: false,
    interior_color_id: '',
    interior_type_id: '',
    is_damaged: false,
    has_accident: false,
    complete_maintenance_book: false,
    non_smoker: false,
    technical_inspection_status: false,
    description: '',
    features: [] as string[],
});

const models = ref<any[]>([]);
const versions = ref<any[]>([]);

const toggleFeature = (id: string, checked: boolean) => {
    if (checked) {
        if (!form.features.includes(id)) form.features.push(id);
    } else {
        form.features = form.features.filter((fId) => fId !== id);
    }
};

watch(
    () => form.brand_id,
    async (brandId) => {
        form.model_id = '';
        form.vehicle_version_id = '';
        models.value = [];
        versions.value = [];

        if (!brandId) return;
        const { data } = await axios.get('/vehicle-models', {
            params: { brand_id: brandId },
        });
        models.value = data;
    },
);

watch(
    () => form.model_id,
    async (modelId) => {
        form.vehicle_version_id = '';
        versions.value = [];

        if (!modelId) return;
        const { data } = await axios.get('/vehicle-versions', {
            params: { model_id: modelId },
        });
        versions.value = data;
    },
);

const submit = (status: 'active' | 'draft') => {
    form.status = status;
    form.transform((data: Record<string, any>) => {
        const transformed: Record<string, any> = {};
        for (const [key, value] of Object.entries(data)) {
            transformed[key] = value === '' ? null : value;
        }
        return transformed;
    }).post(vehicleStore.url());
};
</script>
