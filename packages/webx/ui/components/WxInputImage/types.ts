import type { WxLocalizedValue } from '@/types/locale';
import type { WxFilemanagerFile } from '../../widgets/WxFilemanager/types';
import type { WxValue } from '../WxInput';

export interface WxInputImageProps {
    modelValue?: WxSingleImage | WxLocalizedValue<WxSingleImage> | WxSingleImage[] | WxLocalizedValue<WxSingleImage[]>;
    value?: WxSingleImage | WxLocalizedValue<WxSingleImage> | WxSingleImage[] | WxLocalizedValue<WxSingleImage[]>;
    name?: string;
    localized?: boolean;
    placeholder?: string;
    multiple?: boolean;
    preview?: boolean;
    direct?: boolean;
}


export interface WxSingleImage {
    src : WxFilemanagerFile,
    title ?: WxLocalizedValue|WxValue,
    alt ?: WxLocalizedValue|WxValue,
}
