<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import axios from 'axios';
import { Loader2, CheckCircle2, AlertCircle, Eye } from 'lucide-vue-next';
import { ref, onMounted, onUnmounted, computed } from 'vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
    DialogDescription,
} from '@/components/ui/dialog';
import { show as vehicleShow } from '@/routes/vehicles';

const props = defineProps<{
    vehicleId: number;
    mode?: 'create' | 'edit';
}>();

const emit = defineEmits<{
    (e: 'complete'): void;
    (e: 'close'): void;
}>();

const progress = ref(0);
const ready = ref(false);
const error = ref(false);
const showModal = ref(true);
let interval: any = null;

const closeModal = () => {
    showModal.value = false;
    emit('close');
};

const fetchStatus = async () => {
    try {
        const res = await axios.get(
            `/vehicles/${props.vehicleId}/images-status`,
        );
        progress.value = res.data.progress;
        ready.value = res.data.ready;

        if (ready.value) {
            clearInterval(interval);
            emit('complete');

            // Refresh the page props to show the new images without full browser reload
            router.reload({
                only: ['images', 'ad', 'flash'],
            });
        }
    } catch (e) {
        console.error('Error fetching image status:', e);
        error.value = true;
        clearInterval(interval);
    }
};

onMounted(() => {
    fetchStatus();
    interval = setInterval(fetchStatus, 3000);
});

onUnmounted(() => {
    if (interval) clearInterval(interval);
});

// Premium design constants
const statusConfig = computed(() => {
    if (error.value) {
        return {
            label: 'Erreur',
            variant: 'destructive' as const,
            icon: AlertCircle,
            message: "Une erreur est survenue lors de l'optimisation.",
            detail: "Échec du suivi en temps réel",
            color: 'text-destructive',
            bg: 'bg-destructive/10'
        };
    }
    if (ready.value) {
        return {
            label: 'Succès',
            variant: 'secondary' as const,
            icon: CheckCircle2,
            message: 'Images traitées avec succès !',
            detail: "C'est prêt !",
            color: 'text-emerald-500',
            bg: 'bg-emerald-500/10'
        };
    }
    return {
        label: 'Optimisation',
        variant: 'secondary' as const,
        icon: Loader2,
        message: 'Veuillez patienter pendant le traitement des images...',
        detail: 'Optimisation des visuels...',
        color: 'text-primary',
        bg: 'bg-primary/10'
    };
});
</script>

