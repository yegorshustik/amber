// packages/webx/ui/composables/useEscapeClose.ts
import { onUnmounted, watch } from 'vue';

type UseEscapeCloseOptions = {
    enabled: () => boolean;
    persistent?: () => boolean;
    onClose: () => void;
};

export function useEscapeClose(options: UseEscapeCloseOptions) {
    const onKeyDown = (e: KeyboardEvent) => {
        if (e.key !== 'Escape') return;
        if (!options.enabled()) return;
        if (options.persistent?.()) return;

        options.onClose();
    };

    const add = () => document.addEventListener('keydown', onKeyDown);
    const remove = () => document.removeEventListener('keydown', onKeyDown);

    watch(
        () => options.enabled(),
        (isEnabled) => {
            if (isEnabled) add();
            else remove();
        },
        { immediate: true },
    );

    onUnmounted(() => remove());
}
