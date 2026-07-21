<script setup lang="ts">
import type { WxAlertProps } from './types';

const props = withDefaults(defineProps<WxAlertProps>(), {
    type  : 'success'
});
</script>

<template>
    <div :class="['wx-alert', 'p-8 p-lg-16 rounded-2 fw-bold', `wx-alert--${props.type}`]">
        <slot />
    </div>
</template>

<style scoped lang="scss">
.wx-alert {
    border-left: 3px solid var(--wx-alert-border);
    background-color: var(--wx-alert-background);
    color: var(--wx-alert-color);

    /* Насколько "высветлять" базовый цвет (без прозрачности) */
    --wx-alert-tint: 15%;

    &--info {
        --wx-alert-color: var(--wx-info);
        --wx-alert-border: var(--wx-info);
        --wx-alert-background: color-mix(in srgb, var(--wx-info) var(--wx-alert-tint), white);
    }

    &--success {
        --wx-alert-border: var(--wx-success);
        --wx-alert-color: var(--wx-success);
        --wx-alert-background: color-mix(in srgb, var(--wx-success) var(--wx-alert-tint), white);
    }

    &--warning {
        --wx-alert-border: var(--wx-warning);
        --wx-alert-color: var(--wx-warning);
        --wx-alert-background: color-mix(in srgb, var(--wx-warning) var(--wx-alert-tint), white);
    }

    &--danger {
        --wx-alert-border: var(--wx-danger);
        --wx-alert-color: var(--wx-danger);
        --wx-alert-background: color-mix(in srgb, var(--wx-danger) var(--wx-alert-tint), white);
    }
}
</style>
