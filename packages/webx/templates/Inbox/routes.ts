import type { RouteRecordRaw } from 'vue-router';

export const inboxRoutes: RouteRecordRaw[] = [
    {
        path: '',
        name: 'inbox',
        component: () => import('./Index.vue'),
        meta: {
            auth : true
        },
        children: [
            {
                path: 'inbox/form/:id',
                name: 'inbox.form.applications',
                component: () => import('./Applications.vue'),
            }
        ]
    }, {
        path: 'inbox/forms',
        name: 'inbox.forms',
        component: () => import('./Forms.vue'),
        meta: {
            auth : true
        }
    }, {
        path: 'inbox/forms/edit/:id?',
        name: 'inbox.forms.edit',
        component: () => import('./FormEdit.vue'),
    }, {
        path: 'inbox/forms/application/:id',
        name: 'inbox.forms.application',
        component: () => import('./FormApplication.vue'),
    }
];
