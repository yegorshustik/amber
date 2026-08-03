export interface WxSidePanelProps {
    modelValue: boolean;
    size?: number;         // Ширина панели в px
    title?: string;
    persistent?: boolean;

    closeOnOverlay?: boolean;
    closeOnEscape?: boolean;
}