<template>
    <Dialog :open="showModal" @update:open="(val) => !val && (error || ready) && closeModal()">
        <DialogContent 
            class="sm:max-w-md border border-white/20 bg-background/80 backdrop-blur-xl shadow-2xl p-0 overflow-hidden"
            :show-close-button="error || ready"
        >
            <!-- Premium Header with animated background -->
            <div class="relative h-1.5 bg-muted/30 overflow-hidden">
                <div 
                    class="absolute inset-0 bg-primary transition-all duration-1000 ease-out"
                    :style="{ width: progress + '%', opacity: error ? 0.3 : 1 }"
                    :class="[
                        error ? 'bg-destructive' : 'animate-shimmer bg-linear-to-r from-primary/80 via-primary to-primary/80'
                    ]"
                ></div>
            </div>

            <div class="p-8 space-y-8">
                <DialogHeader class="space-y-4">
                    <div class="flex items-center justify-between">
                         <Badge 
                            :variant="statusConfig.variant"
                            class="px-3 py-1 rounded-full text-[10px] font-black tracking-widest uppercase transition-all duration-500"
                            :class="[
                                ready ? 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20' : 
                                error ? 'bg-destructive text-destructive-foreground' : 
                                'bg-primary/10 text-primary border-primary/20'
                            ]"
                        >
                            {{ statusConfig.label }}
                        </Badge>
                        <span class="text-sm font-black tabular-nums tracking-tighter" :class="statusConfig.color">
                            {{ progress }}%
                        </span>
                    </div>
                    
                    <div class="space-y-2">
                        <DialogTitle class="text-3xl font-black tracking-tight text-foreground leading-none">
                            {{ mode === 'edit' ? 'Mise à jour' : 'Publication' }}
                        </DialogTitle>
                        <DialogDescription class="text-muted-foreground font-medium text-base/relaxed max-w-[90%]">
                            {{ statusConfig.message }}
                        </DialogDescription>
                    </div>
                </DialogHeader>

                <!-- Process indicator -->
                <div class="relative py-2">
                    <div class="h-4 w-full bg-muted/30 rounded-full overflow-hidden border border-border/40 p-1">
                         <div 
                            class="h-full rounded-full transition-all duration-1000 ease-out shadow-[0_0_15px_rgba(var(--primary),0.2)]"
                            :style="{ width: progress + '%' }"
                            :class="[
                                error ? 'bg-destructive' : 'bg-primary'
                            ]"
                        ></div>
                    </div>
                </div>

                <!-- Detail info card -->
                <div 
                    class="flex items-center gap-4 p-5 rounded-3xl border transition-all duration-700"
                    :class="[
                        error ? 'bg-destructive/5 border-destructive/10' : 
                        ready ? 'bg-emerald-500/5 border-emerald-500/10' : 
                        'bg-primary/5 border-primary/10'
                    ]"
                >
                    <div 
                        class="p-3 rounded-2xl transition-all duration-500 shrink-0"
                        :class="statusConfig.bg"
                    >
                        <component 
                            :is="statusConfig.icon" 
                            class="size-5" 
                            :class="[
                                statusConfig.color,
                                !ready && !error && 'animate-spin'
                            ]"
                        />
                    </div>
                    <div class="flex flex-col gap-0.5">
                        <span class="text-sm font-bold tracking-tight text-foreground">
                            {{ statusConfig.detail }}
                        </span>
                        <span v-if="!ready && !error" class="text-[11px] text-muted-foreground font-semibold">
                            Traitement en arrière-plan par le moteur IA
                        </span>
                        <span v-else-if="ready" class="text-[11px] text-muted-foreground font-semibold">
                            Tous les visuels sont optimisés
                        </span>
                    </div>
                </div>

                <!-- Footer Actions -->
                <div v-if="ready" class="grid grid-cols-2 gap-4 animate-in fade-in slide-in-from-bottom-4 duration-1000">
                    <Button 
                        type="button" 
                        variant="secondary" 
                        class="h-14 rounded-2xl font-black text-[11px] uppercase tracking-widest bg-muted/50 hover:bg-muted text-foreground border-none active:scale-[0.98] transition-all cursor-pointer"
                        @click="closeModal"
                    >
                        OK
                    </Button>
                    <Button 
                        type="button" 
                        class="h-14 rounded-2xl font-black text-[11px] uppercase tracking-widest bg-primary hover:bg-primary/90 text-primary-foreground shadow-xl shadow-primary/20 border-none active:scale-[0.98] transition-all cursor-pointer"
                        @click="router.visit(vehicleShow.url(vehicleId))"
                    >
                        <Eye class="mr-2 size-4" />
                        Voir l'annonce
                    </Button>
                </div>

                <div v-if="error" class="animate-in fade-in duration-500 pt-2">
                    <Button 
                        variant="ghost" 
                        class="w-full text-[10px] font-black text-muted-foreground hover:text-foreground transition-all uppercase tracking-[0.2em] h-12 rounded-2xl cursor-pointer"
                        @click="closeModal"
                    >
                        Continuer sans attendre
                    </Button>
                </div>

                <div class="pt-4 flex flex-col items-center gap-1.5 opacity-40">
                    <div class="flex items-center gap-3">
                         <div class="h-px w-8 bg-muted-foreground/30"></div>
                         <p class="text-[9px] font-black uppercase tracking-[0.3em] text-muted-foreground">
                            Media Engine V2.1
                        </p>
                        <div class="h-px w-8 bg-muted-foreground/30"></div>
                    </div>
                </div>
            </div>
        </DialogContent>
    </Dialog>
</template>

<style scoped>
@keyframes shimmer {
  0% { transform: translateX(-100%); }
  100% { transform: translateX(100%); }
}

.animate-shimmer {
  animation: shimmer 2.5s infinite linear;
}
</style>

