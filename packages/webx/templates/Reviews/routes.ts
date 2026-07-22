import type { RouteRecordRaw } from 'vue-router';

export const reviewsRoutes: RouteRecordRaw[] = [
    {
        path: 'reviews',
        name: 'reviews',
        component: () => import('./Reviews.vue'),
        meta: {
            auth: true,
        },
    },
    {
        path: 'reviews/edit/:id?',
        name: 'reviews.edit',
        component: () => import('./ReviewEdit.vue'),
    }
];
