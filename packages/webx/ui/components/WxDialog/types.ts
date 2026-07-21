import type { ApiMethod } from '@/types/api';

export interface WxDialogProps {
    modelValue: boolean;
    size?: number;
    zIndex?: number;
    title?: string;
    persistent?: boolean;

    closeOnOverlay?: boolean;
    closeOnEscape?: boolean;

    action?: string;
    method?: ApiMethod;
}
