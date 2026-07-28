import type { RouteRecordRaw } from 'vue-router';

export const configurationRoutes: RouteRecordRaw[] = [
    {
        path: 'configuration',
        name: 'configuration',
        component: () => import('./Index.vue'),
        meta: {
            auth : true
        }
    }, {
        path: 'configuration/faq',
        name: 'configuration.faq',
        component: () => import('./Faq.vue'),
        meta: {
            auth : true
        }
    }
];
