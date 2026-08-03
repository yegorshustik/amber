import type { WxLocalizedValue } from '@/types/locale';
import type { WxSingleImage } from '@/ui/components/WxInputImage';

export type Review = {
    id: number;
    image?: WxSingleImage;
    name: WxLocalizedValue;
    job: WxLocalizedValue;
    published_at: string;
    is_published: boolean;
    content: WxLocalizedValue;
};
