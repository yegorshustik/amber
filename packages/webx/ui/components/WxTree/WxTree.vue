<script setup lang="ts">
import type { Stat } from '@he-tree/tree-utils';
import { dragContext, Draggable, OpenIcon } from '@he-tree/vue';
import '@he-tree/vue/style/default.css';
import '@he-tree/vue/style/material-design.css';
import { computed, nextTick, onMounted, ref, watch } from 'vue';
import type { ApiResponse } from '@/types/api';
import { api } from '@/utils';
import WxCheck from '../WxCheck';
import WxInput from '../WxInput/WxInput.vue';
import type { WxTreeProps, WxTreeItem } from './types';

import { eachDraggable, eachDroppable, findNodeById, getExpandedKeys, loadTree, restoreExpandedState, updateExpandedState } from './utils';

const props = withDefaults(defineProps<WxTreeProps>(), {
    stateId: null,
    parentId: 0,
    checkable: false,
    draggable: false,
    selectable: false,
    searchable: false,
    type: 'default',
});

const emit = defineEmits(['update:modelValue', 'select', 'loaded']);

const defaultChecked = ref(props.modelValue || props.checked);
const innerTreeData = ref<WxTreeItem[]>([]);
const tree = ref();
const checkedNodes = ref([]);
const searchQuery = ref();
const selectedNode = ref(props.selectable ? defaultChecked : {});
const expandedKeys = getExpandedKeys(props.stateId);

const reloadTree = async (new_node: number, callback: any) => {
    innerTreeData.value = await loadTree(props.endpoint, true);

    emit('loaded', innerTreeData.value);

    const node = findNodeById(innerTreeData.value, new_node);

    setTimeout(() => {
        const stat = tree.value.getStat(node);
        tree.value.openNodeAndParents(stat);
        onClickNode(stat);

        if (callback) {
            callback();
        }
    }, 0);
};
defineExpose({ reloadTree });

onMounted(async () => {
    innerTreeData.value = props.treeData ? props.treeData : await loadTree(props.endpoint, true);
    emit('loaded', innerTreeData.value);

    await nextTick();

    if (expandedKeys.value.length > 0) {
        await restoreExpandedState(props.stateId, tree, innerTreeData.value);
    }
});

watch(
    () => props.modelValue,
    async (newVal) => {
        defaultChecked.value = newVal;
    },
);

const statHandler = (stat: Stat<any>) => {
    if (props.checkable && !props.selectable && defaultChecked.value && defaultChecked.value.length > 0) {
        stat.checked = defaultChecked.value.some((v: any) => v.id === stat.data.id && v.parent_id === stat.data.parent_id);
    }

    if (stat.data.open === true) {
        stat.open = true;
    }

    return stat;
};

const isSelected = (stat: Stat<any>) => {
    if (!props.checkable && props.selectable && defaultChecked.value) {
        return defaultChecked.value.id === stat.data.id;
    }

    return false;
};

const toggleTreeNode = (node: any, stat: Stat<any>) => {
    stat.open = !stat.open;

    updateExpandedState(props.stateId, node, stat);
};

const onCheckNode = () => {
    if (props.selectable) return;

    checkedNodes.value = tree.value.getChecked().map((v: any) => {
        return {
            id: v.data.id,
            parent_id: v.data.parent_id,
            title: v.data.title,
        };
    });

    emit('update:modelValue', checkedNodes.value);
};

const onClickNode = (stat: Stat<any>) => {
    if (!props.selectable) return;

    selectedNode.value = {
        id: stat.data.id,
        parent_id: stat.data.parent_id,
        title: stat.data.title,
    };

    emit('update:modelValue', selectedNode.value);
};

const afterDrop = async () => {
    if (!props.draggable) return;

    const siblings = dragContext.targetInfo.siblings.map((sibling) => sibling.data.id);

    const requestData = {
        node: dragContext.dragNode.data.id,
        parent: dragContext.dragNode.parent?.data.id,
        siblings,
    };

    await api.get<ApiResponse<WxTreeItem[]>>(props.endpoint, {
        action: 'move',
        ...requestData,
    });
};

const filteredData = computed({
    get() {
        if (!searchQuery.value) return innerTreeData.value;

        // Глубокая фильтрация дерева
        return filterTree(innerTreeData.value, searchQuery.value.toLowerCase());
    },
    set(value) {
        innerTreeData.value = value;
    },
});

