import type { Ref } from 'vue';

export interface WxActionsProps {
    type?: 'default' | 'adaptive';
    align?: 'start' | 'end';
    parent?: Ref<HTMLDivElement>;
}
