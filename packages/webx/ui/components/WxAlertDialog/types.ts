export interface WxAlertDialogProps {
    modelValue: boolean;

    title?: string;
    message: string;

    okText?: string;

    size?: number;

    persistent?: boolean;
    closeOnOverlay?: boolean;
    closeOnEscape?: boolean;
}
