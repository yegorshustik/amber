import { defineStore } from 'pinia';
import type  { ApiResponse } from '@/types/api';
import type { Configuration } from '@/types/configuration';
import { api } from '@/utils';

export const useConfigurationStore = defineStore('configurationStore', {
    state: () => ({
        params: [],
    }),

    actions: {
        load() {
            api.get<ApiResponse<Configuration[]>>('configuration').then((response) => {
                this.params = response.data;
            })
        },

        all() : any {
            const result = {};

            this.params.forEach(p => {
                result[p.slug] = p.content;
            })

            return result;
        },

        allRaw() : any {
            const result = {};
            this.params.forEach(p => {
                result[p.slug] = p.content_raw;
            })

            return result;
        },

        get(slug) : any {
            return this.params.find(p => p.slug === slug)?.content;
        },

        getRaw(slug) : any {
            return this.params.find(p => p.slug === slug)?.content_raw;
        }
    }
});
