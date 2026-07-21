import type { Placement } from '@popperjs/core';

export interface WxDropdownProps {
    placement?: Placement;
    offset?: [number, number];
    closeOnClick?: boolean;
}

export interface WxDropdownTriggerSlotProps {
    isOpen: boolean;
}

export interface WxDropdownDefaultSlotProps {
    close: () => void;
}
