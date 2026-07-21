export interface WxConfirmDialogProps {
    modelValue: boolean;

    title?: string;
    message: string;

    confirmText?: string;
    cancelText?: string;

    size?: number;

    persistent?: boolean;
    closeOnOverlay?: boolean;
    closeOnEscape?: boolean;
}
