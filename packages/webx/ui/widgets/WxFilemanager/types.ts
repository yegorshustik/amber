export interface WxFilemanagerDialogProps {
    modelValue: boolean;
    multiple?: boolean;
    zIndex?: number;
    size?: number;
}

export interface WxFilelistingProps {
    modelValue: WxFilemanagerFile[];
    selected: WxFilemanagerFile[];
    multiple?: boolean;
}

export interface WxFilemanagerFile {
    id: number;
    name?: string;
    file_name?: string;
    mime?: string;
    size?: number;
    path: string;
    extension?: string;
    url?: string;
}
