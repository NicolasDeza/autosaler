<script setup lang="ts">
import { Download } from 'lucide-vue-next';
import { toast } from 'vue-sonner';
import InstallApp from '@/components/InstallApp.vue';
import { Button } from '@/components/ui/button';
import {
    Tooltip,
    TooltipContent,
    TooltipProvider,
    TooltipTrigger,
} from '@/components/ui/tooltip';
import { useTranslation } from '@/composables/useTranslation';

const { __ } = useTranslation();

defineProps<{
    variant?:
        | 'default'
        | 'destructive'
        | 'outline'
        | 'secondary'
        | 'ghost'
        | 'link';
    size?: 'default' | 'sm' | 'lg' | 'icon';
    showTooltip?: boolean;
    class?: string;
}>();
</script>

<template>
    <InstallApp v-slot="{ install, canInstall, showIosInstallMessage }">
        <template v-if="canInstall || showIosInstallMessage">
            <TooltipProvider v-if="showTooltip" :delay-duration="0">
                <Tooltip>
                    <TooltipTrigger as-child>
                        <Button
                            :variant="variant || 'ghost'"
                            :size="size || 'icon'"
                            :class="[
                                'group h-9 w-9 cursor-pointer',
                                $props.class,
                            ]"
                            @click="
                                showIosInstallMessage
                                    ? toast.info(
                                          __('pwa.ios_guide_step_1') +
                                              '\n' +
                                              __('pwa.ios_guide_step_2'),
                                      )
                                    : install()
                            "
                        >
                            <Download
                                class="size-5 opacity-80 group-hover:opacity-100"
                            />
                            <span class="sr-only">{{ __('pwa.tooltip') }}</span>
                        </Button>
                    </TooltipTrigger>
                    <TooltipContent class="dark border border-background/50">
                        <p>{{ __('pwa.tooltip') }}</p>
                    </TooltipContent>
                </Tooltip>
            </TooltipProvider>

            <Button
                v-else
                :variant="variant || 'outline'"
                :size="size || 'icon'"
                :class="['group cursor-pointer', $props.class]"
                @click="
                    showIosInstallMessage
                        ? toast.info(
                              __('pwa.ios_guide_step_1') +
                                  '\n' +
                                  __('pwa.ios_guide_step_2'),
                          )
                        : install()
                "
            >
                <Download class="size-5 opacity-80 group-hover:opacity-100" />
                <slot>
                    <span class="sr-only">{{ __('pwa.tooltip') }}</span>
                </slot>
            </Button>
        </template>
    </InstallApp>
</template>