// Рекурсивная функция для поиска вложенных элементов
function filterTree(nodes: WxTreeItem[], query: string): WxTreeItem[] {
    return nodes
        .map((node) => {
            const newNode = { ...node };

            const filteredChildren = newNode.children ? filterTree(newNode.children, query) : [];

            const matchesParent = newNode.title.toLowerCase().includes(query);
            const hasMatchingChild = filteredChildren.length > 0;

            if (hasMatchingChild) {
                newNode.children = filteredChildren;
                newNode.open = true;
                return newNode;
            }

            if (matchesParent) {
                return newNode;
            }

            return null;
        })
        .filter((node) => node !== null) as WxTreeItem[];
}
</script>

<template>
    <wx-input v-if="props.searchable" v-model="searchQuery" type="text" />

    <Draggable
        class="wx-tree mtl-tree"
        :class="[`wx-tree--${type}`]"
        :disableDrag="!props.draggable"
        :disableDrop="!props.draggable"
        :default-open="false"
        :eachDraggable="(stat) => eachDraggable(stat, props.parentId)"
        :eachDroppable="(stat) => eachDroppable(stat, props.parentId)"
        :statHandler="statHandler"
        updateBehavior="new"
        @check:node="onCheckNode"
        @after-drop="afterDrop"
        v-model="filteredData"
        ref="tree"
    >
        <template #default="{ node, stat }">
            <OpenIcon v-if="stat.children.length" :open="stat.open" class="mtl-mr" @click="toggleTreeNode(node, stat)" />
            <div v-else class="wx-tree__icon-placeholder"></div>

            <wx-check v-if="props.checkable && !props.selectable" v-model="stat.checked" class="me-8" />

            <div
                class="wx-tree__node d-flex justify-content-between align-items-center flex-grow-1 gap-16"
                :class="{ selected: isSelected(stat) }"
                @click="onClickNode(stat)"
            >
                <template v-if="$slots.title">
                    <div class="wx-tree__title">
                        <slot name="title" :node="node" :stat="stat" />
                    </div>
                </template>
                <template v-else>
                    <div class="wx-tree__title">
                        {{ node.title }}
                    </div>
                </template>

                <template v-if="$slots.actions">
                    <div class="wx-tree__actions flex-grow-1">
                        <slot name="actions" :node="node" :stat="stat" />
                    </div>
                </template>
            </div>
        </template>
    </Draggable>
</template>

<style lang="scss">
.wx-tree {
    &__icon-placeholder {
        position: relative;
        width: 18px;
        /*
        &:before {
            content: '';
            position: absolute;
            top: 50%;
            left: -5px;
            right:5px;
            height: 1px;
            background-color: #bbb;
        }*/
    }

    .tree-node {
        .tree-node-inner {
            padding: 2px 6px;
            border: 1px solid transparent;
            border-radius: 4px;
            cursor: pointer;

            .he-tree__open-icon {
            }
            .wx-tree__node {
            }

            .wx-checkbox {
                --wx-check-size: 16px;
                --wx-check-padding: 2px;
                --wx-check-radius: 4px;

                margin-right: 8px;
            }

            &:hover {
                border-color: var(--wx-light);
            }

            &:has(.wx-tree__node.selected) {
                border-color: var(--wx-primary);
                background-color: color-mix(in srgb, var(--wx-primary) 20%, white);
            }
        }

        &:hover {
            background: none;
        }

        &:not(:hover) {
        }
    }

    &__node {
    }

    .tree-node {
        border-radius: 4px;
    }

    .he-tree-drag-placeholder {
        padding: 3px 8px;
        border-radius: 4px;
    }

    &.wx-tree--data-tree {
        .tree-node {
            padding-bottom: 4px;

            .tree-node-inner {
                padding: 16px;
                border-radius: var(--wx-border-radius);
                background-color: var(--wx-white);
                box-shadow: var(--wx-box-shadow);

                &:has(.wx-tree__actions) {
                    padding: 8px;

                    @include media-breakpoint-up(lg) {
                        padding: 12px;
                    }
                }

                &:hover {
                    border-color: transparent;
                }

                .he-tree-drag-placeholder {
                    border: none;
                    background-color: transparent;
                }

                &:has(.he-tree-drag-placeholder) {
                    border: 1px dashed var(--wx-info);
                    background-color: color-mix(in srgb, var(--wx-info) 25%, white);
                }
            }
        }
    }
}
</style>
