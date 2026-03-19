import { ref } from 'vue';

interface BeforeInstallPromptEvent extends Event {
    prompt: () => Promise<void>;
    userChoice: Promise<{
        outcome: 'accepted' | 'dismissed';
        platform: string;
    }>;
}

const deferredPrompt = ref<BeforeInstallPromptEvent | null>(null);
const canInstall = ref(false);
const isPWA = ref(false);
const isIOS = /iphone|ipad|ipod/i.test(navigator.userAgent);

// Register the listener once at the module level (browser only)
if (typeof window !== 'undefined' && !(window as any)._pwaListenerAdded) {
    (window as any)._pwaListenerAdded = true;

    isPWA.value =
        window.matchMedia('(display-mode: standalone)').matches ||
        (window.navigator as any).standalone === true;

    window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault();
        deferredPrompt.value = e as BeforeInstallPromptEvent;

        canInstall.value = true;
    });

    window.addEventListener('appinstalled', () => {
        canInstall.value = false;
        deferredPrompt.value = null;
    });
}

export function usePwaInstall() {
    const install = async () => {
        if (!deferredPrompt.value) return;

        try {
            await deferredPrompt.value.prompt();
            const { outcome } = await deferredPrompt.value.userChoice;

            if (outcome === 'accepted') {
                canInstall.value = false;
                deferredPrompt.value = null;
            }
        } catch (e) {
            console.error('[PWA] Install error', e);
        }
    };

    return {
        canInstall,
        install,
        isPWA,
        isIOS,
    };
}
