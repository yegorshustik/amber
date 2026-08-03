import type { RouteRecordRaw } from 'vue-router';

export const servicesRoutes: RouteRecordRaw[] = [
    {
        path: 'services',
        name: 'services',
        component: () => import('./Services.vue'),
        meta: {
            auth: true,
        },
    },
    {
        path: 'services/edit/:id?',
        name: 'services.edit',
        component: () => import('./ServiceEdit.vue'),
    },
];
