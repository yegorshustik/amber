<script setup lang="ts">

import type { WxFieldsetProps } from './types';

const props = withDefaults(defineProps<WxFieldsetProps>(), {});

</script>

<template>
    <fieldset class="wx-fieldset">
        <legend class="wx-fieldset__legend" v-if="props.legend">{{ props.legend }}</legend>
        <div class="wx-fieldset__body"><slot></slot></div>
    </fieldset>
</template>

<style scoped lang="scss">
.wx-fieldset {
    --wx-fieldset-padding:1rem;

    margin-bottom: 16px;
    padding: calc(var(--wx-fieldset-padding) / 2);
    border:1px solid var(--wx-border-color);
    border-radius: var(--wx-border-radius-sm);

    &__legend {
        float:none;
        width: auto;
        margin: 0 0 0 calc(var(--wx-fieldset-padding) / 2);
        padding: 0 calc(var(--wx-fieldset-padding) / 2);
        font-weight: 600;
        font-size:16px;
    }

    &__body {

        .wx-fieldset:has(.wx-fieldset__legend) & {
            //padding: 0 calc(var(--wx-fieldset-padding) / 2);
        }

        >*:last-child {
            margin-bottom: 0 !important;
        }
    }
}
</style>
