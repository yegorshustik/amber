export interface WxEntityCardParam {
    option ?: string;
    value : string;
}

export interface WxEntityCardProps {
    title : string;
    image ?: string;
    params ?: WxEntityCardParam[],
}
