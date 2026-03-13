<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { LogIn, UserPlus, Star } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { login, register } from '@/routes';

defineProps<{
    open: boolean;
    title?: string;
    description?: string;
}>();

const emit = defineEmits(['update:open']);

const handleLogin = () => {
    router.visit(login.url());
};

const handleRegister = () => {
    router.visit(register.url());
};
</script>

<template>
    <Dialog :open="open" @update:open="emit('update:open', $event)">
        <DialogContent
            class="gap-0 overflow-hidden border-none bg-card p-0 shadow-2xl sm:max-w-[400px]"
        >
            <div
                class="relative flex h-32 w-full items-center justify-center overflow-hidden bg-primary/10"
            >
                <div
                    class="absolute inset-0 bg-[radial-gradient(circle_at_center,var(--tw-gradient-stops))] from-primary via-transparent to-transparent opacity-10"
                ></div>
                <!-- Decorative stars -->
                <Star
                    class="absolute top-4 left-8 h-4 w-4 rotate-12 text-primary/20"
                />
                <Star
                    class="absolute right-10 bottom-6 h-3 w-3 -rotate-12 text-primary/30"
                />
                <Star
                    class="absolute top-10 right-16 h-5 w-5 rotate-45 text-primary/10"
                />

                <div
                    class="relative z-10 flex h-16 w-16 items-center justify-center rounded-2xl bg-primary shadow-lg ring-4 shadow-primary/20 ring-background"
                >
                    <Star class="h-8 w-8 fill-white text-white" />
                </div>
            </div>

            <div class="p-8 pb-4">
                <DialogHeader class="space-y-3">
                    <DialogTitle
                        class="text-center text-2xl font-black tracking-tight text-foreground uppercase"
                    >
                        {{ title || 'Connexion requise' }}
                    </DialogTitle>
                    <DialogDescription
                        class="text-center text-base leading-relaxed text-muted-foreground"
                    >
                        {{
                            description ||
                            'Vous devez être connecté pour utiliser cette fonctionnalité et sauvegarder vos annonces favorites.'
                        }}
                    </DialogDescription>
                </DialogHeader>

                <div class="mt-8 flex flex-col gap-3">
                    <Button
                        @click="handleLogin"
                        class="h-14 w-full gap-3 text-base font-bold transition-all duration-300 hover:scale-[1.02] active:scale-[0.98]"
                    >
                        <LogIn class="h-5 w-5" />
                        Se connecter
                    </Button>
                    <Button
                        @click="handleRegister"
                        variant="outline"
                        class="h-14 w-full gap-3 border-2 text-base font-bold transition-all duration-300 hover:scale-[1.02] active:scale-[0.98]"
                    >
                        <UserPlus class="h-5 w-5" />
                        Créer un compte
                    </Button>
                </div>
            </div>

            <DialogFooter class="bg-muted/30 p-6 pt-2 sm:justify-center">
                <button
                    @click="emit('update:open', false)"
                    class="text-sm font-bold text-muted-foreground/60 transition-colors hover:text-foreground active:scale-95"
                >
                    Peut-être plus tard
                </button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
