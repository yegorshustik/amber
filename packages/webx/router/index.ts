import { createRouter, createWebHistory } from 'vue-router';
import { useUserStore } from '@/stores';
import { useConfigurationStore } from '@/stores/configuration';
import { articlesRoutes } from '@/templates/Articles';
import { catalogRoutes } from '@/templates/Catalog';
import { configurationRoutes } from '@/templates/Configuration';
import { inboxRoutes } from '@/templates/Inbox';
import { pagesRoutes } from '@/templates/Pages';
import { reviewsRoutes } from '@/templates/Reviews';
import { servicesRoutes } from '@/templates/Services';
import { sitesRoutes } from '@/templates/Sites';
import { usersRoutes } from '@/templates/Users';

import type { User } from '@/types/user';
import { WxLayout } from '@/ui';

import { api } from '@/utils';

const router = createRouter({
    history: createWebHistory('/cms/'),
    routes: [
        {
            path: '/',
            component: WxLayout,
            children: [
                ...inboxRoutes,
                ...sitesRoutes,
                ...usersRoutes,
                ...configurationRoutes,
                ...pagesRoutes,
                ...articlesRoutes,
                ...reviewsRoutes,
                ...servicesRoutes,
                ...catalogRoutes,

                {
                    path: '/:pathMatch(.*)*',
                    name: 'NotFound',
                    component: () => import('@/templates/NotFound.vue'),
                },
            ],
            meta: {
                auth: true,
            },
        },
        {
            path: '/login',
            name: 'sign-in',
            component: () => import('@/templates/Users/SignIn.vue'),
            meta: {
                auth: false,
            },
        },
    ],
});

router.isReady().then(() => {
    useConfigurationStore().load()
})

router.beforeEach(async (to, from, next) => {
    if ((to.meta as any).auth === true) {
        try {
            useUserStore().user = await api.authorizedUser<User>() as User;

            next();
        }
        catch (e) {
            next({ name: 'sign-in' });
        }
    } else {
        next();
    }
})

export default router;
