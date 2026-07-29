import { defineAsyncComponent, markRaw } from 'vue';
import { $t } from '@/locales';
import type { WxIconName } from '@/ui/components/WxIcon';

export interface ComposerGroup {
    slug: string;
    name: string;
}

export type RegistryComposerComponentFeatures = 'add' | 'edit' | 'remove' | 'droppable';

export interface RegistryComposerComponent {
    id ?: string;
    name: string;
    title: string;
    group: string;
    icon?: WxIconName;
    component: any;
    features : RegistryComposerComponentFeatures[];
    defaults: Record<string, any>;
}


export const groups : ComposerGroup[] = [
    {
        name : $t('page-composer.groups.typography'),
        slug : "typography"
    }, {
        name : $t('page-composer.groups.layout'),
        slug : "layout"
    }
];

const defaultFeatures : RegistryComposerComponentFeatures[] = ['add', 'edit', 'remove'];

export const registry: RegistryComposerComponent[] = [
    {
        name: 'Text',
        title: $t('page-composer.components.text'),
        group: 'typography',
        features: defaultFeatures,
        icon: 'text',
        component: markRaw(defineAsyncComponent(() => import('./components/Text.vue'))),
        defaults: {
            text: null,
        },
    },

    {
        name: 'Section',
        title: $t('page-composer.components.section'),
        group: 'typography',
        features: [...defaultFeatures, 'droppable'],
        icon: 'section',
        component: markRaw(defineAsyncComponent(() => import('./components/Section.vue'))),
        defaults: {
            id: null,
            pre_heading: null,
            heading: null,
            text: null,
            color: null,
            heading_max_characters: null,
        },
    },

    {
        name: 'TextBlock',
        title: $t('page-composer.components.text-block'),
        group: 'typography',
        features: [...defaultFeatures, 'droppable'],
        icon: 'richtext',
        component: markRaw(defineAsyncComponent(() => import('./components/TextBlock.vue'))),
        defaults: {
            pre_heading: null,
            heading: null,
            text: null,
            button: null,
            button_url: null,

            additional: {
                reverse: null,
                pre_heading: null,
                heading: null,
                text: null,
                button: null,
                button_url: null,
            },
        },
    },

    {
        name: 'Hero',
        title: $t('page-composer.components.hero'),
        group: 'layout',
        features: [...defaultFeatures],
        icon: 'hero',
        component: markRaw(defineAsyncComponent(() => import('./components/Hero.vue'))),
        defaults: {
            pre_heading: null,
            heading: null,
            text: null,
            image: null,
            image_2: null,
        },
    },

    {
        name: 'Headline',
        title: $t('page-composer.components.headline'),
        group: 'layout',
        features: [...defaultFeatures],
        icon: 'headline',
        component: markRaw(defineAsyncComponent(() => import('./components/Headline.vue'))),
        defaults: {
            color: null,
            pre_heading: null,
            heading: null,
            text: null,
            button_1: null,
            button_1_url: null,
            button_2: null,
            button_2_url: null,
        },
    },

    {
        name: 'Quote',
        title: $t('page-composer.components.quote'),
        group: 'typography',
        features: defaultFeatures,
        icon: 'quote',
        component: markRaw(defineAsyncComponent(() => import('./components/Quote.vue'))),
        defaults: {
            quote: null,
        },
    },
    {
        name: 'Reviews',
        title: $t('page-composer.components.reviews'),
        group: 'layout',
        features: [...defaultFeatures],
        icon: 'chat-dots',
        component: markRaw(defineAsyncComponent(() => import('./components/Reviews.vue'))),
        defaults: {},
    },

    {
        name: 'Cta',
        title: $t('page-composer.components.cta'),
        group: 'layout',
        features: [...defaultFeatures],
        icon: 'horn',
        component: markRaw(defineAsyncComponent(() => import('./components/Cta.vue'))),
        defaults: {
            pre_heading: null,
            heading: null,
            text: null,
            button: null,
            url: null,
        },
    },

    {
        name: 'Cards',
        title: $t('page-composer.components.cards'),
        group: 'layout',
        features: [...defaultFeatures],
        icon: 'cards',
        component: markRaw(defineAsyncComponent(() => import('./components/Cards.vue'))),
        defaults: {
            type: null,
            image: null,
            columns: null,
            items: [],
            button: null,
            url: null,
        },
    },

    {
        name: 'Article',
        title: $t('page-composer.components.article'),
        group: 'layout',
        features: defaultFeatures,
        icon: 'layout-text-left',
        component: markRaw(defineAsyncComponent(() => import('./components/Article.vue'))),
        defaults: {
            hide_toc: '0',
        },
    },

    {
        name: 'Image',
        title: $t('page-composer.components.image'),
        group: 'typography',
        features: defaultFeatures,
        icon: 'image',
        component: markRaw(defineAsyncComponent(() => import('./components/Image.vue'))),
        defaults: {
            image: null,
            signature: null,
        },
    },
    {
        name: 'Faq',
        title: $t('page-composer.components.faq'),
        group: 'layout',
        features: [...defaultFeatures],
        icon: 'question',
        component: markRaw(defineAsyncComponent(() => import('./components/Faq.vue'))),
        defaults: {
            category: '',
        },
    },
    {
        name: 'Person',
        title: $t('page-composer.components.person'),
        group: 'layout',
        features: [...defaultFeatures],
        icon: 'user',
        component: markRaw(defineAsyncComponent(() => import('./components/Person.vue'))),
        defaults: {
            image: null,
            job: null,
            name: null,
            about: null,
            linkedin: null,
        },
    },
    {
        name: 'Contacts',
        title: $t('page-composer.components.contacts'),
        group: 'layout',
        features: [...defaultFeatures],
        icon: 'person-vcard',
        component: markRaw(defineAsyncComponent(() => import('./components/Contacts.vue'))),
        defaults: {},
    },

    {
        name: 'ContactCenter',
        title: $t('page-composer.components.contact-center'),
        group: 'layout',
        features: [...defaultFeatures],
        icon: 'input-cursor-text',
        component: markRaw(defineAsyncComponent(() => import('./components/ContactCenter.vue'))),
        defaults: {
            pre_heading: null,
            heading: null,
            text: null,
            form_id: null,
        },
    },

    {
        name: 'Services',
        title: $t('page-composer.components.services'),
        group: 'layout',
        features: [...defaultFeatures],
        icon: 'server',
        component: markRaw(defineAsyncComponent(() => import('./components/Services.vue'))),
        defaults: {},
    },
];

export const getComponentsInGroup = (group: ComposerGroup) => registry.filter((item: RegistryComposerComponent) => item.group === group.slug);
export const getComponent = (name : string) => registry.filter((item: RegistryComposerComponent) => item.name === name);
