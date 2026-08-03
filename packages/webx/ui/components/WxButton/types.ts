import type { RouteLocationRaw } from 'vue-router';

export interface WxButtonProps {
    theme?:
        'default' |
        'blank' |
        'primary' | 'outline-primary' |
        'success' | 'outline-success' |
        'danger' | 'outline-danger' |
        'warning' | 'outline-warning' |
        'light' | 'outline-light' |
        'info' | 'outline-info' |
        'secondary' | 'outline-secondary' |
        'create' |
        'back'
    ;

    type?: 'submit' | 'button';

    size?: 'sm' | 'md' | 'lg' | 'xl';

    square?: boolean;

    route ?: RouteLocationRaw
}
