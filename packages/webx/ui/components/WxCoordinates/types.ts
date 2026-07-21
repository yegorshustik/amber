export type WxCoordinatesType = {
    latitude: number,
    longitude: number,
};

export interface WxCoordinatesProps {
    modelValue?: WxCoordinatesType;
    value?: WxCoordinatesType;
    name?: string;
}
