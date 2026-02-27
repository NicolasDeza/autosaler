import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

export function usePermissions() {
    const page = usePage();

    const permissions = computed(() => page.props.auth.user?.permissions ?? []);
    const roles = computed(() => page.props.auth.user?.roles ?? []);

    /**
     * Check if the authenticated user has a specific permission.
     * @param name - The name of the permission to check.
     */
    const can = (name: string): boolean => {
        return permissions.value.includes(name);
    };

    /**
     * Check if the authenticated user has a specific role.
     * @param name - The name of the role to check.
     */
    const hasRole = (name: string): boolean => {
        return roles.value.includes(name);
    };

    /**
     * Check if the authenticated user has any of the specified permissions.
     * @param names - An array of permission names to check.
     */
    const hasAnyPermission = (names: string[]): boolean => {
        return names.some((name) => permissions.value.includes(name));
    };

    return { can, hasAnyPermission, hasRole };
}
