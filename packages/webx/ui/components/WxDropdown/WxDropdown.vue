<script setup lang="ts">
import { createPopper } from '@popperjs/core';
import { ref, onMounted, onUnmounted, watch, nextTick } from 'vue';
import type { WxDropdownProps } from './types';

const {
    placement = 'bottom-start',
    offset = [0, 8],
    closeOnClick = true
} = defineProps<WxDropdownProps>();

const isOpen = ref(false);
const triggerRef = ref<HTMLElement | null>(null);
const contentRef = ref<HTMLElement | null>(null);
let popperInstance: any = null;

const toggle = () => (isOpen.value = !isOpen.value);
const close = () => (isOpen.value = false);

const notifyOpen = () => {
    window.dispatchEvent(new CustomEvent('wx-dropdown-opened', { detail: contentRef.value }));
};

const initPopper = () => {
    if (triggerRef.value && contentRef.value) {
        popperInstance?.destroy();

        popperInstance = createPopper(triggerRef.value, contentRef.value, {
            placement: placement,
            modifiers: [
                { name: 'offset', options: { offset: offset } },
                { name: 'preventOverflow', options: { padding: 8 } },
            ],
        });
    }
};

watch(isOpen, async (state) => {
    if (state) {
        await nextTick();

        if (isOpen.value) {
            notifyOpen();
        }

        if (triggerRef.value && contentRef.value) {
            initPopper();
        }
    }
});

const handleClickOutside = (event: MouseEvent) => {
    const target = event.target as Node;
    const isClickInsideTrigger = triggerRef.value?.contains(target);
    const isClickInsideContent = contentRef.value?.contains(target);

    if (isOpen.value && !isClickInsideTrigger) {
        if (isClickInsideContent && !closeOnClick) return;
        close();
    }
};
const handleResize = () => {
    if (isOpen.value) {
        close();
    }
};
const handleGlobalOpen = (e: any) => {
    if (e.detail !== contentRef.value) {
        close();
    }
};

onMounted(() => {
    window.addEventListener('resize', handleResize);
    window.addEventListener('wx-dropdown-opened', handleGlobalOpen);
    document.addEventListener('click', handleClickOutside)
});

onUnmounted(() => {
    window.removeEventListener('resize', handleResize);
    document.removeEventListener('click', handleClickOutside);
    window.removeEventListener('wx-dropdown-opened', handleGlobalOpen);
    popperInstance?.destroy();
});
</script>

<template>
    <div class="wx-dropdown" :class="{ 'opened': isOpen }">
        <div ref="triggerRef" class="wx-dropdown__trigger cursor-pointer" @click.stop="toggle">
            <slot name="trigger" :is-open="isOpen" />
        </div>

        <Teleport to="body">
            <div v-if="isOpen" ref="contentRef" class="wx-dropdown__content bg-white shadow p-6 rounded-2 d-flex flex-column gap-2">
                <slot name="body" :close="close" />
            </div>
        </Teleport>
    </div>
</template>

<style scoped lang="scss">
.wx-dropdown {
    &__content {
        z-index: 1000;
        position: absolute;
        min-width: 100px;
    }
}

/* Базовая анимация появления */
.wx-dropdown-fade-enter-active,
.wx-dropdown-fade-leave-active {
    transition:
        opacity 0.2s ease,
        transform 0.2s ease;
}
.wx-dropdown-fade-enter-from,
.wx-dropdown-fade-leave-to {
    opacity: 0;
    transform: translateY(-10px);
}
</style>
