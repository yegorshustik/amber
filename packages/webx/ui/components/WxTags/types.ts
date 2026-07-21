import type { WxLocalizedValue } from '@/types/locale';
import type { WxValue } from '../WxInput/types';

export interface WxTag {
    id : number;
    title : WxLocalizedValue|WxValue;
    slug : string;
}

export interface WxTagsProps {
    modelValue?: WxTag[];
    value?: WxTag[];
    name?: string;
    placeholder?: string;
    endpoint: string;
}
