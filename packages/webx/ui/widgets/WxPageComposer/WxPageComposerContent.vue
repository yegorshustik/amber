<script setup lang="ts">
import { computed, inject, onBeforeMount, onMounted, onUnmounted, ref, watch } from 'vue';
import WxIcon from '../../components/WxIcon/WxIcon.vue';
import type { WxPageComposerComponent, WxPageComposerComponentProps, WxPageComposerContext } from './types';
import { $t } from '@/locales';
import { wxBuffer } from '@/utils';

const props = withDefaults(defineProps<WxPageComposerComponentProps>(), {});

const buffer = ref();
const editComponent = ref<boolean>(false);
const component = ref<WxPageComposerComponent>(props.component);
const emit = defineEmits(['remove', 'add', 'paste']);

const { pull } = wxBuffer();

onBeforeMount(() => {
    component.value.content = { ...props.registry.defaults, ...props.component.content };
});

const pageComposerContext = inject<WxPageComposerContext>('pageComposerContext');

const isBeingEdited = computed(() => {
    return pageComposerContext.editingId.value === (props.component as WxPageComposerComponent).id
});
const isBeingRemoving = computed(() => {
    return pageComposerContext.removingId.value === (props.component as WxPageComposerComponent).id
});
watch(() => isBeingEdited.value, (newValue) => {
    if (newValue) {
        editComponent.value = true;
    }
})
watch(() => isBeingRemoving.value, (newValue) => {
    if (newValue) {
        emit('remove', props.component)
    }
})
watch(() => editComponent.value, (newValue) => {
    if (newValue === false) {
        pageComposerContext.editingId.value = null;
    }
})


const bufferUpdated = () => {
    buffer.value =  pull();
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
    <div class="wx-component" :class="{ active: editComponent }" :data-id="component.id">
        <div class="wx-component__head d-flex align-items-center py-2">
            <div class="fs-14px fw-semibold">{{ props.registry.title }}</div>

            <div class="wx-component__actions d-flex ms-16 gap-4">
                <button :data-tooltip="$t('sort')" class="wx-component__action text-primary" type="button" @click="pageComposerContext.sortingMode()">
                    <wx-icon name="sort" />
                </button>
                <button :data-tooltip="$t('add-after')" v-if="props.registry.features.includes('add')" class="wx-component__action text-primary p-0" type="button" @click="emit('add', props.component)">
                    <wx-icon name="add" width="24" heigt="24" />
                </button>
                <button :data-tooltip="$t('edit')" v-if="props.registry.features.includes('edit')" class="wx-component__action text-primary" type="button" @click="editComponent = !editComponent">
                    <wx-icon name="edit" />
                </button>
                <button :data-tooltip="$t('copy')" class="wx-component__action text-primary" type="button" @click="pageComposerContext.copyComponent([component] as WxPageComposerComponent[])">
                    <wx-icon name="copy" />
                </button>
                <button v-if="buffer" :data-tooltip="$t('paste')" class="wx-component__action text-primary" type="button" @click="emit('paste', component as WxPageComposerComponent, buffer)">
                    <wx-icon name="paste" />
                </button>
                <button :data-tooltip="$t('remove')" v-if="props.registry.features.includes('remove')" class="wx-component__action text-danger" type="button" @click="() => emit('remove', props.component)">
                    <wx-icon name="remove" />
                </button>
            </div>
        </div>

        <div class="wx-component__body">
            <component :is="props.registry.component" v-model:component="component" v-model:edit="editComponent" />
        </div>
    </div>
</template>

<style scoped lang="scss">
.wx-component {
    --wx-component-padding-x: 16px;
    --wx-component-padding-y: 16px;
    --wx-component-radius: var(--wx-border-radius);
    --wx-component-background: transparent;
    --wx-component-border: var(--wx-border-color);

    --wx-component-hover-background: color-mix(in srgb, var(--wx-info) 5%, white);
    --wx-component-hover-border: var(--wx-border-color);
    --wx-component-highlight-background: color-mix(in srgb, var(--wx-danger) 5%, white);
    --wx-component-highlight-border: var(--wx-danger);

    position: relative;
    padding: var(--wx-component-padding-y) var(--wx-component-padding-x);
    background-color: var(--wx-component-background);
    border: 1px dashed var(--wx-component-border);
    border-radius: var(--wx-component-radius);

    :deep(.wx-button-wide-line__inner) {
        background-color: var(--wx-component-background);
    }

    &:not(:has(.wx-component.highlight)).highlight {
        z-index: 99;

        --wx-component-background: var(--wx-component-highlight-background);
        --wx-component-border: var(--wx-component-highlight-border);
    }


    &:not(:has(.wx-component:active, .wx-component.active)).active,
    &:not(:has(.wx-component:hover, .wx-component.hover)).hover,
    &:not(:has(.wx-component:hover, .wx-component.hover)):hover {
        z-index: 99;

        --wx-component-background: var(--wx-component-hover-background);
        --wx-component-border: var(--wx-component-hover-border);

        border-top-left-radius: 0;
        border-style: solid;

        > .wx-component__head {
            opacity: 1;
            visibility: visible;
        }
    }

    &__action {
        aspect-ratio: 1;
        flex-shrink: 0;
        width: 24px;
        border: none;
        background: transparent;
    }

    &__head {
        position: absolute;
        bottom: 100%;
        left: -1px;
        padding: 2px 4px 2px var(--wx-component-padding-x);
        border-radius: var(--wx-component-radius) var(--wx-component-radius) 0 0;
        background-color: var(--wx-component-background);
        border-top: 1px solid var(--wx-component-border);
        border-right: 1px solid var(--wx-component-border);
        border-left: 1px solid var(--wx-component-border);
        opacity: 0;
        visibility: hidden;
    }
}
</style>
