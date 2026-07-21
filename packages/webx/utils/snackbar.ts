import { h, reactive, render } from 'vue';
import type { WxAlertType } from '../ui/components/WxAlert';
import WxSnackbarHost from '../ui/components/WxSnackbarHost';
import type { WxSnackbarItem } from '../ui/components/WxSnackbarHost';

export type WxSnackbarOptions = {
    type?: WxAlertType;
    timeoutMs?: number;
};

type SnackbarInternalItem = WxSnackbarItem & {
    timerId?: number;
    resolveClosed?: () => void;
};

const state = reactive<{ items: SnackbarInternalItem[] }>({ items: [] });

let hostContainer: HTMLDivElement | null = null;
let hostMounted = false;

function ensureHostMounted() {
    if (hostMounted) return;

    hostContainer = document.createElement('div');
    document.body.appendChild(hostContainer);

    const closeById = (id: string) => {
        const idx = state.items.findIndex((x) => x.id === id);
        if (idx === -1) return;

        const item = state.items[idx];
        if (item.timerId) window.clearTimeout(item.timerId);

        state.items.splice(idx, 1);
        item.resolveClosed?.();
    };

    render(
        h(WxSnackbarHost as any, {
            items: state.items,
            onClose: closeById,
        }),
        hostContainer,
    );

    hostMounted = true;
}

function makeId() {
    return `wx-snackbar-${Date.now()}-${Math.random().toString(16).slice(2)}`;
}

/**
 * Shows floating snackbar at bottom-center.
 * Returns a promise that resolves when snackbar is closed (timeout or click).
 */
export function wxSnackbar(message: string, options: WxSnackbarOptions = {}): Promise<void> {
    ensureHostMounted();

    const id = makeId();
    const type = options.type ?? 'info';
    const timeoutMs = options.timeoutMs ?? 3000;

    return new Promise<void>((resolve) => {
        const item: SnackbarInternalItem = {
            id,
            message,
            type,
            timeoutMs,
            resolveClosed: resolve,
        };

        state.items.push(item);

        if (timeoutMs > 0) {
            item.timerId = window.setTimeout(() => {
                // close by id (duplicate small logic inline to avoid exposing)
                const idx = state.items.findIndex((x) => x.id === id);
                if (idx === -1) return;

                const current = state.items[idx];
                if (current.timerId) window.clearTimeout(current.timerId);

                state.items.splice(idx, 1);
                current.resolveClosed?.();
            }, timeoutMs);
        }
    });
}
