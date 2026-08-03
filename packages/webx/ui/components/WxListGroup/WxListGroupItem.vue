<script setup lang="ts">
import type { WxListGroupItemProps } from './types';

const { active = false } = defineProps<WxListGroupItemProps>();
</script>

<template>
    <div class="wx-list-group-item" :class="{ active: active }">
        <slot></slot>
    </div>
</template>

<style scoped lang="scss">
.wx-list-group-item {
    background-color: transparent;
    border: none;
    color: var(--wx-text-color);
    cursor: pointer;
    padding: 8px 16px;
    white-space: nowrap;
    border-radius: calc(var(--wx-border-radius) - 4px);
    font-size: 16px;
    font-weight: 600;
    transition: all 0.2s var(--wx-easing);

    &:hover {
        background-color: var(--wx-light-gray);
    }

    &.active {
        background-color: var(--wx-primary);
        color: var(--wx-white);
    }
}
</style>
