import type { WxLocalizedValue } from '@/types/locale';

export type WxValue = string | number | WxLocalizedValue;

export interface WxInputProps {
    modelValue?: WxValue;
    value?: WxValue;
    type?: 'text' | 'password' | 'email' | 'search' | 'tel' | 'url' | 'date' |  'time' | 'datetime' | 'color' | 'number';
    name?: string;
    localized?: boolean;
    placeholder?: string;
    disabled?: boolean;
    min?: number;
    max?: number;
    step?: number;
}
