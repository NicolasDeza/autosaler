import { useTranslation } from '@/composables/useTranslation';

type VehicleAdFieldGroup =
    | 'feature_categories'
    | 'features'
    | 'fuel_types'
    | 'transmission_types'
    | 'body_types'
    | 'euro_norms'
    | 'exterior_colors'
    | 'interior_colors'
    | 'interior_types';

export function useVehicleAdFieldTranslation() {
    const { __ } = useTranslation();

    const translateVehicleAdField = (
        group: VehicleAdFieldGroup,
        value?: string | null,
    ): string => {
        if (!value) {
            return '';
        }

        const translationKey = `vehicleAdFields.${group}.${value}`;
        const translated = __(translationKey);

        if (translated !== translationKey) {
            return translated;
        }

        return value.replace(/[_-]+/g, ' ').trim();
    };

    return { translateVehicleAdField };
}
