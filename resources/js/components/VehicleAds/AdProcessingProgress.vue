<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import axios from 'axios';
import { Loader2, CheckCircle2, AlertCircle, X, Eye } from 'lucide-vue-next';
import { ref, onMounted, onUnmounted } from 'vue';
import { Button } from '@/components/ui/button';
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
</script>

<template>
    <div
        v-if="showModal"
        class="fixed inset-0 z-100 flex items-center justify-center bg-black/40 p-4 backdrop-blur-sm animate-in fade-in duration-300"
    >
        <div
            class="relative w-full max-w-md space-y-6 rounded-3xl border border-white/40 bg-white/90 p-8 shadow-2xl backdrop-blur-xl animate-in zoom-in-95 duration-300 ease-out"
        >
            <!-- Bouton de fermeture manuel (visible seulement en cas d'erreur ou de succès) -->
            <button
                v-if="error || ready"
                type="button"
                @click="closeModal"
                class="absolute right-4 top-4 rounded-full p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition-colors"
            >
                <X class="h-5 w-5" />
            </button>

            <div class="space-y-2 text-center">
                <h3 class="leading-tight text-xl font-bold text-gray-900">
                    {{ mode === 'edit' ? 'Modification' : 'Création' }} de l'annonce
                </h3>
                <p class="text-sm font-medium text-gray-500">
                    {{ error ? "Une erreur est survenue lors du suivi." : 'Veuillez patienter pendant le traitement des images...' }}
                </p>
            </div>

            <div class="relative pt-2">
                <div class="mb-2 flex items-center justify-between">
                    <span
                        class="inline-block rounded-full px-2.5 py-1 text-xs font-semibold uppercase"
                        :class="
                            error
                                ? 'bg-red-100 text-red-600'
                                : 'bg-indigo-100 text-indigo-600'
                        "
                    >
                        {{ error ? 'Erreur' : ready ? 'Terminé' : 'En cours' }}
                    </span>
                    <span
                        class="inline-block text-xs font-bold"
                        :class="error ? 'text-red-600' : 'text-indigo-600'"
                    >
                        {{ progress }}%
                    </span>
                </div>
                <div
                    class="h-3 w-full overflow-hidden rounded-full border border-gray-100 bg-gray-100 text-xs shadow-inner"
                >
                    <div
                        :style="{ width: progress + '%' }"
                        class="flex h-full flex-col justify-center whitespace-nowrap text-center text-white shadow-sm transition-all duration-1000 ease-out"
                        :class="
                            error
                                ? 'bg-red-500'
                                : 'bg-linear-to-r from-indigo-500 to-violet-500'
                        "
                    ></div>
                </div>
            </div>

            <div class="flex items-center justify-center gap-3 py-2">
                <div
                    v-if="!ready && !error"
                    class="flex items-center gap-2 rounded-2xl border border-indigo-100 bg-indigo-50 px-4 py-2.5"
                >
                    <Loader2 class="h-4 w-4 animate-spin text-indigo-600" />
                    <span class="text-xs font-medium text-indigo-700"
                        >Optimisation des visuels...</span
                    >
                </div>
                <div
                    v-else-if="ready"
                    class="flex items-center gap-2 rounded-2xl border border-emerald-100 bg-emerald-50 px-4 py-2.5"
                >
                    <CheckCircle2 class="h-4 w-4 text-emerald-600" />
                    <span class="text-xs font-medium text-emerald-700"
                        >C'est prêt !</span
                    >
                </div>
                <div
                    v-else-if="error"
                    class="flex items-center gap-2 rounded-2xl border border-red-100 bg-red-50 px-4 py-2.5"
                >
                    <AlertCircle class="h-4 w-4 text-red-600" />
                    <span class="text-xs font-medium text-red-700"
                        >Échec du suivi en temps réel</span
                    >
                </div>
            </div>

            <!-- Actions de succès -->
            <div v-if="ready" class="grid grid-cols-2 gap-3 mt-2 animate-in fade-in slide-in-from-bottom-2 duration-500">
                <Button type="button" @click="closeModal" variant="outline" class="rounded-2xl py-6 hover:cursor-pointer">
                    OK
                </Button>
                <Button type="button" @click="router.visit(vehicleShow.url(vehicleId))" class="rounded-2xl py-6 hover:cursor-pointer">
                    <Eye class="mr-2 h-4 w-4" />
                    Voir l'annonce
                </Button>
            </div>

            <div v-if="error" class="text-center">
                <button
                    type="button"
                    @click="closeModal"
                    class="text-xs font-bold text-gray-400 hover:text-gray-600 underline underline-offset-4 decoration-current transition-colors"
                >
                    Ignorer et continuer
                </button>
            </div>

            <p
                class="text-center text-[10px] font-bold uppercase tracking-widest text-gray-400"
            >
                Autosaler Media Engine
            </p>
        </div>
    </div>
</template>
