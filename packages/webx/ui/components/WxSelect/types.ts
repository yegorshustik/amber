export interface WxSelectOption {
    value: string | number;
    label: string;
    disabled?: boolean;
    [key: string]: any;
}

export interface WxSelectProps {
    modelValue?: any | any[];
    value?: any | any[];
    options: WxSelectOption[];
    multiple?: boolean;
    searchable?: boolean;
    placeholder?: string;
    disabled?: boolean;
    name?: string;
    returnObject?: boolean;
}
