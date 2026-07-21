export type WxMenuMode = 'accordion' | 'dropdown';

export interface WxMenuItem {
    title: string;
    route?: string;
    params?: Record<string, any>;
    children?: WxMenuItem[];
    active ?: string[]
}
