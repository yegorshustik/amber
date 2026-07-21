import { defineStore } from 'pinia';

interface CacheEntry {
    data: any;
    expiry: number;
}

export const useApiCacheStore = defineStore('apiCache', {
    state: () => ({
        cache: new Map<string, CacheEntry>(),
        // Храним текущие обещания, чтобы не дублировать запросы
        activeRequests: new Map<string, Promise<any>>()
    }),
    actions: {
        // Метод-обертка для выполнения запроса с защитой от дублей
        async call<T>(key: string, requestFn: () => Promise<T>, ttl: number): Promise<T> {
            const cached = this.cache.get(key);
            if (cached && Date.now() < cached.expiry) {
                return cached.data as T;
            }

            if (this.activeRequests.has(key)) {
                return this.activeRequests.get(key);
            }

            // Создаем обертку с try/catch ВНУТРИ промиса
            const promise = (async () => {
                try {
                    const data = await requestFn();
                    this.cache.set(key, { data, expiry: Date.now() + ttl });
                    return data;
                } catch (error) {
                    // Обязательно логируем и выбрасываем дальше,
                    // но теперь это будет "пойманная" ошибка
                    console.error(`[ApiCache Error]:`, error);
                    throw error;
                } finally {
                    this.activeRequests.delete(key);
                }
            })();

            this.activeRequests.set(key, promise);
            return promise;
        },

        clear() {
            this.cache.clear();
            this.activeRequests.clear();
        }
    }
});
