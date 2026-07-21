import type { WxCoordinatesType } from '@/ui/components/WxCoordinates';

export interface WxMapProps {
    coordinates: WxCoordinatesType;
    zoom?:number;
}
