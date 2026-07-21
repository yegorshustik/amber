import type { WxSingleImage } from '@/ui/components/WxInputImage';

export interface WxUser {
    id: number;
    name: string;
    first_name: string;
    last_name: string;
    email: string;
    phone: string;
    status: string;
    is_activated: boolean;
    image: WxSingleImage;
}
