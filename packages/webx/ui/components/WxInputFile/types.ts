import type { WxLocalizedValue } from '@/types/locale';
import type { WxFilemanagerFile } from '../../widgets/WxFilemanager/types';
import type { WxValue } from '../WxInput';

export interface WxInputFileProps {
    modelValue?: WxSingleFile | WxLocalizedValue<WxSingleFile> | WxSingleFile[] | WxLocalizedValue<WxSingleFile[]>;
    value?: WxSingleFile | WxSingleFile[] | WxLocalizedValue<WxSingleFile> | WxLocalizedValue<WxSingleFile[]>;
    name?: string;
    localized?: boolean;
    placeholder?: string;
    multiple?: boolean;
}

export interface WxSingleFile {
    src : WxFilemanagerFile,
    title ?: WxValue|null,
}
