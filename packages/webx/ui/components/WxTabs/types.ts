// types.ts
import type { Ref } from 'vue';

export interface WxTabItem {
    id: string;
    name: string;
}

export interface WxTabsContext {
    type: 'horizontal' | 'vertical';
    active: Ref<string | null>;
    registerTab: (tab: WxTabItem) => void;
    unregisterTab: (id: string) => void;
    setActive: (id: string) => void;
}
