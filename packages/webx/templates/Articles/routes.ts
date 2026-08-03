import type { RouteRecordRaw } from 'vue-router';

export const articlesRoutes: RouteRecordRaw[] = [
    {
        path: 'articles',
        name: 'articles',
        component: () => import('./Articles.vue'),
        meta: {
            auth : true
        }
    }, {
        path: 'articles/edit/:id?',
        name: 'articles.edit',
        component: () => import('./ArticleEdit.vue'),
    }, {
        path: 'articles/rubrics',
        name: 'articles.rubrics',
        component: () => import('./Rubrics.vue'),
    }, {
        path: 'articles/rubrics/edit/:id?',
        name: 'articles.rubrics.edit',
        component: () => import('./RubricEdit.vue'),
    }
];
