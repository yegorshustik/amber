import type { RouteRecordRaw } from 'vue-router';

export const guidelineRoutes: RouteRecordRaw[] = [
    {
        path: 'guideline',
        children: [
            {
                path: '',
                name: 'guideline.pages',
                component: () => import('./Pages.vue'),
                meta: {},
            },
            {
                path: 'page-full',
                name: 'guideline.page-full',
                component: () => import('./PageFull.vue'),
                meta: {},
            },
            {
                path: 'cards',
                name: 'guideline.cards',
                component: () => import('./Cards.vue'),
                meta: {},
            },
            {
                path: 'dropdowns',
                name: 'guideline.dropdowns',
                component: () => import('./Dropdowns.vue'),
                meta: {},
            },
            {
                path: 'buttons',
                name: 'guideline.buttons',
                component: () => import('./Buttons.vue'),
                meta: {},
            },
            {
                path: 'actions',
                name: 'guideline.actions',
                component: () => import('./Actions.vue'),
                meta: {},
            },
            {
                path: 'i18n',
                name: 'guideline.i18n',
                component: () => import('./I18n.vue'),
                meta: {},
            },
            {
                path: 'tabs',
                name: 'guideline.tabs',
                component: () => import('./Tabs.vue'),
                meta: {},
            },
            {
                path: 'accordion',
                name: 'guideline.accordion',
                component: () => import('./Accordion.vue'),
                meta: {},
            },
            {
                path: 'alerts',
                name: 'guideline.alerts',
                component: () => import('./Alerts.vue'),
                meta: {},
            },
            {
                path: 'dialogs',
                name: 'guideline.dialogs',
                component: () => import('./Dialogs.vue'),
                meta: {},
            },
            {
                path: 'api',
                name: 'guideline.api',
                component: () => import('./Api.vue'),
                meta: {},
            },
            {
                path: 'tree',
                name: 'guideline.tree',
                component: () => import('./Tree.vue'),
                meta: {},
            },
            {
                path: 'sortable',
                name: 'guideline.sortable',
                component: () => import('./Sortable.vue'),
                meta: {},
            },
            {
                path: 'filemanager',
                name: 'guideline.filemanager',
                component: () => import('./Filemanager.vue'),
                meta: {},
            },
            {
                path: 'fieldset',
                name: 'guideline.fieldset',
                component: () => import('./Fieldset.vue'),
                meta: {},
            },
            {
                path: 'datatable',
                name: 'guideline.datatable',
                component: () => import('./Datatable.vue'),
                meta: {},
            },
            {
                path: 'entity-card',
                name: 'guideline.entity-card',
                component: () => import('./EntityCard.vue'),
                meta: {},
            },
            {
                path: 'form',
                name: 'guideline.form',
                component: () => import('./Form.vue'),
                meta: {},
            },
            {
                path: 'page-composer',
                name: 'guideline.page-composer',
                component: () => import('./PageComposer.vue'),
                meta: {},
            },
            {
                path: 'form-elements',
                name: 'guideline.form-elements',
                component: () => import('./FormElements.vue'),
                redirect: '/guideline/form-elements/inputs',
                meta: {},
                children: [
                    {
                        path: 'inputs',
                        name: 'guideline.form-elements.inputs',
                        component: () => import('./FormElements/Inputs.vue'),
                        meta: {
                            title: 'Inputs',
                        },
                    },
                    {
                        path: 'tel',
                        name: 'guideline.form-elements.tel',
                        component: () => import('./FormElements/Tel.vue'),
                        meta: {
                            title: 'Tel',
                        },
                    },
                    {
                        path: 'datetime',
                        name: 'guideline.form-elements.datetime',
                        component: () => import('./FormElements/Datetime.vue'),
                        meta: {
                            title: 'Date && time',
                        },
                    },
                    {
                        path: 'color',
                        name: 'guideline.form-elements.color',
                        component: () => import('./FormElements/Color.vue'),
                        meta: {
                            title: 'Color',
                        },
                    },
                    {
                        path: 'number',
                        name: 'guideline.form-elements.number',
                        component: () => import('./FormElements/Number.vue'),
                        meta: {
                            title: 'Number',
                        },
                    },
                    {
                        path: 'textarea',
                        name: 'guideline.form-elements.textarea',
                        component: () => import('./FormElements/Textarea.vue'),
                        meta: {
                            title: 'Textarea',
                        },
                    },
                    {
                        path: 'select',
                        name: 'guideline.form-elements.select',
                        component: () => import('./FormElements/Select.vue'),
                        meta: {
                            title: 'Select',
                        },
                    },
                    {
                        path: 'checkbox',
                        name: 'guideline.form-elements.checkbox',
                        component: () => import('./FormElements/Checkbox.vue'),
                        meta: {
                            title: 'Checkbox',
                        },
                    },
                    {
                        path: 'media',
                        name: 'guideline.form-elements.media',
                        component: () => import('./FormElements/Media.vue'),
                        meta: {
                            title: 'Media',
                        },
                    },
                    {
                        path: 'tags',
                        name: 'guideline.form-elements.tags',
                        component: () => import('./FormElements/Tags.vue'),
                        meta: {
                            title: 'Tags',
                        },
                    },
                    {
                        path: 'seo',
                        name: 'guideline.form-elements.seo',
                        component: () => import('./FormElements/Seo.vue'),
                        meta: {
                            title: 'Seo',
                        },
                    },
                    {
                        path: 'heading',
                        name: 'guideline.form-elements.heading',
                        component: () => import('./FormElements/Heading.vue'),
                        meta: {
                            title: 'Heading',
                        },
                    },
                    {
                        path: 'video',
                        name: 'guideline.form-elements.video',
                        component: () => import('./FormElements/Video.vue'),
                        meta: {
                            title: 'Video',
                        },
                    },
                ],
            },
            {
                path: 'grid',
                name: 'guideline.grid',
                component: () => import('./Grid.vue'),
                meta: {},
            },
        ],
    },
];
