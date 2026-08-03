import type { RouteRecordRaw } from 'vue-router';

export const catalogRoutes: RouteRecordRaw[] = [
    {
        path: 'catalog',
        name: 'catalog',
        component: () => import('./Catalog.vue'),
        meta: {
            auth: true,
        },
    },
    {
        path: 'catalog/edit/:id?',
        name: 'catalog.edit',
        component: () => import('./CatalogEdit.vue'),
    },
];
