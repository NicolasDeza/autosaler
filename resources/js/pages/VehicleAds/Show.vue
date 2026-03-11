<template>
    <Head :title="`${ad.brand?.name} ${ad.model?.name}`" />

    <AppLayout>
        <div class="mx-auto w-full max-w-5xl space-y-8 p-4 md:p-8">
            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-3xl font-bold">
                        {{ ad.brand?.name }} {{ ad.model?.name }}
                    </h1>
                    <!-- <h2
                        v-if="ad.vehicle_version"
                        class="mt-1 text-xl font-bold text-gray-700 uppercase"
                    >
                        {{ ad.vehicle_version.name }}
                    </h2> -->
                    <h2 class="mt-1 text-xl font-bold text-gray-700 uppercase">
                        {{ ad.vehicle_version_name }}
                    </h2>
                </div>
                <div class="text-3xl font-bold text-blue-600">
                    € {{ ad.price }}
                </div>
            </div>

            <!-- Image Gallery Mock -->
            <div class="space-y-4">
                <div class="aspect-video w-full rounded-lg bg-slate-300"></div>
                <div class="flex gap-4 overflow-x-auto">
                    <div
                        v-for="i in 6"
                        :key="i"
                        class="h-20 w-32 shrink-0 rounded bg-slate-200"
                    ></div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
                <div class="col-span-1 space-y-8 md:col-span-2">
                    <!-- Characteristics Grid -->
                    <div class="rounded-lg border bg-white p-6 shadow-sm">
                        <h3 class="mb-6 border-b pb-2 text-xl font-bold">
                            Caractéristiques principales
                        </h3>
                        <div class="grid grid-cols-2 gap-y-6 sm:grid-cols-3">
                            <div class="flex flex-col gap-1">
                                <span
                                    class="text-xs font-bold text-gray-500 uppercase"
                                    >Kilométrage</span
                                >
                                <span class="font-semibold"
                                    >{{ ad.mileage.toLocaleString() }} km</span
                                >
                            </div>
                            <div class="flex flex-col gap-1">
                                <span
                                    class="text-xs font-bold text-gray-500 uppercase"
                                    >1ère immatriculation</span
                                >
                                <span class="font-semibold">{{
                                    new Date(
                                        ad.first_registration_date,
                                    ).toLocaleDateString()
                                }}</span>
                            </div>
                            <div class="flex flex-col gap-1" v-if="ad.power_kw">
                                <span
                                    class="text-xs font-bold text-gray-500 uppercase"
                                    >Puissance</span
                                >
                                <span class="font-semibold"
                                    >{{ ad.power_kw }} kW</span
                                >
                            </div>
                            <div
                                class="flex flex-col gap-1"
                                v-if="ad.fuel_type"
                            >
                                <span
                                    class="text-xs font-bold text-gray-500 uppercase"
                                    >Carburant</span
                                >
                                <span class="font-semibold">{{
                                    ad.fuel_type?.code
                                }}</span>
                            </div>
                            <div
                                class="flex flex-col gap-1"
                                v-if="ad.transmission_type"
                            >
                                <span
                                    class="text-xs font-bold text-gray-500 uppercase"
                                    >Boîte de vitesse</span
                                >
                                <span class="font-semibold">{{
                                    ad.transmission_type?.code
                                }}</span>
                            </div>
                            <div
                                class="flex flex-col gap-1"
                                v-if="ad.body_type"
                            >
                                <span
                                    class="text-xs font-bold text-gray-500 uppercase"
                                    >Carrosserie</span
                                >
                                <span class="font-semibold">{{
                                    ad.body_type?.code
                                }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Additional details -->
                    <div class="rounded-lg border bg-white p-6 shadow-sm">
                        <h3 class="mb-6 border-b pb-2 text-lg font-bold">
                            Informations techniques & Esthétique
                        </h3>
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <table class="w-full text-sm">
                                <tbody>
                                    <tr class="border-b">
                                        <td class="py-2 text-gray-500">
                                            Cylindrée
                                        </td>
                                        <td class="py-2 text-right font-medium">
                                            {{ ad.engine_displacement ?? '-' }}
                                            cm³
                                        </td>
                                    </tr>
                                    <tr class="border-b">
                                        <td class="py-2 text-gray-500">
                                            Cylindres
                                        </td>
                                        <td class="py-2 text-right font-medium">
                                            {{ ad.cylinder_count ?? '-' }}
                                        </td>
                                    </tr>
                                    <tr class="border-b">
                                        <td class="py-2 text-gray-500">
                                            Vitesses
                                        </td>
                                        <td class="py-2 text-right font-medium">
                                            {{ ad.gear_count ?? '-' }}
                                        </td>
                                    </tr>
                                    <tr class="border-b">
                                        <td class="py-2 text-gray-500">
                                            Poids
                                        </td>
                                        <td class="py-2 text-right font-medium">
                                            {{ ad.weight_kg ?? '-' }} kg
                                        </td>
                                    </tr>
                                    <tr class="border-b">
                                        <td class="py-2 text-gray-500">
                                            Portes / Sièges
                                        </td>
                                        <td class="py-2 text-right font-medium">
                                            {{ ad.doors ?? '-' }} /
                                            {{ ad.seats ?? '-' }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <table class="w-full text-sm">
                                <tbody>
                                    <tr class="border-b">
                                        <td class="py-2 text-gray-500">
                                            Norme Euro
                                        </td>
                                        <td class="py-2 text-right font-medium">
                                            {{ ad.euro_norm?.code ?? '-' }}
                                        </td>
                                    </tr>
                                    <tr class="border-b">
                                        <td class="py-2 text-gray-500">
                                            CO2 / Conso
                                        </td>
                                        <td class="py-2 text-right font-medium">
                                            {{ ad.co2_emission ?? '-' }} g/km /
                                            {{ ad.fuel_consumption_avg ?? '-' }}
                                            L
                                        </td>
                                    </tr>
                                    <tr class="border-b">
                                        <td class="py-2 text-gray-500">
                                            Couleur ext.
                                        </td>
                                        <td class="py-2 text-right font-medium">
                                            {{ ad.exterior_color?.code ?? '-' }}
                                            <span
                                                v-if="
                                                    ad.exterior_color_metalised
                                                "
                                                >(Métallisé)</span
                                            >
                                        </td>
                                    </tr>
                                    <tr class="border-b">
                                        <td class="py-2 text-gray-500">
                                            Intérieur
                                        </td>
                                        <td class="py-2 text-right font-medium">
                                            {{
                                                ad.interior_color?.code ?? '-'
                                            }},
                                            {{ ad.interior_type?.code ?? '-' }}
                                        </td>
                                    </tr>
                                    <tr class="border-b">
                                        <td class="py-2 text-gray-500">
                                            Propriétaires préc.
                                        </td>
                                        <td class="py-2 text-right font-medium">
                                            {{ ad.previous_owner ?? '-' }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- State & History -->
                    <div class="rounded-lg border bg-white p-6 shadow-sm">
                        <h3 class="mb-6 border-b pb-2 text-lg font-bold">
                            État & Historique
                        </h3>
                        <div class="grid grid-cols-2 gap-y-3 sm:grid-cols-3">
                            <div
                                class="flex items-center gap-2 text-sm"
                                :class="
                                    ad.is_damaged
                                        ? 'text-red-500'
                                        : 'text-green-600'
                                "
                            >
                                <component
                                    :is="
                                        ad.is_damaged
                                            ? AlertTriangle
                                            : CheckCircle
                                    "
                                    class="h-4 w-4"
                                />
                                Endommagé: {{ ad.is_damaged ? 'Oui' : 'Non' }}
                            </div>
                            <div
                                class="flex items-center gap-2 text-sm"
                                :class="
                                    ad.has_accident
                                        ? 'text-red-500'
                                        : 'text-green-600'
                                "
                            >
                                <component
                                    :is="
                                        ad.has_accident
                                            ? AlertTriangle
                                            : CheckCircle
                                    "
                                    class="h-4 w-4"
                                />
                                Accident: {{ ad.has_accident ? 'Oui' : 'Non' }}
                            </div>
                            <div
                                class="flex items-center gap-2 text-sm"
                                :class="
                                    ad.complete_maintenance_book
                                        ? 'text-green-600'
                                        : 'text-gray-400'
                                "
                            >
                                <CheckCircle class="h-4 w-4" /> Carnet complet
                            </div>
                            <div
                                class="flex items-center gap-2 text-sm"
                                :class="
                                    ad.non_smoker
                                        ? 'text-green-600'
                                        : 'text-gray-400'
                                "
                            >
                                <CheckCircle class="h-4 w-4" /> Non-fumeur
                            </div>
                            <div
                                class="flex items-center gap-2 text-sm"
                                :class="
                                    ad.technical_inspection_status
                                        ? 'text-green-600'
                                        : 'text-gray-400'
                                "
                            >
                                <CheckCircle class="h-4 w-4" /> CT valide
                            </div>
                        </div>
                    </div>

                    <div
                        v-if="ad.description"
                        class="rounded-lg border bg-white p-6 shadow-sm"
                    >
                        <h3 class="mb-4 text-lg font-bold">Description</h3>
                        <p class="whitespace-pre-wrap text-gray-700">
                            {{ ad.description }}
                        </p>
                    </div>
                </div>

                <!-- Contact Sidebar -->
                <div class="col-span-1 space-y-4">
                    <Card class="p-6">
                        <h3 class="mb-6 text-xl font-bold">Vendeur</h3>
                        <div class="space-y-4 text-sm">
                            <div
                                class="flex items-center gap-2 text-lg font-bold"
                            >
                                <Building2 class="h-5 w-5 text-gray-400" />
                                {{
                                    ad.user?.company?.name ??
                                    ad.user?.first_name +
                                        ' ' +
                                        ad.user?.last_name
                                }}
                            </div>
                            <div
                                v-if="ad.user?.company"
                                class="flex items-start gap-2 text-gray-600"
                            >
                                <MapPin
                                    class="mt-1 h-4 w-4 shrink-0 text-gray-400"
                                />
                                <div>
                                    {{ ad.user.company.address }}<br />
                                    {{ ad.user.company.city?.zip_code }}
                                    {{ ad.user.company.city?.code }}<br />
                                    {{ ad.user.company.country?.code }}
                                </div>
                            </div>
                            <div
                                v-if="
                                    ad.user?.company?.phone ||
                                    ad.user?.company?.email
                                "
                                class="mt-2 space-y-2 text-gray-600"
                            >
                                <div
                                    v-if="ad.user.company.phone"
                                    class="flex items-center gap-2"
                                >
                                    <Phone
                                        class="h-4 w-4 shrink-0 text-gray-400"
                                    />
                                    {{ ad.user.company.phone }}
                                </div>
                                <div
                                    v-if="ad.user.company.email"
                                    class="flex items-center gap-2"
                                >
                                    <Mail
                                        class="h-4 w-4 shrink-0 text-gray-400"
                                    />
                                    {{ ad.user.company.email }}
                                </div>
                            </div>
                            <Button class="w-full font-bold"
                                >Contacter le vendeur</Button
                            >
                        </div>
                    </Card>

                    <Card v-if="$page.props.auth?.user" class="p-6">
                        <h3
                            class="mb-4 text-sm font-bold text-gray-500 uppercase"
                        >
                            Actions
                        </h3>
                        <div class="flex flex-col gap-2">
                            <Button
                                variant="outline"
                                class="flex w-full justify-start gap-2"
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
</template>

<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import {
    CheckCircle,
    AlertTriangle,
    Edit,
    Building2,
    MapPin,
    Phone,
    Mail,
} from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import { edit as vehicleEdit } from '@/routes/vehicles';

defineProps<{
    ad: any;
}>();
</script>
