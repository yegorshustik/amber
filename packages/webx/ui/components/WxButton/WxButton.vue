<script setup lang="ts">
import { RouterLink } from 'vue-router';
import WxAction from '../WxAction/WxAction.vue';
import type { WxButtonProps } from './types';

const props = withDefaults(defineProps<WxButtonProps>(), {
    theme: 'default',
    type: 'button',
    size: 'lg',
    square: false,
});
</script>

<template>
    <RouterLink
        v-if="props.route"
        :to="props.route"
        :class="['wx-button', props.square ? 'wx-button-square' : '', `wx-button-${props.size}`, `wx-button-${props.theme}`]"
    >
        <slot />
    </RouterLink>
    <button v-else-if="props.theme === 'create'" class="wx-button-wide-line" :type="props.type">
        <span class="wx-button-wide-line__inner"><wx-action type="add" /></span>
    </button>
    <button
        v-else
        :type="props.type"
        :class="['wx-button', props.square ? 'wx-button-square' : '', `wx-button-${props.size}`, `wx-button-${props.theme}`]"
    >
        <slot />
    </button>
</template>

<style scoped lang="scss">
.wx-button-wide-line {
    display: flex;
    justify-content: center;
    width: 100%;
    border: 0;
    background: transparent;
    padding: 0;
    position: relative;

    &:before {
        z-index: 2;
        content: '';
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 1px;
        background-color: var(--wx-light-gray);
        transition: background-color 200ms var(--wx-easing);
    }

    &__inner {
        z-index: 3;
        position: relative;
        padding: 0 0.5rem;
        background-color: var(--wx-body-bg);

        .bg-white & {
            background-color: var(--wx-white);
        }
    }

    &:hover {
        &:before {
            background-color: var(--wx-light-gray-hover);
        }
    }
}

.wx-button {
    --wx-button-padding-x: var(--wx-button-lg-padding-x);
    --wx-button-padding-y: var(--wx-button-lg-padding-y);
    --wx-button-font-size: var(--wx-button-lg-font-size);
    --wx-button-font-weight: var(--wx-button-lg-font-weight);
    --wx-button-border-radius: var(--wx-button-lg-border-radius);

    --wx-button-sm-size: 28px;
    --wx-button-sm-padding-x: 12px;
    --wx-button-sm-padding-y: 4px;
    --wx-button-sm-font-size: 12px;
    --wx-button-sm-font-weight: 600;
    --wx-button-sm-border-radius: 8px;

    --wx-button-md-size: 34px;
    --wx-button-md-padding-x: 14px;
    --wx-button-md-padding-y: 4px;
    --wx-button-md-font-size: 14px;
    --wx-button-md-font-weight: 600;
    --wx-button-md-border-radius: 10px;

    --wx-button-lg-size: 38px;
    --wx-button-lg-padding-x: 1rem;
    --wx-button-lg-padding-y: 0.4rem;
    --wx-button-lg-font-size: 1rem;
    --wx-button-lg-font-weight: 600;
    --wx-button-lg-border-radius: 10px;

    --wx-button-xl-size: 44px;
    --wx-button-xl-padding-x: 32px;
    --wx-button-xl-padding-y: 8px;
    --wx-button-xl-font-size: 18px;
    --wx-button-xl-font-weight: 600;
    --wx-button-xl-border-radius: 12px;

    @each $button-theme, $button-colors in $buttons {
        @each $button-state, $button-color in $button-colors {
            @each $prop, $color in $button-color {
                --wx-button-#{$button-theme}-#{$button-state}-#{$prop}: #{$color};
            }
        }
    }

    display: inline-flex;
    text-align: center;
    justify-content: center;
    padding: var(--wx-button-padding-y) var(--wx-button-padding-x);
    background: var(--wx-button-background);
    border: 1px solid var(--wx-button-border);
    border-radius: var(--wx-button-border-radius);
    font-size: var(--wx-button-font-size);
    font-weight: var(--wx-button-font-weight);
    color: var(--wx-button-color);
    transition:
        background-color 200ms ease-in-out,
        border-color 200ms ease-in-out,
        color 200ms ease-in-out;

    &[disabled],
    &.disabled {
        cursor: not-allowed;
    }

    @each $size in (sm, md, lg, xl) {
        &-#{$size} {
            --wx-button-size: var(--wx-button-#{$size}-size);
            --wx-button-padding-x: var(--wx-button-#{$size}-padding-x);
            --wx-button-padding-y: var(--wx-button-#{$size}-padding-y);
            --wx-button-font-size: var(--wx-button-#{$size}-font-size);
            --wx-button-font-weight: var(--wx-button-#{$size}-font-weight);
            --wx-button-border-radius: var(--wx-button-#{$size}-border-radius);
        }
    }

    &-back {
        --wx-button-padding-y: 0;
        --wx-button-padding-x: 0;

        display: flex;
        align-items: center;
        justify-content: center;
        width: var(--wx-button-size);
        height: var(--wx-button-size);

        :deep(svg) {
            width: 20px;
            height: 20px;
        }
    }

    &-square {
        --wx-button-padding-y: 0;
        --wx-button-padding-x: 0;

        width: var(--wx-button-size);
        height: var(--wx-button-size);
        align-items: center;
        justify-content: center;
    }

    @each $button-theme, $button-colors in $buttons {
        &-#{$button-theme} {
            @each $button-state, $button-color in $button-colors {
                @if $button-state == 'initial' {
                    @each $prop, $color in $button-color {
                        --wx-button-#{$prop}: var(--wx-button-#{$button-theme}-#{$button-state}-#{$prop});
                    }
                } @else {
                    &:#{$button-state},
                    &.#{$button-state} {
                        @each $prop, $color in $button-color {
                            --wx-button-#{$prop}: var(--wx-button-#{$button-theme}-#{$button-state}-#{$prop});
                        }
                    }
                }
            }
        }
    }
}
</style>
