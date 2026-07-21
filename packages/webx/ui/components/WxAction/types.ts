import type { RouteLocationRaw } from 'vue-router';

export interface WxActionProps {
    type?:
        | 'add'
        | 'folder-add'
        | 'edit'
        | 'remove'
        | 'folder-remove'
        | 'sort'
        | 'link'
        | 'more'
        | 'upload'
        | 'copy'
        | 'paste'
        | 'send'
        | 'details'
        | 'eye'
        | 'eye-slash'
        | 'map'
        | 'search'
        | 'restore'
        | 'goto';

    title?: string;
    hidden?: boolean;
    route?: RouteLocationRaw;
}
