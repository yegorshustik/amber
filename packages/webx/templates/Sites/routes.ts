import type { RouteRecordRaw } from 'vue-router';

export const sitesRoutes: RouteRecordRaw[] = [
    {
        path: 'sites',
        name: 'sites',
        component: () => import('./Sites.vue'),
        meta: {
            auth: true,
        },
    },
    {
        path: 'sites/edit/:id?',
        name: 'sites.edit',
        component: () => import('./SiteEdit.vue'),
    },
];
