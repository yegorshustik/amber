<script setup lang="ts">
import { onMounted, ref, watch } from 'vue';
import WxDropdown from '../WxDropdown/WxDropdown.vue';
import type { WxTreeItem } from '../WxTree';
import WxTree from '../WxTree';
import { flattenTreeToMap, loadTree } from '../WxTree/utils';
import type { WxSelectTreeProps } from './types';

const props = withDefaults(defineProps<WxSelectTreeProps>(), {
    multiple: false,
    searchable: false,
});

const emit = defineEmits(['update:modelValue', 'change']);

const currentValue = ref(props.modelValue ?? props.value);
const treeData = ref<WxTreeItem[]>([]);
const selected = ref<WxTreeItem | WxTreeItem[]>();

onMounted(async () => {
    treeData.value = await loadTree(props.endpoint);

    if (currentValue.value) {
        const flatTree = flattenTreeToMap(treeData.value);

        if (props.multiple) {
            selected.value = currentValue.value.map((id: number) => flatTree[id]);
        } else {
            selected.value = flatTree[currentValue.value];
        }
    }
});

watch(
    () => [props.modelValue, props.value],
    () => {
        currentValue.value = props.modelValue ?? props.value;
    },
    { deep: true },
);

watch(
    () => selected,
    () => {
        emit('update:modelValue', selected.value);
        emit('change', selected.value);
    },
    { deep: true },
);

const removeSelected = (node: WxTreeItem) => {
    if (props.multiple) {
        selected.value = (selected.value as WxTreeItem[]).filter((n) => n.id !== node.id);
    } else {
        selected.value = {} as WxTreeItem;
    }
};
</script>

<template>
    <template v-if="name && props.multiple">
        <select v-if="selected" :name="name" :multiple="multiple" class="wx-native-hidden-select" tabindex="-1" aria-hidden="true">
            <option v-for="node in selected as WxTreeItem[]" :key="node.id" :value="node.id" :selected="(selected as WxTreeItem[])?.includes(node)">
                {{ node.title }}
            </option>
        </select>
        <input type="hidden" v-else :name="name.replace('[]', '')" value="" />
    </template>
    <select v-if="name && !props.multiple" :name="name" class="wx-native-hidden-select" tabindex="-1" aria-hidden="true">
        <option v-if="selected" :value="(selected as WxTreeItem)?.id" selected>
            {{ (selected as WxTreeItem).title }}
        </option>
    </select>

    <wx-dropdown ref="dropdownRef" v-if="treeData.length > 0" :closeOnClick="false" class="wx-select-tree">
        <template #trigger>
            <div
                class="wx-select-tree__trigger d-flex align-items-center"
                :class="{
                    multiple: props.multiple,
                    filled: !props.multiple ? !!(selected as WxTreeItem)?.id : (selected as WxTreeItem[])?.length > 0,
                }"
            >
                <div class="wx-select-tree__values flex-grow-1">
                    <template v-if="multiple">
                        <template v-if="(selected as WxTreeItem[])?.length > 0">
                            <div class="wx-select-tree__tags">
                                <span v-for="(node, index) in selected" :key="index" class="wx-select-tree__tag">
                                    {{ (node as WxTreeItem).title }}
                                    <i class="remove" @click.stop="removeSelected(node)">×</i>
                                </span>
                            </div>
                        </template>
                        <span v-else class="placeholder">{{ props.placeholder ?? 'Select' }}</span>
                    </template>
                    <template v-else>
                        <div class="wx-select-tree__value">
                            <template v-if="$slots.value">
                                <slot name="value" :selected="selected" />
                            </template>
                            <template v-else>
                                {{ (selected as WxTreeItem)?.title ?? props.placeholder ?? 'Select' }}
                            </template>
                        </div>
                    </template>
                </div>
                <div class="wx-select-tree__icon flex-shrink-0">
                    <svg width="10" height="6" viewBox="0 0 10 6" fill="none">
                        <path d="M1 1L5 5L9 1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
            </div>
        </template>

        <template #body>
            <div class="wx-select-tree-content">
                <wx-tree
                    draggable
                    searchable
                    :selectable="!props.multiple"
                    :checkable="props.multiple"
                    :state-id="props.stateId"
                    :tree-data="treeData"
                    :endpoint="props.endpoint"
                    v-model="selected"
                />
            </div>
        </template>
    </wx-dropdown>
</template>

<style scoped lang="scss">
.wx-select-tree {
    --wx-input-padding-y: 2px;

    width: 100%;

    &__values {
        //max-height: calc(var(--wx-input-size) * 4);
        //@include scrollbar;
    }

    &__trigger {
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        width: 100%;
        min-height: var(--wx-input-size);
        padding: var(--wx-input-padding-y) var(--wx-input-padding-x);
        border-radius: var(--wx-input-radius);
        border: 1px solid var(--wx-input-border);
        background-color: var(--wx-input-background);
        box-shadow: none;
        color: var(--wx-input-color);
        transition:
            background-color 200ms var(--wx-easing),
            border-color 200ms var(--wx-easing),
            color 200ms var(--wx-easing);

        .placeholder {
            color: var(--wx-input-placeholder);
        }

        &:hover {
            --wx-input-border: var(--wx-input-hover-border);
            --wx-input-background: var(--wx-input-hover-background);
            --wx-input-color: var(--wx-input-hover-color);
        }

        .wx-dropdown.opened & {
            --wx-input-border: var(--wx-input-focus-border);
            --wx-input-background: var(--wx-input-focus-background);
            --wx-input-color: var(--wx-input-focus-color);
        }

        &.filled {
            --wx-input-border: var(--wx-input-filled-border);
            --wx-input-background: var(--wx-input-filled-background);
            --wx-input-color: var(--wx-input-filled-color);
        }

        .form-control-container.invalid &,
        &.invalid {
            --wx-input-border: var(--wx-input-invalid-border);
            --wx-input-background: var(--wx-input-invalid-background);
            --wx-input-color: var(--wx-input-invalid-color);
        }
    }

    &__icon {
        transition: rotate 200ms var(--wx-easing);

        .wx-dropdown.opened & {
            rotate: 180deg;
        }
    }

    &__value {
        transition: color 200ms var(--wx-easing);

        .wx-select-tree__trigger:not(.filled) & {
            color: var(--wx-input-placeholder);
        }
    }

    &__tags {
        display: flex;
        flex-wrap: wrap;
        gap: 2px;
        margin-left: calc((var(--wx-input-padding-x) - 4px) * -1);
    }

    &__tag {
        align-items: center;
        background: var(--wx-primary-light, #eef6ff);
        color: var(--wx-primary, #007bff);
        padding: 6px 12px;
        border-radius: calc(var(--wx-input-radius) - 2px);
        margin-right: 2px;
        font-size: 14px;

        .remove {
            margin-left: 6px;
            cursor: pointer;
            &:hover {
                color: #000;
            }
        }
    }
}

.wx-select-tree-content {
    max-height: 300px;
    @include scrollbar;
}

</style>
