import type { WxSingleFile } from '@/ui/components/WxInputFile';
import type { WxSingleImage } from '@/ui/components/WxInputImage';

export interface ApiTokenResponse {
    token: string;
}

export interface User {
    id: number;
    name: string;
    first_name: string;
    last_name: string;
    email: string;
    phone: string;
    company_name: string;
    company_erdpo: string;
    company_legal_address: string;
    status: string;
    is_activated: boolean;
    image?: WxSingleImage;
    documents?:WxSingleFile[]
}
