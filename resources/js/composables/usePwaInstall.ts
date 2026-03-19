import { ref } from 'vue';

interface BeforeInstallPromptEvent extends Event {
    prompt: () => Promise<void>;
    userChoice: Promise<{ outcome: 'accepted' | 'dismissed'; platform: string }>;
}

const deferredPrompt = ref<BeforeInstallPromptEvent | null>(null);
const canInstall = ref(false);

// Register the listener once at the module level (browser only)
if (typeof window !== 'undefined') {
    console.log('[PWA] Registering beforeinstallprompt listener');
    window.addEventListener('beforeinstallprompt', (e) => {
        console.log('[PWA] beforeinstallprompt event caught');
        // Prevent default browser prompt
        e.preventDefault();
        // Stash the event so it can be triggered later
        deferredPrompt.value = e as BeforeInstallPromptEvent;
        canInstall.value = true;
    });

    window.addEventListener('appinstalled', () => {
        console.log('[PWA] App installed event caught');
        // Hide the install button as the app is already installed
        canInstall.value = false;
        deferredPrompt.value = null;
    });
}

export function usePwaInstall() {
    const install = async () => {
        if (!deferredPrompt.value) {
            return;
        }

        // Show the install prompt
        await deferredPrompt.value.prompt();

        // Wait for the user to respond to the prompt
        const { outcome } = await deferredPrompt.value.userChoice;

        if (outcome === 'accepted') {
            // If accepted, we clear the prompt and hide the trigger
            canInstall.value = false;
            deferredPrompt.value = null;
        }
    };

    return {
        canInstall,
        install,
    };
}
