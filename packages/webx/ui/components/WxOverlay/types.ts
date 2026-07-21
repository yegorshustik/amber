import type { StyleValue } from 'vue';

export interface WxOverlayProps {
    modelValue: boolean;

    persistent?: boolean;
    lockScroll?: boolean;

    closeOnOverlay?: boolean;
    closeOnEscape?: boolean;

    teleportTo?: string;

    overlayClass?: string;
    contentClass?: string;

    overlayTransition?: string;
    contentTransition?: string;
    contentAppear?: boolean;

    zIndex?: number;

    overlayStyle?: StyleValue;
    contentStyle?: StyleValue;
}

export interface WxOverlayDefaultSlotProps {
    close: () => void;
}
