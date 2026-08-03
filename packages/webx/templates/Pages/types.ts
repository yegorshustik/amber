import type { WxLocalizedValue } from '@/types/locale';
import type { WxPageComposerResponse } from '@/ui/widgets/WxPageComposer';
import type { WxSeo } from '@/ui/widgets/WxSeo';

export interface PageTree {
    id: number;
    parent_id: number | null;
    title: string;
    slug: string;
    position: number;
    children?: PageTree[];
}

export interface Page {
    id: number;
    parent_id: number | null;
    title: WxLocalizedValue;
    slug: string;
    is_published: boolean;
    seo?: WxSeo;
    content?: WxPageComposerResponse;
    options?: Record<string, any>;
}
