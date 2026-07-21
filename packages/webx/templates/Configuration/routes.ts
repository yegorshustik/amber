import type { RouteRecordRaw } from 'vue-router';

export const configurationRoutes: RouteRecordRaw[] = [
    {
        path: 'configuration',
        name: 'configuration',
        component: () => import('./Index.vue'),
        meta: {
            auth : true
        }
    }
];
