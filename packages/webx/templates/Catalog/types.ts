import type { WxLocalizedValue } from '@/types/locale';
import type { WxSingleImage } from '@/ui/components/WxInputImage';
import type { WxSeo } from '@/ui/widgets/WxSeo';

export type CatalogType = {
    value: string;
    title: string;
}

export type CatalogFaqItems = {
    question: WxLocalizedValue;
    answer: WxLocalizedValue;
}

export interface Catalog {
    id: number;
    type?: CatalogType;
    title?: WxLocalizedValue;
    is_published?: boolean;
    is_visible?: boolean;
    slug?: string;
    country?: WxLocalizedValue;
    city?: WxLocalizedValue;
    short_details?: WxLocalizedValue;
    details?: WxLocalizedValue;
    age_range?: WxLocalizedValue;
    gender?: WxLocalizedValue;
    boarding?: WxLocalizedValue;
    curriculum?: WxLocalizedValue;
    size?: WxLocalizedValue;
    campus_style?: WxLocalizedValue;
    acceptance?: WxLocalizedValue;
    programs?: WxLocalizedValue;
    degrees?: WxLocalizedValue;
    established?: WxLocalizedValue;
    image?: WxSingleImage;
    pre_heading?: WxLocalizedValue;
    heading?: WxLocalizedValue;
    content?: WxLocalizedValue;
    faq?: CatalogFaqItems[];
    seo?: WxSeo;
    position?: number;
}
