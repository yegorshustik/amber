import type { WxLocalizedValue } from '@/types/locale';
import type { WxValue } from '../../components/WxInput/types';

export type WxHeadingType = 'none' | 'h1' | 'h2' | 'h3' | 'h4' | 'h5' | 'h6';

export interface WxHeadingContent {
    text ?: WxLocalizedValue|WxValue,
    style ?: WxHeadingType,
    level ?: WxHeadingType,
}

export interface WxHeadingProps {
    modelValue?: WxHeadingContent;
    value?: WxHeadingContent;
    name?: string;
    class?: string;
    preview?: boolean;
}
