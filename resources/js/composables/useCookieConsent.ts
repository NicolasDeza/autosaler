import { computed, reactive } from 'vue';

export type CookieConsentState = {
    decided: boolean;
    analytics: boolean;
    marketing: boolean;
};

const STORAGE_KEY = 'cookie_consent';

function loadFromStorage(): CookieConsentState {
    try {
        const raw = localStorage.getItem(STORAGE_KEY);
        if (raw) {
            return JSON.parse(raw) as CookieConsentState;
        }
    } catch {
        // ignore
    }
    return { decided: false, analytics: false, marketing: false };
}

const state = reactive<CookieConsentState>(loadFromStorage());

function persist() {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(state));
}

export function useCookieConsent() {
    const hasDecided = computed(() => state.decided);
    const analyticsAllowed = computed(() => state.analytics);
    const marketingAllowed = computed(() => state.marketing);

    function acceptAll() {
        state.decided = true;
        state.analytics = true;
        state.marketing = true;
        persist();
    }

    function rejectAll() {
        state.decided = true;
        state.analytics = false;
        state.marketing = false;
        persist();
    }

    function saveCustom(analytics: boolean, marketing: boolean) {
        state.decided = true;
        state.analytics = analytics;
        state.marketing = marketing;
        persist();
    }

    function reset() {
        state.decided = false;
        state.analytics = false;
        state.marketing = false;
        localStorage.removeItem(STORAGE_KEY);
    }

    return {
        hasDecided,
        analyticsAllowed,
        marketingAllowed,
        acceptAll,
        rejectAll,
        saveCustom,
        reset,
    };
}
