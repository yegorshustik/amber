import { defineStore } from 'pinia';
import type  { ApiResponse } from '@/types/api';
import { api } from '@/utils';
import type { Site } from '../templates/Sites/types';

export const useSitesStore = defineStore('sitesStore', {
    state: () => ({
        sites: [],
        active: null,
    }),

    actions: {
        async load() {
            const response = await api.get<ApiResponse<Site[]>>('sites/list');
            this.sites = response.data;

            if (localStorage.getItem('current_site')) {
                this.active = this.sites.find((s) => s.id === parseInt(localStorage.getItem('current_site'))) || this.sites[0];
            } else {
                this.active = this.sites[0] || null;
            }
        },

        reload(sites: Site[]) {
            this.sites = sites;
        },

        isActive(site: Site): boolean {
            return this.active?.id === site.id;
        },

        activeId(): number|string|null {
            return localStorage.getItem('current_site') ? parseInt(localStorage.getItem('current_site')) : null;
        },

        setSite(site: Site) {
            localStorage.setItem('current_site', site.id as string);

            this.active = site;
        },
    },
});
