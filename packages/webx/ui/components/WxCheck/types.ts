export type WxCheckType = 'checkbox' | 'radio' | 'switch';

export interface WxCheckProps {
    modelValue?: any | any[];
    type?: WxCheckType;
    label?: string;
    value?: string|number;
    class?: string;
    name?: string;
    checked?: boolean;
    disabled?: boolean;
    binary?: boolean;
}
