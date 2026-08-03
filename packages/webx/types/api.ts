export type ApiMethod = 'get' | 'post' | 'put' | 'patch' | 'delete';

export interface ApiOptions {
    body?: any;
    headers?: Record<string, string>;
    params?: Record<string, any>;
}

export interface ApiResponse<T> {
    data: T;
    message?: string;
    status?: string;
}

export interface ValidationError {
    message: string;
    errors: Record<string, string[]>;
}

export interface LengthAwarePagination<T> {
    current_page: number;
    data: T[];
    first_page_url: string;
    from: number | null;
    last_page: number;
    last_page_url: string;
    links: {
        url: string | null;
        label: string;
        active: boolean;
    }[];
    next_page_url: string | null;
    path: string;
    per_page: number;
    prev_page_url: string | null;
    to: number | null;
    total: number;
}
