import type { RouteRecordRaw } from 'vue-router';

export const storesRoutes: RouteRecordRaw[] = [
    {
        path: 'stores',
        name: 'stores',
        component: () => import('./Stores.vue'),
        meta: {
            auth: true,
        },
    },
    {
        path: 'stores/edit/:id?',
        name: 'stores.edit',
        component: () => import('./StoreEdit.vue'),
    }
];
