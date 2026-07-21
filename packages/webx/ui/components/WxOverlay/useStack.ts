import { ref } from 'vue';

const activeOverlays = ref<string[]>([]);

export function useStack() {
    const registerOverlay = (id: string) => {
        activeOverlays.value.push(id);
    };

    const unregisterOverlay = (id: string) => {
        activeOverlays.value = activeOverlays.value.filter(d => d !== id);
    };

    const closeLast = () => {
        return activeOverlays.value[activeOverlays.value.length - 1];
    };

    return { registerOverlay, unregisterOverlay, closeLast, activeOverlays };
}
