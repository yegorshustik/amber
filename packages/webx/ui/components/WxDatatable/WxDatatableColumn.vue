 <script setup lang="ts">

 import { inject, onMounted, onUnmounted } from 'vue';
 import type { WxDatatableColumn, WxDatatableContext } from './types';

const props = withDefaults(defineProps<WxDatatableColumn>(), {});

const context = inject<WxDatatableContext>('datatableContext');

onMounted(() => {
    context.registerColumn({ ...props });
});

onUnmounted(() => {
    context.unregisterColumn({ ...props });
});

</script>

<template>
    <div class="wx-datatable__column" :data-title="props.title">
        <slot />
    </div>
</template>

<style scoped lang="scss">

</style>
