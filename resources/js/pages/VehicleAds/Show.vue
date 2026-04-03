<script setup lang="ts">
import { Head, router, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

import AppContent from '@/components/AppContent.vue';
import LoginRequiredModal from '@/components/Auth/LoginRequiredModal.vue';
import DealerContactModal from '@/components/VehicleAds/DealerContactModal.vue';
import DealerContactCard from '@/components/VehicleAds/Details/DealerContactCard.vue';
import DealerContactSticky from '@/components/VehicleAds/Details/DealerContactSticky.vue';
import VehicleConditionBadges from '@/components/VehicleAds/Details/VehicleConditionBadges.vue';
import VehicleDescription from '@/components/VehicleAds/Details/VehicleDescription.vue';
import VehicleDetailsHeader from '@/components/VehicleAds/Details/VehicleDetailsHeader.vue';
import VehicleFeaturesList from '@/components/VehicleAds/Details/VehicleFeaturesList.vue';
import VehicleQuickStats from '@/components/VehicleAds/Details/VehicleQuickStats.vue';
import VehicleTechnicalSpec from '@/components/VehicleAds/Details/VehicleTechnicalSpec.vue';
import VehicleGallery from '@/components/VehicleAds/VehicleGallery.vue';

import { useComparison } from '@/composables/useComparison';
import { useTranslation } from '@/composables/useTranslation';
import { useVehicleAdFieldTranslation } from '@/composables/useVehicleAdFieldTranslation';

import {
    edit as vehicleEdit,
    index as vehiclesIndex,
    favorite as vehicleFavorite,
} from '@/routes/vehicles';

const props = defineProps<{
    ad: any;
}>();

const { __ } = useTranslation();
const { translateVehicleAdField } = useVehicleAdFieldTranslation();
const page = usePage();
const { addVehicle, removeVehicle, isSelected } = useComparison();

const showContactModal = ref(false);
const showLoginModal = ref(false);

const canEdit = computed(() => {
    return page.props.auth.user?.id === props.ad.user_id;
});

const toggleComparison = () => {
    if (isSelected(props.ad.id)) {
        removeVehicle(props.ad.id);
    } else {
        addVehicle({
            id: props.ad.id,
            brand: props.ad.brand?.name,
            model: props.ad.model?.name,
            vehicle_version_name:
                props.ad.vehicle_version_name || props.ad.vehicle_version?.name,
            price: Number(props.ad.price),
            image: props.ad.primary_image?.thumb,
        });
    }
};

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

const groupedFeatures = computed(() => {
    if (!props.ad.features) {
        return [];
    }
    const groups = new Map();
    const features = Array.isArray(props.ad.features) ? props.ad.features : [];

    for (const feature of features) {
        const categoryCode =
            feature.category?.code ?? feature.category?.key ?? 'other';
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

const formatOptionLabel = (
    value?: string,
    group: 'feature_categories' | 'features' = 'features',
) => translateVehicleAdField(group, value);

const openContactModal = () => {
    showContactModal.value = true;
};
</script>

<template>
    <Head :title="`${ad.brand?.name} ${ad.model?.name}`" />

    <AppContent>
        <div class="mx-auto w-full space-y-8 py-4 pb-32 lg:py-8 lg:pb-8">
            <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
                <div class="col-span-1 space-y-8 lg:col-span-2">
                    <div
                        class="space-y-8 rounded-lg border bg-card p-4 shadow-sm sm:p-6"
                    >
                        <VehicleDetailsHeader
                            :ad="ad"
                            :is-selected="isSelected"
                            @back="router.visit(vehiclesIndex.url())"
                            @toggle-favorite="toggleFavorite"
                            @toggle-comparison="toggleComparison"
                        />

                        <VehicleGallery
                            :images="ad.gallery"
                            :brand-name="ad.brand?.name"
                            :model-name="ad.model?.name"
                        />

                        <VehicleQuickStats :ad="ad" />
                    </div>

                    <VehicleTechnicalSpec :ad="ad" />

                    <VehicleConditionBadges :ad="ad" />

                    <VehicleFeaturesList
                        v-if="ad.features?.length"
                        :grouped-features="groupedFeatures"
                        :format-option-label="formatOptionLabel"
                    />

                    <VehicleDescription
                        v-if="ad.description"
                        :description="ad.description"
                    />
                </div>

                <!-- Contact Sidebar (Desktop) -->
                <DealerContactCard
                    :ad="ad"
                    :can-edit="canEdit"
                    :vehicle-edit="vehicleEdit"
                    @contact="openContactModal"
                />
            </div>
        </div>

        <template #sticky-bottom>
            <!-- Mobile Sticky Contact Bar -->
            <DealerContactSticky :ad="ad" @contact="openContactModal" />
        </template>
    </AppContent>

    <DealerContactModal
        v-model:open="showContactModal"
        :vehicle-ad-id="ad.id"
    />

    <LoginRequiredModal
        v-model:open="showLoginModal"
        :title="__('vehicleAd.favorites.add')"
        :description="__('vehicleAd.favorites.login_required')"
    />
</template>
