<script setup lang="ts">
import { Eye, Star, MessageCircle, Car } from 'lucide-vue-next';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';

interface Props {
    ads: any; // We'll type this properly if possible, or leave as any for now
}

const props = defineProps<Props>();

// Computed metrics could go here based on props.ads or injected global stats
// For now, we use placeholders or simple aggregations from the current page
const totalAds = props.ads?.total || 0;
const totalViews =
    props.ads?.data?.reduce(
        (acc: number, ad: any) => acc + (ad.stat?.views_count || 0),
        0,
    ) || 0;
const totalContacts =
    props.ads?.data?.reduce(
        (acc: number, ad: any) => acc + (ad.stat?.contact_count || 0),
        0,
    ) || 0;
const totalFavs =
    props.ads?.data?.reduce(
        (acc: number, ad: any) => acc + (ad.stat?.fav_count || 0),
        0,
    ) || 0;
</script>

<template>
    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
        <Card>
            <CardHeader
                class="flex flex-row items-center justify-between space-y-0 pb-2"
            >
                <CardTitle class="text-sm font-medium">
                    Véhicules en ligne
                </CardTitle>
                <Car class="h-4 w-4 text-muted-foreground" />
            </CardHeader>
            <CardContent>
                <div class="text-2xl font-bold">{{ totalAds }}</div>
                <p class="text-xs text-muted-foreground">
                    Total de vos annonces enregistrées
                </p>
            </CardContent>
        </Card>

        <Card>
            <CardHeader
                class="flex flex-row items-center justify-between space-y-0 pb-2"
            >
                <CardTitle class="text-sm font-medium">
                    Vues Totales
                </CardTitle>
                <Eye class="h-4 w-4 text-muted-foreground" />
            </CardHeader>
            <CardContent>
                <div class="text-2xl font-bold">{{ totalViews }}</div>
                <p class="text-xs text-muted-foreground">
                    Sur l'ensemble de vos véhicules
                </p>
            </CardContent>
        </Card>

        <Card>
            <CardHeader
                class="flex flex-row items-center justify-between space-y-0 pb-2"
            >
                <CardTitle class="text-sm font-medium"> Favoris </CardTitle>
                <Star class="h-4 w-4 text-muted-foreground" />
            </CardHeader>
            <CardContent>
                <div class="text-2xl font-bold">{{ totalFavs }}</div>
                <p class="text-xs text-muted-foreground">
                    Mises en favoris par les utilisateurs
                </p>
            </CardContent>
        </Card>

        <Card>
            <CardHeader
                class="flex flex-row items-center justify-between space-y-0 pb-2"
            >
                <CardTitle class="text-sm font-medium">
                    Contacts générés
                </CardTitle>
                <MessageCircle class="h-4 w-4 text-muted-foreground" />
            </CardHeader>
            <CardContent>
                <div class="text-2xl font-bold">{{ totalContacts }}</div>
                <p class="text-xs text-muted-foreground">
                    Prises de contact pour vos offres
                </p>
            </CardContent>
        </Card>
    </div>

    <div class="mt-4 grid gap-4 lg:grid-cols-2">
        <!-- Chart placeholders -->
        <Card
            class="flex min-h-[300px] items-center justify-center text-muted-foreground"
        >
            Graphique des Vues (à implémenter)
        </Card>
        <Card
            class="flex min-h-[300px] items-center justify-center text-muted-foreground"
        >
            Graphique des Contacts (à implémenter)
        </Card>
    </div>
</template>
