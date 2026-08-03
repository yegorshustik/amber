import type { WxMenuItem } from '@/types/menu';

export type WxMenuMode = 'accordion' | 'dropdown';

export interface WxMenuProps {
    items: WxMenuItem[];
    mode?: WxMenuMode;
}
