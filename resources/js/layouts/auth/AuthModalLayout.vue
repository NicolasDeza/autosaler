<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import type { InertiaLinkProps } from '@inertiajs/vue3';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { home } from '@/routes';

defineProps<{
    title?: string;
    description?: string;
    closeHref?: NonNullable<InertiaLinkProps['href']>;
}>();

const handleOpenChange = (open: boolean): void => {
    if (open) {
        return;
    }

    // Utiliser l'historique du navigateur empêche les boucles de redirection
    // si l'utilisateur a été redirigé vers /login depuis une route protégée.
    if (window.history.length > 1) {
        window.history.back();
    } else {
        router.visit(home(), {
            replace: true,
        });
    }
};
</script>

<template>
    <div class="min-h-svh bg-background">
        <Dialog :open="true" @update:open="handleOpenChange">
            <DialogContent class="sm:max-w-md">
                <DialogHeader class="items-center text-center">
                    <AppLogoIcon
                        class="mb-1 size-6 fill-current text-foreground sm:size-7 dark:text-white"
                    />
                    <DialogTitle class="text-xl font-medium">
                        {{ title }}
                    </DialogTitle>
                    <DialogDescription>
                        {{ description }}
                    </DialogDescription>
                </DialogHeader>

                <slot />
            </DialogContent>
        </Dialog>
    </div>
</template>
