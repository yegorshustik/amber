<script setup lang="ts">
import WxAlert from '../WxAlert';
import type { WxSnackbarItem } from './types';

const props = defineProps<{
    items: WxSnackbarItem[];
}>();

const emit = defineEmits<{
    (e: 'close', id: string): void;
}>();
</script>

<template>
    <div class="wx-snackbar-host">
        <TransitionGroup name="wx-snackbar" tag="div" class="wx-snackbar-stack">
            <div v-for="item in props.items" :key="item.id" class="wx-snackbar-item">
                <div class="wx-snackbar-clickable" @click="emit('close', item.id)">
                    <wx-alert :type="item.type">
                        {{ item.message }}
                    </wx-alert>
                </div>
            </div>
        </TransitionGroup>
    </div>
</template>

<style scoped lang="scss">
.wx-snackbar-host {
    position: fixed;
    left: 50%;
    bottom: 16px;
    transform: translateX(-50%);
    z-index: 1200;
    pointer-events: none;
    width: min(720px, calc(100vw - 32px));
}

.wx-snackbar-stack {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.wx-snackbar-item {
    pointer-events: auto;
}

.wx-snackbar-clickable {
    cursor: pointer;
}

/* Animations */
.wx-snackbar-enter-active,
.wx-snackbar-leave-active {
    transition: transform 200ms ease, opacity 200ms ease;
}
.wx-snackbar-enter-from,
.wx-snackbar-leave-to {
    opacity: 0;
    transform: translateY(10px);
}
</style>
