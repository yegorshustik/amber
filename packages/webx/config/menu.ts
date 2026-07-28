import { $t } from '@/locales';
import type { WxMenuItem } from '@/types/menu';


export const menu: WxMenuItem[] = [
    {
        title: $t('menu.inbox'),
        route: 'inbox',
        active: ['inbox.form.applications'],
        children: [
            {
                title: $t('menu.inbox'),
                route: 'inbox',
            },
            {
                title: $t('menu.inbox-forms'),
                route: 'inbox.forms',
                active: ['inbox.forms.edit'],
            },
        ],
    },

    {
        title: $t('menu.content'),
        children: [
            {
                title: $t('menu.pages'),
                route: 'pages',
                active: ['pages.edit'],
            },
            {
                title: $t('menu.articles'),
                route: 'articles',
                children: [
                    {
                        title: $t('menu.articles'),
                        route: 'articles',
                        active: ['articles.edit'],
                    },
                    {
                        title: $t('menu.articles-rubrics'),
                        active: ['articles.rubrics.edit'],
                        route: 'articles.rubrics',
                    },
                ],
            },
            {
                title: $t('reviews.menu'),
                route: 'reviews',
                active: ['reviews.edit'],
            },
            {
                title: $t('faq.menu'),
                route: 'configuration.faq',
                active: [],
            },
        ],
    },
    {
        title: $t('menu.system'),
        children: [
            {
                title: $t('menu.configuration'),
                route: 'configuration',
            },
            {
                title: $t('sites.menu'),
                route: 'sites',
            },
            {
                title: $t('menu.users'),
                route: 'users',
            },
        ],
    },
];
