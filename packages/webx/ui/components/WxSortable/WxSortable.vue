<script setup lang="ts">
import { ref, watch } from 'vue';
import { VueDraggableNext } from 'vue-draggable-next';
import type { WxSortableProps } from './types';

const props = withDefaults(defineProps<WxSortableProps>(), {
    handle: '.handle',
    cards: false,
});

const emit = defineEmits(['update:modelValue', 'sorted']);

const innerValue = ref(props.modelValue);

defineSlots<{
    content(props: { item: any; index: number }): any;
    actions(props: { item: any; index: number }): any;
}>();

watch(
    () => innerValue,
    () => {
        emit('update:modelValue', innerValue.value);
        emit('sorted', innerValue.value);
    },
    { deep: true },
);

watch(
    () => props.modelValue,
    () => {
        innerValue.value = props.modelValue;
    },
    { deep: true },
);

const change = () => {
    emit('update:modelValue', innerValue.value);
    emit('sorted', innerValue.value);
};
</script>

<template>
    <VueDraggableNext
        :class="['wx-sortable', props.cards ? 'wx-sortable--cards' : '']"
        @change="() => change()"
        :handle="props.handle"
        :animation="150"
        v-model="innerValue"
    >
        <div class="wx-sortable__row d-flex gap-16 p-4" v-for="(item, index) in innerValue" :key="index">
            <div class="wx-sortable__content flex-grow-1">
                <slot name="content" :item="item" :index="index as number" />
            </div>
            <div v-if="$slots.actions" class="wx-sortable__actions">
                <slot name="actions" :item="item" :index="index as number" />
            </div>
        </div>
    </VueDraggableNext>
</template>

<style scoped lang="scss">
.wx-sortable {
    &:not(.wx-sortable--cards) {
        .wx-sortable__row {
            align-items: center;
            &:not(:last-child) {
                border-bottom: 1px solid var(--wx-border-color);
            }
        }
    }

    &.wx-sortable--cards {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 16px;

        .wx-sortable__row {
            flex-direction: column-reverse;
            border: 1px solid var(--wx-border-color);
            border-radius: var(--wx-border-radius);
            background: var(--wx-white);

            .wx-sortable__content {
                padding: 0 12px 12px 12px;
            }

            .wx-sortable__actions {
                display: flex;
                justify-content: flex-end;
            }
        }
    }
}
</style>
