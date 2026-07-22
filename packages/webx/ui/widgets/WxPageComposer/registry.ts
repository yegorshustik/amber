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
            pre_heading: null,
            heading: null,
            text: null,
            color: null,
            heading_max_characters: null,
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
        defaults: {
        },
    },
];

export const getComponentsInGroup = (group: ComposerGroup) => registry.filter((item: RegistryComposerComponent) => item.group === group.slug);
export const getComponent = (name : string) => registry.filter((item: RegistryComposerComponent) => item.name === name);
