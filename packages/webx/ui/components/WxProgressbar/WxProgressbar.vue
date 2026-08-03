<script setup lang="ts">

import { ref, watch } from 'vue';
import type { WxProgressbarProps } from './types';

const props = withDefaults(defineProps<WxProgressbarProps>(), {});

const progress = ref(props.modelValue);

watch(() => props.modelValue, (value) => {
    progress.value = value;
})

</script>

<template>
    <div class="wx-progress" v-if="progress > 0">
        <div class="wx-progress__bar" :style="{ width: `${progress}%` }"></div>
    </div>
</template>

<style scoped lang="scss">
.wx-progress {
    height: 6px;
    background-color: var(--wx-light-gray);
    border-radius: 4px;
    overflow: hidden;

    &__bar {
        height: 100%;
        border-radius: 2px;
        background-color: var(--wx-primary);
    }
}
</style>
