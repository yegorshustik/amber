<script setup lang="ts">
import { RouterLink } from 'vue-router';
import type { WxDropdownLinkProps } from './types';

const props = withDefaults(defineProps<WxDropdownLinkProps>(), {});
</script>

<template>
    <router-link v-if="props.route" :to="props.route" class="wx-dropdown-link w-100">
        <slot name="before"></slot>
        <span class="me-auto"><slot /></span>
        <slot name="after"></slot>
    </router-link>
    <button v-else class="wx-dropdown-link w-100">
        <slot name="before"></slot>
        <span class="me-auto"><slot /></span>
        <slot name="after"></slot>
    </button>
</template>

<style scoped lang="scss">
.wx-dropdown-link {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 6px 16px;
    background: none;
    border: none;
    border-radius: 8px;
    text-align: left;
    font-size: 14px;
    font-weight: 600;
    color: var(--wx-dark);
    transition:
        background-color 0.2s ease-in-out,
        color 0.2s ease-in-out;

    &.active,
    &:hover {
        background-color: var(--wx-light-gray);
        color: var(--wx-primary);
    }
}
</style>
