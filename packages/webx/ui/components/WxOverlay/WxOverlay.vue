<script setup lang="ts">
import { computed, onUnmounted, watch } from 'vue';
import type { WxOverlayProps } from './types';
import { useStack } from './useStack';

const props = withDefaults(defineProps<WxOverlayProps>(), {
    modelValue: false,
    persistent: false,
    lockScroll: false,

    closeOnOverlay: true,
    closeOnEscape: true,

    teleportTo: 'body',
    overlayClass: 'wx-overlay',
    contentClass: 'wx-overlay__content',
    overlayTransition: 'wx-overlay-fade',
    contentTransition: 'wx-overlay-content',
    contentAppear: true,
    zIndex: 1000,
});

const emit = defineEmits<{
    (e: 'update:modelValue', value: boolean): void;
}>();

const { registerOverlay, unregisterOverlay, closeLast } = useStack();
const overlayId = Math.random().toString(36).substr(2, 9);

const close = () => emit('update:modelValue', false);

const onOverlayClick = () => {
    if (props.persistent) return;
    if (!props.closeOnOverlay) return;

    close();
};
const handleEsc = (e: KeyboardEvent) => {
    if (e.key === 'Escape') {
        if (closeLast() === overlayId) {
            close();
        }
    }
};

watch(() => props.modelValue, (isOpen) => {
    if (isOpen) {
        registerOverlay(overlayId);
        window.addEventListener('keydown', handleEsc);
    } else {
        unregisterOverlay(overlayId);
        window.removeEventListener('keydown', handleEsc);
    }
}, { immediate: true });

onUnmounted(() => {
    unregisterOverlay(overlayId);
    window.removeEventListener('keydown', handleEsc);
});

/*
useEscapeClose({
    enabled: () => props.modelValue && props.closeOnEscape,
    persistent: () => props.persistent,
    onClose: close,
});
 */
//useBodyScrollLock(() => props.modelValue && props.lockScroll);

const mergedOverlayStyle = computed(() => {
    const zIndexStyle = { zIndex: props.zIndex };
    if (!props.overlayStyle) return zIndexStyle;
    return [zIndexStyle, props.overlayStyle];
});
</script>

<template>
    <Teleport :to="teleportTo">
        <Transition :name="overlayTransition">
            <div
                v-if="modelValue"
                :class="overlayClass"
                :style="mergedOverlayStyle"
                @click.self="onOverlayClick"
            >
                <Transition :name="contentTransition" :appear="contentAppear">
                    <div :class="contentClass" :style="contentStyle">
                        <slot :close="close" />
                    </div>
                </Transition>
            </div>
        </Transition>
    </Teleport>
</template>

<style scoped lang="scss">
.wx-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.4);
    display: flex;
    justify-content: center;
    padding: 16px;
    overflow: auto;
}

.wx-overlay__content {
    margin-block: auto;
}
</style>
