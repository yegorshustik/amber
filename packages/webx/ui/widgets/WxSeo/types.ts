import type { WxSingleImage } from '../../components/WxInputImage/types';
import type { WxValue } from '../../components/WxInput/types';

export interface WxSeo {
    og ?: WxSingleImage;
    title ?: WxValue;
    keywords ?: WxValue;
    description ?: WxValue;
    h1 ?: WxValue;
}

export interface WxSeoProps {
    modelValue?: WxSeo;
    value?: WxSeo;
    name: string;
}
