<template>
    <Head :title="`${ad.brand?.name} ${ad.model?.name}`" />

    <AppLayout>
        <div class="mx-auto w-full max-w-7xl space-y-8 p-4 md:p-8">
            <button
                class="flex cursor-pointer items-center gap-1.5 text-sm text-muted-foreground transition-colors hover:text-foreground"
                @click="router.visit(vehiclesIndex.url())"
            >
                <ChevronLeft class="h-4 w-4" />
                Retour aux annonces
            </button>

            <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
                <div class="col-span-1 space-y-8 md:col-span-2">
                    <div
                        class="space-y-8 rounded-lg border bg-card p-4 shadow-sm sm:p-6"
                    >
                        <div
                            class="flex flex-col gap-4 p-1 sm:flex-row sm:items-start sm:justify-between sm:p-2"
                        >
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <h1 class="text-2xl font-bold sm:text-3xl">
                                        {{ ad.brand?.name }}
                                        {{ ad.model?.name }}
                                    </h1>
                                    <h2
                                        class="mt-1 text-sm font-bold tracking-wider text-muted-foreground/80 uppercase"
                                    >
                                        {{ ad.vehicle_version_name }}
                                    </h2>
                                </div>
                                <Button
                                    variant="ghost"
                                    size="icon"
                                    class="size-10 shrink-0 cursor-pointer rounded-md border border-border bg-background transition-all hover:border-primary hover:bg-primary/10 hover:text-primary"
                                    :class="{
                                        'border-primary/20 fill-primary text-primary':
                                            ad.is_favorited,
                                    }"
                                    @click="toggleFavorite"
                                >
                                    <Star
                                        class="size-5"
                                        :class="{
                                            'fill-primary': ad.is_favorited,
                                        }"
                                    />
                                </Button>
                            </div>
                            <div class="flex flex-col sm:items-end">
                                <div
                                    class="inline-flex self-start bg-primary py-1 pr-4 pl-6 text-white shadow-sm [clip-path:polygon(10%_0,100%_0,100%_100%,0_100%)] sm:self-auto sm:py-1.5 sm:pr-5 sm:pl-7"
                                >
                                    <div class="flex items-end gap-2">
                                        <span
                                            class="text-lg font-black tracking-tight sm:text-2xl"
                                        >
                                            {{ Number(ad.price).toLocaleString('fr-FR') }} €
                                        </span>
                                        <span
                                            class="pb-0.5 text-[9px] font-semibold tracking-[0.18em] text-white/80 uppercase sm:text-[10px]"
                                        >
                                            TVAC
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <div
                                class="aspect-video w-full rounded-lg bg-muted"
                            ></div>
                            <div class="flex gap-3 overflow-x-auto">
                                <div
                                    v-for="i in 6"
                                    :key="i"
                                    class="h-16 w-24 shrink-0 rounded bg-muted/60"
                                ></div>
                            </div>
                        </div>

                        <div class="p-2 sm:p-0">
                            <div
                                class="grid grid-cols-2 gap-y-6 sm:grid-cols-3"
                            >
                                <div class="flex flex-col gap-1.5">
                                    <div class="flex items-center gap-1.5">
                                        <Gauge
                                            class="h-3.5 w-3.5 text-primary"
                                        />
                                        <span
                                            class="text-xs font-bold text-muted-foreground uppercase"
                                            >Kilométrage</span
                                        >
                                    </div>
                                    <span class="font-semibold"
                                        >{{
                                            ad.mileage.toLocaleString()
                                        }}
                                        km</span
                                    >
                                </div>
                                <div class="flex flex-col gap-1.5">
                                    <div class="flex items-center gap-1.5">
                                        <Calendar
                                            class="h-3.5 w-3.5 text-primary"
                                        />
                                        <span
                                            class="text-xs font-bold text-muted-foreground uppercase"
                                            >1ère immatriculation</span
                                        >
                                    </div>
                                    <span class="font-semibold">{{
                                        new Date(
                                            ad.first_registration_date,
                                        ).toLocaleDateString()
                                    }}</span>
                                </div>
                                <div
                                    class="flex flex-col gap-1.5"
                                    v-if="ad.power_kw"
                                >
                                    <div class="flex items-center gap-1.5">
                                        <Zap class="h-3.5 w-3.5 text-primary" />
                                        <span
                                            class="text-xs font-bold text-muted-foreground uppercase"
                                            >Puissance</span
                                        >
                                    </div>
                                    <span class="font-semibold"
                                        >{{ ad.power_kw }} kW</span
                                    >
                                </div>
                                <div
                                    class="flex flex-col gap-1.5"
                                    v-if="ad.fuel_type"
                                >
                                    <div class="flex items-center gap-1.5">
                                        <Fuel
                                            class="h-3.5 w-3.5 text-primary"
                                        />
                                        <span
                                            class="text-xs font-bold text-muted-foreground uppercase"
                                            >Carburant</span
                                        >
                                    </div>
                                    <span class="font-semibold">{{
                                        ad.fuel_type?.code
                                    }}</span>
                                </div>
                                <div
                                    class="flex flex-col gap-1.5"
                                    v-if="ad.transmission_type"
                                >
                                    <div class="flex items-center gap-1.5">
                                        <Cog class="h-3.5 w-3.5 text-primary" />
                                        <span
                                            class="text-xs font-bold text-muted-foreground uppercase"
                                            >Boîte de vitesse</span
                                        >
                                    </div>
                                    <span class="font-semibold">{{
                                        ad.transmission_type?.code
                                    }}</span>
                                </div>
                                <div
                                    class="flex flex-col gap-1.5"
                                    v-if="ad.body_type"
                                >
                                    <div class="flex items-center gap-1.5">
                                        <Truck
                                            class="h-3.5 w-3.5 text-primary"
                                        />
                                        <span
                                            class="text-xs font-bold text-muted-foreground uppercase"
                                            >Carrosserie</span
                                        >
                                    </div>
                                    <span class="font-semibold">{{
                                        ad.body_type?.code
                                    }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional details -->
                    <div class="rounded-lg border bg-card p-6 shadow-sm">
                        <h3 class="mb-6 text-lg font-bold">
                            Informations techniques & Esthétique
                        </h3>
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div class="text-sm">
                                <div
                                    class="flex items-center justify-between border-b py-2.5"
                                >
                                    <span class="text-muted-foreground"
                                        >Cylindrée</span
                                    >
                                    <span class="font-medium"
                                        >{{
                                            ad.engine_displacement ?? '-'
                                        }}
                                        cm³</span
                                    >
                                </div>
                                <div
                                    class="flex items-center justify-between border-b py-2.5"
                                >
                                    <span class="text-muted-foreground"
                                        >Cylindres</span
                                    >
                                    <span class="font-medium">{{
                                        ad.cylinder_count ?? '-'
                                    }}</span>
                                </div>
                                <div
                                    class="flex items-center justify-between border-b py-2.5"
                                >
                                    <span class="text-muted-foreground"
                                        >Vitesses</span
                                    >
                                    <span class="font-medium">{{
                                        ad.gear_count ?? '-'
                                    }}</span>
                                </div>
                                <div
                                    class="flex items-center justify-between border-b py-2.5"
                                >
                                    <span class="text-muted-foreground"
                                        >Poids</span
                                    >
                                    <span class="font-medium"
                                        >{{ ad.weight_kg ?? '-' }} kg</span
                                    >
                                </div>
                                <div
                                    class="flex items-center justify-between py-2.5"
                                >
                                    <span class="text-muted-foreground"
                                        >Portes / Sièges</span
                                    >
                                    <span class="font-medium"
                                        >{{ ad.doors ?? '-' }} /
                                        {{ ad.seats ?? '-' }}</span
                                    >
                                </div>
                            </div>
                            <div class="text-sm">
                                <div
                                    class="flex items-center justify-between border-b py-2.5"
                                >
                                    <span class="text-muted-foreground"
                                        >Norme Euro</span
                                    >
                                    <span class="font-medium">{{
                                        ad.euro_norm?.code ?? '-'
                                    }}</span>
                                </div>
                                <div
                                    class="flex items-center justify-between border-b py-2.5"
                                >
                                    <span class="text-muted-foreground"
                                        >CO2 / Conso</span
                                    >
                                    <span class="font-medium"
                                        >{{ ad.co2_emission ?? '-' }} g/km /
                                        {{ ad.fuel_consumption_avg ?? '-' }}
                                        L</span
                                    >
                                </div>
                                <div
                                    class="flex items-center justify-between border-b py-2.5"
                                >
                                    <span class="text-muted-foreground"
                                        >Couleur ext.</span
                                    >
                                    <span class="font-medium"
                                        >{{ ad.exterior_color?.code ?? '-'
                                        }}<span
                                            v-if="ad.exterior_color_metalised"
                                        >
                                            (Métallisé)</span
                                        ></span
                                    >
                                </div>
                                <div
                                    class="flex items-center justify-between border-b py-2.5"
                                >
                                    <span class="text-muted-foreground"
                                        >Intérieur</span
                                    >
                                    <span class="font-medium"
                                        >{{ ad.interior_color?.code ?? '-' }},
                                        {{
                                            ad.interior_type?.code ?? '-'
                                        }}</span
                                    >
                                </div>
                                <div
                                    class="flex items-center justify-between py-2.5"
                                >
                                    <span class="text-muted-foreground"
                                        >Propriétaires préc.</span
                                    >
                                    <span class="font-medium">{{
                                        ad.previous_owner ?? '-'
                                    }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- State & History -->
                    <div class="rounded-lg border bg-card p-6 shadow-sm">
                        <h3 class="mb-6 text-lg font-bold">
                            État & Historique
                        </h3>
                        <div class="flex flex-wrap gap-2">
                            <span
                                class="inline-flex items-center gap-1.5 rounded-full border px-3 py-1 text-xs font-semibold"
                                :class="
                                    ad.is_damaged
                                        ? 'border-red-200 bg-red-50 text-red-600 dark:border-red-800 dark:bg-red-950/40 dark:text-red-400'
                                        : 'border-green-200 bg-green-50 text-green-700 dark:border-green-800 dark:bg-green-950/40 dark:text-green-400'
                                "
                            >
                                <component
                                    :is="
                                        ad.is_damaged
                                            ? AlertTriangle
                                            : CheckCircle
                                    "
                                    class="h-3.5 w-3.5"
                                />
                                Endommagé: {{ ad.is_damaged ? 'Oui' : 'Non' }}
                            </span>
                            <span
                                class="inline-flex items-center gap-1.5 rounded-full border px-3 py-1 text-xs font-semibold"
                                :class="
                                    ad.has_accident
                                        ? 'border-red-200 bg-red-50 text-red-600 dark:border-red-800 dark:bg-red-950/40 dark:text-red-400'
                                        : 'border-green-200 bg-green-50 text-green-700 dark:border-green-800 dark:bg-green-950/40 dark:text-green-400'
                                "
                            >
                                <component
                                    :is="
                                        ad.has_accident
                                            ? AlertTriangle
                                            : CheckCircle
                                    "
                                    class="h-3.5 w-3.5"
                                />
                                Accident: {{ ad.has_accident ? 'Oui' : 'Non' }}
                            </span>
                            <span
                                class="inline-flex items-center gap-1.5 rounded-full border px-3 py-1 text-xs font-semibold"
                                :class="
                                    ad.complete_maintenance_book
                                        ? 'border-green-200 bg-green-50 text-green-700 dark:border-green-800 dark:bg-green-950/40 dark:text-green-400'
                                        : 'border-border bg-muted/50 text-muted-foreground/50'
                                "
                            >
                                <CheckCircle class="h-3.5 w-3.5" /> Carnet
                                complet
                            </span>
                            <span
                                class="inline-flex items-center gap-1.5 rounded-full border px-3 py-1 text-xs font-semibold"
                                :class="
                                    ad.non_smoker
                                        ? 'border-green-200 bg-green-50 text-green-700 dark:border-green-800 dark:bg-green-950/40 dark:text-green-400'
                                        : 'border-border bg-muted/50 text-muted-foreground/50'
                                "
                            >
                                <CheckCircle class="h-3.5 w-3.5" /> Non-fumeur
                            </span>
                            <span
                                class="inline-flex items-center gap-1.5 rounded-full border px-3 py-1 text-xs font-semibold"
                                :class="
                                    ad.technical_inspection_status
                                        ? 'border-green-200 bg-green-50 text-green-700 dark:border-green-800 dark:bg-green-950/40 dark:text-green-400'
                                        : 'border-border bg-muted/50 text-muted-foreground/50'
                                "
                            >
                                <CheckCircle class="h-3.5 w-3.5" /> CT valide
                            </span>
                        </div>
                    </div>

                    <div
                        v-if="ad.features?.length"
                        class="rounded-lg border bg-card p-6 shadow-sm"
                    >
                        <h3 class="mb-6 text-lg font-bold">
                            Equipements & options
                        </h3>
                        <div class="space-y-6">
                            <div
                                v-for="category in groupedFeatures"
                                :key="category.id"
                                class="space-y-3"
                            >
                                <h4
                                    class="text-sm font-semibold tracking-wide text-muted-foreground uppercase"
                                >
                                    {{ formatOptionLabel(category.code) }}
                                </h4>
                                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                                    <div
                                        v-for="feature in category.features"
                                        :key="feature.id"
                                        class="flex items-start gap-2.5 text-sm text-foreground"
                                    >
                                        <CheckCircle
                                            class="mt-0.5 h-4 w-4 shrink-0 text-green-600"
                                        />
                                        <span>{{
                                            formatOptionLabel(
                                                feature.code ?? feature.key,
                                            )
                                        }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        v-if="ad.description"
                        class="rounded-lg border bg-card p-6 shadow-sm"
                    >
                        <h3 class="mb-4 text-lg font-bold">Description</h3>
                        <p class="whitespace-pre-wrap text-foreground">
                            {{ ad.description }}
                        </p>
                    </div>
                </div>

                <!-- Contact Sidebar -->
                <div
                    class="col-span-1 space-y-4 md:sticky md:top-24 md:self-start"
                >
                    <Card
                        class="overflow-hidden border border-border bg-card p-0 shadow-sm"
                    >
                        <div
                            class="border-b border-primary/20 bg-[hsl(216,27%,15%)] px-6 py-5"
                        >
                            <div class="flex items-start justify-between gap-4">
                                <div class="space-y-3">
                                    <span
                                        class="inline-flex items-center rounded-full border border-white/15 bg-white/10 px-3 py-1 text-[11px] font-bold tracking-[0.22em] text-white/80 uppercase"
                                    >
                                        Vendeur
                                    </span>
                                    <div class="space-y-1">
                                        <div
                                            class="flex items-center gap-2 text-lg font-black tracking-tight text-white"
                                        >
                                            <span>
                                                {{
                                                    ad.user?.company?.name ??
                                                    ad.user?.first_name +
                                                        ' ' +
                                                        ad.user?.last_name
                                                }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-5 p-6 text-sm">
                            <div
                                v-if="ad.user?.company"
                                class="flex items-start gap-3"
                            >
                                <MapPin
                                    class="mt-0.5 h-4 w-4 shrink-0 text-muted-foreground"
                                />
                                <div
                                    class="space-y-1 leading-relaxed text-foreground"
                                >
                                    <p class="font-medium text-foreground">
                                        Adresse du garage
                                    </p>
                                    <p>{{ ad.user.company.address }}</p>
                                    <p>
                                        {{ ad.user.company.city?.zip_code }}
                                        {{ ad.user.company.city?.code }}
                                    </p>
                                    <p>
                                        {{ ad.user.company.country?.code }}
                                    </p>
                                </div>
                            </div>

                            <div
                                v-if="
                                    ad.user?.company?.phone ||
                                    ad.user?.company?.email
                                "
                                class="space-y-3"
                            >
                                <a
                                    v-if="ad.user.company.phone"
                                    :href="`tel:${ad.user.company.phone}`"
                                    class="group flex items-start gap-3 text-foreground transition-colors hover:text-foreground/80"
                                >
                                    <Phone
                                        class="mt-0.5 h-4 w-4 shrink-0 text-muted-foreground transition-colors group-hover:text-foreground"
                                    />
                                    <div class="space-y-1 leading-relaxed">
                                        <p
                                            class="text-xs font-bold tracking-wide text-muted-foreground uppercase"
                                        >
                                            Téléphone
                                        </p>
                                        <p
                                            class="font-semibold text-foreground"
                                        >
                                            {{ ad.user.company.phone }}
                                        </p>
                                    </div>
                                </a>

                                <a
                                    v-if="ad.user.company.email"
                                    :href="`mailto:${ad.user.company.email}`"
                                    class="group flex items-start gap-3 text-foreground transition-colors hover:text-foreground/80"
                                >
                                    <Mail
                                        class="mt-0.5 h-4 w-4 shrink-0 text-muted-foreground transition-colors group-hover:text-foreground"
                                    />
                                    <div
                                        class="min-w-0 space-y-1 leading-relaxed"
                                    >
                                        <p
                                            class="text-xs font-bold tracking-wide text-muted-foreground uppercase"
                                        >
                                            Email
                                        </p>
                                        <p
                                            class="truncate font-semibold text-foreground"
                                        >
                                            {{ ad.user.company.email }}
                                        </p>
                                    </div>
                                </a>
                            </div>

                            <div class="space-y-3 border-t border-border pt-5">
                                <Button
                                    class="h-11 w-full cursor-pointer rounded font-bold shadow-sm"
                                >
                                    Contacter le vendeur
                                </Button>
                                <Button
                                    v-if="ad.user?.company?.phone"
                                    as-child
                                    class="h-11 w-full cursor-pointer rounded border border-green-600 bg-white px-4 text-sm font-bold text-green-600 shadow-none transition-all duration-200 hover:bg-green-50 hover:text-green-600"
                                >
                                    <a
                                        :href="`https://wa.me/${ad.user.company.phone?.replace(/\D/g, '')}`"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                    >
                                        <svg
                                            viewBox="0 0 24 24"
                                            class="h-4 w-4 fill-current"
                                            xmlns="http://www.w3.org/2000/svg"
                                        >
                                            <path
                                                d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"
                                            />
                                        </svg>
                                        WhatsApp
                                    </a>
                                </Button>
                            </div>
                        </div>
                    </Card>

                    <Card v-if="canEdit" class="p-6">
                        <h3
                            class="mb-4 text-sm font-bold text-muted-foreground uppercase"
                        >
                            Actions
                        </h3>
                        <div class="flex flex-col gap-2">
                            <Button
                                variant="outline"
                                class="flex w-full cursor-pointer justify-start gap-2"
                                @click="
                                    () => router.visit(vehicleEdit.url(ad.id))
                                "
                            >
                                <Edit class="h-4 w-4" /> Modifier l'annonce
                            </Button>
                        </div>
                    </Card>
                </div>
            </div>
        </div>
    </AppLayout>

    <LoginRequiredModal
        v-model:open="showLoginModal"
        title="Ajouter aux favoris"
        description="Connectez-vous pour enregistrer ce véhicule et être informé de toute baisse de prix."
    />
</template>

<script setup lang="ts">
import { Head, router, usePage } from '@inertiajs/vue3';
import {
    CheckCircle,
    AlertTriangle,
    Edit,
    MapPin,
    Phone,
    Mail,
    Gauge,
    Calendar,
    Zap,
    Fuel,
    Cog,
    Truck,
    ChevronLeft,
    Star,
} from 'lucide-vue-next';
import { computed, ref } from 'vue';
import LoginRequiredModal from '@/components/Auth/LoginRequiredModal.vue';
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import {
    edit as vehicleEdit,
    index as vehiclesIndex,
    favorite as vehicleFavorite,
} from '@/routes/vehicles';

const props = defineProps<{
    ad: any;
    canEdit: boolean;
}>();

type FeatureCategory = {
    id?: number | string;
    code?: string;
    key?: string;
    sort_order?: number | null;
};

type FeatureOption = {
    id: number | string;
    code?: string;
    key?: string;
    sort_order?: number | null;
    category?: FeatureCategory | null;
};

type GroupedFeature = {
    id: string;
    code: string;
    sortOrder: number;
    features: FeatureOption[];
};

const showLoginModal = ref(false);
const page = usePage();

const formatOptionLabel = (value?: string): string => {
    if (!value) {
        return '';
    }

    return value.replace(/[_-]+/g, ' ').trim();
};

const groupedFeatures = computed<GroupedFeature[]>(() => {
    const groups = new Map<string, GroupedFeature>();
    const features: FeatureOption[] = Array.isArray(props.ad?.features)
        ? props.ad.features
        : [];

    for (const feature of features) {
        const categoryCode = feature.category?.code ?? feature.category?.key ?? 'autres';
        const categoryId = String(feature.category?.id ?? categoryCode);
        const existingGroup = groups.get(categoryId);

        if (existingGroup) {
            existingGroup.features.push(feature);
            continue;
        }

        groups.set(categoryId, {
            id: categoryId,
            code: categoryCode,
            sortOrder: feature.category?.sort_order ?? Number.MAX_SAFE_INTEGER,
            features: [feature],
        });
    }

    return [...groups.values()]
        .sort((a, b) => {
            if (a.sortOrder !== b.sortOrder) {
                return a.sortOrder - b.sortOrder;
            }

            return a.code.localeCompare(b.code, 'fr');
        })
        .map((group) => ({
            ...group,
            features: [...group.features].sort((a, b) => {
                const left = a.code ?? a.key ?? '';
                const right = b.code ?? b.key ?? '';

                return left.localeCompare(right, 'fr');
            }),
        }));
});

const toggleFavorite = () => {
    if (!page.props.auth?.user) {
        showLoginModal.value = true;
        return;
    }
    router.post(
        vehicleFavorite.url({ vehicleAd: props.ad.id }),
        {},
        { preserveScroll: true },
    );
};
</script>
