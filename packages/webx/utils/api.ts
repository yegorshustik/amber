import { ref } from 'vue';
import { config } from '@/config/api';

import { useApiCacheStore, useSitesStore } from '@/stores';
import type { ApiMethod, ApiOptions, ApiResponse, ValidationError } from '@/types/api';
import type { User } from '@/types/user';

class ApiClient {
    private readonly CACHE_TTL = 300000;

    private baseUrl: string = '';
    private token: string = '';

    public isLoading = ref(false);
    public enableLoading = ref(true);
    private activeRequests = ref(0);

    constructor() {
        this.baseUrl = config.baseUrl;
        this.token = config.token;
    }

    public getToken ()
    {
        return localStorage.getItem(this.token)
    }

    public async cachedRequest<T>(method: ApiMethod, endpoint: string, options: any): Promise<T> {
        const cacheStore = useApiCacheStore();
        const cacheKey = this.generateCacheKey(method, endpoint, options);

        if (method !== 'get') {
            return await this.executeFetch<T>(method, endpoint, options);
        }

        return cacheStore.call(
            cacheKey,
            () => this.executeFetch<T>(method, endpoint, options),
            this.CACHE_TTL
        );
    }

    public async request<T>(method: ApiMethod, endpoint: string, options: any): Promise<T> {
        return await this.executeFetch<T>(method, endpoint, options);
    }

    public prepareUrl(url : string) {
        return `${this.baseUrl}/${url.replace(/^\//, '')}`
    }

    private async executeFetch<T>(method: ApiMethod, endpoint: string, options: ApiOptions = {}): Promise<T> {
        this.activeRequests.value++;
        this.isLoading.value = true;

        try {
            const url = new URL(`${this.prepareUrl(endpoint)}`, window.location.origin);

            if (options.params) {
                Object.keys(options.params).forEach(key => {
                    const val = options.params![key];
                    if (val !== null && val !== undefined) {
                        url.searchParams.append(key, String(val));
                    }
                });
            }

            const headers: Record<string, string> = {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-Selected-Site': useSitesStore().activeId() as string,
                ...options.headers,
            };

            if (localStorage.getItem(this.token)) {
                headers['Authorization'] = `Bearer ${localStorage.getItem(this.token)}`;
            }

            let body: any = undefined;

            if (options.body) {
                if (options.body instanceof FormData) {
                    body = options.body;
                } else {
                    headers['Content-Type'] = 'application/json';
                    body = JSON.stringify(options.body);
                }
            }

            const config: RequestInit = {
                method: method.toUpperCase(),
                headers,
                body
            };

            const response = await fetch(url.toString(), config);

            // Обработка ошибок валидации (422)
            if (response.status === 422) {
                const errorData: ValidationError = await response.json();
                throw {
                    status: 422,
                    errors: errorData.errors,
                    message: errorData.message
                };
            }

            // Обработка системных ошибок
            if (!response.ok) {
                const errorData = await response.json();

                if (errorData.message) {
                    throw {
                        status: response.status,
                        message: errorData.message
                    };
                }

                const errorText = await response.text();
                throw {
                    status: response.status,
                    message: errorText || `API Error: ${response.statusText}`
                };
            }

            if (response.status === 204) return null as T;

            return (await response.json()) as T;

        } finally {
            this.activeRequests.value--;
            if (this.activeRequests.value <= 0) {
                this.activeRequests.value = 0;
                this.isLoading.value = false;
            }
        }
    }

    public async downloadStream<T>(method: ApiMethod, endpoint: string, options: ApiOptions = {}): Promise<T> {
        this.activeRequests.value++;
        this.isLoading.value = true;

        try {
            const url = new URL(`${this.prepareUrl(endpoint)}`, window.location.origin);

            if (options.params) {
                Object.keys(options.params).forEach(key => {
                    const val = options.params![key];
                    if (val !== null && val !== undefined) {
                        url.searchParams.append(key, String(val));
                    }
                });
            }

            const headers: Record<string, string> = {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                ...options.headers,
            };

            if (localStorage.getItem(this.token)) {
                headers['Authorization'] = `Bearer ${localStorage.getItem(this.token)}`;
            }

            let body: any = undefined;

            if (options.body) {
                if (options.body instanceof FormData) {
                    body = options.body;
                } else {
                    headers['Content-Type'] = 'application/json';
                    body = JSON.stringify(options.body);
                }
            }

            const config: RequestInit = {
                method: method.toUpperCase(),
                headers,
                body
            };

            const response = await fetch(url.toString(), config);

            // Обработка ошибок валидации (422)
            if (response.status === 422) {
                const errorData: ValidationError = await response.json();
                throw {
                    status: 422,
                    errors: errorData.errors,
                    message: errorData.message
                };
            }

            // Обработка системных ошибок
            if (!response.ok) {
                const errorData = await response.json();

                if (errorData.message) {
                    throw {
                        status: response.status,
                        message: errorData.message
                    };
                }

                const errorText = await response.text();
                throw {
                    status: response.status,
                    message: errorText || `API Error: ${response.statusText}`
                };
            }

            if (response.status === 204) return null as T;

            return (await response.blob()) as T;

        } finally {
            this.activeRequests.value--;
            if (this.activeRequests.value <= 0) {
                this.activeRequests.value = 0;
                this.isLoading.value = false;
            }
        }
    }

    public async csrf<T>(url : string) {
        const headers: Record<string, string> = {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        };

        const config: RequestInit = {
            method: 'get',
            headers
        };

        const response = await fetch(url, config);
        if (!response.ok) {
            const errorText = await response.text();
            throw {
                status: response.status,
                message: errorText || `API Error: ${response.statusText}`
            };
        }

        if (response.status === 204) return null as T;

        return (await response.json()) as T;
    }

    public get<T>(url: string, params?: Record<string, any>) {
        return this.request<T>('get', url, { params });
    }

    public getCached<T>(url: string, params?: Record<string, any>) {
        return this.cachedRequest<T>('get', url, { params });
    }

    public post<T>(url: string, body?: any) {
        return this.request<T>('post', url, { body });
    }

    public put<T>(url: string, body?: any) {
        return this.request<T>('put', url, { body });
    }

    public patch<T>(url: string, body?: any) {
        return this.request<T>('patch', url, { body });
    }

    public delete<T>(url: string, body?: any) {
        return this.request<T>('delete', url, { body });
    }

    public async all<T extends readonly unknown[] | []>(values: T): Promise<{ [P in keyof T]: Awaited<T[P]> }> {
        this.activeRequests.value++;
        this.isLoading.value = true;
        try {
            return await Promise.all(values);
        } finally {
            this.activeRequests.value--;
            if (this.activeRequests.value <= 0) {
                this.isLoading.value = false;
            }
        }
    }

    private generateCacheKey(method: string, endpoint: string, options: ApiOptions): string {
        return `${method}:${endpoint}:${JSON.stringify(options.params || {})}:${JSON.stringify(options.body || {})}`;
    }

    public async authorizedUser<T>() {
        if (!localStorage.getItem(this.token)) {
            throw {
                status : 401,
                message : "Unauthorized"
            }
        }
        this.enableLoading.value = false;
        const response = await this.get<ApiResponse<User>>('user/check');
        this.enableLoading.value = true;

        return response.data as T;
    }
}

export const api = new ApiClient();
