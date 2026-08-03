<script setup lang="ts">
import { inject, onUnmounted, ref } from 'vue';
import { VueDraggableNext } from 'vue-draggable-next';
import WxIcon from '../../components/WxIcon/WxIcon.vue';
import type { WxPageComposerComponent, WxPageComposerContext, WxPageComposerSortingProps } from './types';
import WxPageComposerSorting from './WxPageComposerSorting.vue';

const props = withDefaults(defineProps<WxPageComposerSortingProps>(), {});

const children = ref(props.modelValue);

const emit = defineEmits(['update:modelValue']);

const pageComposerContext = inject<WxPageComposerContext>('pageComposerContext');

const sorted = () => {
    emit('update:modelValue', children.value);
};

onUnmounted(() => {
    setTimeout(() => {
        document.querySelectorAll('.wx-component').forEach((el) => {
            el.classList.remove('highlight');
        });
    }, 0)
});

const getFromRegistry = (id: string) => {
    return pageComposerContext.findComponent(id);
};
const highlightComponent = (component: WxPageComposerComponent) => {
    document.querySelectorAll('.wx-component[data-id="' + component.id + '"]').forEach((el) => {
        el.classList.add('highlight');
    });
};
const restoreComponent = (component: WxPageComposerComponent) => {
    document.querySelectorAll('.wx-component[data-id="' + component.id + '"]').forEach((el) => {
        el.classList.remove('highlight');
    });
};
</script>

<template>
    <VueDraggableNext tag="ul" class="wx-page-composer-sorting" @change="() => sorted()" :animation="150" v-model="children" :group="{ name: 'g1' }">
        <li v-for="child in children" :key="child.id">
            <div @mouseover="highlightComponent(child)" @mouseout="restoreComponent(child)" class="d-flex align-items-center gap-16">
                {{ child.name }}

                <div class="d-flex align-items-center ms-auto gap-8">
                    <div
                        class="text-primary"
                        v-if="getFromRegistry(child.name).features.includes('edit')"
                        @click="pageComposerContext.startEditing(child)"
                    >
                        <wx-icon name="edit" />
                    </div>

                    <div
                        class="text-danger ms-auto"
                        v-if="getFromRegistry(child.name).features.includes('remove')"
                        @click="pageComposerContext.startRemoving(child)"
                    >
                        <wx-icon name="remove" />
                    </div>
                </div>
            </div>
            <wx-page-composer-sorting v-if="getFromRegistry(child.name).features.includes('droppable')" v-model="child.children" />
        </li>
    </VueDraggableNext>
</template>

<style scoped lang="scss">
.wx-page-composer-sorting {
    list-style: none;
    margin: 0;
    padding: 0;

    :deep(> li) {
        > div {
            margin-bottom: 4px;
            padding: 4px 12px;
            border: 1px solid var(--wx-border-color);
            border-radius: var(--wx-border-radius-sm);
            font-weight: 500;
            transition: background-color 200ms var(--wx-easing);
            cursor: pointer;

            &:hover {
                background-color: var(--wx-lightest);
            }
        }

        > ul {
            margin-left: 24px;
        }
    }
}
</style>
