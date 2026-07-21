<script setup lang="ts">
import { inject, onMounted, onUnmounted, ref, watch } from 'vue';
import { wxBuffer, wxConfirm } from '@/utils';
import type { WxPageComposerCanvasProps, WxPageComposerContext, WxPageComposerComponent as ComponentType } from './types';
import WxPageComposerContent from './WxPageComposerContent.vue';
import WxActions from '../../components/WxActions/WxActions.vue';
import WxAction from '../../components/WxAction/WxAction.vue';
import { $t } from '@/locales';

const props = withDefaults(defineProps<WxPageComposerCanvasProps>(), {});

const children = ref(props.children);
const buffer = ref();
const { pull } = wxBuffer();

watch(() => props.children, () => {
    children.value = props.children;
})

const pageComposerContext = inject<WxPageComposerContext>('pageComposerContext');

const removeComponent = (component: ComponentType) => {
    wxConfirm().then(() => {
        const index = props.children.findIndex((item) => item.id === component.id);

        if (index !== -1) {
            // eslint-disable-next-line vue/no-mutating-props
            props.children.splice(index, 1);
        }
    });
};

const bufferUpdated = () => {
    buffer.value = pull();
}

onMounted(() => {
    bufferUpdated();
    window.addEventListener('wxBufferUpdated', () => bufferUpdated())
    window.addEventListener('storage', () => bufferUpdated())
})

onUnmounted(() => {
    window.removeEventListener('storage', () => bufferUpdated())
    window.removeEventListener('wxBufferUpdated', () => bufferUpdated())
});
</script>

<template>
    <div class="wx-page-composer-canvas">
        <div class="wx-page-composer-canvas__components d-flex flex-column gap-16">
            <wx-page-composer-content
                v-for="(child, index) in children"
                :key="child.id"
                v-model:component="children[index]"
                @add="(after) => pageComposerContext.addComponent(props.children, after)"
                @paste="(after, buffer) => pageComposerContext.pasteComponent('after', props.children, after, buffer.content)"
                :registry="pageComposerContext.findComponent(child.name)"
                @remove="(item) => removeComponent(item)"
            />
        </div>

        <div class="mt-8 d-flex justify-content-center" >
            <wx-actions>
                <wx-action :data-tooltip="$t('page-composer.add')" type="add" @click="() => pageComposerContext.addComponent(props.children)"  />
                <wx-action :data-tooltip="$t('page-composer.copy')" type="copy" @click="() => pageComposerContext.copyComponent(props.children)"  />
                <wx-action v-if="buffer" :data-tooltip="$t('page-composer.paste')" type="paste" @click="() => pageComposerContext.pasteComponent('append', props.children, null, buffer.content)"  />
            </wx-actions>
        </div>
    </div>
</template>

<style scoped lang="scss"></style>
