export interface WxLocalesList {
    name: string;
    short: string;
    code: string;
}

export interface WxLocales {
    default: string;
    list: WxLocalesList[];
}

export type WxLocalizedValue<T = string> = Record<string, T>;
