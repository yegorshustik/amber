import type { WxLocalizedValue } from '@/types/locale';
import type { WxSingleImage } from '@/ui/components/WxInputImage';


export type WxQuoteType = 'default' | 'full';

export interface WxQuoteContent {
    type?: WxQuoteType;
    text?: WxLocalizedValue;
    pre_heading?: WxLocalizedValue;
    name?: WxLocalizedValue;
    job?: WxLocalizedValue;
    image?: WxSingleImage;
}

export interface WxQuoteProps {
    modelValue?: WxQuoteContent;
    value?: WxQuoteContent;
    name?: string;
    class?: string;
    preview?: boolean;
}
