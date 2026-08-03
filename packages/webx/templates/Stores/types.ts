import type { Site } from '@/templates/Sites';
import type { WxLocalizedValue } from '@/types/locale';
import type { WxCoordinatesType } from '@/ui/components/WxCoordinates';
import type { WxSingleImage } from '@/ui/components/WxInputImage';

export type Store = {
    id: number;
    city?: StoreCity;
    form?: StoreForm;
    contacts?: StoreContact[];
    sites?: Site[];
    images?: WxSingleImage[];
    title: WxLocalizedValue;
    address: WxLocalizedValue;
    content: WxLocalizedValue;
    coordinates: WxCoordinatesType;
    is_published: boolean;
};

export type StoreCity = {
    id: number;
    title: WxLocalizedValue;
    is_published: boolean;
};

export type StoreContact = {
    title: WxLocalizedValue;
    content: WxLocalizedValue;
};

export type StoreForm = {
    id:number;
    title: WxLocalizedValue;
};

