<template>
    <Head title="Dealer Dashboard" />

    <AppLayout>
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-6"
        >
            <div class="mb-4 flex items-center justify-between">
                <h1 class="text-3xl font-bold tracking-tight">Mon stock</h1>
                <div class="flex items-center gap-2">
                    <Button variant="outline" size="sm">
                        <Download class="mr-2 h-4 w-4" /> Enregistrer en CSV
                    </Button>
                    <Button variant="outline" size="sm">
                        <Printer class="mr-2 h-4 w-4" /> Liste imprimée
                    </Button>
                    <Button as-child size="sm">
                        <Link :href="vehicleCreate.url()">
                            <Plus class="mr-2 h-4 w-4" />
                            Créer une annonce
                        </Link>
                    </Button>
                </div>
            </div>

            <Tabs v-model="activeTab" class="w-full">
                <TabsList class="mb-4">
                    <TabsTrigger value="overview"> Dashboard </TabsTrigger>
                    <TabsTrigger value="vehicles">
                        Liste des véhicules
                    </TabsTrigger>
                </TabsList>
                <TabsContent value="overview" class="space-y-4">
                    <DealerOverviewTab :ads="ads" />
                </TabsContent>
                <TabsContent value="vehicles" class="space-y-4">
                    <DealerVehiclesTab :ads="ads" />
                </TabsContent>
            </Tabs>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import { ref, watch, onMounted } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { Button } from '@/components/ui/button';
import { Download, Printer, Plus } from 'lucide-vue-next';
import { create as vehicleCreate } from '@/routes/vehicles';
import DealerOverviewTab from '@/components/dealer/DealerOverviewTab.vue';
import DealerVehiclesTab from '@/components/dealer/DealerVehiclesTab.vue';

interface Props {
    ads: any;
}

const props = defineProps<Props>();

const activeTab = ref('overview');

onMounted(() => {
    const savedTab = localStorage.getItem('dealerDashboardTab');
    if (savedTab === 'overview' || savedTab === 'vehicles') {
        activeTab.value = savedTab;
    }
});

watch(activeTab, (newTab) => {
    localStorage.setItem('dealerDashboardTab', newTab);
});
</script>
