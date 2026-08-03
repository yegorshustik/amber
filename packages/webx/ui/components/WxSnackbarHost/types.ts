import type { WxAlertType } from '../WxAlert';

export interface WxSnackbarItem {
    id: string;
    message: string;
    type: WxAlertType;
    timeoutMs: number;
}
