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
const showIOSInstallMessage = ref(false);

// Listener global (module-level)
if (typeof window !== 'undefined' && !(window as any)._pwaListenerAdded) {
    (window as any)._pwaListenerAdded = true;

    // Détecte si la PWA est déjà installée
    isPWA.value =
        window.matchMedia('(display-mode: standalone)').matches ||
        (window.navigator as any).standalone === true;

    // Android / Chrome
    window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault(); // empêche le banner automatique
        deferredPrompt.value = e as BeforeInstallPromptEvent;
        canInstall.value = true;
        showIOSInstallMessage.value = false; // on masque le message iOS
    });

    // Après installation
    window.addEventListener('appinstalled', () => {
        canInstall.value = false;
        deferredPrompt.value = null;
        showIOSInstallMessage.value = false;
    });

    // Si c'est iOS et non PWA, on montre le message "Ajouter à l'écran d'accueil"
    if (isIOS && !isPWA.value) {
        showIOSInstallMessage.value = true;
    }
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
        showIOSInstallMessage,
    };
}
