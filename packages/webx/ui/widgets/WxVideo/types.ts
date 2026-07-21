import { WxLocalizedValue } from '@/types/locale';
import type { WxSingleImage } from '../../components/WxInputImage/types';
import type { WxSingleFile } from '../../components/WxInputFile/types';

export type WxVideoSource = 'mp4' | 'youtube';

export interface WxVideoContent {
    source ?: WxVideoSource,
    poster ?: WxSingleImage,
    file ?: WxSingleFile,
    id ?: string,
    signature ?: WxLocalizedValue|string,
}

export interface WxVideoProps {
    modelValue?: WxVideoContent|WxLocalizedValue<WxVideoContent>;
    value?: WxVideoContent|WxLocalizedValue<WxVideoContent>;
    name?: string;
    preview?: boolean;
    localized?: boolean;
}
