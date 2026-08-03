import { WxValue } from '../WxInput/types';

export interface WxTextareaProps {
    modelValue?: WxValue;
    value?: WxValue;
    localized?: boolean;
    wysiwyg?: boolean;
    preset?: 'minimal' | 'maximal';
    placeholder?: string;
    disabled?: boolean;
    name?: string;
}
