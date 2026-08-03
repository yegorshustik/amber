<script setup lang="ts">
import { defineAsyncComponent, computed } from 'vue';
import type { WxIconName } from './types';

const props = defineProps<{
    name: WxIconName;
}>();

const modules = import.meta.glob('./assets/*.svg', {
    query: '?component',
    import: 'default'
});

const iconComponent = computed(() => {
    const path = `./assets/${props.name}.svg`;
    const loader = modules[path];

    if (!loader) {
        console.warn(`Icon "${props.name}" not found in assets/`);
        return null;
    }

    return defineAsyncComponent(loader as any);
});

</script>

<template>
    <component :is="iconComponent" v-if="iconComponent" class="svg-content wx-icon" />
</template>

<style scoped lang="scss"></style>
