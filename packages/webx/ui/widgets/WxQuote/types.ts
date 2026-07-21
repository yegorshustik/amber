import type { WxLocalizedValue } from '@/types/locale';
import type { WxSingleImage } from '@/ui/components/WxInputImage';
import type { WxValue } from '../../components/WxInput/types';


export interface WxQuoteContent {
    text ?: WxLocalizedValue|WxValue,
    signature ?: WxLocalizedValue|WxValue,
    image ?: WxSingleImage
}

export interface WxQuoteProps {
    modelValue?: WxQuoteContent;
    value?: WxQuoteContent;
    name?: string;
    class?: string;
    preview?: boolean;
}
