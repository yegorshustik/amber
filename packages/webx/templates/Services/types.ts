import type { WxLocalizedValue } from '@/types/locale';
import type { WxPageComposerResponse } from '@/ui/widgets/WxPageComposer';
import type { WxSeo } from '@/ui/widgets/WxSeo';

export interface Service {
    id: number | string;
    title: WxLocalizedValue;
    details: WxLocalizedValue;
    slug: string;
    is_published: boolean;
    seo?: WxSeo;
    content?: WxPageComposerResponse;
}
