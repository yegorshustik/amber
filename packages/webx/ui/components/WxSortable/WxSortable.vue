<script setup lang="ts">
import { ref, watch } from 'vue';
import { VueDraggableNext } from 'vue-draggable-next';
import type { WxSortableProps } from './types';

const props = withDefaults(defineProps<WxSortableProps>(), {
    handle: '.handle',
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
    <VueDraggableNext class="wx-sortable" @change="() => change()" :handle="props.handle" :animation="150" v-model="innerValue">
        <div class="wx-sortable__row d-flex align-items-center gap-16 p-4" v-for="(item, index) in innerValue" :key="index">
            <div class="wx-sortable__content flex-grow-1">
                <slot name="content" :item="item" :index="index" />
            </div>
            <div v-if="$slots.actions" class="wx-sortable__actions">
                <slot name="actions" :item="item" :index="index" />
            </div>
        </div>
    </VueDraggableNext>
</template>

<style scoped lang="scss">
.wx-sortable {
    &__row {
        &:not(:last-child) {
            border-bottom: 1px solid var(--wx-border-color);
        }
    }
}
</style>
