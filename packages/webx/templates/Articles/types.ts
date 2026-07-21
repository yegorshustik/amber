import type { WxLocalizedValue } from '@/types/locale';
import type { WxSingleImage } from '@/ui/components/WxInputImage';
import type { WxPageComposerResponse } from '@/ui/widgets/WxPageComposer';
import type { WxSeo } from '@/ui/widgets/WxSeo/types';

export interface Tag {
    id: number;
    title: WxLocalizedValue;
    slug: string;
}

export interface Article {
    id: number;
    image?: WxSingleImage;
    title: WxLocalizedValue;
    slug: string;
    is_published: boolean;
    published_at: string;
    tags: Tag[];
    rubrics: ArticleRubric[];
    seo?: WxSeo;
    announcement?: WxLocalizedValue;
    content?: WxPageComposerResponse;
}

export interface ArticleRubric {
    id: number;
    image?: WxSingleImage;
    title: WxLocalizedValue;
    slug: string;
    is_published: boolean;
    seo?: WxSeo;
    content?: WxLocalizedValue;
}
