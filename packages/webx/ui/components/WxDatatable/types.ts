import type { WxLengthAwarePagination } from '@/types/pagination';

export interface WxDatatableProps {
    endpoint?: string;
    endpointQuery?: Record<string, any>;
    data?: WxLengthAwarePagination<any>;
    search?: string;
    persist?: string;
    sortable?: boolean;
    searchable?: boolean;
    compact?: boolean;
    adaptive?: 'sm' | 'md' | 'lg' | 'xl' | 'xxl';
    heading?: string;
    selectable?: 'checkbox' | 'radio' | 'none';
    rowStyle?: (row: any) => any;
    rowClass?: (row: any) => any;
}

export interface WxDatatableColumn {
    id: string;
    size: string;
    title?: string;
    sortable?: boolean;
}

export interface WxDatatableContext {
    registerColumn: (...WxDatatableColumn) => void;
    unregisterColumn: (...WxDatatableColumn) => void;
}
