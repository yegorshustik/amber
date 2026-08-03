<script setup lang="ts">
import { computed } from 'vue';
import type { WxGridProps } from './types';

const props = withDefaults(defineProps<WxGridProps>(), {
    gap : 16
});

const gridStyles = computed(() => ({
    '--wx-grid-gap' : `${props.gap}px`,
}))

</script>

<template>
    <div class="grid-wrapper" :style="gridStyles">
        <div class="grid">
            <slot></slot>
        </div>
    </div>
</template>

<style scoped lang="scss">
.grid-wrapper {
    container-type: inline-size;
    width: 100%;

    >.grid {
        display: grid;
        grid-template-columns: repeat(12, 1fr);
        column-gap: var(--wx-grid-gap);
    }
}
</style>
