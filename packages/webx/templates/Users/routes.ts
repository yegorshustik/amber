import type { RouteRecordRaw } from 'vue-router';

export const usersRoutes: RouteRecordRaw[] = [
    {
        path: 'users',
        name: 'users',
        component: () => import('./Index.vue'),
        meta: {
            auth : true
        }
    }
];
