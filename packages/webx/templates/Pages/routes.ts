import type { RouteRecordRaw } from 'vue-router';

export const pagesRoutes: RouteRecordRaw[] = [
    {
        path: 'pages',
        name: 'pages',
        component: () => import('./Index.vue'),
        meta: {
            auth : true
        }
    }, {
        path: 'pages/edit/:id?',
        name: 'pages.edit',
        component: () => import('./Edit.vue'),
        meta: {
            auth : true
        }
    }
];
