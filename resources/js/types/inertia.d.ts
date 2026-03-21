import type { PageProps as InertiaPageProps } from '@inertiajs/core';

export interface ExtendedPageProps extends InertiaPageProps {
    locale?: string;
    canRegister?: boolean;
    flash?: {
        success?: string | null;
        error?: string | null;
        processing_images?: boolean | null;
        created?: boolean | null;
        dealerRegistrationSuccess?: boolean;
    };
    auth?: {
        user: any;
    };
}
