import type { PageProps as InertiaPageProps } from '@inertiajs/core';

export interface ExtendedPageProps extends InertiaPageProps {
    locale?: string;
}
