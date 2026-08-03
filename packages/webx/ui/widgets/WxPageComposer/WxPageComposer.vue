<script setup lang="ts">
import { provide, ref } from 'vue';
import { $t } from '@/locales';
import { wxBuffer } from '@/utils';
import WxFieldset from '../../components/WxFieldset/WxFieldset.vue';
import WxIcon from '../../components/WxIcon/WxIcon.vue';
import WxSidePanel from '../../components/WxSidePanel/WxSidePanel.vue';

import type { RegistryComposerComponent } from './registry';
import { registry, groups, getComponentsInGroup } from './registry';
import type { WxPageComposerProps, WxPageComposerContext, WxPageComposerComponent } from './types';
import WxPageComposerCanvas from './WxPageComposerCanvas.vue';
import WxPageComposerSorting from './WxPageComposerSorting.vue';

const props = withDefaults(defineProps<WxPageComposerProps>(), {});

const currentValue = ref<WxPageComposerComponent[]>(props.modelValue || props.value || []);
const componentsBrowser = ref<boolean>(false);
const sorting = ref<boolean>(false);
const currentCanvasChildren = ref<WxPageComposerComponent[]>([]);
const currentCanvasAfter = ref<WxPageComposerComponent>(null);
const editingId = ref<string | null>(null);
const removingId = ref<string | null>(null);

const { push, clear } = wxBuffer();

const addComponent = (children: WxPageComposerComponent[], after : WxPageComposerComponent | null = null) => {
    currentCanvasChildren.value = children;
    currentCanvasAfter.value    = after;
    componentsBrowser.value     = true;
};

const sortingMode = () => {
    sorting.value = true;
};

const findComponent = (name: string) => {
    return registry.find((item: RegistryComposerComponent) => item.name === name);
};

const appendComponent = (component: RegistryComposerComponent) => {
    const id = `${Math.random().toString(36).substring(2, 9)}`;

    const data = {
        id: id,
        name: component.name,
        children: [],
        content: {},
    } as WxPageComposerComponent

    if (currentCanvasAfter.value) {
        const index = currentCanvasChildren.value.findIndex(c => c.id === currentCanvasAfter.value.id)

        if (index !== -1) {
            currentCanvasChildren.value.splice(index + 1, 0, data);
        }
        else {
            currentCanvasChildren.value.push(data)
        }
    }
    else {
        currentCanvasChildren.value.push(data);
    }

    currentCanvasAfter.value = null;
    componentsBrowser.value = false;
};

const startEditing = (component: WxPageComposerComponent) => {
    editingId.value = component.id as string;
};
const startRemoving = (component: WxPageComposerComponent) => {
    removingId.value = component.id as string;
};

const copyComponent = (component: WxPageComposerComponent[]) => {
    push({
        title : $t('page-composer.component-buffer'),
        content : component
    });
};

const prepareForPaste = (component: WxPageComposerComponent) => {
    const clone = JSON.parse(JSON.stringify(component));
    const id = `${Math.random().toString(36).substring(2, 9)}`;

    const refreshIds = (item: any) => {
        item.id = id;
        if (item.children && item.children.length > 0) {
            item.children.forEach(refreshIds);
        }
    };

    refreshIds(clone);
    return clone;
};

const pasteComponent = (action, children, after : WxPageComposerComponent, buffer: WxPageComposerComponent[]) => {


    const content : WxPageComposerComponent[] = [];

    for (const item of buffer) {
        content.push(prepareForPaste(item));
    }

    if (action === 'after') {
        const index = children.findIndex(c => c.id === after.id)


        if (index !== -1) {
            children.splice(index + 1, 0, ...content);
        }
        else {
            children.push(...content)
        }
    }
    else if (action === 'append') {
        children.push(...content)
    }

    clear()
};

provide<WxPageComposerContext>('pageComposerContext', {
    addComponent,
    copyComponent,
    pasteComponent,
    findComponent,
    sortingMode,
    startEditing,
    editingId,
    startRemoving,
    removingId
});
</script>

<template>
    <div class="wx-page-composer">
        <textarea :name="props.name" v-if="props.name" class="wx-native-hidden">{{ currentValue }}</textarea>
        <wx-page-composer-canvas v-model:children="currentValue" />
    </div>

    <wx-side-panel :size="800" v-model="componentsBrowser" :title="$t('page-composer.browser')">
        <wx-fieldset v-for="group in groups" :legend="group.name" :key="group.slug">
            <div class="wx-components-grid">
                <div
                    class="wx-components-grid__item d-flex align-items-center justify-content-center flex-column cursor-pointer gap-8 rounded border p-16"
                    v-for="component in getComponentsInGroup(group)"
                    :key="component.name"
                    @click="() => appendComponent(component)"
                >
                    <wx-icon :name="component.icon" />

                    <div class="fw-semibold fs-14px text-center">{{ component.title }}</div>
                </div>
            </div>
        </wx-fieldset>
    </wx-side-panel>
    <wx-side-panel :size="500" v-model="sorting" :title="$t('page-composer.sort-components')">
        <wx-page-composer-sorting v-model="currentValue" />
    </wx-side-panel>
</template>

<style scoped lang="scss">
.wx-components-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(128px, 1fr));
    gap: 8px;
    align-items: stretch;

    &__item {
        aspect-ratio: 16/9;
        transition: background-color 0.2s var(--wx-easing);

        &:hover {
            background-color: var(--wx-light-gray);
        }

        :deep(.wx-icon) {
            width: 32px;
            height: 32px;
        }
    }
}
</style>
