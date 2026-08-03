import { RouteLocationRaw } from 'vue-router';

export interface WxPageProps {
    heading: string;
    size?: 'default' | 'full';
    back ?: RouteLocationRaw;
}
