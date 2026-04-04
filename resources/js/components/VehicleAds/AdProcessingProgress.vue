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
import { useTranslation } from '@/composables/useTranslation';
import { show as vehicleShow } from '@/routes/vehicles';

const props = defineProps<{
    vehicleId: number;
    mode?: 'create' | 'edit';
}>();

const emit = defineEmits<{
    (e: 'complete'): void;
    (e: 'close'): void;
}>();

const { __ } = useTranslation();

const progress = ref(0);
const ready = ref(false);
const error = ref(false);
const showModal = ref(true);
const isProcessing = ref(false);

const closeModal = () => {
    showModal.value = false;
    emit('close');
};

const fetchStatus = async () => {
    if (isProcessing.value || ready.value || error.value) return;

    try {
        const res = await axios.get(
            `/vehicles/${props.vehicleId}/images-status`,
        );
        progress.value = res.data.progress;
        ready.value = res.data.ready;

        if (ready.value) {
            emit('complete');

            // Refresh the page props to show the new images without full browser reload
            router.reload({
                only: ['images', 'ad', 'flash'],
            });
        } else if (res.data.next_media_id) {
            // Trigger manual processing for the next pending media
            await processMedia(res.data.next_media_id);
        } else {
            // If not ready but no next_media_id (shouldn't happen if logic is correct), 
            // wait a bit and retry
            setTimeout(fetchStatus, 2000);
        }
    } catch (e) {
        console.error('Error fetching image status:', e);
        error.value = true;
    }
};

const processMedia = async (mediaId: number) => {
    isProcessing.value = true;
    try {
        await axios.post(`/vehicles/media/${mediaId}/process`);
        isProcessing.value = false;
        // Check status again to get next media or finish
        await fetchStatus();
    } catch (e) {
        console.error('Error processing media:', e);
        error.value = true;
        isProcessing.value = false;
    }
};

onMounted(() => {
    fetchStatus();
});

onUnmounted(() => {
    // Cleanup if needed
});

// Premium design constants
const statusConfig = computed(() => {
    if (error.value) {
        return {
            label: __('vehicleAd.ad_processing.status_error'),
            variant: 'destructive' as const,
            icon: AlertCircle,
            message: __('vehicleAd.ad_processing.msg_error'),
            detail: __('vehicleAd.ad_processing.detail_error'),
            color: 'text-destructive',
            bg: 'bg-destructive/10',
        };
    }
    if (ready.value) {
        return {
            label: __('vehicleAd.ad_processing.status_success'),
            variant: 'secondary' as const,
            icon: CheckCircle2,
            message: __('vehicleAd.ad_processing.msg_success'),
            detail: __('vehicleAd.ad_processing.detail_success'),
            color: 'text-emerald-500',
            bg: 'bg-emerald-500/10',
        };
    }
    return {
        label: __('vehicleAd.ad_processing.status_optimizing'),
        variant: 'secondary' as const,
        icon: Loader2,
        message: __('vehicleAd.ad_processing.msg_optimizing'),
        detail: __('vehicleAd.ad_processing.detail_optimizing'),
        color: 'text-primary',
        bg: 'bg-primary/10',
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
                            {{
                                mode === 'edit'
                                    ? __('vehicleAd.ad_processing.title_edit')
                                    : __('vehicleAd.ad_processing.title_create')
                            }}
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
                            {{ __('vehicleAd.ad_processing.hint_background') }}
                        </span>
                        <span v-else-if="ready" class="text-[11px] text-muted-foreground font-semibold">
                            {{ __('vehicleAd.ad_processing.hint_finished') }}
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
                        {{ __('vehicleAd.ad_processing.action_continue') }}
                    </Button>
                    <Button 
                        type="button" 
                        class="h-14 rounded-2xl font-black text-[11px] uppercase tracking-widest bg-primary hover:bg-primary/90 text-primary-foreground shadow-xl shadow-primary/20 border-none active:scale-[0.98] transition-all cursor-pointer"
                        @click="router.visit(vehicleShow.url(vehicleId))"
                    >
                        <Eye class="mr-2 size-4" />
                        {{ __('vehicleAd.ad_processing.action_view_ad') }}
                    </Button>
                </div>

                <div v-if="error" class="animate-in fade-in duration-500 pt-2">
                    <Button 
                        variant="ghost" 
                        class="w-full text-[10px] font-black text-muted-foreground hover:text-foreground transition-all uppercase tracking-[0.2em] h-12 rounded-2xl cursor-pointer"
                        @click="closeModal"
                    >
                        {{ __('vehicleAd.ad_processing.action_skip') }}
                    </Button>
                </div>

                <div class="pt-4 flex flex-col items-center gap-1.5 opacity-40">
                    <div class="flex items-center gap-3">
                         <div class="h-px w-8 bg-muted-foreground/30"></div>
                         <p class="text-[9px] font-black uppercase tracking-[0.3em] text-muted-foreground">
                            {{ __('vehicleAd.ad_processing.footer_version') }}
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

